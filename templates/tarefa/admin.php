<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pages = (empty($_GET['page'])) ? 0 : ($_GET['page'] - 1);
$per_page = 10;
$r = !empty($_GET['r']) ? $_GET['r'] : 'tarefa/admin'; //se for incluido(include) na index mesmo sem parametro será capaz de entender as chamadas pela url

$campo_procurado = trim(strip_tags($_GET['c']));
$termo_de_busca = trim(strip_tags($_GET['t']));

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID' => 'id_tarefa',
    'Data e Hora' => 'data_e_hora',
    'Tarefa' => 'tarefa',
    'Login' => 'login'
);
$is_data = array('data_e_hora');
$tabela = array(TBL_TAREFAS, TBL_USUARIO);
$condition = " advogado_id_advogado = id ";
if ($_GET['date'] != 'all') {
    $condition .= " and data_e_hora >= '" . date('Y-m-d H:i:s') . "'";
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

$condition .= " order by data_e_hora";

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<table class="table">
    <tr>
        <?php print Main::getForm($_SERVER["REQUEST_URI"], $r, $campos_da_tabela, array('c' => $campo_procurado, 't' => $termo_de_busca)); ?>
        <?php print TEXT_DEFAULT_FILTER . "<a class='btn current right' href='index.php?r=tarefa/admin" . (!empty($campo_procurado) ? "&c=$campo_procurado" : "") . (!empty($termo_de_busca) ? "&t=$termo_de_busca" : "") . ($_GET['date'] != "all" ? "&date=all" : "") . "'>" . (($_GET['date'] == "all") ? TEXT_DISPLAY_FILTER : TEXT_DISPLAY_ALL) . "</a>"; ?>
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
