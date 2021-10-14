<?php
function check_mdp_format($mdp)
{
	$majuscule = preg_match('@[A-Z]@', $mdp);
	$minuscule = preg_match('@[a-z]@', $mdp);
	$chiffre = preg_match('@[0-9]@', $mdp);
	
	if(!$majuscule || !$minuscule || !$chiffre || strlen($mdp) < 8)
	{
		return false;
	}
	else 
		return true;
}



function createUser($pdo){
    check_mdp_format($_POST['password']);


    if (!empty($_POST)){
        
        if(check_mdp_format($_POST['password'])){
            if($_POST['password'] === $_POST['password2']){
                $firstname = htmlspecialchars($_POST['firstname']);
                $surname = htmlspecialchars($_POST['surname']);
                $email = htmlspecialchars($_POST['email']);
                $adress = htmlspecialchars($_POST['adress']);
                $zipcode = htmlspecialchars($_POST['zipcode']);
                $city = htmlspecialchars($_POST['city']);
                $date_of_birth = htmlspecialchars($_POST['date_of_birth']);
                $password = password_hash(htmlspecialchars($_POST['password']),PASSWORD_BCRYPT);      
                
                
                if($firstname && $surname && $email && $date_of_birth && $password){
                
                    try{ 
                        $addUser = "INSERT INTO habitant (firstname , surname, adress, zipcode, city, email , date_of_birth, password) VALUES ('$firstname' , '$surname', '$adress', '$zipcode', '$city' , '$email', '$date_of_birth', '$password') ";
                        $pdo->prepare($addUser)->execute();
                        echo '<div class="alert alert-success" role="alert">
                                Votre demande à bien été transmise vous allez recevoir un mail de confirmation.
                                Merci de cliquer sur le lien afin de valider votre adresse email.
                            </div>';
                            

                        mail_confirm();
                        return true;
                        }
                    catch(PDOException $e){
                        if($e->errorInfo[0] === '23000'){
                            echo '<div class="alert alert-danger" role="alert">
                            L\'adresse email est déja utilisé!
                            </div>';
                            return false;
                        }
                    }
                }
                else{
                    echo 'merci de remplir tout les champs';
                    return false;
                }
            }else{
                echo '<div class="alert alert-danger" role="alert">
            Vous n\'avez pas saisie deux fois le même mot de passe
            </div>';
            return false;
            }
        }else{
            echo '<div class="alert alert-danger" role="alert">
            Le mot de passe doit contenir au minimum 8 caractères avec MAJUSCULE, minuscule et Chiffre!
            </div>';
            return false;
        }
    }
}

// Je verifie la connection en récupérant les données du formulaire
function authenticateUser($pdo){
    if(!empty($_POST)){

        // On récupère les valeurs du formulaire en se protégeant des injection SSL
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        
        // On verifie qu'un mot de passe et un identifiant ont été saisie
        if($email !== "" && $password !== ""){

            //On lance la requete et on récupère la ligne de la base de donnée
            $requete = "SELECT * FROM habitant WHERE email = ?";
            $r = $pdo->prepare($requete);
            $r->execute(array($email));
            $result = $r->fetch(PDO::FETCH_ASSOC);
            
            // On vérifie que la BDD nous retourne bien un résultat et que l'email est bien identique
            if(isset($result) && $email === $result['email']){
                
                //On vérifie que l'utilisateur à été validé par un employer
                if ($result['validity'] !== '0'){
                    
                    //On Verifie que le mot de passe correspond au hash
                    if(password_verify($password, $result['password'])){

                        if ($result['role'] === 'habitant'){
                            header('Location: ./connectedUser.php');
                            echo 'RedirectionUser()';

                        }elseif($result['role'] === 'employer'){
                            header('Location: ./employerDashboard.php');
                            echo '<script>RedirectionEmployer()</script> ';
                        }else{
                            echo '<div class="alert alert-danger" role="alert">
                                Merci de contacter le webmaster
                            </div>';
                        }
                        // Injecte les donnée d'utilisateur dans la session
                        foreach($result as $key=> $res){
                            
                            // On enlève la valeur password de la session
                            if($key !== 'password'){
                                $_SESSION[$key]= $res;
                            }
                        }
                        
                    // Affiche si le mot de passe est incorect
                    }else{
                        echo '<div class="alert alert-danger" role="alert">
                        L\'adresse email et/ou le mot de passe est invalide!
                    </div>';
                    }

                // Affiche si le compte n'est pas encore validé
                }else{
                    echo '<div class="alert alert-danger" role="alert">
                    Votre compte n\'a pas encore été validé!
                    </div>';
                }

            // Affiche si l'email saisie est incorect
            }else{
                echo '<div class="alert alert-danger" role="alert">
                L\'adresse email et/ou le mot de passe est invalide!
            </div>';}
        } 
    }
    
}

function valid_email($pdo){
    if(isset($_GET['email'])){

        $email = $_GET['email'];

        $request = "UPDATE habitant
        SET verify_email = '1'
        WHERE email = '$email'";

        $pdo->query($request);
    }else{

    }
}
function mail_confirm(){
    
    $to = htmlspecialchars($_POST['email']);
    $subject = 'Confirmation de votre adresse mail';
    $message = '<h2>Confirmation adresse email mediatheque</h2><br><br>
    <p>Bonjour<br><br>
    Nous avons le plaisir de vous annoncer que nous avons bien reçu votre demande d\'inscription<br><br>
    Nous vous remercions de bien vouloir valider votre adresse email en cliquant sur le lien ci-dessous <br><br>
    <a href="www.mediatheque.av-developpeur.fr/inscription.php?email='.$_POST['email'].'">Merci de valider votre email</a>';
    
    

   
    $header = array(
        'From' => 'mediatheque@noresponse.fr',
        'Reply-To' => htmlspecialchars($_POST['email']) ,
        'Content-type' => 'text/html; charset=iso-8859-1'       
    );

    mail($to, $subject, $message, $header);
    echo $message;
}

// on cherche a valider le compte de l'utilisateur manuelement
function validity_accept($pdo){
    
    if(!empty($_GET)){
        $email = $_GET['email'];
        $request= "UPDATE habitant SET validity = '1' WHERE email = ?";
        $r = $pdo->prepare($request);
        $r->execute(array($email));
        
    }
}
function en_attente($pdo){
    $request = 'SELECT surname , firstname , email , adress ,  city , zipcode FROM habitant WHERE validity = 0 AND verify_email = 1';
    $r = $pdo->query($request);
    $r->execute();
    $results = $r->fetchAll(PDO::FETCH_ASSOC);

    
    
    $count = count($results);

    echo $count;
}
function verify_validity($pdo){
    validity_accept($pdo);
    $request = 'SELECT surname , firstname , email , adress ,  city , zipcode FROM habitant WHERE validity = 0 AND verify_email = 1';
    $r = $pdo->query($request);
    $r->execute();
    $results = $r->fetchAll(PDO::FETCH_ASSOC);

    
    
    $count = count($results);
    if ($count > 0){
            echo '<div class="madiv">';
        for($i = 0 ; $i < $count ; $i++){
            
            echo '<div class="validity_habitant" >';
            foreach($results[$i] as $key => $result){
                switch ($key){
                    case 'surname':
                        echo '<div><p> Nom : '.$result.'</p></div>';
                        break;
                    case 'firstaname':
                        echo '<div><p> Prenom : '.$result.'</p></div>';
                        break;
                    case 'email':
                        echo '<div><p> Email : '.$result.'</p></div>';
                        break;
                    case 'adress':
                        echo '<div><p> Adresse : '.$result.'</p></div>';
                        break;
                    case 'city':
                        echo '<div><p> Ville : '.$result.'</p></div>';
                        break;
                    case 'zipcode':
                        echo '<div><p> Code Postal : '.$result.'</p></div>';
                        break;
                }
            }
            
            echo '<form action="./employerDashboard.php" method="get" >
            <input type="text" style="display : none;" name="email" value="'.$results[$i]['email'].'">
            <input type="text" style="display : none;" name="attente" value="1">
            <div>
            <button class="btn btn-success" type="submit" name="validity" value="1">Valider l\'utilisateur</button>
            </div>
            </form>';
            echo '</div>';
        }
        echo '</div>';
    }
}
