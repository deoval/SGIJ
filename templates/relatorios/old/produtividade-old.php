<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
/* select sum(valor), usuario.login from pagamentos, processos, usuario where pagamentos.processos_id_processo = processos.id_processo and processos.advogado_alocado = usuario.id
  group by usuario.id */
$campos_da_tabela = array('id', 'sum(valor)', 'login', 'nome');
$tabela = array(TBL_USUARIO, TBL_PAGAMENTOS, TBL_PROCESSOS);

$condition = " 1 ";

if (!empty($_GET['m']) || !empty($_GET['y'])) {
$m = $_GET['m'];
$y = (empty($_GET['y']))?date("Y"):$_GET['y'];
$b = $d%4;

if(in_array($m,array( 1,3,5,7,8,10,12))){
$d=31;

}else if($m == 2){
  if($b == 0){$d = 28;}else{$d=29;}
}else{
$d = 30;
}
if(!empty($m)){

$m1 = $m;
$m2 = $m;

}else{
$m1=1;
$m2 = '12';
}
$condition .= " and ( vencimento BETWEEN '$y-$m1-01 00:00:00' AND  '$y-$m2-$d 23:00:00') ";
}

$condition .= " and advogado_alocado = id and processos_id_processo = id_processo ";
$condition .= " group by id ";

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
foreach ($dados as $dado) {
    $datas[$dado['nome']] = array('x_bar_value' => $dado['sum(valor)']);
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
$mes = array('Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

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
                                  //  chartContext: "label",
                                    chartBarClass :"barra-bar-chart",
                                    animationDelay: 2000,
                                    margin:2,
                                    chartWrapperClass:'barra-bar-wrapper',
                                });           
                            });
</script>
<h2>Relatório de Produtividade(<?php print (empty($_GET['m'])?'todos os pagamentos' .(!empty($y)?"/" . $y:""):$mes[$m-1] . "/" . $y); ?>)</h2>
<div style="border:1px solid #333;width:270px;float:right;padding:5px;text-align:center">
    Escolha um ano<br />
    <a class="btn" href=index.php?r=relatorios/rentabilidade&y=<?php print (date('Y') - 1); ?>>Ano <?php print (date('Y') - 1); ?></a>
    <a class="btn" href=index.php?r=relatorios/rentabilidade&y=<?php print (date('Y')); ?>>Ano <?php print (date('Y')); ?></a>
    <a class="btn" href=index.php?r=relatorios/rentabilidade&y=<?php print (date('Y') + 1); ?>>Ano <?php print (date('Y') + 1); ?></a>
    Escolha um mês<br />
<?php 

foreach($mes as $key=>$m){ ?>
    <a class="btn" style="width:200px" href=index.php?r=relatorios/rentabilidade&m=<?php print ($key+1); ?>&y=<?php print $_GET['y']; ?>><?php print $m ; ?></a>
<?php

 } ?> 
</div>
<div id="chart_simple_div" style="position:relative; width:700px; height:400px; margin-bottom:20px"></div>
<script>
jQuery(document).ready(function() {
jQuery('div.ddchart-y-axis div').prepend('R$ ');
});

</script>

