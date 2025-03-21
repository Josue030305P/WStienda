<?php

require_once 'Conexion.php';


class Producto extends Conexion {


  private $conexion;


  public function __construct(){
    $this->conexion = parent::getConexion();
  }

  public function add( $params = []) : bool {

    $saveStatus = false;

    try {

      $sql = "INSERT INTO productos(tipo,genero,talla,precio) VALUES(?,?,?,?)";
      $consulta = $this->conexion->prepare($sql);
      $consulta->execute(array(
          $params['tipo'],
          $params['genero'],
          $params['talla'],
          $params['precio']

      ));

      if ($consulta->rowCount() > 0) {
        $saveStatus = true;
      }

      return $saveStatus;


    }
    catch(Exception $e) {
      error_log($e->getMessage());
      return false;
    }

  }


  public function getAll() : array {
    try {
      $consulta = $this->conexion->prepare('SELECT tipo, genero, talla, precio FROM productos ORDER BY id DESC');
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
      return ['code' => 0, 'msg' => $e->getMessage()];
    }
  }

}

// $producto = new Producto();
// $datos = [
//   'tipo' => 'Polo',
//   'genero' => 'Femenino',
//   'talla' => 'M',
//   'precio' => 1200
// ];

// echo json_encode($producto->add($datos));

// $producto = new Producto();

// echo json_encode($producto->getAll());