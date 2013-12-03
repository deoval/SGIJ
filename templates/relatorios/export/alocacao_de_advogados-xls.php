<?php

if (file_exists('config.php')) {
    require_once( 'config.php' );
}
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
if (!empty($_GET['m'])) {
    $mes = $_GET['m'];
}
else{
    $mes = date("m");
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array('Advogado Alocado' => 'nome', 'Total processo' => 'count(advogado_alocado)', 'Mês' => $mes );

$tabela = array(TBL_USUARIO, TBL_PROCESSOS);

$condition = " advogado_alocado = id ";
if (!empty($mes)) {
    $m = $mes;
    $y = date("Y");
    $b = $y%4;

    if(in_array($m,array( 1,3,5,7,8,10,12))){
        $d=31;

    }else if($m == 2){
        if($b == 0){$d = 28;}else{$d=29;}
    }else{
        $d = 30;
    }
    $condition .= " and ( data_abertura BETWEEN '$y-$m-01 00:00:00' AND  '$y-$m-$d 23:00:00') ";
}
$condition .= " group by advogado_alocado ";

$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
$meses = array (1 => "janeiro", 2 => "fevereiro", 3 => "março", 4 => "abril", 5 => "maio", 6 => "junho", 7 => "julho", 8 => "agosto", 9 => "setembro", 10 => "outubro", 11 => "novembro", 12 => "dezembro");
$m = "_". $meses[$mes];
$arquivo = 'alocacao_de_advogados_xls'.$m.'.xls';

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
