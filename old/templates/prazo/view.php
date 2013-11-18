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
    'ID do Prazo' => id_prazo,
    'Processo' => id_num_processo,
    'Tipo de Prazo' => tipo_de_prazo,
    'Data de Inicio' => data_inicio,
    'Data Limite' => data_limite
 
);
$tabela = array(TBL_PRAZOS);
$condition = " id_prazo = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$is_data = array('data_inicio', 'data_limite');
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<div class="w100both">
    <div class="left50percent">
        <h3>Dados do prazo <a class="btn left" href=index.php?r=prazo/update&id=<?php print $id; ?>>Atualizar</a></h3>
        <?php
        foreach ($campos_da_tabela as $key => $campos) {
            $value = in_array($campos, $is_data) ? date_format(date_create($dados[0][$campos]), "d/m/Y H:i:s") : $dados[0][$campos];
            $value = ($campos == 'id_num_processo' ? "<a href='index.php?r=processo/view&id=" . $value . "'>Ver Processo</a>" : $value);
            print "<div id='" . $campos . "' class='preview'><span class='label'>" . $key . "</span> " . str_replace('_', ' ', $value) . "</div>\n";
        }
        ?>
    </div>
</div>
