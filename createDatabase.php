<?php

require_once "./controllers/pdoConnexion.php";
try{

        $usercreate='CREATE TABLE IF NOT EXISTS habitant
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
       

        $bookcreate='CREATE TABLE IF NOT EXISTS book
        (
            id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            link_img VARCHAR(255) NULL,
            descriptions TEXT(60000) NOT NULL,
            publication_date DATE NULL,
            auteur VARCHAR(255) NOT NULL,
            dispo VARCHAR(255) NOT NULL DEFAULT "disponible",
            genre VARCHAR(255) NOT NULL,
            reserveid INTEGER NULL

                    )';
        $pdo->prepare($bookcreate)->execute();
        

        $reservation='CREATE TABLE IF NOT EXISTS reservation
        (
            id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
            reader INTEGER NOT NULL,
            book INTEGER NOT NULL,
            reservation DATE NOT NULL,
            recuperation DATE NULL,
            bookreturn DATE NULL,
            statut VARCHAR(255) DEFAULT "reserved",
            FOREIGN KEY (reader) REFERENCES habitant(id),
            FOREIGN KEY (book) REFERENCES book(id)
                    )';

        $pdo->prepare($reservation)->execute();
        

        $password = password_hash('Test1234',PASSWORD_BCRYPT);
        $admin = "INSERT INTO habitant (`id`, `firstname`, `surname`, `adress`, `zipcode`, `city`, `email`, `date_of_birth`, `password`, `role`, `validity`, `verify_email`) VALUES 
        (1, 'admin' , 'admin' , 'admin', 00000, 'admin' ,  'admin@admin.fr' ,DATE(NOW()),?,'employer',1,1)";

        $r = $pdo->prepare($admin);
        $r->execute([$password]);
    }catch(PDOException $e){
        echo 'Une erreur est survenue le webmaster à été avisé ';
             mail('contact@av.developpeur.fr', ' erreur requette sql', $e);
    }catch (Exception $e){
        echo 'Une erreur est survenue le webmaster à été avisé';
         mail('contact@av.developpeur.fr', ' erreur php', $e);
    }
