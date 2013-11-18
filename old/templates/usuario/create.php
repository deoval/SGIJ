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
    $tabela = array(TBL_USUARIO);
    $condition = " login = '" . Main::formatSlashes($_POST['login']) . "'";
    $dados_usuario_existente = $pdo->getArrayData($campos_da_tabela_existente, $tabela, $condition);
    if (!empty($dados_usuario_existente)) {
        $erro = 1;
    }

    $campos = array(
        'id' => Main::formatSlashes($_POST['id']),
        'login' => Main::formatSlashes($_POST['login']),
        'senha' => Main::formatSlashes(base64_encode($_POST['senha'])),
        'cargo' => Main::formatSlashes($_POST['cargo'])
    );
    $tabela = TBL_USUARIO;
    if ($erro != 1)
        $success = $pdo->insertData($campos, $tabela);
    $campos = array(
        'id_advogado' => 'null',
        'nome' => Main::formatSlashes($_POST['nome']),
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
    $campos = array(
        'id_telefone' => 'null',
        'numero_telefone' => Main::formatSlashes($_POST['numero_telefone']),
        'id_telefone_advogado' => $success
    );
    $tabela = TBL_TELEFONES_USUARIO;
//insere os dados na tabela de telefones dos clientes
    if ($erro != 1)
        $pdo->insertData($campos, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    if ($erro != 1) {
        print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . INSERT_SUCCESS . ' </strong></div>';
        // Main::redirect('index.php?r=usuario/' . UPDATE_FILENAME . '&id=' . $success,2);
        Main::redirect('index.php?r=usuario/create',1);
    }
}
$campos_da_tabela = array(
    'ID' => 'id',
    'Login' => 'login',
    'Cargo' => 'cargo',
    'Telefone' => 'numero_telefone',
);
$campos_advogado = array(
    'RG' => 'rg',
    'CPF' => 'cpf',
    'OAB' => 'numero_oab',
    'Nome' => 'nome',
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
<div id="error"><?php if ($erro == 1) print "Ocorreu um erro ao tentar salvar os dados, verifique se já existe o login"; ?></div>
<form id="form-update" class="form-update" method="POST">
    <input type='hidden' class='input-block-level' name='id' placeholder='null' value="null" />
    <?php
    foreach ($campos_da_tabela as $key => $campos) {
        if (in_array($campos, $campos_excluidos_form))
            continue;
        if (empty($dropdown[$campos])) {
            print "<input type='text' class='input-block-level' id='$campos' name='$campos' title='" . $key . "' placeholder='" . $key . "'>";
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
                        $dados_caixa_campos .= "<input type='text' class='input-block-level' title='" . $key_subd . "' id='$valorcampo_dropdown' name='$valorcampo_dropdown' placeholder='" . $key_subd . "' value=\"" . $dados[1][$valorcampo_dropdown] . "\">\n";
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
        jQuery(".choose").change(function () {
            $(".choose-dropdown." + jQuery(this).attr('id') ).css('display','none');
            $("." + jQuery(this).val()).css('display','block');
            $(".choose-dropdown." + jQuery(this).attr('id') +" input" ).attr('disabled','disabled');
            $("." + jQuery(this).val() + " input").removeAttr('disabled');
            
            
        }).change();
        jQuery("#form-update").submit(function(){
            jQuery('#error').html("");
<?php
foreach ($campos_da_tabela as $campos) {
    if (in_array($campos, $campos_excluidos_form))
        continue;
    ?>
                            if(jQuery('#<?php print $campos; ?>').val()==""){
                                jQuery('#error').append('O campo ' + jQuery('#<?php print $campos; ?>').attr('placeholder') + ' não pode estar vazio!<br />');
                                return false;
                            }
<?php } ?>
                });
            });
</script>
