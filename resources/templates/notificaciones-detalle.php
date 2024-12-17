<?php
// Database connection
$conexion = new mysqli('localhost', 'root', '', 'gestoralertas');
if ($conexion->connect_error) {
    die("Connection error: " . $conexion->connect_error);
}

// Function to get dropdown options
function obtenerOpcionesDropdown($conexion, $tableName, $idField, $displayField) {
    $stmt = $conexion->prepare("SELECT $idField, $displayField FROM $tableName");
    $stmt->execute();
    $result = $stmt->get_result();

    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = $row;
    }
    return $options;
}

// Retrieve options for dropdowns
$alertas = obtenerOpcionesDropdown($conexion, 'diccionarioAlertas', 'id_AlertaDiccionario', 'nombre');
$medios = obtenerOpcionesDropdown($conexion, 'medioNotificacion', 'id_Medio', 'nombreMedio');
$clientes = obtenerOpcionesDropdown($conexion, 'proyectos', 'id_Proyecto', 'nombreCliente');

// Form handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAlerta = $_POST['alerta'];
    $idMedio = $_POST['medio'];
    $idCliente = $_POST['cliente'];
    $mensaje = "Notification for the selected alert";

    // Call stored procedure to register notification
    $stmt = $conexion->prepare("CALL P_RegistrarNotificacion(?, ?, 'Sent')");
    $stmt->bind_param('is', $idAlerta, $mensaje);
    if ($stmt->execute()) {
        $success = "Notification successfully registered.";
    } else {
        $error = "Error registering notification: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Notificación</title>

    <!-- Link to CSS-->
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/common.css">
    <link rel="stylesheet" href="/SC502-G1-Proyecto_gestion_de_Alertas/assets/css/notificaciones-detalle.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>

    <!-- Development of the common navbar for the project -->
    <nav class="navbar navbar-expand-lg" id="nav_common">
        <div class="container-fluid">
            <a class="navbar-brand" href="common.php" id="nav_logoCommon">
                <img src="/SC502-G1-Proyecto_gestion_de_Alertas/assets/media/logo.png" alt="Logo">
            </a>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="alerta-cliente.php">Alertas por cliente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notificaciones.php">Notificaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="proyectos.php">Proyectos</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Administración
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Administrar usuarios</a></li>
                            <li><a class="dropdown-item" href="#">Administrar clientes</a></li>
                            <li><a class="dropdown-item" href="#">Administrar roles</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div>
                <a class="nav-link" href="cerrar-sesion.php" id="nav_logout">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <section>
        <!-- Container -->
        <div class="container">
            <h2 class="form-title">Código de notificación:</h2>

            <!-- Botones de Editar y Guardar -->
            <div class="button-container">
                <button class="button">✏️ Editar</button>
                <button class="button">💾 Guardar</button>
                <a href="notificaciones.php">
                <button class="button">Regresar</button>
                </a>
            </div>

            <div class="form-grid" id="form_detalle">
              <!-- Primera columna -->
            <div class=" form-group" style="margin-bottom: 15px;">
                <label for="analista">Analista:</label>
                <input type="text" id="analista" name="analista">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="cliente">Cliente:</label>
                <input type="text" id="cliente" name="cliente">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="herramienta">Herramienta de monitoreo:</label>
                <input type="text" id="herramienta" name="herramienta">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="descripcion">Descripción de notificación:</label>
                <input type="text" id="descripcion" name="descripcion">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="alerta">Nombre de la Alerta:</label>
                <input type="text" id="alerta" name="alerta">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="criticidad">Criticidad:</label>
                <input type="text" id="criticidad" name="criticidad">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="dispositivo">Dispositivo:</label>
                <input type="text" id="dispositivo" name="dispositivo">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="fechaIncidencia">Fecha de incidencia:</label>
                <input type="datetime-local" id="fechaIncidencia" name="fechaIncidencia" class="uniform-field">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="fechaNotificacion">Fecha de notificación:</label>
                <input type="datetime-local" id="fechaNotificacion" name="fechaNotificacion" class="uniform-field">
            </div>
            <!-- Segunda columna -->
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="canal">Canal de comunicación:</label>
                <input type="text" id="canal" name="canal">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="grupo">Grupo / Persona notificada:</label>
                <input type="text" id="grupo" name="grupo">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="tiquete">Tiquete Speed-e:</label>
                <input type="text" id="tiquete" name="tiquete">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="vertical">Vertical de ingeniería:</label>
                <input type="text" id="vertical" name="vertical"">
                </div>
                <div class=" form-group" style="margin-bottom: 15px;">
                <label for="comentarios">Comentarios:</label>
                <textarea id="comentarios" name="comentarios" rows="4"></textarea>
            </div>
    </section>

    <!-- Development of the common footer for the project -->
    <footer class="mt-auto p-2" id="footer_common">
        <div class="container">
            <div class="col">
                <p class="lead text-center" style="font-size: 1rem;">
                    Derechos Reservados Gestor de alertas - Universidad Fidélitas &COPY; 2024
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>


</body>

</html>