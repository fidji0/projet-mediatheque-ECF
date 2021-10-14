<div class="form-perso">
<?php
    require_once "./controllers/functionAuthenticate.php";
    authenticateUser($pdo);
?>
    <form action="./connect.php" method="post" >
      
        <div class="form-group pt-2">
            <label for="contactForm1">Votre email :</label>
            <input type="email" class="form-control" id="contactForm1" name="email" required>
        </div>
        
        <div class="form-group pt-2">
            <label for="contactForm1">Votre mot de passe :</label> 
            <input type="password" class="form-control" id="afPassword" name="password" required >
            <input type="checkbox" onclick="Afficher()"><label for="">Afficher le mot de passe</label>
        </div> 
        <div class="form-group pt-2">
            <button class="btn btn-success" type="submit">Se connecter</button>
        </div>
        <div class="form-group pt-2">
            <a  href="./inscription.php"><i> Pas encore inscrit ?</i></a>
        </div>
    </form>
</div>
