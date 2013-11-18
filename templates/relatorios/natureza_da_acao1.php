<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
$pdo = new conectaPDO(); //INICIA CONEXÃO PDO
$campos_da_tabela = array('sum(valor)', 'natureza_da_acao');
$tabela = array(TBL_PAGAMENTOS, TBL_PROCESSOS);
$condition = " processos_id_processo=id_processo ";
$condition .= " group by natureza_da_acao ";
$dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
$pdo->endConnection(); //FIM DA CONEXÃO
foreach ($dados as $dado) {
    $datas[$dado['natureza_da_acao']] = $dado['sum(valor)'];
}
$dnatureza_acao = array(
'Penal' => 'penal',
'Trabalhista' => 'trabalhista',
'Pequenas causas' => 'pequenas causas',
'Família' => 'familia',
'JECrim' => 'jecrim',
'Orfanológico' => 'orfanologico',
'Execução penal' => 'execucao penal',
'Registro público'=>'registro publico'
);

?>        
        <script src="js/pizza/raphael.js"></script>

<script src="js/pizza/pie.js"></script>
<style media="screen">
    #holder {
        height: 480px;
        left: 50%;
        margin: -240px 0 0 -320px;
        position: relative;
        top: 50%;
        width: 640px;
    }
    #copy {
        bottom: 0;
        font: 300 .7em "Helvetica Neue", Helvetica, "Arial Unicode MS", Arial, sans-serif;
        position: absolute;
        right: 1em;
        text-align: right;
    }
    #copy a {
        color: #fff;
    }
</style>
<style media="print">

    body {
        background: #fff;
        color: #000;
        font: 100.1% "Lucida Grande", Lucida, Verdana, sans-serif;
    }
    #holder {
        height: 480px;
        left: 50%;
        margin: 0 0 0 -320px;
        position: relative !important;
        top: 0;
        width: 640px;
    }
    #copy {
        bottom: 0;
        font-size: .7em;
        position: absolute;
        right: 1em;
        text-align: right;
    }
</style>
<style media="screen">
    #holder {
        margin: -200px 0 0 -350px;
        width: 700px;
        height: 700px;
    }

</style>
<h2>Relatório de Natureza da Ação(montante financeiro x natureza da ação)</h2><br /><br />
<div class="right center piecorrect">
</div>
<table class="tabela">
    <tbody>
        <?php foreach ($dnatureza_acao as $key=>$dado) { ?>
            <tr>
                <th scope="row"> <?php print $key; ?> </th>
                <td> <?php print !empty($datas[$dado])?number_format($datas[$dado], 2, '.', ''):0; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div id="holder"></div>

