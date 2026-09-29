<?php

require_once __DIR__ . '/../controller/AlojamientoController.php';

$controller = new AlojamientoController();
$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->registrarIngreso($_POST);
}

$precios = $controller->preciosVigentes();
$tipoSeleccionado = $_POST['tipo_vehiculo'] ?? '';
$patenteCargada = ($resultado !== null && !$resultado['ok']) ? ($_POST['patente'] ?? '') : '';

function e(string $texto): string{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar ingreso - Estacionamiento Roldán</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 2rem; }
        .tarjeta { max-width: 420px; margin: 0 auto; background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.15); }
        h1 { font-size: 1.3rem; margin-top: 0; }
        .local { color: #6b7280; font-size: .9rem; margin-bottom: 1rem; }
        label { display: block; margin: 1rem 0 .3rem; font-weight: bold; }
        input[type=text] { width: 100%; padding: .5rem; box-sizing: border-box; text-transform: uppercase; }
        .tipos label { display: inline-block; font-weight: normal; margin-right: 1rem; }
        #precio { margin-top: .8rem; padding: .5rem; background: #eef2ff; border-radius: 4px; min-height: 1.2rem; }
        button { margin-top: 1.2rem; padding: .6rem 1.2rem; cursor: pointer; }
        .mensaje { padding: .7rem; border-radius: 4px; margin-bottom: 1rem; }
        .ok { background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
<div class="tarjeta">
    <h1>Registrar ingreso</h1>
    <div class="local">Estacionamiento Roldán</div>

    <?php if ($resultado !== null): ?>
        <div class="mensaje <?= $resultado['ok'] ? 'ok' : 'error' ?>">
            <?= e($resultado['mensaje']) ?>
            <?php if ($resultado['ok']): ?>
                <br>Patente: <strong><?= e($resultado['alojamiento']->getPatente()) ?></strong>
                | Tipo: <?= e($resultado['alojamiento']->getTipoVehiculo()) ?>
                | Hora de ingreso: <?= e($resultado['alojamiento']->getHoraIngreso()->format('d/m/Y H:i')) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="ingreso.php">
        <label for="patente">Patente</label>
        <input type="text" id="patente" name="patente" maxlength="12" value="<?= e($patenteCargada) ?>" required>

        <label>Tipo de vehículo</label>
        <div class="tipos">
            <label><input type="radio" name="tipo_vehiculo" value="auto" <?= $tipoSeleccionado === 'auto' ? 'checked' : '' ?> required> Auto</label>
            <label><input type="radio" name="tipo_vehiculo" value="camioneta" <?= $tipoSeleccionado === 'camioneta' ? 'checked' : '' ?>> Camioneta</label>
            <label><input type="radio" name="tipo_vehiculo" value="moto" <?= $tipoSeleccionado === 'moto' ? 'checked' : '' ?>> Moto</label>
        </div>

        <div id="precio"></div>

        <button type="submit">Registrar ingreso</button>
    </form>
</div>

<script>
    // Precios vigentes por tipo, cargados desde la base de datos.
    const precios = <?= json_encode($precios) ?>;
    const cajaPrecio = document.getElementById('precio');

    function mostrarPrecio() {
        const elegido = document.querySelector('input[name="tipo_vehiculo"]:checked');
        if (!elegido) { cajaPrecio.textContent = ''; return; }
        const valor = precios[elegido.value];
        cajaPrecio.textContent = valor === null
            ? 'Todavía no hay un precio cargado para este tipo.'
            : 'Precio por hora: $' + Number(valor).toLocaleString('es-AR', { minimumFractionDigits: 2 });
    }

    document.querySelectorAll('input[name="tipo_vehiculo"]').forEach(function (radio) {
        radio.addEventListener('change', mostrarPrecio);
    });
    mostrarPrecio();
</script>
</body>
</html>
