<?php
// public/canjear_premio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'cliente') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ClienteController.php';
include '../src/controllers/PremioController.php';

$id_premio = $_GET['id'];
$puntos_necesarios = $_GET['puntos'];
$cliente = obtenerClientePorTelefono($_SESSION['telefono_movil']);
$id_cliente = $cliente['id_cliente'];

try {
    if (canjearPremio($id_premio, $id_cliente, $puntos_necesarios)) {
        $mensaje = "¡Felicitaciones! Has canjeado el premio con éxito. Presenta el siguiente código en nuestra sucursal para poder entregarte el premio";
        $imagen = "images/QR.png"; // Cambia esta ruta a la imagen que desees usar
    } else {
        $mensaje = "Lo sentimos, no se pudo canjear el premio. Inténtalo nuevamente.";
        $imagen = "images/no.png"; // Cambia esta ruta a la imagen que desees usar
    }
} catch (Exception $e) {
    $mensaje = "Ocurrio un error. Inténtalo nuevamente.";
    $imagen = "images/no.png"; // Cambia esta ruta a la imagen que desees usar
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
        <div class="alert alert-info">
            <img src="<?= htmlspecialchars($imagen) ?>" alt="Resultado" class="img-fluid" style="max-width: 200px;">
            <p class="mt-3"><?= htmlspecialchars($mensaje) ?></p>
        </div>
        <p><a href="user.php" class="btn btn-primary">Volver</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
