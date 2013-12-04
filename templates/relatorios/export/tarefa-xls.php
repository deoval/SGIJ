<?php

if (file_exists('config.php')) {
    require_once( 'config.php' );
}
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$dt_i = $_GET['dt_i'];
$dt_f = $_GET['dt_f'];
$adv = $_GET['adv'];
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO

$campos_da_tabela = array(
    'ID' => 'id_tarefa',
    'Data e Hora' => 'data_e_hora',
    'Tarefa' => 'tarefa',
    'Nome' => 'nome'
);

$tabela = array(TBL_TAREFAS, TBL_USUARIO);

$condition = " advogado_id_advogado = id";
if ($adv !=0){
    $condition .= "  and id=" . $adv;
}

if (!empty($dt_i) && !empty($dt_f)) {
    $condition .= " and data_e_hora between '". $dt_i ."' and '". $dt_f ."'";
}

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO

$arquivo = 'tarefa_xls.xls';

$html = '';
$html .= '<table width=1000px>';
$html .= '<tr>';
foreach (array_keys($campos_da_tabela) as $ct) {

    $html .= '<td bgcolor="#000000" border="0px"><font color="#777777"><b>' . mb_convert_encoding($ct, 'UTF-16LE', 'UTF-8') . '</b></font></td>';
}
$html .= '</tr>';
foreach ($dados as $dado) {
    $html .= '<tr>';
    foreach ($campos_da_tabela as $ct) {
        $html .= '<td cellpadding="3px" cellspacing="3px" border="1px" bordercolor="#666666"><b>' . mb_convert_encoding($dado[$ct], 'UTF-16LE', 'UTF-8') . '</b></td>';
    }
    $html .= '</tr>';
}
$html .= '</tr>';
$html .= '</table>';

// Configurações header para forçar o download
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
header("Content-Description: PHP Generated Data");

// Envia o conteúdo do arquivo

echo $html;
exit();
?>
