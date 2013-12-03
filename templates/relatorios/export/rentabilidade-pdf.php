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
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO

$campos_da_tabela = array(
    'Nome' => 'nome',
    'Tipo de Cliente' => 'tipo_cliente',
    'Status do pagamento'=>'status_pagamento',
    'Valor' => 'valor'
);

$tabela = array(TBL_CLIENTE, TBL_PROCESSOS, TBL_PAGAMENTOS);

$condition .= " id_cliente = cliente and id_processo = processos_id_processo";


/* 	$campos_da_tabela = array(
  'Id Processo'=>'id_processo',
  'Numero do Processo(TJ)'=>'numero_processo_tj',
  'Nome'=>'nome',
  'E-mail'=>'email',
  'Tipo de Cliente'=>'tipo_cliente',
  'Natureza da A&ccedil;&atilde;o'=>'natureza_da_acao',
  'Status do processo'=>'status_processo',
  'Plano de pagamento'=>'plano_pagamento',
  'Forma de pagamento'=>'forma_pagamento',
  'Status do pagamento'=>'status_pagamento',
  'Valor'=>'sum(valor)'
  );

  $tabela = array(TBL_CLIENTE, TBL_PROCESSOS, TBL_PAGAMENTOS);
  $condition  ="1";
  $condition .= " and id_cliente = cliente and id_processo = processos_id_processo
  group by tipo_cliente,id_processo "; */

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO


$html = '';
$html .= '<table>';
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

$mpdf->Output('rentabilidade_pdf.pdf', 'D');
exit;
?>
