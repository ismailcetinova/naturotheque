<?php
    namespace App\SAE\Web;
    use App\SAE\Model\Model; 
    use App\SAE\Model\TaxRefAPI;     
    use App\SAE\Model\Naturotheque;
    use App\SAE\Model\Taxon;
    use App\SAE\Model\Utilisateur;// chargement des modèle
    ini_set('display_errors', 'on');
    session_start();

/**
 * La classe ControllerPrincipal sert de contrôleur principal pour l'application web.
 * Elle gère les différentes actions de l'utilisateur, telles que la connexion, l'inscription,
 * l'affichage de la page d'accueil, la recherche, et la gestion de la naturothèque.
 */

    class ControllerPrincipal {
            /**
     * Affiche la page de recherche de taxons.
     */
        public static function recherche() : void {
            ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Recherche de taxons", "cheminVueBody" => "recherche/recherche.php"]);
        }
            /**
     * Affiche la page de connexion.
     */

        public static function pageconnexion() : void {
            ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Connexion", "cheminVueBody" => "Connexion/connexionsite.php"]);
        }


     /**
     * Traite la connexion de l'utilisateur.
     * Utilise les données POST pour identifier l'utilisateur.
     * En cas de succès, redirige vers la page d'accueil, sinon affiche un message d'erreur.
     */
        public static function connexion() : void {
            if (isset($_POST['connexion'])) {
                // On récupère les données du formulaire
                $email_pseudonyme = $_POST['email_pseudonyme']; // L'adresse email saisie par l'utilisateur
                $password = $_POST['password']; // Le mot de passe saisi par l'utilisateur
                $utilisateur = Utilisateur::connexion($email_pseudonyme, $password);
                        if ($utilisateur){
                            $_SESSION["utilisateur"] = $utilisateur;
                            ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Connexion", "cheminVueBody" => "accueil/accueil.php"]);
                        } else {
                        // On stocke le message d'erreur dans une session
                        $_SESSION['error'] = "Mots de passe incorrect";
                        // On redirige vers la page de connexion
                        ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Connexion", "cheminVueBody" => "Connexion/connexionsite.php"]);

                    }
                }
        }

            /**
     * Affiche la page d'inscription.
     */
        public static function pageinscription() : void {
            ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Inscription", "cheminVueBody" => "inscription/inscriptionsite.php"]);
        }
    /**
     * Traite l'inscription de l'utilisateur.
     * Vérifie les données envoyées via POST, crée un nouvel utilisateur, et l'ajoute à la base de données.
     * En cas de succès, connecte l'utilisateur et redirige vers la page d'accueil.
     */
        public static function inscription() : void {
            $model = Model::getPdo();
            //Vérification de l'envoi du formulaire
        if (isset($_POST['inscription'])) {
            // On récupère les valeurs de $_POST dans les variables correspondantes
            $pseudonyme = htmlspecialchars($_POST['pseudonyme']);
            $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    //Vérification que le formulaire n'est  pas vide
    if (!empty($pseudonyme) && !empty($password) && !empty($cpassword) && !empty($email)) {
        if ($password == $cpassword) {
            //Hachage du mot de passe
            $mdp_hache = password_hash($password, PASSWORD_DEFAULT);
            $date = new \DateTime();
            $date = $date->format('Y-m-d');
            try{
            $utilisateur = new Utilisateur($pseudonyme, $mdp_hache, $email, $date, false);
            $reponse = $utilisateur->inscription();
            if ($reponse == True) {
                $_SESSION["utilisateur"] = $utilisateur;
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Inscription réussi !", "cheminVueBody" => "accueil/accueil.php"]);
            } else {
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Inscription", "cheminVueBody" => "inscription/inscriptionsite.php"]);
                exit;
                }
            }catch(PDOException $e) {
                if ($e->getCode() == '23000'){
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur, pseudonyme ou email déjà utilisé', "cheminVueBody" => "message/message.php", "message" => "Inscription non réussi, pseudonyme ou email déjà utilisé", "fichier" => "../inscription/inscriptionsite.php"]);
                }
                exit;
            }
            } 
        } else {
            // On stocke le message d'erreur dans une session
            session_start();
            $_SESSION['error'] = "Les mots de passe ne correspondent pas. Veuillez réessayer.";
            // On redirige vers la page du formulaire
            ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Inscription", "cheminVueBody" => "inscription/inscriptionsite.php"]);
            exit;
        }
    }
        }
    /**
     * Affiche la page du compte utilisateur.
     * Charge les informations de l'utilisateur connecté depuis la session et les affiche.
     */
        public static function monCompte() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
            }
            ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Compte de " . $utilisateur->getPseudonyme(), "cheminVueBody" => "moncompte/moncompte.php"]);
        }
    /**
     * Affiche une page d'erreur avec un message personnalisé.
     * @param string $errorMessage Le message d'erreur à afficher.
     */
        public static function error(string $errorMessage) : void {
            ControllerPrincipal::afficheVue('view.php', ["errorMessage" => $errorMessage, "pagetitle" => "Erreur", "cheminVueBody" => "erreur/erreur.php"]);
        }
        
    /**
     * Affiche la page d'accueil de l'application.
     * Charge et affiche les informations spécifiques à l'utilisateur si connecté.
     */
        public static function accueil() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
            }
            ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Accueil de Naturotheque", "cheminVueBody" => "accueil/accueil.php"]);
        }


         /**
     * Effectue une recherche de taxons basée sur les critères fournis par l'utilisateur.
     * Utilise l'API TaxRefAPI pour chercher des taxons et affiche les résultats.
     */
        public static function search() : void {
            $ApiTaxa = new TaxRefAPI();
            $taxons_search = $_POST['recherche'];
            $choix = isset($_POST['choix']) ? $_POST['choix'] : "scientificNames";
            if($choix == "id" && is_numeric($taxons_search)){
                $taxons_search = intval($taxons_search);
                $taxon = Taxon::Taxon_by_id($taxons_search);
                if (!empty($taxon)){
                    $searchResults[] = $taxon;
                }
            }else{
            $searchResults = $ApiTaxa->SearchAPI($choix, $taxons_search, 1, 20);
            }
            if (!empty($searchResults)){
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Recherche de '.$taxons_search, "cheminVueBody" => "recherche/recherche.php", "searchResults" => $searchResults]);
            }else{
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur, taxons non trouvé', "cheminVueBody" => "message/message.php", "message" => "Aucun résultat trouvé, veuillez réessayer !", "fichier" => "../recherche/recherche.php"]);
            }
        }
    /**
     * Affiche les détails d'un taxon spécifique.
     * Charge les informations du taxon et de la naturothèque de l'utilisateur, si connecté.
     */
        public static function details() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
                $tabnaturotheque = Naturotheque::afficher_naturotheque_utilisateur($utilisateur->getPseudonyme());
                ($tabnaturotheque);
            }
            $taxonid = $_GET['taxon'];
            $taxon = Taxon::Taxon_by_ID($taxonid);
            if (!empty($utilisateur)){
                $utilisateur->dernier_vue($taxonid);
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Recherche de '. $taxon->getfullname(), "cheminVueBody" => "details/details.php", "taxon" => $taxon, "tabnaturotheque" => $tabnaturotheque]);
            }else{
            ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Recherche de '. $taxon->getfullname(), "cheminVueBody" => "details/details.php", "taxon" => $taxon]);
            }
        }
    /**
     * Ajoute un taxon à la naturothèque de l'utilisateur.
     * Vérifie si l'utilisateur est connecté et ajoute le taxon à sa naturothèque.
     */
        public static function ajouterTaxonNaturotheque() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
                $ApiTaxa = new TaxRefAPI();
                $naturothequeid = $_GET['naturotheque'];
                $taxonid = $_GET['taxon'];
                $taxon = $_SESSION['taxon'];
                $tabnaturotheque = Naturotheque::afficher_naturotheque_utilisateur($utilisateur->getPseudonyme());
                $result = Naturotheque::addTaxonNaturotheque($naturothequeid, $taxonid);
                if($result){
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Taxon ajouté à la naturothèque avec succès !', "cheminVueBody" => "message/message.php", "message" => "Taxon rajouté à la naturothèque avec succès !", "taxon" => $taxon, "tabnaturotheque" => $tabnaturotheque, "fichier" => "../details/details.php"]);
                }else{
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur, taxon déjà présent dans la naturothèque !', "cheminVueBody" => "message/message.php", "message" => "Taxon déjà présent dans la naturothèque !", "taxon" => $taxon, "tabnaturotheque" => $tabnaturotheque, "fichier" => "../details/details.php"]);
                }
            }
        }
    /**
     * Affiche la naturothèque de l'utilisateur.
     * Charge et affiche tous les taxons présents dans la naturothèque de l'utilisateur connecté.
     */
        public static function naturotheque() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
                $tabnaturotheque = Naturotheque::afficher_naturotheque_utilisateur($utilisateur->getPseudonyme());
                ($tabnaturotheque);
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Naturothèque", "cheminVueBody" => "naturotheque/naturotheque.php", "tabnaturotheque" => $tabnaturotheque]);
            }else{
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Naturothèque", "cheminVueBody" => "naturotheque/naturotheque.php"]);
            }

        }
    /**
     * Affiche la page pour créer une nouvelle naturothèque.
     * Vérifie si l'utilisateur est connecté avant d'afficher le formulaire de création.
     */
        public static function pageCreerNaturotheque() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Création d'une naturothèque", "cheminVueBody" => "naturotheque/createNaturotheque.php"]);
            }else{
                ControllerPrincipal::error("Vous n'êtes pas identifié !");
            }
            
        }
            /**
     * Traite la création d'une nouvelle naturothèque.
     * Récupère les données du formulaire, les valide, et crée une nouvelle naturothèque associée à l'utilisateur.
     */
        public static function CreerNaturotheque() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
            }
            $titre = htmlspecialchars($_POST['titre']);
            $description = htmlspecialchars($_POST['description']);
            $categories = htmlspecialchars($_POST['categories']);
            $prive = isset($_POST['prive']) ? true : false;
            if(isset($_FILES['image_fond']) && $_FILES['image_fond']['error'] == 0) { 
                $allowedTypes = ['image/jpeg', 'image/png'];
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($fileInfo, $_FILES['image_fond']['tmp_name']);
                finfo_close($fileInfo);
                if (!in_array($mimeType, $allowedTypes)) {
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur, fichier incompatible ', "cheminVueBody" => "message/message.php", "message" => "Erreur, l'image de fond n'est pas dans le bon format. Veuillez reessayer ! ", "fichier" => "../Naturotheque/createNaturotheque.php"]);
                    exit(); 
                }
                $imageData = file_get_contents($_FILES['image_fond']['tmp_name']); // Lire le fichier
                $imageData = base64_encode($imageData);
            } else {
                $imageData = null; // Aucune image n'a été téléchargée
            }
            Naturotheque::creation_naturotheque_bd($utilisateur->getPseudonyme(), $categories, $titre, $description,$prive,$imageData);
            ControllerPrincipal::naturotheque();
        }

    /**
     * Affiche les détails d'une naturothèque spécifique.
     * Charge et affiche les informations d'une naturothèque, y compris tous les taxons qu'elle contient.
     * @param string $naturothequeid L'identifiant de la naturothèque à afficher.
     */
        public static function DetailNaturotheque() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
            }
                $naturothequeid = $_GET['naturothequeid'];
                $naturotheque = Naturotheque::getNaturothequeById($naturothequeid);
                if($naturotheque->getPrive() == 0 ||(!empty($utilisateur) && $naturotheque->getPseudonyme() == $utilisateur->getPseudonyme())){
                    $tabtaxon = Naturotheque::getTaxonByNaturothequeId($naturothequeid);
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Détail de la naturothèque' . $naturotheque->getTitre(), "cheminVueBody" => "naturotheque/detailNaturotheque.php", "naturotheque" => $naturotheque, "tabtaxon" => $tabtaxon]);
                    exit();
                }
            ControllerPrincipal::error("Vous n'avez pas accès à cette page !");
    }

    /**
     * Supprime une naturothèque spécifique.
     * Vérifie si l'utilisateur est autorisé à supprimer la naturothèque avant de procéder.
     */
    public static function SupprimerNaturotheque() : void {
        if (isset($_SESSION["utilisateur"])){
            $utilisateur = $_SESSION["utilisateur"];
            $naturothequeid = $_GET['naturothequeid'];
            $naturotheque = Naturotheque::getNaturothequeById($naturothequeid);
            if($naturotheque->verifyUserNaturotheque($utilisateur->getPseudonyme())){
                Naturotheque::SupprimerNaturotheque($naturothequeid);
                ControllerPrincipal::naturotheque();
            }else{
                ControllerPrincipal::error("Vous n'avez pas accès à cette page !");
            }
        }
    }

    /**
     * Supprime une naturothèque de l'administration.
     * Cette fonction est réservée aux administrateurs pour supprimer n'importe quelle naturothèque.
     */

    public static function SupprimerNaturothequeAdmin() : void {
        if (isset($_SESSION["utilisateur"])){
            $utilisateur = $_SESSION["utilisateur"];
            $naturothequeid = $_GET['naturothequeid'];
            if($utilisateur->isAdmin()){
                Naturotheque::SupprimerNaturotheque($naturothequeid);
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Suppression réussi !', "cheminVueBody" => "message/message.php", "message" => "Suppression de la naturothèque réussi !", "fichier" => "../administration/pageadministration.php"]);
            }else{
                ControllerPrincipal::error("Vous n'avez pas accès à cette page !");
            }
        }
    }

    /**
     * Supprime un taxon d'une naturothèque.
     * Permet à un utilisateur de supprimer un taxon de sa naturothèque.
     */

        public static function SupprimerTaxonNaturotheque() : void {
            $naturothequeid =$_GET['naturothequeid'];
            $taxonid = $_GET['taxonid'];
            $result = Naturotheque::DeleteTaxonByNaturothequeId($naturothequeid, $taxonid);
            $naturotheque = Naturotheque::getNaturothequeById($naturothequeid);
            $tabtaxon = Naturotheque::getTaxonByNaturothequeId($naturothequeid);
            if($result){
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Taxon supprimé de la naturothèque avec succès !', "cheminVueBody" => "message/message.php", "message" => "Taxon supprimé de la naturothèque avec succès !",  "naturotheque" => $naturotheque, "tabtaxon" => $tabtaxon, "fichier" => "../Naturotheque/detailNaturotheque.php"]);
            }else{
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur, taxon pas supprimé', "cheminVueBody" => "message/message.php", "message" => "Erreur, le taxon n'a pas été supprimé. Veuillez reessayer ! ",  "naturotheque" => $naturotheque, "tabtaxon" => $tabtaxon, "fichier" => "../Naturotheque/detailNaturotheque.php"]);
            }
        }

        /**
     * Affiche la page pour modifier une naturothèque existante.
     * Charge les données de la naturothèque spécifiée pour modification.
     */


        public static function PageModifierNaturotheque() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
            }
            $naturothequeid = $_GET['naturothequeid'];
            $naturotheque = Naturotheque::getNaturothequeById($naturothequeid);
            if($naturotheque){
                if($naturotheque->verifyUserNaturotheque($utilisateur->getPseudonyme())){
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Modification de la naturothèque' . $naturotheque->getTitre(), "cheminVueBody" => "naturotheque/ModifyNaturotheque.php", "naturotheque" => $naturotheque]);
                }else{
                    ControllerPrincipal::error("Vous n'avez pas accès à cette page !");
                }
            }else{
                ControllerPrincipal::error("La naturothèque n'existe pas, veuillez reessayer");
            }
        }

    /**
     * Applique les modifications à une naturothèque.
     * Met à jour la naturothèque avec les nouvelles informations fournies par l'utilisateur.
     */
        public static function ModifierNaturotheque() : void {
            $naturothequeid = $_POST['naturothequeid'];
            $titre = htmlspecialchars($_POST['titre']);
            $description = htmlspecialchars($_POST['description']);
            $categories = htmlspecialchars($_POST['categories']);
            $prive = isset($_POST['prive']) ? true : false;
            if(isset($_FILES['image_fond']) && $_FILES['image_fond']['error'] == 0) { 
                $allowedTypes = ['image/jpeg', 'image/png'];
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($fileInfo, $_FILES['image_fond']['tmp_name']);
                finfo_close($fileInfo);
                if (!in_array($mimeType, $allowedTypes)) {
                    $naturotheque = Naturotheque::getNaturothequeById($naturothequeid);
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur, fichier incompatible ', "cheminVueBody" => "message/message.php", "message" => "Erreur, l'image de fond n'est pas dans le bon format. Veuillez reessayer ! ",  "naturotheque" => $naturotheque, "fichier" => "../Naturotheque/ModifyNaturotheque.php"]);
                    exit(); 
                }
                $imageData = file_get_contents($_FILES['image_fond']['tmp_name']); // Lire le fichier
                $imageData = base64_encode($imageData);
            } else {
                $imageData = null; // Aucune image n'a été téléchargée
            }
            $result = Naturotheque::UpdateNaturotheque($naturothequeid, $titre, $description, $categories, $prive, $imageData);
            if($result == True){
                ControllerPrincipal::naturotheque();
            }else{
                $naturotheque = Naturotheque::getNaturothequeById($naturothequeid);
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur, naturothèque pas modifié ', "cheminVueBody" => "message/message.php", "message" => "Erreur, La naturothèquen n'a pas été supprimé. Veuillez reessayer ! ",  "naturotheque" => $naturotheque, "fichier" => "../Naturotheque/ModifyNaturotheque.php"]);
            }
        }

    /**
     * Recherche des naturothèques par titre.
     * Permet à un utilisateur de rechercher des naturothèques par leur titre.
     */

        public static function searchNaturotheque() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
                $tabnaturotheque = Naturotheque::afficher_naturotheque_utilisateur($utilisateur->getPseudonyme());
            }
            $titrenaturotheque = $_POST['titrenaturotheque'];
            $tabsearchnaturotheque = Naturotheque::SearchNaturothequePrive($titrenaturotheque);
            if(!empty($tabsearchnaturotheque)){
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Naturothèque", "cheminVueBody" => "naturotheque/naturotheque.php", "tabsearchnaturotheque" => $tabsearchnaturotheque]);
            }else{
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Naturothèque non trouvé !', "cheminVueBody" => "message/message.php", "message" => "Aucune naturothèque trouvé, veuillez reessayer !", "fichier" => "../Naturotheque/naturotheque.php"]);
            }
        }



    /**
     * Recherche un utilisateur par email ou pseudonyme.
     * Fonction réservée à l'administration pour chercher des utilisateurs dans la base de données.
     */
        public static function searchUser() : void {
            if (isset($_SESSION["utilisateur"])){
            $utilisateur = $_SESSION["utilisateur"];
            if($utilisateur->isAdmin()){
                $email_pseudonyme = $_POST['pseudonyme_email'];
                $tabuser = Utilisateur::SearchUser($email_pseudonyme);
                if(!empty($tabuser)){
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Page d'administration", "cheminVueBody" => "administration/pageadministration.php",  "utilisateur" => $utilisateur, "tabuser" => $tabuser]);
                }else{
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur !', "cheminVueBody" => "message/message.php", "message" => "Aucun utilisateur trouvé, veuillez recommencer !", "fichier" => "../administration/pageadministration.php"]);
                }
            }else{
                ControllerPrincipal::error("Vous n'avez pas les autorisations requises pour accéder à cette page!'");
            }
            }else{
                ControllerPrincipal::error("Vous n'êtes pas identifié !");
            }

        }

    /**
     * Recherche des naturothèques pour l'administration.
     * Permet à un administrateur de rechercher des naturothèques par titre, indépendamment de leur visibilité.
     */
        public static function searchNaturothequeAdmin() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
                if ($utilisateur->isAdmin()){
                $titrenaturotheque = $_POST['titrenaturotheque'];
                $tabnaturotheque = Naturotheque::SearchNaturothequeAdmin($titrenaturotheque);
                if(!empty($tabnaturotheque)){
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Page d'administration", "cheminVueBody" => "administration/pageadministration.php",  "utilisateur" => $utilisateur, "tabnaturotheque" => $tabnaturotheque]);
                }else{
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur !', "cheminVueBody" => "message/message.php", "message" => "Aucune naturothèque trouvé, veuillez recommencer !", "fichier" => "../administration/pageadministration.php"]);
            }
        }else{
            ControllerPrincipal::error("Vous n'avez pas les autorisations requises pour accéder à cette page!'");
        }
        }else{
            ControllerPrincipal::error("Vous n'êtes pas identifié !");
        }
        }
        
        

    /**
     * Affiche une vue spécifique avec des paramètres donnés.
     * @param string $cheminVue Le chemin de la vue à afficher.
     * @param array $parametres Les paramètres à passer à la vue.
     */

        private static function afficheVue(string $cheminVue, array $parametres = []) : void {
            extract($parametres); // Crée des variables à partir du tableau $parametres
            require "../view/$cheminVue"; // Charge la vue
        }


    /**
     * Gère la déconnexion de l'utilisateur.
     * Détruit la session de l'utilisateur et redirige vers la page de connexion.
     */
        public static function deconnexion() : void {
            if (isset($_SESSION["utilisateur"])) {
                unset($_SESSION["utilisateur"]);
                session_destroy();
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Connexion", "cheminVueBody" => "Connexion/connexionsite.php"]);
            } else {
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Connexion", "cheminVueBody" => "Connexion/connexionsite.php"]);
            }
        }


    /**
     * Affiche les statistiques de l'utilisateur.
     * Affiche les statistiques concernant les dernières vues de taxons par l'utilisateur.
     */
        public static function statistiques() : void {
            if (isset($_SESSION["utilisateur"])){
                $utilisateur = $_SESSION["utilisateur"];
                $tabtaxon = $utilisateur->taxon_dernier_vue();
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Statistiques", "cheminVueBody" => "statistiques/statistiques.php", "tabtaxon" => $tabtaxon]);
            } else {
                ControllerPrincipal::error("Vous devez être connecté pour accéder à vos statistiques.");
            }
        }


    /**
     * Met à jour les informations de l'utilisateur.
     * Permet à l'utilisateur de mettre à jour son profil, y compris l'email, le pseudonyme, et le mot de passe.
     */

        public static function UpdateUser() : void {
            if (isset($_SESSION["utilisateur"])) {
                $utilisateur = $_SESSION ["utilisateur"];
                $email = $_POST["email"];
                $pseudonyme = $_POST["pseudonyme"];
                $oldpassword = $_POST["oldpassword"];
                $password = $_POST["password"];
                $cpassword = $_POST["cpassword"];
                $oldpseudo = $utilisateur->getPseudonyme();
                $utilisateur->setEmail($email);
                $utilisateur->setPseudonyme($pseudonyme);
                if (!empty($oldpassword) || !empty($password) || !empty($cpassword)) {
                    if (!password_verify($oldpassword, $utilisateur->getmdp_hache())) {
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Erreur, votre ancien mot de passe n'est pas bon !", "cheminVueBody" => "message/message.php", "message" => "Erreur, votre ancien mot de passe ne correspond pas ! ",  "utilisateur" => $utilisateur, "fichier" => "../moncompte/moncompte.php"]);
                    exit;
                    }

                    if ($password !== $cpassword) {
                        ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Erreur, les mots de passes ne sont pas identiques !", "cheminVueBody" => "message/message.php", "message" => "Erreur, les mots de passes ne correspondent pas ! ",  "utilisateur" => $utilisateur, "fichier" => "../moncompte/moncompte.php"]);
                        exit;
                    }
                    $new_password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $utilisateur->setMdp_Hache($new_password_hash);                    
                }
                $utilisateur->UpdateUser($oldpseudo);
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Compte mise à jour avec succès !", "cheminVueBody" => "message/message.php", "message" => "Votre compte a bien été mis à jour !",  "utilisateur" => $utilisateur, "fichier" => "../moncompte/moncompte.php"]);
            }
        }

    /**
     * Supprime le compte d'un utilisateur.
     * Permet à l'utilisateur de supprimer son propre compte de la base de données.
     */
        public static function supprimerCompte() : void {
            // Assurez-vous que l'utilisateur est connecté
            if (isset($_SESSION["utilisateur"])) {
                $utilisateur = $_SESSION["utilisateur"];
        
                // Supprimez le compte de la base de données
                $suppressionReussie = Utilisateur::supprimerCompte($utilisateur->getPseudonyme());
        
                if ($suppressionReussie) {
                    // Déconnectez l'utilisateur après la suppression
                    unset($_SESSION["utilisateur"]);
                    session_destroy();
        
                    // Redirigez l'utilisateur vers la page de connexion ou toute autre page appropriée
                    header("Location: frontController.php?action=suppressionReussi");
                    exit();
                } else {
                    // La suppression a échoué, redirigez l'utilisateur avec un message d'erreur
                    $errorMessage = "La suppression du compte a échoué. Veuillez réessayer.";
                    ControllerPrincipal::error($errorMessage);
                    exit();
                }
            } else {
                // Si l'utilisateur n'est pas connecté, redirigez-le simplement vers la page de connexion
                header("Location: frontController.php?action=pageconnexion");
                exit();
            }
        }

    /**
     * Permet d'accéder à la page d'administration
     */
        public static function pageadministration() : void {
            if (isset($_SESSION["utilisateur"])) {
                $utilisateur = $_SESSION["utilisateur"];
                if($utilisateur->isAdmin()){
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => "Page d'administration", "cheminVueBody" => "administration/pageadministration.php",  "utilisateur" => $utilisateur]);
                }
        }
        
    }

    /**
 * Édite une naturothèque par un administrateur.
 *
 * Cette fonction permet de modifier les informations d'une naturothèque par un administrateur.
 *
 */
    public static function editNaturothequeAdmin() : void {
        if (isset($_SESSION["utilisateur"])) {
            $utilisateur = $_SESSION["utilisateur"];
            if($utilisateur->isAdmin()){
            $naturothequeid = $_POST['naturothequeid'];
            $titre = htmlspecialchars($_POST['titre']);
            $description = htmlspecialchars($_POST['description']);
            $categories = htmlspecialchars($_POST['categories']);
            $prive = isset($_POST['prive']) ? true : false;
            if(isset($_FILES['image_fond']) && $_FILES['image_fond']['error'] == 0) { 
                $allowedTypes = ['image/jpeg', 'image/png'];
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($fileInfo, $_FILES['image_fond']['tmp_name']);
                finfo_close($fileInfo);
                if (!in_array($mimeType, $allowedTypes)) {
                    $naturotheque = Naturotheque::getNaturothequeById($naturothequeid);
                    ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur, fichier incompatible ', "cheminVueBody" => "message/message.php", "message" => "Erreur, l'image de fond n'est pas dans le bon format. Veuillez reessayer ! ",  "naturotheque" => $naturotheque, "fichier" => "../Naturotheque/ModifyNaturotheque.php"]);
                    exit(); 
                }
                $imageData = file_get_contents($_FILES['image_fond']['tmp_name']); // Lire le fichier
                $imageData = base64_encode($imageData);
            } else {
                $imageData = null; // Aucune image n'a été téléchargée
            }
            $result = Naturotheque::UpdateNaturotheque($naturothequeid, $titre, $description, $categories, $prive, $imageData);
            if($result == True){
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Modification de la naturothèque réussi !', "cheminVueBody" => "message/message.php", "message" => "Modification de la naturothèque réussi !", "fichier" => "../administration/pageadministration.php"]);
            }else{
                $naturotheque = Naturotheque::getNaturothequeById($naturothequeid);
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur, naturothèque pas modifié ', "cheminVueBody" => "message/message.php", "message" => "Erreur, La naturothèquen n'a pas été supprimé. Veuillez reessayer ! ",  "naturotheque" => $naturotheque, "fichier" => "../Naturotheque/ModifyNaturotheque.php"]);
            }
        }else{
            ControllerPrincipal::error("Vous n'avez pas les autorisations requises pour accéder à cette page!'");
        }
    }else{
        ControllerPrincipal::error("Vous n'êtes pas identifié !");
    }
}

/**
 * Affiche la page de modification d'une naturothèque pour les administrateurs.
 *
 * Cette fonction affiche la page permettant de modifier une naturothèque pour les administrateurs.
 *
 */
public static function PageModifierNaturothequeAdmin() : void {
    if (isset($_SESSION["utilisateur"])){
        $utilisateur = $_SESSION["utilisateur"];
        if($utilisateur->isAdmin()){
    $naturothequeid = $_GET['naturothequeid'];
    $naturotheque = Naturotheque::getNaturothequeById($naturothequeid);
    if($naturotheque){
            ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Modification de la naturothèque' . $naturotheque->getTitre(), "cheminVueBody" => "administration/ModifyNaturotheque.php", "naturotheque" => $naturotheque]);
        }else{
        ControllerPrincipal::error("La naturothèque n'existe pas, veuillez reessayer");
    }
}else{
    ControllerPrincipal::error("Vous n'avez pas les autorisations requises pour accéder à cette page!'");
}
}else{
    ControllerPrincipal::error("Vous n'êtes pas identifié !");
}
}

    /**
     * Affiche une vue spécifique avec des paramètres donnés.
     * @param string $cheminVue Le chemin de la vue à afficher.
     * @param array $parametres Les paramètres à passer à la vue.
     */
        public static function supprimerCompteAdmin() : void {
            if (isset($_SESSION["utilisateur"])) {
                $utilisateur = $_SESSION["utilisateur"];
                $pseudonyme = $_GET["pseudonyme"];
                if($utilisateur->isAdmin()){
                $suppressionReussie = Utilisateur::supprimerCompte($pseudonyme);
                if($suppressionReussie){
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Suppression réussi !', "cheminVueBody" => "message/message.php", "message" => "Suppression de " . $pseudonyme . " réussi !", "fichier" => "../administration/pageadministration.php"]);
                }else{
                ControllerPrincipal::afficheVue('view.php', ["pagetitle" => 'Erreur !', "cheminVueBody" => "message/message.php", "message" => "L'utilisateur " . $pseudonyme . " ne s'est pas supprimé, veuillez reessayer !", "fichier" => "../administration/pageadministration.php"]);
                }
                }
        }
        }
}
?>
