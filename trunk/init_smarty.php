<?php
///////////////////////////////////////////////////////////////////////////////////////////
// Smarty Path must contain the path name to find Smarty.class.php. By default this is
// "PET_Smarty/Smarty.class.php", however, Smarty can be installed anywhere - or even
// shared between applications, so modify this line as required
//
// Note that in addition to the templates directory smarty needs three directories
// each of which needs to be writable by the web-server. The default locations for these
// are: 
// - templates/templates_c
// - templates/cache
// - templates/configs
///////////////////////////////////////////////////////////////////////////////////////////
$smartyPath = "PET_Smarty/Smarty.class.php";
$templatesOK = false;
if (file_exists($smartyPath))
  {
  @require($smartyPath);
  $templatesOK = true;

  $tmpl = new Smarty();

  $tmpl->template_dir = 'templates';
  $tmpl->compile_dir = 'templates/templates_c';
  $tmpl->cache_dir = 'templates/cache';
  $tmpl->config_dir = 'templates/configs';

  // Session specifics
  session_start();

  if (isset($_SESSION['devID'])) { $devID = $_SESSION['devID']; }
  else $devID = "";
  if (isset($_SESSION['projID'])) { $projID = $_SESSION['projID']; }
  else $projID = "";
  }
else echo "<h1>PET Templates not found</h1><p>Could not find Smarty Template Classes. Please check smarty Path in init_smarty.php</p>"
?>