<?php
// Inicialize a sessão
session_start();

 
// Incluir arquivo de configuração
require_once "conn.php";
 
// Defina variáveis e inicialize com valores vazios
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processando dados do formulário quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validar nova senha
    if(empty(trim($_POST["nova_senha"]))){
        $new_password_err = "Por favor insira a nova senha.";     
    } elseif(strlen(trim($_POST["nova_senha"])) < 6){
        $new_password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $new_password = trim($_POST["nova_senha"]);
    }
    
    // Validar e confirmar a senha
    if(empty(trim($_POST["confirmar_senha"]))){
        $confirm_password_err = "Por favor, confirme a senha.";
    } else{
        $confirm_password = trim($_POST["confirmar_senha"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "A senha não confere.";
        }
    }
        
    // Verifique os erros de entrada antes de atualizar o banco de dados
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare uma declaração de atualização
        $sql = "UPDATE crud SET senha = :senha WHERE id = :id";
        
        if($stmt = $pdo->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":senha", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            
            // Definir parâmetros
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
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
    <link href="style_senha.css" rel="stylesheet">
    <title>Update</title>
</head>
<body id="pag">
    

    <div> 
        <h1> CRUD UPDATE</h1>
    </div>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="test">
    <div>
<label> Nova senha </label>
</div>
    <div> 
        <input type="password" name="nova_senha"  id="senha" placeholder="Senha:"<?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?> value=<?php echo $new_password; ?>> 
        <span ><?php echo $new_password_err; ?></span>
    </div>
    </div>
    <p ><input type="checkbox" onclick="myFunction()" > Mostrar Senha </p>
    <div>
<label> Confirme a nova senha </label>
</div>
    <div> 
        <input type="password" name="confirmar_senha"  placeholder="Confirme a senha:"<?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>> 
        <span ><?php echo $confirm_password_err; ?></span>
    </div>
    </div>
    
    <input type="submit" class="cor_input"  value="Redefinir">
    <button class="cor_botao_senha"><a href="index.php"> Cancelar </button></a>

</body>
</html>
<script>

function myFunction() {
    var x = document.getElementById("senha");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
    }
</script>