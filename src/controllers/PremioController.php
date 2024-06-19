<?php
// src/controllers/PremioController.php
include __DIR__ . '/../config/config.php';

function getPremios() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM Premios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function agregarPremio($nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Premios (nombre_premio, descripcion, puntos_necesarios, cantidad_disponible) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible]);
}

function eliminarPremio($id_premio) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Premios WHERE id_premio = ?");
    $stmt->execute([$id_premio]);
}

function actualizarPremio($id_premio, $nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Premios SET nombre_premio = ?, descripcion = ?, puntos_necesarios = ?, cantidad_disponible = ? WHERE id_premio = ?");
    $stmt->execute([$nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible, $id_premio]);
}

function obtenerPremio($id_premio) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Premios WHERE id_premio = ?");
    $stmt->execute([$id_premio]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function canjearPremio($id_premio, $id_cliente) {
    global $conn;
    $conn->beginTransaction();

    // Obtener información del premio
    $stmt = $conn->prepare("SELECT * FROM Premios WHERE id_premio = ?");
    $stmt->execute([$id_premio]);
    $premio = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($premio && $premio['cantidad_disponible'] > 0) {
        // Reducir cantidad disponible del premio
        $stmt = $conn->prepare("UPDATE Premios SET cantidad_disponible = cantidad_disponible - 1 WHERE id_premio = ?");
        $stmt->execute([$id_premio]);

        // Obtener puntos del cliente
        $stmt = $conn->prepare("SELECT puntos FROM Clientes WHERE id_cliente = ?");
        $stmt->execute([$id_cliente]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        // Restar puntos del cliente
        $stmt = $conn->prepare("UPDATE Clientes SET puntos = puntos - ? WHERE id_cliente = ?");
        $stmt->execute([$premio['puntos_necesarios'], $id_cliente]);

        $conn->commit();
        return true;
    } else {
        $conn->rollBack();
        return false;
    }
}

//registra premios-cleinte canjeados 
function registrarCanjes($id_cliente, $id_premio, $fecha){
    // Obtener la fecha actual
    global $conn;
    $stmt = $conn->prepare("INSERT INTO canjes (id_cliente, id_premio, fecha_canje) VALUES (?, ?,?)");
    $stmt->execute([$id_cliente, $id_premio, $fecha]);
}

//obtiene todos los canjes hecho por el cliente
function obtenerCanjesCliente($id_cliente) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT c.id_cliente, c.id_premio, c.fecha_canje, p.nombre_premio
        FROM canjes c
        JOIN premios p ON c.id_premio = p.id_premio
        WHERE c.id_cliente = ?
    ");
    $stmt->execute([$id_cliente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function obtenerCanjesAdmin() {
    global $conn;
    $stmt = $conn->prepare("
        SELECT c.id_cliente, cl.nombre, cl.apellidos, c.id_premio, p.nombre_premio, c.fecha_canje
        FROM canjes c
        JOIN premios p ON c.id_premio = p.id_premio
        JOIN clientes cl ON c.id_cliente = cl.id_cliente
    ");
    $stmt->execute([]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>
