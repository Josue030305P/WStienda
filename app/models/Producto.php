<?php
require_once 'Conexion.php';

class Producto extends Conexion {
    private $conexion;

    public function __construct(){
        $this->conexion = parent::getConexion();
    }

    public function add($params = []): bool {
        try {
            $sql = "INSERT INTO productos(tipo, genero, talla, precio) VALUES(?,?,?,?)";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([$params['tipo'], $params['genero'], $params['talla'], $params['precio']]);
            return $consulta->rowCount() > 0;
        } catch(Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getAll(): array {
        try {
            $consulta = $this->conexion->prepare('SELECT id, tipo, genero, talla, precio FROM productos ORDER BY id DESC');
            $consulta->execute();
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function getById($id): array {
        try {
            $consulta = $this->conexion->prepare('SELECT id, tipo, genero, talla, precio FROM productos WHERE id = ?');
            $consulta->execute([$id]);
            return $consulta->fetch(PDO::FETCH_ASSOC) ?: ['code' => 0, 'msg' => 'Producto no encontrado'];
        } catch (PDOException $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }

    public function update($params = []): bool {
        try {
            $sql = "UPDATE productos SET tipo=?, genero=?, talla=?, precio=? WHERE id=?";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([$params['tipo'], $params['genero'], $params['talla'], $params['precio'], $params["id"]]);
            return $consulta->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function delete($id): bool {
        try {
            $sql = "DELETE FROM productos WHERE id = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al eliminar producto: " . $e->getMessage());
            return false;
        }
    }
    
}

// $producto = new Producto();
// // $datos = [
// //   "tipo" => "Camisa",
// //   "genero" => "Masculino",
// //   "talla" => "M" ,
// //   "precio" => 269,
// //   "id" => 3
// // ];

// // var_dump($producto->update($datos));
// var_dump($producto->delete("1"));