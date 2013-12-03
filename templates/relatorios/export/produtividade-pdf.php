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
if (!empty($_GET['m'])) {
    $mes = $_GET['m'];
}
else{
    $_GET['m'] = date("m");
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO


$campos_da_tabela = array('Nome' =>'nome', 'Número de Processos'=> 'count(advogado_alocado)', 'Mês'=>$_GET['m'], 'Ano'=> date("Y"), 'Valor Total' => 'sum(valor)');
$tabela = array(TBL_USUARIO, TBL_PAGAMENTOS, TBL_PROCESSOS);

$condition = " 1 ";

if (!empty($_GET['m']) || !empty($_GET['y'])) {
    $m = $_GET['m'];
    $y = (empty($_GET['y']))?date("Y"):$_GET['y'];
    $b = $y%4;

    if(in_array($m,array( 1,3,5,7,8,10,12))){
        $d=31;

    }else if($m == 2){
        if($b == 0){$d = 28;}else{$d=29;}
    }else{
        $d = 30;
    }
    if(!empty($m)){

        $m1 = $m;
        $m2 = $m;

    }else{
        $m1=1;
        $m2 = '12';
    }
    $condition .= " and ( vencimento BETWEEN '$y-$m1-01 00:00:00' AND  '$y-$m2-$d 23:00:00') ";
}

$condition .= " and advogado_alocado = id and processos_id_processo = id_processo ";
$condition .= " group by id ";

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

$meses = array (1 => "janeiro", 2 => "fevereiro", 3 => "março", 4 => "abril", 5 => "maio", 6 => "junho", 7 => "julho", 8 => "agosto", 9 => "setembro", 10 => "outubro", 11 => "novembro", 12 => "dezembro");
$m = "_". $meses[$mes];
$mpdf->Output('produtividade_pdf'.$m.'.pdf', 'D');
exit;
?>
