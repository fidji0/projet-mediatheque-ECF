<?php
session_start();
require_once "./controllers/pdoConnexion.php";

// On verifie que l'utilisateur est bien connecté
if(!isset($_SESSION['email'])){
    header('Location: ./connect.php');
}else{
$title = "Vous êtes bien connecté";
$descriptionPage = "Bienvenue sur le site de la médiathèque de ..., nous sommes heureux de votre présence.";


require_once "./models/head.php";
require_once "./models/header.php";
?>
<main>
<div class="flexboxIndex">
    <div>
        
    </div>
    <div class="presentation">
        <h2>Dashboard employer</h2>
<a href="www.mediatheque.av-developpeur.fr/inscription.php?email=$_POST['email']"></a>
    </div>
</div>






</main>
















<?php
var_dump($_SESSION);
require_once "./models/footer.php";

}