<div class="form-perso">
    <?php
    require_once "./controllers/Book.php";
    if(!empty($_POST) && $_POST['newBook'] === 'on'){
        $book = new Book(
            htmlspecialchars($_POST['title']) , 
            addslashes(htmlspecialchars($_POST['descriptions'])), 
            htmlspecialchars($_POST['publication_date']), 
            htmlspecialchars($_POST['auteur']), 
            htmlspecialchars($_POST['genre']));
        $test = $book->add_book($pdo);
        if($test === false){
           echo '<form action="./employerDashboard.php" method="post" enctype="multipart/form-data" >
                    <div class="form-group pt-2">
                        <label for="contactForm1">Titre *</label>
                        <input type="text" class="form-control" id="contactForm1" name="title" placeholder="Titre du livre" value="'.$_POST['title'].'">
                    </div>
                    <div class="form-group pt-2">
                        <label for="formFile" class="form-label">Image jpg jpeg ou png uniquement</label>
                        <input class="form-control" type="file" id="formFile" name="image" accept="image/png , image/jpg, image/jpeg">
                    </div>
                    <div class="form-group pt-2" >
                        <label for="contactForm1">Description *</label>
                        <textarea name="descriptions" class="form-control" id="contactForm1" cols="20" rows="3" placeholder="Description de l\'ouvrage"  required>'.addslashes($_POST['descriptions']).'</textarea>
                    </div>
                    <div class="form-group pt-2">
                        <label for="contactForm1">Date de publication</label> 
                        <input type="date" class="form-control" id="contactForm1" name="publication_date" value="'.$_POST['publication_date'].'">
                    </div>   
                    <div class="form-group pt-2" >
                        <label for="contactForm1">Auteur *</label>
                        <input type="text" class="form-control" id="contactForm1" name="auteur" placeholder="Nom de l\'auteur" value="'.$_POST['auteur'].'" required>
                    </div>
                    <div class="form-group pt-2" >
                        <label for="contactForm1">Genre *</label>
                        <input type="text" class="form-control" id="contactForm1" name="genre" placeholder="Roman" value="'.$_POST['genre'].'" required>
                    </div>
                    <div class="form-group pt-2">
                        <button class="btn btn-success" type="submit" name="newBook" value="on" >Enregistrer</button>
                        
                    </div>
                </form>';
        }else{
            echo '<form action="./employerDashboard.php" method="post" enctype="multipart/form-data" >
                <div class="form-group pt-2">
                    <label for="contactForm1">Titre *</label>
                    <input type="text" class="form-control" id="contactForm1" name="title" placeholder="Titre du livre">
                </div>
                <div class="form-group pt-2">
                    <label for="formFile" class="form-label">Image jpg jpeg ou png uniquement</label>
                    <input class="form-control" type="file" id="formFile" name="image" accept="image/png , image/jpg, image/jpeg">
                </div>
                <div class="form-group pt-2" >
                    <label for="contactForm1">Description *</label>
                    <textarea name="descriptions" class="form-control" id="contactForm1" cols="20" rows="3" placeholder="Description de l\'ouvrage" required></textarea>
                </div>
                <div class="form-group pt-2">
                    <label for="contactForm1">Date de publication</label> 
                    <input type="date" class="form-control" id="contactForm1" name="publication_date" >
                </div>   
                <div class="form-group pt-2" >
                    <label for="contactForm1">Auteur *</label>
                    <input type="text" class="form-control" id="contactForm1" name="auteur" placeholder="Nom de l\'auteur" required>
                </div>
                <div class="form-group pt-2" >
                    <label for="contactForm1">Genre *</label>
                    <input type="text" class="form-control" id="contactForm1" name="genre" placeholder="Roman" required>
                </div>
                <div class="form-group pt-2">
                    <button class="btn btn-success" type="submit" name="newBook" value="on" >Enregistrer</button>
                    
                </div>
            </form>';
        }
        }else{
            echo '<form action="./employerDashboard.php" method="post" enctype="multipart/form-data" >
                <div class="form-group pt-2">
                    <label for="contactForm1">Titre *</label>
                    <input type="text" class="form-control" id="contactForm1" name="title" placeholder="Titre du livre">
                </div>
                <div class="form-group pt-2">
                    <label for="formFile" class="form-label">Image jpg jpeg ou png uniquement</label>
                    <input class="form-control" type="file" id="formFile" name="image" accept="image/png , image/jpg, image/jpeg">
                </div>
                <div class="form-group pt-2" >
                    <label for="contactForm1">Description *</label>
                    <textarea name="descriptions" class="form-control" id="contactForm1" cols="20" rows="3" placeholder="Description de l\'ouvrage" required></textarea>
                </div>
                <div class="form-group pt-2">
                    <label for="contactForm1">Date de publication</label> 
                    <input type="date" class="form-control" id="contactForm1" name="publication_date" >
                </div>   
                <div class="form-group pt-2" >
                    <label for="contactForm1">Auteur *</label>
                    <input type="text" class="form-control" id="contactForm1" name="auteur" placeholder="Nom de l\'auteur" required>
                </div>
                <div class="form-group pt-2" >
                    <label for="contactForm1">Genre *</label>
                    <input type="text" class="form-control" id="contactForm1" name="genre" placeholder="Roman" required>
                </div>
                <div class="form-group pt-2">
                    <button class="btn btn-success" type="submit" name="newBook" value="on" >Enregistrer</button>
                    
                </div>
            </form>';
    }
    var_dump($test);
    ?>

    