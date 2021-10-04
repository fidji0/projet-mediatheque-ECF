<?php
session_start();
require_once "./controllers/pdoConnexion.php";
require_once "./controllers/functionSearch.php";
require_once "./controllers/functionAuthenticate.php";
require_once "./controllers/Book.php";

// On verifie que l'utilisateur est bien connecté
if(!isset($_SESSION['email'])){
    header('Location: ./connect.php');
}else{
    if($_SESSION['role'] !== 'employer'){
        header('Location: ./index.php');
    }
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
        <p>Bonjour <?=$_SESSION['firstname'] ?></p>
    </div>
<?=    verify_validity($pdo);
?>    <div>
<?= require_once "./models/add_books_forms.php";
?>
    </div>
</div>






</main>
















<?php
var_dump($_POST);
var_dump($_FILES);

require_once "./models/footer.php";

}