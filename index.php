<?php


include_once("conn.php");

$email = $email_err = "";
$username = $password = $telefone = "";
$username_err = $password_err = $telefone_err = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){
if(empty(trim($_POST["nome"]))){
        $username_err = "Por favor coloque um nome de usuário.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["nome"]))){
        $username_err = "O nome de usuário pode conter apenas letras, números e sublinhados.";
    }else{
        // Prepare uma declaração selecionada
        $sql = "SELECT id FROM crud WHERE nome = :nome";
        
        if($stmt = $pdo->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":nome", $param_username, PDO::PARAM_STR);
            
            // Definir parâmetros
            $param_username = trim($_POST["nome"]);
            
            // Tente executar a declaração preparada
            if($stmt->execute()){
                if($stmt->rowCount() > 0){
                    $username_err = "Este nome de usuário já está em uso.";
                } else{
                    $username = trim($_POST["nome"]);
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            // Fechar declaração
            unset($stmt);
        }
    }
    
    // Validar email
    if(empty(trim($_POST["email"]))){
        $email_err = "Por favor insira um email.";     
    } 
    else{
        $email = trim($_POST["email"]);


    // Validar telefone
    if(empty(trim($_POST["telefone"]))){
        $telefone_err = "Por favor insira um numero para contato";     
    } elseif(strlen(trim($_POST["telefone"])) < 9){
        $telefone_err = "insira um numero de telefone valido";
    } else{
        $telefone = trim($_POST["telefone"]);
    }
    }

    // Validar senha
    if(empty(trim($_POST["senha"]))){
        $password_err = "Por favor insira uma senha.";     
    } elseif(strlen(trim($_POST["senha"])) < 6){
        $password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $password = trim($_POST["senha"]);
    }

    if(empty($username_err) && empty($email_err) && empty($telefone_err) && empty($password_err)){
        
        // Prepare uma declaração de inserção
        $sql = "INSERT INTO crud (nome, email, telefone, senha) VALUES (:nome, :email, :telefone, :senha)";
         
        if($stmt = $pdo->prepare($sql)){
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":nome", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":telefone", $param_telefone, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $param_password, PDO::PARAM_STR);
            
            // Definir parâmetros
            $param_username = $username;
            $param_email = $email;
            $param_telefone = $telefone;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            // Tente executar a declaração preparada
            if($stmt->execute()){
                  header('location:index.php');
                
            } else{
                echo "<script>alert('OPS, algo deu errado!')</script>";
                header ("location: index.php");
            }

            // Fechar declaração
            unset($stmt);
        }
    }
}

if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $sql = $pdo->prepare("DELETE FROM crud WHERE id= :id");
    $sql->bindParam(":id", $id, PDO::PARAM_STR);
    $sql->execute();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>CRUD</title>
</head>
<body>
    

    <div> 
        <h1> CRUD</h1>
    </div>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div> 
    <label> Insira um nome de usuario: </label>
            <input type="text" name="nome" class="nome" placeholder="Nome de usuarios:"<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?> value=<?php echo $username; ?>> 
                    <span class="text-muted"><?php echo $username_err; ?></span>
    </div>

    <br>

    <div> 
    <label> Insira seu email: </label>
            <input type="email" name="email" class="email" placeholder="Email:"<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?> value=<?php echo $email; ?>> 
                    <span class="text-muted"><?php echo $email_err; ?></span>
    </div>

    <br>

    <div> 
    <label> Insira seu telefone com o seu DD: </label>
            <input type="tel" name="telefone" class="telefone" placeholder="Telefone:"<?php echo (!empty($telefone_err)) ? 'is-invalid' : ''; ?>value=<?php echo $password; ?>>
            <span class="text-muted"><?php echo $telefone_err; ?></span>
    </div>

    <br>

    <div> 
    <label> Insira sua senha: </label>
            <input type="password" name="senha" class="senha" id ="senha" placeholder="Senha:" <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>value=<?php echo $password; ?>>
            <span class="text-muted"><?php echo $password_err; ?></span>
            <br>
            <p><input type="checkbox" onclick="myFunction()" > Mostrar Senha </p>
    </div>
    
    <div>
    <input type="submit" value="Enviar"> 
    </div>
    </form>



<hr> 


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

<?php


 $sql = $pdo->prepare("SELECT * FROM crud");
 $sql->execute();

 $fetchClientes = $sql->fetchAll();

 foreach($fetchClientes as $key => $value){
     echo '<a href="?delete='.$value['id'].'"> (X) </a>';
     echo ' <table>' .  ' <tr> <strong>Nome:</strong>' . '<td>'. '<u>'  .$value['nome'].'</u>' . '</td>' . '</tr>'.'</table> ' ;
     echo ' <table>' .  ' <tr> <strong>Email:</strong>' . '<td>'. '<u>' .$value['email']. '</u>'.'</td>' . '</tr>'.'</table> ' ;
     echo ' <table>' .  ' <tr> <strong>Telefone: </strong>' . '<td>'. $value['telefone'].'</td>' . '</tr>'.'</table> ' ;
     echo '<hr>';
 }
?>