<div class="form-perso">
<?php
    require_once "./controllers/functionAuthenticate.php";
    authenticateUser($pdo);
?>
    <form action="./connect.php" method="post" >
      
        <div class="form-group pt-2">
            <label for="contactForm1">Votre email</label>
            <input type="email" class="form-control" id="contactForm1" name="email" required>
        </div>
        
        <div class="form-group pt-2">
            <label for="contactForm1">Votre mot de passe</label> 
            <input type="password" class="form-control" id="contactForm1" name="password" required >
        </div> 
        <div class="form-group pt-2">
            <button class="btn btn-success" type="submit">Se connecter</button>
        </div>
    </form>
</div>
