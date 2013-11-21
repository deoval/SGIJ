<link rel="stylesheet" href="css/chosen.css">
<script src="js/chosen.jquery.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
/* busca os advogados para popular o dropdown */
$campos_da_tabela = array(
    'id', 'nome', 'login'
);
$tabela = array(TBL_USUARIO, TBL_ADVOGADO);
$condition = "advogado_id_advogado = id and cargo IN ('advogado','advogado_socio')";
$dropdown_dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
foreach ($dropdown_dados as $dd) {
    $dadvogados[$dd['login'] . " - " . $dd['nome']] = $dd['id'];
}

/* busca os clientes para popular dropdown */
$campos_da_tabela = array(
    'id_cliente', 'nome'
);

$tabela = array(TBL_CLIENTE);
$condition = "1";

$dropdown_dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);

foreach ($dropdown_dados as $dd) {
    $dclientes[$dd['id_cliente'] . ' - ' . $dd['nome']] = $dd['id_cliente'];
}
if (empty($dclientes)) {
    print EMPTY_CLIENTE_PROCESSOS;
    exit();
}
/* busca os processos */
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
$dnatureza_acao = array(
'Penal' => 'Penal',
'Trabalhista' => 'Trabalhista',
'Pequenas causas' => 'Pequenas causas',
'Família' => 'Família',
'JECrim' => 'Jecrim',
'Orfanológico' => 'Orfanológico',
'Execução penal' => 'Execucao penal',
'Registro público'=>'Registro público'
);
$dposicao_cliente = array(
'Réu' => 'Reu',
'Autor' => 'Autor',
);
$dstatus_processo = array(
'Em andamento' => 'em andamento',
'Encerrado' => 'encerrado'
);
$dropdown = array('advogado_alocado' => $dadvogados, 'cliente' => $dclientes, 'natureza_da_acao'=>$dnatureza_acao, 'posicao_cliente' => $dposicao_cliente ,'status_processo' => $dstatus_processo);

if ($_POST && !empty($_POST)) {

    $campos_da_tabela_existente = array(
        'ID' => 'id_processo'
    );
    $tabela = array(TBL_PROCESSOS);
    $condition = " numero_processo_tj = '" . Main::formatSlashes($_POST['numero_processo_tj']) . "'";
    $dados_processo_existente = $pdo->getArrayData($campos_da_tabela_existente, $tabela, $condition);
    if (!empty($dados_processo_existente)) {
        $erro = 1;
    }

    $campos = array(
        'advogado_alocado' => Main::formatSlashes($_POST['advogado_alocado']),
        'cliente' => Main::formatSlashes($_POST['cliente']),
        'data_abertura' => Main::formatSlashes(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['data_abertura'])))),
        'localizacao_documentos' => Main::formatSlashes($_POST['localizacao_documentos']),
        'natureza_da_acao' => Main::formatSlashes($_POST['natureza_da_acao']),
        'posicao_cliente' => Main::formatSlashes($_POST['posicao_cliente']),
        'status_processo' => Main::formatSlashes($_POST['status_processo']),
        'tipo_acao' => Main::formatSlashes($_POST['tipo_acao']),
        'numero_processo_tj' => Main::formatSlashes($_POST['numero_processo_tj']),
    );
    $tabela = TBL_PROCESSOS;
    if ($erro != 1)
    $success = $pdo->insertData($campos, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    if ($erro != 1) {
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . INSERT_SUCCESS . ' </strong></div>';
    //Main::redirect('index.php?r=processo/' . UPDATE_FILENAME . '&id=' . $success, 2);
    Main::redirect('index.php?r=processo/create',1);
}
}
?>
<h1>Novo Processo</h1>
<div id="error"><?php if ($erro == 1) print "Ocorreu um erro ao tentar salvar os dados, verifique se já existe o processo"; ?></div>
<form id="form-update" class="form-update" method="POST">
    <input type='hidden' class='input-block-level' name='id_processo' placeholder='null' value="null" />
    <?php
    foreach ($campos_da_tabela as $key => $campos) {
        if (empty($dropdown[$campos])) {
            print "<input type='text' class='input-block-level' name='$campos' id='$campos' placeholder='" . $key . "'><br />";
        } else {

            $dados_campo_select = "";
            $dados_caixa_campos = "";

            $dados_campo_select = "<label>" . $key . "</label><select name='$campos' class='choose chosen-select' id='$campos'>";
            foreach ($dropdown[$campos] as $key_d => $campo_dropdown) {
                $selected = $campo_dropdown == $_SESSION['user']['id'] ? 'selected' : '';
                $dados_campo_select .= "<option value='$campo_dropdown' $selected>" . $key_d . "</option>\n";
            }
            $dados_campo_select .= "</select><br />";

            foreach ($dropdown[$campos] as $key_d => $campo_dropdown) {
                $dados_caixa_campos .= "<div class='choose-dropdown $campos $key_d'>\n";
                if (is_array($campo_dropdown)) {
                    foreach ($campo_dropdown as $key_subd => $valorcampo_dropdown) {
                        $dados_caixa_campos .= "<input type='text' class='input-block-level' name='$valorcampo_dropdown' placeholder='" . $key_subd . "' title='" . $key_subd . "'>\n";
                    }
                }
                $dados_caixa_campos .= "</div>\n";
            }
            print $dados_campo_select;
            print $dados_caixa_campos;
        }
    }
    ?>
    <input type="submit" id="update-btn" class="btn btn-large btn-primary" value="<?php print CREATE; ?>"></input>
</form>
<span id=count></span>
<script>
    jQuery(document).ready(function(){

        jQuery(".choose").change(function () {
            $(".choose-dropdown." + jQuery(this).attr('id') ).css('display','none');
            $("." + jQuery(this).val()).css('display','block');
            
        }).change();
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
        jQuery("#form-update").submit(function(){
            jQuery('#error').html("");
<?php foreach ($campos_da_tabela as $campos) { ?>
                        if(jQuery('#<?php print $campos; ?>').val()==""){
                            jQuery('#error').append('Formulario incompleto!<br />');
                            return false;
                        }
<?php } ?>
                });
            });
            $('.chosen-select').chosen({no_results_text:'Nenhum resultado encontrado!'});
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
</script>

<style>
    .choose-dropdown.cliente,.choose-dropdown.advogado_alocado{display:none !important;}
    #cliente, #advogado_alocado{clear:both;}

#numero_processo_tj, #tipo_acao,#data_abertura{width:450px;}
</style>
