<?php 
session_start();

require_once "./controllers/pdoConnexion.php";
require_once "./controllers/Reservation.php";
require_once "./models/head.php";
require_once "./models/header.php";

$reservation = new Reservation();
echo '<pre>';
$reservation->historique_reservation($pdo);
