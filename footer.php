<?php

include 'init.php';
?>
<head>
    <link rel="stylesheet" href="css/footer.css">
</head>
<div class="footer-basic">
    <footer>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="index.php"><?php echo $translations['Accueil'] ?? 'Home'; ?></a></li>
            <li class="list-inline-item"><a href="#"><?php echo $translations['Services'] ?? 'Services'; ?></a></li>
            <li class="list-inline-item"><a href="#"><?php echo $translations['About'] ?? 'About'; ?></a></li>
            <li class="list-inline-item"><a href="#"><?php echo $translations['Terms'] ?? 'Terms'; ?></a></li>
            <li class="list-inline-item"><a href="#"><?php echo $translations['Privacy Policy'] ?? 'Privacy Policy'; ?></a></li>
        </ul>
        <p class="copyright"><?php echo $translations['Footer'] ?? 'PCS © 2024'; ?></p>
    </footer>
</div>
