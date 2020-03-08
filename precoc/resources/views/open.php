<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Login | PROFESSOR</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="../../../content/logo-titulo.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../content/style.css">

</head>
<body>
    <div class="container mt-5 p-5 border border-dark rounded col col-12 col-xl-4 col-lg-6 col-md-8 col-sm-12" id="meio1">
        <div class="container" id="logo">
            <h1 class="text-center my-3">PRÉ-COC</h1>
            <h2 class="text-center pb-3">PROFESSOR</h2> 

            <form action="menu.php" method="POST">        
                <div class="row justify-content-center align-items-center mt-5">
                    <div class="col-md-8">
                        <div class="col-md-24">
                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><img src="../../../content/login-usuario.png"></div>
                                        </div>
                                    <input class="form-control rounded-0 border border-dark" type="text" name="usuario" placeholder="E-mail/matricula">
                                </div>
                            </div>
                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><img src="../../../content/login-senha.png"></div>
                                        </div>        
                                    <input class="form-control rounded-0 border border-dark" type="password" name="senha" placeholder="•••••••"> 
                                </div>
                                <a href="esqueceu_senha.html" style="color: #1e10ba;">Não sei minha senha...</a>
                            </div>                       
                            <div class="form-group justify-content-center align-items-center mt-2 mb-5">
                                <input class="form-control border border-dark btn btn-primary btn-block" type="button" value="Entrar">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="toast p-2 mb-2" id="aviso" role="alert" style="background-color: rgb(158, 13, 5); color: white; min-width: 100px; display: none"> 
                        <div class="toast-body">
                            Login inválido, tente novamente
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <footer style="flex-shrink: none;" class="py-4 text-white-50">
        <div class="container text-center">
        <small>© 2019 Copyright: <a href="https://github.com/danielShz" target="_blank"> Daniel Arruda </a> & <a href="https://github.com/muniz034" target="_blank"> Pedro Muniz </a> </small>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="../js/index.js"></script>
</body>
</html>