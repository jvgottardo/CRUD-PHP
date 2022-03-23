<?php

session_start();

$nome = (int)$_GET['id'];
$_SESSION['id'] = $nome;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style_update.css" rel="stylesheet">
    <title>Update</title>
</head>
<body>

<h1>
    O que deseja trocar?
</h1>

<form method="post" >

<table  style="width:25%">
    <tr>
        <th>
<label>
    <p>Nome de usuario:</p>
</label>
</th>
<th>
<label>
    <p>Email:</p>
</label>
</th>
<th>
<label>
    <p>Telefone:</p>
</label>
</th>
<th>
<label>
    <p>Senha:</p>
</label>
</th>
</tr>



<tr>
    <td>
<div>
    <button class="cor_botao_update"><a href="usuario_update.php">Nome de usuario</a></button>
</div>
</td>


<td>
<div>
    <button class="cor_botao_update"><a href="email_update.php">Email</a></button>
</div>
</td>


<td>
<div>
    <button class="cor_botao_update"><a href="telefone_update.php">Telefone</a></button>
</div>
</td>


<td>
<div>
    <button class="cor_botao_update"><a href="senha_update.php">Senha</a></button>
</div>
</td>
</table>

</form>
</body>
</html>