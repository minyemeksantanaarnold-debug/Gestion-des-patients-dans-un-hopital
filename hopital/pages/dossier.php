<?php
require_once '../includes/config.php';
requireLogin();
$db = getDB();

$pid = intval($_GET['id'] ?? 0);
if (!$pid) redirect('patients.php');

$stmt = $db->prepare("SELECT p.*, d.id_dossier, d.antecedents, d.allergies, d.statut, d.date_creation FROM patient p LEFT JOIN dossier d ON p.id_patient=d.id_patient WHERE p.id_patient=?");
$stmt->bind_param("i",$pid);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if(!$data) redirect('patients.php');

$did = $data['id_dossier'];

// Consultations
$consults = $db->query("SELECT c.*, m.nom, m.prenom, m.specialite FROM consultation c JOIN medecin m ON c.id_medecin=m.id_medecin WHERE c.id_dossier=$did ORDER BY c.date_consultation DESC");

// Ordonnances
$ordos = $db->query("SELECT o.*, m.nom, m.prenom FROM ordonnance o JOIN medecin m ON o.id_medecin=m.id_medecin WHERE o.id_dossier=$did ORDER BY o.date_prescription DESC");

// Maladies
$maladies = $db->query("SELECT ma.* FROM maladie ma JOIN dossier_maladie dm ON ma.id_maladie=dm.id_maladie WHERE dm.id_dossier=$did");

// Médecins disponibles pour nouvelle consultation
$medecins = $db->query("SELECT * FROM medecin ORDER BY nom");

// Nouvelle consultation
$consult_success = '';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_consultation'])) {
    $id_med = intval($_POST['id_medecin']);
    $diag = sanitize($_POST['diagnostic']);
    $obs = sanitize($_POST['observations']);
    $stmt2 = $db->prepare("INSERT INTO consultation (id_medecin,id_dossier,diagnostic,observations) VALUES(?,?,?,?)");
    $stmt2->bind_param("iiss",$id_med,$did,$diag,$obs);
    $stmt2->execute();
    $consult_success = "Consultation enregistrée !";
    redirect("dossier.php?id=$pid&ok=1");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dossier <?= sanitize($data['nom']) ?> — HôpitalSys</title>
<?php include '../includes/head_styles.php'; ?>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="main">
  <?php include '../includes/topbar.php'; ?>
  <div class="content">

    <?php if(isset($_GET['new'])): ?><div class="alert success">✅ Patient et dossier créés avec succès !</div><?php endif; ?>
    <?php if(isset($_GET['ok'])): ?><div class="alert success">✅ Consultation enregistrée !</div><?php endif; ?>

    <div class="page-header">
      <div>
        <div class="page-tag">Dossier patient #<?= $did ?></div>
        <h1><?= sanitize($data['nom']) ?> <?= sanitize($data['prenom']) ?></h1>
      </div>
      <div style="display:flex;gap:10px">
        <span class="status <?= strtolower(str_replace([' ','é'],['_','e'],$data['statut']??'ouvert')) ?>" style="padding:8px 16px"><?= $data['statut']??'Ouvert' ?></span>
        <a href="ajouter_patient.php?edit=<?= $pid ?>" class="btn-sm">✏️ Modifier</a>
        <a href="patients.php" class="btn-sm">← Retour</a>
      </div>
    </div>

    <!-- INFOS PATIENT -->
    <div class="card">
      <div class="card-head"><h3>👤 Informations personnelles</h3></div>
      <div style="padding:24px">
        <div class="detail-grid">
          <div class="detail-item"><label>Sexe</label><span><?= $data['sexe']==='M'?'♂ Masculin':'♀ Féminin' ?></span></div>
          <div class="detail-item"><label>Date de naissance</label><span><?= $data['date_naissance']?date('d/m/Y',strtotime($data['date_naissance'])):'—' ?></span></div>
          <div class="detail-item"><label>Téléphone</label><span><?= sanitize($data['telephone']??'—') ?></span></div>
          <div class="detail-item"><label>Groupe sanguin</label><span><span class="badge"><?= sanitize($data['groupe_sanguin']??'?') ?></span></span></div>
          <div class="detail-item"><label>Adresse</label><span><?= sanitize($data['adresse']??'—') ?></span></div>
          <div class="detail-item"><label>Dossier créé le</label><span><?= $data['date_creation']?date('d/m/Y',strtotime($data['date_creation'])):'—' ?></span></div>
        </div>
        <div style="margin-top:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div>
            <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:#5a6a80;margin-bottom:6px">Antécédents</div>
            <div style="background:#0f1923;padding:14px;border-radius:2px;font-size:13px;color:#c8d4e0;min-height:60px"><?= nl2br(sanitize($data['antecedents']??'Aucun antécédent renseigné')) ?></div>
          </div>
          <div>
            <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:#5a6a80;margin-bottom:6px">Allergies</div>
            <div style="background:#0f1923;padding:14px;border-radius:2px;font-size:13px;color:#c8d4e0;min-height:60px"><?= nl2br(sanitize($data['allergies']??'Aucune allergie renseignée')) ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- MALADIES -->
    <?php if($maladies->num_rows > 0): ?>
    <div class="card">
      <div class="card-head"><h3>🦠 Maladies diagnostiquées</h3></div>
      <div style="padding:16px 24px;display:flex;gap:8px;flex-wrap:wrap">
        <?php while($m=$maladies->fetch_assoc()): ?>
        <span style="background:rgba(192,57,43,0.15);color:#e74c3c;padding:6px 14px;border-radius:2px;font-size:13px">
          <?= sanitize($m['nom_maladie']) ?> <span style="color:#5a6a80;font-size:11px"><?= $m['code_CIM'] ?></span>
        </span>
        <?php endwhile; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- NOUVELLE CONSULTATION -->
    <div class="card">
      <div class="card-head"><h3>➕ Enregistrer une consultation</h3></div>
      <div style="padding:24px">
        <form method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label>Médecin *</label>
              <select name="id_medecin" required>
                <option value="">— Choisir un médecin —</option>
                <?php $medecins->data_seek(0); while($m=$medecins->fetch_assoc()): ?>
                <option value="<?=$m['id_medecin']?>"><?= sanitize($m['nom']) ?> <?= sanitize($m['prenom']) ?> — <?= sanitize($m['specialite']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Diagnostic</label>
            <textarea name="diagnostic" placeholder="Résultat de la consultation, diagnostic posé..."></textarea>
          </div>
          <div class="form-group">
            <label>Observations</label>
            <textarea name="observations" placeholder="Notes complémentaires, examens demandés..."></textarea>
          </div>
          <button type="submit" name="add_consultation" class="btn-primary">🩺 Enregistrer la consultation</button>
        </form>
      </div>
    </div>

    <!-- HISTORIQUE CONSULTATIONS -->
    <div class="card">
      <div class="card-head"><h3>📅 Historique des consultations</h3></div>
      <table class="data-table">
        <thead><tr><th>Date</th><th>Médecin</th><th>Spécialité</th><th>Diagnostic</th><th>Observations</th></tr></thead>
        <tbody>
          <?php if($consults->num_rows === 0): ?>
          <tr><td colspan="5" style="text-align:center;color:#5a6a80;padding:24px">Aucune consultation enregistrée</td></tr>
          <?php else: while($c=$consults->fetch_assoc()): ?>
          <tr>
            <td><?= date('d/m/Y H:i',strtotime($c['date_consultation'])) ?></td>
            <td><strong style="color:white">Dr. <?= sanitize($c['nom']) ?> <?= sanitize($c['prenom']) ?></strong></td>
            <td><span class="badge"><?= sanitize($c['specialite']) ?></span></td>
            <td><?= sanitize($c['diagnostic']??'—') ?></td>
            <td><?= sanitize($c['observations']??'—') ?></td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

    <!-- ORDONNANCES -->
    <div class="card">
      <div class="card-head"><h3>💊 Ordonnances</h3></div>
      <table class="data-table">
        <thead><tr><th>Date</th><th>Médecin</th><th>Médicaments</th><th>Posologie</th><th>Durée</th></tr></thead>
        <tbody>
          <?php if($ordos->num_rows === 0): ?>
          <tr><td colspan="5" style="text-align:center;color:#5a6a80;padding:24px">Aucune ordonnance</td></tr>
          <?php else: while($o=$ordos->fetch_assoc()): ?>
          <tr>
            <td><?= date('d/m/Y',strtotime($o['date_prescription'])) ?></td>
            <td>Dr. <?= sanitize($o['nom']) ?> <?= sanitize($o['prenom']) ?></td>
            <td><?= sanitize($o['medicaments']) ?></td>
            <td><?= sanitize($o['posologie']??'—') ?></td>
            <td><?= $o['duree'] ? $o['duree'].' jours' : '—' ?></td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
</body>
</html>
