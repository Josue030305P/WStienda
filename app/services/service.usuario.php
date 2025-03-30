<?php
require_once "../models/Usuario.php";

$usuario = new Usuario();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

$metodo = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';

switch ($endpoint) {
    case 'getUsers':
        $response = $usuario->getAll();
        http_response_code(200);
        echo json_encode($response);
        break;

    case 'addUser':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data["nombreuser"], $data["passworduser"])) {
            
            $data["passworduser"] = password_hash($data["passworduser"], PASSWORD_BCRYPT);
            $status = $usuario->add($data);
            http_response_code($status ? 201 : 500);
            echo json_encode(["success" => $status]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
        }
        break;

        case 'login':
            $data = json_decode(file_get_contents("php://input"), true);
        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(["error" => "Formato JSON inválido"]);
            exit;
        }

        if (!isset($data['nombreuser'], $data['passworduser'])) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
            exit;
        }

        $userData = $usuario->getUserByUsername($data['nombreuser']);

        if (!$userData || !password_verify($data['passworduser'], $userData['passworduser'])) {
            http_response_code(401);
            echo json_encode(["success" => false, "error" => "Credenciales incorrectas"]);
            exit;
        }

        echo json_encode(["success" => true]);
        break;

        

}
