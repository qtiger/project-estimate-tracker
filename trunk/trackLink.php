<?php
require ('tmUtils.php');
DBConnect();

parse_str($_SERVER['QUERY_STRING'],$query);

echo "start<br>";
if ($sqlSess)
{
echo "sess<br>";
 if (array_key_exists('id', $query))
 {
echo "id<br>";
  $res = mysql_query("select count from outboundclick where id = " . $query['id'],$sqlSess);

  if ($res)
  {
echo "res<br>";
    $row = mysql_fetch_array($res);
    $c = $row[0]+1;

    mysql_query("update outboundclick set count = " . $c . " where id = " . $query['id'],$sqlSess);
  }
 }
}
?>
