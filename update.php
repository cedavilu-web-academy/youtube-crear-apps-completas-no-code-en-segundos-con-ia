<?php
require 'config/database.php';
require 'functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$producto = obtenerProductoPorId($pdo, $id);

if (!$producto) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
    $imagen = $_FILES['imagen'];

    $errors = [];
    if (empty($nombre)) $errors[] = "Nombre es requerido";
    if (empty($descripcion)) $errors[] = "Descripción es requerida";
    if (!$precio) $errors[] = "Precio inválido";
    if (!in_array($categoria, ['perro', 'gato', 'ave', 'roedor'])) $errors[] = "Categoría inválida";
    
    if (!empty($imagen['name'])) {
        $imagen_valida = validarImagen($imagen);
        if ($imagen_valida !== true) $errors[] = $imagen_valida;
    }

    if (empty($errors)) {
        $imagen_ruta = $producto['imagen'];
        
        if (!empty($imagen['name'])) {
            // Eliminar imagen anterior
            if (file_exists($producto['imagen'])) {
                unlink($producto['imagen']);
            }
            $imagen_ruta = subirImagen($imagen);
        }

        $stmt = $pdo->prepare("UPDATE productos SET 
            nombre = ?, 
            descripcion = ?, 
            precio = ?, 
            categoria = ?, 
            imagen = ?
            WHERE id = ?");
            
        $stmt->execute([$nombre, $descripcion, $precio, $categoria, $imagen_ruta, $id]);
        header("Location: index.php");
        exit();
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h2>Editar Producto</h2>
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
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="<?= $producto['precio'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria" class="form-control" required>
                <option value="perro" <?= $producto['categoria'] == 'perro' ? 'selected' : '' ?>>Perro</option>
                <option value="gato" <?= $producto['categoria'] == 'gato' ? 'selected' : '' ?>>Gato</option>
                <option value="ave" <?= $producto['categoria'] == 'ave' ? 'selected' : '' ?>>Ave</option>
                <option value="roedor" <?= $producto['categoria'] == 'roedor' ? 'selected' : '' ?>>Roedor</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Imagen Actual</label><br>
            <img src="<?= htmlspecialchars($producto['imagen']) ?>" width="100">
        </div>
        <div class="mb-3">
            <label>Nueva Imagen</label>
            <input type="file" name="imagen" accept="image/*" class="form-control">
        </div>
        <button type="submit" class="btn btn-warning">Actualizar</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>