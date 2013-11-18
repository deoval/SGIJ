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
    'ID' => 'id_cliente',
    'Nome' => 'nome',
    'E-mail' => 'email',
    'Tipo de Cliente' => 'tipo_cliente',
    'Tipo de Pessoa' => 'tipo_pessoa',
    'Bairro' => 'bairro',
    'Telefone' => 'numero_telefone'
);
$tabela = array(TBL_CLIENTE, TBL_DADOS_CLIENTE, TBL_TELEFONES_CLIENTE);
$condition = "dados_cliente_id_dados_cliente = id_cliente and id_cliente=id_telefone_cliente";

switch ($campo_procurado) {
    case 'cpf':
        $tabela[] = TBL_PESSOA_FISICA;
        $condition .= " and id_codigo_cliente_fisica = id_cliente ";
        break;
    case 'cnpj':
        $tabela[] = TBL_PESSOA_JURIDICA;
        $condition .= " and id_codigo_cliente_juridica = id_cliente ";
        break;
}
if (!empty($campo_procurado) && !empty($termo_de_busca)) {
    $condition .= " and $campo_procurado LIKE '%$termo_de_busca%'";
}

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<table class="table">
    <tr>
        <?php
        $campos_da_busca = array(
            'ID' => 'id_cliente',
            'Nome' => 'nome',
            'CNPJ' => 'cnpj',
            'CPF' => 'cpf',
            'Tipo de Cliente' => 'tipo_cliente',
        );
        print Main::getForm($_SERVER["REQUEST_URI"], $_GET['r'], $campos_da_busca, array('c' => $campo_procurado, 't' => $termo_de_busca));
        ?>
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
        $id_tb = $dado['id_cliente'];
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