<?php
session_start();

require_once "./controllers/pdoConnexion.php";
require_once "./controllers/functionAuthenticate.php";





require_once "./models/head.php";
require_once "./models/header.php";

echo '<div class="flexbox-forms">';
require_once "./models/add_user_form.php";
echo '</div>';






















require_once "./models/footer.php";