<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Take One' : 'Take One - Crafting Talent. Creating Stories. Shaping Impact.'; ?></title>
	<meta charset="UTF-8">
	<meta name="description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Take One is a creative-driven ecosystem dedicated to discovering, refining, and positioning exceptional talent while crafting stories that resonate with culture.'; ?>">
	<meta name="keywords" content="take one, talent management, film production, actor development, African entertainment, Lagos">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php if (isset($pageImage)): ?>
	<?php
		$siteUrl   = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
		$ogTitle   = isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Take One' : 'Take One';
		$ogDesc    = isset($pageDescription) ? htmlspecialchars($pageDescription) : '';
		$ogImage   = strpos($pageImage, 'http') === 0 ? $pageImage : $siteUrl . '/' . ltrim($pageImage, '/');
		$ogUrl     = $siteUrl . $_SERVER['REQUEST_URI'];
	?>
	<!-- Open Graph / Social Sharing -->
	<meta property="og:type"        content="article">
	<meta property="og:url"         content="<?php echo $ogUrl; ?>">
	<meta property="og:title"       content="<?php echo $ogTitle; ?>">
	<meta property="og:description" content="<?php echo $ogDesc; ?>">
	<meta property="og:image"       content="<?php echo htmlspecialchars($ogImage); ?>">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:site_name"   content="Take One">

	<!-- Twitter Card -->
	<meta name="twitter:card"        content="summary_large_image">
	<meta name="twitter:title"       content="<?php echo $ogTitle; ?>">
	<meta name="twitter:description" content="<?php echo $ogDesc; ?>">
	<meta name="twitter:image"       content="<?php echo htmlspecialchars($ogImage); ?>">
	<?php endif; ?>

	<!-- Favicon -->
	<link href="img/favicon.ico" rel="shortcut icon" />

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i&display=swap" rel="stylesheet">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/font-awesome.min.css" />
	<link rel="stylesheet" href="css/magnific-popup.css" />
	<link rel="stylesheet" href="css/owl.carousel.min.css" />
	<link rel="stylesheet" href="css/animate.css" />
	<link rel="stylesheet" href="css/slicknav.min.css" />

	<!-- Main Stylesheets -->
	<link rel="stylesheet" href="css/style.css" />

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<!-- Page Preloder -->
	<div id="preloder">
		<div class="loader"></div>
	</div>

	<!-- Header section -->
	<header class="header-section">
		<a href="index.php" class="site-logo">
			<img src="img/logo.png" alt="Take One">
		</a>
		<ul class="main-menu">
			<li><a href="index.php">Home</a></li>
			<li><a href="about.php">About</a></li>
			<li><a href="talents.php">Talents</a></li>
			<li><a href="productions.php">Productions</a></li>
			<li><a href="studios.php">Studios</a></li>
			<li><a href="blog.php">Blog</a></li>
			<li><a href="contact.php">Contact</a></li>
		</ul>
	</header>
	<!-- Header section end -->
