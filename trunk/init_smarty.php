<?php
// put full path to Smarty.class.php
$smartyPath = "../../Smarty/Smarty.class.php";
$templatesOK = false;
if (file_exists($smartyPath))
  {
  @require($smartyPath);
  $templatesOK = true;

  $tmpl = new Smarty();

  $tmpl->template_dir = 'taskmon_templates/templates';
  $tmpl->compile_dir = 'taskmon_templates/templates_c';
  $tmpl->cache_dir = 'taskmon_templates/cache';
  $tmpl->config_dir = 'taskmon_templates/configs';

  // Session specifics
  session_start();

  if (isset($_SESSION['devID'])) { $devID = $_SESSION['devID']; }
  else $devID = "";
  if (isset($_SESSION['projID'])) { $projID = $_SESSION['projID']; }
  else $projID = "";
  }
else echo "<h1>PET Templates not found</h1><p>Could not find Smarty Template Classes. Please check smarty Path in init_smarty.php</p>"
?>