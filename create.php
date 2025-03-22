<?php
require 'config/database.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
    $imagen = $_FILES['imagen'];

    // Validaciones
    $errors = [];
    if (empty($nombre)) $errors[] = "Nombre es requerido";
    if (empty($descripcion)) $errors[] = "Descripción es requerida";
    if (!$precio) $errors[] = "Precio inválido";
    if (!in_array($categoria, ['perro', 'gato', 'ave', 'roedor'])) $errors[] = "Categoría inválida";
    
    $imagen_valida = validarImagen($imagen);
    if ($imagen_valida !== true) $errors[] = $imagen_valida;

    if (empty($errors)) {
        $imagen_ruta = subirImagen($imagen);
        if ($imagen_ruta) {
            $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria, imagen) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $precio, $categoria, $imagen_ruta]);
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Error al subir la imagen";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h2>Crear Producto</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria" class="form-control" required>
                <option value="perro">Perro</option>
                <option value="gato">Gato</option>
                <option value="ave">Ave</option>
                <option value="roedor">Roedor</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Imagen</label>
            <input type="file" name="imagen" accept="image/*" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>