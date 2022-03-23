<?php
session_start();

require_once "conn.php";

$new_nome = $confirm_nome = "";
$new_nome_err = $confirm_nome_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){  
    if(empty(trim($_POST["novo_nome"]))){
        $new_nome_err = "Por favor insira o novo nome.";     
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["novo_nome"]))){
        $new_nome_err = "O nome de usuário pode conter apenas letras, números e sublinhados.";
    }else{
        $sql = "SELECT id FROM crud WHERE nome = :nome";
    
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":nome", $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST["novo_nome"]);
            if($stmt->execute()){
                if($stmt->rowCount() > 0){
                    $new_nome_err = "<h4>Este nome de usuário já está em uso!</h4>";
                } else{
                    $new_nome = trim($_POST["novo_nome"]);
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            unset($stmt);
        }
    }
    
    // Validar e confirmar a senha
    if(empty(trim($_POST["confirmar_nome"]))){
        $confirm_nome_err = "Por favor, confirme o nome.";
    } else{
        $confirm_nome = trim($_POST["confirmar_nome"]);
        if(empty($new_nome_err) && ($new_nome != $confirm_nome)){
            $confirm_nome_err = "O nome não confere.";
        }
    }
    // Verifique os erros de entrada antes de atualizar o banco de dados
    if(empty($new_nome_err) && empty($confirm_nome_err)){
        // Prepare uma declaração de atualização
        $sql = "UPDATE crud SET nome = :nome WHERE id = :id";
        
        if($stmt = $pdo->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":nome", $param_nome, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            
            // Definir parâmetros
            $param_nome = $confirm_nome;
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
    <link href="style_usuario.css" rel="stylesheet">
    <title>Update</title>
</head>
<body id="pag">
    

    <div> 
        <h1> CRUD UPDATE</h1>
    </div>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="test">
    <div>
<label> Novo nome: </label>
</div>
    <div> 
        <input type="text" name="confirmar_nome"  placeholder="Nome:"<?php echo (!empty($new_nome_err)) ? 'is-invalid' : ''; ?>> 
        <span ><?php echo $new_nome_err; ?></span>
    </div>
    </div>
    <br>
    <div>
<label> Confirme o novo nome: </label>
</div>
    <div> 
        <input type="text" name="novo_nome"  placeholder="Confirme o nome:"<?php echo (!empty($confirm_nome_err)) ? 'is-invalid' : ''; ?>> 
        <span ><?php echo $confirm_nome_err; ?></span>
    </div>
    </div>
<br>
    <div>
    <input type="submit" class="cor_input"  value="Redefinir">
    <button class="cor_botao_usuario"><a href="index.php"> Cancelar </button></a>
</div>
    </form>
</body>
</html>


