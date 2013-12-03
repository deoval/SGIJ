<?php

?>        

<h2  class="left" style="width: 75%;" >Relatório de Natureza da Ação</h2>
<div class="center ">
    <h4><?php print EXPORT_DATA; ?><br /></h4>
    <a href='export.php?r=relatorios/export/natureza_da_acao-pdf'><img src=images/pdf.png /></a>
    <a href='export.php?r=relatorios/export/natureza_da_acao-xls'><img src=images/xls.png /></a>

</div>
<div id="chart_simple_div" style="z-index:-1; position:relative; width:800px; margin-bottom:20px">
    <?php
        $caminho = "templates/relatorios/geraRelatorio.php?r=relatorios/natureza_da_acao";
    ?>
    <img src="<?php echo $caminho ?>">
</div>

