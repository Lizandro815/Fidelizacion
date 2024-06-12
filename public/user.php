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

$cliente = obtenerCliente($_SESSION['id_cliente']);
$premios = getPremios();
$beneficios = getBeneficios();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Puntos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Bienvenido, <?= htmlspecialchars($cliente['nombre']) ?></h1>
        <p>Tus puntos: <?= htmlspecialchars($cliente['puntos']) ?></p>

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
                <div class="row">
                    <?php foreach ($premios as $premio): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
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
                <div class="row">
                    <?php foreach ($beneficios as $beneficio): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($beneficio['nombre_empresa']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($beneficio['descripcion']) ?></p>
                                <p class="card-text">Puntos necesarios: <?= htmlspecialchars($beneficio['puntos_necesarios']) ?></p>
                                <?php if ($beneficio['cantidad_disponible'] > 0): ?>
                                    <?php if ($cliente['puntos'] >= $beneficio['puntos_necesarios']): ?>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#canjearBeneficioModal" data-id="<?= $beneficio['id_beneficio'] ?>" data-nombre="<?= htmlspecialchars($beneficio['nombre_empresa']) ?>" data-puntos="<?= $beneficio['puntos_necesarios'] ?>">Canjear</button>
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

    <!-- Modal para Canjear Beneficio -->
    <div class="modal fade" id="canjearBeneficioModal" tabindex="-1" aria-labelledby="canjearBeneficioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="canjearBeneficioModalLabel">Canjear Beneficio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas canjear <strong id="beneficioNombre"></strong> por <strong id="beneficioPuntos"></strong> puntos?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarCanjeBeneficio">Canjear</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
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

        $('#canjearBeneficioModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');
            var puntos = button.data('puntos');

            var modal = $(this);
            modal.find('#beneficioNombre').text(nombre);
            modal.find('#beneficioPuntos').text(puntos);
            modal.find('#confirmarCanjeBeneficio').data('id', id);
            modal.find('#confirmarCanjeBeneficio').data('puntos', puntos);
        });

        $('#confirmarCanjeBeneficio').on('click', function () {
            var id = $(this).data('id');
            var puntos = $(this).data('puntos');
            window.location.href = 'canjear_beneficio.php?id=' + id + '&puntos=' + puntos;
        });
    </script>
</body>
</html>
