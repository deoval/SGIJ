<?php
if (file_exists('config.php')) {
    require_once( 'config.php' );
}
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
ob_start();

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO

$campos_da_tabela = array(
    'Nome' => 'nome',
    'Email' => 'email',
    'Tipo de Cliente' => 'tipo_cliente',
    'Status do pagamento'=>'status_pagamento',
    'Valor' => 'valor'
);

$tabela = array(TBL_CLIENTE, TBL_PROCESSOS, TBL_PAGAMENTOS);

$condition .= " id_cliente = cliente and id_processo = processos_id_processo";
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO


$html = '';
$html .= '<table  border="1" width="800px">';
$html .= '<tr id="topotab">';
foreach (array_keys($campos_da_tabela) as $ct) {
    $html .= '<td class="titulo"><b>' . $ct . '</b></td>';
}
$html .= '</tr>';
foreach ($dados as $dado) {
    $html .= '<tr>';
    foreach ($campos_da_tabela as $ct) {
        $html .= '<td ><b>' . $dado[$ct] . '</b></td>';
    }
    $html .= '</tr>';
}
$html .= '</table>';

?>
<h2 class="left" style="width: 75%;">Relatório de Rentabilidade<?php //print $str_title; ?></h2>
<div class="center" >
    <h4><?php print EXPORT_DATA; ?></h4>
    <a href='export.php?r=relatorios/export/rentabilidade-pdf'><img src=images/pdf.png /></a>
    <a href='export.php?r=relatorios/export/rentabilidade-xls'><img src=images/xls.png /></a>
</div>
<br>
<br>
<input type="button" id="botaoalt" class="btn" value="Gerar Gráfico" onclick="alteraDiv();">
<br>
<br>
<div id="tabela">

    <?php print $html; ?>

</div>
<div id="holder" style="display: none">
    <?php  $caminho = "templates/relatorios/geraRelatorio.php?r=relatorios/rentabilidade";?><img src="<?php echo $caminho ?>">
    <?php  $caminho = "templates/relatorios/geraRelatorio.php?r=relatorios/rentabilidade2";?><img src="<?php echo $caminho ?>">

</div>
<script>
    function alteraDiv(){

        var divRel = document.getElementById('tabela');
        var btn = document.getElementById('botaoalt');
        var divGra = document.getElementById('holder');
        if ( divRel.style.display == 'none' ) {
            divRel.style.display = '';
            divGra.style.display = 'none';
            btn.value = 'Gerar Gráfico';
        }
        else if (divGra.style.display == 'none') {
            divGra.style.display = '';
            divRel.style.display = 'none';
            btn.value = 'Voltar';

        }
        else{
            alert('erro');
        }
    };

</script>
