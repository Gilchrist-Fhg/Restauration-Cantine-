<?php
session_start();
require_once 'connexion_bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password !== $password_confirm) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } else {
        $sql = "SELECT id FROM utilisateurs WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $erreur = "Cet email existe déjà.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql2 = "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("sss", $nom, $email, $hash);
            
            if ($stmt2->execute()) {
                header("Location: login.php?success=1");
                exit();
            } else {
                $erreur = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Cantine</title>
</head>
<body>

    <h2>Inscription Cantine</h2>

    <?php if(isset($erreur)) { echo "<p>$erreur</p>"; } ?>

    <form method="POST">
        <label>Nom complet :</label><br>
        <input type="text" name="nom" required><br><br>

        <label>Email :</label><br>
        <input type="email" name="email" required><br><br>

        <label>Mot de passe :</label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirmer mot de passe :</label><br>
        <input type="password" name="password_confirm" required><br><br>

        <button type="submit">S'inscrire</button>
    </form>

    <br>
    <a href="login.php">Déjà un compte ? Se connecter</a>

</body>
</html>
