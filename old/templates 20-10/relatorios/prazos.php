<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}

$year = (!empty($_GET['y']) ? $_GET['y'] : date('Y'));

$pdo = new conectaPDO();
$campos_da_tabela = array('count(id_prazo)', 'month(data_inicio)', 'year(data_inicio)');
$tabela = array(TBL_PRAZOS);
$condition = " id_num_processo is not null and year(data_inicio) = '$year' ";
$condition .= " group by month(data_inicio),year(data_inicio) ";
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);

$campos_da_tabela = array('count(id_prazo)', 'month(data_limite)', 'year(data_limite)');
$condition = " id_num_processo is not null and year(data_limite) = '$year' ";
$condition .= " group by month(data_limite),year(data_limite) ";
$dados2 = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
?> 
<script src="js/Chart.js"></script>
<h1>Montante de prazos por mês em <?php print $year; ?></h1>
<canvas id="canvas" height="450" width="600"></canvas>

<?php
$mes = array('Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
foreach ($dados as $dado) {
    $array_data[$dado['month(data_inicio)']] = $dado['count(id_prazo)'];
}
foreach ($dados2 as $dado) {
    $array_data2[$dado['month(data_limite)']] = $dado['count(id_prazo)'];
}
foreach (array_keys($mes) as $m) {
    $array_dat[$m + 1] = empty($array_data[$m + 1]) ? 0 : $array_data[$m + 1];
    $array_dat2[$m + 1] = empty($array_data2[$m + 1]) ? 0 : $array_data2[$m + 1];
}
?>
<div style="border:1px solid #333;width:270px;float:right;padding:5px">
    Escolha um ano<br />
    <a class="btn" href=index.php?r=relatorios/prazos&y=<?php print (date('Y') - 1); ?>>Ano <?php print (date('Y') - 1); ?></a>
    <a class="btn" href=index.php?r=relatorios/prazos&y=<?php print (date('Y')); ?>>Ano <?php print (date('Y')); ?></a>
    <a class="btn" href=index.php?r=relatorios/prazos&y=<?php print (date('Y') + 1); ?>>Ano <?php print (date('Y') + 1); ?></a>
    Referência<br />
    <div style="clear:both;padding:2px"><div style="width:15px;height:15px;padding:5px;border:2px solid #8c8c8a;background-color:#c8c8b9;float:left"></div><div style="float:left;height:15px;padding:5px">Data de Inicio</div></div>
    <div style="clear:both;padding:2px"><div style="width:15px;height:15px;padding:5px;border:2px solid #669abb;background-color:#97bbc5;float:left"></div><div style="float:left;height:15px;padding:5px">Data Limite</div></div>
</div>
<script>
    var barChartData = {
        labels : ["<?php print implode('","', $mes); ?>"],
        datasets : [
            {
                fillColor : "#c8c8b9",
                strokeColor : "#8c8c8a",
                data : [<?php print implode(',', $array_dat); ?>]
            },
            {
                fillColor : "#97bbc5",
                strokeColor : "#669abb",
                data : [<?php print implode(',', $array_dat2); ?>]
            }
        ]
			
    }
    var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(barChartData);

</script>
