<?php
// public/dashboard.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Programa de Fidelización</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Fidelización</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="clientes.php">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="premios.php">Premios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="beneficios.php">Beneficios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1 class="my-4">Bienvenido al Panel de administrador</h1>
        <p><a href="clientes.php" class="btn btn-primary">Gestionar Clientes</a></p>
        <p><a href="premios.php" class="btn btn-primary">Gestionar Premios</a></p>
        <p><a href="beneficios.php" class="btn btn-primary">Gestionar Beneficios</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
