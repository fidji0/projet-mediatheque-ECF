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

            $firstname = htmlspecialchars($_POST['firstname']);
            $surname = htmlspecialchars($_POST['surname']);
            $email = htmlspecialchars($_POST['email']);
            $date_of_birth = htmlspecialchars($_POST['date_of_birth']);
            $password = password_hash(htmlspecialchars($_POST['password']),PASSWORD_BCRYPT);      
            
            
            if($firstname && $surname && $email && $date_of_birth && $password){
            
                try{ 
                    $addUser = "INSERT INTO habitant (firstname , surname, email , date_of_birth, password) VALUES ('$firstname' , '$surname', '$email', '$date_of_birth', '$password') ";
                    $pdo->prepare($addUser)->execute();
                    echo '<div class="alert alert-success" role="alert">
                            Votre demande à bien été transmise vous allez recevoir un mail de confirmation.
                            Merci de cliquer sur le lien afin de valider votre adresse email.
                        </div>';

                    mail_confirm();
                    }
                catch(PDOException $e){
                    if($e->errorInfo[0] === '23000'){
                        echo '<div class="alert alert-danger" role="alert">
                        L\'adresse email est déja utilisé!
                        </div>';
                    }
                }
            }
            else{
                echo 'merci de remplir tout les champs';
            }
        }else{
            echo '<div class="alert alert-danger" role="alert">
            Le mot de passe doit contenir au minimum 8 caractères avec MAJUSCULE, minuscule et Chiffre!
            </div>';
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
            $requete = "SELECT * FROM habitant WHERE email = '$email'";
            $result = $pdo->query($requete)->fetch((PDO::FETCH_ASSOC));
            
            // On vérifie que la BDD nous retourne bien un résultat et que l'email est bien identique
            if(isset($result) && $email === $result['email']){
                
                //On vérifie que l'utilisateur à été validé par un employer
                if ($result[0]['validity'] !== '0'){
                    
                    //On Verifie que le mot de passe correspond au hash
                    if(password_verify($password, $result['password'])){

                        if ($result['role'] === 'habitant'){
                            header('Location: ./connectedUser.php');

                        }elseif($result['role'] === 'employer'){
                            header('Location: ./employerDashboard.php');

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
        'From' => htmlspecialchars($_POST['email']),
        'Reply-To' => htmlspecialchars($_POST['email']) ,
        'Content-type' => 'text/html; charset=iso-8859-1'       
    );

    mail($to, $subject, $message, $header);
    echo $message;
}
