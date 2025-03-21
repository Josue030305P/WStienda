<?php

require_once "../config/Server.php";

const METHOD = "AES-256-CBC";
const SECRET_KEY = "_S3N@t1."; // Ofucar la palabra Senati
const SECRET_IV = "037970";

// Temporalmente los parámetros de conexión estarán en la clase
// hasta arreglar el problema que genera llamarlo desde REPORTES

class Conexion {

  protected static function getConexion() {
    try {
      $pdo = new PDO(SGBD, USER, PASS);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $pdo;
    } catch (Exception $e) {
      // Es mejor registrar el error en un log en lugar de solo "morir"
      error_log($e->getMessage()); // Esto registra el error en el log del servidor
      throw new Exception("No se pudo conectar a la base de datos.");
    }
  }

  public static function ejecutarConsultaSimple($consulta) {
    try {
      $sql = self::getConexion()->prepare($consulta);
      $sql->execute();
      return $sql;
    } catch (PDOException $e) {
      // Manejo de error en la ejecución de la consulta
      error_log($e->getMessage());
      return null;
    }
  }

  public function encryption($string) {
    $output = false;
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);  // El IV debe tener 16 bytes en AES-256-CBC

    // Encriptar
    $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
    if ($output === false) {
      error_log("Error en la encriptación.");
      return false;
    }
    $output = base64_encode($output);
    return $output;
  }

  protected static function decryption($string) {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);

    // Desencriptar
    $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
    if ($output === false) {
      error_log("Error en la desencriptación.");
      return false;
    }
    return $output;
  }

  protected static function generarCodigoAleatorio($letra, $longitud, $numero) {
    for ($i = 1; $i <= $longitud; $i++) {
      $aleatorio = rand(0, 9);
      $letra .= $aleatorio;
    }
    return $letra . "-" . $numero;
  }

  public static function limpiarCadena($cadena) {
    $cadena = trim($cadena);
    $cadena = stripslashes($cadena);
    $cadena = str_ireplace("<script>", "", $cadena);
    $cadena = str_ireplace("</script>", "", $cadena);
    $cadena = str_ireplace("<script src", "", $cadena);
    $cadena = str_ireplace("<script type", "", $cadena);
    $cadena = str_ireplace("SELECT * FROM", "", $cadena);
    $cadena = str_ireplace("DELETE FROM", "", $cadena);
    $cadena = str_ireplace("INSERT INTO", "", $cadena);
    $cadena = str_ireplace("DROP TABLE", "", $cadena);
    $cadena = str_ireplace("DROP DATABASE", "", $cadena);
    $cadena = str_ireplace("TRUNCATE TABLE", "", $cadena);
    $cadena = str_ireplace("SHOW TABLES", "", $cadena);
    $cadena = str_ireplace("SHOW DATABASES", "", $cadena);
    $cadena = str_ireplace("<?php", "", $cadena);
    $cadena = str_ireplace("?>", "", $cadena);
    $cadena = str_ireplace("--", "", $cadena);
    $cadena = str_ireplace(">", "", $cadena);
    $cadena = str_ireplace("<", "", $cadena);
    $cadena = str_ireplace("[", "", $cadena);
    $cadena = str_ireplace("]", "", $cadena);
    $cadena = str_ireplace("^", "", $cadena);
    $cadena = str_ireplace("==", "", $cadena);
    $cadena = str_ireplace(";", "", $cadena);
    $cadena = str_ireplace("::", "", $cadena);
    $cadena = stripslashes($cadena);
    $cadena = trim($cadena);
    return $cadena;
  }
}
