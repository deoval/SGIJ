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
    'Login' => 'login',
    'Senha' => 'senha',
    'Cargo' => 'cargo',
    'Telefone' => 'numero_telefone',
);
$campos_excluidos_form = array('id');
$tabela = array(TBL_USUARIO, TBL_TELEFONES_USUARIO);
$condition = "id=id_telefone_advogado and id = " . $id;
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
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
    $campos = array(
        'login' => Main::formatSlashes($_POST['login']),
        'cargo' => Main::formatSlashes($_POST['cargo']),
        'senha' => ($_POST['senha'] != $dados[0]['senha'] ? Main::formatSlashes(base64_encode($_POST['senha'])) : ''),
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo)) {
            $campos_a_alterar[$key] = "'$campo'";
        }
    }
    $tabela = TBL_USUARIO;
    $condition = "id = $id";
    if (!empty($campos_a_alterar))
        $pdo->updateData($campos_a_alterar, $condition, $tabela);
    $campos = array(
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
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo))
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = TBL_ADVOGADO;
    $condition = "advogado_id_advogado = $id";

    $pdo->updateData($campos_a_alterar, $condition, $tabela);
    $campos = array(
        'numero_telefone' => Main::formatSlashes($_POST['numero_telefone']),
    );
    $campos_a_alterar = array();
    foreach ($campos as $key => $campo) {
        if (!empty($campo))
            $campos_a_alterar[$key] = "'$campo'";
    }
    $tabela = TBL_TELEFONES_USUARIO;
    $condition = "id_telefone_advogado = $id";
    if (!empty($campos_a_alterar))
        $pdo->updateData($campos_a_alterar, $condition, $tabela);
    $pdo->endConnection(); //FIM DA CONEXÃO
    print '<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a><strong> ' . UPDATE_SUCCESS . ' </strong></div>';
    Main::redirect('index.php?r=usuario/' . UPDATE_FILENAME . '&id=' . $id, 1);
}
?>
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

    <input type="submit" id="update-btn" class="btn btn-large btn-primary" value="<?php print UPDATE; ?>"></input>
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
    });
</script>
