<?php
/**
 * Template: Front Page (Homepage)
 *
 * Displays hero section + featured works, skills, and certificates.
 * Set this page as your "Static Front Page" in Settings > Reading.
 *
 * @package NatsumePortfolio
 */

get_header();

// ─── Hero Section ────────────────────────────────────────────────────
$intro_video = get_field('intro_video');
$loop_video = get_field('loop_video');
$greeting   = get_field('hero_greeting') ?: 'Hello';
$name       = get_field( 'hero_name' ) ?: get_bloginfo( 'name' );
$desc       = get_field( 'hero_description' );
$hero_btn_text1 = get_field('hero_button_text1') ?: 'Behind the Name';
$hero_btn_text2 = get_field('hero_button_text2') ?: 'View Resume';
$about_btn_text = get_field('about_button_text') ?: 'The Story Behind Natsume';
$hero_resume_url = esc_url( natsume_portfolio_get_resume_viewer_url() );
?>

<!-- HERO SECTION -->
<section class="hero" id="hero">
  <?php if ($intro_video) : ?>
  <video id="heroVideo" autoplay muted playsinline>
    <source src="<?php echo esc_url($intro_video['url']); ?>" type="video/mp4">
  </video>
  <?php endif; ?>

  <div class="hero-content">
    <h1> <?php echo esc_html($greeting); ?> <span class="wave"><i class="fa-solid fa-hand"></i></span> </h1>
    <h2 class="name-line">
          <?php
    $typing_texts = get_field('hero_typing_texts');

    $typing_array = [];

    if ($typing_texts) {
        $texts = explode(',', $typing_texts);

        foreach ($texts as $text) {
            $typing_array[] = trim($text);
        }
    }
    ?>
      <span 
        id="typed-text"
        data-roles='<?php echo json_encode($typing_array); ?>'>
      </span>
      <span class="cursor">|</span>
    </h2>
    
    <?php if ($desc) : ?>
    <p><?php echo esc_html($desc); ?></p>
    <?php endif; ?>

    <div class="hero-buttons">

      <a href="#about" class="btn-premium hero-secondary">
        <i class="fa-solid fa-user-secret"></i>
        <?php echo esc_html($hero_btn_text1); ?>
      </a>

      <a href="<?php echo $hero_resume_url; ?>" class="btn-premium hero-primary" target="_blank" rel="noopener noreferrer">
        <i class="fa-solid fa-file-lines"></i>
        <?php echo esc_html($hero_btn_text2); ?>
      </a>

    </div>

  </div>
</section>

<?php if ($intro_video && $loop_video) : ?>
<script>
  const video = document.getElementById("heroVideo");

  video.addEventListener("ended", () => {
    video.src = "<?php echo esc_url($loop_video['url']); ?>";
    video.loop = true;
    video.play();
  });
</script>
<?php endif; ?>

<!-- ABOUT SECTION -->
<section class="about-section" id="about">

  <div class="about-container">

    <!-- IMAGE (OVERLAY) -->
    <div class="about-image">
      <?php 
      $about_bg = get_field('about_background_image');
      if ($about_bg): ?>
        <img src="<?php echo esc_url($about_bg['url']); ?>" alt="About Image">
      <?php else: ?>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/smoke.png" alt="About Image">
      <?php endif; ?>
    </div>

    <!-- CONTENT -->
    <div class="about-content">

      <?php if (get_field('about_title')) : ?>

<div class="about-title">
    <span class="line"></span>
    <?php echo esc_html(get_field('about_title')); ?>
</div>

<?php endif; ?>

      <!-- BORDER ONLY -->
        <div class="about-box"></div>

        <!-- TEXT (SEPARATE LAYER) -->
        <div class="about-text">
        <?php 
        $about_para1 = get_field('about_paragraph_1');
        $about_para2 = get_field('about_paragraph_2');
        ?>
        
        <?php if ($about_para1): ?>
        <p><?php echo esc_html($about_para1); ?></p>
        <?php endif; ?>

        <?php if ($about_para2): ?>
        <p><?php echo esc_html($about_para2); ?></p>
        <?php endif; ?>
      
        </div>

    <div class="about-buttons">
    <a href="<?php echo esc_url(home_url('/about')); ?>" class="btn-premium">
          <i class="fa-solid fa-book-open"></i>
          <?php echo esc_html($about_btn_text); ?>
      </a>
    </div>
  </div>
  </div>

</section>

<?php natsume_portfolio_render_section_transition( 'section-transition--angle' ); ?>

<!-- WORKS SECTION -->
<section class="works-section" id="works">

  <h2 class="works-title">
    <?php echo esc_html(get_field('works_title') ?: 'MY WORKS'); ?>
  </h2>

  <div class="works-slider">

    <!-- PREV BUTTON -->
    <button class="nav-btn prev">&#10094;</button>

    <div class="slider-wrapper">

      <?php

      $works = new WP_Query(array(
        'post_type'      => 'work',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC'
      ));

      if ($works->have_posts()) :

        while ($works->have_posts()) :
          $works->the_post();

          $work_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
      ?>

      <a
        href="<?php the_permalink(); ?>"
        class="work-item"
        aria-label="<?php echo esc_attr( get_the_title() ); ?>"
      >

        <?php if ($work_image): ?>

          <img
            src="<?php echo esc_url($work_image); ?>"
            alt="<?php echo esc_attr( get_the_title() ); ?>"
          >

        <?php else: ?>

          <span class="work-item__placeholder" aria-hidden="true">
            <i class="fa-solid fa-diagram-project"></i>
          </span>

        <?php endif; ?>

        <div class="work-overlay">
          <?php the_title(); ?>
        </div>

      </a>

      <?php
        endwhile;

        wp_reset_postdata();

      else :
      ?>
        <div class="works-empty">No projects available yet.</div>
      <?php
      endif;
      ?>

    </div>

    <!-- NEXT BUTTON -->
    <button class="nav-btn next">&#10095;</button>

  </div>

  <?php $works_btn_text = get_field('works_button_text') ?: 'VIEW PROJECTS'; ?>
  <a
    href="<?php echo get_post_type_archive_link('work'); ?>"
    class="btn-premium works-btn"
  >
    <i class="fas fa-up-right-from-square"></i>
    <?php echo esc_html($works_btn_text); ?>
  </a>

</section>

<?php natsume_portfolio_render_section_transition( 'section-transition--circuit' ); ?>

<!-- SKILLS SECTION -->
<section class="skills-section" id="skills">
  <!-- GIF IMAGE -->
  <div class="skills-image">
    <?php 
    $skills_gif = get_field('skills_gif');
    if ($skills_gif): ?>
      <img 
        src="<?php echo esc_url($skills_gif['url']); ?>" 
        alt="Skills GIF"
      >
    <?php else: ?>
      <img 
        src="<?php echo get_template_directory_uri(); ?>/assets/images/error.gif" 
        alt="Skills GIF"
      >
    <?php endif; ?>
  </div>
  <!-- TITLE -->
  <h2 class="skills-title">
    <?php 
    echo esc_html(
      get_field('skills_title') ?: 'MY SKILLS'
    ); 
    ?>
  </h2>
  <!-- SKILLS CONTENT -->
  <div class="skills-content">
    <?php
    $skills = new WP_Query(array(
      'post_type'      => 'skill',
      'posts_per_page' => -1,
      'orderby'        => 'date',
      'order'          => 'DESC',
    ));

    $all_skill_items = array();

    if ($skills->have_posts()) :
      while ($skills->have_posts()) :
        $skills->the_post();
        $skill_id = get_the_ID();
        $skill_name  = get_the_title();
        $skill_level = get_field('skill_percentage', $skill_id) ?: 50;
        $skill_icon  = get_field('skill_icon', $skill_id);
        $skill_icon_class = '';
        $skill_key = strtolower(trim($skill_name));

        if (stripos($skill_key, 'frontend') !== false) {
          $skill_icon_class = 'fa-solid fa-code';
        } elseif (stripos($skill_key, 'ui/ux') !== false || stripos($skill_key, 'uiux') !== false || stripos($skill_key, 'ui') !== false) {
          $skill_icon_class = 'fa-solid fa-palette';
        } elseif (stripos($skill_key, 'javascript') !== false || stripos($skill_key, 'js') !== false) {
          $skill_icon_class = 'fa-brands fa-js';
        } elseif (stripos($skill_key, 'react') !== false) {
          $skill_icon_class = 'fa-brands fa-react';
        }

        // Additional icon fallbacks for specific skill names
        if (stripos($skill_key, 'creative') !== false || stripos($skill_key, 'media') !== false || stripos($skill_key, 'edit') !== false) {
          $skill_icon_class = $skill_icon_class ?: 'fa-solid fa-film';
        } elseif (stripos($skill_key, 'interactive') !== false || stripos($skill_key, 'animation') !== false) {
          $skill_icon_class = $skill_icon_class ?: 'fa-solid fa-magic';
        }

        $skill_card = array(
          'id' => $skill_id,
          'name' => $skill_name,
          'level' => intval($skill_level),
          'icon' => $skill_icon,
          'icon_class' => $skill_icon_class,
          'featured' => (bool) get_field('featured_skill', $skill_id),
          'hero' => (bool) get_field('hero_skill', $skill_id),
        );

        $all_skill_items[] = $skill_card;
      endwhile;
      wp_reset_postdata();
    endif;

    // Sort by highest percentage first
    usort($all_skill_items, function($a, $b) {
      return $b['level'] - $a['level'];
    });

    // Show top 5 skills
    $skills_preview = array_slice($all_skill_items, 0, 5 );

    if (!empty($skills_preview)) :
      foreach ($skills_preview as $skill_item) :
        $skill_name  = $skill_item['name'];
        $skill_level = $skill_item['level'];
        $skill_icon  = $skill_item['icon'];
        $skill_icon_class = $skill_item['icon_class'];
    ?>
      <div class="skill">
        <!-- TOP -->
        <div class="skill-top">
          <div class="skill-name">
            <?php if ($skill_icon): ?>
              <img 
                src="<?php echo esc_url($skill_icon['url']); ?>" 
                alt="<?php echo esc_attr($skill_name); ?>"
              >
            <?php elseif ($skill_icon_class): ?>
              <i class="<?php echo esc_attr($skill_icon_class); ?>"></i>
            <?php endif; ?>
            <span>
              <?php echo esc_html($skill_name); ?>
            </span>
          </div>
          <span class="skill-percent">
            <?php echo esc_html($skill_level); ?>%
          </span>
        </div>
        <!-- BAR -->
        <div class="skill-bar">
          <div 
            class="skill-progress"
            data-width="<?php echo esc_attr($skill_level); ?>%"
            style="
              width: <?php echo esc_attr($skill_level); ?>%;
            "
          ></div>
        </div>
      </div>
    <?php
      endforeach;
    else :
    ?>
      <div class="skills-empty">No skills available yet.</div>
    <?php
    endif;
    ?>
  </div>
  <!-- BUTTON -->
  <?php 
  $skills_btn_text = get_field('skills_button_text') ?: 'SEE MORE';
  ?>
  <a 
    href="<?php echo get_post_type_archive_link('skill'); ?>" 
    class="btn-premium skills-btn"
  >
    <i class="fas fa-arrow-right"></i>
    <?php echo esc_html($skills_btn_text); ?>
  </a>
</section>

<?php natsume_portfolio_render_section_transition( 'section-transition--scan' ); ?>

<!-- CERTIFICATES SECTION -->
<section class="cert-section" id="certificates">

  <!-- TITLE -->
  <h2 class="cert-title">
    <?php echo esc_html(get_field('certificate_title') ?: 'MY CERTIFICATES'); ?>
  </h2>

  <!-- HORIZONTAL AREA -->
  <div class="cert-horizontal">

    <div class="cert-track">

      <?php

      $certs = new WP_Query(array(

        'post_type'      => 'certificate',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',

      ));

      $certificate_button_text = get_field('certificate_button_text', get_queried_object_id());

      if ($certs->have_posts()) :

        $index = 0;

        while ($certs->have_posts()) :

          $certs->the_post();

          $cert_image = get_field('cert_image');

          $rotate = '';

          if ($index % 3 == 0) {
            $rotate = 'rotate-left';
          }

          elseif ($index % 3 == 1) {
            $rotate = 'rotate-center';
          }

          else {
            $rotate = 'rotate-right';
          }

      ?>

      <div class="cert-card">
        <div class="cert-card-inner <?php echo $rotate; ?>">
          <?php if ($cert_image): ?>
            <img
              src="<?php echo esc_url($cert_image['url']); ?>"
              alt="<?php the_title(); ?>"
            >
          <?php endif; ?>
        </div>
      </div>

      <?php

        $index++;

        endwhile;

        wp_reset_postdata();

      endif;
      ?>

      <!-- BUTTON CARD -->
      <a
        href="<?php echo get_post_type_archive_link('certificate'); ?>"
        class="cert-button-card btn-premium"
      >
        <i class="fas fa-arrow-right"></i>
        <?php echo esc_html($certificate_button_text); ?>
      </a>

    </div>

  </div>

</section>

<?php natsume_portfolio_render_section_transition( 'section-transition--coordinate' ); ?>

<!-- CTA SECTION -->
<section class="cta-section" id="cta">

  <!-- FLOATING HEAD IMAGE -->
  <div class="cta-head">

    <?php 
    $cta_head_img = get_field('cta_head_image');

    if ($cta_head_img): ?>

      <img 
        src="<?php echo esc_url($cta_head_img['url']); ?>" 
        alt="Head"
      >

    <?php endif; ?>

  </div>

  <!-- MAIN CTA CARD -->
  <div class="cta-card">

    <!-- BACKGROUND IMAGE -->

    <?php 
    $cta_bg_img = get_field('cta_background_image');

    if ($cta_bg_img): ?>

      <img 
        src="<?php echo esc_url($cta_bg_img['url']); ?>" 
        alt="CTA Background"
        class="cta-bg"
      >

    <?php endif; ?>

    <!-- OVERLAY -->
    <div class="cta-overlay"></div>

    <!-- CONTENT -->
    <div class="cta-content">

      <!-- TITLE -->
      <h2>

        <?php 
        echo esc_html(
          get_field('cta_title')
        ); 
        ?>

      </h2>

      <!-- DESCRIPTION -->
      <p>

        <?php 
        echo esc_html(
          get_field('cta_description')
        ); 
        ?>

      </p>

      <!-- BUTTON -->

      <?php 

      $cta_btn = get_field('cta_button_link');

      $cta_btn_text = get_field('cta_button_text');

      if ($cta_btn):

      ?>

      <a 
        href="<?php echo esc_url($cta_btn['url']); ?>" 

        class="btn-premium cta-btn"

        target="<?php echo esc_attr($cta_btn['target']); ?>"
      >
        <i class="fas fa-up-right-from-square"></i>
        <?php echo esc_html($cta_btn_text); ?>

      </a>

      <?php endif; ?>

    </div>

  </div>

</section>

<?php get_footer(); ?>
