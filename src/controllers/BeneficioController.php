<?php
// src/controllers/BeneficioController.php
include __DIR__ . '/../config/config.php';

function getBeneficios() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM Beneficios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function agregarBeneficio($nombre_empresa, $descripcion, $descuento, $cantidad_disponible, $puntos_necesarios) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Beneficios (nombre_empresa, descripcion, descuento, cantidad_disponible, puntos_necesarios) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre_empresa, $descripcion, $descuento, $cantidad_disponible, $puntos_necesarios]);
}

function eliminarBeneficio($id_beneficio) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Beneficios WHERE id_beneficio = ?");
    $stmt->execute([$id_beneficio]);
}

function actualizarBeneficio($id_beneficio, $nombre_empresa, $descripcion, $descuento, $cantidad_disponible, $puntos_necesarios) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Beneficios SET nombre_empresa = ?, descripcion = ?, descuento = ?, cantidad_disponible = ?, puntos_necesarios = ? WHERE id_beneficio = ?");
    $stmt->execute([$nombre_empresa, $descripcion, $descuento, $cantidad_disponible, $puntos_necesarios, $id_beneficio]);
}

function obtenerBeneficio($id_beneficio) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Beneficios WHERE id_beneficio = ?");
    $stmt->execute([$id_beneficio]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function reducirCantidadBeneficio($id_beneficio) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Beneficios SET cantidad_disponible = cantidad_disponible - 1 WHERE id_beneficio = ?");
    $stmt->execute([$id_beneficio]);
}
?>
