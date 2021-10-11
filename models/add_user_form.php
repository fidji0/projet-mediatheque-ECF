<div class="form-perso">
    <?php
    require_once "./controllers/functionAuthenticate.php";
     createUser($pdo);
     $return = false ;

    ?>

    <form action="./inscription.php" method="post" >
        <div class="form-group pt-2">
            <label for="contactForm1">Votre Prénom</label>
            <input type="text" class="form-control" id="contactForm1" name="firstname" placeholder="Votre nom" required 
            <?php if(isset($return) && $return === false) {echo 'value="'.$_POST['firstname'].'"';} ?>>
        </div>
        <div class="form-group pt-2" >
            <label for="contactForm1">Votre Nom</label>
            <input type="text" class="form-control" id="contactForm1" name="surname" placeholder="Votre prenom" 
            <?php if(isset($return) && $return === false) {echo 'value="'.$_POST['surname'].'"';} ?> required>
        </div>
        <div class="form-group pt-2" >
            <label for="contactForm1">Adresse postale</label>
            <input type="text" class="form-control" id="contactForm1" name="adress" placeholder="Votre adresse" 
            <?php if(isset($return) && $return === false) {echo 'value="'.$_POST['adress'].'"';} ?> required>
        </div>
        <div class="form-group pt-2" >
            <label for="contactForm1">Code Postal</label>
            <input type="number" class="form-control" id="contactForm1" name="zipcode" placeholder="Votre code postal" 
            <?php if(isset($return) && $return === false) {echo 'value="'.$_POST['zipcode'].'"';} ?> required>
        </div>
        <div class="form-group pt-2" >
            <label for="contactForm1">Ville</label>
            <input type="text" class="form-control" id="contactForm1" name="city" placeholder="Votre ville" 
            <?php if(isset($return) && $return === false) {echo 'value="'.$_POST['city'].'"';} ?> required>
        </div>
        <div class="form-group pt-2">
            <label for="contactForm1">Votre email</label>
            <input type="email" class="form-control" id="contactForm1" name="email" 
            <?php if(isset($return) && $return === false) {echo 'value="'.$_POST['email'].'"';} ?> required>
        </div>
        <div class="form-group pt-2">
            <label for="contactForm1">Votre date de naissance</label> 
            <input type="date" class="form-control" id="contactForm1" name="date_of_birth" 
            <?php if(isset($return) && $return === false) {echo 'value="'.$_POST['dat_of_birth'].'"';} ?> required >
        </div>    
        <div class="form-group pt-2">
            <label for="contactForm1">Votre mot de passe</label> 
            <input type="password" class="form-control" id="contactForm1" name="password" >
            <label class="text-little" for="">Doit contenir au moins 8 caractères avec MAJ min et chiffre</label>
        </div> 
        <div class="form-group pt-2">
            <label for="contactForm1">Vérifier votre mot de passe</label> 
            <input type="password" class="form-control" id="contactForm1" name="password2" >
        </div> 
        <div class="form-group pt-2">
            <button class="btn btn-success" type="submit">Enregistrer</button>
            
        </div>
    </form>
</div>
