<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<meta name="viewport" content="width=device-width" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

















	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>


	<div class="main">
		<div class="hdr1">

			<div class="head">
			<?php if ( function_exists( 'jetpack_the_site_logo' ) ) jetpack_the_site_logo(); ?>
			<h5 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h5>
			<h6 class="site-description"><?php bloginfo( 'description' ); ?></h6>

			</div>
			


				<div class="sidebar-head4 span2">

					<?php dynamic_sidebar( 'sidebar-header' ); ?>

				</div>

		</div>
	</div>


		<div class="main3">
			<div class="main4">
					
						<ul><li>
							 <?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?> </li>
						</ul>
						 <a id="live-menu" class="responsive-menu" href="#">Menu</a>
					
			</div>
		</div>

<div class="main">
	<div class="content-main">