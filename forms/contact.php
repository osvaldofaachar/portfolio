
<?php
// Verifica se o método é POST (para evitar acessos diretos)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Redireciona para a página principal se não for POST
    header("Location: ../index.html"); // Ajuste o caminho se o formulário estiver em outra página
    exit();
}

// Sanitiza e obtém os dados do formulário
$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
$message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

// Validação básica
$errors = [];
if (empty($name)) {
    $errors[] = "Nome é obrigatório.";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email válido é obrigatório.";
}
if (empty($subject)) {
    $errors[] = "Assunto é obrigatório.";
}
if (empty($message)) {
    $errors[] = "Mensagem é obrigatória.";
}

// Se houver erros, redireciona com erro
if (!empty($errors)) {
    $errorMessage = implode(" ", $errors);
    header("Location: ../index.php?error=" . urlencode($errorMessage)); // Ajuste o caminho
    exit();
}

// Configurações do email
$to = "osvaldofaachar@gmail.com"; // Substitua pelo seu email
$emailSubject = "Contato via Portfólio: " . $subject; // Prefixo para identificar
$emailBody = "Nome: $name\n";
$emailBody .= "Email: $email\n";
$emailBody .= "Assunto: $subject\n\n";
$emailBody .= "Mensagem:\n$message\n";

// Headers para o email
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Envia o email
if (mail($to, $emailSubject, $emailBody, $headers)) {
    // Sucesso: redireciona com parâmetro de sucesso
    header("Location: ../index.php?success=1"); // Ajuste o caminho
} else {
    // Erro no envio: redireciona com erro
    header("Location: ../index.php?error=" . urlencode("Erro ao enviar a mensagem. Tente novamente."));
}
exit();
?>
