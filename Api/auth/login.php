<?php
header("Content-Type: application/json");
require_once '../../connexion_bdd.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if(empty($email) || empty($password)){
    echo json_encode(["status" => "error", "message" => "Email et mot de passe requis"]);
    exit();
}

$sql = "SELECT * FROM utilisateurs WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 1){
    $user = $result->fetch_assoc();
    if(password_verify($password, $user['mot_de_passe'])){
        echo json_encode([
            "status" => "success",
            "message" => "Connexion reussie",
            "user" => ["id" => $user['id'], "email" => $user['email'], "role" => $user['role']]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Mot de passe incorrect"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Utilisateur non trouve"]);
}
?>
