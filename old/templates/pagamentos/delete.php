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
    'ID (Pgto)' => id_pagamento,
    'Tempo de Atraso' => tempo_atraso,
    'Plano de pagamento' => plano_pagamento,
    'Valor' => valor,
    'Parcelas pagas' => parcelas_pagas,
    'Forma de pagamento' => forma_pagamento,
    'Status' => status_pagamento,
    'Vencimento' => vencimento,
 //   'Processo' => processos_id_processo
);
$is_data = array('vencimento');
$tabela = array(TBL_PAGAMENTOS);
$condition = "id_pagamento = $id";
$pagamentos = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection();
if ($_POST['deletar'] == 'ok') {
    $pdo = new conectaPDO(); //INICIA CONEXÃO PDO
    $tabela = TBL_PAGAMENTOS;
    $pdo->deleteData($tabela, $condition);
    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . DELETE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=pagamentos/admin',1);
}
?>
<h2><?php print CONFIRM_EXCLUDE; ?></h2>
<?php
foreach ($campos_da_tabela as $key => $campos) {
    $value = in_array($campos, $is_data) ? date_format(date_create($pagamentos[0][$campos]), "d/m/Y H:i:s") : $pagamentos[0][$campos];
    print "$key : " . $value . "<br />\n";
}
?>
<form method=POST action="<?php print $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="deletar" value="ok" />
    <?php $l = explode("/", $_GET['r']); ?>
    <a class="btn btn-large" href="index.php?r=<?php print $l[0]; ?>/admin"><?php print BACK; ?></a>
    <input type="submit" class="btn btn-large" value="Confirmar Exclusão">
</form>
