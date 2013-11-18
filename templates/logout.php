<h1>Voce fez logout com sucesso do sistema</h1>
<?php
session_unset();
session_destroy();
session_write_close();
?>
