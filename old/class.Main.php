<?php

if (file_exists('config.php')) {
    require_once( 'config.php' );
}

Class Main {

    public static function getRoutes($post_param) {
        $routexy = explode("/", $post_param);
        //limit serve para limitar acesso aos recursos de template pela primeira parte da rota (pagamentos, perfil, logout)
        $limit = array(
            'advogado' => array('perfil', 'processo', 'pagamentos', 'logout', 'prazo', ''),
            'secretaria' => array('perfil', 'cliente', 'tarefa', 'logout', '')
        );
        /*
          o index 'liberar_acesso' de limit serve para dar acesso a um arquivo especifico de pasta limitada
          formato $limit['liberar_acesso'][cargo][pasta ou arquivo][arquivo]
          $limit['liberar_acesso']['advogado']['tarefa']['index'] = '1';
          lê-se liberar acesso ao advogado a rota tarefa/index
         */
        $limit['liberar_acesso']['advogado']['tarefa']['index'] = '1';
        $limit['liberar_acesso']['advogado']['cliente']['view'] = '1';
        $limit['liberar_acesso']['advogado']['usuario']['view'] = '1';
        $limit['liberar_acesso']['secretaria']['processo']['view'] = '1';
        $limit['liberar_acesso']['secretaria']['usuario']['view'] = '1';

        //quando entra na index a rota de template nao está definida por default apresenta as tarefas do advogado logado ou admin p/secretaria
        if (!isset($post_param)) {
            $post_param = ($_SESSION['user']['cargo'] == 'secretaria' ? 'tarefa/admin' : 'tarefa/index');
        }
        //se nao tem id na sessão nao existe login por isso vai para tela de login
        if (empty($_SESSION['user']['id'])) {
            $post_param = 'login';
        }

        /* verifico se limitar_acesso ao arquivo está ou não permitido antes de colocar 403 por diretorio nao permitido */
        if (!empty($routexy[1])
                && isset($limit['liberar_acesso'][$_SESSION['user']['cargo']][$routexy[0]][$routexy[1]])) {
            $liberar_acesso = TRUE;
        }
        // se o diretorio nao foi autorizado mostra pagina 403
        if (!empty($routexy[0])
                && $liberar_acesso != TRUE
                && !empty($_SESSION['user']['id'])
                && (is_array($limit[$_SESSION['user']['cargo']]) && !in_array($routexy[0], $limit[$_SESSION['user']['cargo']]) )
                && in_array($_SESSION['user']['cargo'], array_keys($limit))) {
            $post_param = '403';
        }
        $file_template = TEMPLATE_FOLDER . $post_param . '.php';
        if (is_file($file_template)) {
            $file = $file_template;
        } else {
            $file = TEMPLATE_FOLDER . '404.php';
        }

        return $file;
    }

    public static function getPagination($total, $per_page, $link_adicional, $route) {
        $todas = 0;
        $t = 0;
        while ($todas <= $total) {
            $t+=1;
            $links .= "<li><a href=index.php?r=" . $route . "&page=$t$link_adicional>" . ($t) . "</a></li>";
            $todas+=$per_page;
        }
        if ($todas > 0) {
            return $links;
        } else {
            return "";
        }
    }

    public static function getForm($url, $route, $campos_da_tabela, $filtro = array()) {
        $string = "";
        $string .= "<form id='form-search' method='GET' action='$url'>";
        $string .= "<input type='hidden' name='r' value='$route'>";
        $string .= "<input type='text' placeholder='Filtro de busca' name='t' value='' />";
        $string .= "<select name='c'>";
        foreach ($campos_da_tabela as $key => $campos) {
            $string .= "<option value='$campos'>$key</option>\n";
        }
        $string .= "</select></form>";
        if (!empty($filtro['c']) || !empty($filtro['t'])) {
            $string = "<a class='btn remove-filter' href=index.php?r=$route>Remover Filtro</a>";
        }
        return $string;
    }

    public static function getBreadCrumb($r, $id) {
//breadcrumb apresentado 
        $string = "";
        $string .="<ul class='breadcrumb'>";
        $breadcrumbs_url = explode("/", $r); //exemplo ?r=clientes/admin
        if (!isset($r)) {
            $string .= "<li style='text-transform:capitalize'> Inicial <span class='divider'>/</span></li>";
        }
        if (!empty($breadcrumbs_url[0])) {
            $string .= "<li style='text-transform:capitalize'>" . $breadcrumbs_url[0] . " <span class='divider'>/</span></li>";
        }
        if (!empty($breadcrumbs_url[1])) {
            $string .= "<li style='text-transform:capitalize'>" . str_replace('_', ' ', $breadcrumbs_url[1]) . " <span class='divider'>/</span></li>";
        }
        $string .= "</ul>";
        return $string;
    }

    public static function redirect($url, $time = 0) {
        if (!headers_sent()) {
            sleep($time);
            header('Location: ' . $url);
            exit;
        } else {
            echo '<script type="text/javascript">';
            echo 'window.setTimeout("window.location.href=\'' . $url . '\';", ' . ($time * 1000) . ');';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="' . $time . ';url=' . $url . '" />';
            echo '</noscript>';
            exit;
        }
    }

    public static function formatSlashes($string) {
        //o sistema só via usar aspas simples, porisso substituiremos as aspas duplas 
        $string = addslashes($string);
        $string = str_replace('"', "'", $string);
        return $string;
    }

    public static function getAdminLinks($r, $id_tb) {
        $folder = explode("/", $r);
        $html = "";
        $html .= "<a class=\"action-links\" title='ver' href=index.php?r=" . $folder[0] . "/" . VIEW_FILENAME . "&id=$id_tb><img src=images/" . VIEW_FILENAME . ".png></a>";
        $html .= "<a class=\"action-links\" title='atualizar' href=index.php?r=" . $folder[0] . "/" . UPDATE_FILENAME . "&id=$id_tb><img src=images/" . UPDATE_FILENAME . ".png></a>";
        $html .= "<a class=\"action-links\" title='deletar' href=index.php?r=" . $folder[0] . "/" . DELETE_FILENAME . "&id=$id_tb><img src=images/" . DELETE_FILENAME . ".png></a>";
        return $html;
    }

}

?>
