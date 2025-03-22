<?php
function obtenerProductos($pdo) {
    $stmt = $pdo->query("SELECT * FROM productos ORDER BY creado_en DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerProductoPorId($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function validarImagen($imagen) {
    $allowed_types = ['image/jpeg', 'image/png'];
    if (!in_array($imagen['type'], $allowed_types)) {
        return "Formato de imagen no permitido (solo JPG/PNG)";
    }
    if ($imagen['size'] > 2 * 1024 * 1024) {
        return "La imagen no debe superar 2MB";
    }
    return true;
}

function subirImagen($imagen) {
    $target_dir = "uploads/";
    $target_file = $target_dir . uniqid() . '_' . basename($imagen["name"]);
    if (move_uploaded_file($imagen["tmp_name"], $target_file)) {
        return $target_file;
    }
    return false;
}
?>