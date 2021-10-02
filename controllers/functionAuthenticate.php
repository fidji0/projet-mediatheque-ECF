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
                            Votre demande à bien été transmise
                        </div>';
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


function authenticateUser($pdo){
    if(!empty($_POST)){

        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        
        if($email !== "" && $password !== ""){
            $requete = "SELECT * FROM habitant WHERE email = '$email'";
            $result = $pdo->query($requete)->fetchAll();
            
            if(isset($result) && $email == $result[0]['email']){
                
                if ($result[0]['validity'] !== '0'){
                    
                    if(password_verify($password, $result[0]['password'])){
                        echo '<div class="alert alert-success" role="alert">
                            Vous êtes connecté.e
                        </div>';
                    
                    }else{
                        echo '<div class="alert alert-danger" role="alert">
                        L\'adresse email et/ou le mot de passe est invalide!
                    </div>';
                    }
                }else{
                    echo '<div class="alert alert-danger" role="alert">
                    Votre compte n\'a pas encore été validé!
                    </div>';
                }

            }else{
                echo '<div class="alert alert-danger" role="alert">
                L\'adresse email et/ou le mot de passe est invalide!
            </div>';}
        } 
    }
}
