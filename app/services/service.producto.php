<?php
require_once "../models/Producto.php";

$producto = new Producto();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

$metodo = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';

switch ($endpoint) {
    case 'getProducts':
        $response = isset($_GET['id']) ? $producto->getById(intval($_GET['id'])) : $producto->getAll();
        http_response_code(200);
        echo json_encode($response);
        break;

    case 'addProduct':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data["tipo"], $data["genero"], $data["talla"], $data["precio"])) {
            $status = $producto->add($data);
            http_response_code($status ? 201 : 500);
            echo json_encode(["success" => $status]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
        }
        break;

    case 'updateProduct':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data["id"], $data["tipo"], $data["genero"], $data["talla"], $data["precio"])) {
            $status = $producto->update($data);
            http_response_code($status ? 200 : 500);
            echo json_encode(["success" => $status]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
        }
        break;

    case 'deleteProduct':
        if (isset($_GET['id'])) {
            $status = $producto->delete(intval($_GET['id']));
            http_response_code($status ? 200 : 500);
            echo json_encode(["success" => $status]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "ID requerido para eliminar"]);
        }
        break;


}
