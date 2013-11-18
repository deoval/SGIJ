<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pages = (empty($_GET['page'])) ? 0 : ($_GET['page'] - 1);
$per_page = 10;
$r = $_GET['r'];
$campo_procurado = trim(strip_tags($_GET['c']));
$termo_de_busca = trim(strip_tags($_GET['t']));
$is_data = array('vencimento');

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID (Pgto)' => id_pagamento,
    'Tempo de Atraso' => tempo_atraso,
    'Plano de pagamento' => plano_pagamento,
    'Valor' => valor,
    'Parcelas pagas' => parcelas_pagas,
    'Forma de pagamento' => forma_pagamento,
    'Status' => status_pagamento,
    'Vencimento' => vencimento,
    'ID Processo' => processos_id_processo
);

$tabela = array(TBL_PROCESSOS, TBL_PAGAMENTOS);
$condition = " processos_id_processo = id_processo";
if (!empty($campo_procurado) && !empty($termo_de_busca)) {
    $condition .= " and $campo_procurado LIKE '%$termo_de_busca%'";
}
if ($_GET['date'] != 'all') {
    $condition .= " and vencimento >= '" . date('Y-m-d H:i:s') . "'";
}

$condition .= " order by vencimento,id_pagamento";

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO

$total = count($dados);
$dados = array_slice($dados, $pages * $per_page, $per_page, true);
?>
<table class="table">
    <tr>
        <?php
$campos_da_busca = $campos_da_tabela;
unset($campos_da_busca['Parcelas pagas']);
 print Main::getForm($_SERVER["REQUEST_URI"], $_GET['r'], $campos_da_busca, array('c' => $campo_procurado, 't' => $termo_de_busca)); ?>
        <?php print TEXT_DEFAULT_FILTER . "<a class='btn current right' href='index.php?r=pagamentos/admin" . (!empty($campo_procurado) ? "&c=$campo_procurado" : "") . (!empty($termo_de_busca) ? "&t=$termo_de_busca" : "") . ($_GET['date'] != "all" ? "&date=all" : "") . "'>" . (($_GET['date'] == "all") ? TEXT_DISPLAY_FILTER : TEXT_DISPLAY_ALL) . "</a>"; ?>   
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
    foreach ($dados as $key => $dado) {
        $id_tb = $dado['id_pagamento'];

        foreach ($campos_da_tabela as $campos) {
            $value = in_array($campos, $is_data) ? date_format(date_create($dado[$campos]), "d/m/Y H:i:s") : $dado[$campos];
            $value = ($campos == 'processos_id_processo' ? "<a href='index.php?r=processo/view&id=" . $value . "'>" . $value . "</a>" : $value);
            print "<td>" . str_replace("_", " ", $value) . "</td>";
        }
        print "<td>";
        print Main::getAdminLinks($r, $id_tb);
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
