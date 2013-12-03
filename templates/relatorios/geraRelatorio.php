<?php
if (file_exists('../../config.php')) {
    require_once( '../../config.php' );
}
if (is_file('../../class.Main.php')) {
    require_once('../../class.Main.php');
}
if (file_exists('../../class.PDOcrud.php')) {
    require_once( '../../class.PDOcrud.php' );
}
include("../../phplot/phplot.php");

function decode_array($dados){  //$dados é um array, decodifica de utf8 para iso-8891
	foreach($dados as &$dado) {
		foreach($dado as &$str) {
			$str = 	utf8_decode($str);			
		}	
	}
	return $dados;
}

function verMes($data){
    $dt = explode("-", $data);

    return $dt[1];
}

$relatorio = explode("/", $_GET['r']);

if ($relatorio[1]=="alocacao_de_advogado"){


    $pdo = new conectaPDO(); //INICIA CONEXï¿½O PDO
    $campos_da_tabela = array('nome', 'count(advogado_alocado)');
    $tabela = array(TBL_USUARIO, TBL_PROCESSOS);
    $condition = " advogado_alocado = id ";
    if (!empty($_GET['m'])) {
        $m = $_GET['m'];
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
    $maiorval=0;
    foreach ($dados as $dado) {
        if ($dado['count(advogado_alocado)']>$maiorval){
            $maiorval = $dado['count(advogado_alocado)'];
        }
    }
    $ypos= $maiorval/15;
    if ($ypos<500)
    {$ypos =500;}

    //Inicio para gerar o grafico
    $grafico = new PHPlot(800, $ypos);
    #Indicamos o titulo do grafico e o titulo dos dados no eixo X e Y do mesmo
    $title = "Alocação por processos";
    $mes = array (1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
    $title .= " em ". $mes[$_GET['m']];
   	$grafico->SetTitle(utf8_decode($title));
    $grafico->SetXTitle("Advogados");
    $grafico->SetYTitle(utf8_decode("Número de Processos"));
    $grafico->SetImageBorderType('plain');
    $grafico->SetYTickIncrement(1);
    $grafico->SetFontGD('y_label',2,null );
    $grafico->SetFontGD('x_label',4,null );
    $grafico->SetFontGD('y_title',5,null );
    $grafico->SetFontGD('x_title',5,null );
    $grafico->SetFontGD('legend',5,null );
    $grafico->SetFontGD('title',5,null );


    #Definimos os dados do grafico

    if(empty($dados[0])){
        $dados = array(
            array('',0));

    }

    $grafico->SetDataValues(decode_array($dados));
    $grafico->SetDataColors(array('#6668D7'));
    #Neste caso, usariamos o grafico em barras
    $grafico->SetPlotType("bars");
    #Exibimos o grafico
    $grafico->DrawGraph();
}
elseif($relatorio[1]=="rentabilidade"){

    $fieldcriteria = 'COUNT( id_cliente )';
    $tabela = array(TBL_CLIENTE, TBL_PROCESSOS, TBL_PAGAMENTOS);
    $condition = " id_cliente = cliente and id_processo =  processos_id_processo and status_pagamento =  'quitado'";


    $pdo = new conectaPDO(); //INICIA CONEXï¿½O PDO
    $campos_da_tabela = array('vencimento', 'tipo_cliente');
    $condition .= " group by tipo_cliente, DATE_FORMAT( vencimento , '%m' )";
    $dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
    $pdo->endConnection(); //FIM DA CONEXï¿½O
    //var_dump($dados);
    if(empty($dados[0])){
        $dados = array(
            array('',0));

    }
    $dadosPlot1 = array (
        array("Janeiro",0,0),
        array("Fevereiro",0,0),
        array("Março",0,0),
        array("Abril",0,0),
        array("Maio",0,0),
        array("Junho",0,0),
        array("Julho",0,0),
        array("Agosto",0,0),
        array("Setembro",0,0),
        array("Outubro",0,0),
        array("Novembro",0,0),
        array("Dezembro",0,0)
    );
    foreach($dados as $indexnum => $arrayBD){
        foreach ($arrayBD as $indexlet => $value){
            if($indexlet == 'vencimento'){

                if($arrayBD['tipo_cliente']=='Mensalista'){

                    $dadosPlot1[verMes($value)-1][1] += 1;
                }
                else if ($arrayBD['tipo_cliente']=='Varejista'){
                    $dadosPlot1[verMes($value)-1][2] += 1;
                }

            }
        }
    }
    //var_dump($dadosPlot1);
    //Define the object
		$graph =& new PHPlot(1000,400);
		$graph->SetDataType("text-data");  //Must be called before SetDataValues


		$graph->SetDataValues(decode_array($dadosPlot1));
		$graph->SetYTickIncrement(1);  //a smaller graph now - so we set a new tick increment


		$graph->SetXTitle("");
		$graph->SetYTitle("Quantidade");
		$graph->SetPlotType("lines");
		$graph->SetLineWidth(3);
        $graph->SetDataColors(array('#6668D7','#dc8420'));
        $graph->SetLegend(array('Mensalista', 'Varejista'));
        $graph->SetXTickLabelPos('none');
        $graph->SetXTickPos('none');
        $graph->SetNewPlotAreaPixels(68,null,null,null);
		$graph->DrawGraph();

}

elseif($relatorio[1]=="rentabilidade2"){

    $fieldcriteria = 'valor';
    $tabela = array(TBL_CLIENTE, TBL_PROCESSOS, TBL_PAGAMENTOS);
    $condition = " id_cliente = cliente and id_processo =  processos_id_processo ";
    $str = TEXT_TIPO_CLIENTECOUNT;

    $pdo = new conectaPDO(); //INICIA CONEXï¿½O PDO
    $campos_da_tabela = array('vencimento', 'tipo_cliente', $fieldcriteria);
    //$condition .= " group by tipo_cliente ";
    $dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
    $pdo->endConnection(); //FIM DA CONEXï¿½O
    if(empty($dados[0])){
        $dados = array(
            array('',0));

    }
    $dadosPlot = array (
        array("Janeiro",0,0),
        array("Fevereiro",0,0),
        array("Março",0,0),
        array("Abril",0,0),
        array("Maio",0,0),
        array("Junho",0,0),
        array("Julho",0,0),
        array("Agosto",0,0),
        array("Setembro",0,0),
        array("Outubro",0,0),
        array("Novembro",0,0),
        array("Dezembro",0,0)
    );

    foreach($dados as $indexnum => $arrayBD){
        foreach ($arrayBD as $indexlet => $value){
            if($indexlet == 'vencimento'){

                if($arrayBD['tipo_cliente']=='Mensalista'){

                    $dadosPlot[verMes($value)-1][1] += $arrayBD['valor'];
                }
                else if ($arrayBD['tipo_cliente']=='Varejista'){
                    $dadosPlot[verMes($value)-1][2] += $arrayBD['valor'];
                }

            }
        }
    }

    $graph =& new PHPlot(1000,600);
    $graph->SetDataType("text-data");  //Must be called before SetDataValues

	$graph->SetDataValues(decode_array($dadosPlot));

	$graph->SetXTitle("");
	$graph->SetYTitle("Valor");
	//$graph->SetYTickIncrement(10);
    $graph->SetXTickLabelPos('none');
    $graph->SetXTickPos('none');
    $graph->SetDataColors(array('#6668D7','#dc8420'));
    $graph->SetLegend(array('Mensalista', 'Varejista'));
    $graph->SetYLabelType('data',0, 'R$ ');
    $graph->SetYTickIncrement(1000);
	$graph->SetPlotType("bars");
	$graph->DrawGraph();
}

elseif ($relatorio[1]=="natureza_da_acao"){

    $pdo = new conectaPDO(); //INICIA CONEXï¿½O PDO
    $campos_da_tabela = array( 'natureza_da_acao', 'sum(valor)');
    $tabela = array(TBL_PAGAMENTOS, TBL_PROCESSOS);
    $condition = " processos_id_processo=id_processo ";
    $condition .= "and status_pagamento='quitado'";
    $condition .= " group by natureza_da_acao ";
    $dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
    $pdo->endConnection(); //FIM DA CONEXï¿½O
    //var_dump($dados);

    $maiorval=0;
    foreach ($dados as $dado) {
        if ($dado['sum(valor)']>$maiorval){
            $maiorval = $dado['sum(valor)'];
        }
    }
    $ypos= $maiorval/15;
    if ($ypos<400)
    {$ypos =400;}


    //Inicio para gerar o grafico
    $grafico = new PHPlot(  870, $ypos);
    #Indicamos o t?tul do gr?fico e o t?tulo dos dados no eixo X e Y do mesmo
    $grafico->SetTitle(utf8_decode("Montante financeiro x natureza da ação"));
    $grafico->SetXTitle(utf8_decode("Natureza da AçÃo"));
    $grafico->SetYTitle("Valor");
    $grafico->SetYDataLabelPos('plotin');
    $grafico->SetImageBorderType('plain');
    $grafico->SetYTickIncrement(500);
    $grafico->SetYLabelType('data',0, 'R$ ');

    $grafico->SetFontGD('y_label',3,null );
    $grafico->SetFontGD('x_label',2,null );
    $grafico->SetFontGD('y_title',5,null );
    $grafico->SetFontGD('x_title',5,null );
    $grafico->SetFontGD('legend',5,null );
    $grafico->SetFontGD('title',5,null );
    #Definimos os dados do gr?fico

    if(empty($dados[0])){
        $dados = array(
            array('',0));

    }

    $grafico->SetDataValues(decode_array($dados));
    $grafico->SetDataColors(array('#6668D7'));
    #Neste caso, usariamos o grÃ¡fico em barras
    $grafico->SetPlotType("bars");
    #Exibimos o gr?fico
    $grafico->DrawGraph();


}

elseif($relatorio[1]=="produtividade"){

    $pdo = new conectaPDO(); //INICIA CONEXï¿½O PDO
    /* select sum(valor), usuario.login from pagamentos, processos, usuario where pagamentos.processos_id_processo = processos.id_processo and processos.advogado_alocado = usuario.id
      group by usuario.id */
    $campos_da_tabela = array('nome', 'sum(valor)');
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
    $pdo->endConnection(); //FIM DA CONEXï¿½O
    //var_dump($dados);
    $maiorval=0;
    foreach ($dados as $dado) {
        if ($dado['sum(valor)']>$maiorval){
            $maiorval = $dado['sum(valor)'];
        }
    }
    $ypos= $maiorval/15;
    if ($ypos<400)
    {$ypos =400;}

    //Inicio para gerar o grafico
    $grafico = new PHPlot(800, $ypos);
    #Indicamos o t?tul do gr?fico e o t?tulo dos dados no eixo X e Y do mesmo
    $title = "Rendimento em reais de cada advogado";
    $mes = array (1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
    $title .= " em ". $mes[$_GET['m']];
    $grafico->SetTitle(utf8_decode($title));
    $grafico->SetYDataLabelPos('plotin');
    $grafico->SetXTitle("Advogados");
    $grafico->SetYTitle("Valor");
    $grafico->SetYLabelType('data',0, 'R$ ');
    $grafico->SetDataColors(array('#6668D7'));
    $grafico->SetImageBorderType('plain');
    $grafico->SetYTickIncrement(500);

    #Definimos os dados do gr?fico

    if(empty($dados[0])){
        $dados = array(
            array('',0));

    }

    $grafico->SetDataValues(decode_array($dados));
    #Neste caso, usariamos o gr?fico em barras
    $grafico->SetPlotType("bars");
    #Exibimos o gr?fico
    $grafico->DrawGraph();


}

?>