<?php

class Search{
        public $search = '*';
        public $genre = '';

    public function __construct(string $search, string $genre)
    {
        $this->search = $search;
        $this->genre = $genre;
    }

    public function select_genre($pdo){
        $request = "SELECT title , link_img, descriptions, auteur, genre, dispo, publication_date FROM book";
        if(!empty($this->genre)){
            $request .= "WHERE genre = $this->genre";
        }
            var_dump($request);

            $results = $pdo->query($request)->fetchAll(PDO::FETCH_ASSOC);
            
            $count = count($results);
        
            
        for($i = 0 ; $i < $count ; $i++){
            
            echo '<div style="padding : 25px 25px;">';
            foreach($results[$i] as $key => $result){
                if(!empty($result)){
                switch ($key) {
                    case 'title' :
                        echo '<div><h2>Titre du Livre: '.$result.'</h2></div>' ;
                        break;
                    case 'link_img':
                        echo '<div><img src="'.$result.'" height="200px" width="200px" alt=""></div>';
                        break;
                    case 'descriptions':
                        echo '<div><p> Description : '.stripslashes($result).'</p></div>';
                        break;
                    case 'auteur':
                        echo '<div><p> Auteur : '.$result.'</p></div>';
                        break;
                    case 'genre':
                        echo '<div><p>Genre : '.$result.'</p></div>';
                        break;
                    case 'publication_date':
                        echo '<div><p>Date de publication : '.$result.'</p></div>';
                        break;
                    case 'dispo':
                        echo '<div><form action="./connectedUser.php">
                        <button class="btn btn-primary" disabled="disabled" type="submit" name="'.$result.'">Je réserve ce livre</button>
                        </form></div>';
                        break;
                }
                } 
                
            }
            echo '</div>';
        }   



    }
}
