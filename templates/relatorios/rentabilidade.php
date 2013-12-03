<?php
/*if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}

$criteria = $_GET['criteria'];
if (!empty($criteria)) {
    $fieldcriteria = 'sum(valor)';
    $tabela = array(TBL_CLIENTE, TBL_PROCESSOS, TBL_PAGAMENTOS);
    $condition = " id_cliente = cliente and id_processo =  processos_id_processo ";
    $str = TEXT_TIPO_CLIENTECOUNT;
    $str_title = "Relatório de Rentabilidade";//TEXT_TIPO_CLIENTEXVALOR;
} else {
    $fieldcriteria = 'count(id_cliente)';
    $tabela = array(TBL_CLIENTE);
    $condition = "1";
    $str = TEXT_TIPO_CLIENTEXVALOR;
    $str_title = "Relatório de Rentabilidade";//TEXT_TIPO_CLIENTECOUNT;
}

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array($fieldcriteria, 'tipo_cliente');
$condition .= " group by tipo_cliente ";
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO */
?>
<h2 class="left" style="width: 75%;">Relatório de Rentabilidade<?php //print $str_title; ?></h2>
<div class="center" >
    <h4><?php print EXPORT_DATA; ?></h4>
    <a href='export.php?r=relatorios/export/rentabilidade-pdf'><img src=images/pdf.png /></a>
    <a href='export.php?r=relatorios/export/rentabilidade-xls'><img src=images/xls.png /></a>
</div>
<br>
<br>

<div id="holder">
    <?php  $caminho = "templates/relatorios/geraRelatorio.php?r=relatorios/rentabilidade";?><img src="<?php echo $caminho ?>">
    <?php  $caminho = "templates/relatorios/geraRelatorio.php?r=relatorios/rentabilidade2";?><img src="<?php echo $caminho ?>">

</div>

