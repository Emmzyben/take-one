<?php
include 'header.php';
require_once '../includes/db.php';

$message = '';

// Handle Add Category
if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        try {
            $stmt->execute([$name, $slug]);
            $message = '<div class="alert alert-success">Category added!</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Slug already exists!</div>';
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    $message = '<div class="alert alert-success">Category deleted!</div>';
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<h3>Blog Categories</h3>
<?php echo $message; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Add Category</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Category Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-accent w-100">Add Category</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">All Categories</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                            <td>
                                <a href="?delete=<?php echo $cat['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete category? Posts will be uncategorized.')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
