<?php
// public/canjear_premio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'cliente') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/PremioController.php';
include '../src/controllers/ClienteController.php';

$id_premio = $_GET['id'];
$id_cliente = $_SESSION['telefono_movil'];

$cliente = obtenerClientePorTelefono($id_cliente);

if (canjearPremio($id_premio, $cliente['id_cliente'])) {
    $mensaje = "¡Felicitaciones! Has canjeado el premio con éxito. Presenta el siguiente codigo en nuestra sucursal para poder entregarte el premio";
    $imagen = "./images/QR.png"; // Cambia esta ruta a la imagen que desees usar
} else {
    $mensaje = "Lo sentimos, no se pudo canjear el premio. Inténtalo nuevamente.";
    $imagen = "./images/no.png"; // Cambia esta ruta a la imagen que desees usar
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canjear Premio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container text-center">
        <h1 class="my-4">Canjear Premio</h1>
        <div class="alert alert-success">
            <img src="<?= htmlspecialchars($imagen) ?>" alt="Felicitaciones" class="img-fluid" style="max-width: 200px;">
            <p class="mt-3"><?= htmlspecialchars($mensaje) ?></p>
        </div>
        <p><a href="user.php" class="btn btn-primary">Volver</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
