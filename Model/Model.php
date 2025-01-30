<?php 
namespace App\SAE\Model;
use App\SAE\Config\Conf;
use \PDO;

/**
 * Classe Model pour la gestion de la connexion à la base de données.
 * Implémente le modèle Singleton pour assurer une unique instance de connexion.
 */

class Model{
  /** @var PDO Instance de PDO pour la connexion à la base de données. */
  private $pdo;

  /** @var Model|null Instance statique de la classe pour le singleton. */
  private static $instance = null;

  
  /**
   * Retourne l'instance PDO pour la connexion à la base de données.
   * @return PDO Instance de PDO pour les opérations de base de données.
   */
  public static function getPdo(){
    return static::getInstance()->pdo;
  }

    /**
     * Constructeur privé pour initialiser la connexion PDO.
     * Utilise la configuration définie dans la classe Conf pour établir la connexion.
     * Le constructeur est privé pour empêcher l'instanciation directe et assurer le modèle Singleton.
     */
  private function __construct(){
    Conf::init();
    $hostname = Conf::getHostname();
    $databaseName = Conf::getDatabase();
    $login = Conf::getLogin();
    $password = Conf::getPassword();
    $this->pdo = new PDO("mysql:host=$hostname;dbname=$databaseName",$login,$password);
    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
    return $this->pdo;
  }


    /**
     * Obtient ou crée l'instance unique de Model.
     * Cette méthode statique vérifie si une instance de Model existe déjà.
     * Si non, elle crée une nouvelle instance et l'initialise avec une connexion PDO.
     * @return Model L'unique instance de la classe Model.
     */
  public static function getInstance() {
    if (is_null(static::$instance)){
      static::$instance = new Model();
    }
    return static::$instance;
  }
}