<?php

if (file_exists('config.php')) {
    require_once( 'config.php' );
}
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'Nome' => 'nome',
    'E-mail' => 'email',
    'Tipo de Cliente' => 'tipo_cliente',
    'Plano de pagamento' => 'plano_pagamento',
    'Forma de pagamento' => 'forma_pagamento',
    'Status do pagamento' => 'status_pagamento',
    'Valor' => 'sum(valor)'
);

$tabela = array(TBL_CLIENTE, TBL_PROCESSOS, TBL_PAGAMENTOS);

$condition .= " id_cliente = cliente and id_processo = processos_id_processo
group by tipo_cliente,id_cliente ";

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

  $condition .= " id_cliente = cliente and id_processo = processos_id_processo
  group by tipo_cliente,id_processo "; */

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
$arquivo = 'tipo_de_cliente_xls.xls';

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
