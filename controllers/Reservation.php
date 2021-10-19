<?php

class Reservation{
        private $book = 'book';
        private $habitant = 'habitant';
        private $reservation = 'reservation';

    public function reserved_book($pdo){
        if ($pdo){
        // je verifie l'existence de $get dispo
        if (!empty($_GET['dispo'])){
            
            $id = $_GET['dispo'];

            // je récupère la disponibilité de livre en BDD
            $verif = "SELECT dispo FROM book WHERE id = ?";
            $resultVerif = $pdo->prepare($verif);
            $resultVerif->execute(array($id));
            $p = $resultVerif->fetchAll(PDO::FETCH_ASSOC);
           
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
                $rest = $d->fetchAll(PDO::FETCH_ASSOC);
                $reserveid = $rest[0]['id'];
                

                // ajout de l'id de réservation et changement dispo du livre
                $b = $pdo->prepare($request);
                
                $test = $b->execute(array($reserveid , $id));
                
                
            }catch (PDOException $e){
                echo 'Une erreur est survenue le webmaster à été avisé';
                 mail('contact@av.developpeur.fr', ' erreur sql', $e);
            }catch (Exception $e){
                echo 'Une erreur est survenue le webmaster à été avisé';
                 mail('contact@av.developpeur.fr', ' erreur php', $e);
            }
            }
        }
    }}
    // permet de valider la récupération d'un livre 
    public function recuperation_book($pdo){
        if ($pdo){
        // creation de la pagination
        $request = "SELECT COUNT(R.id) AS ctn FROM book AS B 
        INNER JOIN reservation as R INNER JOIN habitant AS H
        WHERE B.dispo = 'reserved' AND B.id = R.book AND R.recuperation  IS NULL AND H.id = R.reader";

        if(!empty($_GET['search_valid']) && $_GET['search_valid'] === '1' && (!empty($_GET['title']) || !empty($_GET['auteur'])
            || !empty($_GET['surname']) || !empty($_GET['firstname']))){
                $request .= " AND B.title LIKE ? AND B.auteur LIKE ? AND H.surname LIKE ? AND H.firstname LIKE ?";
                $d = $pdo->prepare($request);
                 
              try{  $d->execute(array('%'.$_GET['title'].'%' ,'%'.$_GET['auteur'].'%','%'.$_GET['surname'].'%','%'.$_GET['firstname'].'%'));
              }catch (PDOException $e){
                echo 'Une erreur est survenue le webmaster à été avisé';
                mail('contact@av.developpeur.fr', ' erreur sql', $e);
              }catch (Exception $e){
                echo 'Une erreur est survenue le webmaster à été avisé';
                 mail('contact@av.developpeur.fr', ' erreur php', $e);
            }
        }else{
        $d = $pdo->prepare($request);
        $d->execute();
        }
        $counts = $d->fetchAll(PDO::FETCH_ASSOC);
        
        $nbr_element_par_page = 2;
        if (empty($_GET['page']) || is_numeric($_GET['page']) === false){
            $_GET['page']=1;
        }
        
        $debut = ($_GET['page']-1)*$nbr_element_par_page;
        $nombre_de_page = ceil($counts[0]['ctn']/$nbr_element_par_page);
        


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
                    $result3 = $r->fetchAll(PDO::FETCH_ASSOC);

                    // vérifie le statut de disponibilité du livre
                    if($result3[$i]['dispo'] === 'reserved' ){

                        // modifie le statut du livre a emprunter et ajoute la date de récupération et modifie le statut de la réservation à valider
                        $request4 = "UPDATE book SET dispo = 'emprunter' WHERE id = ?;
                        UPDATE reservation SET recuperation = DATE(NOW()), statut = 'emprunter' WHERE id = ? ";
                        try{
                        $c=  $pdo->prepare($request4);
                        $c->execute(array($book , $reservid));
                        }catch(PDOException $e){
                            echo 'Une erreur est survenue le webmaster à été avisé';
                            mail('contact@av.developpeur.fr', ' erreur requette sql', $e);
                        }catch (Exception $e){
                            echo 'Une erreur est survenue le webmaster à été avisé';
                             mail('contact@av.developpeur.fr', ' erreur php', $e);
                        }
                    }
                }
            }
            // On recupere les informations à afficher 
            $request = "SELECT B.title, B.auteur , H.surname, H.firstname ,R.id ,  R.book FROM book AS B 
            INNER JOIN reservation as R INNER JOIN habitant AS H
            WHERE B.dispo = 'reserved' AND B.id = R.book AND R.recuperation  IS NULL AND H.id = R.reader";


            if(!empty($_GET['search_valid']) && $_GET['search_valid'] === '1' && (!empty($_GET['title']) || !empty($_GET['auteur'])
            || !empty($_GET['surname']) || !empty($_GET['firstname']))){
                $request .= " AND (B.title LIKE ? AND B.auteur LIKE ? AND H.surname LIKE ? AND H.firstname LIKE ?) LIMIT $nbr_element_par_page OFFSET $debut";
                $r = $pdo->prepare($request);
                
                $r->execute(array('%'.$_GET['title'].'%' ,'%'.$_GET['auteur'].'%','%'.$_GET['surname'].'%','%'.$_GET['firstname'].'%'));
               
            }else{
                $request .= " LIMIT $nbr_element_par_page OFFSET $debut";
                $r = $pdo->prepare($request);
                $r->execute();
            }
            
            
            $result = $r->fetchAll(PDO::FETCH_ASSOC);
            $count = count($result);
            // création du tableau
            echo '<div class="scroll"><table class="table_valid_book">
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
                    <input type="text" style="display : none" name="recuperation" value="1">
                </td>
                <td>
                    <input class="form-control" type="text" name="firstname" placeholder="Prenom">';
                    if(!empty($_GET['page'])){
                        echo '<input class="p-2" type="text" style="display : none" name="page" value="'.$_GET['page'].'">';
                    }
                    echo '
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
                                echo '<input type="text" style="display : none" name="recuperation" value="1">';
                                if(!empty($_GET['search_valid']) && $_GET['search_valid'] === '1' && (!empty($_GET['title']) || !empty($_GET['auteur'])
                                || !empty($_GET['surname']) || !empty($_GET['firstname']))){
                                    echo '<input type="text" style="display : none" name="search_valid" value="'.$_GET['search_valid'].'">
                                    <input type="text" style="display : none" name="title" value="'.$_GET['title'].'">
                                    <input type="text" style="display : none" name="auteur" value="'.$_GET['auteur'].'">
                                    <input type="text" style="display : none" name="surname" value="'.$_GET['surname'].'">
                                    <input type="text" style="display : none" name="firstname" value="'.$_GET['firstname'].'">';
                                }
                                echo '<td><div class="pb-2 align-center"> <button class="btn btn-primary p-2" type="submit" name="reservid" value="'.$return.'" >Je valide la récupération du livre</button>
                                </div></td>';
                                break;
                        }
                    }
                }
                echo '</form></div></tr>';
            } 
            echo '</tbody></table></div>';

            
        if($counts[0]['ctn'] > 0){
            echo '<div class="mt-5 "  style="width : 100%;"> 
        <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link"
        ';
        if(!empty($_GET['search_valid']) && $_GET['search_valid'] === '1' && (!empty($_GET['title']) || !empty($_GET['auteur'])
        || !empty($_GET['surname']) || !empty($_GET['firstname']))){
            $search = '&search_valid='.$_GET['search_valid'];
            $title = '&title='.$_GET['title'];
            $auteur = '&auteur='.$_GET['auteur'];
            $surname = '&surname='.$_GET['surname'];
            $firstname = '&firstname='.$_GET['firstname'];
        }else{
            $search = '&search_valid=1';
            $title = '&title=';
            $auteur = '&auteur=';
            $surname = '&surname=';
            $firstname = '&firstname=';
            }

        // on redirige si le visiteur modifie manuelement la page dans l'url
        if ($_GET['page'] < 1 || $_GET['page'] > $nombre_de_page){
            header('Location: ./employerDashboard.php?page=1&recuperation=1'.$search.$title.$auteur.$surname.$firstname.'');
        }
        // on renvoit a la page 1 si appuis sur precedent a la page 1
        if ($_GET['page'] == 1){
            echo 'href="?page=1&recuperation=1'.$search.$title.$auteur.$surname.$firstname.'">Precedente</a></li>';
        }else{
            echo ' href="?page='.($_GET['page']-1).'&recuperation=1'.$search.$title.$auteur.$surname.$firstname.'">Precedente</a></li>';
        }
        echo '<li><a class="page-link" href="?page=1&recuperation=1'.$search.$title.$auteur.$surname.$firstname.'"><<</a></li>';

        // on boucle l'affichage des liens avec maximum 5 page à la fois
        if (!empty($_GET['page']) && $_GET['page'] > 3 ){
            $i= ($_GET['page']-2);
        }else{
            $i= 1 ;
        }
        if (!empty($_GET['page']) && ($_GET['page']+3) < $nombre_de_page ){
            $fin = $_GET['page'] + 3;
        }else{
            $fin = $nombre_de_page;
        }
        for($i ; $i <= $fin ; $i++ ){
            echo '<li class="';
            if ($i == $_GET['page']){
                echo ' active ';
                
            }
            echo 'page-item"><a class="page-link" href="?page='.$i.'&recuperation=1'.$search.$title.$auteur.$surname.$firstname.'">'.$i.'</a></li>';
            
        }
        echo '<li><a class="page-link" href="?page='.$nombre_de_page.'&recuperation=1'.$search.$title.$auteur.$surname.$firstname.'">>></a></li>';
        echo '<li class="page-item"><a class="page-link" ';
        
        if ($_GET['page'] == $nombre_de_page){
            echo 'href="?page='.$nombre_de_page.'&recuperation=1'.$search.$title.$auteur.$surname.$firstname.'">Suivante</a></li>';
        }else{
            echo ' href="?page='.($_GET['page']+1).'&recuperation=1'.$search.$title.$auteur.$surname.$firstname.'">Suivante</a></li>';
        }
        echo '</ul>
            </nav>
            </div>';
    }
        }catch(PDOException $e){
            echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur requette sql', $e);
        }catch (Exception $e){
            echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur php', $e);
        }
    }}
    // enregistre un retour et affiche les livres a retournés
    public function search_return_book($pdo){
        if ($pdo){
        $request = "SELECT COUNT(R.id) AS ctn
        FROM habitant AS H INNER JOIN book AS B INNER JOIN reservation AS R 
        WHERE R.reader = H.id AND R.book = B.id AND R.statut = 'emprunter' AND B.dispo = 'emprunter'";

        if(!empty($_GET['search_return']) && $_GET['search_return'] === '1' && (!empty($_GET['title']) || !empty($_GET['auteur'])
        || !empty($_GET['surname']) || !empty($_GET['firstname']) || !empty($_GET['email']))){
            $request .= " AND (B.title LIKE ? AND B.auteur LIKE ? AND H.surname LIKE ? AND H.firstname LIKE ? AND H.email LIKE ?) ORDER BY R.recuperation";
            $r = $pdo->prepare($request);
        
            $r->execute(array('%'.$_GET['title'].'%' ,'%'.$_GET['auteur'].'%','%'.$_GET['surname'].'%','%'.$_GET['firstname'].'%','%'.$_GET['email'].'%'));
            $count = $r->fetchAll(PDO::FETCH_ASSOC);
            var_dump($count);
        }else{
            $request .= "ORDER BY R.recuperation";
            $r = $pdo->prepare($request);
            $r->execute();
            $counts = $r->fetchAll(PDO::FETCH_ASSOC);
            

        }
        
          

        $nbr_element_par_page = 2;
        if (empty($_GET['page']) || is_numeric($_GET['page']) === false){
            $_GET['page']=1;
        }
        
        
        $debut = ($_GET['page']-1)*$nbr_element_par_page;
        $nombre_de_page = ceil($counts[0]['ctn']/$nbr_element_par_page);
        


        try{
        $request = "SELECT H.surname , H.firstname , H.email ,  B.title , B.auteur , R.id , DATEDIFF ( DATE(NOW()) , recuperation) AS d
            FROM habitant AS H INNER JOIN book AS B INNER JOIN reservation AS R 
            WHERE R.reader = H.id AND R.book = B.id AND R.statut = 'emprunter' AND B.dispo = 'emprunter'";
        
        if(!empty($_GET['search_return']) && $_GET['search_return'] === '1' && (!empty($_GET['title']) || !empty($_GET['auteur'])
        || !empty($_GET['surname']) || !empty($_GET['firstname']) || !empty($_GET['email']))){
            $request .= " AND (B.title LIKE ? AND B.auteur LIKE ? AND H.surname LIKE ? AND H.firstname LIKE ? AND H.email LIKE ?) ORDER BY R.recuperation LIMIT $nbr_element_par_page OFFSET $debut";
            $r = $pdo->prepare($request);
           
            $r->execute(array('%'.$_GET['title'].'%' ,'%'.$_GET['auteur'].'%','%'.$_GET['surname'].'%','%'.$_GET['firstname'].'%','%'.$_GET['email'].'%'));
           
        }else{
            $request .= "ORDER BY R.recuperation LIMIT $nbr_element_par_page OFFSET $debut";
            $r = $pdo->prepare($request);
            $r->execute();
        }
            $result = $r->fetchAll(PDO::FETCH_ASSOC);
            $count = count($result);
            

            echo '<div class="scroll"><table class="table_valid_book">
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
                    <input type="text" style="display : none" name="retour" value="'.$_GET['retour'].'">
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
                            case 'email' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).' </h5></div></td>';
                            break;
                            case 'id':
                                echo '<input type="text" style="display : none" name="retour" value="'.$_GET['retour'].'">';
                                if(!empty($_GET['search_return']) && $_GET['search_return'] === '1' && ((!empty($_GET['title']) || !empty($_GET['auteur'])
                                || !empty($_GET['surname']) || !empty($_GET['firstname']) || !empty($_GET['email'])))){
                                    echo '<input type="text" style="display : none" name="search_return" value="'.$_GET['search_return'].'">
                                    <input type="text" style="display : none" name="title" value="'.$_GET['title'].'">
                                    <input type="text" style="display : none" name="auteur" value="'.$_GET['auteur'].'">
                                    <input type="text" style="display : none" name="surname" value="'.$_GET['surname'].'">
                                    <input type="text" style="display : none" name="firstname" value="'.$_GET['firstname'].'">
                                    <input type="text" style="display : none" name="email" value="'.$_GET['email'].'">'
                                    ;
                                }
                                echo '<td><div class="pb-2 align-center"> <button class="btn btn-primary p-2" type="submit" name="returnid" value="'.$return.'" >Je valide le retour du livre</button>
                                </div></td>';
                                break;
                        }
                    }
                }
                echo '</form></div></tr>';
            }
            echo '</tbody></table></div>';
                 
        if($counts[0]['ctn'] != 0){
            echo '<div class="mt-5 "  style="width : 100%;"> 
        <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link"
        ';
        if(!empty($_GET['search_return']) && $_GET['search_return'] === '1' && (!empty($_GET['title']) || !empty($_GET['auteur'])
        || !empty($_GET['surname']) || !empty($_GET['firstname']) || !empty($_GET['email']))){
            $search = '&search_return='.$_GET['search_valid'];
            $title = '&title='.$_GET['title'];
            $auteur = '&auteur='.$_GET['auteur'];
            $surname = '&surname='.$_GET['surname'];
            $firstname = '&firstname='.$_GET['firstname'];
            $email = '&email='.$_GET['email'];
        }else{
            $search = '&search_return=1';
            $title = '&title=';
            $auteur = '&auteur=';
            $surname = '&surname=';
            $firstname = '&firstname=';
            $email = '&email=';
            }

        // on redirige si le visiteur modifie manuelement la page dans l'url
        if ($_GET['page'] < 1 || $_GET['page'] > $nombre_de_page){
            header('Location: ./employerDashboard.php?page=1&retour=1'.$search.$title.$auteur.$surname.$firstname.$email);
        }
        // on renvoit a la page 1 si appuis sur precedent a la page 1
        if ($_GET['page'] == 1){
            echo 'href="?page=1&retour=1'.$search.$title.$auteur.$surname.$firstname.$email.'">Precedente</a></li>';
        }else{
            echo ' href="?page='.($_GET['page']-1).'&retour=1'.$search.$title.$auteur.$surname.$firstname.$email.'">Precedente</a></li>';
        }
        echo '<li><a class="page-link" href="?page=1&retour=1'.$search.$title.$auteur.$surname.$firstname.$email.'"><<</a></li>';

        // on boucle l'affichage des liens avec maximum 5 page à la fois
        if (!empty($_GET['page']) && $_GET['page'] > 3 ){
            $i= ($_GET['page']-2);
        }else{
            $i= 1 ;
        }
        if (!empty($_GET['page']) && ($_GET['page']+3) < $nombre_de_page ){
            $fin = $_GET['page'] + 3;
        }else{
            $fin = $nombre_de_page;
        }
        for($i ; $i <= $fin ; $i++ ){
            echo '<li class="';
            if ($i == $_GET['page']){
                echo ' active ';
                
            }
            echo 'page-item"><a class="page-link" href="?page='.$i.$search.$title.$auteur.$surname.$firstname.$email.'&retour=1">'.$i.'</a></li>';
            
        }
        echo '<li><a class="page-link" href="?page='.$nombre_de_page.$search.$title.$auteur.$surname.$firstname.$email.'&retour=1">>></a></li>';
        echo '<li class="page-item"><a class="page-link" ';
        
        if ($_GET['page'] == $nombre_de_page){
            echo 'href="?page='.$nombre_de_page.$search.$title.$auteur.$surname.$firstname.$email.'&retour=1">Suivante</a></li>';
        }else{
            echo ' href="?page='.($_GET['page']+1).$search.$title.$auteur.$surname.$firstname.$email.'&retour=1">Suivante</a></li>';
        }
        echo '</ul>
            </nav>
            </div>';
    }
        }
        catch (PDOException $e){
            echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur requette sql', $e);
        }catch (Exception $e){
            echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur php', $e);
        }
    }
    }
    public function valid_return_book($pdo){
        if ($pdo){
        if (!empty($_GET['returnid'])){
            $request = "UPDATE book SET dispo = 'disponible' WHERE dispo = 'emprunter' AND reserveid = ? ;
            UPDATE reservation SET bookreturn = DATE(NOW()) , statut = 'terminer' WHERE id = ? AND statut = 'emprunter' AND bookreturn IS NULL";

            $r = $pdo->prepare($request);
            $r->execute(array($_GET['returnid'], $_GET['returnid']));
        }
    }}

    public function annulation_reservation($pdo){
        if ($pdo){
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

    }
    private function echo($value){
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
    }
    
    public function en_retard($pdo){
        if ($pdo){
        $request = "SELECT H.surname , H.firstname , H.email ,  B.title , B.auteur , R.id  AS d
        FROM habitant AS H INNER JOIN book AS B INNER JOIN reservation AS R 
        WHERE DATEDIFF ( DATE(NOW()) , R.recuperation)> '21' AND R.reader = H.id AND R.book = B.id AND R.statut = 'emprunter' AND B.dispo = 'emprunter'";


       try{ $request .= "ORDER BY R.recuperation";
        $r = $pdo->prepare($request);
        $r->execute();
        
        $result = $r->fetchAll(PDO::FETCH_ASSOC);
        $count = count($result);
        echo $count;}
        catch(PDOException $e)
        {
            echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur requette sql', $e);
            
        }catch (Exception $e){
            echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur php', $e);
        }
    }
    }


    // historique de chaque utilisateur qui d'affiche sur la page mon compte
    public function historique_reservation($pdo){
        if ($pdo){
        try{ 
            $request = 'SELECT B.title, B.auteur, DATE_FORMAT(R.reservation, "%d/%m/%Y") AS reservation ,
             DATE_FORMAT(R.bookreturn, "%d/%m/%Y") AS bookreturn ,
            DATE_FORMAT(DATE_ADD(R.recuperation, INTERVAL 21 DAY) ,"%d/%m/%Y") AS recuperation , 
            DATEDIFF(DATE_ADD(R.recuperation, INTERVAL 21 DAY) ,DATE(NOW())) AS d  ,
            DATE_FORMAT(DATE_ADD(R.reservation, INTERVAL 3 DAY) , "%d/%m/%Y" ) AS av,
             R.recuperation AS recup FROM book AS B INNER JOIN reservation AS R INNER JOIN habitant AS H 
            WHERE R.reader = ? AND H.id = R.reader AND B.id = R.book  ORDER BY reservation DESC ';
            $r = $pdo->prepare($request);
            $r->execute([$_SESSION['id']]);
            $result = $r->fetchAll(PDO::FETCH_ASSOC);
            $count = count($result);

            
          
             
            echo '<div class="scroll"><table class="table_historique">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Etat</th>
                </tr>
            </thead>
            <tbody>
            
            ';
            for($i = 0 ; $i < $count ; $i++){
                
                $return_date = $result[$i]['bookreturn'];
                //var_dump($return_date);
                $d = $result[$i]['d'];
                $recup = $result[$i]['recup'];
                $av = $result[$i]['av'];
                $rec=  $result[$i]['recuperation'];
                
                if($d < 0 && empty($return_date)){
                    echo '<tr class=" tr-alert">';
                }else{
                    echo '
                        <tr>';
                }
                foreach($result[$i] as $key => $return){
                    if(!empty($result)){
                        switch ($key) {
                            case 'title' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).' </h5></div></td>';
                                break;
                            case 'auteur' :
                                echo '<td><div class="align-center"><h5>'.ucfirst($return).' </h5></div></td>';
                                break;
                           
                            case 'bookreturn' :
                                
                                if($return === null && $d >= 0 && !empty($recup)){
                                    echo '<td><div class="align-center"><h5>A retourner avant le <br>'.$rec.' </h5></div></td>';
                                    break;
                                }elseif($d < 0 && !isset($return)){
                                    echo '<td><div class="align-center"><h5>EN RETARD <br> devait être rendu avant le </br>'.$rec.' </h5></div></td>';
                                    break;
                                }elseif(empty($return) && empty($recup)){
                                    echo '<td><div class="align-center"><h5>A récupérer avant le <br>'.$av.' </h5></div></td>';
                                    break;
                                }else{
                                echo '<td><div class="align-center"><h5>Rendu</h5></div></td>';
                            break;}
                            
                        }
                    }
                }
                echo '</tr>';
            }
            echo '</tbody></table></div>';


        

        }catch(PDOException $e){
            echo 'Une erreur est survenue le webmaster à été avisé'. $e;
            mail('contact@av.developpeur.fr', ' erreur requette sql', $e);
        }catch (Exception $e){
            echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur requette sql', $e);
        }

    }
    }
    public function habitant_retard_notif($pdo){
        if ($pdo){
        try{
            
        $request = 'SELECT DATEDIFF(DATE_ADD(R.recuperation, INTERVAL 21 DAY) , DATE(NOW())) AS d FROM book AS B INNER JOIN reservation AS R INNER JOIN habitant AS H 
            WHERE R.reader = ? AND H.id = R.reader AND B.id = R.book AND R.recuperation IS NOT NULL AND R.bookreturn IS NULL ORDER BY d ';
            $r = $pdo->prepare($request);
            $r->execute([$_SESSION['id']]);
            $result = $r->fetchAll(PDO::FETCH_ASSOC);
            $count = count($result);
            
            $nbr_livre_retard = 0;
            for($i = 0 ; $i < $count ; $i++){
            $d = $result[$i]['d'];
                if ($d < 0){
                    $nbr_livre_retard ++;
                }
            }
            if($nbr_livre_retard > 0){
            echo 'Vous avez un ou plusieurs livres en retard';
            }

        }catch(PDOException $e)
        {
            echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur requette sql', $e);
            
        }catch (Exception $e){
            echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur php', $e);
        }
        }
}}
