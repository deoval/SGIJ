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
<h2>Rentabilidade<?php //print $str_title; ?></h2><br />
<div class="right center piecorrect">
    <a class='btn right action-links' href='index.php?r=relatorios/rentabilidade<?php print (empty($_GET['criteria']) ? "&criteria=1" : ""); ?>'><?php print $str; ?></a>
    <br /> 
    <h4><?php print EXPORT_DATA; ?><br /></h4>
    <a href='export.php?r=relatorios/export/tipo_de_cliente-pdf'><img src=images/pdf.png /></a>
    <a href='export.php?r=relatorios/export/tipo_de_cliente-xls'><img src=images/xls.png /></a>

</div>
<table class="tabela">
    <tbody>
        <?php 
$coundnum = 0;
if(empty($criteria)){
foreach ($dados as $dado) { 
$countnum += $dado[$fieldcriteria];
}
}?>
        <?php foreach ($dados as $dado) { ?>
            <tr>
                <th scope="row"> <?php print $dado['tipo_cliente']; ?> </th>
                <td> <?php print empty($criteria)?(round($dado[$fieldcriteria]/$countnum*100,2)) . "% (" . $dado[$fieldcriteria] . ")":number_format($dado[$fieldcriteria], 2, '.', ''); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div id="holder">
    <?php  $caminho = "templates/relatorios/geraRelatorio.php?r=relatorios/rentabilidade";?><img src="<?php echo $caminho ?>"></div>

