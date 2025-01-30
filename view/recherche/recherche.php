<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="../assets/css/recherche.css" rel="stylesheet">
    <link href="../assets/css/view.css" rel="stylesheet">

</head>
<body>
<div class="boite">
            <form method="POST" action="index.php">
                <input type="hidden" name="action" value="search" />
                <!-- Recherche -->
                <div class="recherche">
                    <input type="text" name="recherche" class="search" id="recherche" required placeholder="Chercher un taxon">
                    <input type="submit" value="OK">
                </div>

                <!-- Choix type -->
                <fieldset class="type">
                <div>
                        <label for="choix_id">Recherche par ID</label>
                        <input type="radio" name="choix" value="id" id="choix_id" required="required" />
                    </div>
                    <div>
                        <label for="choix_sci">Nom scientifique</label>
                        <input type="radio" name="choix" value="scientificNames" id="choix_sci" />
                    </div>
                    <div>
                        <label for="choix_fr">Nom français</label>
                        <input type="radio" name="choix" value="frenchVernacularNames" id="choix_fr" />
                    </div>
                    <div>
                        <label for="choix_en">Nom anglais</label>
                        <input type="radio" name="choix" value="englishVernacularNames" id="choix_en" />
                    </div>
                </fieldset>
            </form>

<?php
use App\SAE\Model\TaxRefAPI;
use App\SAE\Model\Utilisateur;
?>

<!-- Début de la section Animaux -->
<div class="animaux">
    <?php
    if (!empty($searchResults)) {
        foreach ($searchResults as $taxon) {
            ?>
            <!-- Début de la section Animal -->
            <div class="animal">
                <p><a href="index.php?action=details&taxon=<?= $taxon->getId(); ?>"><?= $taxon->getScientificName(); ?></a></p>
                <?php
                if(isset($taxon->getMediaImage()[0])){
                    echo '<img class="animal-image" src="' . $taxon->getMediaImage()[0] . '" alt="test">';
                }else{
                    echo '<img class="animal-image" src=" https://www.shutterstock.com/image-vector/default-ui-image-placeholder-wireframes-600nw-1037719192.jpg" alt="test">';
                }
              
    
                ?>
            </div>
            <br>
            <!-- Fin de la section Animal -->
            <?php
        }
    }
    ?>

</div>
<!-- Fin de la section Animaux -->
</div>
</div><!-- Fin de la section Animaux -->
</div><!-- Fin de la section boite -->

</body>
</html>