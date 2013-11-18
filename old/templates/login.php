<style type="text/css">
    .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
        box-shadow: 0 1px 2px rgba(0,0,0,.05);
    }
    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }
    .form-signin input[type="text"],
    .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
    }
</style>
<?php
if (file_exists('class.PDOcrud.php')) {
    require_once( 'class.PDOcrud.php' );
}
if ($_POST && !empty($_POST)) {
    $conection = new conectaPDO();
    $conexao = $conection->getLoginBind($_POST['user'], base64_encode($_POST['pass']), 'usuario');
    $conection->endConnection();
    if (empty($conexao)) {
        print "<div class='erro label-important'>" . LOGIN_ERROR . "</div>";
    }
}
if (!empty($conexao) && !empty($_POST['user'])) {
    $_SESSION['user'] = array('id' => $conexao[0]['id'], 'login' => $conexao[0]['login'], 'cargo' => $conexao[0]['cargo']);
    Main::redirect('index.php');
}
?>

<form class="form-signin" method="POST" action="index.php?r=login">
    <input type="hidden" name="r" value="login" />
    <h2 class="form-signin-heading">Administração</h2>
    <input type="text" name="user" class="input-block-level" placeholder="Login">
    <input type="password" name="pass" class="input-block-level" placeholder="Senha">
    <button class="btn btn-large btn-primary" type="submit"><?php print LOGIN; ?></button>
</form>
