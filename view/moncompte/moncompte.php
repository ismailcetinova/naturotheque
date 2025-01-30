<!DOCTYPE html>
<html>
    <head>
        <title>Mon compte</title>
        <meta charset="utf-8">
        <link href="../assets/css/moncompte.css" rel="stylesheet">
        <link href="../assets/css/view.css" rel="stylesheet">
    </head>
    <body>
        <div class="rectangle-blanc">
            <?php
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
            }
            date_default_timezone_set('Europe/Paris');
            $date = date('j F Y');
            $heure = date('H\hi');
            echo "Bonjour " .  $utilisateur->getPseudonyme() . " nous sommes le " . $date . " et il est " . $heure . " Que voulez-vous faire aujourd'hui ? ";
            ?>
            <h2>Modifier mon compte</h2>
            <hr>
            <h4> Vous êtes membre depuis le : <?php echo $utilisateur->getDate_Creation(); ?></h4>
            <form method="post" name="modificationCompte" action="index.php">
                <input type="hidden" name="action" value="UpdateUser" />
                <div>
                    <label for="pseudonyme">Modifier le pseudonyme :</label>
                    <input type="text" class="input" value="<?php echo $utilisateur->getPseudonyme() ?>" name="pseudonyme" required>
                </div>
                <div>
                    <label for="email">Modifier l'adresse mail :</label>
                    <input type="email" class="input" value="<?php echo $utilisateur->getEmail() ?>" name="email" required autocomplete="email">
                </div>
                <div>
                    <label for="oldpassword">Ancien mot de passe :</label>
                    <input type="password" name="oldpassword" id="oldpassword" placeholder="Ancien mot de passe" minlength="8" class="input">
                </div>
                <div>
                    <label for="password">Modifier le mot de passe :</label>
                    <input type="password" name="password" id="password" placeholder="Nouveau mot de passe" minlength="8" class="input">
                </div>
                <div>
                    <label for="cpassword">Confirmer le mot de passe :</label>
                    <input type="password" name="cpassword" id="cpassword" placeholder="Confirmez votre Mot de passe" minlength="8" class="input">
                </div>
                <br>
                <input type="submit" name="modificationCompte" id="modificationCompte" value="Enregistrer">
            </form>
            <br>
            <hr>
            <br>
            <div>
                <a href="index.php?action=statistiques" class="statistiques">Mes statistiques</a>
                <a href="#popupSupprimerCompte" class="supp">Supprimer mon compte</a>
                <div id="popupSupprimerCompte" class="popup">
                    <div class="pop">
                        <h1>Supprimer mon compte</h1>
                        <p>Êtes-vous sûr de vouloir supprimer votre compte? Cette action est irréversible.</p>
                        <form method="POST" action="frontController.php?action=supprimerCompte">
                            <b><a href="frontController.php?action=supprimerCompte" class="supprimer">Supprimer mon compte</a></b>
                        </form>
                        <hr>
                        <b><a href="#" class="fermer-popup">Fermer</a></b>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>