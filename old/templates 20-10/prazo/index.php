<h4><?php print TEXT_LIST_TAREFAS . ucfirst($_SESSION['user']['login']); ?></h4>
<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pages = (empty($_GET['page'])) ? 0 : ($_GET['page'] - 1);
$per_page = 10;
$r = !empty($_GET['r']) ? $_GET['r'] : 'tarefa/index'; //se for incluido(include) na index mesmo sem parametro será capaz de entender as chamadas pela url

$campo_procurado = trim(strip_tags($_GET['c']));
$termo_de_busca = trim(strip_tags($_GET['t']));

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID' => 'id_tarefa',
    'Data' => 'data_e_hora',
    'Tarefa' => 'tarefa'
);
$tabela = array(TBL_TAREFAS);
$condition = " advogado_id_advogado = " . $_SESSION['user']['id'];
if ($_GET['date'] != 'all') {
    $condition .= " and data_e_hora >= '" . date('Y-m-d H:i:s') . "'";
}
if (!empty($campo_procurado) && !empty($termo_de_busca)) {
    $condition .= " and $campo_procurado LIKE '%$termo_de_busca%'";
}

$condition .= " order by data_e_hora";

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<table class="table">
    <tr>
        <?php print "<a class='btn current right action-links' href='index.php?r=$r" . (!empty($campo_procurado) ? "&c=$campo_procurado" : "") . (!empty($termo_de_busca) ? "&t=$termo_de_busca" : "") . ($_GET['date'] != "all" ? "&date=all" : "") . "'>" . (($_GET['date'] == "all") ? "Tarefas até esta data" : "Todas as Tarefas") . "</a>"; ?>
    </tr>
    <tr>
        <?php
        foreach (array_keys($campos_da_tabela) as $campos) {
            print "<th>$campos</th>";
        }
        ?>
    </tr>
    <?php
    $total = count($dados);
    $dados = array_slice($dados, $pages * $per_page, $per_page, true);
    $folder = explode("/", $_GET['r']);
    foreach ($dados as $key => $dado) {
        $id_tb = $dado['id_tarefa'];
        print "<tr>";
        foreach ($campos_da_tabela as $campos) {
            print "<td>" . str_replace("_", " ", $dado[$campos]) . "</td>";
        }
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