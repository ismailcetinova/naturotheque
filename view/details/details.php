<!DOCTYPE html>
<html>
<head>
    <link href="../assets/css/details.css" rel="stylesheet">
    <link href="../assets/css/view.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
    <body>
    <div class="info"> <h3>Information sur le taxon : </h3><hr>
            <?php
        if (isset($_SESSION["utilisateur"])){
            $utilisateur = $_SESSION["utilisateur"];
        }
        $_SESSION['taxon'] = $taxon;
        echo $taxon;
        ?>
        <?php
        if ($utilisateur){
            if (!empty($tabnaturotheque)){
        ?></div><!-- fin de la section info-->

        <div class="ajouter"><!-- Début section ajouter-->

        <form action="index.php" method="GET">
        <label for="naturotheque">Sélectionner la naturothèque:</label>
        <select name="naturotheque" id="naturotheque">
        <?php
            foreach($tabnaturotheque as $naturotheque){
                echo '<option value="' . $naturotheque->getId() . '">' . $naturotheque->getTitre() . '</option>';
            }
        ?>
        </select>
        <input type="hidden" name="action" value="ajouterTaxonNaturotheque">
        <input type="hidden" name="taxon" value="<?php echo $taxon->getId(); ?>">
        <input type="submit" value="Ajouter à la naturothèque">
        </form>
<?php
            }
        }
?>
 </div><!-- Fin de la section Ajouter -->
 <div class="info">
 <?php
if (!empty($taxon->getMediaImage())){
    echo '<h4>'. $taxon->getScientificName().'</h4>';
    echo '<div id="imageCarousel">';
    foreach($taxon->getMediaImage() as $media_image){
        echo "<img class='animal-image carousel-image' src='" . $media_image . "' alt='test' style='display: none'>";
    }
    echo '</div>';
}
?></div>

        <div id="map" style="height: 500px;"></div>
        
        <script>
    var map = L.map('map',{minZoom:2}).setView([51.505, -0.09], 3);

    L.tileLayer('https://tile.gbif.org/3857/omt/{z}/{x}/{y}@1x.png', {
        maxZoom: 19,
        attribution: '&copy; GBIF',
        noWrap:false
    }).addTo(map);

    // Add a PNG image overlay
    var imageUrl = "https://api.gbif.org/v2/map/occurrence/density/0/0/0%404x.png?srs=EPSG%3A3857&hexPerTile=1001&squareSize=4096&taxonKey=<?= $taxon->getGBIF() ?>";
    var imageBounds = [[-90, -180], [90, 180]]; // Replace with actual coordinates
    L.imageOverlay(imageUrl, imageBounds,{opacity:0.7}).addTo(map);
</script>
<div class="center">
    <a href="frontController.php?action=recherche" class="btn">Retour</a>
</div>
<script>
$(document).ready(function() {
    var currentImageIndex = 0;
    var images = $('.carousel-image'); // Obtenez toutes les images
    images.eq(currentImageIndex).show(); // Affichez la première image

    setInterval(function() {
        images.eq(currentImageIndex).hide(); // Cachez l'image actuelle
        currentImageIndex = (currentImageIndex + 1) % images.length; // Passez à l'image suivante
        images.eq(currentImageIndex).show(); // Affichez la nouvelle image
    }, 3000); // Changez d'image toutes les 3 secondes
});
</script>
    </body>
</html>