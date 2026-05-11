<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>

<!-- NAVBAR -->
<nav class="navbar">

  <!-- LOGO -->
  <div class="logo">
    <a href="<?php echo esc_url(home_url('/')); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/natsume_logo.png" alt="Logo">
    </a>
  </div>

  <!-- WORDPRESS MENU -->
  <?php
  wp_nav_menu(array(
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'nav-links',
    'fallback_cb'    => 'natsume_portfolio_fallback_menu',
    'depth'          => 1,
  ));
  ?>

</nav>

</header>

<main class="site-main">
