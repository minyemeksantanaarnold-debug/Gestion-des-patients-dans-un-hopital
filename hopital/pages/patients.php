<?php
require_once '../includes/config.php';
requireLogin();
$db = getDB();

$search = sanitize($_GET['q'] ?? '');
$where = $search ? "WHERE p.nom LIKE '%$search%' OR p.prenom LIKE '%$search%' OR p.telephone LIKE '%$search%'" : '';

$patients = $db->query("
  SELECT p.*, d.id_dossier, d.statut 
  FROM patient p 
  LEFT JOIN dossier d ON p.id_patient = d.id_patient 
  $where
  ORDER BY p.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Patients — HôpitalSys</title>
<?php include '../includes/head_styles.php'; ?>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/topbar.php'; ?>
  <div class="content">
    <div class="page-header">
      <div>
        <div class="page-tag">Gestion</div>
        <h1>Liste des Patients</h1>
      </div>
      <a href="ajouter_patient.php" class="btn-primary">+ Nouveau Patient</a>
    </div>

    <div class="search-bar">
      <form method="GET" style="display:flex;gap:12px;flex:1">
        <input type="text" name="q" placeholder="🔍 Rechercher par nom, prénom, téléphone..." value="<?= $search ?>">
        <button type="submit" class="btn-primary" style="padding:12px 20px;">Chercher</button>
        <?php if($search): ?><a href="patients.php" class="btn-sm" style="padding:12px 16px;">✕</a><?php endif; ?>
      </form>
    </div>

    <div class="card">
      <div class="card-head">
        <h3>Tous les patients</h3>
      </div>
      <table class="data-table">
        <thead>
          <tr>
            <th>N°</th><th>Nom & Prénom</th><th>Date naissance</th>
            <th>Sexe</th><th>Téléphone</th><th>Groupe sanguin</th>
            <th>Statut dossier</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; while($p = $patients->fetch_assoc()): ?>
          <tr>
            <td style="color:#5a6a80"><?= $i++ ?></td>
            <td><strong style="color:white"><?= sanitize($p['nom']) ?> <?= sanitize($p['prenom']) ?></strong></td>
            <td><?= $p['date_naissance'] ? date('d/m/Y', strtotime($p['date_naissance'])) : '—' ?></td>
            <td><?= $p['sexe'] === 'M' ? '♂ M' : '♀ F' ?></td>
            <td><?= sanitize($p['telephone'] ?? '—') ?></td>
            <td><span class="badge"><?= sanitize($p['groupe_sanguin'] ?? '?') ?></span></td>
            <td><span class="status <?= strtolower(str_replace([' ','é'],['_','e'],$p['statut'] ?? 'ouvert')) ?>"><?= $p['statut'] ?? 'Ouvert' ?></span></td>
            <td style="display:flex;gap:6px;padding:10px 20px">
              <a href="dossier.php?id=<?= $p['id_patient'] ?>" class="btn-sm">📋 Dossier</a>
              <a href="ajouter_patient.php?edit=<?= $p['id_patient'] ?>" class="btn-sm">✏️</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
