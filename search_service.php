<?php
require_once 'include/connection_db.php';

if (isset($_GET['query']) || isset($_GET['department'])) {
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $department = isset($_GET['department']) ? $_GET['department'] : '';

    $sql = "SELECT S.id, S.type FROM SERVICE S
            JOIN INTERVENTION I ON S.id = I.id_service
            WHERE S.type LIKE :searchTerm";

    if (!empty($department)) {
        $sql .= " AND I.id_departement = :department";
    }

    $stmt = $bdd->prepare($sql);
    $searchTerm = "%" . $query . "%";
    $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);

    if (!empty($department)) {
        $stmt->bindParam(':department', $department, PDO::PARAM_INT);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = '<div class="list-group">';
    if (count($results) > 0) {
        foreach ($results as $row) {
            $output .= '<button type="button" class="list-group-item list-group-item-action" data-id="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['type']) . '</button>';
        }
    } else {
        $output .= '<button type="button" class="list-group-item list-group-item-action disabled">Aucun service trouvé</button>';
    }
    $output .= '</div>';

    echo $output;
}
?>
