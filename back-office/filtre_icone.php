<?php
session_start();
include 'include/db.php';

// Check if the user is an admin
if (!isset($_SESSION['status']) || $_SESSION['status'] != 0) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $id_filtre = $_POST['id_filtre'];
    $largeur = $_POST['largeur'];
    $hauteur = $_POST['hauteur'];
    $image = $_FILES['image'];

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($image['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../image/';
        $image_path = $upload_dir . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $image_path);

        if ($id > 0) {
            $stmt = $bdd->prepare("UPDATE ICONE_FILTRE SET nom = ?, id_FILTRE = ?, largeur = ?, hauteur = ?, emplacement = ? WHERE id = ?");
            $stmt->execute([$name, $id_filtre, $largeur, $hauteur, $image_path, $id]);
        } else {
            $stmt = $bdd->prepare("INSERT INTO ICONE_FILTRE (nom, id_FILTRE, largeur, hauteur, emplacement) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $id_filtre, $largeur, $hauteur, $image_path]);
        }
    }
}

$icons = $bdd->query("SELECT * FROM ICONE_FILTRE")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Filtre Icones</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
</head>
<?php include 'header.php'; ?>    

<body class="container text-center">
    <h1>Gestion des Icônes</h1>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="">
        <div class="form-group">
            <label for="name">Nom de l'icône</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="id_filtre">ID du Filtre</label>
            <input type="number" name="id_filtre" id="id_filtre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="largeur">Largeur</label>
            <input type="number" name="largeur" id="largeur" class="form-control" placeholder="50" required>
        </div>
        <div class="form-group">
            <label for="hauteur">Hauteur</label>
            <input type="number" name="hauteur" id="hauteur" class="form-control" placeholder="50" required>
        </div>
        <div class="form-group">
            <label for="image">Image de l'icône</label>
            <input type="file" name="image" id="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter/Modifier Icône</button>
    </form>

    <h2>Icônes existantes</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>ID du Filtre</th>
                <th>Largeur</th>
                <th>Hauteur</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($icons as $icon): ?>
                <tr>
                    <td><?php echo htmlspecialchars($icon['nom']); ?></td>
                    <td><?php echo htmlspecialchars($icon['id_FILTRE']); ?></td>
                    <td><?php echo htmlspecialchars($icon['largeur']); ?></td>
                    <td><?php echo htmlspecialchars($icon['hauteur']); ?></td>
                    <td><?php echo htmlspecialchars(basename($icon['emplacement'])); ?></td>
                    <td>
                        <a href="delete_icone.php?id=<?php echo $icon['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
