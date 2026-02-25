<?php
require_once 'includes/config.php';
if (isLoggedIn()) redirect('pages/dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = sanitize($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM receptionniste WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id_reception'];
        $_SESSION['user_nom'] = $user['prenom'] . ' ' . $user['nom'];
        redirect('pages/dashboard.php');
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion — HôpitalSys</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap');
* { margin:0; padding:0; box-sizing:border-box; }
body {
  font-family: 'DM Sans', sans-serif;
  background: #0f1923;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
.bg-pattern {
  position: fixed; inset: 0;
  background: 
    radial-gradient(ellipse at 20% 50%, rgba(192,57,43,0.15) 0%, transparent 50%),
    radial-gradient(ellipse at 80% 20%, rgba(44,62,80,0.4) 0%, transparent 50%);
}
.login-box {
  position: relative;
  background: #1a2535;
  border: 1px solid #2a3545;
  border-radius: 4px;
  padding: 48px 40px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 40px 80px rgba(0,0,0,0.4);
}
.logo {
  display: flex; align-items: center; gap: 12px;
  margin-bottom: 32px;
}
.logo-icon {
  width: 44px; height: 44px;
  background: #c0392b;
  border-radius: 2px;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px;
}
.logo-text {
  font-family: 'Syne', sans-serif;
  font-weight: 800;
  font-size: 20px;
  color: white;
}
.logo-text span { color: #e74c3c; }
h2 {
  font-family: 'Syne', sans-serif;
  font-size: 26px;
  font-weight: 800;
  color: white;
  margin-bottom: 6px;
}
.subtitle { color: #5a6a80; font-size: 14px; margin-bottom: 32px; }
.form-group { margin-bottom: 20px; }
label { display: block; font-size: 12px; font-weight: 500; color: #8a9ab0; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
input {
  width: 100%;
  background: #0f1923;
  border: 1px solid #2a3545;
  border-radius: 2px;
  padding: 14px 16px;
  color: white;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  transition: border-color 0.2s;
}
input:focus { outline: none; border-color: #c0392b; }
input::placeholder { color: #3a4a5a; }
.btn {
  width: 100%;
  background: #c0392b;
  color: white;
  border: none;
  padding: 15px;
  font-family: 'Syne', sans-serif;
  font-weight: 700;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  cursor: pointer;
  border-radius: 2px;
  transition: background 0.2s;
  margin-top: 8px;
}
.btn:hover { background: #a93226; }
.error {
  background: rgba(192,57,43,0.15);
  border: 1px solid rgba(192,57,43,0.3);
  color: #e74c3c;
  padding: 12px 16px;
  border-radius: 2px;
  font-size: 13px;
  margin-bottom: 20px;
}
.hint { color: #3a4a5a; font-size: 12px; text-align: center; margin-top: 20px; }
.hint strong { color: #5a6a80; }
</style>
</head>
<body>
<div class="bg-pattern"></div>
<div class="login-box">
  <div class="logo">
    <div class="logo-icon">🏥</div>
    <div class="logo-text">Hôpital<span>Sys</span></div>
  </div>
  <h2>Connexion</h2>
  <p class="subtitle">Accès réservé au personnel autorisé</p>
  
  <?php if ($error): ?>
  <div class="error">⚠️ <?= $error ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label>Identifiant</label>
      <input type="text" name="login" placeholder="Votre login" required autofocus>
    </div>
    <div class="form-group">
      <label>Mot de passe</label>
      <input type="password" name="password" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn">Se connecter →</button>
  </form>
  <p class="hint">Test : <strong>admin</strong> / <strong>password</strong></p>
</div>
</body>
</html>
