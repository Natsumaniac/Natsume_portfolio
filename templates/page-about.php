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
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/smoke.png" alt="Smoke Background">
  </div>

  <!-- LOGO OVERLAY -->
  <div class="logo-overlay">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/natsume_logo.png" alt="Natsume Logo">
  </div>

  <!-- LEFT CONTENT BOX -->
  <div class="left-content">
    <div class="content-box">
      <p class="natsume-sentence">
        <span class="natsume-title">"NATSUME"</span>
        <span class="stands-text">stands for</span>
        <span class="emphasis-text">not so me</span>.
      </p>
    </div>
  </div>

  <!-- RIGHT CONTENT BOX -->
  <div class="right-content">
    <div class="content-box">
      <p>It is a reflection of constant evolution - a commitment to growth that goes beyond my current self, challenging my own boundaries every single day.</p>
    </div>
  </div>
</section>

<?php get_footer(); ?>
