<?php
// public/editar_beneficio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/BeneficioController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_beneficio = $_POST['id_beneficio'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $descripcion = $_POST['descripcion'];
    $descuento = $_POST['descuento'];
    $cantidad_disponible = $_POST['cantidad_disponible'];

    actualizarBeneficio($id_beneficio, $nombre_empresa, $descripcion, $descuento, $cantidad_disponible);
    header("Location: beneficios.php");
    exit();
}

$id_beneficio = $_GET['id'];
$beneficio = obtenerBeneficio($id_beneficio);
if (!$beneficio) {
    echo "Error: Beneficio no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Beneficio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Editar Beneficio</h1>
        <form action="editar_beneficio.php" method="post">
            <input type="hidden" name="id_beneficio" value="<?= htmlspecialchars($beneficio['id_beneficio']) ?>">
            <div class="form-group">
                <label for="nombre_empresa">Nombre de la Empresa:</label>
                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="<?= htmlspecialchars($beneficio['nombre_empresa']) ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?= htmlspecialchars($beneficio['descripcion']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="descuento">Descuento (%):</label>
                <input type="number" step="0.01" class="form-control" id="descuento" name="descuento" value="<?= htmlspecialchars($beneficio['descuento']) ?>" required>
            </div>
            <div class="form-group">
                <label for="cantidad_disponible">Cantidad Disponible:</label>
                <input type="number" class="form-control" id="cantidad_disponible" name="cantidad_disponible" value="<?= htmlspecialchars($beneficio['cantidad_disponible']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
        </form>
        <p><a href="beneficios.php" class="btn btn-secondary mt-3">Volver</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
