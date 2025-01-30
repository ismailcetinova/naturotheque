<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de la naturothèque</title>
    <style>
        html {
            background: hsl(250, 11%, 78%) url('<?php echo $naturotheque->getImageFond(); ?>');
            background-size: cover;
            background-position: center;
        }
    </style>
    <link rel="stylesheet" href="../assets/css/detailsnaturotheque.css">
    <link rel="stylesheet" href="../assets/css/view.css">
</head>
<body>
    <section id="main">
        <div class="titleBox">
            <h1>Détail de la naturothèque</h1>
            <p>Voici les détails de la naturothèque</p>
        </div>

        <?php
        $verification = null;
        if (isset($_SESSION["utilisateur"])){
            $utilisateur = $_SESSION["utilisateur"];
            $verification = $naturotheque->verifyUserNaturotheque($utilisateur->getPseudonyme());
        }
        echo $naturotheque;
        ?>
        
        <div class="naturothequeContainer">
            <div class="scrollContainer">
                <div class="taxonContainer" id="taxonContainer">
                    <?php foreach($tabtaxon as $taxon): ?>
                        <div class="taxonBlock">
                            <?php echo "<p><a href='index.php?action=details&taxon=" . $taxon->getId() . "'>" . $taxon->getScientificName() . "</a></p>"; ?>
                            <?php 
                            $images = $taxon->getMediaImage();
                            if (!empty($images)): ?>
                                <?php echo "<img src='" . $images[0] . "' alt='" . $taxon->getFrenchVernacularName() . "'>"; ?>
                            <?php endif;
                        if($verification){
                        echo "<p><a href='index.php?action=SupprimerTaxonNaturotheque&naturothequeid={$naturotheque->getId()}&taxonid={$taxon->getId()}'> Supprimer le taxon de la naturothèque ! </a></p>";   }
                        ?>
                      </div>
                    <?php endforeach; ?>
                    <div class="actionButtons">
                            <hr>
                            <br>
                            <?php if($verification){?>
                            <a href="index.php?action=PageModifierNaturotheque&naturothequeid=<?= $naturotheque->getId() ?>" class="btn">Modifier la naturothèque</a>
                            <hr>

                            <a href="index.php?action=SupprimerNaturotheque&naturothequeid=<?= $naturotheque->getId() ?>" class="btn-danger">Supprimer la naturothèque</a>
                            <?php }?>
                        <div class="returnButton">
                            <a href="index.php?action=naturotheque" class="btn">Retour</a>
                        </div>
                    </div>
                </div>
                <button class="scrollButton left" onclick="scrollHorizontal(-1)">&lt;</button>
                <button class="scrollButton right" onclick="scrollHorizontal(1)">&gt;</button>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const taxonBlocks = document.querySelectorAll('.taxonBlock');
        let currentIndex = 0;

        function showTaxon(index) {
            taxonBlocks.forEach((block, i) => {
                block.style.display = (i === index) ? 'block' : 'none';
            });
        }

        document.querySelector('.scrollButton.left').addEventListener('click', () => {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : taxonBlocks.length - 1;
            showTaxon(currentIndex);
        });

        document.querySelector('.scrollButton.right').addEventListener('click', () => {
            currentIndex = (currentIndex < taxonBlocks.length - 1) ? currentIndex + 1 : 0;
            showTaxon(currentIndex);
        });

        showTaxon(currentIndex);
    });
    </script>
</body>
</html>