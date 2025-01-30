<?php
namespace App\SAE\Config;
require_once __DIR__ . '/../vendor/autoload.php'; 
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
  class Conf {
   static private $databases;
   static public function init(): void {
       if (is_null(static::$databases)) {
           static::$databases = array(
               'hostname' => getenv('hostname') ?: 'localhost',
               'database' => getenv('database') ?: 'sae3.01',
               'login' => getenv('login') ?: 'root',
               'password' => getenv('password') ?: ''
           );
       }
   }
  static public function getHostname() : string {
    return static::$databases['hostname'];
    }
  static public function getDatabase() : string {
      return static::$databases['database'];
  }
  static public function getLogin() : string {
  return static::$databases['login'];
  }
  static public function getPassword() : string {
    return static::$databases['password'];
    }
  }
?>
