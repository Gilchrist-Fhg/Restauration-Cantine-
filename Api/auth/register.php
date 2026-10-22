<?php
header("Content-Type: application/json");
require_once '../../connexion_bdd.php';

$data = json_decode(file_get_contents("php://input"), true);
$nom = $data['nom'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if(empty($nom) || empty($email) || empty($password)){
    echo json_encode(["status" => "error", "message" => "Tous les champs sont requis"]);
    exit();
}

$sql = "SELECT id FROM utilisateurs WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    echo json_encode(["status" => "error", "message" => "Email deja existant"]);
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$sql2 = "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("sss", $nom, $email, $hash);

if($stmt2->execute()){
    echo json_encode(["status" => "success", "message" => "Inscription reussie"]);
} else {
    echo json_encode(["status" => "error", "message" => "Erreur lors de l'inscription"]);
}
?>
