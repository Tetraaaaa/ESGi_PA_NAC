<?php
include 'include/db.php';

if (isset($_GET["search"]) && !empty($_GET["search"])) {
    $q = "SELECT u.*, s.name AS status_name
          FROM USER u
          LEFT JOIN STATUS s ON u.status = s.id
          WHERE u.email LIKE :email";
    $stmt = $bdd->prepare($q);
    $stmt->execute([':email' => '%' . $_GET["search"] . '%']);
    $list_user = $stmt->fetchAll();
} else {
    $q = "SELECT u.*, s.name AS status_name
    FROM USER u
    LEFT JOIN STATUS s ON u.status = s.id;
    ";
    $stmt = $bdd->query($q);
    $list_user = $stmt->fetchAll();
}

foreach ($list_user as $user) {
    echo '<tr>
                    <td>#' . htmlspecialchars($user['id']) . '</td>
                    <td>' . htmlspecialchars($user['nom']) . '</td>
                    <td>' . htmlspecialchars($user['prenom']) . '</td>
                    <td>' . htmlspecialchars($user['email']) . '</td>
                    <td>' . htmlspecialchars($user['status_name']) . '</td>
                    <td><button type="button" class="btn btn-primary" onclick="window.location.href=\'edit_user.php?user_id=' . $user['id'] . '\'">Edit</button></td>
                    <td><button type="button" class="btn btn-danger" onclick="del_user(' . htmlspecialchars($user['id']) . ')">Delete</button></td>
                </tr>';
}
?> 
