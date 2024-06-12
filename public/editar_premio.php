<?php
// public/editar_premio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/PremioController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_premio = $_POST['id_premio'];
    $nombre_premio = $_POST['nombre_premio'];
    $descripcion = $_POST['descripcion'];
    $puntos_necesarios = $_POST['puntos_necesarios'];
    $cantidad_disponible = $_POST['cantidad_disponible'];

    actualizarPremio($id_premio, $nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible);
    header("Location: premios.php");
    exit();
}

$id_premio = $_GET['id'];
$premio = obtenerPremio($id_premio);
if (!$premio) {
    echo "Error: Premio no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Premio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Editar Premio</h1>
        <form action="editar_premio.php" method="post">
            <input type="hidden" name="id_premio" value="<?= htmlspecialchars($premio['id_premio']) ?>">
            <div class="form-group">
                <label for="nombre_premio">Nombre del Premio:</label>
                <input type="text" class="form-control" id="nombre_premio" name="nombre_premio" value="<?= htmlspecialchars($premio['nombre_premio']) ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?= htmlspecialchars($premio['descripcion']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="puntos_necesarios">Puntos Necesarios:</label>
                <input type="number" class="form-control" id="puntos_necesarios" name="puntos_necesarios" value="<?= htmlspecialchars($premio['puntos_necesarios']) ?>" required>
            </div>
            <div class="form-group">
                <label for="cantidad_disponible">Cantidad Disponible:</label>
                <input type="number" class="form-control" id="cantidad_disponible" name="cantidad_disponible" value="<?= htmlspecialchars($premio['cantidad_disponible']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
        </form>
        <p><a href="premios.php" class="btn btn-secondary mt-3">Volver</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
