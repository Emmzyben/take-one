<?php
include 'header.php';
require_once '../includes/db.php';

// Stats
$blogCount = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$catCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$commentCount = $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();

// Recent Posts
$recentPosts = $pdo->query("SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 5")->fetchAll();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h1 class="display-4 text-white"><?php echo $blogCount; ?></h1>
            <p class="text-muted mb-0">Total Blog Posts</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h1 class="display-4 text-white"><?php echo $catCount; ?></h1>
            <p class="text-muted mb-0">Categories</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-4">
            <h1 class="display-4 text-danger"><?php echo $commentCount; ?></h1>
            <p class="text-muted mb-0">Pending Comments</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Recent Blog Posts</h5>
        <a href="edit-post.php" class="btn btn-accent btn-sm">Add New Post</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentPosts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                        <td><span class="badge badge-<?php echo $post['status']; ?>"><?php echo ucfirst($post['status']); ?></span></td>
                        <td>
                            <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                            <a href="delete-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; if (empty($recentPosts)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No posts available yet.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
