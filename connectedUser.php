<?php
session_start();
require_once "./controllers/pdoConnexion.php";
require_once "./controllers/functionAuthenticate.php";
require_once "./controllers/search.php";
require_once "./controllers/Reservation.php";
$search = new Search();
$reserved = new Reservation();



valid_email($pdo);
// On verifie que l'utilisateur est bien connecté
if(!isset($_SESSION['email'])){
    header('Location: ./connect.php');
    echo '<script> NotConnected() </script>';
}else{
$page = 3;
$title = "Cherchez et réservez vos livres";
$descriptionPage = "Vous pouvez réserver vos livre ici";


require_once "./models/head.php";
require_once "./models/header.php";
?>
<main class="mainConnected">
<?php
    require_once "./models/search_bar.php";
?>

</div>
        <div class="container-book">
        <?php
            
            $reserved->reserved_book($pdo);
            $search->detail_book($pdo); 
        ?>
        </div>
        


</main>


<?php

require_once "./models/footer.php";

}