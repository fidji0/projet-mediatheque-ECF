<?php

class Search{
        public $search = '';
        public $genre = '';

    public function genre($pdo){
        if ($pdo){
        $request = "SELECT genre FROM book GROUP BY genre";

        $d = $pdo->prepare($request);
        $d->execute();
        $result = $d->fetchAll(PDO::FETCH_ASSOC);

        $count= count($result);
        for($i = 0 ; $i < $count ; $i++){
            
            foreach($result[$i] as $key => $res){ 
                echo '<option>'.ucfirst($res).'</option>';
            }
        }
    }}
    public function select_genre($pdo){
        if ($pdo){
        try{  
        
        $request = "SELECT COUNT(id) AS ctn FROM book ";
        if(!empty($_GET['search_book']) && $_GET['search_book'] == '1' && ((!empty($_GET['title']) || !empty($_GET['genre'])))){
            $title = $_GET['title'];
            $genre = $_GET['genre'];
            $request .= "WHERE genre LIKE ? AND title LIKE ? ";
            $d = $pdo->prepare($request);
            $d->execute(['%'.$_GET['genre'].'%','%'.$_GET['title'].'%']);
            

        }else{
            $d = $pdo->prepare($request);
            $d->execute();
            
        }
       
        
        $counts = $d->fetchAll(PDO::FETCH_ASSOC);
        

         }catch(PDOException $e){
             echo 'Une erreur est survenue le webmaster à été avisé';
             mail('contact@av.developpeur.fr', ' erreur requette sql', $e);
         }
        // creation de la pagination
        $nbr_element_par_page = 10;
        if (empty($_GET['page'])){
            $_GET['page']=1;
        }
        $page = $_GET['page'];
        $debut = ($_GET['page']-1)*$nbr_element_par_page;
        $nombre_de_page = ceil($counts[0]['ctn']/$nbr_element_par_page);
        
        
       

        $request = "SELECT id ,link_img, title ,  descriptions, auteur, genre, dispo FROM book ";
        
        if(!empty($_GET['search_book']) && $_GET['search_book'] == '1' && (!empty($_GET['title']) || !empty($_GET['genre']))){
            $request .= "WHERE genre LIKE ? AND  title LIKE ? ORDER BY title LIMIT $nbr_element_par_page OFFSET $debut ";
            $r = $pdo->prepare($request);
            $r->execute(array('%'.$_GET['genre'].'%','%'.$_GET['title'].'%'));
        }else{
            $request .= "ORDER BY title LIMIT $nbr_element_par_page OFFSET $debut";
            $r = $pdo->prepare($request);
            $r->execute();
        }

            $results = $r->fetchAll(PDO::FETCH_ASSOC);
            
            $count = count($results);
        
        if($count > 0){    
        for($i = 0 ; $i < $count ; $i++){
       
            echo '
            <div class="bgBook">
            <form class="width100" action="./connectedUser.php" method="get">';
        
            $id = $results[$i]['id'];
            foreach($results[$i] as $key => $result){ 
                if(!empty($result)){
                switch ($key) {
                    case 'title' :
                        if(strlen($result) >= 20){
                            echo '<div class="p-2 align-center">
                            <h5>
                            <a href="./connectedUser.php?id='.$id.'">'.substr(ucfirst($result),0,20).'...</a>
                            </h5>
                            </div>' ;}
                        elseif(strlen($result) < 20){
                            echo '<div class="p-2 align-center">
                            <h5>
                            <a href="./connectedUser.php?id='.$id.'">'.ucfirst($result).'</a>
                            </h5>
                            </div>';
                        }
                        break;
                    case 'link_img':
                        echo '<div class="p-2">
                        <a href="./connectedUser.php?id='.$id.'"><img class="br20px" src="'.$result.'" height="300px" width="100%" alt=""></a>
                        
                        </div>';
                        break;
                    case 'auteur':
                        echo '<div class=" align-center"><p> Auteur :<strong> '.ucfirst($result).'</strong></p></div>';
                        break;
                    case 'genre':
                        echo '<div class=" align-center"><p>Genre : <br><strong> '.ucfirst($result).'</strong></p></div>';
                        break;
                    case 'publication_date':
                        echo '<div class=" align-center"><p>Date de publication : '.$result.'</p></div>';
                        break;
                    case 'id':
                        if(!empty($_GET['page'])){
                            echo '<input class="p-2" type="text" style="display : none" name="page" value="'.$_GET['page'].'">';
                        }
                        echo '<input class="" type="text" style="display : none" value="'.$result.'">';
                        break;
                    case 'dispo':
                        if($result === 'disponible'){
                            echo '<div class="pb-2 align-center"> <button class="btn btn_perso p-2" type="submit" name="dispo" value="'.$id.'" >Je réserve ce livre</button>
                            </div>';
                        break;}
                        elseif($result === 'reserved'){
                            echo '<div class="pb-2 align-center"><button class="btn btn_perso p-2" disabled="disabled" type="submit"  >Livre actuellement indisponible</button>
                            </div>';
                        break;}
                        elseif($result === 'emprunter'){
                            echo '<div class="pb-2 align-center"><button class="btn btn_perso p-2" disabled="disabled" type="submit"  >Livre actuellement indisponible</button>
                            </div>';
                        }
                        break;
                    
                }
                } 
                
            }
            echo '</form></div>';
        }   
    }
        if($counts[0]['ctn'] === '0'){
            echo '<div class="alert alert-danger" role="alert">
                    Il n\'y a pas de résultat à votre recherche.
                </div>';
        }else{
            
        
        echo '<div class="mt-5 "  style="width : 100%;"> 
        <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link"
        ';
        //
        if(!empty($_GET['title']) || !empty($_GET['genre'])){
            $genre = '&genre='.$_GET['genre'];
            $title = '&title='.$_GET['title'];
            $search = '&search_book='.$_GET['search_book'];
        }else{
            $genre = '&genre=';
            $title = '&title=';
            $search = '&search_book=';
        }
        // on redirige si le visiteur modifie manuelement la page dans l'url
        if ($_GET['page'] < 1 || $_GET['page'] > $nombre_de_page){
            header('Location: ./connectedUser.php?page=1'.$genre.$title.$search.'');
        }
        // on renvoit a la page 1 si appuis sur precedent a la page 1
        if ($_GET['page'] === 1){
            echo 'href="?page=1'.$genre.$title.'">Precedente</a></li>';
        }else{
            echo ' href="?page='.($_GET['page']-1).$genre.$title.$search.'">Precedente</a></li>';
        }
        echo '<li><a class="page-link" href="?page=1'.$genre.$title.$search.'"><<</a></li>';

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
            echo 'page-item"><a class="page-link" href="?page='.$i.$genre.$title.$search.'">'.$i.'</a></li>';
            
        }
        echo '<li><a class="page-link" href="?page='.$nombre_de_page.$genre.$title.$search.'">>></a></li>';
        echo '<li class="page-item"><a class="page-link" ';
        
        if ($_GET['page'] == $nombre_de_page){
            echo 'href="?page='.$nombre_de_page.$genre.$title.$search.'">Suivante</a></li>';
        }else{
            echo ' href="?page='.($_GET['page']+1).$genre.$title.$search.'">Suivante</a></li>';
        }
        echo '</ul>
            </nav>
            </div>';
        }
    }
    }
    function detail_book($pdo){
        
        if(!empty($_GET['id']) && $pdo){
            
            $request = "SELECT id, link_img, title , descriptions, auteur, genre, publication_date, dispo FROM book WHERE id = ?";
                $d= $pdo->prepare($request);
                $d->execute([$_GET['id']]);

                $results = $d->fetchAll(PDO::FETCH_ASSOC);
                    
                echo '
                <div class="width100 mb-5">
                <form class="detailBook" action="./connectedUser.php" method="get">';
                if (!empty($results[0]['link_img'])){
                            
                    echo '<div><div class="p-2"><img class="br20px" src="'.$results[0]['link_img'].'" height="auto" width="300px" alt=""></div></div>';
            }
                echo '<div>';
                foreach($results[0] as $key => $result){ 
                    if(!empty($result)){
                    
                        
                    switch ($key) {
                        case 'title' :
                                echo '<div ><div class="p-2 align-center">
                                <h5>
                                '.ucfirst($result).'
                                </h5>
                                </div>' ;
                            
                            break;
                        
                        case 'descriptions':
                            echo '<div class="p-2 "><p>'.ucfirst(stripslashes(nl2br($result))).'</p></div>';
                            break;
                        case 'auteur':
                            echo '<div class="p-2"><p> Auteur :<strong> '.ucfirst($result).'</strong></p></div>';
                            break;
                        case 'genre':
                            echo '<div class="p-2"><p>Genre : <br><strong> '.ucfirst($result).'</strong></p></div>';
                            break;
                        case 'publication_date':
                            echo '<div class="p-2"><p>Date de publication : '.$result.'</p></div>';
                            break;
                        case 'id':
                            if(!empty($_GET['id'])){
                                echo '<input class="p-2" type="text" style="display : none" name="id" value="'.$_GET['id'].'">';
                            }
                            echo '<input class="p-2" type="text" style="display : none" value="'.$result.'">';
                            break;
                        case 'dispo':
                            if($result === 'disponible'){
                                echo '<div class="pb-2 align-center"> <button class="btn btn_perso p-2" type="submit" name="dispo" value="'.$_GET['id'].'" >Je réserve ce livre</button>
                                </div>';
                            break;}
                            elseif($result === 'reserved'){
                                echo '<div class="pb-2 align-center"><button class="btn btn_perso p-2" disabled="disabled" type="submit"  >Livre actuellement indisponible</button>
                                </div>';
                            break;}
                            elseif($result === 'emprunter'){
                                echo '<div class="pb-2 align-center"><button class="btn btn_perso p-2" disabled="disabled" type="submit"  >Livre actuellement indisponible</button>
                                </div>';
                            }
                            break;
                        
                    }
                    } 
                    
                }
                echo '</div></form></div>';
            }else{
                $this->select_genre($pdo);
            }  
    }
    
}
