
<div class="loginpage">
<div class="container" id="container">
        <div class="form-container sign-up">
            <form action="" method="POST">
                <h1>Crie uma conta</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>ou use seu e-mail para cadastro</span>
                <input type="text" name="nomeC" placeholder="Nome">
                <input type="email" name="emailC" placeholder="Email">
                <input type="password" name="senhaC" placeholder="Senha">
                <button type="submit" name="botaoCadastro">inscreva-se</button>
            </form>
        </div>
        
        <div class="form-container sign-in">
            <form action="" method="POST">
                <h1>Entrar</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>ou use sua senha de e-mail</span>
                <input type="email" name="emailL" placeholder="Email">
                <input type="password" name="senhaL" placeholder="Senha">
                <a href="#">Esqueceu sua senha?</a>
                <button type="submit" name="botaoLogin">Entrar</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Bem-vindo de volta</h1>
                    <p>Insira seus dados pessoais para usar todos os recursos do site</p>
                    <button class="hidden" id="login">Entrar</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Olá amigo</h1>
                    <p>Insira seus dados pessoais para usar todos os recursos do site</p>
                    <button class="hidden" id="register">inscreva-se</button>
                </div>
            </div>
        </div>
    </div>
<?php
    include_once('../config/conexao.php');
    // Verifica se o formulário foi enviado
    if (isset($_POST['botaoCadastro'])) {
        // Recebe os dados do formulário
        $nome = $_POST['nomeC'];
        $email = $_POST['emailC'];
        $senha = password_hash($_POST['senhaC'], PASSWORD_DEFAULT); // Usando hash seguro para a senha
    
    //    // Verifica se foi enviado algum arquivo de foto
    //    if (!empty($_FILES['foto']['name'])) {
    //        $formatosPermitidos = array("png", "jpg", "jpeg", "gif"); // Formatos permitidos
    //        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION); // Obtém a extensão do arquivo
    //
    //        // Verifica se a extensão do arquivo está nos formatos permitidos
    //        if (in_array(strtolower($extensao), $formatosPermitidos)) {
    //            $pasta = "img/user/"; // Define o diretório para upload
    //            $temporario = $_FILES['foto']['tmp_name']; // Caminho temporário do arquivo
    //            $novoNome = uniqid() . ".$extensao"; // Gera um nome único para o arquivo
    //
    //            // Move o arquivo para o diretório de imagens
    //            if (move_uploaded_file($temporario, $pasta . $novoNome)) {
    //                // Sucesso no upload da imagem
    //            } else {
    //                echo '<div class="container">
    //                        <div class="alert alert-danger alert-dismissible">
    //                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    //                            <h5><i class="icon fas fa-exclamation-triangle"></i> Erro!</h5>
    //                            Não foi possível fazer o upload do arquivo.
    //                        </div>
    //                    </div>';
    //                exit(); // Termina a execução do script após o erro
    //            }
    //        } else {
    //            echo '<div class="container">
    //                    <div class="alert alert-danger alert-dismissible">
    //                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    //                        <h5><i class="icon fas fa-exclamation-triangle"></i> Formato Inválido!</h5>
    //                        Formato de arquivo não permitido.
    //                    </div>
    //                </div>';
    //            exit(); // Termina a execução do script após o erro
    //        }
    //    } else {
    //        // Define um avatar padrão caso não seja enviado nenhum arquivo de foto
    //        $novoNome = 'avatar-padrao.png'; // Nome do arquivo de avatar padrão
    //    }
    
        // Prepara a consulta SQL para inserção dos dados do usuário
        $cadastro = "INSERT INTO tb_user (nome_user, email_user, senha_user) VALUES ( :nome, :email, :senha)";
    
        try {
            $result = $conect->prepare($cadastro);
            $result->bindParam(':nome', $nome, PDO::PARAM_STR);
            $result->bindParam(':email', $email, PDO::PARAM_STR);
            $result->bindParam(':senha', $senha, PDO::PARAM_STR);
    //      $result->bindParam(':foto', $novoNome, PDO::PARAM_STR);
            $result->execute();
            $contar = $result->rowCount();
        
            if ($contar > 0) {
                header("Location: home.php?acao=login");
                exit;
            } else {
                header("Location: home.php?acao=login&erro=ERROR");
                exit;
            }
        } catch (PDOException $e) {
            // Loga a mensagem de erro em vez de exibi-la para o usuário
            echo("ERRO DE PDO: " . $e->getMessage());
        }
    }
    ?>
<?php
// Iniciar a sessão no início de todos os arquivos onde ela é necessária
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o formulário de login foi enviado
if (isset($_POST['emailL']) && isset($_POST['senhaL'])) {
    $login = filter_input(INPUT_POST, 'emailL', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senhaL', FILTER_DEFAULT);

    if ($login && $senha) {
        $select = "SELECT * FROM tb_user WHERE email_user = :emailLogin";

        try {
            $resultLogin = $conect->prepare($select);
            $resultLogin->bindParam(':emailLogin', $login, PDO::PARAM_STR);
            $resultLogin->execute();

            $verificar = $resultLogin->rowCount();
            if ($verificar > 0) {
                $user = $resultLogin->fetch(PDO::FETCH_ASSOC);

                // Verifica a senha
                if (password_verify($senha, $user['senha_user'])) {
                    // Criar sessão com as informações do usuário
                    $_SESSION['loginUser'] = $login;
                    $_SESSION['id_user'] = $user['id_user']; // Armazenando o ID do usuário

                    // Redirecionar para a página inicial após login bem-sucedido
                    header("Location: home.php?acao=bemvindo");
                    exit;
                } else {
                    // Redirecionar em caso de senha incorreta
                    header("Location: login.php?acao=login&erro=senha_incorreta");
                    exit;
                }
            } else {
                // Redirecionar em caso de e-mail não encontrado
                header("Location: login.php?acao=login&erro=email_nao_encontrado");
                exit;
            }
        } catch (PDOException $e) {
            error_log("ERRO DE LOGIN DO PDO: " . $e->getMessage());
            header("Location: login.php?acao=login&erro=erro_login");
            exit;
        }
    } else {
        // Redirecionar caso os campos não estejam preenchidos
        header("Location: login.php?acao=login&erro=campos_obrigatorios");
        exit;
    }
}
?>


</div>
    <script src="../login/script.js"></script>