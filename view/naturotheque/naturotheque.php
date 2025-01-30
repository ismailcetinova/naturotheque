<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="../assets/css/naturotheque.css" rel="stylesheet">
    <link href="../assets/css/view.css" rel="stylesheet">

</head>
<body>
      <h1>Naturothèques</h1>
      <form method="POST" class="connexion-form" name="CreerNaturotheque" action="index.php"> 
        <input type="hidden" name="action" value="searchNaturotheque" />
        <input type="text" name="titrenaturotheque" class="search" placeholder="Chercher une naturothèque">
        <input type="submit" value="OK">
    </form>
    
    <div class="rectangle-blanc">

<?php
if(isset($tabsearchnaturotheque)){
    echo "<h3>Naturothèque trouvé : </h3><hr>";
    foreach($tabsearchnaturotheque as $naturotheque){
    echo $naturotheque;
    echo '<p><a href="index.php?action=DetailNaturotheque&naturothequeid=' . $naturotheque->getId() . '">' . $naturotheque->getTitre() . '</a></p>';
}
}

if (isset($_SESSION["utilisateur"])){
    $utilisateur = $_SESSION["utilisateur"];
    echo '<h4><a class="create" href="index.php?action=pagecreerNaturotheque"> CREE UNE NATUROTHEQUE</a></h4>';
?>
<div class='list-nat'>

<?php
    if (isset($tabnaturotheque) && !empty($tabnaturotheque)) {
        echo "<h3>Vos naturotheques : </h3><hr>";
        foreach($tabnaturotheque as $naturotheque){
            echo '<p><a href="index.php?action=DetailNaturotheque&naturothequeid=' . $naturotheque->getId() . '">' . $naturotheque->getTitre() . '</a></p>';
        }
    }
}
?>
</div>
</body>            
</html>
