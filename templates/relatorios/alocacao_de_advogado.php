<?php
 $mes = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');

?>


<h2>Relatório de Alocação de Advogados<?php //print "(" . (empty($_GET['m'])?'todos os processos':$mes[$m-1] . "/" . $y).")"; ?></h2>
<!--a class='btn right piecorrect' href='index.php?r=relatorios/alocacao_de_advogado<?php print ($_GET['dt'] != "atual" ? "&dt=atual" : ""); ?>'><?php print $str; ?></a-->
<div style="border:1px solid #333;width:270px;float:right;padding:5px;text-align:center">
    Escolha um mês<br />
    <?php

    foreach($mes as $key=>$m){ ?>
        <a class="btn" style="width:200px" href=index.php?r=relatorios/alocacao_de_advogado&m=<?php print ($key+1); ?>><?php print $m ; ?></a>
    <?php

    } ?>
</div>
<div id="chart_simple_div" style="z-index:-1;position:relative; width:700px; height:400px; margin-bottom:20px">
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
