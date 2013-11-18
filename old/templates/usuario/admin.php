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
    'ID' => 'id',
    'Login' => 'login',
    'Cargo' => 'cargo');
$tabela = array(TBL_USUARIO, TBL_TELEFONES_USUARIO);
$condition = "id=id_telefone_advogado";
if (!empty($campo_procurado) && !empty($termo_de_busca)) {
    $condition .= " and $campo_procurado LIKE '%$termo_de_busca%'";
}

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<table class="table">
    <tr>
        <?php print Main::getForm($_SERVER["REQUEST_URI"], $_GET['r'], $campos_da_tabela, array('c' => $campo_procurado, 't' => $termo_de_busca)); ?>
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
    $folder = explode("/", $_GET['r']);
    foreach ($dados as $key => $dado) {
        $id_tb = $dado['id'];
        print "<tr>";
        foreach ($campos_da_tabela as $campos) {
            print "<td>" . str_replace("_", " ", $dado[$campos]) . "</td>";
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
if ($total > $per_page)
    $links = Main::getPagination($total, $per_page, $link_adicional, $_GET['r']);
?>
<div class="pagination">
    <ul>
        <?php print $links; ?>
    </ul>
</div>
