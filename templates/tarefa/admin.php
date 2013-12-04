<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pages = (empty($_GET['page'])) ? 0 : ($_GET['page'] - 1);
$per_page = 10;
$r = !empty($_GET['r']) ? $_GET['r'] : 'tarefa/admin'; //se for incluido(include) na index mesmo sem parametro será capaz de entender as chamadas pela url
$dt_i = $_GET['dt_i'];
$dt_f = $_GET['dt_f'];
$adv = $_GET['adv'];

if (!empty($dt_i) && !empty($dt_f)){
    $dt_i = explode(' ', $dt_i);
    $dt_i = explode('/', $dt_i[0]);
    $dt_i = array_reverse($dt_i);
    $dt_i = implode('-', $dt_i);
    $dt_f = explode(' ', $dt_f);
    $dt_f = explode('/', $dt_f[0]);
    $dt_f = array_reverse($dt_f);
    $dt_f = implode('-', $dt_f);
}

$campo_procurado = trim(strip_tags($_GET['c']));
$termo_de_busca = trim(strip_tags($_GET['t']));

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID' => 'id_tarefa',
    'Data e Hora' => 'data_e_hora',
    'Tarefa' => 'tarefa',
    'Nome' => 'nome'
);
$campos_nomes = array(
    'id',
    'nome'
);
$is_data = array('data_e_hora');
$tabela = array(TBL_TAREFAS, TBL_USUARIO);
$condition = " advogado_id_advogado = id";
if ($adv !=0){
    $condition .= "  and id=" . $adv;
}
$condition .= " and data_e_hora between '". $dt_i ."' and '". $dt_f ."'";

$condition_nome = " advogado_id_advogado = id order by nome";

$nomes = $pdo->getArrayData($campos_nomes, $tabela, $condition_nome);
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<table class="table">
    <form id='form-search' method='GET' action='<?php echo $_SERVER["REQUEST_URI"] ?>'>
    <tr>
        <input type='hidden' name='r' value='<?php echo $r ?>'>
        <input type='text' placeholder='Data Inicial' name='dt_i' value='' id='data_inicio'/>
        &nbsp&nbsp&nbsp
        <input type='text' placeholder='Data Final' name='dt_f' value='' id='data_fim'/>
        &nbsp&nbsp&nbsp
        <select name='adv'>";
            <option value='0'>--Todos--</option>\n
        <?php    foreach ($nomes as $key => $nome) {
        print "<option value='" . $nome['id'] . "'>" . $nome['nome'] . "</option>\n";
        }?>
        </select>

        <button type='submit' style='margin-bottom:10px' class='btn btn-default'>Consultar</button>
        <div class="center right ">
            <h4><?php print EXPORT_DATA; ?><br /></h4>
            <a href=export.php?r=relatorios/export/tarefa-pdf&dt_i=<?php echo $dt_i ?>&dt_f=<?php echo $dt_f ?>&adv=<?php echo $adv ?>><img src=images/pdf.png /></a>
            <a href=export.php?r=relatorios/export/tarefa-xls&dt_i=<?php echo $dt_i ?>&dt_f=<?php echo $dt_f ?>&adv=<?php echo $adv ?>><img src=images/xls.png /></a>

        </div>
    </form>
        <?php //print Main::getForm($_SERVER["REQUEST_URI"], $r, $campos_da_tabela, array('c' => $campo_procurado, 't' => $termo_de_busca)); ?>
        <?php //print TEXT_DEFAULT_FILTER . "<a class='btn current right' href='index.php?r=tarefa/admin" . (!empty($campo_procurado) ? "&c=$campo_procurado" : "") . (!empty($termo_de_busca) ? "&t=$termo_de_busca" : "") . ($_GET['date'] != "all" ? "&date=all" : "") . "'>" . (($_GET['date'] == "all") ? TEXT_DISPLAY_FILTER : TEXT_DISPLAY_ALL) . "</a>"; ?>
    </tr>
    <br>
    <br>
    <br>
    <br>
    <br>

    <tr>
        <?php
        foreach (array_keys($campos_da_tabela) as $campos) {
            print "<th>$campos</th>";
        }
        ?>
        <th>Acao</th>
    </tr>
    <?php
    $total = count($dados);
    $dados = array_slice($dados, $pages * $per_page, $per_page, true);
    foreach ($dados as $key => $dado) {
        $id_tb = $dado['id_tarefa'];
        print "<tr>";
        foreach ($campos_da_tabela as $campos) {
            $value = in_array($campos, $is_data) ? date_format(date_create($dado[$campos]), "d/m/Y H:i:s") : $dado[$campos];
            print "<td>" . str_replace("_", " ", $value) . "</td>";
        }
        print "<td>";
        print Main::getAdminLinks($r, $id_tb);
        print "</td>\n";
        print "</tr>\n";
    }
    if (empty($dados)) {
        print "<td style='text-align:center' colspan=" . (count($campos_da_tabela) + 1) . ">" . TABLE_EMPTY . "</td>";
    }
    ?>
</table>
<?php
if (!empty($campo_procurado) && !empty($termo_de_busca)) {
    $link_adicional = "&c=$campo_procurado&t=$termo_de_busca";
}
$link_adicional .= ($_GET['date'] == "all" ? "&date=all" : "");
if ($total > $per_page)
    $links = Main::getPagination($total, $per_page, $link_adicional, $r);
?>
<div class="pagination">
    <ul>
        <?php print $links; ?>
    </ul>
</div>

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