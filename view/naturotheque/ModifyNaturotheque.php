<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="../assets/css/creenaturotheque.css" rel="stylesheet">
    <link href="../assets/css/view.css" rel="stylesheet">

</head>
<body>
    <div class='rectangle-blanc'>
    <form method="POST" class="connexion-form" name="ModifierNaturotheque" action="index.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ModifierNaturotheque"/>
        <input type="hidden" name="naturothequeid" value="<?php echo $naturotheque->getId(); ?>"/>
        <label for="Titre">Titre de la naturothèque: </label>
        <input type="text" id="titre" name="titre" value="<?php  echo $naturotheque->getTitre(); ?>" required>
        <label for="Description">Description de la naturothèque: </label>
        <input type="textarea" id="description" name="description" value="<?php echo $naturotheque->getDescription(); ?>">
        <label for="categories">Catégories de la naturothèque : </label>
        <input type="text" id="categories" name="categories" value="<?php echo $naturotheque->getCategories(); ?>">
        <label for="image">Image de fond ? (Facultatif sous format PNG / JPEG )</label>
        <input type="file" name="image_fond" />
        <label for="categories">Naturothèque privé ? </label>
        <input type="checkbox" id="prive" name="prive" <?php echo $naturotheque->getPrive() ? 'checked' : ''; ?>>
        <input type="submit" class="btn" name="create" id="create" value="Modifier la naturothèque">
    </form>
</div>
    </body>