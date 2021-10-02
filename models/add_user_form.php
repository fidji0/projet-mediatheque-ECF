<div class="form-perso">
    <?php
    require_once "./controllers/functionAuthenticate.php";
    createUser($pdo);
    ?>

    <form action="./inscription.php" method="post" >
        <div class="form-group pt-2">
            <label for="contactForm1">Votre Nom</label>
            <input type="text" class="form-control" id="contactForm1" name="firstname" placeholder="Votre nom" required>
        </div>
        <div class="form-group pt-2" >
            <label for="contactForm1">Prémon</label>
            <input type="text" class="form-control" id="contactForm1" name="surname" placeholder="Votre prenom" required>
        </div>
        <div class="form-group pt-2">
            <label for="contactForm1">Votre email</label>
            <input type="email" class="form-control" id="contactForm1" name="email" required>
        </div>
        <div class="form-group pt-2">
            <label for="contactForm1">Votre date de naissance</label> 
            <input type="date" class="form-control" id="contactForm1" name="date_of_birth" required >
        </div>    
        <div class="form-group pt-2">
            <label for="contactForm1">Votre mot de passe</label> 
            <input type="password" class="form-control" id="contactForm1" name="password" >
        </div> 
        <div class="form-group pt-2">
            <button class="btn btn-success" type="submit">Enregistrer</button>
        </div>
    </form>
</div>
