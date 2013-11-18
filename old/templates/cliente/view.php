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
    'Telefone' => 'numero_telefone'
);
$tabela = array(TBL_CLIENTE, TBL_DADOS_CLIENTE, TBL_TELEFONES_CLIENTE);
$condition = "dados_cliente_id_dados_cliente = id_cliente and id_cliente = $id and id_cliente=id_telefone_cliente ";
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$extensao_do_campo_tabela = array('tipo_pessoa' => array(
        'pessoa_fisica' => array(
            'RG' => 'rg',
            'CPF' => 'cpf'
        ),
        'pessoa_juridica' => array(
            'Inscr. Estadual' => 'inscricao_estadual',
            'Inscr.Municipal' => 'inscricao_municipal',
            'CNPJ' => 'cnpj'
        ),
    ),
);
$condition = "id_codigo_cliente_" . str_replace('pessoa_', '', $dados[0]['tipo_pessoa']) . "=id_cliente and id_cliente = " . $id;
$dados_pessoa[$dados[0]['tipo_pessoa']] = $pdo->getArrayData($extensao_do_campo_tabela['tipo_pessoa'][$dados[0]['tipo_pessoa']], array($dados[0]['tipo_pessoa'], TBL_CLIENTE), $condition);
$dados = array_merge($dados, $dados_pessoa);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<div class="w100both">
    <div class="left50percent">
        <h3>Dados do Cliente <a class="btn left" href=index.php?r=cliente/update&id=<?php print $id; ?>>Atualizar</a></h3>
        <?php
        foreach ($campos_da_tabela as $key => $campos) {
            if (!is_array($extensao_do_campo_tabela[$campos])) {
                print "<div id='" . $campos . "' class='preview'><span class='label'>" . $key . "</span> " . $dados[0][$campos] . "</div>\n";
            } else {
                foreach ($extensao_do_campo_tabela[$campos][$dados[0][$campos]] as $key_sub => $campos_add) {
                    print "<div id='" . $key_sub . "' class='preview'><span class='label'>" . $key_sub . "</span> " . $dados[$dados[0][$campos]][0][$campos_add] . "</div>\n";
                }
            }
        }
        ?>
    </div>
    <div class="left50percent">
        <h3>Processos iniciados por este cliente</h3>
        <?php
//busca processos iniciados por este cliente
        $pdo = new conectaPDO(); //INICIA CONEXÃO PDO
        $campos_da_tabela = array(
            'id_processo', 'numero_processo_tj', 'status_processo'
        );
        $tabela = array('processos');
        $condition = "cliente = $id";
        $processos = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
        $pdo->endConnection(); //FIM DA CONEXÃO
        foreach ($processos as $processo) {
            print "<div><a href='index.php?r=processo/" . VIEW_FILENAME . "&id=" . $processo['id_processo'] . "' title='status:" . $processo['status_processo'] . "'>" . $processo['numero_processo_tj'] . "</a></div>";
        }
        ?>
    </div>
</div>