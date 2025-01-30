<?php
namespace App\SAE\Model;
use App\SAE\Model\Taxon;

/**
 * Classe TaxRefAPI pour interagir avec l'API externe TaxRef.
 * Fournit des méthodes pour récupérer des données taxonomiques à partir de l'API TaxRef du MNHN.
 */
class TaxRefAPI{
    

    /** @var string|null L'URL de l'API à laquelle les requêtes sont envoyées. */
        private $api_url;

        /**
     * Constructeur de la classe TaxRefAPI.
     */
        public function __construct()
        {
            $this->api_url = null;
        }


    /**
     * Obtient l'URL actuellement définie pour l'API.
     * @return string|null L'URL de l'API.
     */
        public function getUrl(){
            return $this->api_url;
        }

        
    /**
     * Définit l'URL pour l'API.
     * @param string $url L'URL à définir pour l'API.
     */
        public function setUrl($url){
            $this->api_url = $url;
        }


    /**
     * Récupère les données depuis une URL spécifiée.
     * @param string $url L'URL à partir de laquelle récupérer les données.
     * @return array|null Les données récupérées sous forme de tableau, ou null en cas d'échec.
     */
        public function fetchData($url){
            $response = @file_get_contents($url);
            if ($response !== false && !empty($response)){
                $data = json_decode($response, true);
                if ($data !== null){
                    return $data;
                }
            }
            return null;
    }


    /**
     * Construit l'URL pour les requêtes à l'API TaxRef en fonction des paramètres donnés.
     * @param string $dossier Le dossier de l'API à utiliser.
     * @param string $commande La commande spécifique de l'API.
     * @param array $params Les paramètres supplémentaires de la requête.
     */
        public function URL_Taxa($dossier, $commande, $params = []){
            $url = "https://taxref.mnhn.fr/api/" . $dossier . "/" . $commande;
            if (!empty($params)){
                $url .= '?' . http_build_query($params);
            }

            $this->api_url = $url;
        }

            /**
     * Récupère les données d'un taxon spécifique par son ID via l'API TaxRef.
     * @param int $id L'identifiant du taxon.
     * @return array|null Les données du taxon sous forme de tableau, ou null en cas d'échec.
     */

        //Fonction DataByID prenant en paramètres un ID qui permet  de chercher un taxon spécifique avec un ID.
        public function DataByID($id){
            $this->URL_Taxa('taxa', $id, []);
            return $this->fetchData($this->api_url);
        }


    /**
     * Effectue une recherche dans l'API TaxRef selon le critère spécifié.
     * @param string $choix Le champ sur lequel effectuer la recherche.
     * @param string $recherche La valeur à rechercher.
     * @param int $page La page de résultats à afficher.
     * @param int $size Le nombre de résultats par page.
     * @return array|null Liste des taxons correspondant à la recherche, ou null en cas d'échec.
     */
        public function SearchAPI($choix, $recherche, $page, $size){
            $this->URL_Taxa('taxa', 'search', [$choix => $recherche, 'page' => $page, 'size' => $size]);
            $data = $this->fetchData($this->api_url);
            if ($data){
            $listTaxon = $this->DataToListTaxon($data);
            return $listTaxon;
            }else{
                return null;
            }
        }


    /**
     * Obtient l'identifiant GBIF d'un taxon spécifique.
     * @param int $id L'identifiant du taxon.
     * @return int|null L'identifiant GBIF du taxon, ou null si non trouvé.
     */
        public function GetGBIF(int $id){
            $this->URL_Taxa('taxa', $id . '/externalIds', []);
            $tabdata = $this->fetchData($this->api_url);
            foreach($tabdata['_embedded']['externalDb'] as $data){
                if($data['externalDbName'] == 'GBIF'){
                    return $data['externalId'];
                }
            }
            return null;

        }
        /*public function SearchInDatabase(string $choix, string $recherche, int $page, int $size){
            try{
                $sql = "SELECT * FROM  "
            }

        } */ 


    /**
     * Récupère les médias associés à l'URL définie dans l'instance.
     * @return array|null Les données des médias, ou null en cas d'échec.
     */
        public function media(){
            $data = $this->fetchData($this->api_url);
            if (isset($data['_embedded']['media']))  {
                $lien_image = $data['_embedded']['media'];
                if ($lien_image !== null){
                    return $lien_image;
                    }
                }
            }


    /**
     * Convertit les données de l'API en liste d'objets Taxon.
     * @param array $ArrayData Les données récupérées de l'API.
     * @return array|null Liste des objets Taxon, ou null si les données ne contiennent pas de taxons.
     */
        public function DataToListTaxon($ArrayData){
            $listTaxon = [];
            if (isset($ArrayData['_embedded']['taxa'])){
            $ArrayData = $ArrayData['_embedded']['taxa'];
            foreach($ArrayData as $data){
                $taxon = Taxon::Taxon_by_DATA($data);
                $listTaxon[] = $taxon;                
            }
            return $listTaxon;
        }else{
            return null;
        }
    }
}
    ?>