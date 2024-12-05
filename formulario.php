<?php
// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'asistencias');

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $fecha = $_POST['fecha'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];

    // Validar los datos (opcional)
    if (empty($fecha) || empty($codigo) || empty($nombre) || empty($cantidad) || empty($precio) || empty($tipo)) {
        echo "<script>alert('Por favor, complete todos los campos.');</script>";
    } else {
        // Insertar los datos en la base de datos
        $sql = "INSERT INTO inventario (fecha, codigo, nombre, cantidad, precio, tipo) 
                VALUES ('$fecha', '$codigo', '$nombre', '$cantidad', '$precio', '$tipo')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Movimiento registrado con éxito.');</script>";
        } else {
            echo "<script>alert('Error al registrar el movimiento: " . $conn->error . "');</script>";
        }
    }
}

// Cerrar la conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8f5;
            overflow-x: hidden;
        }
        header {
            margin-bottom: 20px;
            justify-content: center;
            margin-top: 50px;
          margin-left:915px;
            
        
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(50px);
            animation: fadeIn 1.2s forwards;
        }
        h1 {
            color: #198754;
        }
        .btn {
            transition: all 0.3s ease-in-out;
        }
        .btn:hover {
            background-color: #14532d;
            transform: translateY(-3px);
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h1 class="text-center">Registrar Movimiento</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" name="codigo" id="codigo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" name="precio" id="precio" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Guardar</button>
        </form>
    </div>
    <header>
        <a href="index.html" class="btn btn-success mx-3">Volver</a>
    </header>
</body>
</html>
