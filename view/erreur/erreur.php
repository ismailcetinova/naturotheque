<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/css/erreur.css">
    <link rel="stylesheet" href="../assets/css/view.css">
    <title>Erreur</title>
</head>
<body>
    <div class="main">
        <h2>Erreur - Page non trouvée</h2><hr>

        <p>Désolé, la page que vous recherchez n'a pas été trouvée.</p>
        <?php echo "Erreur : " . $errorMessage; ?><br>
        <b><a href="frontController.php?action=accueil" class="retour">Retour à la page d'accueil</a></b>
    </div>
</body>
</html>