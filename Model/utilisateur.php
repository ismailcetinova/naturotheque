<?php
namespace App\SAE\Model;
use App\SAE\Model\Model;
use App\SAE\Model\Taxon;
use \PDO;

/**
 * Classe Utilisateur représentant un utilisateur du système.
 */

class Utilisateur{
    /** @var string Pseudonyme de l'utilisateur. */
    private string $pseudonyme;

    /** @var string Email de l'utilisateur. */
    private string $email;

    /** @var string Mot de passe haché de l'utilisateur. */
    private string $mdp_hache;

    /** @var string Date de création du compte utilisateur. */
    private string $date_creation;

    /** @var bool Indique si l'utilisateur est administrateur. */
    private bool $admin;


    //Les getters : 

    /**
     * Obtient le pseudonyme de l'utilisateur.
     * @return string Pseudonyme de l'utilisateur.
     */
    public function getPseudonyme(){
        return $this->pseudonyme;
    }


    /**
     * Obtient l'email de l'utilisateur.
     * @return string Email de l'utilisateur.
     */
    public function getEmail(){
        return $this->email;
    }


    /**
     * Obtient le mot de passe haché de l'utilisateur.
     * @return string Mot de passe haché.
     */
    public function getMdp_hache(){
        return $this->mdp_hache;
    }

    /**
     * Obtient la date de création du compte de l'utilisateur.
     * @return string Date de création du compte.
     */
    public function getDate_Creation(){
        return $this->date_creation;
    }


    /**
     * Vérifie si l'utilisateur est administrateur.
     * @return bool Vrai si l'utilisateur est administrateur, faux sinon.
     */
    public function getAdmin(){
        return $this->admin;
    }


    /**
     * Définit le pseudonyme de l'utilisateur.
     * @param string $pseudonyme Nouveau pseudonyme de l'utilisateur.
     */
    public function setPseudonyme(string $pseudonyme){
        $this->pseudonyme = $pseudonyme;
    }


    /**
     * Définit l'email de l'utilisateur.
     * @param string $email Nouvel email de l'utilisateur.
     */
    public function setEmail(string $email){
        $this->email = $email;
    }
    

    /**
     * Définit le mot de passe haché de l'utilisateur.
     * @param string $mdp_hache Nouveau mot de passe haché de l'utilisateur.
     */
    public function setMdp_hache(string $mdp_hache){
        $this->mdp_hache = $mdp_hache;
    }
    public function isAdmin() : bool {
        return $this->admin == 1;
    }


    /**
     * Constructeur de la classe Utilisateur.
     * @param string $pseudonyme Pseudonyme de l'utilisateur.
     * @param string $mdp_hache Mot de passe haché de l'utilisateur.
     * @param string $email Email de l'utilisateur.
     * @param string $date_creation Date de création du compte.
     * @param bool $admin Indique si l'utilisateur est administrateur.
     */
    public function __construct(string $pseudonyme, string $mdp_hache, string $email, string $date_creation, bool $admin) {
        $this->pseudonyme = $pseudonyme;
        $this->mdp_hache = $mdp_hache;
        $this->email = $email;
        $this->date_creation = $date_creation;
        $this->admin = $admin;
    }



    /**
     * Inscription d'un nouvel utilisateur dans la base de données.
     * @return bool Retourne vrai si l'inscription a réussi, faux en cas d'erreur.
     */
    public function inscription() : string {
        try {
            $sql = "INSERT INTO utilisateur (pseudo, email, mot_de_passe, date_creation, administrateur) VALUES (:pseudo, :email, :mot_de_passe, :date_creation, :administrateur)";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "pseudo" => $this->pseudonyme,
                "email" => $this->email,
                "mot_de_passe" => $this->mdp_hache,
                "date_creation" => $this->date_creation,
                "administrateur" => ($this->admin === false) ? 0 : 1,
            );
            $pdoStatement->execute($values);
            return True;
        } catch (PDOException $e) {
            echo "erreur";
            exit();
        }
    }


    /**
     * Authentifie un utilisateur à partir de son pseudonyme/email et mot de passe.
     * @param string $pseudonyme_email Pseudonyme ou email de l'utilisateur.
     * @param string $mot_de_passe Mot de passe de l'utilisateur.
     * @return Utilisateur|bool Instance de Utilisateur si authentification réussie, faux sinon.
     */
    public static function connexion(string $pseudonyme_email, string $mot_de_passe) {
        $sql = "SELECT * FROM utilisateur WHERE pseudo = :pseudonyme_email OR email = :pseudonyme_email";
        $pdoStatement = Model::getPdo()->prepare($sql);
        $values = array(
            "pseudonyme_email" => $pseudonyme_email,
        );
        $pdoStatement->execute($values);

        $information_user = $pdoStatement->fetch(PDO::FETCH_ASSOC);

        if($information_user && password_verify($mot_de_passe, $information_user['mot_de_passe'])){
            $utilisateur = new Utilisateur($information_user['pseudo'], $information_user['mot_de_passe'], $information_user['email'], $information_user['date_creation'], $information_user['administrateur']);
            return $utilisateur;
        }
        return False;
    }


    /**
     * Enregistre la dernière vue d'un taxon par l'utilisateur.
     * @param int $id_taxon Identifiant du taxon vu.
     */
    public function dernier_vue(int $id_taxon) : void {
    try{
        $sql = "INSERT INTO dernier_vue (pseudonyme, id_taxon, date) VALUES (:id_utilisateur, :id_taxon, NOW())";
        $pdoStatement = Model::getPdo()->prepare($sql);
        $values = array (
            "id_utilisateur" => $this->pseudonyme,
            "id_taxon" => $id_taxon
        );
        $pdoStatement->execute($values);
    }catch (PDOException $e) {
        echo "Erreur PDO : " . $e->getMessage();
    }
}



    /**
     * Récupère les derniers taxons vus par l'utilisateur.
     * @return array Liste des taxons récemment vus.
     */
public function taxon_dernier_vue(){
    try{
        $sql = "SELECT id_taxon FROM dernier_vue WHERE pseudonyme = :pseudonyme ORDER BY date DESC LIMIT 5";        $pdoStatement = Model::getPdo()->prepare($sql);
        $values = array(
            "pseudonyme" => $this->pseudonyme,
        );
        $pdoStatement->execute($values);
        $tabtaxon = [];
        foreach($pdoStatement as $id_taxon){
            $taxon = Taxon::Taxon_by_ID($id_taxon["id_taxon"]);
            $tabtaxon[] = $taxon;
        }
        return $tabtaxon;
    }catch (PDOException $e){
        return "Erreur PDO : " . $e->getMessage();
    }
}


    /**
     * Met à jour les informations de l'utilisateur dans la base de données.
     * @param string $oldpseudo Ancien pseudonyme de l'utilisateur pour identification.
     * @return bool Retourne vrai si la mise à jour a réussi, faux en cas d'erreur.
     */
    public function UpdateUser(string $oldpseudo){
    try{
        $sql = "UPDATE utilisateur SET pseudo = :pseudo, email = :email, mot_de_passe = :mdp WHERE pseudo = :oldpseudo";
        $pdoStatement = Model::getPdo()->prepare($sql);
        $values = array (
            "pseudo" => $this->pseudonyme,
            "oldpseudo" => $oldpseudo,
            "email" => $this->email,
            "mdp" => $this->mdp_hache
        );
        $pdoStatement->execute($values);
        $sql = "UPDATE naturotheque SET pseudonyme = :pseudo WHERE pseudonyme = :oldpseudo";
        $pdoStatement = Model::getPdo()->prepare($sql);
        $values = array (
            "pseudo" => $this->pseudonyme,
            "oldpseudo" => $oldpseudo,
        );
        $pdoStatement->execute($values);
        $sql = "UPDATE dernier_vue SET pseudonyme = :pseudo WHERE pseudonyme = :oldpseudo";
        $pdoStatement = Model::getPdo()->prepare($sql);
        $values = array (
            "pseudo" => $this->pseudonyme,
            "oldpseudo" => $oldpseudo,
        );
        $pdoStatement->execute($values);
        return True;
    }catch (PDOException $e) {
        echo "Erreur PDO : " . $e->getMessage();
    }
}

    /**
     * Représentation en chaîne de caractères de l'objet Utilisateur.
     * Fournit une représentation textuelle des principales informations de l'utilisateur.
     * @return string Une représentation textuelle de l'utilisateur.
     */
    public function __toString() {
        return "pseudonyme : \n " . $this->pseudonyme .  "\n email :\n " . $this->email . "\n date_creation \n" . $this->date_creation . "\n admin : \n" . $this->admin;
    }


    /**
     * Supprime le compte d'un utilisateur de la base de données.
     * @param string $pseudonyme Pseudonyme de l'utilisateur à supprimer.
     * @return bool Vrai si la suppression a été réussie, faux sinon.
     */
    public static function supprimerCompte(string $pseudonyme) : bool {
        try {
            $pdo = Model::getPdo();

            // Suppression des naturothèques de l'utilisateur
            $sqlNaturotheques = "DELETE FROM naturotheque WHERE pseudonyme = :pseudonyme";
            $stmtNaturotheques = $pdo->prepare($sqlNaturotheques);
            $stmtNaturotheques->bindParam(":pseudonyme", $pseudonyme, PDO::PARAM_STR);
            $stmtNaturotheques->execute();

            // Suppression des vues précédentes de l'utilisateur
            $sqlVues = "DELETE FROM dernier_vue WHERE pseudonyme = :pseudonyme";
            $stmtVues = $pdo->prepare($sqlVues);
            $stmtVues->bindParam(":pseudonyme", $pseudonyme, PDO::PARAM_STR);
            $stmtVues->execute();

            // Suppression de l'utilisateur
            $sqlUtilisateur = "DELETE FROM utilisateur WHERE pseudo = :pseudonyme";
            $stmtUtilisateur = $pdo->prepare($sqlUtilisateur);
            $stmtUtilisateur->bindParam(":pseudonyme", $pseudonyme, PDO::PARAM_STR);
            $stmtUtilisateur->execute();

            return true;
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        }
    }


    /**
     * Recherche des utilisateurs dans la base de données par pseudonyme ou email.
     * @param string $email_pseudo Le pseudonyme ou l'email à rechercher.
     * @return array|false Retourne un tableau d'objets Utilisateur correspondant à la recherche, ou faux en cas d'erreur.
     */
    public static function SearchUser(string $email_pseudo){
        try{
            $sql = "SELECT email, mot_de_passe, pseudo, date_creation, administrateur FROM utilisateur WHERE administrateur = 0 AND (pseudo LIKE :email_pseudo OR email like :email_pseudo)";
            $pdoStatement = Model::getPdo()->prepare($sql);
            $values = array(
                "email_pseudo" => "%$email_pseudo%",
            );           
            $pdoStatement->execute($values);
            $tabuser = [];
            foreach($pdoStatement as $information_user){
                $utilisateur = new Utilisateur($information_user['pseudo'], $information_user['mot_de_passe'], $information_user['email'], $information_user['date_creation'], $information_user['administrateur']);
                $tabuser[] = $utilisateur;
            }
            return $tabuser;
        }catch (PDOException $e){
            return "Erreur PDO : " . $e->getMessage();
        }
    }

    
}
?>