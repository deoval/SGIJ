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
    'ID do Processo' => 'id_processo',
    'Cliente' => 'cliente',
    'Advogado Alocado' => 'advogado_alocado',
    'Natureza da Acao' => 'natureza_da_acao',
    'Tipo de Ação' => 'tipo_acao',
    'Data de Abertura' => 'data_abertura',
    'Posição do Cliente' => 'posicao_cliente',
    'Status do Processo' => 'status_processo',
    'Localização dos Documento' => 'localizacao_documentos',
    'Numero Processo TJ' => 'numero_processo_tj',
);
$is_data = array('data_abertura');
$tabela = array(TBL_PROCESSOS);
$condition = "id_processo = $id";
$processos = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);


$campos = array(
    'nome' => 'nome',
);
$tabela = array(TBL_CLIENTE);
$condition = "id_cliente = " . $processos[0]['cliente'];
$cliente = $pdo->getArrayData($campos, $tabela, $condition);

$campos = array(
    'login' => 'login',
);
$tabela = array(TBL_USUARIO);
$condition = "id = " . $processos[0]['advogado_alocado'];
$advogado_alocado = $pdo->getArrayData($campos, $tabela, $condition);

$id_do_cliente = $processos[0]['cliente'];
$campos_juncao['cliente'] = "<a href=index.php?r=cliente/view&id=$id_do_cliente>" . $cliente[0]['nome'] . " ($id_do_cliente)</a>";
$id_do_advogado = $processos[0]['advogado_alocado'];
$campos_juncao['advogado_alocado'] = "<a href=index.php?r=usuario/view&id=$id_do_advogado>" . $advogado_alocado[0]['login'] . " ($id_do_advogado)</a>";
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<div class="w100both">
    <div class="left50percent">
        <h3>Dados do Processo <a class="btn left" href=index.php?r=processo/update&id=<?php print $id; ?>>Atualizar</a></h3>
        <?php
        foreach ($campos_da_tabela as $key => $campo) {
            if (in_array($campo, array_keys($campos_juncao))) {
                print "<span class='label'>$key </span> " . $campos_juncao[$campo] . "<br />";
            } else {
                print "<span class='label'>$key </span> " . (in_array($campo, $is_data) ? date_format(date_create($processos[0][$campo]), "d/m/Y") : $processos[0][$campo]) . "<br />";
            }
        }
        ?>
    </div>
    <div class="left50percent">
        <?php
        $pdo = new conectaPDO(); //INICIA CONEXÃO PDO
        $campos_da_tabela = array(
            'ID do Prazo' => id_prazo,
            'Processo' => id_num_processo,
            'Data de Inicio' => data_inicio,
            'Data Limite' => data_limite,
        );
        $tabela = array(TBL_PRAZOS);
        $condition = "id_num_processo = $id order by data_limite, data_inicio ";
        $prazos = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
        $pdo->endConnection(); //FIM DA CONEXÃO
        ?>
        <h3>Prazos para este processo</h3>
        <?php
        foreach ($prazos as $prazo) {
            print "<div><a href='index.php?r=prazo/" . VIEW_FILENAME . "&id=" . $prazo['id_prazo'] . "' title='Data de Inicio/Limite: " . date_format(date_create($prazo['data_inicio']), "d/m/Y H:i:s") . " / " . date_format(date_create($prazo['data_limite']), "d/m/Y H:i:s") . "'>" . date_format(date_create($prazo['data_inicio']), "d/m/Y H:i:s") . " / " . date_format(date_create($prazo['data_limite']), "d/m/Y H:i:s") . "</a></div>";
        }
        ?>

    </div>
</div>
