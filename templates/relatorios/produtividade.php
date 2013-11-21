<?php
    $mes = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
?>
<h2>Relatório de Produtividade</h2>
<div style="border:1px solid #333;width:270px;float:right;padding:5px;text-align:center">
<!--    Escolha um ano<br />
    <a class="btn" href=index.php?r=relatorios/rentabilidade&y=<?php print (date('Y') - 1); ?>>Ano <?php print (date('Y') - 1); ?></a>
    <a class="btn" href=index.php?r=relatorios/rentabilidade&y=<?php print (date('Y')); ?>>Ano <?php print (date('Y')); ?></a>
    <a class="btn" href=index.php?r=relatorios/rentabilidade&y=<?php print (date('Y') + 1); ?>>Ano <?php print (date('Y') + 1); ?></a>
    Escolha um mês<br />!-->
    Escolha um mês<br />
<?php 

foreach($mes as $key=>$m){ ?>
    <a class="btn" style="width:200px" href=index.php?r=relatorios/produtividade&m=<?php print ($key+1); ?>><?php print $m ; ?></a>
<?php

 } ?> 
</div>
<div id="chart_simple_div" style="z-index:-1; position:relative; width:700px; height:400px; margin-bottom:20px">
    <?php
    $caminho = "templates/relatorios/geraRelatorio.php?r=relatorios/produtividade";
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
