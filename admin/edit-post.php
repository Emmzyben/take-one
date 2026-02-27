<?php
include 'header.php';
require_once '../includes/db.php';

$id = $_GET['id'] ?? null;
$message = '';
$post = [
    'title' => '',
    'category_id' => '',
    'content' => '',
    'image' => '',
    'status' => 'published',
    'slug' => ''
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $slug = $_POST['slug'] ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    
    // Image Handling
    $image_path = $post['image'];
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../img/blog/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_path'] ?? $_FILES['image']['tmp_name'], $target_file)) {
            $image_path = "img/blog/" . $image_name;
        }
    }

    try {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE posts SET title=?, category_id=?, content=?, status=?, slug=?, image=? WHERE id=?");
            $stmt->execute([$title, $category_id, $content, $status, $slug, $image_path, $id]);
            $message = '<div class="alert alert-success">Post updated!</div>';
        } else {
            $stmt = $pdo->prepare("INSERT INTO posts (title, category_id, content, status, slug, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $category_id, $content, $status, $slug, $image_path]);
            $id = $pdo->lastInsertId();
            $message = '<div class="alert alert-success">Post created!</div>';
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Error: Slug might already exist.</div>';
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><?php echo $id ? 'Edit Post' : 'Add New Post'; ?></h3>
    <a href="index.php" class="btn btn-outline-light btn-sm">Back to List</a>
</div>

<?php echo $message; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label>Post Title</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Content</label>
                        <textarea name="content" id="post-content" class="form-control" rows="15" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label>Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $post['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Custom Slug (Title based by default)</label>
                        <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($post['slug']); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Featured Image</label>
                        <?php if ($post['image']): ?>
                            <div class="mb-2"><img src="../<?php echo $post['image']; ?>" class="img-fluid rounded" style="max-height: 150px;"></div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-accent w-100 py-3 mt-3">SAVE POST</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- TinyMCE Rich Text Editor -->
<script src="https://cdn.tiny.cloud/1/d4nazmi1l2kcvlyhb06fgwofgeyd1s8rgrl0ql1hmu3fh3s0/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#post-content',
    height: 500,
    skin: 'oxide-dark',
    content_css: 'dark',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
        'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
        'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | ' +
             'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
             'bullist numlist outdent indent | link image media | ' +
             'removeformat | code fullscreen | help',
    menubar: 'file edit view insert format tools table help',
    block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Blockquote=blockquote; Code=pre',
    content_style: `
        body {
            font-family: Roboto, Arial, sans-serif;
            font-size: 16px;
            color: #e0e0e0;
            background: #1a1a1a;
            line-height: 1.8;
            padding: 15px;
        }
        a { color: #be1e2d; }
        h1, h2, h3, h4 { color: #ffffff; }
        blockquote {
            border-left: 4px solid #be1e2d;
            margin-left: 0;
            padding-left: 20px;
            color: #aaa;
            font-style: italic;
        }
        pre {
            background: #111;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
        }
    `,
    setup: function(editor) {
        // When form submits, push TinyMCE HTML content into the textarea
        editor.on('change', function() {
            editor.save();
        });
    }
});
</script>

<?php include 'footer.php'; ?>
