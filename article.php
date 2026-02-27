<?php
require_once 'includes/db.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = ? AND p.status = 'published'");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: blog.php');
    exit();
}

$pageTitle = $post['title'];
$pageDescription = substr(strip_tags($post['content']), 0, 160);
$pageImage = !empty($post['image']) ? $post['image'] : 'img/blog/1.jpg';
include 'includes/header.php';

// Handle Comment Submission
$commentMessage = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    $post_id = $post['id'];

    if (!empty($name) && !empty($email) && !empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, name, email, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$post_id, $name, $email, $comment]);
        $commentMessage = '<div class="alert alert-success">Your comment has been submitted and is awaiting approval.</div>';
    } else {
        $commentMessage = '<div class="alert alert-danger">Please fill in all fields.</div>';
    }
}
?>



	<!-- Blog section -->
	<section class="blog-section spad sec-white" style="margin-top: 50px;">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="blog-post single-post">
						<img src="<?php echo $post['image'] ?: 'img/blog/1.jpg'; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
						<div class="post-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></div>
						<h3><?php echo htmlspecialchars($post['title']); ?></h3>
						<div class="post-metas">
							<div class="post-meta">By <?php echo htmlspecialchars($post['author']); ?></div>
							<div class="post-meta">in <a href="blog.php?category=<?php echo $post['category_id']; ?>"><?php echo htmlspecialchars($post['category_name']); ?></a></div>
						</div>
						<div class="post-content">
							<?php echo $post['content']; ?>
						</div>

						<style>
							.comments-section {
								margin-top: 60px;
								padding-top: 40px;
								border-top: 2px solid #f0f0f0;
							}
							.comments-title {
								font-size: 24px;
								font-weight: 800;
								margin-bottom: 40px;
								color: #111;
								text-transform: uppercase;
								letter-spacing: 1px;
							}
							.comments-list {
								list-style: none;
								padding: 0;
								margin-bottom: 60px;
							}
							.comment-item {
								background: #fdfdfd;
								border: 1px solid #eee;
								padding: 30px;
								border-radius: 12px;
								margin-bottom: 25px;
								transition: 0.3s;
							}
							.comment-item:hover {
								border-color: #be1e2d;
								box-shadow: 0 10px 30px rgba(0,0,0,0.05);
							}
							.comment-header {
								display: flex;
								justify-content: space-between;
								align-items: center;
								margin-bottom: 15px;
							}
							.comment-author {
								font-weight: 800;
								color: #be1e2d;
								font-size: 16px;
							}
							.comment-date {
								font-size: 12px;
								color: #999;
								font-weight: 600;
							}
							.comment-body {
								font-size: 15px;
								line-height: 1.7;
								color: #444;
								margin: 0;
							}
							.comment-form-wrap {
								background: #111;
								padding: 30px;
								border-radius: 12px;
								color: #fff;
								margin-top: 30px;
							}
							.comment-form-wrap h5 {
								color: #fff;
								font-weight: 800;
								margin-bottom: 18px;
								font-size: 16px;
								text-transform: uppercase;
								letter-spacing: 1px;
							}
							.comment-form .form-control-custom {
								background: #1a1a1a;
								border: 1px solid #333;
								color: #fff;
								padding: 10px 15px;
								border-radius: 8px;
								margin-bottom: 14px;
								width: 100%;
								font-size: 14px;
								transition: 0.3s;
							}
							.comment-form .form-control-custom:focus {
								border-color: #be1e2d;
								outline: none;
								background: #222;
							}
							.comment-form .form-control-custom::placeholder {
								color: #666;
							}
							.comment-btn {
								background: #be1e2d;
								color: #fff;
								border: none;
								padding: 10px 30px;
								font-size: 13px;
								font-weight: 800;
								text-transform: uppercase;
								letter-spacing: 2px;
								border-radius: 6px;
								transition: 0.3s;
								cursor: pointer;
							}
							.comment-btn:hover {
								background: #fff;
								color: #be1e2d;
							}
						</style>

						<div class="comments-section" id="comments">
							<?php
							$commentStmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? AND status = 'approved' ORDER BY created_at DESC");
							$commentStmt->execute([$post['id']]);
							$comments = $commentStmt->fetchAll();
							?>
							<h5 class="comments-title">Responses (<?php echo count($comments); ?>)</h5>
							<?php echo $commentMessage; ?>
							
							<div class="comments-list">
								<?php foreach ($comments as $com): ?>
								<div class="comment-item">
									<div class="comment-header">
										<span class="comment-author"><?php echo htmlspecialchars($com['name']); ?></span>
										<span class="comment-date"><?php echo date('M d, Y', strtotime($com['created_at'])); ?></span>
									</div>
									<p class="comment-body"><?php echo nl2br(htmlspecialchars($com['comment'])); ?></p>
								</div>
								<?php endforeach; if (empty($comments)): ?>
								<div class="text-center py-5 bg-light rounded shadow-sm">
									<p class="text-muted mb-0">No responses yet. Be the first to share your thoughts!</p>
								</div>
								<?php endif; ?>
							</div>

							<div class="comment-form-wrap">
								<h5>Leave a Response</h5>
								<form class="comment-form" method="POST" action="#comments">
									<div class="row">
										<div class="col-md-6">
											<input type="text" name="name" class="form-control-custom" placeholder="Full Name" required>
										</div>
										<div class="col-md-6">
											<input type="email" name="email" class="form-control-custom" placeholder="E-mail Address" required>
										</div>
										<div class="col-md-12">
											<textarea name="comment" class="form-control-custom" rows="3" placeholder="Write your response here..." required></textarea>
											<button type="submit" name="submit_comment" class="comment-btn">POST RESPONSE</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 sidebar">
					<div class="sb-widget">
						<form class="sb-search" action="blog.php" method="GET">
							<input type="text" name="search" placeholder="Search stories & news..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
							<button><i class="fa fa-search"></i></button>
						</form>
					</div>
					<div class="sb-widget">
						<h2 class="sb-title">Categories</h2>
						<ul class="sb-cata-list">
							<?php
							$cats = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM posts p WHERE p.category_id = c.id AND p.status = 'published') as post_count FROM categories c ORDER BY c.name ASC")->fetchAll();
							foreach ($cats as $c):
								if ($c['post_count'] > 0):
							?>
							<li><a href="blog.php?category=<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?><span><?php echo $c['post_count']; ?></span></a></li>
							<?php 
								endif;
							endforeach; ?>
						</ul>
					</div>
					<div class="sb-widget">
						<h2 class="sb-title">Recent Posts</h2>
						<div class="latest-news-widget">
							<?php
							$recent = $pdo->query("SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 4")->fetchAll();
							foreach ($recent as $r):
							?>
							<div class="ln-item">
								<img src="<?php echo $r['image'] ?: 'img/blog-thumbs/1.jpg'; ?>" alt="" style="width: 70px; height: 70px; object-fit: cover;">
								<div class="ln-text">
									<div class="ln-date"><?php echo date('M Y', strtotime($r['created_at'])); ?></div>
									<h6><a href="article.php?slug=<?php echo $r['slug']; ?>" style="color: inherit;"><?php echo htmlspecialchars($r['title']); ?></a></h6>
								</div>
							</div>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="sb-widget">
						<h2 class="sb-title">Community Responses</h2>
						<div class="latest-comments-widget">
							<?php
							$recentComments = $pdo->query("SELECT c.*, p.title as post_title, p.slug as post_slug FROM comments c JOIN posts p ON c.post_id = p.id WHERE c.status = 'approved' ORDER BY c.created_at DESC LIMIT 4")->fetchAll();
							foreach ($recentComments as $rc):
							?>
							<div class="lc-item">
								<div class="lc-text" style="padding-left: 0;">
									<h6><?php echo htmlspecialchars($rc['name']); ?> <span>on</span> <a href="article.php?slug=<?php echo $rc['post_slug']; ?>"><?php echo htmlspecialchars($rc['post_title']); ?></a></h6>
									<div class="lc-date"><?php echo date('M Y', strtotime($rc['created_at'])); ?></div>
								</div>
							</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php include 'includes/footer.php'; ?>
