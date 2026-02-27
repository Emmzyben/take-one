<?php
require_once 'includes/db.php';

$pageTitle = 'Blog';
$pageDescription = 'A living archive of stories, milestones, insights, and industry moments from Take One.';
include 'includes/header.php';
?>

	<!-- Page top section -->
	<section class="page-top-section set-bg" data-setbg="img/header-bg/1.jpg">
		<div class="container">
			<h2>The Take One Blog</h2>
			<p style="color:#ccc; margin-top:15px; font-size:18px;">Stories. Milestones. Industry Moments.</p>
		</div>
	</section>

	<!-- Blog section -->
	<section class="blog-section spad sec-white">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<?php
					$where = "WHERE status = 'published'";
					$params = [];

					if (isset($_GET['category'])) {
						$where .= " AND category_id = ?";
						$params[] = $_GET['category'];
					}

					if (isset($_GET['search'])) {
						$where .= " AND (title LIKE ? OR content LIKE ?)";
						$search = "%" . $_GET['search'] . "%";
						$params[] = $search;
						$params[] = $search;
					}

					// Pagination logic
					$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
					$perPage = 6;
					$offset = ($page - 1) * $perPage;

					$countStmt = $pdo->prepare("SELECT COUNT(*) FROM posts $where");
					$countStmt->execute($params);
					$totalPosts = $countStmt->fetchColumn();
					$totalPages = ceil($totalPosts / $perPage);

					$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id $where ORDER BY p.created_at DESC LIMIT $perPage OFFSET $offset");
					$stmt->execute($params);
					$posts = $stmt->fetchAll();

					if (empty($posts)):
					?>
					<div class="text-center py-5">
						<h3>No posts found.</h3>
						<p>Try searching for something else or browse categories.</p>
						<a href="blog.php" class="site-btn">View All Posts</a>
					</div>
					<?php else: ?>
                        <!-- Main Content Area -->
                        <?php 
                        $is_standard_view = (!isset($_GET['page']) || $_GET['page'] == 1) && !isset($_GET['category']) && !isset($_GET['search']);
                        
                        foreach ($posts as $index => $post): 
                            if ($index == 0 && $is_standard_view): 
                        ?>
                            <!-- Featured Post -->
                            <div class="blog-post featured-post">
                                <img src="<?php echo $post['image'] ?: 'img/blog/1.jpg'; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                <div class="post-date"><?php echo date('M Y', strtotime($post['created_at'])); ?></div>
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <div class="post-metas">
                                    <div class="post-meta">By <?php echo htmlspecialchars($post['author']); ?></div>
                                    <div class="post-meta">in <a href="blog.php?category=<?php echo $post['category_id']; ?>"><?php echo htmlspecialchars($post['category_name']); ?></a></div>
                                </div>
                                <p><?php echo substr(strip_tags($post['content']), 0, 300) . '...'; ?></p>
                                <a href="article.php?slug=<?php echo $post['slug']; ?>" class="site-btn">Read More</a>
                            </div>
                            <div class="row"> <!-- Start sub-grid row -->
                        <?php 
                            else: 
                                // On page 1, if we just finished post 0, we already opened a row.
                                // If we are on page 2+, we need to open a row at the very start of the loop.
                                if (!$is_standard_view && $index == 0) {
                                    echo '<div class="row">';
                                }
                        ?>
                            <div class="col-md-6">
                                <div class="blog-post">
                                    <img src="<?php echo $post['image'] ?: 'img/blog/2.jpg'; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="height: 200px; object-fit: cover; width: 100%;">
                                    <div class="post-date"><?php echo date('M Y', strtotime($post['created_at'])); ?></div>
                                    <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                                    <div class="post-metas">
                                        <div class="post-meta">By <?php echo htmlspecialchars($post['author']); ?></div>
                                        <div class="post-meta">in <a href="blog.php?category=<?php echo $post['category_id']; ?>"><?php echo htmlspecialchars($post['category_name']); ?></a></div>
                                    </div>
                                    <p><?php echo substr(strip_tags($post['content']), 0, 100) . '...'; ?></p>
                                    <a href="article.php?slug=<?php echo $post['slug']; ?>" class="read-more">Read More</a>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 

                        // Close the row if any row was opened
                        // Row is opened if: (is_standard_view && count > 1) OR (!is_standard_view && count > 0)
                        if (($is_standard_view && count($posts) > 1) || (!$is_standard_view && count($posts) > 0)) {
                            echo '</div>';
                        }
                        ?>
						
						<?php if ($totalPages > 1): ?>
						<div class="site-pagination">
							<?php for ($i = 1; $i <= $totalPages; $i++): ?>
							<a href="?page=<?php echo $i; ?><?php echo isset($_GET['category']) ? '&category='.$_GET['category'] : ''; ?><?php echo isset($_GET['search']) ? '&search='.$_GET['search'] : ''; ?>" class="<?php echo $page == $i ? 'active' : ''; ?>"><?php echo sprintf("%02d", $i); ?>.</a>
							<?php endfor; ?>
						</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="col-lg-4 sidebar">
					<div class="sb-widget">
						<form class="sb-search" method="GET" action="blog.php">
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
								if ($c['post_count'] > 0 || isset($_GET['category'])):
							?>
							<li><a href="blog.php?category=<?php echo $c['id']; ?>" class="<?php echo isset($_GET['category']) && $_GET['category'] == $c['id'] ? 'text-danger' : ''; ?>"><?php echo htmlspecialchars($c['name']); ?><span><?php echo $c['post_count']; ?></span></a></li>
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
						<h2 class="sb-title">From The Community</h2>
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
