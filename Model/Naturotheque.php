<?php
namespace App\SAE\Model;
use App\SAE\Model\Model;
use \PDO;
/**
 * Représente une naturothèque.
 */
class Naturotheque{
    /**
     * @var int Identifiant de la naturothèque.
     */
    private int $id_naturotheque;

    /**
     * @var string Pseudonyme de l'utilisateur propriétaire de la naturothèque.
     */
    private string $pseudonyme;

    /**
     * @var string Titre de la naturothèque.
     */
    private string $titre;
    
    /**
     * @var string Description de la naturothèque.
     */
    public string $description;

    /**
     * @var string Catégories de la naturothèque.
     */
    private string $categories;

    /**
     * @var bool Indique si la naturothèque est privée ou non.
     */
    private bool $prive;

    
    /**
     * @var string Chemin de l'image de fond de la naturothèque.
     */
    private string $imageFond;

    /**
     * Récupère l'identifiant de la naturothèque.
     * @return int L'identifiant de la naturothèque.
     */
    public function getId() {
        return $this->id_naturotheque;
    }

    /**
     * Récupère le pseudonyme de l'utilisateur propriétaire de la naturothèque.
     * @return string Le pseudonyme de l'utilisateur propriétaire.
     */
    public function getPseudonyme(){
        return $this->pseudonyme;
    }

    /**
     * Récupère le titre de la naturothèque.
     * @return string Le titre de la naturothèque.
     */
    public function getTitre(){
        return $this->titre;
    }

    /**
     * Récupère la description de la naturothèque.
     * @return string La description de la naturothèque.
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * Récupère les catégories de la naturothèque.
     * @return string Les catégories de la naturothèque.
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Récupère l'indicateur de privacité de la naturothèque.
     * @return bool True si la naturothèque est privée, sinon False.
     */
    public function getPrive() {
        return $this->prive;
    }

    
    /**
     * Récupère le chemin de l'image de fond de la naturothèque.
     * @return string Le chemin de l'image de fond.
     */
    public function getImageFond() {
        return $this->imageFond;
    }


    /**
     * Constructeur de la classe Naturotheque.
     * @param int $id_naturotheque L'identifiant de la naturothèque.
     * @param string $pseudonyme Le pseudonyme de l'utilisateur propriétaire.
     * @param string $titre Le titre de la naturothèque.
     * @param string $description La description de la naturothèque.
     * @param string $categories Les catégories de la naturothèque.
     * @param bool $prive Indique si la naturothèque est privée.
     * @param string|null $imageFond Le chemin de l'image de fond.
     */
    public function __construct(int $id_naturotheque,string $pseudonyme, string $titre, string $description, string $categories, bool $prive, ?string $imageFond){
        $this->id_naturotheque = $id_naturotheque;
        $this->pseudonyme = $pseudonyme;
        $this->titre = $titre;
        $this->description = $description;
        $this->categories = $categories;
        $this->prive = $prive;
        if ($imageFond !== NULL){
            $this->imageFond = 'data:image/png;base64,' .  $imageFond;
        }else{
            $this->imageFond = '../assets/1323467.png';
        }
    }

    /**
     * Crée un tableau de catégories à partir d'une chaîne de caractères.
     * @param string $categoriesString La chaîne de caractères contenant les catégories.
     * @return array Le tableau des catégories.
     */
    public static function createCategoriesFromString(string $categoriesString): array {
        // Diviser la chaîne de caractères en un tableau en utilisant le point-virgule comme séparateur
        $categoriesArray = explode(';', $categoriesString);
        // Retirer les espaces blancs autour de chaque catégorie
        $categoriesArray = array_map('trim', $categoriesArray);

        return $categoriesArray;
    }

    /**
     * Crée une naturothèque dans la base de données.
     * @param string $pseudonyme Le pseudonyme de l'utilisateur.
     * @param string $categories Les catégories de la naturothèque sous forme de chaîne.
     * @param string $titre Le titre de la naturothèque.
     * @param string $description La description de la naturothèque.
     * @param bool $prive Indique si la naturothèque est privée ou non.
     * @param string|null $imageData Les données de l'image de fond de la naturothèque.
     * @return bool|string True si la création a réussi, sinon un message d'erreur.
     */
    public static function creation_naturotheque_bd(string $pseudonyme, string $categories, string $titre, string $description, bool $prive, ?string $imageData) : string {
        try {
            $categoriesArray = self::createCategoriesFromString($categories);
            $categoriesForDB = implode(';', $categoriesArray);
            $sql = "INSERT INTO naturotheque (pseudonyme, categorie, titre, description, prive, image_fond) VALUES (:pseudonyme, :categorie, :titre, :description, :prive, :image_fond)";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "pseudonyme" => $pseudonyme,
                "categorie" => $categoriesForDB,
                "titre" => $titre,
                "description" => $description,
                "prive" => ($prive === false) ? 0 : 1,
                "image_fond" => $imageData,
            );
            $pdoStatement->execute($values);
            return True;
        } catch (PDOException $e) {
            return "Erreur PDO : " . $e->getMessage();
        }
    }
    /**
     * Affiche les naturothèques d'un utilisateur.
     * @param string $pseudonyme Le pseudonyme de l'utilisateur.
     * @return array|string Le tableau des naturothèques de l'utilisateur, ou un message d'erreur.
     */
    public static function afficher_naturotheque_utilisateur(string $pseudonyme) : array {
        try {
            $sql = "SELECT id_naturotheque, pseudonyme, categorie, description, titre, prive, image_fond FROM Naturotheque WHERE Pseudonyme = :pseudonyme";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "pseudonyme" => $pseudonyme,
            );
            $pdoStatement->execute($values);
            $tabnaturotheque = [];
            foreach($pdoStatement as $result){
                $naturotheque = new Naturotheque($result['id_naturotheque'], $result['pseudonyme'], $result['titre'], $result['description'], $result['categorie'], $result['prive'], $result['image_fond']);
                $tabnaturotheque[] = $naturotheque;
            }
            return $tabnaturotheque;
        }catch (PDOException $e){
            return "Erreur PDO : " . $e->getMessage();
        }
    }

    
    /**
     * Récupère une naturothèque à partir de son identifiant.
     *
     * @param int $id L'identifiant de la naturothèque.
     * @return Naturotheque|null La naturothèque correspondant à l'identifiant, ou null si non trouvée.
     */
    public static function getNaturothequeById(int $id){
        try{
            $sql = "SELECT id_naturotheque, pseudonyme, categorie,  description, titre, prive, image_fond FROM Naturotheque WHERE id_naturotheque = :id_naturotheque";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "id_naturotheque" => $id,
            );
            $pdoStatement->execute($values);
            $result = $pdoStatement->fetch(PDO::FETCH_ASSOC);
            $naturotheque = new Naturotheque($result['id_naturotheque'], $result['pseudonyme'], $result['titre'], $result['description'], $result['categorie'], $result['prive'], $result['image_fond']);
            return $naturotheque;
        }catch (PDOException $e){
            return "Erreur PDO : " . $e->getMessage();
        }
    }
    
    /**
     * Ajoute un taxon à une naturothèque.
     *
     * @param int $id_naturotheque L'identifiant de la naturothèque.
     * @param int $id_taxon L'identifiant du taxon à ajouter.
     * @return bool|string True si l'ajout a réussi, sinon un message d'erreur.
     */
    public static function addTaxonNaturotheque(int $id_naturotheque, int $id_taxon){
        if(Naturotheque::verifyTaxonInNaturotheque($id_naturotheque, $id_taxon)){
        try{
        $sql = "INSERT INTO naturotheque_taxon (id_naturotheque, id_taxon) VALUES (:id_naturotheque, :id_taxon)";
        $pdoStatement = Model::getPdo()->prepare($sql);
        $values = array(
            "id_naturotheque" => $id_naturotheque,
            "id_taxon" => $id_taxon,
        );
        $pdoStatement->execute($values);
        return True;
    }catch (PDOException $e){
        return "Erreur PDO : " . $e->getMessage();
    }
    }else{
        return False;
    }
}
    
    /**
     * Vérifie si un taxon est déjà présent dans une naturothèque.
     *
     * @param int $id_naturotheque L'identifiant de la naturothèque.
     * @param int $id_taxon L'identifiant du taxon à vérifier.
     * @return bool|string True si le taxon n'est pas présent, sinon un message d'erreur.
     */
    public static function verifyTaxonInNaturotheque(int $id_naturotheque, int $id_taxon){
        try{
        $sql = "SELECT COUNT(*) FROM naturotheque_taxon WHERE id_taxon = :id_taxon AND id_naturotheque = :id_naturotheque";
        $pdoStatement = Model::getPdo()->prepare($sql);
        $values = array(
            "id_taxon" => $id_taxon,
            "id_naturotheque" => $id_naturotheque,
        );
        $pdoStatement->execute($values);
        $result = $pdoStatement->fetch(PDO::FETCH_COLUMN);
        return $result == 0;
        }catch (PDOException $e){
            return "Erreur PDO : " . $e->getMessage();
        }
    }
    
    /**
     * Récupère les taxons d'une naturothèque à partir de son identifiant.
     *
     * @param int $id_naturotheque L'identifiant de la naturothèque.
     * @return array|string Le tableau des taxons de la naturothèque, ou un message d'erreur.
     */
    public static function getTaxonByNaturothequeId(int $id_naturotheque){
        $tabtaxon = [];
        try{
            $sql = "SELECT id_taxon FROM naturotheque_taxon WHERE id_naturotheque = :id_naturotheque";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "id_naturotheque" => $id_naturotheque,
            );
            $pdoStatement->execute($values);
            foreach($pdoStatement as $result){
                $taxon = Taxon::Taxon_by_id($result['id_taxon']);
                $tabtaxon[] = $taxon;                
            }
            return $tabtaxon;
        }catch (PDOException $e){
            return "Erreur PDO : " . $e->getMessage();
        }
    }

    /**
     * Supprime un taxon d'une naturothèque.
     *
     * @param int $id_naturotheque L'identifiant de la naturothèque.
     * @param int $id_taxon L'identifiant du taxon à supprimer.
     * @return bool|string True si la suppression a réussi, sinon un message d'erreur.
     */
    public static function DeleteTaxonByNaturothequeId(int $id_naturotheque, int $id_taxon){
        try{
            $sql = "DELETE FROM naturotheque_taxon WHERE id_naturotheque = :id_naturotheque AND id_taxon = :id_taxon";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "id_naturotheque" => $id_naturotheque,
                "id_taxon" => $id_taxon,
            );
            $pdoStatement->execute($values);
            return True;
        }catch (PDOException $e){
            return "Erreur PDO : " . $e->getMessage();
        }
    }

/**
     * Vérifie si l'utilisateur associé à la naturothèque est le même que celui passé en paramètre.
     *
     * @param string $pseudonyme Le pseudonyme de l'utilisateur à vérifier.
     * @return bool True si l'utilisateur est le propriétaire de la naturothèque, sinon False.
     */
    public function verifyUserNaturotheque(string $pseudonyme){
        return $this->pseudonyme == $pseudonyme;
    }

    /**
     * Supprime une naturothèque de la base de données.
     *
     * @param int $id_naturotheque L'identifiant de la naturothèque à supprimer.
     * @return bool|string True si la suppression a réussi, sinon un message d'erreur.
     */
    public static function SupprimerNaturotheque(int $id_naturotheque){
        try{
            $sql = "DELETE FROM naturotheque WHERE id_naturotheque = :id_naturotheque";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "id_naturotheque" => $id_naturotheque
            );
            $pdoStatement->execute($values);
            $sql = "DELETE FROM naturotheque_taxon WHERE id_naturotheque = :id_naturotheque";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "id_naturotheque" => $id_naturotheque
            );
            $pdoStatement->execute($values);
            return True;
        }catch (PDOException $e){
            return "Erreur PDO : " . $e->getMessage();
        }
    }

    
    /**
     * Met à jour les informations d'une naturothèque dans la base de données.
     *
     * @param int $id_naturotheque L'identifiant de la naturothèque à mettre à jour.
     * @param string $titre Le nouveau titre de la naturothèque.
     * @param string $description La nouvelle description de la naturothèque.
     * @param string $categorie La nouvelle catégorie de la naturothèque.
     * @param bool $prive Indique si la naturothèque est privée ou non.
     * @param string|null $imageData Les nouvelles données de l'image de fond de la naturothèque.
     * @return bool|string True si la mise à jour a réussi, sinon un message d'erreur.
     */
    public static function UpdateNaturotheque(int $id_naturotheque, string $titre, string $description, string $categorie, bool $prive, ?string $imageData){
    try{
        $sql = "UPDATE naturotheque SET titre = :titre, description = :description, categorie = :categorie, prive = :prive, image_fond = :image_fond WHERE id_naturotheque = :id_naturotheque";
        $pdoStatement = Model::getPdo()->prepare($sql);
        $values = array(
            "id_naturotheque" => $id_naturotheque,
            "titre" => $titre,
            "description" => $description,
            "categorie" => $categorie,
            "prive" => ($prive === false) ? 0 : 1,
            "image_fond" => $imageData,
        );
        $pdoStatement->execute($values);
        return True;
    }catch (PDOException $e){
        return "Erreur PDO : " . $e->getMessage();
    }
    }
/**
     * Recherche des naturothèques non-privé selon le titre.
     *
     * @param string $titre Le titre à rechercher.
     * @return array|string Le tableau des naturothèques non-privé correspondantes, ou un message d'erreur.
     */
    public static function SearchNaturothequePrive(string $titre){
        try{
            $sql = "SELECT id_naturotheque, pseudonyme, categorie, titre, description, prive, image_fond FROM naturotheque WHERE prive = 0 AND titre LIKE :titre";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "titre" => "%$titre%",
            );
            $pdoStatement->execute($values);
            $tabnaturotheque = [];
            foreach($pdoStatement as $result){
                $naturotheque = new Naturotheque($result['id_naturotheque'], $result['pseudonyme'], $result['titre'], $result['description'], $result['categorie'], $result['prive'], $result['image_fond']);
                $tabnaturotheque[] = $naturotheque;
            }
            return $tabnaturotheque;
        }catch (PDOException $e){
            return "Erreur PDO : " . $e->getMessage();
        }
    }

    /**
     * Recherche des naturothèques par titre, accessible pour les administrateurs.
     *
     * @param string $titre Le titre à rechercher.
     * @return array|string Le tableau des naturothèques correspondantes, ou un message d'erreur.
     */
    public static function SearchNaturothequeAdmin(string $titre){
        try{
            $sql = "SELECT id_naturotheque, pseudonyme, categorie, titre, description, prive, image_fond FROM naturotheque WHERE titre LIKE :titre";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "titre" => "%$titre%",
            );
            $pdoStatement->execute($values);
            $tabnaturotheque = [];
            foreach($pdoStatement as $result){
                $naturotheque = new Naturotheque($result['id_naturotheque'], $result['pseudonyme'], $result['titre'], $result['description'], $result['categorie'], $result['prive'], $result['image_fond']);
                $tabnaturotheque[] = $naturotheque;
            }
            return $tabnaturotheque;
        }catch (PDOException $e){
            return "Erreur PDO : " . $e->getMessage();
        }
    }
    
    /**
     * Convertit la naturothèque en une représentation sous forme de chaîne de caractères.
     * @return string Une représentation sous forme de chaîne de caractères de la naturothèque.
     */
    public function __toString() {
        return  "<p><strong>Pseudonyme :</strong>" . htmlspecialchars($this->pseudonyme) . 
                "  /<strong>Titre :</strong>" . htmlspecialchars($this->titre) .  
                "  /<strong>Description :</strong>" . htmlspecialchars($this->description);
    }
}

?>