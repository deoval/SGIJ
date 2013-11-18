<?php

//arquivo criado para organização das configurações
define('SQL_HOST', '127.0.0.1');
define('SQL_USER', 'root');
define('SQL_DB', 'sistema');
define('SQL_PASS', '');
define('SQL_PORT', '3306'); //porta padrão mysql
define('PDO_TYPE_SGBD', 'mysql');

//pasta de templates

define('TEMPLATE_FOLDER', 'templates/');
define('CREATE_FILENAME', 'create');
define('UPDATE_FILENAME', 'update');
define('DELETE_FILENAME', 'delete');
define('VIEW_FILENAME', 'view');
define('ADMIN_FILENAME', 'admin');
define('FONT_FOLDER', 'fontes/');
define('FONT_FILE', 'verdana.ttf');
//mensagens basicas
define('NOT_FOUND', ' Nada Encontrado');
define('EMPRESA_NOME', 'Projeto Sistema Advogados ');
define('LOGIN_ERROR', ' Credenciais Invalidas, verifique seu login ou senha ');
define('TABLE_EMPTY', 'Não há registros para exibir');
define('TEXT_LIST_TAREFAS', 'Lista de tarefas para o advogado ');
define('TEXT_DEFAULT_FILTER', 'Em automático os filtros da busca limitam os registros até a data atual ');
define('TEXT_DISPLAY_ALL', 'Mostrar todos os registros ');
define('TEXT_DISPLAY_FILTER', 'Mostrar registros até a data atual ');
define('EMPTY_PROCESSOS_PAGAMENTO', 'Para registrar um pagamento é necessário existir pelo menos um <a href=index.php?r=processo/create>processo</a>');
define('EMPTY_PROCESSOS_PRAZO', 'Para registrar um prazo é necessário existir pelo menos um <a href=index.php?r=processo/create>processo</a>');
define('EMPTY_CLIENTE_PROCESSOS', 'Para registrar um processo é necessário existir pelo menos um <a href=index.php?r=cliente/create>cliente</a>');
define('TEXT_TIPO_CLIENTEXVALOR', 'Tipo de Cliente x Rentabilidade');
define('TEXT_TIPO_CLIENTECOUNT', 'Tipos de Cliente em números ');
define('INSERT_SUCCESS', 'Inclusão realizada com sucesso! ');
define('UPDATE_SUCCESS', 'Alteração realizada com sucesso! ');
define('CONFIRM_EXCLUDE', 'Tem certeza  de que deseja excluir registro? ');
define('DELETE_SUCCESS', 'Exclusão realizada com sucesso! ');
define('EXPORT_DATA', 'Exportar Dados');
define('UPDATE', 'Atualizar');
define('CREATE', 'Criar');
define('LOGIN', 'Login');
define('BACK', 'Voltar');

//tabelas do sistema
define('TBL_CLIENTE', 'cliente');
define('TBL_DADOS_CLIENTE', 'dados_cliente');
define('TBL_TELEFONES_CLIENTE', 'telefones_clientes');
define('TBL_PESSOA_FISICA', 'pessoa_fisica');
define('TBL_PESSOA_JURIDICA', 'pessoa_juridica');
define('TBL_PROCESSOS', 'processos');
define('TBL_TAREFAS', 'tarefas');
define('TBL_PRAZOS', 'prazos');
define('TBL_PAGAMENTOS', 'pagamentos');

define('TBL_USUARIO', 'usuario');
define('TBL_TELEFONES_USUARIO', 'telefones_advogados');
define('TBL_ADVOGADO', 'advogado');
?>
