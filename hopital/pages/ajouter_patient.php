<?php
require_once '../includes/config.php';
requireLogin();
$db = getDB();

$edit_id = intval($_GET['edit'] ?? 0);
$patient = null;
$success = '';
$error = '';

if ($edit_id) {
    $stmt = $db->prepare("SELECT * FROM patient WHERE id_patient = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $patient = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = sanitize($_POST['nom'] ?? '');
    $prenom = sanitize($_POST['prenom'] ?? '');
    $dob = $_POST['date_naissance'] ?? '';
    $sexe = $_POST['sexe'] ?? 'M';
    $tel = sanitize($_POST['telephone'] ?? '');
    $adresse = sanitize($_POST['adresse'] ?? '');
    $groupe = sanitize($_POST['groupe_sanguin'] ?? '');
    $antecedents = sanitize($_POST['antecedents'] ?? '');
    $allergies = sanitize($_POST['allergies'] ?? '');

    if (!$nom || !$prenom || !$sexe) {
        $error = "Nom, prénom et sexe sont obligatoires.";
    } else {
        if ($edit_id) {
            $stmt = $db->prepare("UPDATE patient SET nom=?,prenom=?,date_naissance=?,sexe=?,telephone=?,adresse=?,groupe_sanguin=? WHERE id_patient=?");
            $stmt->bind_param("sssssssi", $nom,$prenom,$dob,$sexe,$tel,$adresse,$groupe,$edit_id);
            $stmt->execute();
            $success = "Patient mis à jour avec succès !";
            $patient = array_merge($patient, compact('nom','prenom','dob','sexe','tel','adresse','groupe'));
        } else {
            $stmt = $db->prepare("INSERT INTO patient (nom,prenom,date_naissance,sexe,telephone,adresse,groupe_sanguin) VALUES(?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssss", $nom,$prenom,$dob,$sexe,$tel,$adresse,$groupe);
            $stmt->execute();
            $pid = $db->insert_id;

            // Créer dossier automatiquement
            $uid = $_SESSION['user_id'];
            $stmt2 = $db->prepare("INSERT INTO dossier (id_patient,id_reception,antecedents,allergies) VALUES(?,?,?,?)");
            $stmt2->bind_param("iiss", $pid,$uid,$antecedents,$allergies);
            $stmt2->execute();

            $success = "Patient enregistré et dossier créé avec succès !";
            redirect("dossier.php?id=$pid&new=1");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title><?= $edit_id ? 'Modifier' : 'Nouveau' ?> Patient — HôpitalSys</title>
<?php include '../includes/head_styles.php'; ?>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/topbar.php'; ?>
  <div class="content">
    <div class="page-header">
      <div>
        <div class="page-tag"><?= $edit_id ? 'Modification' : 'Enregistrement' ?></div>
        <h1><?= $edit_id ? 'Modifier le Patient' : 'Nouveau Patient' ?></h1>
      </div>
      <a href="patients.php" class="btn-sm">← Retour</a>
    </div>

    <?php if($success): ?><div class="alert success">✅ <?= $success ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert error">⚠️ <?= $error ?></div><?php endif; ?>

    <form method="POST">
      <div class="form-card">
        <div style="font-family:'Syne',sans-serif;font-weight:700;color:white;font-size:16px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #2a3545;">
          👤 Informations personnelles
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label>Nom *</label>
            <input type="text" name="nom" placeholder="Nom de famille" value="<?= $patient['nom'] ?? '' ?>" required>
          </div>
          <div class="form-group">
            <label>Prénom *</label>
            <input type="text" name="prenom" placeholder="Prénom" value="<?= $patient['prenom'] ?? '' ?>" required>
          </div>
          <div class="form-group">
            <label>Date de naissance</label>
            <input type="date" name="date_naissance" value="<?= $patient['date_naissance'] ?? '' ?>">
          </div>
          <div class="form-group">
            <label>Sexe *</label>
            <select name="sexe">
              <option value="M" <?= ($patient['sexe']??'')=='M'?'selected':'' ?>>♂ Masculin</option>
              <option value="F" <?= ($patient['sexe']??'')=='F'?'selected':'' ?>>♀ Féminin</option>
            </select>
          </div>
          <div class="form-group">
            <label>Téléphone</label>
            <input type="tel" name="telephone" placeholder="6XX XXX XXX" value="<?= $patient['telephone'] ?? '' ?>">
          </div>
          <div class="form-group">
            <label>Groupe sanguin</label>
            <select name="groupe_sanguin">
              <option value="">— Sélectionner —</option>
              <?php foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g): ?>
              <option value="<?=$g?>" <?= ($patient['groupe_sanguin']??'')==$g?'selected':'' ?>><?=$g?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group full">
            <label>Adresse</label>
            <input type="text" name="adresse" placeholder="Quartier, ville..." value="<?= $patient['adresse'] ?? '' ?>">
          </div>
        </div>

        <?php if(!$edit_id): ?>
        <div style="font-family:'Syne',sans-serif;font-weight:700;color:white;font-size:16px;margin:24px 0 16px;padding-top:20px;border-top:1px solid #2a3545;">
          📋 Informations médicales initiales
        </div>
        <div class="form-group">
          <label>Antécédents médicaux</label>
          <textarea name="antecedents" placeholder="Maladies chroniques, opérations passées..."><?= $patient['antecedents'] ?? '' ?></textarea>
        </div>
        <div class="form-group">
          <label>Allergies connues</label>
          <textarea name="allergies" placeholder="Allergies médicamenteuses, alimentaires..."><?= $patient['allergies'] ?? '' ?></textarea>
        </div>
        <?php endif; ?>

        <div style="margin-top:24px;display:flex;gap:12px;">
          <button type="submit" class="btn-primary"><?= $edit_id ? '💾 Enregistrer les modifications' : '✅ Créer le patient & son dossier' ?></button>
          <a href="patients.php" class="btn-sm" style="padding:10px 20px;">Annuler</a>
        </div>
      </div>
    </form>
  </div>
</div>
</body>
</html>
