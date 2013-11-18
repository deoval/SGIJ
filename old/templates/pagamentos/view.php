<?php

if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
if (is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    exit();
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array(
    'ID (Pgto)' => id_pagamento,
    'Processo' => processos_id_processo,
    'Tempo de Atraso' => tempo_atraso,
    'Plano de pagamento' => plano_pagamento,
    'Valor' => valor,
    'Parcelas pagas' => parcelas_pagas,
    'Forma de pagamento' => forma_pagamento,
    'Status' => status_pagamento,
    'Vencimento' => vencimento
);
$is_data = array('vencimento');
$tabela = array(TBL_PAGAMENTOS);
$condition = "id_pagamento = $id";
$processos = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO

foreach ($campos_da_tabela as $key => $campo) {
$value = (in_array($campo, $is_data) ? date_format(date_create($processos[0][$campo]), "d/m/Y H:i:s") : $processos[0][$campo]);
$value = ($campo == 'processos_id_processo' ? "<a href='index.php?r=processo/view&id=" . $value . "'>Ver Processo</a>" : $value);
    print "<span class='label'>$key </span> " . $value . "<br />";
}
?>
