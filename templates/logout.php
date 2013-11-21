<h1>Voce fez logout com sucesso do sistema</h1>
<?php
session_unset();
session_destroy();
session_write_close();
//header("Location: http://www.google.com");
echo "<meta HTTP-EQUIV='refresh' CONTENT='0.5' URL='./index.php'>";
?>
