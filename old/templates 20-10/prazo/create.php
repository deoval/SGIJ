<link rel="stylesheet" href="css/chosen.css">
<script src="js/chosen.jquery.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<div style="position:fixed;left:70%;display: block;padding: 9.5px;width:250px;margin: 0 0 10px;font-size: 13px;background-color: #f5f5f5;
     border: 1px solid #ccc;border: 1px solid rgba(0, 0, 0, 0.15);-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;">
    Referencia:<br />
    Contestação 15 dias<br />
    Réplica 10 dias<br />
    Agravo de instrumento 10 dias<br />
    Agravo regimental 05 dias<br />
    Apelação 15 dias<br />
    Embargo de declaração 05 dias<br />
    Embargo infringentes 05 dias<br />
    Recurso especial 15 dias<br />
    Recurso extraordinário 15 dias<br />
    Recurso ordinário 15 dias<br />
    Mandado de segurança 180 dias(6 meses).<br />
</div>
<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
/* busca os processos para popular o dropdown */
$campos_da_tabela = array(
    'id_processo', 'numero_processo_tj', 'tipo_acao'
);
$tabela = array(TBL_PROCESSOS);
$condition = "1";
//$condition = " data_abertura >= '" . date('Y-m-d H:i:s') . "'";
$dropdown_dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
foreach ($dropdown_dados as $dd) {
    $dprocessos[$dd['id_processo'] . " - " . $dd['numero_processo_tj']] = $dd['id_processo'];
}
$dtipo_de_prazo = array(
    'Contestação' => 'contestacao',
    'Réplica' => 'replica',
    'Agravo de instrumento' => 'agravo_de_instrumento',
    'Agravo regimental' => 'agravo_regimental',
    'Apelação' => 'apelacao',
    'Embargo de declaração' => 'embargo_de_declaracao',
    'Embargo infringentes' => 'embargo_infringentes',
    'Recurso especial' => 'recurso_especial',
    'Recurso extraordinário' => 'recurso_extraordinario',
    'Recurso ordinário' => 'recurso_ordinario',
    'Mandado de segurança' => 'mandado_de_seguranca'
);
/* busca os prazos */
$campos_da_tabela = array(
    'Tipo de Prazo' => tipo_de_prazo,
    'Data de Inicio' => data_inicio,
    'Data Limite' => data_limite,
    'Numero do Processo' => id_num_processo,
);
$dropdown = array('id_num_processo' => $dprocessos, 'tipo_de_prazo' => $dtipo_de_prazo);
if (empty($dprocessos)) {
    print EMPTY_PROCESSOS_PRAZO;
    exit();
}
if ($_POST && !empty($_POST)) {
    $campos = array(
        'id_prazo' => null,
        'data_limite' => Main::formatSlashes(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['data_limite'])))),
        'data_inicio' => Main::formatSlashes(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['data_inicio'])))),
        'id_num_processo' => Main::formatSlashes($_POST['id_num_processo']),
        'tipo_de_prazo' => Main::formatSlashes($_POST['tipo_de_prazo'])
    );
    $tabela = TBL_PRAZOS;
    $success = $pdo->insertData($campos, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . INSERT_SUCCESS . ' </strong></div>';
    // Main::redirect('index.php?r=prazo/' . UPDATE_FILENAME . '&id=' . $success,2);
    Main::redirect('index.php?r=prazo/create',1);
}
?>
<h1>Novo Prazo</h1>
<div id="error"></div>
<form id="form-update" class="form-update" method="POST">
    <input type='hidden' class='input-block-level' name='id_prazo' placeholder='null' value="null" />
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
<script>
    jQuery(document).ready(function(){
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
                    if(Date(jQuery('#data_inicio').val()) > Date(jQuery('#data_limite').val()) ){
                        jQuery('#error').append('Data inicio nao pode ser maior que a limite!<br />');
                        return false;
                    }
                });
            });
            $('.chosen-select').chosen({no_results_text:'Nenhum resultado encontrado!'});
$( "#data_inicio" ).attr('readonly','');
$( "#data_limite" ).attr('readonly','');
            $( "#data_inicio" ).datetimepicker({
                beforeShowDay: function(date) {
                    var day = date.getDay();
                    return [(day != 0 && day != 6 ), ''];
                },
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
                minDate: new Date('d') + 1
            });
            $( "#data_limite" ).datetimepicker({
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
            });
            function updateDataLimite(data_inicio){
                var dias_do_prazo = $('#tipo_de_prazo').val();
                var prazosjs = new Array();
                prazosjs['contestacao'] = 15;
                prazosjs['replica'] = 10;
                prazosjs['agravo_de_instrumento'] = 10;
                prazosjs['agravo_regimental'] = 5;
                prazosjs['apelacao'] = 15;
                prazosjs['embargo_de_declaracao'] = 5;
                prazosjs['embargo_infringentes'] = 5;
                prazosjs['recurso_especial'] = 15;
                prazosjs['recurso_extraordinario'] = 15;
                prazosjs['recurso_ordinario'] = 15;
                prazosjs['mandado_de_seguranca'] = 180;

                var data = data_inicio.split(' ');
                dt = data[0].split('/');
                dia = dt[0];
                mes = dt[1];
                ano = dt[2];
                tm = ( data[1] ? data[1].split(':') : '');
                hora = tm[0];
                minuto = tm[1];
                segundo = tm[2];
                var dataUpdate = new Date(ano, mes, dia,hora,minuto,segundo);
                dataUpdate.setDate(dataUpdate.getDate() + parseInt(prazosjs[dias_do_prazo]));
                dia = dataUpdate.getDate();
                mes = dataUpdate.getMonth();
                ano = dataUpdate.getYear()+1900;
                if(data_inicio)
                    jQuery( "#data_limite" ).attr('value',dia+'/'+mes+'/'+ano+' '+hora+':'+minuto+':'+segundo);
            }
            jQuery( "#data_inicio" ).change(function(){
                updateDataLimite(jQuery( "#data_inicio" ).val());
            });
            jQuery( "#tipo_de_prazo" ).change(function(){
                updateDataLimite(jQuery( "#data_inicio" ).val());
            });

</script>
<style>
    .choose-dropdown.cliente,.choose-dropdown.advogado_alocado{display:none !important;}
    #cliente, #advogado_alocado{clear:both;}
</style>
