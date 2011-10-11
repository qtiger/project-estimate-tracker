<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"my-data.csv\"");
$data="col1, col start and end col2,col3, \n";
$data .= "seond linedata here from site to download col1";
echo $data;
?>
