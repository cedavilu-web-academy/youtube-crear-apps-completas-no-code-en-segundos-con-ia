<?php
require 'config/database.php';
require 'functions.php';

$productos = obtenerProductos($pdo);
?>

<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Productos de Mascotas</h1>
    <a href="create.php" class="btn btn-primary mb-3">Nuevo Producto</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($producto['imagen']) ?>" width="50"></td>
                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                <td>$<?= number_format($producto['precio'], 2) ?></td>
                <td><?= ucfirst($producto['categoria']) ?></td>
                <td>
                    <a href="update.php?id=<?= $producto['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="delete.php?id=<?= $producto['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>