<?php
// src/controllers/ClienteController.php
include __DIR__ . '/../config/config.php';

function getClientes() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM Clientes");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function agregarCliente($telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $contrasena) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Clientes (telefono_movil, nombre, apellidos, direccion, correo_electronico, estado, ciudad, puntos, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?)");
    $stmt->execute([$telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $contrasena]);
}

function eliminarCliente($id_cliente) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Clientes WHERE id_cliente = ?");
    $stmt->execute([$id_cliente]);
}

function actualizarCliente($id_cliente, $telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $puntos, $contrasena = null) {
    global $conn;
    if ($contrasena) {
        $stmt = $conn->prepare("UPDATE Clientes SET telefono_movil = ?, nombre = ?, apellidos = ?, direccion = ?, correo_electronico = ?, estado = ?, ciudad = ?, puntos = ?, contrasena = ? WHERE id_cliente = ?");
        $stmt->execute([$telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $puntos, $contrasena, $id_cliente]);
    } else {
        $stmt = $conn->prepare("UPDATE Clientes SET telefono_movil = ?, nombre = ?, apellidos = ?, direccion = ?, correo_electronico = ?, estado = ?, ciudad = ?, puntos = ? WHERE id_cliente = ?");
        $stmt->execute([$telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $puntos, $id_cliente]);
    }
}

function obtenerCliente($id_cliente) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Clientes WHERE id_cliente = ?");
    $stmt->execute([$id_cliente]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerClientePorTelefono($telefono_movil) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Clientes WHERE telefono_movil = ?");
    $stmt->execute([$telefono_movil]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function agregarPuntos($id_cliente, $monto) {
    global $conn;
    $puntos = intval($monto / 100) * 5;
    $stmt = $conn->prepare("UPDATE Clientes SET puntos = puntos + ? WHERE id_cliente = ?");
    $stmt->execute([$puntos, $id_cliente]);
}
?>
