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

$relatorio = explode("/", $_GET['r']);

if ($relatorio[1]=="alocacao_de_advogado"){


    $pdo = new conectaPDO(); //INICIA CONEXГO PDO
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
    $pdo->endConnection(); //FIM DA CONEXГO

    //Inicio para gerar o grafico
    $grafico = new PHPlot(800, 400);
    #Indicamos o t?tul do gr?fico e o t?tulo dos dados no eixo X e Y do mesmo
    $grafico->SetTitle("Alocaзгo por processos");
    $grafico->SetXTitle("Advogados");
    $grafico->SetYTitle("Nъmero de Processos");

    $grafico->SetImageBorderType('plain');
    $grafico->SetYTickIncrement(1);

    #Definimos os dados do gr?fico

    if(empty($dados[0])){
        $dados = array(
            array('',0));

    }

    $grafico->SetDataValues($dados);
    #Neste caso, usariamos o gr?fico em barras
    $grafico->SetPlotType("bars");
    #Exibimos o gr?fico
    $grafico->DrawGraph();
}
elseif($relatorio[1]=="rentabilidade"){

    $fieldcriteria = 'sum(valor)';
    $tabela = array(TBL_CLIENTE, TBL_PROCESSOS, TBL_PAGAMENTOS);
    $condition = " id_cliente = cliente and id_processo =  processos_id_processo ";
    $str = TEXT_TIPO_CLIENTECOUNT;

    $pdo = new conectaPDO(); //INICIA CONEXГO PDO
    $campos_da_tabela = array('tipo_cliente', $fieldcriteria);
    $condition .= " group by tipo_cliente ";
    $dados = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
    //var_dump($dados);
    $pdo->endConnection(); //FIM DA CONEXГO


    $fieldcriteria = 'count(id_cliente)';
    $tabela = array(TBL_CLIENTE);
    $condition = "1";
    $str = TEXT_TIPO_CLIENTEXVALOR;

    $pdo = new conectaPDO(); //INICIA CONEXГO PDO
    $campos_da_tabela = array('tipo_cliente', $fieldcriteria);
    $condition .= " group by tipo_cliente ";
    $dados2 = $pdo->getArrayData($campos_da_tabela, $tabela, $condition);
    //var_dump($dados);
    $pdo->endConnection(); //FIM DA CONEXГO

    if(empty($dados[0])){
        $dados = array(
            array('',0));

    }
    # Data for plot #1: bars:
    $y_title1 = 'Valor';
    $legend1 = array('Mensalista', 'Varejista');

    # Data for plot #2: linepoints:
    $y_title2 = 'Nъmero de Clientes';
    $legend2 = array('Clientes');

    //Inicio para gerar o grafico
    $plot = new PHPlot(800, 600);
    $plot->SetImageBorderType('plain');
    $plot->SetPrintImage(False); // Defer output until the end
    #Indicamos o t?tul do gr?fico e o t?tulo dos dados no eixo X e Y do mesmo
    $plot->SetTitle("Relaзгo tipo de clientes e o valor pago pelos mesmos");
    $plot->SetPlotBgColor('gray');
    $plot->SetLightGridColor('black'); // So grid stands out from background

    # Plot 1
    $plot->SetDrawPlotAreaBackground(True);
    $plot->SetPlotType('bars');
    $plot->SetDataType('text-data');
    $plot->SetDataValues($dados);
    $plot->SetYTitle($y_title1);
    $plot->SetDataColors(array('blue', 'orange'));
# Set and position legend #1:
    $plot->SetLegend($legend1);
    $plot->SetLegendPixels(5, 30);
#Set margins to leave room for plot 2 Y title on the right.
    $plot->SetMarginsPixels(120, 120);
# Specify Y range of these data sets:
    $plot->SetPlotAreaWorld(NULL, 0, NULL, 5000);
    $plot->SetYTickIncrement(500);
    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');
# Format Y tick labels as integers, with thousands separator:
    $plot->SetYLabelType('data',0, 'R$');
    $plot->DrawGraph();

# Plot 2
    $plot->SetDrawPlotAreaBackground(False); // Cancel background
    $plot->SetDrawYGrid(False); // Cancel grid, already drawn
    $plot->SetPlotType('linepoints');
    $plot->SetDataValues($dados2);
# Set Y title for plot #2 and position it on the right side:
    $plot->SetYTitle($y_title2, 'plotright');
# Set and position legend #2:
    $plot->SetLegend($legend2);
    $plot->SetLegendPixels(690, 30);
# Specify Y range of this data set:
    $plot->SetPlotAreaWorld(NULL, 0, NULL, 50);
    $plot->SetYTickIncrement(2);
    $plot->SetYTickPos('plotright');
    $plot->SetYTickLabelPos('plotright');
    $plot->SetDataColors('black');
# Format Y tick labels as integers with trailing percent sign:
    $plot->SetYLabelType('data', 0);
    $plot->DrawGraph();

# Now output the graph with both plots:
    $plot->PrintImage();
}

?>