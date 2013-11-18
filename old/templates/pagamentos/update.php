<script src="js/jquery-ui-timepicker-addon.js"></script>
<script src="js/jquery.price_format.js" type="text/javascript"></script>
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

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID do Pagamento' => id_pagamento,
    'Plano de pagamento' => plano_pagamento,
    'Valor' => valor,
    'Parcelas pagas' => parcelas_pagas,
    'Forma de pagamento' => forma_pagamento,
    'Status' => status_pagamento,
    'Vencimento' => vencimento,
    'Tempo de Atraso' => tempo_atraso,
    'Processo' => processos_id_processo
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
$dropdown = array('status_pagamento'=>$dstatus_pagamento,'forma_pagamento' => $dforma_pagamento);

$tabela = array(TBL_PAGAMENTOS);
$condition = "id_pagamento = $id";
$pagamentos = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$is_data = array('vencimento');
$campos_excluidos_form = array('id_pagamento', 'processos_id_processo');


if ($_POST && !empty($_POST)) {
    $campos = array(
        tempo_atraso => Main::formatSlashes($_POST['tempo_atraso']),
        plano_pagamento => Main::formatSlashes($_POST['plano_pagamento']),
        valor => Main::formatSlashes($_POST['valor']),
        parcelas_pagas => Main::formatSlashes($_POST['parcelas_pagas']),
        forma_pagamento => Main::formatSlashes($_POST['forma_pagamento']),
        status_pagamento => Main::formatSlashes($_POST['status_pagamento']),
        vencimento => Main::formatSlashes(date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $_POST['vencimento'])))),
        processos_id_processo => Main::formatSlashes($_POST['processos_id_processo']),
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo))
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = TBL_PAGAMENTOS;
    $condition = "id_pagamento = $id";
    if (!empty($campos_a_alterar))
        $pdo->updateData($campos_a_alterar, $condition, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . UPDATE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=pagamentos/' . UPDATE_FILENAME . '&id=' . $id, 1);
}


$pdo->endConnection(); //FIM DA CONEXÃO
?>
<div style="margin:0px auto;height:30px">Os dados do processo devem ser atualizados no processo <a href=index.php?r=processo/update&id=<?php print $pagamentos[0]['processos_id_processo']; ?>>Atualizar Processo</a></div>
<form id="form-update" class="form-update" method="POST">
    <?php
    foreach ($campos_da_tabela as $key => $campo) {
        if (in_array($campo, $campos_excluidos_form))
            continue; //pulo os campos excluidos
if (empty($dropdown[$campo])) {
        $value = in_array($campo, $is_data) ? date_format(date_create($pagamentos[0][$campo]), "d/m/Y H:i:s") : $pagamentos[0][$campo];
        print "<input type='text' class='input-block-level' title='$key' id='$campo' name='$campo' placeholder='" . $key . "' value=\"" . $value . "\">\n";
}else{
 $dados_campo_select = "";
            $dados_caixa_campos = "";

            $dados_campo_select = "<br /><label>" . $key . "</label><select name='$campo' class='choose chosen-select' id='$campo'>";
            foreach ($dropdown[$campo] as $key_d => $campo_dropdown) {
                $selected = $campo_dropdown == $_SESSION['user']['id'] ? 'selected' : '';
                $dados_campo_select .= "<option value='$campo_dropdown' $selected>" . $key_d . "</option>\n";
            }
            $dados_campo_select .= "</select>\n";
            print $dados_campo_select;
}
    }
    ?>
    <input type="submit" id="update-btn" class="btn btn-large btn-primary" value="<?php print UPDATE; ?>"></input>
</form>
<script>
    jQuery(document).ready(function(){
        $('#valor').priceFormat({
                    prefix: '',
                    thousandsSeparator: ''
                });
	});
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
        weekHeader: 'Неader',
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
