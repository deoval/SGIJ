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
    'ID' => 'id_tarefa',
    'Login' => 'login',
    'Advogado' => 'advogado_id_advogado',
    'Data e hora' => 'data_e_hora',
    'Tarefa' => 'tarefa',

);
$tabela = array(TBL_TAREFAS, TBL_USUARIO);
$condition = " advogado_id_advogado = id ";
$condition .= " and id_tarefa = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$is_data = array('data_e_hora');
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<div class="w100both">
    <div class="left50percent">
        <h3>Dados da Tarefa <a class="btn left" href=index.php?r=tarefa/update&id=<?php print $id; ?>>Atualizar</a></h3>
        <?php
        foreach ($campos_da_tabela as $key => $campos) {
            $value = in_array($campos, $is_data) ? date_format(date_create($dados[0][$campos]), "d/m/Y H:i:s") : $dados[0][$campos];
            $value = ($campos == 'advogado_id_advogado' ? "<a href='index.php?r=usuario/view&id=" . $value . "'>Ver Perfil</a>" : $value);
            print "<div id='" . $campos . "' class='preview'><b>" . $key . " : </b> " . $value . "</div>\n";
        }
        ?>
    </div>
</div>
