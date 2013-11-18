<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
if (is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    exit();
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID' => 'id_tarefa',
    'Login' => 'login',
    'Data' => 'data_e_hora',
    'Tarefa' => 'tarefa'
);
$campos_excluidos_form = array('id_tarefa');
$tabela = array(TBL_TAREFAS, TBL_USUARIO);
$condition = " advogado_id_advogado = id ";
$condition .= " and id_tarefa = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
$is_data = array('data_e_hora');

if ($_POST['deletar'] == 'ok') {
    $pdo = new conectaPDO();
    $tabela = TBL_TAREFAS;
    $condition = " id_tarefa = " . $id;
    $pdo->deleteData($tabela, $condition);
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . DELETE_SUCCESS . ' </strong></div>';
    $pdo->endConnection(); //FIM DA CONEXÃO
    Main::redirect('index.php?r=tarefa/admin',1);
}
?>
<h2><?php print CONFIRM_EXCLUDE; ?></h2>
<?php
foreach ($campos_da_tabela as $key => $campos) {
    $value = in_array($campos, $is_data) ? date_format(date_create($dados[0][$campos]), "d/m/Y H:i:s") : $dados[0][$campos];
    print "$key : " . $value . "<br />\n";
}
?>
<form method=POST action="<?php print $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="deletar" value="ok" />
    <?php $l = explode("/", $_GET['r']); ?>
    <a class="btn btn-large" href="index.php?r=<?php print $l[0]; ?>/admin"><?php print BACK; ?></a>
    <input type="submit" class="btn btn-large" value="Confirmar Exclusão">
</form>
