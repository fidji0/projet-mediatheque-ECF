<?php

class Book{

    public $title;
    public $link_img;
    public $descriptions;	
    public $publication_date;	
    public $auteur;	
    public $dispo;
    public $genre;

    public function __construct(string $title, string $descriptions, $publication_date ,string $auteur, string $genre){
        $this->title = $title;
        $this->descriptions = $descriptions;
        $this->publication_date = $publication_date;
        $this->auteur = $auteur;
        $this->genre = $genre;
        
    }
    
    function add_book($pdo){
        if ($pdo){
        // On verifie que le formulaire à été rempli correctement
        if(!empty($this->title) && !empty($_FILES['image']) && !empty($this->descriptions) && !empty($this->auteur)&& !empty($this->genre)){
           
            if($_FILES['image']['type'] == 'image/png' || $_FILES['image']['type'] == 'image/jpg' || $_FILES['image']['type'] == 'image/jpeg' ){
                // On enregistre le chemin pour disposé l'image
                $uploaddir = "./vues/Img/";
                
                // On créer un identifiant unique pour l'image
                $uploadfile = $uploaddir . uniqid() . basename($_FILES['image']['name']);
            
                $addBook = "INSERT INTO book (title , link_img , publication_date, descriptions , auteur, genre) VALUES (? , ?, ? , ?, ?, ?) ";
                
                $result = $pdo->prepare($addBook);

                $result->execute(array($this->title , $uploadfile , $this->publication_date, $this->descriptions , $this->auteur, $this->genre));

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
                    echo '<div class="alert alert-success" role="alert">
                    Le livre à bien été ajouté
                    </div>';
                    return true;

                } else {
                    echo '<div class="alert alert-danger" role="alert">
                    Le fichier n\'a pas pu être télécharger!
                    </div>';
                    return false;
                }

                

            }else{
                echo '<div class="alert alert-danger" role="alert">
               Le format du fichier ne correspond pas!
                </div>';
                return false;

            }
            
        }else{
            echo '<div class="alert alert-danger" role="alert">
            Vous n\'avez pas remplis tous les champs obligatoire *
            </div>';
            return false;

        }
    }
}}