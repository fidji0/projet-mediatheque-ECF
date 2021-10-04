<?php
try{
$dsn = 'mysql:host=localhost;dbname=mediatheque';
$username = 'root';
$password = '';

$pdo = new PDO($dsn, $username, $password);

}catch(PDOException $e){
    try{
        $dsni = 'mysql:host=localhost';
        $pdp = new PDO($dsni, $username, $password);
        $requete = "CREATE DATABASE IF NOT EXISTS `mediatheque` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
        
        $pdp->prepare($requete)->execute();
        echo "base de donée créée";
        }
        catch (PDOException $e){
            echo $e;
        }
     
       
        $pdo = new PDO($dsn, $username, $password);
        $usercreate='CREATE TABLE habitant
        (
            id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
            firstname VARCHAR(255) NOT NULL,
            surname VARCHAR(255) NOT NULL,
            adress VARCHAR(255) NOT NULL,
            zipcode INTEGER NOT NULL,
            city VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            date_of_birth DATE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(255) NOT NULL DEFAULT "habitant",
            validity BOOLEAN DEFAULT 0,
            verify_email BOOLEAN DEFAULT 0
        )';
        $pdo->prepare($usercreate)->execute();
        echo '<br>table utilisateur creés';

        $bookcreate='CREATE TABLE book
        (
            id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            link_img VARCHAR(255) NULL,
            descriptions VARCHAR(255) NOT NULL,
            publication_date DATE NULL,
            auteur VARCHAR(255) NOT NULL,
            dipo VARCHAR(255) NOT NULL DEFAULT "disponible",
            genre VARCHAR(255) NOT NULL

                    )';
        $pdo->prepare($bookcreate)->execute();
        echo '<br> table livre crée';

        $reservation='CREATE TABLE reservation
        (
            id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
            reader INTEGER NOT NULL,
            book INTEGER NOT NULL,
            reservation DATE NOT NULL,
            recuperation DATE NULL,
            bookreturn DATE NULL
                    )';

        $pdo->prepare($reservation)->execute();
        echo '<br>table reservation crée';
 }
