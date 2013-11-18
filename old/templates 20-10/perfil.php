<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
if (!in_array($_SESSION['user']['cargo'], array('advogado_socio', 'advogado'))) {
    $campos_da_tabela = array(
        'ID' => 'id',
        'Login' => 'login',
        'Cargo' => 'cargo',
        'Telefone' => 'telefone'
    );
    $tabela = array(TBL_USUARIO);
    $condition = "id_telefone_advogado = id and id = " . $_SESSION['user']['id'];
} else {
    $campos_da_tabela = array(
        'ID' => 'id',
        'Login' => 'login',
        'Cargo' => 'cargo',
        'CPF' => 'cpf',
        'RG' => 'rg',
        'OAB' => 'numero_oab',
        'Nome' => 'nome',
        'Endereço' => 'endereco',
        'Número' => 'numero',
        'CEP' => 'cep',
        'Bairro' => 'bairro',
        'Estado' => 'estado',
        'Cidade' => 'cidade',
        'Telefone' => 'telefone');
    $tabela = array(TBL_USUARIO, TBL_ADVOGADO);
    $condition = "advogado_id_advogado=id and id = " . $_SESSION['user']['id'];
}
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
if ($_POST && !empty($_POST)) {
    $pdo = new conectaPDO(); //INICIA CONEXÃO PDO
    $campos = array(
        'Senha' => Main::formatSlashes(base64_encode($_POST['senha']))
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo))
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = TBL_USUARIO;
    $condition = "id = " . $_SESSION['user']['id'];
    if (!empty($campos_a_alterar) && $_POST['senha'] == $_POST['confirmar_senha'])
        $update_senha = $pdo->updateData($campos_a_alterar, $condition, $tabela);
}
if ($update_senha == 1) {
    print "<p class='text-success'>Senha atualizada com sucesso!</p>";
}
?>
<h3>Perfil</h3>
<div class="w100both">
    <div class="left50percent">
        <h4 style="border-bottom: 1px solid #ddd;width: 80%;padding: 10px;border-left: 1px solid #ddd;">Dados Cadastrados</h4><br />
        <?php
        foreach ($campos_da_tabela as $key => $campos) {
            print "<div style='border:1px solid #ccc;margin:5px;padding:5px;margin-right: 15px;'><span class='capitalize'>" . $key . " : </span> " . str_replace('_', ' ', $dados[0][$campos]) . "</div>";
        }
        ?>
    </div>
    <div class="left50percent">
        <h4 style="border-bottom: 1px solid #ddd;width: 80%;padding: 10px;border-left: 1px solid #ddd;">Alterar senha</h4><br />
        <form method="POST" action="index.php?r=perfil" style="margin-top:5px">
            <input type='password' class='input-block-level' name='senha' title='Senha' placeholder='Senha' value=''><br />
            <input type='password' class='input-block-level' name='confirmar_senha' title='Confirmar Senha' placeholder='Confirmar Senha' value=''><br />
            <input type="submit" class="btn btn-primary" value="Alterar senha">
        </form>
    </div>
</div>
<style>
.left50percent{
width:45% !important;
}
.w100both{
margin:0% 5% !important;
}
</style>
