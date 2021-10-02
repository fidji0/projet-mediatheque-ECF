<?php
session_start();

require_once "./controllers/pdoConnexion.php";
require_once "./controllers/functionAuthenticate.php";
valid_email($pdo);




require_once "./models/head.php";
require_once "./models/header.php";

require_once "./models/add_user_form.php";























require_once "./models/footer.php";