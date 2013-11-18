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
    'ID do Prazo' => id_prazo,
    'Processo' => id_num_processo,
    'Tipo de Prazo' => tipo_de_prazo,
    'Data de Inicio' => data_inicio,
    'Data Limite' => data_limite,
);
$tabela = array(TBL_PRAZOS);
$condition = "id_prazo = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$is_data = array('data_limite', 'data_inicio');

if ($_POST['deletar'] == 'ok') {
    $tabela = TBL_PRAZOS;
    $pdo->deleteData($tabela, $condition);
    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . DELETE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=prazo/admin',1);
}
?>
<h2><?php print CONFIRM_EXCLUDE; ?></h2>
<?php
foreach ($campos_da_tabela as $key => $campos) {
    $value = in_array($campos, $is_data) ? date_format(date_create($dados[0][$campos]), "d/m/Y H:i:s") : $dados[0][$campos];
    $value = ($campos == 'id_num_processo' ? "<a href='index.php?r=processo/view&id=" . $value . "'>" . $value . "</a>" : $value);
    print "$key : " . str_replace('_', ' ', $value) . "<br />\n";
}
?>
<form method=POST action="<?php print $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="deletar" value="ok" />
    <?php $l = explode("/", $_GET['r']); ?>
    <a class="btn btn-large" href="index.php?r=<?php print $l[0]; ?>/admin"><?php print BACK; ?></a>
    <input type="submit" class="btn btn-large" value="Confirmar Exclusão">
</form>
