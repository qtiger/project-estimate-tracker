<?php
// put full path to Smarty.class.php
require('/Smarty/Smarty.class.php');
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

?>