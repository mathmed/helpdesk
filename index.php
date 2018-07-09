
<?php

    /* Verificando se existe alguma mensagem de erro */

    $erro = isset($_GET['erro']) ? $_GET['erro'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Helpdesk - Login</title>

        <!-- CSS FILES-->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="custom-css/signin.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="icon" href="imagens/spo.png" type="image/x-icon"/>

        <!-- Javascript files -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>
    </head>
    <body>

        <!-- DIV PRINCIPAL -->
          
            <div class="container">
                    <div class="container">                    
                        <div class="text">
                            <h1><strong>Helpdesk</strong>Login</h1>
                            <div class="description"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- COMEÇO DA DIV DO FORMULÁRIO -->
                        <div class="form-box col-md-12">

                          <div id = 'form_top_id' class="form-top">
                            <div class="form-top-left">
                              <h3><strong>Faça login para acessar o sistema</strong></h3>
                                <p>Utilize seu usuário e sua senha</p>
                            </div>

                            <div class="form-top-right">
                              <i class="fa fa-key"></i>
                            </div>

                            </div>

                            <div class="form-bottom">

                               <!-- ENVIANDO OS DADOS FORMULÁRIOS PARA O BACKEND -->

                                <form role="form" action="system/auth.php" method="post" class="login-form"> 
                                <div class="form-group">
                                  <label class="sr-only" for="form-username">Usuário</label>
                                    <input type="text" name="usuario" placeholder="Usuário..." class="form-username form-control" id="form-username">
                                  </div>
                                  <div class="form-group">
                                    <label class="sr-only" for="form-password">Senha</label>
                                    <input type="password" name="senha" placeholder="Senha..." class="form-password form-control" id="form-password">
                                  </div>
                                  <button type="submit" class="btn btn-primary btnlogin">Logar-se!</button>

                                </form>

                                <!-- FIM DO FORM -->

                         </div>
                                <!-- Div para aparecer erro e/ou esqueceu a senha -->
                                <div style = 'padding-bottom: 20px;'>
                                    <h1 style = 'font-size: 16px; font-weight: bold; color: #FF0000'><?php if($erro == 1){ echo 'Usuário e/ou senha inválido(s)'; } ?></h1>
                                </div>

                        </div>
                </div>
        </div>

        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>