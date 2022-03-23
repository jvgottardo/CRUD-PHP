<?php
// Inicialize a sessão
session_start();

// Incluir arquivo de configuração
require_once "conn.php";

 
// Defina variáveis e inicialize com valores vazios
$new_telefone = $confirm_telefone = "";
$new_telefone_err = $confirm_telefone_err = "";
 

 
// Processando dados do formulário quando o formulário é enviado
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validar nova senha
    if(empty(trim($_POST["novo_telefone"]))){
        $new_telefone_err = "Por favor insira o novo telefone.";     
    } else{
        $new_telefone = trim($_POST["novo_telefone"]);
    }
    
    // Validar e confirmar a senha
    if(empty(trim($_POST["confirmar_telefone"]))){
        $confirm_telefone_err = "Por favor, confirme o telefone.";
    } else{
        $confirm_telefone = trim($_POST["confirmar_telefone"]);
        if(empty($new_telefone_err) && ($new_telefone != $confirm_telefone)){
            $confirm_telefone_err = "O telefone não confere.";
        }
    }
    // Verifique os erros de entrada antes de atualizar o banco de dados
    if(empty($new_telefone_err) && empty($confirm_telefone_err)){
        // Prepare uma declaração de atualização
        $sql = "UPDATE crud SET telefone = :telefone WHERE id = :id";
        
        if($stmt = $pdo->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":telefone", $param_telefone, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            
            // Definir parâmetros
            $param_telefone = $confirm_telefone;
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
    <link href="style_telefone.css" rel="stylesheet">
    <title>Update</title>
</head>
<body id="pag">
    

    <div> 
        <h1> CRUD UPDATE</h1>
    </div>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="test">
    <div>
<label> Novo telefone: </label>
</div>
    <div> 
        <input type="tel" name="confirmar_telefone" placeholder="telefone:"<?php echo (!empty($new_telefone_err)) ? 'is-invalid' : ''; ?>> 
        <span ><?php echo $new_telefone_err; ?></span>
    </div>
    </div>
    <br>
    <div>
<label> Confirme o novo telefone: </label>
</div>
    <div> 
        <input type="tel" name="novo_telefone"  placeholder="Confirme o telefone:"<?php echo (!empty($confirm_telefone_err)) ? 'is-invalid' : ''; ?>> 
        <span ><?php echo $confirm_telefone_err; ?></span>
    </div>
    </div>
<br>
    <div>
    <input type="submit" class="cor_input" value="Redefinir">
    <button><a href="index.php" class="cor_botao_telefone"> Cancelar </button></a>
</div>
    </form>
</body>
</html>


