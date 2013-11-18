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

/*
  busca os dados do cliente para update
 */
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID' => 'id_tarefa',
    'Data' => 'data_e_hora',
    'Tarefa' => 'tarefa'
);
$campos_excluidos_form = array('id_tarefa');
$tabela = array(TBL_TAREFAS);
$condition = "id_tarefa = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$is_data = array('data_e_hora');
$pdo->endConnection(); //FIM DA CONEXÃO
/*
  busca os dados do cliente para update
 */

if ($_POST && !empty($_POST)) {
    $pdo = new conectaPDO(); //INICIA CONEXÃO PDO

    $campos = array(
        'data_e_hora' => Main::formatSlashes(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['data_e_hora'])))),
        'tarefa' => Main::formatSlashes($_POST['tarefa']),
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo))
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = TBL_TAREFAS;
    $condition = "id_tarefa = $id";
    if (!empty($campos_a_alterar))
        $pdo->updateData($campos_a_alterar, $condition, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . UPDATE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=tarefa/' . UPDATE_FILENAME . '&id=' . $id, 1);
}
?>
<form id="form-update" class="form-update" method="POST">
    <?php
    foreach ($campos_da_tabela as $key => $campos) {
        if (in_array($campos, $campos_excluidos_form))
            continue; //pulo os campos excluidos
        $value = in_array($campos, $is_data) ? date_format(date_create($dados[0][$campos]), "d/m/Y H:i:s") : $dados[0][$campos];
        print "<input type='text' class='input-block-level' id='$campos' name='$campos' id='$campos' title='" . $key . "' placeholder='" . $key . "' value=\"" . $value . "\">";
    }
    ?>

    <input type="submit" id="update-btn" class="btn btn-large btn-primary" value="<?php print UPDATE; ?>"></input>
</form>
<span id=count></span>
<script>
    jQuery(document).ready(function(){
        jQuery(".choose").change(function () {
            $(".choose-dropdown." + jQuery(this).attr('id') ).css('display','none');
            $("." + jQuery(this).val()).css('display','block');
            
        }).change();
jQuery("#tarefa").attr('maxlength','3000');
jQuery("#tarefa").keyup(function(){
  jQuery("#count").text("Caracteres Restantes : " + (3000 - $(this).val().length));
});
jQuery("#tarefa").focusout(function(){
  jQuery("#count").text("");
});
    });
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

    
</script>
<style>
#data_e_hora{width:450px;}
</style>
