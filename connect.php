<?php
session_start();

require_once "./controllers/functionAuthenticate.php";
require_once "./controllers/pdoConnexion.php";


$page = 6;

require_once "./models/head.php";
require_once "./models/header.php";
valid_email($pdo);
if(isset($_SESSION['email'])){
    //header('Location: ./connect.php');
    echo '<script> RedirectionUser() </script>';
}

echo '<div class="flexbox-forms">';
require_once "./models/connexion.php";
echo '</div>';























require_once "./models/footer.php";