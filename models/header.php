<header >
    <div class="flexboxheader">
        <a href="./"><h1>MEDIATHEQUE DE La Chapelle-Curreaux</h1></a>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-navbar ">
        
        <button class="navbar-toggler mx-3" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto align-center">
            <li class="nav-item active">
                <a class="nav-link" href="./">Accueil</a>
            </li>
            <?php
            if(!empty($_SESSION['email']) && $_SESSION['role'] === 'employer'){
                echo '<li class="nav-item">
                        <a class="nav-link" href="./employerDashboard.php">Tableau de bord</a>
                    </li>';
                echo '<li class="nav-item">
                        <a class="nav-link" href="./connectedUser.php">Chercher un livre</a>
                    </li>';
            }elseif(!empty($_SESSION['email']) && $_SESSION['role'] === 'habitant'){
                echo '<li class="nav-item">
                        <a class="nav-link" href="./connectedUser.php">Chercher un livre</a>
                    </li>';
            }
            if(empty($_SESSION['email'])){
                echo '<li class="nav-item">
                        <a class="nav-link" href="./connect.php">Se connecter</a>
                    </li>';
            }else{
                echo '<li class="nav-item">
                <a class="nav-link" href="./index.php?disconnected=1">Se deconnecter</a>
            </li>';
            
            }
        ?>
            </ul>
            
        </div>
    </nav>
    
</header>