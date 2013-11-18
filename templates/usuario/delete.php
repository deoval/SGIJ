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
$campos_da_tabela = array(
    'ID' => 'id',
    'Nome' => 'nome',
    'Login' => 'login',
    'Cargo' => 'cargo',
    'Telefone' => 'telefone',
    'Telefone Alternativo' => 'telefone_alternativo',
    'Telefone Celular' => 'telefone_celular',
    'Fax' => 'fax',
    'Observação' => 'observacao',
);
$tabela = array(TBL_USUARIO);
$condition = " id = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);

$campos_advogado = array(
    'RG' => 'rg',
    'CPF' => 'cpf',
    'OAB' => 'numero_oab',
    'Estado' => 'estado',
    'Cidade' => 'cidade',
    'Endereço' => 'endereco',
    'N°' => 'numero',
    'CEP' => 'cep',
    'Bairro' => 'bairro');
$condition = "advogado_id_advogado = " . $id;
$tabela = array(TBL_ADVOGADO);
$dados_advogado = $pdo->getArrayData($campos_advogado, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
$dados = array_merge($dados, $dados_advogado);

if ($_POST['deletar'] == 'ok') {
    $pdo = new conectaPDO();

    $tabela = TBL_ADVOGADO;
    $condition = "advogado_id_advogado = " . $id;
    $pdo->deleteData($tabela, $condition);

    $tabela = TBL_USUARIO;
    $condition = "id = " . $id;
    $pdo->deleteData($tabela, $condition);
    $pdo->endConnection();
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . DELETE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=usuario/admin',1);
}
?>
<h2><?php print CONFIRM_EXCLUDE; ?></h2>
<?php
$all_campos_advogado = array_merge($campos_da_tabela, $campos_advogado);
unset($all_campos_advogado['Telefone']);
$all_campos_advogado['Telefone'] = 'telefone';
unset($all_campos_advogado['Telefone Alternativo']);
$all_campos_advogado['Telefone Alternativo'] = 'telefone_alternativo';
unset($all_campos_advogado['Telefone Celular']);
$all_campos_advogado['Telefone Celular'] = 'telefone_celular';
unset($all_campos_advogado['Fax']);
$all_campos_advogado['Fax'] = 'fax';
unset($all_campos_advogado['Observação']);
$all_campos_advogado['Observação'] = 'observacao';

foreach ($all_campos_advogado as $key => $campos) {
$value = (!empty($dados[0][$campos])?$dados[0][$campos]:$dados[1][$campos]);
    print !empty($value)?"$key : " . $value . "<br />\n":"";
}
?>
<form method=POST action="<?php print $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="deletar" value="ok" />
    <?php $l = explode("/", $_GET['r']); ?>
    <a class="btn btn-large" href="index.php?r=<?php print $l[0]; ?>/admin"><?php print BACK; ?></a>
    <input type="submit" class="btn btn-large" value="Confirmar Exclusão">
</form>
