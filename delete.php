<?php
require 'config/database.php';
require 'functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit();
}

$producto = obtenerProductoPorId($pdo, $id);

if ($producto) {
    // Eliminar imagen
    if (file_exists($producto['imagen'])) {
        unlink($producto['imagen']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit();
?>