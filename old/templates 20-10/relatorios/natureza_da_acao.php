<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array('sum(valor)', 'natureza_da_acao');
$tabela = array(TBL_PAGAMENTOS, TBL_PROCESSOS);
$condition = " processos_id_processo=id_processo ";
$condition .= " group by natureza_da_acao ";
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
foreach ($dados as $dado) {
    $datas[$dado['natureza_da_acao']] = array('x_bar_value' => $dado['sum(valor)']);
}
?>        
<link type="text/css" href="js/barra/ddchart.css" rel="stylesheet" />
<style>.barra-bar-chart{border:2px solid rgba(151,187,205,1); }.x-axis-text{font-size:14px}.ddchart-x-axis {line-height:normal;overflow:visible;}</style>
<script language="javascript" src="js/jquery.js" ></script>
<script language="javascript" src="js/barra/jquery.ddchart.js"></script>         
<script language="javascript">
    $(function() {
        $("#chart_simple_div").ddBarChart({
            chartData: {
                "COLUMNS":["X_BAR_LABEL","X_BAR_VALUE","X_BAR_COLOR", "TOOL_TIP_TITLE"],
                "DATA":[
<?php
$ultimo_elemento = array_reverse(array_keys($datas));
$ultimo_elemento = $ultimo_elemento[0]; //retorna ultimo elemento do array
foreach ($datas as $key => $data) {
    $spc = ",";
    if ($key == $ultimo_elemento) {
        $spc = ""; //no ie virgula no fim do array quebra js
    }
    print "['" . $key . "'," . $data['x_bar_value'] . ",'rgba(151,187,205,0.5)','']{$spc}\n";
}
?>
                                        ]
                                    }, 
                                    chartContext: "",
                                    chartBarClass :"barra-bar-chart",
                                    animationDelay: 2000,
                                    margin:2,
                                    chartWrapperClass:'barra-bar-wrapper'
                                });           
                            });
</script>
<h2>Relatório de Natureza da Ação(montante financeiro x natureza da ação)</h2>
<div id="chart_simple_div" style="position:relative; width:700px; height:400px; margin-bottom:20px"></div>

