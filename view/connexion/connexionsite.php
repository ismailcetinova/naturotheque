<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connectez-vous !</title>
        <link href="../assets/css/inscription.css" rel="stylesheet">
        <link href="../assets/css/view.css" rel="stylesheet">

     </head>
    <body>
        <form method="post" class="connexion-form" name="connexion" action="index.php">
        <input type="hidden" name="action" value="connexion" />
        <section id="main">
            <div class="titleBox">
                <h1>De retour ?</h1>
                <p>connectez-vous</p>
                <!--insertion du logo-->
                <img src="../assets/nat.png" style="width:20%"> 
              
            </div>

            <div class="inputBox">
                <!-- Ajout de l'attribsut name et type -->
                <input type="text" class="input" name="email_pseudonyme">
                <p class="inputName">E-MAIL/PSEUDONYME</p>
            </div>

            <div class="inputBox">
                <!-- Ajout de l'attribut name et type -->
                <input type="password" class="input" name="password">
                <p class="inputName">MOT DE PASSE</p>
            </div>
            <!-- Ajout de l'attribut value -->
            <input type="submit" class="btn" name="connexion" id="connexion" value="SE CONNECTER">
            <br>

            <div class="switchPage">
                <br>
                <p>vous n'avez pas de compte ?</p>
                <a href="index.php?action=pageinscription">inscrivez-vous</a>
            </div>
            
        </section>
        </form>
       
</body>

</html>