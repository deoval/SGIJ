<?php
if (file_exists('config.php')) {
    require_once( 'config.php' );
}
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
ob_start();
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO

$campos_da_tabela = array('Natureza da Ação' => 'natureza_da_acao', 'Valor Total' => 'sum(valor)', 'Quantidade de pagamentos por natureza da ação' => 'count(natureza_da_acao)' );

$tabela = array(TBL_PAGAMENTOS, TBL_PROCESSOS);

$condition = " processos_id_processo=id_processo ";
$condition .= "and status_pagamento='quitado'";
$condition .= " group by natureza_da_acao ";

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO

$html = '';
$html .= '<table border="1" width="800px">';
$html .= '<tr>';
foreach (array_keys($campos_da_tabela) as $ct) {
    $html .= '<td class="titulo"><b>' . $ct . '</b></td>';
}
$html .= '</tr>';
foreach ($dados as $dado) {
    $html .= '<tr>';
    foreach ($campos_da_tabela as $ct) {
        $html .= '<td><b>' . $dado[$ct] . '</b></td>';
    }
    $html .= '</tr>';
}
$html .= '</table>';

?>        

<h2  class="left" style="width: 75%;" >Relatório de Natureza da Ação</h2>
<div class="center ">
    <h4><?php print EXPORT_DATA; ?><br /></h4>
    <a href='export.php?r=relatorios/export/natureza_da_acao-pdf'><img src=images/pdf.png /></a>
    <a href='export.php?r=relatorios/export/natureza_da_acao-xls'><img src=images/xls.png /></a>

</div>
<input type="button" id="botaoalt" class="btn" value="Gerar Gráfico" onclick="alteraDiv();">
<br>
<br>
<div id="tabela">

    <?php print $html; ?>

</div>
<div id="chart_simple_div" style="display:none; z-index:-1; position:relative; width:800px; margin-bottom:20px">
    <?php
        $caminho = "templates/relatorios/geraRelatorio.php?r=relatorios/natureza_da_acao";
    ?>
    <img src="<?php echo $caminho ?>">
</div>
<script>
    function alteraDiv(){

        var divRel = document.getElementById('tabela');
        var btn = document.getElementById('botaoalt');
        var divGra = document.getElementById('chart_simple_div');
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

