php
<?php
session_start();
require_once 'connexion_bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Aucun compte trouvé avec cet email.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Cantine</title>
    <style>
        body{font-family:Arial;display:flex;justify-content:center;align-items:center;height:100vh;background:#f4f6f8;margin:0}
        .box{background:#fff;padding:30px;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.1);width:320px}
        h2{text-align:center;color:#2c3e50}
        input{width:100%;padding:11px;margin:10px 0;border:1px solid #ccc;border-radius:6px;box-sizing:border-box}
        button{width:100%;padding:11px;background:#2c3e50;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:bold}
        button:hover{background:#1a252f}
        .error{color:red;text-align:center;font-size:14px}
        a{color:#2c3e50;text-decoration:none;font-size:14px}
    </style>
</head>
<body>
<div class="box">
    <h2>Connexion</h2>
    <?php if(isset($erreur)) echo "<p class='error'>$erreur</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    <p style="text-align:center;margin-top:15px;"><a href="inscription.php">Créer un compte</a> | <a href="mot-de-passe-oublie.php">Mot de passe oublié?</a></p>
</div>
</body>
</html
