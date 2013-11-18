<script src="js/jquery-ui-timepicker-addon.js"></script>
<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$dt_i = trim(strip_tags($_GET['dt_i']));
$dt_f = trim(strip_tags($_GET['dt_f']));

$pages = (empty($_GET['page'])) ? 0 : ($_GET['page'] - 1);
$per_page = 10;
$r = $_GET['r']; //se for incluido(include) na index mesmo sem parametro será capaz de entender as chamadas pela url
$campo_procurado = trim(strip_tags($_GET['c']));
$termo_de_busca = trim(strip_tags($_GET['t']));
$is_data = array('data_limite', 'data_inicio');
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID do Prazo' => id_prazo,
    'Processo' => id_num_processo,
    'Data de Inicio' => data_inicio,
    'Data Limite' => data_limite,
    'Numero Processo TJ' => 'numero_processo_tj',
    'Tipo de Prazo' => 'tipo_de_prazo',
    'Status do Processo' => 'status_processo',
    'Tipo de Ação' => 'tipo_acao',
);
$tabela = array(TBL_PRAZOS, TBL_PROCESSOS);
$condition = " id_num_processo = id_processo";
if (!empty($dt_i) && !empty($dt_f)){
    $dt_i = explode(' ', $dt_i);
    $dt_i = explode('/', $dt_i[0]);
    $dt_i = array_reverse($dt_i);
    $dt_i = implode('-', $dt_i);
    $dt_f = explode(' ', $dt_f);
    $dt_f = explode('/', $dt_f[0]);
    $dt_f = array_reverse($dt_f);
    $dt_f = implode('-', $dt_f);
    $condition .= " and data_limite between '". $dt_i ."' and '". $dt_f ."'";
}

if ($_GET['date'] != 'all') {
    $condition .= " and (data_limite >= '" . date('Y-m-d H:i:s') . "' or data_inicio >= '" . date('Y-m-d H:i:s') . "')";
}
if (!empty($campo_procurado) && !empty($termo_de_busca)) {
    if (in_array($campo_procurado, $is_data)) {
        $termo_de_busca = explode(' ', $termo_de_busca);
        $termo_de_busca = explode('/', $termo_de_busca[0]);
        $termo_de_busca = array_reverse($termo_de_busca);
        $termo_de_busca = implode('-', $termo_de_busca);
    }
    $condition .= " and $campo_procurado LIKE '%$termo_de_busca%'";
}

$condition .= " order by data_limite, data_inicio";
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<table class="table">
    <tr>
        <?php print Main::getForm($_SERVER["REQUEST_URI"], $r, $campos_da_tabela, array('c' => $campo_procurado, 't' => $termo_de_busca)); ?>
        <?php //print TEXT_DEFAULT_FILTER . "<a class='btn current right' href='index.php?r=prazo/admin" . (!empty($campo_procurado) ? "&c=$campo_procurado" : "") . (!empty($termo_de_busca) ? "&t=$termo_de_busca" : "") . ($_GET['date'] != "all" ? "&date=all" : "") . "'>" . (($_GET['date'] == "all") ? TEXT_DISPLAY_FILTER : TEXT_DISPLAY_ALL) . "</a>"; ?>
    </tr>
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
        $id_tb = $dado['id_prazo'];
        print "<tr>";
        foreach ($campos_da_tabela as $campos) {
            $value = in_array($campos, $is_data) ? date_format(date_create($dado[$campos]), "d/m/Y H:i:s") : $dado[$campos];
            $value = ($campos == 'id_num_processo' ? "<a href='index.php?r=processo/view&id=" . $value . "'>" . $value . "</a>" : $value);
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
