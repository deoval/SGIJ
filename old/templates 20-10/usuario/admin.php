<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
function mascara_string($mascara,$string)
{
   $string = str_replace(" ","",$string);
   for($i=0;$i<strlen($string);$i++)
   {
      $mascara[strpos($mascara,"#")] = $string[$i];
   }
   return $mascara;
}


$pages = (empty($_GET['page'])) ? 0 : ($_GET['page'] - 1);
$per_page = 10;
$r = $_GET['r'];
$campo_procurado = trim(strip_tags($_GET['c']));
$termo_de_busca = trim(strip_tags($_GET['t']));

$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID' => 'id',
    'Nome' => 'nome',
    'Login' => 'login',
    'CPF' => 'cpf',
   'OAB' => 'numero_oab',
    'Cargo' => 'cargo');
$tabela = array(TBL_USUARIO, TBL_ADVOGADO);
$condition = " (id = advogado_id_advogado or cargo='secretaria')  ";
if (!empty($campo_procurado) && !empty($termo_de_busca)) {
if($campo_procurado == 'cpf'){
$termo_de_busca = str_replace(".","",$termo_de_busca);
$termo_de_busca = str_replace("-","",$termo_de_busca);
$termo_de_busca = mascara_string("###.###.###-##",$termo_de_busca);
}
    $condition .= " and $campo_procurado LIKE '%$termo_de_busca%'";
if(in_array($campo_procurado,array('numero_oab','cpf'))){
$condition .= " and cargo <> 'secretaria'";
}
}
$condition .= " group by id ";

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
?>
<table class="table">
    <tr>
        <?php 
$campos_busca = $campos_da_tabela;
print Main::getForm($_SERVER["REQUEST_URI"], $_GET['r'], $campos_busca, array('c' => $campo_procurado, 't' => $termo_de_busca)); ?>
    </tr>
    <tr>
        <?php
        foreach (array_keys($campos_da_tabela) as $campos) {
            print "<th>$campos</th>";
        }
        ?>
        <th>Acao</th>
    </tr>
    <?php
    $total = count($dados);
    $dados = array_slice($dados, $pages * $per_page, $per_page, true);
    $folder = explode("/", $_GET['r']);
    foreach ($dados as $key => $dado) {
        $id_tb = $dado['id'];
        print "<tr>";
        foreach ($campos_da_tabela as $campos) {
	    $value = ($dado['cargo'] =='secretaria' && in_array($campos, array('cpf','numero_oab')))?"":str_replace("_", " ", $dado[$campos]);
            print "<td>" . $value . "</td>";
        }
        print "<td>";
        print Main::getAdminLinks($r, $id_tb);
        print "</td>\n";
        print "</tr>\n";
    }
    if (empty($dados)) {
        print "<td style='text-align:center' colspan=" . (count($campos_da_tabela) + 1) . ">" . TABLE_EMPTY . "</td>";
    }
    ?>
</table>
<?php
if (!empty($campo_procurado) && !empty($termo_de_busca)) {
    $link_adicional = "&c=$campo_procurado&t=$termo_de_busca";
}
if ($total > $per_page)
    $links = Main::getPagination($total, $per_page, $link_adicional, $_GET['r']);
?>
<div class="pagination">
    <ul>
        <?php print $links; ?>
    </ul>
</div>
