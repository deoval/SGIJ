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
if (is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    exit();
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
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'Tipo de Prazo' => tipo_de_prazo,
    'ID do Prazo' => id_prazo,
    'Data de Inicio' => data_inicio,
    'Data Limite' => data_limite,
    'Numero do Processo' => id_num_processo,
);
$campos_excluidos_form = array('id_prazo', 'id_num_processo');
$dropdown = array('tipo_de_prazo' => $dtipo_de_prazo);
$tabela = array(TBL_PRAZOS);
$condition = "id_prazo = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$is_data = array('data_limite', 'data_inicio');
$pdo->endConnection(); //FIM DA CONEXÃO
/*
  busca os dados do cliente para update
 */

if ($_POST && !empty($_POST)) {
    $pdo = new conectaPDO(); //INICIA CONEXÃO PDO

    $campos = array(
        'data_inicio' => Main::formatSlashes(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['data_inicio'])))),
        'data_limite' => Main::formatSlashes(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['data_limite'])))),
        'tipo_de_prazo' => Main::formatSlashes($_POST['tipo_de_prazo']),
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo))
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = TBL_PRAZOS;
    $condition = "id_prazo = $id";
    if (!empty($campos_a_alterar))
        $pdo->updateData($campos_a_alterar, $condition, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . UPDATE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=prazo/' . UPDATE_FILENAME . '&id=' . $id, 1);
}
?>

<div style="margin:0px auto;height:30px">Os dados do processo devem ser atualizados no processo <a href=index.php?r=processo/update&id=<?php print $dados[0]['id_num_processo']; ?>>Atualizar Processo</a></div>
<form id="form-update" class="form-update" method="POST">
    <?php
    foreach ($campos_da_tabela as $key => $campos) {
        if (in_array($campos, $campos_excluidos_form))
            continue; //pulo os campos excluidos
        if (empty($dropdown[$campos])) {
            $value = in_array($campos, $is_data) ? date_format(date_create($dados[0][$campos]), "d/m/Y H:i:s") : $dados[0][$campos];
            print "<input type='text' class='input-block-level' id='$campos' name='$campos' title='" . $key . "' placeholder='" . $key . "' value=\"" . $value . "\"><br />";
        } else {
            $dados_campo_select = "";
            $dados_campo_select = "<br /><label>Tipo de Prazo</label><select name='tipo_de_prazo'>";
            foreach ($dtipo_de_prazo as $key_d => $campo_dropdown) {
                $selected = ($campo_dropdown == $dados[0]['tipo_de_prazo']) ? 'selected' : '';
                $dados_campo_select .= "<option value='$campo_dropdown' $selected>" . $key_d . "</option>\n";
            }
            $dados_campo_select .= "</select><br />";
            print $dados_campo_select;
        }
    }
    ?>
    <input type="submit" id="update-btn" class="btn btn-large btn-primary" value="<?php print UPDATE; ?>"></input>
</form>
<script>
    jQuery(document).ready(function(){
        jQuery(".choose").change(function () {
            $(".choose-dropdown." + jQuery(this).attr('id') ).css('display','none');
            $("." + jQuery(this).val()).css('display','block');
            
        }).change();
    });
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
        showButtonPanel:  false
    });
</script>
<style>
#data_inicio,#data_limite{width:450px;}
</style>
