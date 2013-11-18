<script src="js/jquery.maskedinput.js" type="text/javascript"></script>
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
    'ID do Cliente' => 'id_cliente',
    'Nome' => 'nome',
    'E-mail' => 'email',
    'Tipo de Cliente' => 'tipo_cliente',
    'Tipo de Pessoa' => 'tipo_pessoa',
    'Endereço' => 'endereco',
    'Numero' => 'numero',
    'Bairro' => 'bairro',
    'CEP' => 'cep',
    'Cidade' => 'cidade',
    'Estado' => 'estado',
    'Telefone' => 'telefone'
);
$campos_excluidos_form = array('id_cliente');
$tabela = array(TBL_CLIENTE);
$condition = " id_cliente = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$dropdown = array('tipo_pessoa' => array(
        'pessoa_fisica' => array(
            'RG' => 'rg',
            'CPF' => 'cpf'
        ),
        'pessoa_juridica' => array(
            'Inscr. Estadual' => 'inscricao_estadual',
            'Inscr.Municipal' => 'inscricao_municipal',
            'CNPJ' => 'cnpj'
        ),
    ),
    'tipo_cliente' => array('Mensalista' => 'Mensalista', 'Varejista' => 'Varejista')
);
$condition = "id_codigo_cliente_" . str_replace('pessoa_', '', $dados[0]['tipo_pessoa']) . "=id_cliente and id_cliente = " . $id;
$dados_pessoa[$dados[0]['tipo_pessoa']] = $pdo->getArrayData($dropdown['tipo_pessoa'][$dados[0]['tipo_pessoa']], array($dados[0]['tipo_pessoa'], TBL_CLIENTE), $condition);
$dados = array_merge($dados, $dados_pessoa);

$pdo->endConnection(); //FIM DA CONEXÃO
/*
  busca os dados do cliente para update
 */

if ($_POST && !empty($_POST)) {
    $pdo = new conectaPDO(); //INICIA CONEXÃO PDO

    $campos = array(
        'nome' => Main::formatSlashes($_POST['nome']),
        'email' => Main::formatSlashes($_POST['email']),
        'tipo_cliente' => Main::formatSlashes($_POST['tipo_cliente']),
        'tipo_pessoa' => Main::formatSlashes($_POST['tipo_pessoa']),
        'estado' => Main::formatSlashes($_POST['estado']),
        'cidade' => Main::formatSlashes($_POST['cidade']),
        'endereco' => Main::formatSlashes($_POST['endereco']),
        'bairro' => Main::formatSlashes($_POST['bairro']),
        'cep' => Main::formatSlashes($_POST['cep']),
        'numero' => Main::formatSlashes($_POST['numero']),
        'telefone' => Main::formatSlashes($_POST['telefone']),
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo))
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = TBL_CLIENTE;
    $condition = "id_cliente = $id";
  
    if (!empty($campos_a_alterar))
        $pdo->updateData($campos_a_alterar, $condition, $tabela);

    $dropdown_update = array('tipo_pessoa' => array(
            'pessoa_fisica' => array(
                'rg' => Main::formatSlashes($_POST['rg']),
                'cpf' => Main::formatSlashes($_POST['cpf'])
            ),
            'pessoa_juridica' => array(
                'inscricao_estadual' => $_POST['inscricao_estadual'],
                'inscricao_municipal' => $_POST['inscricao_municipal'],
                'cnpj' => $_POST['cnpj']
            ),
        )
    );
//update se erro = 0 faz insert
    $campos_a_alterar = array();
    foreach ($dropdown_update['tipo_pessoa'][$_POST['tipo_pessoa']] as $key => $campo) {
        if (!empty($campo))
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = $_POST['tipo_pessoa'];
    $condition = " id_codigo_cliente_" . str_replace('pessoa_', '', $_POST['tipo_pessoa']) . " = " . $id;
    if (!empty($campos_a_alterar))
        $id_update = $pdo->updateData($campos_a_alterar, $condition, $tabela);
 
    if ($id_update == 0 && empty($dados[$_POST['tipo_pessoa']][0])) {
        foreach ($dropdown_update['tipo_pessoa'][$_POST['tipo_pessoa']] as $key => $campo) {
            if (!empty($campo))
                $campos_a_alterar[$key] = "$campo";
        }
        $campos_a_alterar["id_codigo_cliente_" . str_replace('pessoa_', '', $_POST['tipo_pessoa'])] = $id;
        $campos_a_alterar["id_" . $_POST['tipo_pessoa']] = "null";
        $pdo->insertData($campos_a_alterar, $tabela);
    }
    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . UPDATE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=cliente/' . UPDATE_FILENAME . '&id=' . $id, 1);
}
?>
<div id="error"></div>
<form id="form-update" class="form-update" method="POST">
<?php
foreach ($campos_da_tabela as $key => $campos) {
    if (in_array($campos, $campos_excluidos_form))
        continue; //pulo os campos excluidos
    if (empty($dropdown[$campos])) {
        print "<input type='text' class='input-block-level' id='$campos' name='$campos' title='" . $key . "' placeholder='" . $key . "' value=\"" . $dados[0][$campos] . "\">";
    } else {

        $dados_campo_select = "";
        $dados_caixa_campos = "";

        $dados_campo_select = "<select name='$campos' class='choose' id='$campos' >";
        foreach ($dropdown[$campos] as $key_d => $campo_dropdown) {
            $dados_campo_select .= "<option value='$key_d' " . ($dados[0][$campos] == $key_d ? " selected" : "") . ">" . str_replace('_', ' ', $key_d) . "</option>\n";
        }
        $dados_campo_select .= "</select>\n";

        foreach ($dropdown[$campos] as $key_d => $campo_dropdown) {
            $dados_caixa_campos .= "<div class='choose-dropdown $campos $key_d'>\n";
            if (is_array($campo_dropdown)) {
                foreach ($campo_dropdown as $key_subd => $valorcampo_dropdown) {
                    if (in_array($valorcampo_dropdown, $campos_excluidos_form))
                        continue;
                    $dados_caixa_campos .= "<input type='text' class='input-block-level' title='" . $key_subd . "' id='$valorcampo_dropdown' name='$valorcampo_dropdown' placeholder='" . $key_subd . "' value=\"" . $dados[$key_d][0][$valorcampo_dropdown] . "\">\n";
                }
            }
            $dados_caixa_campos .= "</div>\n";
        }
        print $dados_campo_select;
        print $dados_caixa_campos;
    }
}
?>

    <input type="submit" id="update-btn" class="btn btn-large btn-primary" value="<?php print UPDATE; ?>"></input>
</form>
<script>
    jQuery(document).ready(function(){
	jQuery("#inscricao_estadual").keyup(function() {
		var valor = jQuery("#inscricao_estadual").val().replace(/[a-zA-Z]+/g,'');
		jQuery("#inscricao_estadual").val(valor);
	});
	jQuery("#inscricao_municipal").keyup(function() {
		var valor = jQuery("#inscricao_municipal").val().replace(/[a-zA-Z]+/g,'');
		jQuery("#inscricao_municipal").val(valor);
	});
        jQuery("#form-update").submit(function(){
            jQuery('#error').html("");
function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
if(!IsEmail(jQuery('#email').val())){
jQuery('#error').append(' Email invalido!<br />');
                    return false;
}
});
        jQuery("#cpf").mask("999.999.999-99");
        jQuery("#cnpj").mask("99.999.999/9999-99");
        jQuery("#cep").mask("99999-999");
        jQuery(".choose").change(function () {
            $(".choose-dropdown." + jQuery(this).attr('id') ).css('display','none');
            $("." + jQuery(this).val()).css('display','block');
            
        }).change();
    });
</script>
