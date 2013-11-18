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
    'ID do Processo' => 'id_processo',
    'Cliente' => 'cliente',
    'Advogado Alocado' => 'advogado_alocado',
    'Natureza da Acao' => 'natureza_da_acao',
    'Tipo de Ação' => 'tipo_acao',
    'Data de Abertura' => 'data_abertura',
    'Posição do Cliente' => 'posicao_cliente',
    'Status do Processo' => 'status_processo',
    'Localização dos Documento' => 'localizacao_documentos',
    'Numero Processo TJ' => 'numero_processo_tj',
);
$is_data = array('data_abertura');
$tabela = array(TBL_PROCESSOS);
$condition = "id_processo = $id";
$processos = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
if ($_POST['deletar'] == 'ok') {
    $pdo = new conectaPDO();
    $tabela = TBL_PROCESSOS;
    $pdo->deleteData($tabela, $condition);
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . DELETE_SUCCESS . ' </strong></div>';
    $pdo->endConnection(); //FIM DA CONEXÃO
    Main::redirect('index.php?r=processo/admin',1);
}
?>
<h2><?php print CONFIRM_EXCLUDE; ?></h2>
<span class="btn-danger" style="padding:2px">* Ao deletar um processo todos os prazos alocados a esse processo sumirão</span><br />
<?php
foreach ($campos_da_tabela as $key => $campos) {
    $value = in_array($campos, $is_data) ? date_format(date_create($processos[0][$campos]), "d/m/Y") : $processos[0][$campos];
    print "$key : " . $value . "<br />\n";
}
?>
<form method=POST action="<?php print $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="deletar" value="ok" />
    <?php $l = explode("/", $_GET['r']); ?>
    <a class="btn btn-large" href="index.php?r=<?php print $l[0]; ?>/admin"><?php print BACK; ?></a>
    <input type="submit" class="btn btn-large" value="Confirmar Exclusão">
</form>
