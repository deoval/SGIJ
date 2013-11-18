<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
if (is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    exit();
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array('id_cliente', 'nome', 'email', 'tipo_cliente', 'tipo_pessoa', 'estado', 'cidade', 'bairro', 'endereco', 'cep', 'numero', 'telefone','telefone_alternativo','telefone_celular','fax','observacao');
$tabela = array(TBL_CLIENTE);
$condition = " id_cliente = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO

if ($_POST['deletar'] == 'ok') {
    $pdo = new conectaPDO();
    $tabela = TBL_PESSOA_FISICA;
    $condition = "id_codigo_cliente_fisica = " . $id;
    $pdo->deleteData($tabela, $condition);
    $tabela = TBL_PESSOA_JURIDICA;
    $condition = "id_codigo_cliente_juridica = " . $id;
    $pdo->deleteData($tabela, $condition);
    $tabela = TBL_CLIENTE;
    $condition = "id_cliente = " . $id;
    $pdo->deleteData($tabela, $condition);
    $pdo->endConnection();
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . DELETE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=cliente/admin',1);
}
?>
<h2><?php print CONFIRM_EXCLUDE; ?></h2>
<?php
$campos_da_tabela = array(
    'ID do Cliente' => 'id_cliente',
    'Nome' => 'nome',
    'E-mail' => 'email',
    'Tipo de Cliente' => 'tipo_cliente',
    'Tipo de Pessoa' => 'tipo_pessoa',
    'Estado' => 'estado',
    'Cidade' => 'cidade',
    'Bairro' => 'bairro',
    'Endereço' => 'endereco',
    'CEP' => 'cep',
    'Numero' => 'numero',
    'Telefone' => 'telefone',
    'Telefone Alternativo' => 'telefone_alternativo',
    'Telefone Celular' => 'telefone_celular',
    'Fax' => 'fax',
    'Observação' => 'observacao',
);
foreach ($campos_da_tabela as $key => $campos) {
    print "$key : " . $dados[0][$campos] . "<br />\n";
}
?>
<form method=POST action="<?php print $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="deletar" value="ok" />
    <?php $l = explode("/", $_GET['r']); ?>
    <a class="btn btn-large" href="index.php?r=<?php print $l[0]; ?>/admin"><?php print BACK; ?></a>
    <input type="submit" class="btn btn-large" value="Confirmar Exclusão">
</form>
