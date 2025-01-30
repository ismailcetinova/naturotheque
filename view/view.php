<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $pagetitle; ?></title>
        <link href="../../assets/css/view.css" rel="stylesheet">
    </head>
    <?php
    $utilisateur = null;
    if (isset($_SESSION["utilisateur"])){
        $utilisateur = $_SESSION["utilisateur"];
    }
    ?>
    <body>
    <div class="container">
        <header class="<?php echo $utilisateur && $utilisateur->getAdmin() == 1 ? 'admin-header' : ''; ?>">
            <nav>
            <ul>
    <?php
    echo '<li><a href="index.php?action=recherche">Recherche</a></li>';
    echo '<li><a href="index.php?action=naturotheque">Naturotheque</a></li>';
    
    if($utilisateur == null){
        echo '<li><a href="index.php?action=pageConnexion">Se connecter</a></li>';
    } else {
        echo '<li><a href="index.php?action=moncompte">Mon compte</a></li>';
        echo '<li><a href="index.php?action=deconnexion">Se déconnecter</a></li>';
        
        if ($utilisateur->getAdmin() == 1){
            echo '<li><a href="index.php?action=pageadministration">Page administration</a></li>';
        }
    }
    ?>
</ul>

            </nav>
        </header>
    <main>
        <?php
            require __DIR__ . "/{$cheminVueBody}";
        ?>
    </main>
    </div>
    
    <footer>
    <p>
        Projet réalisé par : RUHAULT Léna, MAKSIMOUS Thomas, CETINOVA Ismail, HUSSAIN Tania et BARERA Lucie  - Université Paris Est Créteil - 
    </p>
    </footer>
    </body>
</html>