<?php
$code = isset($_GET['code']) ? intval($_GET['code']) : 500;
switch ($code) {
case 400:
$message = "Bad Request";
break;
case 401:
$message = "Unauthorized";
break;
case 403:
$message = "Forbidden";
break;
case 404:
$message = "Page not found";
break;
case 500:
$message = "Internal Server Error";
break;
default:
$message = "Unknown Error";
}
?>

  <head>
    <meta charset="utf-8">
    <title>River Ride - Erreur <?php echo $code; ?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/stylesheet_error.css">
    <link href="picture/logo.png" rel="icon" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="Js/script.js" async></script>
    <style>
      /* Ajoutez ici votre propre CSS pour personnaliser la page */
    </style>
  </head>

  <body>
  <header>
    <?php include("header.php"); ?>
  </header>

  <div class="container">
    <div class="row">
      <div class="col-12 text-center">
        <h1 class="mx-auto"><?php echo $code; ?> - <?php echo $message; ?></h1>
      </div>
    </div>
  </div>

  <footer>
    <!-- Ajoutez ici le code HTML pour le pied de page -->
  </footer>
  </body>

  </html>




