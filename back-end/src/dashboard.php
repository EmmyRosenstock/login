<?php
session_start();

if (!isset($_SESSION['users_id'])) {
    header('Location: index.php');
    exit;
}

// Exibir dashboard para o usuário logado
?>
<h1>Dashboard</h1>
<p>Bem-vindo, <?php echo $_SESSION['users_id']; ?>!</p>