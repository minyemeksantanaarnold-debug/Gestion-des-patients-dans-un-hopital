<?php
require_once '../includes/config.php';
requireLogin();

$db = getDB();

$stats = [];
foreach(['patient','dossier','medecin','consultation'] as $table) {
    $r = $db->query("SELECT COUNT(*) as c FROM $table");
    $stats[$table] = $r->fetch_assoc()['c'];
}

// Derniers patients
$recent = $db->query("SELECT p.*, d.statut FROM patient p LEFT JOIN dossier d ON p.id_patient = d.id_patient ORDER BY p.created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — HôpitalSys</title>
<?php include '../includes/head_styles.php'; ?>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/topbar.php'; ?>
  <div class="content">
    <div class="page-header">
      <div>
        <div class="page-tag">Vue d'ensemble</div>
        <h1>Tableau de bord</h1>
      </div>
      <a href="ajouter_patient.php" class="btn-primary">+ Nouveau Patient</a>
    </div>

    <!-- STATS -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(192,57,43,0.15);color:#e74c3c">🧑‍🤝‍🧑</div>
        <div class="stat-info">
          <div class="stat-num"><?= $stats['patient'] ?></div>
          <div class="stat-label">Patients enregistrés</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(39,174,96,0.15);color:#27ae60">📋</div>
        <div class="stat-info">
          <div class="stat-num"><?= $stats['dossier'] ?></div>
          <div class="stat-label">Dossiers ouverts</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(52,152,219,0.15);color:#3498db">👨‍⚕️</div>
        <div class="stat-info">
          <div class="stat-num"><?= $stats['medecin'] ?></div>
          <div class="stat-label">Médecins</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(155,89,182,0.15);color:#9b59b6">🩺</div>
        <div class="stat-info">
          <div class="stat-num"><?= $stats['consultation'] ?></div>
          <div class="stat-label">Consultations</div>
        </div>
      </div>
    </div>

    <!-- RECENT PATIENTS -->
    <div class="card">
      <div class="card-head">
        <h3>Derniers patients enregistrés</h3>
        <a href="patients.php" class="link-more">Voir tous →</a>
      </div>
      <table class="data-table">
        <thead>
          <tr><th>Nom & Prénom</th><th>Sexe</th><th>Téléphone</th><th>Groupe sanguin</th><th>Statut dossier</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php while($p = $recent->fetch_assoc()): ?>
          <tr>
            <td><strong><?= sanitize($p['nom']) ?> <?= sanitize($p['prenom']) ?></strong></td>
            <td><?= $p['sexe'] === 'M' ? '♂ Masculin' : '♀ Féminin' ?></td>
            <td><?= sanitize($p['telephone'] ?? '—') ?></td>
            <td><span class="badge"><?= sanitize($p['groupe_sanguin'] ?? '?') ?></span></td>
            <td><span class="status <?= strtolower(str_replace(' ','_',$p['statut'] ?? 'ouvert')) ?>"><?= $p['statut'] ?? 'Ouvert' ?></span></td>
            <td><a href="dossier.php?id=<?= $p['id_patient'] ?>" class="btn-sm">Voir dossier</a></td>
          </tr>
          <?php endwhile; ?>
          <?php if($stats['patient'] == 0): ?>
          <tr><td colspan="6" style="text-align:center;color:#5a6a80;padding:30px">Aucun patient enregistré</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
