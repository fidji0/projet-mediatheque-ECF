<?php

class Search{
        public $search = '';
        public $genre = '';

   
    public function select_genre($pdo){
        $request = "SELECT COUNT(id) AS ctn FROM book ";
        $d = $pdo->prepare($request);
        $d->execute();
        $count = $d->fetchAll(PDO::FETCH_ASSOC);

        // creation de la pagination
        $nbr_element_par_page = 1;
        if (empty($_GET['page'])){
            $_GET['page']=1;
        }
        $page = $_GET['page'];
        $debut = ($_GET['page']-1)*$nbr_element_par_page;
        $nombre_de_page = ceil($count[0]['ctn']/$nbr_element_par_page);
        
        
       

        $request = "SELECT id ,link_img, title ,  descriptions, auteur, genre, dispo, publication_date  FROM book LIMIT $nbr_element_par_page OFFSET $debut";
        
        if(!empty($_GET['search_book']) && $_GET['search_book'] === '1' && (!empty($_GET['title']) || !empty($_GET['genre']))){
            $request .= "WHERE genre LIKE ? AND  tile = ?";
            $r = $pdo->prepare($request);
            $r->execute(array('%'.$_GET['title'].'%','%'.$_GET['genre'].'%'));
        }else{
            $r = $pdo->prepare($request);
            $r->execute();
        }

            $results = $r->fetchAll(PDO::FETCH_ASSOC);

            $count = count($results);
        
            
        for($i = 0 ; $i < $count ; $i++){
           
            echo '
            <div class="m-1 bgBook">
            <form action="./connectedUser.php" method="get">';
            $id = $results[$i]['id'];
            foreach($results[$i] as $key => $result){ 
                if(!empty($result)){
                switch ($key) {
                    case 'title' :
                        if(strlen($result) >= 10){
                            echo '<div class="p-2 align-center">
                            <h5>
                            <a href="./connectedUser.php?id='.$id.'">'.substr(ucfirst($result),0,10).' ...</a>
                            </h5>
                            </div>' ;}
                        elseif(strlen($result) < 10){
                            echo '<div class="p-2 align-center">
                            <h5>
                            <a href="./connectedUser.php?id='.$id.'">'.substr(ucfirst($result),0,10).' ...</a>
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
                        echo '<input class="" type="text" style="display : none" value="'.$result.'">';
                    case 'dispo':
                        if($result === 'disponible'){
                            echo '<div class="pb-2 align-center"> <button class="btn btn-primary p-2" type="submit" name="dispo" value="'.$id.'" >Je réserve ce livre</button>
                            </div>';
                        break;}
                        elseif($result === 'reserved'){
                            echo '<div class="pb-2 align-center"><button class="btn btn-primary p-2" disabled="disabled" type="submit"  >Livre actuellement indisponible</button>
                            </div>';
                        break;}
                        elseif($result === 'emprunter'){
                            echo '<div class="pb-2 align-center"><button class="btn btn-primary p-2" disabled="disabled" type="submit"  >Livre actuellement indisponible</button>
                            </div>';
                        }
                        break;
                    
                }
                } 
                
            }
            echo '</form></div>';
        }   

        echo '<div class="mt-5 "  style="width : 100%;"> 
        <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item"><a class="page-link"
        ';

        // on redirige si le visiteur modifie manuelement la page dans l'url
        if ($_GET['page'] < 1 || $_GET['page'] > $nombre_de_page){
            header('Location: ./connectedUser.php?page=1');
        }
        // on renvoit a la page 1 si appuis sur precedent a la page 1
        if ($_GET['page'] === 1){
            echo 'href="?page=1">Precedente</a></li>';
        }else{
            echo ' href="?page='.($_GET['page']-1).'">Precedente</a></li>';
        }
        echo '<li><a class="page-link" href="?page=1"><<</a></li>';

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
            echo 'page-item"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
            
        }
        echo '<li><a class="page-link" href="?page='.$nombre_de_page.'">>></a></li>';
        echo '<li class="page-item"><a class="page-link" ';
        
        if ($_GET['page'] === $nombre_de_page){
            echo 'href="?page='.$nombre_de_page.'">Suivante</a></li>';
        }else{
            echo ' href="?page='.($_GET['page']+1).'">Suivante</a></li>';
        }
        echo '</ul>
            </nav>
            </div>';
        
        
    }
    function detail_book($pdo){
        if(!empty($_GET['id'])){
            $id = $_GET['id'];
            $request = "SELECT id, link_img, title , descriptions, auteur, genre, dispo, publication_date  FROM book WHERE id = $id";
            

                $results = $pdo->query($request)->fetchAll(PDO::FETCH_ASSOC);
                
            
                echo '
                <div class="m-1 detailBook">
                <form action="./connectedUser.php" method="get">';
                foreach($results[0] as $key => $result){ 
                    if(!empty($result)){
                    switch ($key) {
                        case 'title' :
                                echo '<div class="flexBook"><div class="p-2 align-center">
                                <h5>
                                '.ucfirst($result).'
                                </h5>
                                </div>' ;
                            
                            break;
                        case 'link_img':
                            echo '<div class="p-2"><img class="br20px" src="'.$result.'" height="auto" width="300px" alt=""></div>';
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
                            echo '<input class="p-2" type="text" style="display : none" value="'.$result.'">';
                        case 'dispo':
                            if($result === 'disponible'){
                                echo '<div class="pb-2 align-center"> <button class="btn btn-primary p-2" type="submit" name="dispo" value="'.$id.'" >Je réserve ce livre</button>
                                </div>';
                            break;}
                            elseif($result === 'reserved'){
                                echo '<div class="pb-2 align-center"><button class="btn btn-primary p-2" disabled="disabled" type="submit"  >Livre actuellement indisponible</button>
                                </div>';
                            break;}
                            elseif($result === 'emprunter'){
                                echo '<div class="pb-2 align-center"><button class="btn btn-primary p-2" disabled="disabled" type="submit"  >Livre actuellement indisponible</button>
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
