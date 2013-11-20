<?php
error_reporting(0);
?>
<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
if (is_file('./class.Main.php')) {
    require_once('./class.Main.php');
}
$nav = array(
    'advogado' => array('processo', 'prazo'),
    'secretaria' => array('cliente', 'tarefa'),
    'advogado_socio' => array('cliente', 'tarefa', 'usuario', 'processo', 'prazo', 'pagamento', 'relatorio')
);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php print EMPRESA_NOME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap-dropdown.js"></script>
        <script src="js/bootstrap-collapse.js"></script>
        <script src="js/jquery-ui.js"></script>
        <!-- Le styles -->
        <link rel="stylesheet" href="css/jquery-ui.css" />
        <link href="css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            /*body {
                padding-top: 60px;
                padding-bottom: 40px;
            }*/
        </style>
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
        <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="ico/favicon.png">
    </head>

    <body>

    <div id="cabecalho">
        <header>
            <img height="200px" width="200px" src="images/Logo.png">
            <?php
            if (!empty($_SESSION['user']['id'])) {
                $link_login_logout = 'logout';
                $sys_user_name = $_SESSION['user']['login'];
                $sys_user_cargo = "(" . str_replace('_', ' ', $_SESSION['user']['cargo']) . ")";
            } else {
                $link_login_logout = 'login';
                $sys_user_name = '';
                $sys_user_cargo = '';
            }
            ?>
            <div class="navbar-form pull-right">
                <span style="line-height:40px;color:#f2eeee; text-transform:capitalize"><?php print $sys_user_name; ?> <?php print $sys_user_cargo; ?></span>
                <a style="line-height:40px;text-decoration: none;color:#f2eeee;text-transform:capitalize" href="index.php?r=<?php print $link_login_logout; ?>"><?php print $link_login_logout; ?></a></div>
        </header>

            <div class="navbar navbar-inverse navbar-fixed-top" id="menu">
                <div class="navbar-inner">
                    <div class="container">

                        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <div class="nav-collapse collapse">
                            <ul class="nav">
                                <li><a href="index.php">Inicial</a></li>
                                <li><a href="?r=perfil">Perfil</a></li>
                                <?php if (isset($_SESSION['user']) && in_array('usuario', $nav[$_SESSION['user']['cargo']])) { ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuário <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="index.php?r=usuario/create">Incluir</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=usuario/admin">Consultar</a></li>


                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if (isset($_SESSION['user']) && in_array('cliente', $nav[$_SESSION['user']['cargo']])) { ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cliente <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="index.php?r=cliente/create">Incluir</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=cliente/admin">Consultar</a></li>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if (isset($_SESSION['user']) && in_array('processo', $nav[$_SESSION['user']['cargo']])) { ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Processos <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="index.php?r=processo/create">Incluir</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=processo/admin">Consultar</a></li>


                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if (isset($_SESSION['user']) && in_array('tarefa', $nav[$_SESSION['user']['cargo']])) { ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tarefas <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="index.php?r=tarefa/create">Incluir</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=tarefa/admin">Consultar</a></li>


                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if (isset($_SESSION['user']) && in_array('prazo', $nav[$_SESSION['user']['cargo']])) { ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Prazos <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="index.php?r=prazo/create">Incluir</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=prazo/admin">Consultar</a></li>


                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if (isset($_SESSION['user']) && in_array('pagamento', $nav[$_SESSION['user']['cargo']])) { ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pagamentos <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="index.php?r=pagamentos/create">Incluir</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=pagamentos/admin">Consultar</a></li>


                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if (isset($_SESSION['user']) && in_array('relatorio', $nav[$_SESSION['user']['cargo']])) { ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Relatórios <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="index.php?r=relatorios/rentabilidade">Relatório de Rentabilidade</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=relatorios/alocacao_de_advogado">Relatório Alocação de Advogados</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=relatorios/produtividade">Relatório de Produtividade</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=relatorios/prazos">Relatório de Prazos</a></li>
                                            <li class="divider"></li>
                                            <li><a href="index.php?r=relatorios/natureza_da_acao">Relatório de Natureza da Ação</a></li>
                                            <li class="divider"></li>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>

                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>
        </div>
        <div id="conteudo">
        <div class="container">
            <div class="row">
                <div class="span12">
                    <?php print Main::getBreadCrumb($_GET['r'], $_GET['id']); ?>
                    <?php
                    include(Main::getRoutes($_GET['r']));
                    ?>
                </div>
            </div>

            <hr>

            <footer id="footer">
                <p><?php print EMPRESA_NOME . " &copy; " . Date('Y'); ?></p>
            </footer>

        </div>  <!-- /container -->
        </div>
        <script>
            jQuery( document ).tooltip({
                position: {
                    my: "left bottom-5",
                    at: "left top",
                    using: function( position, feedback ) {
                        $( this ).css( position );
                        
                    }
                }
            });  
        </script>
    </body>
</html>
