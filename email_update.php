<?php
// Inicialize a sessão
session_start();

// Incluir arquivo de configuração
require_once "conn.php";

 
// Defina variáveis e inicialize com valores vazios
$new_email = $confirm_email = "";
$new_email_err = $confirm_email_err = "";
 

 
// Processando dados do formulário quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validar nova senha
    if(empty(trim($_POST["novo_email"]))){
        $new_email_err = "Por favor insira o novo email.";     
    } else{
        $new_email = trim($_POST["novo_email"]);
    }
    
    // Validar e confirmar a senha
    if(empty(trim($_POST["confirmar_email"]))){
        $confirm_email_err = "Por favor, confirme o email.";
    } else{
        $confirm_email = trim($_POST["confirmar_email"]);
        if(empty($new_email_err) && ($new_email != $confirm_email)){
            $confirm_email_err = "O email não confere.";
        }
    }
    // Verifique os erros de entrada antes de atualizar o banco de dados
    if(empty($new_email_err) && empty($confirm_email_err)){
        // Prepare uma declaração de atualização
        $sql = "UPDATE crud SET email = :email WHERE id = :id";
        
        if($stmt = $pdo->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            
            // Definir parâmetros
            $param_email = $confirm_email;
            $param_id = $_SESSION['id'];

               
            
            // Tente executar a declaração preparada
            if($stmt->execute()){
                // Senha atualizada com sucesso. Destrua a sessão e redirecione para a página de login
                session_destroy();
                header("location: index.php");
    
                exit();
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            // Fechar declaração
            unset($stmt);
        }
    }
    
    // Fechar conexão
    unset($pdo);
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link href="style_email.css" rel="stylesheet">
    <title>Update</title>
</head>
<body id="pag">
    

    <div> 
        <h1> CRUD UPDATE</h1>
    </div>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="test">
    <div>
<label> Novo email: </label>
</div>
    <div> 
        <input type="email" name="confirmar_email"  placeholder="Email:"<?php echo (!empty($new_email_err)) ? 'is-invalid' : ''; ?>> 
        <span ><?php echo $new_email_err; ?></span>
    </div>
    </div>
    <br>
    <div>
<label> Confirme o novo email: </label>
</div>
    <div> 
        <input type="email" name="novo_email"  placeholder="Confirme o email:"<?php echo (!empty($confirm_email_err)) ? 'is-invalid' : ''; ?>> 
        <span ><?php echo $confirm_email_err; ?></span>
    </div>
    </div>
<br>
    <div>
    <input type="submit" class="cor_input" value="Redefinir">
    <button class="cor_botao_email"><a href="index.php" > Cancelar </button></a>
</div>
    </form>
</body>
</html>


