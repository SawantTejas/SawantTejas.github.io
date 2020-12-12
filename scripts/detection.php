<?php
$id = isset($_GET['id'])?$_GET['id'] : "";

echo shell_exec("py C:\\xampp\\htdocs\\SmartAssign\\scripts\\db.py $id 2>&1");


?>
