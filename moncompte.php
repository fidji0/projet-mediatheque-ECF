<?php 
session_start();

$page = 5;
require_once "./controllers/pdoConnexion.php";
require_once "./controllers/Reservation.php";
require_once "./models/head.php";
require_once "./models/header.php";

$reservation = new Reservation();

$reservation->historique_reservation($pdo);


require_once "./models/footer.php";
