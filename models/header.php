<?php
    require_once "./controllers/Reservation.php";
    $reservation = new Reservation();
?>
<header >
    <div class="flexboxheader">
        <a href="./"><h1 class="h1Tittle">Médiatheque de La Chapelle-Curreaux</h1></a>
    </div>
    
        
    
    <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-navbar ">
    
        <button class="navbar-toggler bg-light mx-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        

        <div class="collapse navbar-collapse mx-3" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            
            <?php
            echo '<li class="nav-item active">
                    <a class="nav-link  ';
                    if ($page === 1){
                        echo ' active ';
                    }    
                    echo '" href="./">Accueil</a>
                </li>';
            if(!empty($_SESSION['email']) && $_SESSION['role'] === 'employer'){
                echo '<li class="nav-item">
                        <a class="nav-link ';
                        if ($page === 2){
                            echo ' active ';
                        }    
                        echo '" href="./employerDashboard.php">Tableau de bord</a>
                    </li>';

                echo '<li class="nav-item">
                        <a class="nav-link ';
                if ($page === 3){
                    echo ' active ';
                }    
                echo '" href="./connectedUser.php">Chercher un livre</a>
                    </li>';
            }elseif(!empty($_SESSION['email']) && $_SESSION['role'] === 'habitant'){
                echo '<li class="nav-item">
                        <a class="nav-link" href="./connectedUser.php">Chercher un livre</a>
                    </li>';
            }
           
        ?>
        
            
            
        
        <?php
        
        //echo '<ul class="navbar-nav mr-auto">';
         if(empty($_SESSION['email'])){
            echo '<li class="nav-item active">
                    <a class="nav-link ';
                if ($page === 6){
                    echo ' active ';
                }    
                echo '" href="./connect.php">Se connecter</a>
                </li>';
        }else{
            echo '<li class="nav-item active"><a class="nav-link  ';
            if ($page === 5){
                echo ' active ';
            }    
            echo '" href="./moncompte.php">Mon compte</a></li>
            <li class="nav-item active">
            <a class="nav-link" href="./index.php?disconnected=1">Se deconnecter</a></li>
        ';
        
        }
        
        ?>
          </ul>
            </div>
    </nav>
    </div>
    
            <div>
                
            </div>
            <div style="color : red;">
            <?php
               $reservation->habitant_retard_notif($pdo); 
            ?>

        </div>
    <?php
    if($title !== 'Tableau de bord employé'){
        ?>
    <div>
        <img class="img_header" src="./vues/Img/livre.png" alt="">
    </div>
    <?php } ?>
    
</header>