<?php
session_start();
require_once "./controllers/pdoConnexion.php";
require_once "./controllers/functionAuthenticate.php";
require_once "./controllers/search.php";

valid_email($pdo);
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
        <img src="./vues/Img/bibliotheque.jpg" alt="">
    </div>
    <div class="presentation">
        <h2>Bienvenue sur le site de la bibliothèque</h2>
        <form action="#" method="get" >
      
        <div class="form-group pt-2">
            <label for="contactForm1">Votre email</label>
            <input type="text" class="form-control" id="contactForm1" name="email" required>
        </div>
        <?php
            $search = new Search('*', '');
            $search->select_genre($pdo); 
        ?>
        
            
    </form>

    </div>
</div>






</main>
















<?php
var_dump($_GET);
require_once "./models/footer.php";

}