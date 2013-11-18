<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pages = (empty($_GET['page'])) ? 0 : ($_GET['page'] - 1);
$per_page = 10;
$r = $_GET['r'];
$campo_procurado = trim(strip_tags($_GET['c']));
$termo_de_busca = trim(strip_tags($_GET['t']));

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID do Processo' => 'id_processo',
    'Numero Processo TJ' => 'numero_processo_tj',
    'Status do Processo' => 'status_processo',
    'Tipo de Ação' => 'tipo_acao',
    'Natureza da Ação' => 'natureza_da_acao',
    'Data de Abertura' => 'data_abertura'
);
$tabela[0] = TBL_PROCESSOS;
$condition = "1";
if (!empty($campo_procurado) && !empty($termo_de_busca)) {
    $condition .= " and $campo_procurado LIKE '%$termo_de_busca%'";
}
if ($_GET['date'] != 'all') {
    $condition .= " and data_abertura >= '" . date('Y-m-d H:i:s') . "'";
}

$condition .= " order by data_abertura";

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO

$total = count($dados);
$dados = array_slice($dados, $pages * $per_page, $per_page, true);
?>
<table class="table">
    <tr>
        <?php print Main::getForm($_SERVER["REQUEST_URI"], $_GET['r'], $campos_da_tabela, array('c' => $campo_procurado, 't' => $termo_de_busca)); ?>
        <?php print TEXT_DEFAULT_FILTER . "<a class='btn current right' href='index.php?r=processo/admin" . (!empty($campo_procurado) ? "&c=$campo_procurado" : "") . (!empty($termo_de_busca) ? "&t=$termo_de_busca" : "") . ($_GET['date'] != "all" ? "&date=all" : "") . "'>" . (($_GET['date'] == "all") ? TEXT_DISPLAY_FILTER : TEXT_DISPLAY_ALL) . "</a>"; ?>   
    </tr>
    <tr>
        <th>ID</th>
        <th>Numero Processo TJ</th>
        <th>Tipo de Ação</th>
        <th>Natureza da Ação</th>
        <th>Status</th>
        <th>Data de Abertura</th>
        <th>Acao</th>
    </tr>
    <?php
    foreach ($dados as $key => $dado) {
        $id_processo = $dado['id_processo'];

        print "<tr>";
        print "<td>" . $dado['id_processo'] . "</td>";
        print "<td>" . $dado['numero_processo_tj'] . "</td>";
        print "<td>" . $dado['tipo_acao'] . "</td>";
        print "<td>" . $dado['natureza_da_acao'] . "</td>";
        print "<td>" . $dado['status_processo'] . "</td>";
        print "<td>" . date_format(date_create($dado['data_abertura']), "d/m/Y") . "</td>";
        print "<td>";
        print Main::getAdminLinks($r, $id_processo);
        print "</td>";
        print "</tr>";
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
    $links = Main::getPagination($total, $per_page, $link_adicional, $_GET['r']);
?>
<div class="pagination">
    <ul>
        <?php print $links; ?>
    </ul>
</div>
