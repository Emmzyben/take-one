<?php
$pageTitle = 'Home';
$pageDescription = 'Take One is a creative-driven ecosystem dedicated to discovering, refining, and positioning exceptional talent while crafting stories that resonate with culture and endure beyond trends.';
include 'includes/header.php';
?>

	<!-- Hero section -->
	<section class="hero-section">
		<div class="hero-slider owl-carousel">
			<div class="hero-item set-bg" data-setbg="img/slider/1.jpg">
				<div class="container">
					<div class="row">
						<div class="col-lg-10 offset-lg-1">
							<h2>Talent. Stories. Impact.</h2>
							<p>Take One is a creative-driven ecosystem dedicated to discovering, refining, and positioning exceptional talent while crafting stories that resonate with culture and endure beyond trends.</p>
							<a href="talents.php" class="site-btn">View Our Talents</a>
						</div>
					</div>
				</div>
			</div>
			<div class="hero-item set-bg" data-setbg="img/slider/2.jpg">
				<div class="container">
					<div class="row">
						<div class="col-lg-10 offset-lg-1">
							<h2>Stories Crafted With Intention</h2>
							<p>Every frame, character, and narrative is guided by creative integrity, emotional resonance, and genuine audience connection.</p>
							<a href="productions.php" class="site-btn">Explore Productions</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Hero section end -->

	<!-- Section 1: WHITE — About -->
	<section class="blog-list-section spad sec-dark">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="game-title mb-0">
						<h2>More Than a Production Company</h2>
					</div>
					<br>
					<p>We are not simply creators of visual content.</p>
					<p>We are builders of careers, shapers of narratives, and architects of creative growth.</p>
					<p>Through talent management, film production, and actor development, we create the structure that allows creativity to thrive — professionally, sustainably, and powerfully.</p>
					<a href="about.php" class="site-btn" style="margin-top:20px;">Who We Are</a>
				</div>
				<div class="col-lg-6">
					<img src="img/about.jpg" alt="Take One" style="border-radius:4px; margin-top:30px;">
				</div>
			</div>
		</div>
	</section>

	<!-- Section 2: Journal -->
	<section class="blog-section spad sec-white">
		<div class="container">
			<div class="section-title text-center">
				<h2>Stories, Achievements &amp; Industry Moments</h2>
				<p style="margin-top:15px; margin-bottom:50px; max-width:700px; margin-left:auto; margin-right:auto;">Step into the world of Take One — talent milestones, productions, industry insights, and landmark achievements.</p>
			</div>
			<div class="row">
				<?php
				require_once 'includes/db.php';
				$postsStmt = $pdo->query("SELECT p.*, c.name as category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 'published' ORDER BY p.created_at DESC LIMIT 4");
				$posts = $postsStmt->fetchAll();

				if ($posts):
					foreach ($posts as $post):
				?>
				<div class="col-md-6">
					<div class="blog-post">
						<img src="<?php echo $post['image'] ?: 'img/blog/1.jpg'; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="height: 220px; object-fit: cover; width: 100%;">
						<div class="post-date"><?php echo date('M Y', strtotime($post['created_at'])); ?></div>
						<h4><?php echo htmlspecialchars($post['title']); ?></h4>
						<div class="post-metas">
							<div class="post-meta">By <?php echo htmlspecialchars($post['author']); ?></div>
							<div class="post-meta">in <a href="blog.php?category=<?php echo $post['category_id']; ?>"><?php echo htmlspecialchars($post['category_name']); ?></a></div>
						</div>
						<p><?php echo substr(strip_tags($post['content']), 0, 120) . '...'; ?></p>
						<a href="article.php?slug=<?php echo $post['slug']; ?>" class="read-more">Read More</a>
					</div>
				</div>
				<?php
					endforeach;
				else:
				?>
				<div class="col-12 text-center">
					<p>No blog posts found. Check back soon!</p>
				</div>
				<?php endif; ?>
			</div>

			<!-- View All Button -->
			<div class="text-center" style="margin-top: 20px;">
				<a href="blog.php" class="site-btn">View All Stories</a>
			</div>
		</div>
	</section>


	<!-- Section 3: WHITE — Creative Ecosystem -->
	<section class="blog-list-section spad sec-dark">
		<div class="container">
			<div class="section-title" style="margin-bottom:60px;">
				<h2>Our Creative Ecosystem</h2>
			</div>
			<div class="row">
				<!-- Take One Talents -->
				<div class="col-md-6 col-lg-3 mb-4">
					<div class="ecosystem-card" style="background: #1e1e1e; border-radius: 10px; overflow: hidden; height: 100%; border-bottom: 4px solid #be1e2d; display: flex; flex-direction: column;">
						<img src="https://images.unsplash.com/photo-1531384441138-2736e62e0919?q=80&w=500&h=350&auto=format&fit=crop" alt="Take One Talents" style="width: 100%; height: 200px; object-fit: cover;">
						<div class="p-4" style="flex-grow: 1; display: flex; flex-direction: column;">
							<h5 style="color: #fff; margin-bottom: 15px; font-weight: 700;">Take One Talents</h5>
							<p style="color: #ccc; font-size: 14px; margin-bottom: 25px; flex-grow: 1;">Representing and positioning actors for meaningful opportunities within the entertainment industry.</p>
							<a href="talents.php" class="site-btn btn-sm" style="width: 100%; text-align: center; padding: 10px 0;">View Our Talents</a>
						</div>
					</div>
				</div>
				<!-- Take One Productions -->
				<div class="col-md-6 col-lg-3 mb-4">
					<div class="ecosystem-card" style="background: #1e1e1e; border-radius: 10px; overflow: hidden; height: 100%; border-bottom: 4px solid #be1e2d; display: flex; flex-direction: column;">
						<img src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?q=80&w=500&h=350&auto=format&fit=crop" alt="Take One Productions" style="width: 100%; height: 200px; object-fit: cover;">
						<div class="p-4" style="flex-grow: 1; display: flex; flex-direction: column;">
							<h5 style="color: #fff; margin-bottom: 15px; font-weight: 700;">Take One Productions</h5>
							<p style="color: #ccc; font-size: 14px; margin-bottom: 25px; flex-grow: 1;">Developing and producing compelling visual stories shaped by craft, creativity, and cultural relevance.</p>
							<a href="productions.php" class="site-btn btn-sm" style="width: 100%; text-align: center; padding: 10px 0;">Explore Productions</a>
						</div>
					</div>
				</div>
				<!-- Take One Studios -->
				<div class="col-md-6 col-lg-3 mb-4">
					<div class="ecosystem-card" style="background: #1e1e1e; border-radius: 10px; overflow: hidden; height: 100%; border-bottom: 4px solid #be1e2d; display: flex; flex-direction: column;">
						<img src="https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=500&h=350&auto=format&fit=crop" alt="Take One Studios" style="width: 100%; height: 200px; object-fit: cover;">
						<div class="p-4" style="flex-grow: 1; display: flex; flex-direction: column;">
							<h5 style="color: #fff; margin-bottom: 15px; font-weight: 700;">Take One Studios</h5>
							<p style="color: #ccc; font-size: 14px; margin-bottom: 25px; flex-grow: 1;">Equipping actors with industry-ready tools — from acting reels and monologues to headshots and professional assets.</p>
							<a href="studios.php" class="site-btn btn-sm" style="width: 100%; text-align: center; padding: 10px 0;">Explore Take One Studios</a>
						</div>
					</div>
				</div>
				<!-- Take One TV -->
				<div class="col-md-6 col-lg-3 mb-4">
					<div class="ecosystem-card" style="background: #1e1e1e; border-radius: 10px; overflow: hidden; height: 100%; border-bottom: 4px solid #be1e2d; display: flex; flex-direction: column;">
						<img src="https://images.unsplash.com/photo-1593784991095-a205069470b6?q=80&w=500&h=350&auto=format&fit=crop" alt="Take One TV" style="width: 100%; height: 200px; object-fit: cover;">
						<div class="p-4" style="flex-grow: 1; display: flex; flex-direction: column;">
							<h5 style="color: #fff; margin-bottom: 15px; font-weight: 700;">Take One TV</h5>
							<p style="color: #ccc; font-size: 14px; margin-bottom: 25px; flex-grow: 1;">Showcasing the best compelling visual stories that resonate with audiences in their day to day lives through a creative lense.</p>
							<a href="blog.php" class="site-btn btn-sm" style="width: 100%; text-align: center; padding: 10px 0;">Watch Take One TV</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>


<?php include 'includes/footer.php'; ?>

