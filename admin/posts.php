<?php
include 'header.php';
require_once '../includes/db.php';

$posts = $pdo->query("SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Blog Posts</h3>
    <a href="edit-post.php" class="btn btn-accent">Add New Post</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                    <tr>
                        <td>
                            <img src="../<?php echo $post['image'] ?: 'img/blog/1.jpg'; ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td><strong><?php echo htmlspecialchars($post['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($post['category_name'] ?: 'Uncategorized'); ?></td>
                        <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                        <td><span class="badge badge-<?php echo $post['status']; ?>"><?php echo ucfirst($post['status']); ?></span></td>
                        <td>
                            <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                            <a href="delete-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; if (empty($posts)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No blog posts found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
