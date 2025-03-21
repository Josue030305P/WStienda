<?php
require_once "../models/Producto.php";
$producto = new Producto();

header('Access-Control-Allow-Origin');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-type: application/json; charset=utf-8");

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'GET') {
  $registros = [];
  if (isset($_GET['q']) && $_GET['q'] == 'showAll') {
    
    $registros = $producto->getAll(); 

  }

  header('HTTP/1.1 200 OK');
  echo json_encode($registros);  

} else if ($metodo == 'POST') {

  if (isset($_POST) && !empty($_POST)) {
    $datos = $_POST; // Lo que mande en POST
  } else {
    $rawData = file_get_contents("php://input");  
    $datos = json_decode($rawData, true); 
  }

  // Verificar que los datos requeridos estén presentes
  if (isset($datos["tipo"], $datos["genero"], $datos["talla"], $datos["precio"])) {
    $registro = [  
      "tipo" => $datos["tipo"],
      "genero" => $datos["genero"],
      "talla" => $datos["talla"],
      "precio" => $datos["precio"],
    ];

    
    $status = $producto->add($registro);
    header('HTTP/1.1 200 OK');  
    echo json_encode(["status" => $status]);
  } else {
    echo json_encode(["status" => false, "message" => "Faltan datos en la solicitud."]);
  }

}
?>
