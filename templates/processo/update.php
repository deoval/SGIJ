<script src="js/jquery-ui-timepicker-addon.js"></script>
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
$campos_da_tabela = array(
    'Advogado Alocado' => 'advogado_alocado',
    'Cliente' => 'cliente',
   'Numero Processo TJ' => 'numero_processo_tj',
    'Tipo de Ação' => 'tipo_acao',
      'Natureza da Ação' => 'natureza_da_acao',
    'Posição do Cliente' => 'posicao_cliente',
    'Status do Processo' => 'status_processo',
   'Data de Abertura' => 'data_abertura',
    'Localização dos Documentos' => 'localizacao_documentos',

);
$tabela = array(TBL_PROCESSOS);
$condition = "id_processo = $id";
$processos = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$is_data = array('data_abertura');

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
$dnatureza_acao = array(
'Penal' => 'penal',
'Trabalhista' => 'trabalhista',
'Pequenas causas' => 'pequenas causas',
'Família' => 'familia',
'JECrim' => 'jecrim',
'Orfanológico' => 'orfanologico',
'Execução penal' => 'execucao penal',
'Registro público'=>'registro publico'
);
$dposicao_cliente = array(
'Réu' => 'Reu',
'Autor' => 'Autor',
);
$dstatus_processo = array(
'Em andamento' => 'em andamento',
'Encerrado' => 'encerrado'
);
$dropdown = array('natureza_da_acao'=>$dnatureza_acao, 'posicao_cliente' => $dposicao_cliente ,'status_processo' => $dstatus_processo);

if ($_POST && !empty($_POST)) {

   $campos_da_tabela_existente = array(
        'ID' => 'id_processo'
    );
    $tabela = array(TBL_PROCESSOS);
    $condition = " id_processo <> $id and  numero_processo_tj = '" . Main::formatSlashes($_POST['numero_processo_tj']) . "' 
";
    $dados_processo_existente = $pdo->getArrayData($campos_da_tabela_existente, $tabela, $condition);
    if (!empty($dados_processo_existente)) {
        $erro = 1;
    }
    $campos = array(
        'advogado_alocado' => Main::formatSlashes($_POST['advogado_alocado']),
        'cliente' => Main::formatSlashes($_POST['cliente']),
        'data_abertura' => Main::formatSlashes(date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_abertura'])))),
        'localizacao_documentos' => Main::formatSlashes($_POST['localizacao_documentos']),
        'natureza_da_acao' => Main::formatSlashes($_POST['natureza_da_acao']),
        'posicao_cliente' => Main::formatSlashes($_POST['posicao_cliente']),
        'status_processo' => Main::formatSlashes($_POST['status_processo']),
        'tipo_acao' => Main::formatSlashes($_POST['tipo_acao']),
        'numero_processo_tj' => Main::formatSlashes($_POST['numero_processo_tj']),
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo) && $erro != 1)
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = TBL_PROCESSOS;
    $condition = "id_processo = $id";
    if (!empty($campos_a_alterar))
        $pdo->updateData($campos_a_alterar, $condition, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    if ($erro != 1) {
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . UPDATE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=processo/' . UPDATE_FILENAME . '&id=' . $id, 1);
}
}


$pdo->endConnection(); //FIM DA CONEXÃO
?>
<div id="error"><?php if ($erro == 1) print "Processo existente "; ?></div>
<form id="form-update" class="form-update" method="POST">
    <?php
    foreach ($campos_da_tabela as $key => $campo) {
if (empty($dropdown[$campo])) {
        if (in_array($campo, array_keys($campos_juncao))) {

            $value = in_array($campo, $is_data) ? date_format(date_create($campos_juncao[$campo]), "d/m/Y") : $campos_juncao[$campo];
            print " $key  " . $value . "<br />"; //value =  $campos_juncao[$campo] 
        } else {
            $value = in_array($campo, $is_data) ? date_format(date_create($processos[0][$campo]), "d/m/Y") : $processos[0][$campo];
            print "<input type='text' class='input-block-level' title='$key' id='$campo' name='$campo' placeholder='" . $key . "' value=\"" . $value . "\"><br />"; //value =  $processos[0][$campo] 
        }
    }else{
        $dados_campo_select = "";
        $dados_caixa_campos = "";
        $dados_campo_select = "<label>" . $key . "</label><select name='$campo' class='choose' id='$campo' >";
        foreach ($dropdown[$campo] as $key_d => $campo_dropdown) {
            $dados_campo_select .= "<option value='$key_d' " . ($processos[0][$campo] == $key_d ? " selected" : "") . ">" . str_replace('_', ' ', $key_d) . "</option>\n";
        }
        $dados_campo_select .= "</select><br />";
        print $dados_campo_select;
}
}
    ?>
    <input type="submit" id="update-btn" class="btn btn-large btn-primary" value="<?php print UPDATE; ?>"></input>
</form>
<span id=count></span>
<script>
    $( "#data_abertura" ).datepicker({
        closeText: 'Fechar',
        prevText: 'Anterior',
        nextText: 'Proximo',
        currentText: 'Dia e Hora atual',
        monthNames: ['Janeiro','Fevereiro','Маrço','Аbril','Маio','Junho',
            'Julho','Аgosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Маr','Аbr','Маi','Jun',
            'Jul','Аgo','Set','Оuт','Nov','Dez'],
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
        dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
        weekHeader: 'Неader',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '',
        showButtonPanel:  false
    });
jQuery(document).ready(function(){
	jQuery("#numero_processo_tj").keyup(function() {
		var valor = jQuery("#numero_processo_tj").val().replace(/[a-zA-Z]+/g,'');
		jQuery("#numero_processo_tj").val(valor);
	});
jQuery("#localizacao_documentos").attr('maxlength','2000');
jQuery("#localizacao_documentos").keyup(function(){
  jQuery("#count").text("Caracteres Restantes : " + (2000 - $(this).val().length));
});
jQuery("#localizacao_documentos").focusout(function(){
  jQuery("#count").text("");
});

});

</script>
<style>
#numero_processo_tj, #tipo_acao,#data_abertura{width:450px;}
</style>
