<?php
session_start();
require_once "./controllers/pdoConnexion.php";
require_once "./controllers/functionAuthenticate.php";
require_once "./controllers/Book.php";
require_once "./controllers/Reservation.php";

$title = "Tableau de bord employé";
$descriptionPage = "Bienvenue sur le site de la médiathèque de ..., nous sommes heureux de votre présence.";
$page = 2;

require_once "./models/head.php";
$reserved = new Reservation();


// On verifie que l'utilisateur est bien connecté
if(!isset($_SESSION['email'])){
        //header('Location: ./connect.php');
        echo '<script> NotConnected() </script>';
}else{
    if($_SESSION['role'] !== 'employer'){
        //header('Location: ./index.php');
        echo '<script> RedirectionUser() </script>';
    }
    



require_once "./models/header.php";
$reserved->annulation_reservation($pdo);
    $reserved->valid_return_book($pdo);
?>
<main>
<?php



    if(!empty($_GET['retour']) && $_GET['retour'] == '1'){
        $reserved->search_return_book($pdo);
    }elseif(!empty($_GET['recuperation']) && $_GET['recuperation'] == '1'){
        $reserved->recuperation_book($pdo);
    }elseif(!empty($_GET['attente']) && $_GET['attente'] == 1){
        verify_validity($pdo);
    }elseif(!empty($_GET['addBook']) && $_GET['addBook'] == 1){
        require_once "./models/add_books_forms.php";
    }

?>
<div class="presentation">
        
        
        
        <div><h2>Tableau de bord</h2></div>
        
    </div>
<div class="welcome">
    <div><p>Bonjour <?php echo $_SESSION['firstname'] ?></p></div>
</div>

<div class="flexboxDashboard">
    <div>
        <a href="?recuperation=1"><button class="button">Valider une récupération</button></a>
    </div>
    <div>
        <a href="?retour=1"><button class="button">Valider un retour</button></a>
    </div>
    <div>
        <a href="?retour=1"><button class="btn-retard"><?php $reserved->en_retard($pdo) ?></button></a>
    </div>
</div>
<div class="flexboxDashboard">
   
    <div>
        <a href="?addBook=1"><button class="button">Ajouter un livre</button></a>
    </div>
    <div>
        <a href="?attente=1"><button class="button"><?php en_attente($pdo) ?> en attente de validation</button></a>
    </div>
</div>



    </div>







</main>
















<?php


require_once "./models/footer.php";
}
?>