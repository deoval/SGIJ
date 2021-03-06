<script src="js/jquery.maskedinput.js" type="text/javascript"></script>
<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
if ($_POST && !empty($_POST)) {

    $pdo = new conectaPDO(); //INICIA CONEXÃO PDO
    $campos_da_tabela_existente = array(
        'ID' => 'id'
    );
    $tabela = array(TBL_USUARIO, TBL_ADVOGADO);
    $condition = " advogado_id_advogado = id and ( login = '" . Main::formatSlashes($_POST['login']) . "' 
or cpf = '" . Main::formatSlashes($_POST['cpf']) . "' 
or rg = '" . Main::formatSlashes($_POST['rg']) . "' 
or numero_oab = '" . Main::formatSlashes($_POST['numero_oab']) ."')
";
    $dados_usuario_existente = $pdo->getArrayData($campos_da_tabela_existente, $tabela, $condition);
    if (!empty($dados_usuario_existente)) {
        $erro = 1;
    }

    $campos = array(
        'id' => Main::formatSlashes($_POST['id']),
        'nome' => Main::formatSlashes($_POST['nome']),
        'login' => Main::formatSlashes($_POST['login']),
        'senha' => Main::formatSlashes(base64_encode($_POST['senha'])),
        'cargo' => Main::formatSlashes($_POST['cargo']),
        'telefone' => Main::formatSlashes($_POST['telefone']),
        'telefone_alternativo' => Main::formatSlashes($_POST['telefone_alternativo']),
        'telefone_celular' => Main::formatSlashes($_POST['telefone_celular']),
        'fax' => Main::formatSlashes($_POST['fax']),
        'observacao' => Main::formatSlashes($_POST['observacao']),
    );
    $tabela = TBL_USUARIO;
    if ($erro != 1)
        $success = $pdo->insertData($campos, $tabela);
    $campos = array(
        'id_advogado' => 'null',
        'cpf' => Main::formatSlashes($_POST['cpf']),
        'numero_oab' => Main::formatSlashes($_POST['numero_oab']),
        'rg' => Main::formatSlashes($_POST['rg']),
        'estado' => Main::formatSlashes($_POST['estado']),
        'cidade' => Main::formatSlashes($_POST['cidade']),
        'endereco' => Main::formatSlashes($_POST['endereco']),
        'bairro' => Main::formatSlashes($_POST['bairro']),
        'cep' => Main::formatSlashes($_POST['cep']),
        'numero' => Main::formatSlashes($_POST['numero']),
        'advogado_id_advogado' => $success
    );
    $tabela = TBL_ADVOGADO;
    if ($erro != 1)
        $pdo->insertData($campos, $tabela);

    $pdo->endConnection(); //FIM DA CONEXÃO

    if ($erro != 1) {
        print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . INSERT_SUCCESS . ' </strong></div>';
        Main::redirect('index.php?r=usuario/create',1);
    }
}
$campos_da_tabela = array(
    'ID' => 'id',
    'Nome' => 'nome',
    'Login' => 'login',
    'Senha' => 'senha',
    'Cargo' => 'cargo',
    'Telefone' => 'telefone',
    'Telefone Alternativo(opcional)' => 'telefone_alternativo',
    'Telefone Celular(opcional)' => 'telefone_celular',
    'Fax (opcional)' => 'fax',
    'Observação(opcional)' => 'observacao',
);
$campos_advogado = array(
    'RG' => 'rg',
    'CPF' => 'cpf',
    'OAB' => 'numero_oab',
    'Endereço' => 'endereco',
    'N°' => 'numero',
    'Bairro' => 'bairro',
    'CEP' => 'cep',
    'Cidade' => 'cidade',
    'Estado' => 'estado'
);

$dropdown = array('cargo' => array('advogado' => $campos_advogado, 'secretaria' => 'secretaria', 'advogado_socio' => $campos_advogado));
$campos_excluidos_form = array('id');
?>
<h1>Novo Usuario</h1>
<div id="error"><?php if ($erro == 1) print "Login, RG ou CPF existente "; ?></div>
<form id="form-update" class="form-update" method="POST">
    <input type='hidden' class='input-block-level' name='id' placeholder='null' value="null" />
    <?php
    foreach ($campos_da_tabela as $key => $campos) {
        if (in_array($campos, $campos_excluidos_form))
            continue;
        if (empty($dropdown[$campos])) {
$post = "";
$post = !empty($_POST[$campos])?$_POST[$campos]:'' ;
            print "<input type='text' class='input-block-level' id='$campos' name='$campos' title='" . $key . "' placeholder='" . $key . "' value='$post'><br />";
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
$post = "";
$post = !empty($_POST[$valorcampo_dropdown])?$_POST[$valorcampo_dropdown]:$dados[1][$valorcampo_dropdown];
                        $dados_caixa_campos .= "<input type='text' class='input-block-level' title='" . $key_subd . "' id='$valorcampo_dropdown' name='$valorcampo_dropdown' placeholder='" . $key_subd . "' value=\"" . $post . "\"><br />\n";
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
        jQuery("#cpf").mask("999.999.999-99");
        jQuery("#cep").mask("99999-999");
	jQuery("#rg").keyup(function() {
		var valor = jQuery("#rg").val().replace(/[a-zA-Z]+/g,'');
		jQuery("#rg").val(valor);
	});
        jQuery(".choose").change(function () {
            $(".choose-dropdown." + jQuery(this).attr('id') ).css('display','none');
            $("." + jQuery(this).val()).css('display','block');
            $(".choose-dropdown." + jQuery(this).attr('id') +" input" ).attr('disabled','disabled');
            $("." + jQuery(this).val() + " input").removeAttr('disabled');
            
            
        }).change();
        jQuery("#form-update").submit(function(){
            jQuery('#error').html("");
if(jQuery('#cargo').val() == "advogado" || jQuery('#cargo').val() == "advogado_socio"){
                            if(jQuery('#rg').val()=="" || jQuery('#cpf').val()=="" || jQuery('#numero_oab').val()==""){
                                jQuery('#error').append('Formulario imcompleto!<br />');
                                return false;
                            }
}
<?php
$campos_optionais = array('fax','telefone_celular','telefone_alternativo','observacao');
foreach ($campos_da_tabela as $campos) {
    if (in_array($campos, $campos_excluidos_form) || in_array($campos, $campos_optionais) )
        continue;
    ?>
                            if(jQuery('#<?php print $campos; ?>').val()==""){
                                jQuery('#error').append('Formulario imcompleto!<br />');
                                return false;
                            }
<?php } ?>
                });
            });
</script>
<style>
#nome{width:450px;}
#login{width:450px;}
#senha{width:400px;}
#rg{width:600px;}
#cpf{width:600px;}
#numero_oab{width:600px;}
#endereco{width:100%;}
#numero{width:100px;clear:both;}
#bairro{width:600px;}
#cep{width:100px;}
#cidade{width:300px;}
#estado{width:300px;}
#telefone,#telefone_celular{width:300px;}
#telefone_alternativo, #fax{width:300px;}
</style>
