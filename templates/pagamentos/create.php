<link rel="stylesheet" href="css/chosen.css">
<script src="js/jquery.price_format.js" type="text/javascript"></script>
<script src="js/chosen.jquery.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'id_processo', 'numero_processo_tj', 'tipo_acao'
);
$tabela = array(TBL_PROCESSOS);
$condition = "1";
$dropdown_dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
foreach ($dropdown_dados as $dd) {
    $dprocessos[$dd['id_processo'] . " - " . $dd['numero_processo_tj']] = $dd['id_processo'];
}

$campos_da_tabela = array(
    'Processo Relacionado' => processos_id_processo,
    'Plano de pagamento' => plano_pagamento,
    'Valor' => valor,
    'Parcelas pagas' => parcelas_pagas,
    'Forma de pagamento' => forma_pagamento,
    'Status do pagamento' => status_pagamento,
    'Vencimento' => vencimento,
    'Tempo de Atraso' => tempo_atraso,
);
$dstatus_pagamento = array(
    'Pendente' => 'pendente',
    'Quitado' => 'quitado'
);
$dforma_pagamento = array(
    'Cheque' => 'cheque',
    'Depósito em Conta' => 'deposito em conta',
    'Dinheiro' => 'dinheiro',
    'Imóveis' => 'imóveis',
    'Objeto e outros' => 'objeto e outros'
);
$dropdown = array('processos_id_processo' => $dprocessos,'status_pagamento'=>$dstatus_pagamento,'forma_pagamento' => $dforma_pagamento);
if (empty($dprocessos)) {
    print EMPTY_PROCESSOS_PAGAMENTO;
    exit();
}
if ($_POST && !empty($_POST)) {
    $campos = array(
        'vencimento' => Main::formatSlashes(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['vencimento'])))),
        'plano_pagamento' => Main::formatSlashes($_POST['plano_pagamento']),
        'valor' => Main::formatSlashes($_POST['valor']),
        'parcelas_pagas' => Main::formatSlashes($_POST['parcelas_pagas']),
        'status_pagamento' => Main::formatSlashes($_POST['status_pagamento']),
        'forma_pagamento' => Main::formatSlashes($_POST['forma_pagamento']),
        'tempo_atraso' => Main::formatSlashes($_POST['tempo_atraso']),
        'processos_id_processo' => Main::formatSlashes($_POST['processos_id_processo']),
    );
    $tabela = TBL_PAGAMENTOS;
    $error = "";
    foreach ($campos as $key => $campo) {
        if (empty($_POST[$campo]))
            $error .= $key;
    }

    $success = $pdo->insertData($campos, $tabela);

    $pdo->endConnection(); //FIM DA CONEXÃO
    if ($success) {
        print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . INSERT_SUCCESS . ' </strong></div>';
        //Main::redirect('index.php?r=pagamentos/' . UPDATE_FILENAME . '&id=' . $success, 2);
        Main::redirect('index.php?r=pagamentos/create',1);
    }
}
?>
<h1>Novo Pagamento</h1>
<div id="error"></div>
<form id="form-update" class="form-update" method="POST">
    <input type='hidden' class='input-block-level' name='id_pagamento' placeholder='null' value="null" />
    <?php
    foreach ($campos_da_tabela as $key => $campos) {
        if (empty($dropdown[$campos])) {
            print "<br /><input type='text' class='input-block-level' name='$campos' id='$campos' placeholder='" . $key . "'>";
        } else {

            $dados_campo_select = "";
            $dados_caixa_campos = "";

            $dados_campo_select = "<br /><label>" . $key . "</label><select name='$campos' class='choose chosen-select' id='$campos'>";
            foreach ($dropdown[$campos] as $key_d => $campo_dropdown) {
                $selected = $campo_dropdown == $_SESSION['user']['id'] ? 'selected' : '';
                $dados_campo_select .= "<option value='$campo_dropdown' $selected>" . $key_d . "</option>\n";
            }
            $dados_campo_select .= "</select>\n";

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
    <div><input type="submit" id="update-btn" class="btn btn-large btn-primary" value="<?php print CREATE; ?>"></input></div>
</form>
<script>
    jQuery(document).ready(function(){
        $('#valor').priceFormat({
                    prefix: '',
                    thousandsSeparator: ''
                });
        jQuery(".choose").change(function () {
            $(".choose-dropdown." + jQuery(this).attr('id') ).css('display','none');
            $("." + jQuery(this).val()).css('display','block');
            
        }).change();
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
    $( "#vencimento" ).datetimepicker({
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
        weekHeader: 'Не',
        dateFormat: 'dd/mm/yy',
        timeFormat: 'HH:mm:ss',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '',
        timeText: 'Horário',
        hourText: 'Hora',
        minuteText: 'Мinuto',
        showButtonPanel:  false,
	minDate: 0
    });
</script>
<style>
    .choose-dropdown.cliente,.choose-dropdown.advogado_alocado{display:none !important;}
    #cliente, #advogado_alocado{clear:both;}
#plano_pagamento,#valor,#parcelas_pagas,#vencimento,#tempo_atraso{width:450px;}

</style>
