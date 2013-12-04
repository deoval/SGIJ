<?php
 $mestab = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');

if (file_exists('config.php')) {
    require_once( 'config.php' );
}
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
ob_start();
//print_r($_SESSION);
//if($_SESSION['user']['cargo'] != 'advogado_socio')die('Sem permissoes suficientes');
//die();
if (!empty($_GET['m'])) {
    $mes = $_GET['m'];
}
else{
    $mes = date("m");
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO

$campos_da_tabela = array('Advogado Alocado' => 'nome', 'Total processo' => 'count(advogado_alocado)', 'Mês' => $mes );

$tabela = array(TBL_USUARIO, TBL_PROCESSOS);

$condition = " advogado_alocado = id ";
if (!empty($mes)) {
    $m = $mes;
    $y = date("Y");
    $b = $y%4;

    if(in_array($m,array( 1,3,5,7,8,10,12))){
        $d=31;

    }else if($m == 2){
        if($b == 0){$d = 28;}else{$d=29;}
    }else{
        $d = 30;
    }
    $condition .= " and ( data_abertura BETWEEN '$y-$m-01 00:00:00' AND  '$y-$m-$d 23:00:00') ";
}
$condition .= " group by advogado_alocado ";

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


<h2  class="left" style="width: 75%;" >Relatório de Alocação de Advogados<?php //print "(" . (empty($_GET['m'])?'todos os processos':$mes[$m-1] . "/" . $y).")"; ?></h2>
<div class="center">
    <h4><?php print EXPORT_DATA; ?><br /></h4>
    <a href=export.php?r=relatorios/export/alocacao_de_advogados-pdf&m=<?php echo $_GET['m'] ?> ><img src=images/pdf.png /></a>
    <a href=export.php?r=relatorios/export/alocacao_de_advogados-xls&m=<?php echo $_GET['m'] ?>><img src=images/xls.png /></a>

</div>
<br>
<br>
<br>
<input type="button" id="botaoalt" class="btn" value="Gerar Gráfico" onclick="alteraDiv();">
<br>
<br>
<div id="tabela">

    <?php print $html; ?>

</div>
<!--a class='btn right piecorrect' href='index.php?r=relatorios/alocacao_de_advogado<?php print ($_GET['dt'] != "atual" ? "&dt=atual" : ""); ?>'><?php print $str; ?></a-->
<div style="border:1px solid #333;width:270px;float:right;padding:5px;text-align:center">
    Escolha um mês<br />
    <?php

    foreach($mestab as $key=>$m){ ?>
        <a class="btn" style="width:200px" href=index.php?r=relatorios/alocacao_de_advogado&m=<?php print ($key+1); ?>><?php print $m ; ?></a>
    <?php

    } ?>
</div>

<div id="chart_simple_div" style="display:none; z-index:-1;position:relative; width:700px; height:400px; margin-bottom:20px">
    <?php
        $caminho = "templates/relatorios/geraRelatorio.php?r=relatorios/alocacao_de_advogado";
        if ($_GET['m']){
           $caminho .= "&m=".$_GET['m'];
        }
        else {
            $mesatual = date("m");
            $caminho .= "&m=".$mesatual;
        }

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
