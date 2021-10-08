<?php
session_start();
require_once "./controllers/pdoConnexion.php";
require_once "./controllers/functionSearch.php";
require_once "./controllers/functionAuthenticate.php";
require_once "./controllers/Book.php";
require_once "./controllers/Reservation.php";

$reserved = new Reservation();

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
<?=   
    $reserved->annulation_reservation($pdo);
    $reserved->valid_return_book($pdo);
    $reserved->recuperation_book($pdo);

    $reserved->search_return_book($pdo);
verify_validity($pdo);
?>   
<?= require_once "./models/add_books_forms.php";
?>
    </div>
</div>






</main>
















<?php


require_once "./models/footer.php";

}