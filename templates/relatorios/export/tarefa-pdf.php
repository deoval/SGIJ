<?php

if (file_exists('config.php')) {
    require_once( 'config.php' );
}
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
ob_start();
//print_r($_SESSION);
//if($_SESSION['user']['cargo'] != 'advogado_socio')die('Sem permissoes suficientes');
//die();
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


$html = '';
$html .= '<table border="1">';
$html .= '<tr>';
foreach (array_keys($campos_da_tabela) as $ct) {
    $html .= '<td class="titulo"><b>' . $ct . '</b></td>';
}
$html .= '</tr>';
foreach ($dados as $dado) {
    $html .= '<tr>';
    foreach ($campos_da_tabela as $ct) {
        $html .= '<td><b>' . $dado[$ct] . '</b></td>';
    }
    $html .= '</tr>';
}
$html .= '</table>';

include("mpdf/mpdf.php");

$mpdf = new mPDF('L');

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('mpdf/pdf.css');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html, 2);

$mpdf->Output('tarefa_pdf.pdf', 'D');
exit;
?>
