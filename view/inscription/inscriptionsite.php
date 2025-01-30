<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inscription</title>
        <link rel="stylesheet" href="../assets/css/inscription.css">
        <link rel="stylesheet" href="../assets/css/view.css">

    </head>
<body>
<form method="post" name="inscription" action="index.php">
<input type="hidden" name="action" value="inscription" />
    <section id="main">
        <div class="titleBox">
            <h1>INSCRIPTION</h1>
            <p>Veuillez remplir le formulaire ci-dessous</p>
            <!--insertion du logo-->
            <img src="../assets/nat.png" style="width:20%">        </div>

            <div class="inputBox">
                <!-- Ajout de l'attribut name et type -->
                <input type="text" class="input" placeholder="Votre pseudonyme" name="pseudonyme" required>
                <p class="inputName">PSEUDONYME</p>
            </div>


            <div class="inputBox">
                <!-- Ajout de l'attribut name et type -->
                <input type="email" class="input" placeholder="Votre Email" name="email" required autocomplete="email">
                <p class="inputName">E-MAIL</p>
            </div>


            <div class="inputBox">
                <!-- Ajout de l'attribut name et type -->
                <input type="password" name="password" id="password" placeholder="Votre Mot de passe" required minlength="8" class="input">
                <p class="inputName">MOT DE PASSE</p>
            </div>


            <div class="inputBox">
                <input type="password" name="cpassword" id="cpassword" placeholder="Confirmez votre Mot de passe" required minlength="8" class="input">
                <p class="inputName"> CONFIRMER MOT DE PASSE</p>
            </div>

            <div class="inputBox">
                <input type="checkbox" id="accepter_conditions" name="accepter_conditions" required>
                <label for="accepter_conditions">J'accepte les <a href="../assets/condition générale.pdf" target="_blank">conditions générales</a> de l'application</label>
            </div>



            <input type="submit" name="inscription" id="inscription" value="S'INSCRIRE" class="btn">
       
    
        <div class="switchPage">
            <p>Déjà inscrit ?</p>
            <a href="index.php?action=pageConnexion">Se connecter</a>
        </div>
     </section>
    </form>
</body>
</html>