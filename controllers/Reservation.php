<?php

class Reservation{
        private $book = 'book';
        private $habitant = 'habitant';
        private $reservation = 'reservation';

    public function reserved_book($pdo){

        // je verifie l'existence de $get dispo
        if (!empty($_GET['dispo'])){
            
            $id = $_GET['dispo'];

            // je récupère la disponibilité de livre en BDD
            $verif = "SELECT dispo FROM book WHERE id = ?";
            $resultVerif = $pdo->prepare($verif);
            $resultVerif->execute(array($id));
            $p = $resultVerif->fetchAll();
           
            // je vérifie si le livre est disponible
            if($p[0]['dispo'] === 'disponible'){

                // je créé une requete sql pour modifier la dispo du livre selectionne et ajouter id de la réservation
                $request = "UPDATE book SET dispo = 'reserved' , reserveid = ? WHERE id = ?";

                // requete pour créer la réservation avec les information du lecteur ,du livre et de la date du jour
                $request2 = "INSERT INTO reservation (reader, book, reservation) VALUES (?, ? , DATE(NOW())) ";

                // requete pour récupérer l'identifiant de commande a injecter dans le livre
                $request3 = "SELECT id FROM reservation WHERE reader = ? AND book = ? AND recuperation IS NULL AND statut = 'reserved' LIMIT 1";
            try{
                //lancement de la création de la réservation
                $reserved = $pdo->prepare($request2);
                $reserved->execute(array($_SESSION['id'], $_GET['dispo']));
                
                // récupération de l'id de la réservation
                $d = $pdo->prepare($request3);
                $d->execute(array($_SESSION['id'], $_GET['dispo']));
                $rest = $d->fetchAll();
                $reserveid = $rest[0]['id'];
                

                // ajout de l'id de réservation et changement dispo du livre
                $b = $pdo->prepare($request);
                
                $test = $b->execute(array($reserveid , $id));
                
                echo '<div class="alert alert-success" role="alert">
                Le livre à bien été réservé
                </div>';
            }catch (PDOException $e){
                echo $e;
            }
            }
        }
    }
    // permet de valider la récupération d'un livre 
    public function recuperation_book($pdo){
        
        try{
            // récupère la liste des livres réservé depuis moins de 3 jours
            $request = "SELECT B.title , R.id ,  R.book FROM book AS B INNER JOIN reservation as R WHERE B.dispo = 'reserved' AND B.id = R.book AND R.recuperation IS NULL";
            $r = $pdo->prepare($request);
            $r->execute();
            $result = $r->fetchAll(PDO::FETCH_ASSOC);
            $count = count($result);
           
            // pour chaque retour
            for($i = 0 ; $i < $count ; $i++){
                        
                // verifie que les information entrées en get correspondent a une des réservations en cours            
                if(!empty($_GET['book']) && !empty($_GET['reservid'])){
                    $book = $_GET['book'];
                    $reservid = $_GET['reservid'];

                    // demande le statut de réservation du livre
                    $request3 = "SELECT dispo FROM book WHERE id = ?";
                    $r = $pdo->prepare($request3);
                    $r->execute(array($book));
                    $result3 = $r->fetchAll();

                    // vérifie le statut de disponibilité du livre
                    if($result3[$i]['dispo'] === 'reserved' ){

                        // modifie le statut du livre a emprunter et ajoute la date de récupération et modifie le statut de la réservation à valider
                        $request4 = "UPDATE book SET dispo = 'emprunter' WHERE id = ?;
                        UPDATE reservation SET recuperation = DATE(NOW()), statut = 'emprunter' WHERE id = ? ";
                        try{
                        $c=  $pdo->prepare($request4);
                        $c->execute(array($book , $reservid));
                        }catch(PDOException $e){
                            echo $e;
                        }
                    }
                }
            }
            $request = "SELECT B.title, B.auteur , H.surname, H.firstname ,R.id ,  R.book FROM book AS B 
            INNER JOIN reservation as R INNER JOIN habitant AS H
            WHERE B.dispo = 'reserved' AND B.id = R.book AND R.recuperation  IS NULL AND H.id = R.reader";


            if(!empty($_GET['search_valid']) && $_GET['search_valid'] === '1' && (!empty($_GET['title']) || !empty($_GET['auteur'])
            || !empty($_GET['surname']) || !empty($_GET['firstname']))){
                $request .= " AND (B.title LIKE ? AND B.auteur LIKE ? AND H.surname LIKE ? AND H.firstname LIKE ?)";
                $r = $pdo->prepare($request);
               
                $r->execute(array('%'.$_GET['title'].'%' ,'%'.$_GET['auteur'].'%','%'.$_GET['surname'].'%','%'.$_GET['firstname'].'%'));
               
            }else{
                $r = $pdo->prepare($request);
                $r->execute();
            }
            
            $result = $r->fetchAll(PDO::FETCH_ASSOC);
            $count = count($result);
            
            echo '<table class="table_valid_book">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Nom emprumteur</th>
                    <th>Prenom emprunteur</th>
                    <th>Valider le retrait</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <form action="" method="get">
                <td>
                    <input class="form-control" type="text" name="title"  placeholder="Titre">
                </td>
                <td>
                    <input class="form-control" type="text" name="auteur"  placeholder="Auteur">
                </td>
                <td>
                    <input class="form-control" type="text" name="surname" placeholder="Nom">
                </td>
                <td>
                    <input class="form-control" type="text" name="firstname" placeholder="Prenom">
                </td>
                <td>
                    <button class="btn btn-primary" type="submit" name="search_valid" value="1">Rechercher</button>
                </td>
                </form>
            </tr>
                ';
            for($i = 0 ; $i < $count ; $i++){
               
               
                echo '<tr>
                <div class="m-1 ">
                <form class="container-validation-recuperation" action="" method="get">';
                foreach($result[$i] as $key => $return){
                    if(!empty($result)){
                        switch ($key) {
                            case 'title' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).'</h5></div></td>';
                                break;
                            case 'auteur' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).'</h5></div></td>';
                                break;
                            case 'surname' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).'</h5></div></td>';
                                break;
                            case 'firstname' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).'</h5></div></td>';
                                break;
                            case 'book':
                                echo '<td style="display : none"><input class="" type="text" style="display : none" name="book" value="'.$return.'"></td>';
                                break;
                            case 'id':
                                echo '<td><div class="pb-2 align-center"> <button class="btn btn-primary p-2" type="submit" name="reservid" value="'.$return.'" >Je valide la récupération du livre</button>
                                </div></td>';
                                break;
                        }
                    }
                }
                echo '</form></div></tr>';
            } 
            echo '</tbody></table>';

        }catch(PDOException $e){
            var_dump($e);
        }
    }
    // enregistre un retour et affiche les livres a retournés
    public function search_return_book($pdo){
        try{
        $request = "SELECT H.surname , H.firstname , H.email ,  B.title , B.auteur , R.id , DATEDIFF ( DATE(NOW()) , recuperation) AS d
            FROM habitant AS H INNER JOIN book AS B INNER JOIN reservation AS R 
            WHERE R.reader = H.id AND R.book = B.id AND R.statut = 'emprunter' AND B.dispo = 'emprunter'";
        
        if(!empty($_GET['search_return']) && $_GET['search_return'] === '1' && (!empty($_GET['title']) || !empty($_GET['auteur'])
        || !empty($_GET['surname']) || !empty($_GET['firstname']) || !empty($_GET['email']))){
            $request .= " AND (B.title LIKE ? AND B.auteur LIKE ? AND H.surname LIKE ? AND H.firstname LIKE ? AND H.email LIKE ?) ORDER BY R.recuperation";
            $r = $pdo->prepare($request);
           
            $r->execute(array('%'.$_GET['title'].'%' ,'%'.$_GET['auteur'].'%','%'.$_GET['surname'].'%','%'.$_GET['firstname'].'%','%'.$_GET['email'].'%'));
           
        }else{
            $request .= "ORDER BY R.recuperation";
            $r = $pdo->prepare($request);
            $r->execute();
        }
            $result = $r->fetchAll();
            $count = count($result);
            

            echo '<table class="table_valid_book">
            <thead>
                <tr>
                    <th>Nom emprumteur</th>
                    <th>Prenom emprunteur</th>
                    <th>Email</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Valider le retour</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <form action="" method="get">
                <td>
                    <input class="form-control" type="text" name="surname" placeholder="Nom">
                </td>
                <td>
                    <input class="form-control" type="text" name="firstname" placeholder="Prenom">
                </td>
                <td>
                    <input class="form-control" type="text" name="email"  placeholder="email">
                </td>
                <td>
                    <input class="form-control" type="text" name="title"  placeholder="Titre">
                </td>
                <td>
                    <input class="form-control" type="text" name="auteur"  placeholder="Auteur">
                </td>           
                <td>
                    <button class="btn btn-primary" type="submit" name="search_return" value="1">Rechercher</button>
                </td>
                </form>
            </tr>';
            for($i = 0 ; $i < $count ; $i++){
                
                $d = $result[$i]['d'];
            

           
                echo '
                <div class="m-1 ">
                <form class="container-validation-recuperation"action="" method="get"> 
                ';
                if($d >= 21){
                    echo '<tr class="tr-alert">';
                }else{
                    echo '<tr>';
                }
                
                
                foreach($result[$i] as $key => $return){
                    if(!empty($result)){
                        switch ($key) {
                            case 'title' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).' </h5></div></td>';
                                break;
                            case 'surname' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).' </h5></div></td>';
                            break;
                            case 'auteur' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).' </h5></div></td>';
                            break;
                            case 'firstname' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).' </h5></div></td>';
                            break;
                            case 'surname' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).' </h5></div></td>';
                            break;
                            case 'email' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).' </h5></div></td>';
                            break;
                            case 'id':
                                echo '<td><div class="pb-2 align-center"> <button class="btn btn-primary p-2" type="submit" name="returnid" value="'.$return.'" >Je valide le retour du livre</button>
                                </div></td>';
                                break;
                        }
                    }
                }
                echo '</form></div></tr>';
            }
            echo '</tbody></table>';
        }
        catch (PDOException $e){
            echo $e;
        }
        
    }
    public function valid_return_book($pdo){
        if (!empty($_GET['returnid'])){
            $request = "UPDATE book SET dispo = 'disponible' WHERE dispo = 'emprunter' AND reserveid = ? ;
            UPDATE reservation SET bookreturn = DATE(NOW()) , statut = 'terminer' WHERE id = ? AND statut = 'emprunter' AND bookreturn IS NULL";

            $r = $pdo->prepare($request);
            $r->execute(array($_GET['returnid'], $_GET['returnid']));
        }
    }

    public function annulation_reservation($pdo){
        
        // Récupération des livres reservés avec une date supérieur a 3 jour
        $request = "SELECT id , book , DATEDIFF ( DATE(NOW()) , reservation ) AS diff FROM reservation WHERE DATEDIFF ( DATE(NOW()) , reservation ) > 3 AND statut = 'reserved'";
        $r = $pdo->prepare($request);
        $r->execute();

        // retourne toutes les réservation de plus de 3 jours non récupérer
        $result = $r->fetchAll(PDO::FETCH_ASSOC);
        $count = count($result);

        // boucle sur tous les objets retournés
        for($i = 0 ; $i < $count ; $i++){
            foreach($result[$i] as $key => $return){
                if(!empty($result)){
                    switch ($key){
                        case 'id':
                            $reserveid = $return;
                            break;
                        case 'diff':
                            $diff = $return;
                            break;
                        case 'book':
                            $book = $return;
                            break;
                    }
                
                }
                // Modification de statut en annuler dans la réservation et remise du livre en disponible
                
                $request1 = "UPDATE reservation SET statut = 'annuler' WHERE id = ?;
                UPDATE book SET dispo = 'disponible' WHERE id = ?";
                $r = $pdo->prepare($request1);
                $r->execute(array($reserveid, $book));
                
            }
        
        }

    }
    private function echo($value){
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
    }
    
}
