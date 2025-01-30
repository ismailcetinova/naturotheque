<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="../assets/css/creenaturotheque.css" rel="stylesheet">
    <link href="../assets/css/view.css" rel="stylesheet">

</head>
<body>
    <div class='rectangle-blanc'>
    <form method="POST" class="connexion-form" name="CreerNaturotheque" action="index.php"  enctype="multipart/form-data">
        <input type="hidden" name="action" value="CreerNaturotheque"/>
        <label for="Titre">Donner un titre à la naturothèque: </label>
        <input type="text" id="titre" name="titre" required>
        <label for="Description">Description de la naturothèque: </label>
        <input type="textarea" id="description" name="description" required>
        <label for="categories">Ajouter des catégories : </label>
        <input type="text" id="categories" name="categories" required>
        <label for="categories">Naturothèque privé ? </label>
        <input type="checkbox" id="prive" name="prive">
        <label for="image">Image de fond ? (Facultatif sous format PNG / JPEG)</label>
        <input type="file" name="image_fond" />
        <input type="submit" class="btn" name="create" id="create" value="Créer la naturothèque">
    </form>
</div>
    </body>
    </html>