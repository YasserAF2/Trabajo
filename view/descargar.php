<?php
// Asigna la variable de sesión si no está definida
if (!isset($_SESSION['correo'])) {
    $_SESSION['correo'] = $correo;
}

// Verifica si la variable de sesión está definida
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}


if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filePath = __DIR__ . '\documentos\\' . basename($file); // Asegúrate de que la ruta sea correcta y segura

    if (file_exists($filePath)) {
        // Establece los encabezados para la descarga
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "El archivo no existe: " . $filePath;
    }
} else {
    echo "Archivo no especificado.";
}

