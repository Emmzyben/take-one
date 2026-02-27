<?php
include 'header.php';
require_once '../includes/db.php';

$message = '';

// Handle Actions
if (isset($_GET['approve'])) {
    $pdo->prepare("UPDATE comments SET status = 'approved' WHERE id = ?")->execute([$_GET['approve']]);
    $message = '<div class="alert alert-success">Comment approved!</div>';
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM comments WHERE id = ?")->execute([$_GET['delete']]);
    $message = '<div class="alert alert-success">Comment deleted!</div>';
}

$comments = $pdo->query("SELECT c.*, p.title as post_title FROM comments c JOIN posts p ON c.post_id = p.id ORDER BY c.created_at DESC")->fetchAll();
?>

<h3>Manage Comments</h3>
<?php echo $message; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>On Post</th>
                        <th>User</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $com): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($com['post_title']); ?></strong></td>
                        <td>
                            <?php echo htmlspecialchars($com['name']); ?><br>
                            <small class="text-muted"><?php echo htmlspecialchars($com['email']); ?></small>
                        </td>
                        <td><?php echo nl2br(htmlspecialchars($com['comment'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($com['created_at'])); ?></td>
                        <td><span class="badge badge-<?php echo $com['status']; ?>"><?php echo ucfirst($com['status']); ?></span></td>
                        <td>
                            <?php if ($com['status'] == 'pending'): ?>
                                <a href="?approve=<?php echo $com['id']; ?>" class="btn btn-sm btn-success text-white"><i class="fas fa-check"></i></a>
                            <?php endif; ?>
                            <a href="?delete=<?php echo $com['id']; ?>" class="btn btn-sm btn-danger text-white" onclick="return confirm('Delete comment?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; if (empty($comments)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No comments yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
