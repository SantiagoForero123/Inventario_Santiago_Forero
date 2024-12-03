<?php
//conexion a la base de datos
$conn = new mysqli('localhost', 'root', '', 'asistencias');
// Variables para el filtro
$filtro_fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$filtro_codigo = isset($_GET['codigo']) ? $_GET['codigo'] : '';
$filtro_nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
// Construcción de la consulta con filtros
$sql = "SELECT * FROM inventario WHERE 1=1";
if (!empty($filtro_fecha)) {
    $sql .= " AND fecha = '$filtro_fecha'";
}
if (!empty($filtro_codigo)) {
    $sql .= " AND codigo LIKE '%$filtro_codigo%'";
}
if (!empty($filtro_nombre)) {
    $sql .= " AND nombre LIKE '%$filtro_nombre%'";
}
if (!empty($filtro_tipo)) {
    $sql .= " AND tipo = '$filtro_tipo'";
}
$result = $conn->query($sql);
// Consulta para sumar entradas y salidas
$sql_sum = "SELECT tipo, SUM(cantidad) AS total_cantidad FROM inventario WHERE 1=1";
if (!empty($filtro_fecha)) {
    $sql_sum .= " AND fecha = '$filtro_fecha'";
}
if (!empty($filtro_codigo)) {
    $sql_sum .= " AND codigo LIKE '%$filtro_codigo%'";
}
if (!empty($filtro_nombre)) {
    $sql_sum .= " AND nombre LIKE '%$filtro_nombre%'";
}
if (!empty($filtro_tipo)) {
    $sql_sum .= " AND tipo = '$filtro_tipo'";
}
$sql_sum .= " GROUP BY tipo"; // Agrupar por tipo
$result_sum = $conn->query($sql_sum);
$suma_entradas = 0;
$suma_salidas = 0;
// Procesar la suma
if ($result_sum->num_rows > 0) {
    while ($row = $result_sum->fetch_assoc()) {
        if ($row['tipo'] === 'entrada') {
            $suma_entradas = $row['total_cantidad'];
        } elseif ($row['tipo'] === 'salida') {
            $suma_salidas = $row['total_cantidad'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f2; /* Fondo blanco cálido */
            color: #2c4a33; /* Verde oscuro */
        }
        header {
            margin-bottom: 20px;
            justify-content: center;
            margin-top: 50px;
          margin-left:915px;
            
        
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }
        h1 {
            color: #4caf50; /* Verde vibrante */
        }
        .btn-primary {
            background-color: #4caf50;
            border: none;
            
        }
        .btn-primary:hover {
            background-color: #45a045;
        }
        .table thead {
            background-color: #4caf50;
            color: white;
        }
        .sumas {
            border-top: 2px solid #4caf50;
            padding-top: 10px;
            margin-top: 20px;
        }
        .sumas h5 {
            color: #2c4a33;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Reporte de Inventario</h1>
    <!-- Formulario de Filtros -->
    <form method="GET" class="row g-3 shadow p-4 bg-white rounded mt-4">
        <div class="col-md-3">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" value="<?= $filtro_fecha ?>">
        </div>
        <div class="col-md-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Buscar por código" value="<?= $filtro_codigo ?>">
        </div>
        <div class="col-md-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Buscar por nombre" value="<?= $filtro_nombre ?>">
        </div>
        <div class="col-md-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" id="tipo" class="form-select">
                <option value="">Todos</option>
                <option value="entrada" <?= $filtro_tipo === 'entrada' ? 'selected' : '' ?>>Entrada</option>
                <option value="salida" <?= $filtro_tipo === 'salida' ? 'selected' : '' ?>>Salida</option>
            </select>
        </div>
        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="reporte.php" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>
    <!-- Tabla de Resultados -->
    <table class="table table-striped shadow bg-white mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['fecha'] ?></td>
                        <td><?= $row['codigo'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td><?= $row['cantidad'] ?></td>
                        <td><?= $row['precio'] ?></td>
                        <td><?= $row['tipo'] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No se encontraron resultados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- Sumas de Entradas y Salidas -->
    <div class="sumas mt-4">
        <h5>Sumas Totales</h5>
        <p>Entradas: <strong><?= $suma_entradas ?></strong></p>
        <p>Salidas: <strong><?= $suma_salidas ?></strong></p>
    </div>
</div>

<header>
    <a href="index.html" class="btn btn-primary">Volver</a>
</header>
</body>
</html>
