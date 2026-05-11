<?php
/**
 * Template Name: About Page
 *
 * @package NatsumePortfolio
 */

get_header();
?>

<section class="about-hero">
  <!-- SMOKE BACKGROUND -->
  <div class="smoke-bg">
    <?php 
    $about_hero_bg = get_field('about_hero_background');
    if ($about_hero_bg): ?>
      <img src="<?php echo esc_url($about_hero_bg['url']); ?>" alt="Smoke Background">
    <?php else: ?>
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/smoke.png" alt="Smoke Background">
    <?php endif; ?>
  </div>

  <!-- LOGO OVERLAY -->
  <div class="logo-overlay">
    <?php 
    $about_hero_logo = get_field('about_hero_logo');
    if ($about_hero_logo): ?>
      <img src="<?php echo esc_url($about_hero_logo['url']); ?>" alt="Natsume Logo">
    <?php else: ?>
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/natsume_logo.png" alt="Natsume Logo">
    <?php endif; ?>
  </div>

  <!-- LEFT CONTENT BOX -->
  <div class="left-content">
    <div class="content-box">
      <p class="natsume-sentence">
        <span class="natsume-title"><?php echo esc_html(get_field('about_natsume_title') ?: '"NATSUME"'); ?></span>
        <span class="stands-text"><?php echo esc_html(get_field('about_stands_text') ?: 'stands for'); ?></span>
        <span class="emphasis-text"><?php echo esc_html(get_field('about_emphasis_text') ?: 'not so me'); ?></span>
      </p>
    </div>
  </div>

  <!-- RIGHT CONTENT BOX -->
  <div class="right-content">
    <div class="content-box">
      <p>
        <?php 
        $evolution_desc = get_field('about_evolution_description');
        echo $evolution_desc ? esc_html($evolution_desc) : 'It is a reflection of constant evolution - a commitment to growth that goes beyond my current self, challenging my own boundaries every single day.';
        ?>
      </p>
    </div>
  </div>
</section>

<?php get_footer(); ?>
