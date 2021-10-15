<?php
session_start();
if($_GET['disconnected'] == 1){
    session_unset();
}

require_once "./controllers/pdoConnexion.php";
$page = 1;
$title = "Bienvenue sur le site de la médiathèque de ...";
$descriptionPage = "Bienvenue sur le site de la médiathèque de ..., nous sommes heureux de votre présence.";


require_once "./models/head.php";
require_once "./models/header.php";

?>
<main>
<div class="flexboxIndex">
<div class="presentation">
        <h2>Bienvenue sur le site de la médiathèque</h2>

        <p>
            
        </p></div>

    <div>
        <img src="./vues/Img/books-985954_960_720.jpg" alt="">
    </div>
    
</div>






</main>
















<?php

require_once "./models/footer.php";
