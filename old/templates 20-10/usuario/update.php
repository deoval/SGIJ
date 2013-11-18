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
    'ID' => 'id',
    'Nome' => 'nome',
    'Login' => 'login',
    'Senha' => 'senha',
    'Cargo' => 'cargo',
    'Telefone' => 'telefone',
);
$campos_excluidos_form = array('id');
$tabela = array(TBL_USUARIO);
$condition = " id = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$campos_advogado = array(
    'RG' => 'rg',
    'CPF' => 'cpf',
    'OAB' => 'numero_oab',
    'Endereço' => 'endereco',
    'N°' => 'numero',
    'Bairro' => 'bairro',
    'CEP' => 'cep',
    'Cidade' => 'cidade',
    'Estado' => 'estado',
);
$condition = "advogado_id_advogado = " . $id;
$tabela = array(TBL_ADVOGADO);
$dados_advogado = $pdo->getArrayData($campos_advogado, $tabela, $condition);
$dropdown = array('cargo' => array('advogado' => $campos_advogado, 'secretaria' => 'secretaria', 'advogado_socio' => $campos_advogado));
$dados = array_merge($dados, $dados_advogado);
$pdo->endConnection(); //FIM DA CONEXÃO
/*
  busca os dados do usuario para update
 */

if ($_POST && !empty($_POST)) {
    $pdo = new conectaPDO(); //INICIA CONEXÃO PDO

   $campos_da_tabela_existente = array(
        'ID' => 'id'
    );
    $tabela = array(TBL_USUARIO, TBL_ADVOGADO);
    $condition = " id <> $id and advogado_id_advogado = id and ( login = '" . Main::formatSlashes($_POST['login']) . "' 
or cpf = '" . Main::formatSlashes($_POST['cpf']) . "' 
or rg = '" . Main::formatSlashes($_POST['rg']) . "' 
or numero_oab = '" . Main::formatSlashes($_POST['numero_oab']) ."')
";
    $dados_usuario_existente = $pdo->getArrayData($campos_da_tabela_existente, $tabela, $condition);
    if (!empty($dados_usuario_existente)) {
        $erro = 1;
    }

    $campos = array(
        'login' => Main::formatSlashes($_POST['login']),
        'cargo' => Main::formatSlashes($_POST['cargo']),
        'nome' => Main::formatSlashes($_POST['nome']),
        'telefone' => Main::formatSlashes($_POST['telefone']),
        'senha' => ($_POST['senha'] != $dados[0]['senha'] ? Main::formatSlashes(base64_encode($_POST['senha'])) : ''),
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo) && $erro != 1) {
            $campos_a_alterar[$key] = "'$campo'";
        }
    }
    $tabela = TBL_USUARIO;
    $condition = "id = $id";
    if (!empty($campos_a_alterar))
        $pdo->updateData($campos_a_alterar, $condition, $tabela);
    $campos = array(
        'cpf' => Main::formatSlashes($_POST['cpf']),
        'numero_oab' => Main::formatSlashes($_POST['numero_oab']),
        'rg' => Main::formatSlashes($_POST['rg']),
        'estado' => Main::formatSlashes($_POST['estado']),
        'cidade' => Main::formatSlashes($_POST['cidade']),
        'endereco' => Main::formatSlashes($_POST['endereco']),
        'bairro' => Main::formatSlashes($_POST['bairro']),
        'cep' => Main::formatSlashes($_POST['cep']),
        'numero' => Main::formatSlashes($_POST['numero']),
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo) && $erro != 1)
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = TBL_ADVOGADO;
    $condition = "advogado_id_advogado = $id";

    $pdo->updateData($campos_a_alterar, $condition, $tabela);

    if (!empty($campos_a_alterar))
        $pdo->updateData($campos_a_alterar, $condition, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    if ($erro != 1) {
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . UPDATE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=usuario/' . UPDATE_FILENAME . '&id=' . $id, 1);
}
}
?>
<div id="error"><?php if ($erro == 1) print "Login, RG ou CPF existente "; ?></div>
<form id="form-update" class="form-update" method="POST">
    <?php
    foreach ($campos_da_tabela as $key => $campos) {
        if (in_array($campos, $campos_excluidos_form))
            continue; //pulo os campos excluidos
        if (empty($dropdown[$campos])) {
            print "<input type='text' class='input-block-level' id='$campos' name='$campos' title='" . $key . "' placeholder='" . $key . "' value=\"" . $dados[0][$campos] . "\"><br clear=all />";
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
                        $dados_caixa_campos .= "<input type='text' class='input-block-level' title='" . $key_subd . "' id='$valorcampo_dropdown' name='$valorcampo_dropdown' placeholder='" . $key_subd . "' value=\"" . $dados[1][$valorcampo_dropdown] . "\"><br />\n";
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
#telefone{width:300px;}
</style>
