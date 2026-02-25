<div class="topbar">
  <div style="font-size:13px;color:#5a6a80;">
    <?php echo date('l d F Y'); ?>
  </div>
  <div class="topbar-user">
    <div class="topbar-avatar"><?= strtoupper(substr($_SESSION['user_nom'],0,1)) ?></div>
    <span><?= sanitize($_SESSION['user_nom']) ?></span>
    <a href="../logout.php" class="logout">Déconnexion</a>
  </div>
</div>
