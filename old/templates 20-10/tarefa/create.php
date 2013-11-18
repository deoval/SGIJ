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

/* busca as tarefas */
$campos_da_tabela = array(
    'Data e Hora' => 'data_e_hora',
    'Tarefa' => 'tarefa',
    'Advogado' => 'advogado_id_advogado',
);
$dropdown = array('advogado_id_advogado' => $dadvogados);

if ($_POST && !empty($_POST)) {

    $campos = array(
        'id_tarefa' => null,
        'data_e_hora' => Main::formatSlashes(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['data_e_hora'])))),
        'tarefa' => Main::formatSlashes($_POST['tarefa']),
        'advogado_id_advogado' => Main::formatSlashes($_POST['advogado_id_advogado']),
    );
    $tabela = TBL_TAREFAS;
    $success = $pdo->insertData($campos, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . INSERT_SUCCESS . ' </strong></div>';
    // Main::redirect('index.php?r=tarefa/' . UPDATE_FILENAME . '&id=' . $success, 2);
    Main::redirect('index.php?r=tarefa/create',1);
}
?>
<h1>Nova Tarefa</h1>
<div id="error"></div>
<form id="form-update" class="form-update" method="POST">
    <input type='hidden' class='input-block-level' name='id_tarefa' placeholder='null' value="null" />
    <?php
    foreach ($campos_da_tabela as $key => $campos) {
        if (empty($dropdown[$campos])) {
            print "<input type='text' class='input-block-level' name='$campos' id='$campos' placeholder='" . $key . "'>";
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
    <input type="submit" id="update-btn" class="btn btn-large btn-primary" value="<?php print CREATE; ?>"></input>
</form>
<span id=count></span>
<script>
    jQuery(document).ready(function(){
        jQuery(".choose").change(function () {
            $(".choose-dropdown." + jQuery(this).attr('id') ).css('display','none');
            $("." + jQuery(this).val()).css('display','block');
            
        }).change();
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
            $('.chosen-select').chosen({no_results_text:'Nenhum resultado encontrado!'});
            $( "#data_e_hora" ).datetimepicker({
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
$("#tarefa").attr('maxlength','3000');
$("#tarefa").keyup(function(){
  $("#count").text("Caracteres Restantes : " + (3000 - $(this).val().length));
});
jQuery("#tarefa").focusout(function(){
  jQuery("#count").text("");
});
</script>
<style>
    .choose-dropdown.cliente,.choose-dropdown.advogado_alocado{display:none !important;}
    #cliente, #advogado_alocado{clear:both;}
</style>
