<?php

if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}

/* RETRIEVE DATA */
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array('id', 'teste_conteudo');
$tabela[0] = 'teste'; //array das tabelas, como não tem junção vai 1 tabela
$condition = "1";
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
foreach ($dados as $key => $dado) {
    print "ID: " . $dado['id'] . "CONTEUDO: " . $dado['teste_conteudo'] . "<br />";
}
$pdo->endConnection(); //FIM DA CONEXÃO

/* RETRIEVE DATA LOGIN WITH BIND PARAM */
$conn = new conectaPDO();
$login = "demo";
$senha = "teste";
$tabela = "usuarios"; //tabela que contem os dados de login e senha, um array
$resultado = $conn->getLoginBind($login, $senha, $tabela);
print_r($resultado);
$conn->endConnection(); //FIM DA CONEXÃO


/* UPDATE DATA */
/*
  $pdo = new conectaPDO();//INICIA CONEXÃO PDO
  $campos_a_alterar = array('teste_conteudo' => '"teste1"');
  $tabela = 'teste';
  $condition = 'id = 1';
  print $pdo->updateData($campos_a_alterar, $condition, $tabela);
  $pdo->endConnection();//FIM DA CONEXÃO
 */

/* INSERT DATA */
/*
  $pdo = new conectaPDO();//INICIA CONEXÃO PDO
  $campos_da_tabela = array('teste_conteudo'=>'testef1');
  print $pdo->insertData($campos_da_tabela, $tabela);
  $pdo->endConnection();//FIM DA CONEXÃO
 */
?>
