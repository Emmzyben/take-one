<?php
include 'header.php';
require_once '../includes/db.php';

$message = '';

// Handle Add Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password)) {
        $message = '<div class="alert alert-danger">All fields are required.</div>';
    } elseif ($password !== $confirm) {
        $message = '<div class="alert alert-danger">Passwords do not match.</div>';
    } elseif (strlen($password) < 6) {
        $message = '<div class="alert alert-danger">Password must be at least 6 characters.</div>';
    } else {
        // Check username uniqueness
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);
        if ($check->fetch()) {
            $message = '<div class="alert alert-danger">Username already exists.</div>';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, $email]);
            $message = '<div class="alert alert-success">Admin <strong>' . htmlspecialchars($username) . '</strong> added successfully.</div>';
        }
    }
}

// Handle Delete Admin
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    // Prevent deleting yourself
    if ($del_id == $_SESSION['admin_id']) {
        $message = '<div class="alert alert-danger">You cannot delete your own account.</div>';
    } else {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$del_id]);
        $message = '<div class="alert alert-success">Admin removed.</div>';
    }
}

// Fetch all admins
$admins = $pdo->query("SELECT id, username, email, created_at FROM users ORDER BY created_at ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Admin Users</h3>
</div>

<?php echo $message; ?>

<div class="row">
    <!-- Add Admin Form -->
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color:#fff; font-weight:700;">Add New Admin</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" placeholder="e.g. editor01" required>
                    </div>
                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="admin@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="new-pwd" class="form-control" placeholder="Min. 6 characters" required>
                            <span onclick="toggleField('new-pwd','eye1')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#666;">
                                <i class="fas fa-eye" id="eye1"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <div style="position:relative;">
                            <input type="password" name="confirm_password" id="conf-pwd" class="form-control" placeholder="Repeat password" required>
                            <span onclick="toggleField('conf-pwd','eye2')" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#666;">
                                <i class="fas fa-eye" id="eye2"></i>
                            </span>
                        </div>
                    </div>
                    <button type="submit" name="add_admin" class="btn btn-accent w-100 py-2 mt-2">
                        <i class="fas fa-user-plus me-2"></i> Add Admin
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Admins Table -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color:#fff; font-weight:700;">All Admin Accounts</h5>
                <span class="badge" style="background:#be1e2d; padding:5px 12px; border-radius:20px; font-size:13px;"><?php echo count($admins); ?> Users</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($admin['username']); ?></strong>
                                    <?php if ($admin['id'] == $_SESSION['admin_id']): ?>
                                        <span style="font-size:11px; background:#be1e2d33; color:#be1e2d; padding:2px 8px; border-radius:20px; margin-left:6px;">You</span>
                                    <?php endif; ?>
                                </td>
                                <td style="color:#aaa; font-size:14px;"><?php echo htmlspecialchars($admin['email']); ?></td>
                                <td style="color:#666; font-size:13px;"><?php echo date('M d, Y', strtotime($admin['created_at'])); ?></td>
                                <td>
                                    <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                    <a href="?delete=<?php echo $admin['id']; ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Remove this admin?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php else: ?>
                                    <span style="color:#444; font-size:12px;">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-3" style="border:1px solid #333; background:#1a1a1a;">
            <div class="card-body" style="padding:20px 25px;">
                <div style="display:flex; align-items:flex-start; gap:15px;">
                    <i class="fas fa-shield-alt" style="color:#be1e2d; font-size:22px; margin-top:2px;"></i>
                    <div>
                        <strong style="color:#fff; display:block; margin-bottom:6px;">Security Note</strong>
                        <p style="color:#666; font-size:14px; margin:0; line-height:1.6;">
                            All admin users have full access to the dashboard. Only add trusted individuals.
                            Passwords are stored securely using bcrypt hashing.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleField(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>

<?php include 'footer.php'; ?>
