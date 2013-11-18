<?php

if (file_exists('config.php')) {
    require_once( 'config.php' );
}

Class conectaPDO extends PDO {

    private $hostname = SQL_HOST;
    private $username = SQL_USER;
    private $password = SQL_PASS;
    private $database = SQL_DB;
    private $port = SQL_PORT;
    private $pdo_tipo_sgbd = PDO_TYPE_SGBD; //comum para MySQL, PgSQL, Oracle, Firebird e DBLIB
    private $conexaoPDO; //RECEBE OS DADOS DA CONEXÃO PDO INICIADA 

    public function __construct() {
        //QUANDO CONSTRUO A CLASSE FAÇO A CHAMADA DA CONEXÃO PDO E ADICIONO AO ATRIBUTO PRIVADO $conexaoPDO
        try {
            //mysql:port=3306;host=localhost;dbname=nomedobanco
            $string_pdo_conection = "{$this->pdo_tipo_sgbd}:port={$this->port};host={$this->hostname};dbname={$this->database}";
            $this->conexaoPDO = new PDO($string_pdo_conection, $this->username, $this->password);
            $this->conexaoPDO->exec('SET NAMES utf8'); //defino como utf 8 para que caracters como ç não fiquem assim: Ã§
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /* SQL SELECT */

    public function getArrayData($fields = array(), $tabela, $condition = 1) {
        $dadosRetornados = array(); //ESSA VARIÁVEL VAI RECEBER O ARRAY COM CONTEUDO DOS DADOS VINDO DO BANCO DE DADOS
        try {
            $sql = "SELECT " . implode(",", $fields) . " FROM " . implode(",", $tabela) . " WHERE $condition";
            //ABAIXO FAÇO UM LOOP PARA BUSCAR AS LINHAS DA TABELA
            foreach ($this->conexaoPDO->query($sql) as $key => $row) {
                //ABAIXO FAÇO UM LOOP PARA BUSCAR TODOS OS CAMPOS DE UMA DAS LINHAS QUE QUERO MOSTRAR E JOGO NO ARRAY $dadosRetornados
                foreach ($fields as $field) {
                    $dadosRetornados[$key][$field] = $row[$field];
                }
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }//print $sql;
        return $dadosRetornados; //RETORNO UM ARRAY COM OS CAMPOS
    }

    /* SQL UPDATE */

    public function updateData($campos = array(), $condition, $tabela) {
        $campos_formatados = array(); //ARRAY CRIADO PARA RECEBER AS STRINGS "SET CAMPO = 'VALOR'"
        foreach (array_keys($campos) as $indices) {
            //O ARRAY $campos TEM QUE VIR NO FORMATO $array['field_do_banco'] = 'valor'
            $campos_formatados [] = " {$indices} = $campos[$indices]";
        }
        $sql = "UPDATE $tabela SET " . implode(",", $campos_formatados) . " WHERE $condition";
        $linhasAfetadas = $this->conexaoPDO->exec($sql);
        return $linhasAfetadas;
    }

    /* SQL INSERT */

    public function insertData($campos, $tabela) {
        $sql = "INSERT INTO $tabela(" . implode(",", array_keys($campos)) . ") VALUES (\"" . implode("\",\"", $campos) . "\")";

        $linhasAfetadas = $this->conexaoPDO->exec($sql);
        //return $linhasAfetadas;/* MOSTRA O NUMERO DE LINHAS AFETADAS COM O INSERT */
        return $this->conexaoPDO->lastInsertId(); /* MOSTRA A ULTIMA ID INSERIDA NO BANCO DE DADOS */
    }

    public function getLoginBind($login, $senha, $tabela) {
        // Prepara um comando para execução e retorna um objeto de declaração
        $sql = 'SELECT * FROM ' . $tabela . ' WHERE login = :login and senha= :senha';
        $stm = $this->conexaoPDO->prepare($sql);
        // Constantes PDO::PARAM_*
        // http://php.net/manual/pt_BR/pdostatement.bindvalue.php
        // http://php.net/manual/pt_BR/pdo.constants.php
        $stm->bindValue(':login', $login, PDO::PARAM_STR);
        $stm->bindValue(':senha', $senha, PDO::PARAM_STR);
        $stm->execute();
        //return $stm->debugDumpParams();
        return $stm->fetchAll();
    }

    public function deleteData($tabela, $condition) {
        $campos_formatados = array(); //ARRAY CRIADO PARA RECEBER AS STRINGS "SET CAMPO = 'VALOR'"
        $sql = "DELETE FROM $tabela WHERE $condition";
        $linhasAfetadas = $this->conexaoPDO->exec($sql);
        return $linhasAfetadas;
    }

    public function endConnection() {

        $this->conexaoPDO = null; /* FECHA CONEXAO DO BANCO DE DADOS */
    }

}

?>
