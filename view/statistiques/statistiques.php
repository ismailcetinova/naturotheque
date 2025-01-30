<?php
    if (isset($_SESSION["utilisateur"])){
        $utilisateur = $_SESSION["utilisateur"];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Mes statistiques</title>
        <meta charset="utf-8">
        <link href="../assets/css/moncompte.css" rel="stylesheet">
        <link href="../assets/css/view.css" rel="stylesheet">
    </head>
    <body>
        <div class="rectangle-blanc">
            <h2>Les 5 derniers taxons vues :</h2><hr>
            <?php
                if(isset($tabtaxon)){
                    foreach($tabtaxon as $taxon){
                        echo '<p><a href="index.php?action=details&taxon=' . $taxon->getId() . '">' . $taxon->getScientificName() . '</a></p>';                if(isset($taxon->getMediaImage()[0])){
                    echo '<img class="animal-image" src="' . $taxon->getMediaImage()[0] . '" alt="test">';
                }else{
                    echo '<img class="animal-image" src=" https://www.shutterstock.com/image-vector/default-ui-image-placeholder-wireframes-600nw-1037719192.jpg" alt="test">';
                }
              
            }
        }
                ?>
        </div>
    </body>
</html>
