<?php

class Search{
        public $search = '';
        public $genre = '';

   
    public function select_genre($pdo){
        $request = "SELECT id ,link_img, title ,  descriptions, auteur, genre, dispo, publication_date  FROM book ";
        
        if(!empty($_GET['search_book']) && $_GET['search_book'] === '1' && (!empty($_GET['title']) || !empty($_GET['genre']))){
            $request .= "WHERE genre LIKE ? AND  tile = ?";
            $r = $pdo->prepare($request);
            $r->execute(array('%'.$_GET['title'].'%','%'.$_GET['genre'].'%',));
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
