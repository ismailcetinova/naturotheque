<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/admin.css" rel="stylesheet">
    <link href="../assets/css/view.css" rel="stylesheet">
    <title>page admin</title>
</head>
</html>

<body>
<div class="boite">
    
            <form method="POST" action="index.php">
                <input type="hidden" name="action" value="searchUser" />
                <!-- Recherche -->
                <div class="recherche">
                    Rechercher un utilisateur par son pseudonyme ou email :
                    <input type="text" name="pseudonyme_email" class="search" id="recherche" required placeholder="Pseudonyme ou email">
                    <input type="submit" value="OK">
                </div>
            </form>
            <form method="POST" action="index.php">
                <input type="hidden" name="action" value="searchNaturothequeAdmin" />
                <!-- Recherche -->
                <div class="recherche">
                    Rechercher une naturothèque par son titre :
                    <input type="text" name="titrenaturotheque" class="search" id="recherche" required placeholder="Titre de la naturothèque">
                    <input type="submit" value="OK">
                </div>
            </form>
            <?php
            if(isset($tabuser)){
    echo "<h3>Utilisateur trouvé : </h3><hr>";
    foreach($tabuser as $user){
    echo $user;
    echo '<p><a href="index.php?action=supprimerCompteAdmin&pseudonyme=' . $user->getPseudonyme() . '"> Supprimer utilisateur</a></p>';
}
}

if(isset($tabnaturotheque)){
    echo "<h3>Naturothèque trouvé : </h3><hr>";
    foreach($tabnaturotheque as $naturotheque){
    echo $naturotheque;
    echo '<p><a href="index.php?action=PageModifierNaturothequeAdmin&naturothequeid=' . $naturotheque->getId() . '"> Modifier la naturothèque</a></p>';
    echo '<p><a href="index.php?action=supprimerNaturothequeAdmin&naturothequeid=' . $naturotheque->getId() . '"> Supprimer la naturothèque</a></p>';
}
}
?>