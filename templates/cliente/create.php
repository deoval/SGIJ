<script src="js/jquery.maskedinput.js" type="text/javascript"></script>
<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
if ($_POST && !empty($_POST)) {
    $pdo = new conectaPDO(); //INICIA CONEXÃO PDO
    $campos = array(
        'id_cliente' => Main::formatSlashes($_POST['id_cliente']),
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
        'telefone' => Main::formatSlashes($_POST['numero_telefone']),
	    'telefone_alternativo' => Main::formatSlashes($_POST['telefone_alternativo']),
	    'telefone_celular' => Main::formatSlashes($_POST['telefone_celular']),
	    'fax' => Main::formatSlashes($_POST['fax']),
	    'observacao' => Main::formatSlashes($_POST['observacao']),
    );
    $tabela = TBL_CLIENTE;

    //insere os dados na tabela cliente
    $success = $pdo->insertData($campos, $tabela);

    $campos = array('tipo_pessoa' => array(
            'pessoa_fisica' => array(
                'rg' => Main::formatSlashes($_POST['rg']),
                'cpf' => Main::formatSlashes($_POST['cpf'])
            ),
            'pessoa_juridica' => array(
                'inscricao_estadual' => Main::formatSlashes($_POST['inscricao_estadual']),
                'inscricao_municipal' => Main::formatSlashes($_POST['inscricao_municipal']),
                'cnpj' => Main::formatSlashes($_POST['cnpj'])
            ),
        )
    );
    $campos_da_tabela = $campos['tipo_pessoa'][$_POST['tipo_pessoa']];
    $campos_da_tabela["id_codigo_cliente_" . str_replace('pessoa_', '', $_POST['tipo_pessoa'])] = $success;
    $campos_da_tabela["id_" . $_POST['tipo_pessoa']] = "null";
    $tabela = $_POST['tipo_pessoa'];
    $pdo->insertData($campos_da_tabela, $tabela);


    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . INSERT_SUCCESS . ' </strong></div>';
    //Main::redirect('index.php?r=cliente/' . UPDATE_FILENAME . '&id=' . $success, 2);
    Main::redirect('index.php?r=cliente/create',1);
}
$campos_da_tabela = array(
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
    'Telefone' => 'numero_telefone',
    'Telefone Alternativo(opcional)' => 'telefone_alternativo',
    'Telefone Celular(opcional)' => 'telefone_celular',
    'Fax (opcional)' => 'fax',
    'Observação(opcional)' => 'observacao',
);
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
?>
<h1>Novo Cliente</h1>
<div id="error"></div>
<form id="form-update" class="form-update" method="POST">
    <input type='hidden' class='input-block-level' name='id_cliente' placeholder='null' value="null" />
    <?php
    foreach ($campos_da_tabela as $key => $campos) {
        if (empty($dropdown[$campos])) {
$post = "";
$post = !empty($_POST[$campos])?$_POST[$campos]:'' ;
            print "<input type='text' class='input-block-level' id='$campos' name='$campos' title='" . $key . "' placeholder='" . $key . "' value='$post'><br />";
        } else {

            $dados_campo_select = "";
            $dados_caixa_campos = "";

            $dados_campo_select = "<select name='$campos' class='choose' id='$campos'>";
            foreach ($dropdown[$campos] as $key_d => $campo_dropdown) {
                $dados_campo_select .= "<option value='$key_d'>" . str_replace('_', ' ', $key_d) . "</option>\n";
            }
            $dados_campo_select .= "</select>\n";

            foreach ($dropdown[$campos] as $key_d => $campo_dropdown) {
                $dados_caixa_campos .= "<div class='choose-dropdown $campos $key_d'>\n";
                if (is_array($campo_dropdown)) {
                    foreach ($campo_dropdown as $key_subd => $valorcampo_dropdown) {
                        $dados_caixa_campos .= "<input type='text' class='input-block-level' id='$valorcampo_dropdown' name='$valorcampo_dropdown' placeholder='" . $key_subd . "' title='" . $key_subd . "'>\n";
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
	jQuery("#inscricao_estadual").keyup(function() {
		var valor = jQuery("#inscricao_estadual").val().replace(/[a-zA-Z]+/g,'');
		jQuery("#inscricao_estadual").val(valor);
	});
	jQuery("#inscricao_municipal").keyup(function() {
		var valor = jQuery("#inscricao_municipal").val().replace(/[a-zA-Z]+/g,'');
		jQuery("#inscricao_municipal").val(valor);
	});
	jQuery("#rg").keyup(function() {
		var valor = jQuery("#rg").val().replace(/[a-zA-Z]+/g,'');
		jQuery("#rg").val(valor);
	});
        jQuery("#cpf").mask("999.999.999-99");
        jQuery("#cnpj").mask("99.999.999/9999-99");
        jQuery("#cep").mask("99999-999");
        jQuery(".choose").change(function () {
            $(".choose-dropdown." + jQuery(this).attr('id') ).css('display','none');
            $("." + jQuery(this).val()).css('display','block');
            
        }).change();
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
<?php foreach ($campos_da_tabela as $campos) {
if(in_array($campos,array('telefone_alternativo','telefone_celular','fax','observacao')))
continue;
    ?>
                if(jQuery('#<?php print $campos; ?>').val()==""){
                    jQuery('#error').append(' Formulario incompleto!<br />');
                    return false;
                }
<?php } ?>
                });
            });
</script>
<style>
#nome{width:450px;}
#email{width:450px;}
#rg{width:600px;}
#cpf{width:600px;}
#numero_oab{width:600px;}
#endereco{width:100%;}
#numero{width:100px;clear:both;}
#bairro{width:600px;}
#cep{width:100px;}
#cidade{width:300px;}
#estado{width:300px;}
#numero_telefone,#telefone_celular{width:300px;}
#telefone_alternativo, #fax{width:300px;}
</style>
