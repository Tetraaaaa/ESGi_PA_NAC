<?php
$title="Back-Office Edit ";
include 'include/head.php';



if(!isset($_GET['user_id']) || empty($_GET['user_id'])){
    header("location:user.php");
    exit();
}


echo '<body class="container text-center">';
include 'include/header.php';
include ("include/db.php");
$q="SELECT * FROM utilisateur WHERE u_id=".$_GET['user_id']."";
    $req= $bdd -> query($q);
    $user= $req->fetch();
    echo '<h1>ID utilisateur : #'.$user['u_id'].'</h1>';
    if(isset($_GET['msg']) && !empty($_GET['msg'])){
        if(!isset($_GET['color']) || empty($_GET['color'])){
                $_GET['color']='danger';
        }
        echo '<div class="alert alert-'.$_GET['color'].'">'.htmlspecialchars($_GET['msg'])."</div>";
    }
    $bio=$user['bio'];
        if (strlen($bio)>=30){
            $bio=$user['u_id'].substr($bio,0,30).'...';
        }
    echo '
    <br>
    <form method="post" action="verif_edit/pseudo.php?user_id='.$user['u_id'].'" >
        <div class="input-group mb-3 justify-content-md-center">
            <span class="input-group-text btn btn-primary">Pseudo</span>
            <span class="input-group-text btn btn-info">'.htmlspecialchars($user['pseudo']).'</span>
            <input type="text" class="form-control" aria-describedby="button-addon2" name="pseudo">
            <button class="btn btn-outline-secondary btn btn-warning" type="submit" id="button-addon2">Modifier</button>
        </div>
    </form>
    <br>
    <form method="post" action="verif_edit/nom.php?user_id='.$user['u_id'].'" class="container text-center">
        <div class="input-group mb-3 justify-content-md-center">
            <span class="input-group-text btn btn-primary">Nom</span>
            <span class="input-group-text btn btn-info">'.htmlspecialchars($user['nom']).'</span>
            <input type="text" class="form-control" aria-describedby="button-addon2" name="nom">
            <button class="btn btn-outline-secondary btn btn-warning" type="submit" id="button-addon2">Modifier</button>
        </div>
    </form>
    <br>
    <form method="post" action="verif_edit/prenom.php?user_id='.$user['u_id'].'" class="container text-center">
        <div class="input-group mb-3 justify-content-md-center">
            <span class="input-group-text btn btn-primary">Prénom</span>
            <span class="input-group-text btn btn-info">'.htmlspecialchars($user['prenom']).'</span>
            <input type="text" class="form-control" aria-describedby="button-addon2" name="prenom">
            <button class="btn btn-outline-secondary btn btn-warning" type="submit" id="button-addon2">Modifier</button>
        </div>
    </form>
    <br>
    <form method="post" action="verif_edit/email.php?user_id='.$user['u_id'].'" class="container text-center">
        <div class="input-group mb-3 justify-content-md-center">
            <span class="input-group-text btn btn-primary">Email</span>
            <span class="input-group-text btn btn-info">'.htmlspecialchars($user['email']).'</span>
            <input type="email" class="form-control" aria-describedby="button-addon2" name="email">
            <button class="btn btn-outline-secondary btn btn-warning" type="submit" id="button-addon2">Modifier</button>
        </div>
    </form>
    <br>
    <form method="post" action="verif_edit/bio.php?user_id='.$user['u_id'].'" class="container text-center">
        <div class="input-group mb-3 justify-content-md-center">
            <span class="input-group-text btn btn-primary">Bio</span>
            <span class="input-group-text btn btn-info">'.htmlspecialchars($bio).'</span>
            <input type="text" class="form-control" aria-describedby="button-addon2" name="bio">
            <button class="btn btn-outline-secondary btn btn-warning" type="submit" id="button-addon2">Modifier</button>
        </div>
    </form>
    <br>
    <form method="post" action="verif_edit/photo.php?user_id='.$user['u_id'].'" class="container text-center" enctype="multipart/form-data">
        <div class="input-group mb-3 justify-content-md-center">
            <span class="input-group-text btn btn-primary">Photo</span>
            <img src="'.htmlspecialchars($user['photo']).'">
            <input type="file" name="photo" accept="image/gif, image/jpeg, image/png" name="image">
            <button class="btn btn-outline-secondary btn btn-warning" type="submit" id="button-addon2">Modifier</button>
        </div>
    </form>

    ';



echo '</body>';

