<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="../assets/css/accueil.css">
    <link rel="stylesheet" href="../assets/css/view.css">
    <link rel="stylesheet" href="../../assets/css/accueil.css">


</head>

<body>

<main>
    <h1>Accueil</h1>
<div class="bonjour">
    <?php
if (isset($_SESSION["utilisateur"])){
    $utilisateur = $_SESSION["utilisateur"];
    echo 'Bonjour ' . $utilisateur->getPseudonyme();
}
?></div>
<div class="intro">
<h2><b>Informations sur l’application :</b></h2>
<h4>Qu’est-ce que la naturothèque ?</h4>
<p>La naturothèque est une base de données ou un système de gestion de l'information qui recense de manière exhaustive toutes les espèces vivantes,<br>
    incluant leurs caractéristiques, leur répartition géographique, leur statut de conservation,<br>
    et d'autres informations pertinentes pour la biodiversité.</p>

<h4>À propos de notre application :</h4>
<p>Ce projet porte sur l’inventaire national du patrimoine naturel (INPN), qui recense et diffuse toutes les informations sur les espèces naturelles. La SAE consiste à reprendre l’idée de l’INPN en créant une application<br>
    qui recense les informations des espèces naturelles, tout en intégrant la gestion de la base de données.<br>
    Il nous est demandé de comprendre tout d’abord le concept de biodiversité, de prendre en compte la situation en France métropolitaine et outre-mer,<br>
    et enfin, d'appréhender les menaces pesant actuellement sur la biodiversité.</p>

<h4>Quels sont nos objectifs ?</h4>
<p>Le but du projet est de concevoir une base de données qui nous permettra de développer une application répondant aux besoins du client.<br>
    Concrètement, il s'agit d'une application qui permettra de manipuler des données relatives à la biodiversité.<br>
    Notre application doit permettre aux visiteurs de devenir utilisateurs via la création d’un compte. Cela nécessite la mise en place d’une table contenant les informations de chaque utilisateur.<br>
    Chaque utilisateur aura sa propre naturothèque, et pourra ainsi chercher des espèces et les ajouter ou les supprimer de ses favoris.</p>

</div>
</main>

</body>
</html>
