<?php
require_once 'Conexion.php';

class Usuario extends Conexion {
    private $conexion;

    public function __construct() {
        $this->conexion = parent::getConexion();
    }

    public function getAll(): array {
        try {
            $consulta = $this->conexion->prepare('SELECT id, nombreuser FROM usuarios');
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function add($params = []): bool {
        try {
            $sql = "INSERT INTO usuarios (nombreuser, passworduser) VALUES (?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$params["nombreuser"], $params["passworduser"]]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByUsername($nombreuser): array {
        try {
            $sql = "SELECT * FROM usuarios WHERE nombreuser = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$nombreuser]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }
    
    
}

$user = new Usuario();
// // // $datos = [
// // //     "nombreuser" => "Guliana",
   
// // // ];

// var_dump($user->getUserByUsername("josue123"));
// var_dump($user->add(["nombreuser" => "jj", "passworduser" => "df5"]));
// var_dump($user->getAll());
