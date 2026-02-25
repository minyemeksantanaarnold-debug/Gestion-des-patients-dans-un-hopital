<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'DM Sans',sans-serif; background:#0f1923; color:#c8d4e0; display:flex; min-height:100vh; }

/* SIDEBAR */
.sidebar {
  width: 240px; min-height:100vh; background:#1a2535;
  border-right:1px solid #2a3545; padding:24px 0; flex-shrink:0;
  position:fixed; left:0; top:0; bottom:0; overflow-y:auto; z-index:10;
}
.sidebar-logo {
  display:flex; align-items:center; gap:10px;
  padding:0 20px 24px; border-bottom:1px solid #2a3545; margin-bottom:16px;
}
.sidebar-logo .icon { font-size:24px; }
.sidebar-logo .name { font-family:'Syne',sans-serif; font-weight:800; font-size:16px; color:white; }
.sidebar-logo .name span { color:#e74c3c; }
.nav-label { font-size:10px; letter-spacing:0.15em; text-transform:uppercase; color:#3a4a5a; padding:8px 20px 4px; }
.nav-item {
  display:flex; align-items:center; gap:10px;
  padding:10px 20px; color:#5a6a80; text-decoration:none;
  font-size:14px; font-weight:500; transition:all 0.15s;
  border-left:3px solid transparent;
}
.nav-item:hover { color:white; background:rgba(255,255,255,0.03); }
.nav-item.active { color:white; border-left-color:#e74c3c; background:rgba(192,57,43,0.08); }
.nav-item .icon { font-size:16px; }

/* MAIN */
.main { margin-left:240px; flex:1; display:flex; flex-direction:column; }
.topbar {
  background:#1a2535; border-bottom:1px solid #2a3545;
  padding:16px 32px; display:flex; align-items:center; justify-content:space-between;
  position:sticky; top:0; z-index:5;
}
.topbar-user { display:flex; align-items:center; gap:10px; font-size:14px; color:#8a9ab0; }
.topbar-avatar {
  width:32px; height:32px; background:#c0392b; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  font-size:13px; font-weight:700; color:white;
}
.logout { color:#5a6a80; font-size:12px; text-decoration:none; margin-left:8px; }
.logout:hover { color:#e74c3c; }

/* CONTENT */
.content { padding:32px; }
.page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:32px; }
.page-tag { font-size:10px; letter-spacing:0.2em; text-transform:uppercase; color:#e74c3c; margin-bottom:6px; }
.page-header h1 { font-family:'Syne',sans-serif; font-size:28px; font-weight:800; color:white; }

/* STATS */
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.stat-card { background:#1a2535; border:1px solid #2a3545; border-radius:2px; padding:20px; display:flex; align-items:center; gap:16px; }
.stat-icon { width:48px; height:48px; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:22px; }
.stat-num { font-family:'Syne',sans-serif; font-size:28px; font-weight:800; color:white; }
.stat-label { font-size:12px; color:#5a6a80; }

/* CARD */
.card { background:#1a2535; border:1px solid #2a3545; border-radius:2px; margin-bottom:24px; overflow:hidden; }
.card-head { padding:16px 24px; border-bottom:1px solid #2a3545; display:flex; align-items:center; justify-content:space-between; }
.card-head h3 { font-family:'Syne',sans-serif; font-size:15px; font-weight:700; color:white; }
.link-more { color:#e74c3c; font-size:13px; text-decoration:none; }

/* TABLE */
.data-table { width:100%; border-collapse:collapse; font-size:13px; }
.data-table th { text-align:left; padding:12px 20px; color:#5a6a80; font-size:10px; text-transform:uppercase; letter-spacing:0.08em; border-bottom:1px solid #2a3545; font-weight:500; }
.data-table td { padding:14px 20px; border-bottom:1px solid rgba(255,255,255,0.03); }
.data-table tr:last-child td { border-bottom:none; }
.data-table tr:hover td { background:rgba(255,255,255,0.02); }

/* BADGES & STATUS */
.badge { background:#2a3545; color:#c8d4e0; padding:3px 10px; border-radius:2px; font-size:12px; font-weight:600; }
.status { padding:3px 10px; border-radius:2px; font-size:11px; font-weight:600; text-transform:uppercase; }
.status.ouvert { background:rgba(39,174,96,0.15); color:#27ae60; }
.status.en_cours { background:rgba(52,152,219,0.15); color:#3498db; }
.status.clôturé, .status.cloture { background:rgba(127,140,141,0.15); color:#7f8c8d; }

/* BUTTONS */
.btn-primary { background:#c0392b; color:white; padding:10px 20px; border-radius:2px; text-decoration:none; font-family:'Syne',sans-serif; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; border:none; cursor:pointer; display:inline-block; transition:background 0.2s; }
.btn-primary:hover { background:#a93226; }
.btn-sm { background:#2a3545; color:#c8d4e0; padding:6px 14px; border-radius:2px; text-decoration:none; font-size:12px; font-weight:500; border:none; cursor:pointer; transition:background 0.2s; display:inline-block; }
.btn-sm:hover { background:#3a4a5a; color:white; }
.btn-sm.red { background:rgba(192,57,43,0.2); color:#e74c3c; }
.btn-sm.red:hover { background:rgba(192,57,43,0.35); }

/* FORM */
.form-card { background:#1a2535; border:1px solid #2a3545; border-radius:2px; padding:32px; max-width:760px; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.form-group { margin-bottom:20px; }
.form-group.full { grid-column:1/-1; }
.form-group label { display:block; font-size:11px; font-weight:500; color:#5a6a80; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px; }
.form-group input, .form-group select, .form-group textarea {
  width:100%; background:#0f1923; border:1px solid #2a3545;
  border-radius:2px; padding:12px 14px; color:white;
  font-family:'DM Sans',sans-serif; font-size:14px; transition:border-color 0.2s;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
  outline:none; border-color:#c0392b;
}
.form-group textarea { resize:vertical; min-height:100px; }
.form-group select option { background:#1a2535; }
.form-group input::placeholder, .form-group textarea::placeholder { color:#3a4a5a; }

/* ALERTS */
.alert { padding:12px 16px; border-radius:2px; font-size:13px; margin-bottom:20px; }
.alert.success { background:rgba(39,174,96,0.15); border:1px solid rgba(39,174,96,0.3); color:#27ae60; }
.alert.error { background:rgba(192,57,43,0.15); border:1px solid rgba(192,57,43,0.3); color:#e74c3c; }

/* SEARCH */
.search-bar { display:flex; gap:12px; margin-bottom:24px; }
.search-bar input { flex:1; background:#1a2535; border:1px solid #2a3545; border-radius:2px; padding:12px 16px; color:white; font-family:'DM Sans',sans-serif; font-size:14px; }
.search-bar input:focus { outline:none; border-color:#c0392b; }

/* DETAIL SECTIONS */
.detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px; }
.detail-item label { font-size:10px; text-transform:uppercase; letter-spacing:0.1em; color:#5a6a80; display:block; margin-bottom:4px; }
.detail-item span { color:white; font-size:14px; }

@media(max-width:900px){
  .stats-grid { grid-template-columns:1fr 1fr; }
  .form-grid { grid-template-columns:1fr; }
  .detail-grid { grid-template-columns:1fr; }
}
</style>
