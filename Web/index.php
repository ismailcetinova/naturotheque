<?php
require_once __DIR__ .'/../Lib/Psr4AutoloaderClass.php';


error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// instantiate the loader
$loader = new App\SAE\Lib\Psr4AutoloaderClass();
// register the base directories for the namespace prefix
$loader->addNamespace('App\SAE', __DIR__ . '\..');
// register the autoloader
$loader->register();
// On recupère l'action passée dans l'URL
use App\SAE\Web\ControllerPrincipal;

$action = "accueil";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['action'])){
    $action = $_POST['action'];
}
}else if($_SERVER["REQUEST_METHOD"] == "GET"){
if (isset($_GET['action'])){
    $action = $_GET['action'];
}
}

// Appel de la méthode statique $action de ControllerPrincipal
if (method_exists('App\SAE\Web\ControllerPrincipal', $action)) {
    ControllerPrincipal::$action();
}else{
    ControllerPrincipal::error("Page introuvable");
}
?>