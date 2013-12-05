<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}

$r = $_GET['r'];
$dt_a = date('Y-m-d H:i:s');

$dt_f = $_GET['dt_f'];
$year = (!empty($_GET['y']) ? $_GET['y'] : date('Y'));
$is_data = array('data_limite');
$pdo = new conectaPDO();
$campos_da_tabela = array(
    'Numero Processo TJ' => 'numero_processo_tj',
    'Data Limite' => 'data_limite',
    'Tipo de Prazo' => 'tipo_de_prazo',
    'Tempo restante' => 'CONCAT(datediff(data_limite, now()), " dias")'
);
$tabela = array(TBL_PRAZOS, TBL_PROCESSOS);
$condition = " id_num_processo = id_processo";
if (!empty($dt_a) && !empty($dt_f)){
$dt_a = explode(' ', $dt_a);
$dt_a = explode('/', $dt_a[0]);
$dt_a = array_reverse($dt_a);
$dt_a = implode('-', $dt_a);
$dt_f = explode(' ', $dt_f);
$dt_f = explode('/', $dt_f[0]);
$dt_f = array_reverse($dt_f);
$dt_f = implode('-', $dt_f);
}
$condition .= " and data_limite between '". $dt_a ."' and '". $dt_f ."'";
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<h2 class="left" style="width: 75%;" >Relatório de prazos</h2>
<div class="center">
    <h4><?php print EXPORT_DATA; ?><br /></h4>
    <a href=export.php?r=relatorios/export/prazos-pdf&dt_a=<?php echo $dt_a ?>&dt_f=<?php echo $dt_f ?> ><img src=images/pdf.png /></a>
    <a href=export.php?r=relatorios/export/prazos-xls&dt_a=<?php echo $dt_a ?>&dt_f=<?php echo $dt_f ?> ><img src=images/xls.png /></a>

</div>

<form id='form-search' method='GET' action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
<input type='hidden' name='r' value='<?php echo $r ?>'>
<p>O intervalo é da data de hoje até a data selecionada</p>
&nbsp&nbsp&nbsp
<input type='text' placeholder='Data Limite' name='dt_f' value='' id='data_fim'/>
&nbsp&nbsp&nbsp
<button type='submit' style='margin-bottom:10px' class='btn btn-default'>Consultar</button>
</form>
    <table class="table">
        <tr id="topotab">
            <?php
            foreach (array_keys($campos_da_tabela) as $campos) {
                print "<th>$campos</th>";
            }
            ?>

        </tr>
        <?php
        $total = count($dados);
        $dados = array_slice($dados, $pages * $per_page, $per_page, true);
        foreach ($dados as $key => $dado) {
            $id_tb = $dado['id_prazo'];
            print "<tr>";
            foreach ($campos_da_tabela as $campos) {
                $value = in_array($campos, $is_data) ? date_format(date_create($dado[$campos]), "d/m/Y H:i:s") : $dado[$campos];
                $value = ($campos == 'id_num_processo' ? "<a href='index.php?r=processo/view&id=" . $value . "'>" . $value . "</a>" : $value);

                print "<td>" . str_replace("_", " ", $value) . "</td>";
            }

        }

        if (empty($dados)) {
            print "<td style='text-align:center' colspan=" . (count($campos_da_tabela) + 1) . ">" . TABLE_EMPTY . "</td>";
        }
        ?>
    </table>
<script>
$(function() {
            $( '#data_inicio' ).datepicker({
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
            dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior'
    });
    $( '#data_fim' ).datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior'
    });
});

    </script>



