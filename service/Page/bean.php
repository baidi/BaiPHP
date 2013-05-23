<pre>
<?php
$data = Data::access();
$sql = 'show tables from baiphp';
$tables = $data->entrust($sql);
var_export($tables);
$sql = 'show full columns from user';
$user = $data->entrust($sql);
var_export($user);
?>
</pre>