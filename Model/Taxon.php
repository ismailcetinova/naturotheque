<?php
namespace App\SAE\Model;
use App\SAE\Model\TaxRefAPI;
use App\SAE\Model\Model;
use \PDO;

/**
 * Classe Taxon représentant un taxon avec ses informations taxonomiques et multimédias.
 */
class Taxon{
   /** @var int Identifiant unique du taxon. */
   private int $id;

   /** @var int|null Identifiant du parent du taxon, null si non applicable. */
   private ?int $parentid;

   /** @var string|null Autorité de classification du taxon. */
   private ?string $authority;

   /** @var string Nom scientifique du taxon. */
   private string $scientificname;

   /** @var string Nom complet du taxon. */
   private string $fullname;

   /** @var string|null Nom vernaculaire français du taxon, null si non disponible. */
   private ?string $frenchvernacularname;

   /** @var string Nom du rang taxonomique du taxon. */
   private string $rankname;

   /** @var string|null Classe vernaculaire du taxon, null si non disponible. */
   private ?string $vernacularClassName;

   /** @var string Groupe vernaculaire principal du taxon. */
   private string $vernacularGroup1;
   
   /** @var array Liste des médias (images) associés au taxon. */
   private array $media_image;


   /**
    * Retourne l'identifiant unique du taxon.
    * @return int Identifiant du taxon.
    */
   public function getId(){
      return $this->id;
   }
   
      /**
    * Obtient l'identifiant du parent du taxon, si applicable.
    * @return int|null Identifiant du parent ou null si non applicable.
    */
   public function getParentId(){
      return $this->parentid;
   }

   /**
    * Obtient l'autorité de classification du taxon.
    * @return string|null Autorité de classification ou null si non spécifiée.
    */
   public function getAuthority(){
      return $this->authority;
   }

   /**
    * Obtient le nom scientifique du taxon.
    * @return string Nom scientifique du taxon.
    */
   public function getScientificName(){
      return $this->scientificname;
   }

   /**
    * Obtient le nom complet du taxon.
    * @return string Nom complet du taxon.
    */
   public function getFullName(){
      return $this->fullname;
   }


   /**
    * Obtient le nom vernaculaire français du taxon, si disponible.
    * @return string|null Nom vernaculaire français ou null si non disponible.
    */
   public function getFrenchVernacularName(){
      return $this->frenchvernacularname;
   }
   
   /**
    * Obtient le nom du rang taxonomique du taxon.
    * @return string Nom du rang taxonomique.
    */
   public function getRankName(){
      return $this->rankname;
   }

   /**
    * Obtient la classe vernaculaire du taxon, si disponible.
    * @return string|null Classe vernaculaire ou null si non spécifiée.
    */
   public function getVernacularClassName(){
      return $this->vernacularClassName;
   }

   /**
    * Obtient le groupe vernaculaire principal du taxon.
    * @return string Groupe vernaculaire principal.
    */
   public function getvernacularGroup1(){
      return $this->vernacularGroup1;
   }

   /**
    * Obtient l'identifiant GBIF du taxon, si disponible.
    * @return int|null Identifiant GBIF ou null si non disponible.
    */

   public function getGBIF(){
      return $this->GBIF;
   }
   

   /**
    * Obtient la liste des images médias associées au taxon.
    * @return array Liste des URLs des images.
    */
   public function getMediaImage(){
      return $this->mediaimage;
   }

   /**
    * Ajoute une URL d'image à la liste des médias images du taxon.
    * Cette méthode permet d'ajouter une nouvelle image au taxon en stockant son URL.
    * @param string $urlimage URL de l'image à ajouter à la liste des médias du taxon.
    */

   public function addImage(string $urlimage){
      $this->mediaimage[] = $urlimage;
   }


   /**
    * Constructeur de la classe Taxon.
    * @param int $id Identifiant unique du taxon.
    * @param int|null $parentid Identifiant du parent du taxon.
    * @param string|null $authority Autorité de classification du taxon.
    * @param string $scientificname Nom scientifique du taxon.
    * @param string $fullname Nom complet du taxon.
    * @param string|null $frenchvernacularname Nom vernaculaire français du taxon.
    * @param string $rankname Nom du rang taxonomique du taxon.
    * @param string|null $vernacularClassName Classe vernaculaire du taxon.
    * @param string $vernacularGroup1 Groupe vernaculaire principal du taxon.
    * @param int|null $GBIF Identifiant GBIF du taxon, null si non disponible.
    */
   public function __construct(int $id, ?int $parentid, ?string $authority, string $scientificname, string $fullname, ?string $frenchvernacularname, string $rankname, ?string $vernacularClassName, string $vernacularGroup1, ?int $GBIF) {
      $this->id = $id;
      $this->parentid = $parentid;
      $this->authority = $authority;
      $this->scientificname = $scientificname;
      $this->fullname = $fullname;
      $this->frenchvernacularname = $frenchvernacularname;
      $this->rankname = $rankname;
      $this->vernacularClassName = $vernacularClassName;
      $this->vernacularGroup1 = $vernacularGroup1;
      $this->GBIF = $GBIF;
      $this->mediaimage = [];
   }


  /**
    * Récupère les informations d'un taxon à partir de son identifiant unique via l'API TaxRefAPI.
    * Cette méthode statique permet de créer et retourner une instance de Taxon à partir de son ID.
    * Utilise l'API TaxRefAPI pour récupérer les données du taxon et les médias associés.
    * @param int $id Identifiant unique du taxon à rechercher.
    * @return Taxon|null Une instance de Taxon si le taxon est trouvé, sinon null.
    */
   public static function Taxon_by_ID(int $id){
      $ApiTaxa = new TaxRefAPI();
      $search =  $ApiTaxa->DataByID($id);
      $GBIF = $ApiTaxa->getGBIF($id);
      if (!empty($search)){
      $taxon = new Taxon($search['id'], $search['parentId'], $search['authority'], $search['scientificName'], $search['fullName'], $search['frenchVernacularName'], $search['rankName'], $search['vernacularClassName'], $search['vernacularGroup1'], $GBIF);
      if(isset($search['_links']['media']['href'])){
      $media = $search['_links']['media']['href'];
      $ApiTaxa->setUrl($media);
      $media_image = $ApiTaxa->media();
      if ($media_image){
         foreach($media_image as $image){
            $media_image = $image['_links']['file']['href'];
            $taxon->addImage($media_image);
         }
      }
   }
   return $taxon;
}
return null;
   }

   /**
    * Crée une instance de Taxon à partir d'un tableau de données.
    * Cette méthode statique facilite la création d'un objet Taxon à partir d'un tableau associatif,
    * typiquement retourné par une API ou une base de données.
    * @param array $data Tableau associatif contenant les données du taxon.
    * @return Taxon Une nouvelle instance de Taxon peuplée avec les données fournies.
    */
   public static function Taxon_by_DATA(array $data){
      $ApiTaxa = new TaxRefAPI();
      $taxon = new Taxon($data['id'], $data['parentId'], $data['authority'], $data['scientificName'], $data['fullName'], $data['frenchVernacularName'], $data['rankName'], $data['vernacularClassName'], $data['vernacularGroup1'], NULL);
      if(isset($data['_links']['media']['href'])){
         $media = $data['_links']['media']['href'];
         $ApiTaxa->setUrl($media);
         $media_image = $ApiTaxa->media();
      if ($media_image){
         foreach($media_image as $image){
            $media_image = $image['_links']['file']['href'];
            $taxon->addImage($media_image);
         }
      }
   }
      return $taxon;
   }


   /**
    * Représentation en chaîne de caractères de l'instance de Taxon.
    * Fournit une représentation textuelle détaillée d'un taxon, utile pour le débogage ou l'affichage direct.
    * @return string Une chaîne décrivant le taxon avec ses principales propriétés.
    */
   public function __toString() {
      return "<p> id : \n " . $this->id . 
          ($this->parentid ? "\n <br> parentid : \n " . $this->parentid : "") .
          "\n <br> authority :\n " . $this->authority . 
          "\n <br> scientificname :  \n" . $this->scientificname . 
          "\n<br> fullname : \n" . $this->fullname . 
          ($this->frenchvernacularname ? "\n <br> frenchvernacularname : \n " . $this->frenchvernacularname : "") .
          "\n <br> rankname : \n" . $this->rankname . 
          "\n <br> vernacularClassName : \n" . $this->vernacularClassName . 
          "\n <br> vernacularGroup1 : \n" . $this->vernacularGroup1 . " \n <br> GBIF : \n" . $this->GBIF . "</p></div>";
  }


    
}