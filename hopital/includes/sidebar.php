<div class="sidebar">
  <div class="sidebar-logo">
    <div class="icon">🏥</div>
    <div class="name">Hôpital<span>Sys</span></div>
  </div>
  <div class="nav-label">Navigation</div>
  <?php $current = basename($_SERVER['PHP_SELF']); ?>
  <a href="dashboard.php" class="nav-item <?= $current=='dashboard.php'?'active':'' ?>"><span class="icon">📊</span> Dashboard</a>
  <a href="patients.php" class="nav-item <?= $current=='patients.php'?'active':'' ?>"><span class="icon">🧑‍🤝‍🧑</span> Patients</a>
  <a href="ajouter_patient.php" class="nav-item <?= $current=='ajouter_patient.php'?'active':'' ?>"><span class="icon">➕</span> Nouveau Patient</a>
  <a href="consultations.php" class="nav-item <?= $current=='consultations.php'?'active':'' ?>"><span class="icon">🩺</span> Consultations</a>
  <div class="nav-label">Gestion</div>
  <a href="medecins.php" class="nav-item <?= $current=='medecins.php'?'active':'' ?>"><span class="icon">👨‍⚕️</span> Médecins</a>
  <a href="services.php" class="nav-item <?= $current=='services.php'?'active':'' ?>"><span class="icon">🏨</span> Services</a>
  <a href="maladies.php" class="nav-item <?= $current=='maladies.php'?'active':'' ?>"><span class="icon">🦠</span> Maladies</a>
</div>
