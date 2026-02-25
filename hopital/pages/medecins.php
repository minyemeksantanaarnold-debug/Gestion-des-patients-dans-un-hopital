<?php
require_once '../includes/config.php';
requireLogin();
$db = getDB();
$success = $error = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $nom=sanitize($_POST['nom']??''); $prenom=sanitize($_POST['prenom']??'');
    $spe=sanitize($_POST['specialite']??''); $tel=sanitize($_POST['telephone']??'');
    $email=sanitize($_POST['email']??''); $ext=isset($_POST['externe'])?1:0;
    $id_svc=intval($_POST['id_service']??0);
    if(!$nom||!$prenom){ $error="Nom et prénom obligatoires."; }
    else {
        $stmt=$db->prepare("INSERT INTO medecin (nom,prenom,specialite,telephone,email,externe,id_service) VALUES(?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssii",$nom,$prenom,$spe,$tel,$email,$ext,$id_svc);
        $stmt->execute(); $success="Médecin ajouté !";
    }
}
$medecins=$db->query("SELECT m.*,s.nom_service FROM medecin m LEFT JOIN service s ON m.id_service=s.id_service ORDER BY m.nom");
$services=$db->query("SELECT * FROM service ORDER BY nom_service");
?>
<!DOCTYPE html>
<html lang="fr"><head><meta charset="UTF-8"><title>Médecins — HôpitalSys</title><?php include '../includes/head_styles.php'; ?></head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="main"><?php include '../includes/topbar.php'; ?>
<div class="content">
  <div class="page-header"><div><div class="page-tag">Gestion</div><h1>Médecins</h1></div></div>
  <?php if($success): ?><div class="alert success">✅ <?=$success?></div><?php endif; ?>
  <?php if($error): ?><div class="alert error">⚠️ <?=$error?></div><?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 380px;gap:24px">
    <div class="card">
      <div class="card-head"><h3>Liste des médecins</h3></div>
      <table class="data-table">
        <thead><tr><th>Nom</th><th>Spécialité</th><th>Téléphone</th><th>Service</th><th>Type</th></tr></thead>
        <tbody>
          <?php while($m=$medecins->fetch_assoc()): ?>
          <tr>
            <td><strong style="color:white">Dr. <?=sanitize($m['nom'])?> <?=sanitize($m['prenom'])?></strong></td>
            <td><?=sanitize($m['specialite']??'—')?></td>
            <td><?=sanitize($m['telephone']??'—')?></td>
            <td><?=sanitize($m['nom_service']??'—')?></td>
            <td><span class="badge" style="<?=$m['externe']?'background:rgba(240,144,74,0.15);color:#f0904a':''?>"><?=$m['externe']?'Externe':'Interne'?></span></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div>
      <div class="form-card" style="max-width:100%">
        <div style="font-family:'Syne',sans-serif;font-weight:700;color:white;font-size:15px;margin-bottom:20px">➕ Ajouter un médecin</div>
        <form method="POST">
          <div class="form-group"><label>Nom *</label><input type="text" name="nom" placeholder="Nom" required></div>
          <div class="form-group"><label>Prénom *</label><input type="text" name="prenom" placeholder="Prénom" required></div>
          <div class="form-group"><label>Spécialité</label><input type="text" name="specialite" placeholder="Ex: Cardiologie"></div>
          <div class="form-group"><label>Téléphone</label><input type="tel" name="telephone" placeholder="6XX XXX XXX"></div>
          <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="email@hopital.cm"></div>
          <div class="form-group"><label>Service</label>
            <select name="id_service"><option value="">— Aucun —</option>
              <?php $services->data_seek(0); while($s=$services->fetch_assoc()): ?>
              <option value="<?=$s['id_service']?>"><?=sanitize($s['nom_service'])?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="form-group" style="display:flex;align-items:center;gap:10px">
            <input type="checkbox" name="externe" id="ext" style="width:auto">
            <label for="ext" style="text-transform:none;letter-spacing:0;font-size:13px;color:#c8d4e0;margin:0">Médecin externe (venant d'un autre hôpital)</label>
          </div>
          <button type="submit" class="btn-primary" style="width:100%">Ajouter</button>
        </form>
      </div>
    </div>
  </div>
</div></div>
</body></html>
