<?php namespace InstaPlanner; defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package InstaPlanner
 *
 * @author Leszek Pomianowski
 * @copyright Copyright (c) 2020, RapidDev
 * @license https://opensource.org/licenses/MIT
 * @link https://rdev.cc/
 */
?>
<!DOCTYPE html>
<html>
	<head lang="en" role="contentinfo" dir="ltr" xmlns:og="http://ogp.me/ns#" xmlns:fb="//www.facebook.com/2008/fbml" itemscope="" itemtype="http://schema.org/WebPage" class="" role="banner" user-nonce="<?php echo $this->body_nonce; ?>">
		<title><?php echo $this->Title(); ?></title>
		<meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=5, viewport-fit=cover, user-scalable=0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="google" value="notranslate"/>
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="msapplication-starturl" content="/">
		<meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
		<meta name="apple-mobile-web-app-status-bar-style" content="#0077d4">
		<meta name="theme-color" content="#0077d4">
		<link rel="mask-icon" sizes="any" href="<?php echo $this->GetImage('instaplaner-fav-256.png') ?>" color="#0077d4">
		<link rel="icon" href="<?php echo $this->GetImage('instaplaner-fav-192.png') ?>" sizes="192x192" />
		<link rel="icon" href="<?php echo $this->GetImage('instaplaner-fav-64.png') ?>" sizes="64x64" />
		<link rel="icon" href="<?php echo $this->GetImage('instaplaner-fav-32.png') ?>" sizes="32x32" />
		<link rel="apple-touch-icon-precomposed" href="<?php echo $this->GetImage('instaplaner-fav-256.png') ?>">
		<link rel="shortcut icon" href="<?php echo $this->GetImage('instaplaner-fav-256.png') ?>" type="image/x-icon">
		<meta name="msapplication-TileImage" content="<?php echo $this->GetImage('instaplaner-fav-256.png') ?>" />
		<meta name="description" content="Forward is a link shortener created by RapidDev." />
		<link rel="canonical" href="<?php echo $this->baseurl; ?>" />
<?php foreach ($this->prefetch as $dns): ?>
		<link rel="dns-prefetch" href="<?php echo $dns; ?>" />
<?php endforeach ?>
<?php foreach ($this->styles as $style): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $style[0] . (isset($style[2]) ? '?ver=' . $style[2] : ''); ?>" integrity="<?php echo $style[1]; ?>" crossorigin="anonymous" />
<?php endforeach ?>
		<meta name="twitter:card" content="summary">
		<meta property="og:title" content="InstaPlaner - Schedule your Instagram posts">
		<meta property="og:site_name" content="InstaPlaner" />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="<?php echo $this->baseurl; ?>" />
		<script type="application/ld+json" nonce="<?php echo $this->js_nonce; ?>">
			{"@context":"https://schema.org","@graph":[{"@type":"WebSite","@id":"<?php echo $this->baseurl; ?>#website","url":"<?php echo $this->baseurl; ?>","name":"Forward","description":"Schedule your Instagram posts.","inLanguage":"pl-PL"},{"@type":"ImageObject","@id":"<?php echo $this->baseurl; ?>#primaryimage","inLanguage":"pl-PL","url":"<?php echo $this->GetImage('instaplaner-fav-256.png') ?>","width":256,"height":256,"caption":"Forward"},{"@type":"WebPage","@id":"<?php echo $this->baseurl; ?>#webpage","url":"<?php echo $this->baseurl; ?>","name":"Forward - Link shortener","isPartOf":{"@id":"<?php echo $this->baseurl; ?>#website"},"primaryImageOfPage":{"@id":"<?php echo $this->baseurl; ?>#primaryimage"},"datePublished":"<?php echo date(DATE_ATOM); ?>","dateModified":"<?php echo date(DATE_ATOM); ?>","description":"Schedule your Instagram posts.","inLanguage":"en","potentialAction":[{"@type":"ReadAction","target":["<?php echo $this->baseurl; ?>"]}]}]}
		</script>
		<script>let page_data = {pagenow: '<?php echo $this->name; ?>', baseurl: '<?php echo $this->baseurl; ?>', ajax: '<?php echo ($this->name != 'home' ? $this->AjaxGateway() : ''); ?>', media: '<?php echo $this->InstaPlanner->Options->Get( 'media_library', 'media/img/posts/' ); ?>'};</script>
<?php if( method_exists( $this, 'Header' ) ) { $this->Header(); } ?>
	</head>
	<body class="instaplaner__body <?php echo 'page-' . $this->name; ?>">
<?php if ( $this->name == 'dashboard' || $this->name == 'settings' ): ?>
		<nav class="instaplaner__navigation navbar navbar-expand-lg fixed-top navbar-light">
			<div class="container">
				<a class="navbar-brand" href="<?php echo $this->baseurl . ($this->InstaPlanner->User->IsLoggedIn() ? $this->InstaPlanner->Options->Get( 'dashboard', 'dashboard' ) : '' ); ?>">
					<div class="instaplaner__navigation__logo">
						<img src="https://rdev.lan/dev/instaplaner/media/img/instaplaner.svg" alt="InstaPlaner Logo">
					</div>
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#instaplaner-navbar-mobile" aria-controls="instaplaner-navbar-mobile" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div id="instaplaner-navbar-mobile" class="collapse navbar-collapse">
					<!--
					<ul id="menu-gorne-menu" class="navbar-nav ml-auto" itemscope="" itemtype="http://www.schema.org/SiteNavigationElement">
						<li class="nav-item active">
							<a class="nav-link" href="#">
								<svg width="22px" height="22px" viewBox="0 0 16 16" class="bi bi-house" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
									<path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
								</svg>
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								<svg width="22px" height="22px" viewBox="0 0 16 16" class="bi bi-envelope" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z"/>
								</svg>
							</a>
						</li>
					</ul>
					-->
				</div>
			</div>
		</nav>
		<div class="instaplaner__navigation--clone"></div>
<?php endif ?>