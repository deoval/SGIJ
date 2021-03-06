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
    'Login' => 'login',
    'Cargo' => 'cargo',
    'Telefone' => 'telefone',
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

$condition = " advogado_id_advogado = " . $id;
$tabela = array(TBL_ADVOGADO);
$dados_advogado = $pdo->getArrayData($campos_advogado, $tabela, $condition);

$dados = array_merge($dados, $dados_advogado);

$campos_da_tabela = array_merge($campos_da_tabela, $campos_advogado);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<div class="w100both">
    <div class="left50percent">
        <h3>Dados do Usuário <a class="btn left" href=index.php?r=usuario/update&id=<?php print $id; ?>>Atualizar</a></h3>
        <?php
        foreach ($campos_da_tabela as $key => $campos) {
            $indice = in_array($campos, $campos_advogado) ? 1 : 0;
            if ($indice == 1 && $dados[0]['cargo'] == 'secretaria')
                continue;
            print "<div id='" . $campos . "' class='preview'><span class='label'>" . $key . "</span> " . $dados[$indice][$campos] . "</div>\n";
        }
        ?>
    </div>
    <div class="left50percent">
        <h3>Processos alocados a este advogado</h3>
        <?php
//busca processos iniciados por este cliente
        $pdo = new conectaPDO(); //INICIA CONEXÃO PDO
        $campos_da_tabela = array(
            'id_processo', 'numero_processo_tj', 'status_processo'
        );
        $tabela = array('processos');
        $condition = "advogado_alocado = $id";
        $processos = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
        $pdo->endConnection(); //FIM DA CONEXÃO
        foreach ($processos as $processo) {
            print "<div><a href='index.php?r=processo/" . VIEW_FILENAME . "&id=" . $processo['id_processo'] . "' title='status:" . $processo['status_processo'] . "'>" . $processo['numero_processo_tj'] . "</a></div>";
        }
        ?>
    </div>
</div>
