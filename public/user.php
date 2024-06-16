<?php
// public/user.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'cliente') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ClienteController.php';
include '../src/controllers/PremioController.php';
include '../src/controllers/BeneficioController.php';

$cliente = obtenerClientePorTelefono($_SESSION['telefono_movil']);
$premios = getPremios();
$beneficios = getBeneficios();
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Puntos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            background-color: var(--bg-color, #ffffff); /* Default white */
        }
        .card-img-top {
            height: 200px;
            object-fit: fill;
        }
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card-body {
            flex: 1;
        }
        .beneficio-card {
            background-size: cover;
            background-position: center;
            color: white;
            text-shadow: 1px 1px 2px black;
            height: 250px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .beneficio-card .card-body {
            background: rgba(0, 0, 0, 0.5);
            width: 100%;
            text-align: center;
        }
        .gold-card {
            background: linear-gradient(45deg, #d4af37, #ffd700);
            color: white;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
        }
        .gold-card h5 {
            margin: 10px 0;
        }
        .content-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .content-column {
            flex: 1;
            margin-right: 20px;
        }
        .content-column:last-child {
            margin-right: 0;
        }
        .color-picker {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .theme-color {
            background-color: var(--theme-color, #ffffff) !important;
        }
        .theme-input {
            border-color: var(--theme-color, #ffffff) !important;
        }
    </style>
</head>
<body>
    <div class="color-picker">
        <label for="colorSelect">Tema:</label>
        <select id="colorSelect" class="form-control">
            <option value="#ffffff">Blanco</option>
            <option value="#f5f5dc">Beige</option>
            <option value="#e0f7fa">Cyan claro</option>
            <option value="#ffebee">Rosa claro</option>
            <option value="#e8f5e9">Verde claro</option>
        </select>
    </div>

    <div class="container">
        <h1 class="my-4">Bienvenido a tu cuenta, <?= htmlspecialchars($cliente['nombre']) ?></h1>

        <div class="content-row">
            <div class="content-column">
                <div class="gold-card">
                    <h5>MEGACARD CLUB</h5>
                    <p>Tarjeta <?= htmlspecialchars($cliente['numero_tarjeta']) ?></p>
                </div>
            </div>
            <div class="content-column">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Puntos Acumulados</h5>
                        <p class="card-text"><?= htmlspecialchars($cliente['puntos']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="premios-tab" data-toggle="tab" href="#premios" role="tab" aria-controls="premios" aria-selected="true">Premios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="beneficios-tab" data-toggle="tab" href="#beneficios" role="tab" aria-controls="beneficios" aria-selected="false">Beneficios</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="premios" role="tabpanel" aria-labelledby="premios-tab">
                <h2 class="my-4">Premios Disponibles</h2>
                <input type="text" id="buscarPremios" class="form-control mb-4" placeholder="Buscar premios por nombre...">
                <div class="row" id="listaPremios">
                    <?php foreach ($premios as $premio): ?>
                    <div class="col-md-4 premio-item">
                        <div class="card mb-4 theme-color">
                            <img src="<?= "http://localhost/" . htmlspecialchars($premio['imagen']) ?>" class="card-img-top" alt="Imagen del Premio">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($premio['nombre_premio']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($premio['descripcion']) ?></p>
                                <p class="card-text">Puntos necesarios: <?= htmlspecialchars($premio['puntos_necesarios']) ?></p>
                                <?php if ($premio['cantidad_disponible'] > 0): ?>
                                    <?php if ($cliente['puntos'] >= $premio['puntos_necesarios']): ?>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#canjearModal" data-id="<?= $premio['id_premio'] ?>" data-nombre="<?= htmlspecialchars($premio['nombre_premio']) ?>" data-puntos="<?= $premio['puntos_necesarios'] ?>">Canjear</button>
                                    <?php else: ?>
                                    <button class="btn btn-secondary" disabled>No alcanza</button>
                                    <?php endif; ?>
                                <?php else: ?>
                                <button class="btn btn-secondary" disabled>Agotado</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="tab-pane fade" id="beneficios" role="tabpanel" aria-labelledby="beneficios-tab">
                <h2 class="my-4">Beneficios Disponibles</h2>
                <input type="text" id="buscarBeneficios" class="form-control mb-4" placeholder="Buscar beneficios por nombre...">
                <div class="row" id="listaBeneficios">
                    <?php foreach ($beneficios as $beneficio): ?>
                    <div class="col-md-4 beneficio-item">
                        <div class="card mb-4 beneficio-card" style="background-image: url('http://localhost/<?= htmlspecialchars($beneficio['imagen']) ?>');">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($beneficio['nombre_empresa']) ?></h5>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <a href="logout.php" class="btn btn-danger mt-4">Cerrar Sesión</a>
    </div>

    <!-- Modal para Canjear Premio -->
    <div class="modal fade" id="canjearModal" tabindex="-1" aria-labelledby="canjearModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="canjearModalLabel">Canjear Premio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas canjear <strong id="premioNombre"></strong> por <strong id="premioPuntos"></strong> puntos?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarCanje">Canjear</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Change background color based on user selection
        document.addEventListener('DOMContentLoaded', (event) => {
            const storedColor = localStorage.getItem('bgColor');
            if (storedColor) {
                document.body.style.backgroundColor = storedColor;
                document.documentElement.style.setProperty('--bg-color', storedColor);
                document.documentElement.style.setProperty('--theme-color', storedColor !== '#ffffff' ? storedColor : '#ffffff');
                document.getElementById('colorSelect').value = storedColor;
                toggleThemeClasses(storedColor !== '#ffffff');
            }

            document.getElementById('colorSelect').addEventListener('change', function() {
                const selectedColor = this.value;
                document.body.style.backgroundColor = selectedColor;
                document.documentElement.style.setProperty('--bg-color', selectedColor);
                document.documentElement.style.setProperty('--theme-color', selectedColor !== '#ffffff' ? selectedColor : '#ffffff');
                localStorage.setItem('bgColor', selectedColor);
                toggleThemeClasses(selectedColor !== '#ffffff');
            });
        });

        function toggleThemeClasses(apply) {
            const elements = document.querySelectorAll('.theme-color, .theme-input');
            elements.forEach(element => {
                if (apply) {
                    element.classList.add('theme-applied');
                } else {
                    element.classList.remove('theme-applied');
                }
            });
        }

        $('#canjearModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');
            var puntos = button.data('puntos');

            var modal = $(this);
            modal.find('#premioNombre').text(nombre);
            modal.find('#premioPuntos').text(puntos);
            modal.find('#confirmarCanje').data('id', id);
            modal.find('#confirmarCanje').data('puntos', puntos);
        });

        $('#confirmarCanje').on('click', function () {
            var id = $(this).data('id');
            var puntos = $(this).data('puntos');
            window.location.href = 'canjear_premio.php?id=' + id + '&puntos=' + puntos;
        });

        // Búsqueda en premios
        $('#buscarPremios').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#listaPremios .premio-item').filter(function () {
                $(this).toggle($(this).find('.card-title').text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Búsqueda en beneficios
        $('#buscarBeneficios').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#listaBeneficios .beneficio-item').filter(function () {
                $(this).toggle($(this).find('.card-title').text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
</body>
</html>
