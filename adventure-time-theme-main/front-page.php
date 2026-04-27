<?php
/**
 * Template: Front Page (Homepage)
 *
 * Displays hero section + featured works, skills, and certificates.
 * Set this page as your "Static Front Page" in Settings > Reading.
 *
 * @package DevPortfolio
 */

get_header();

// ─── Hero Section ────────────────────────────────────────────────────
$intro_video = get_field('intro_video');
$loop_video = get_field('loop_video');
$greeting   = 'Hello';
$name       = get_field( 'hero_name' ) ?: get_bloginfo( 'name' );
$tagline    = get_field( 'hero_tagline' );
$desc       = get_field( 'hero_description' );
$image      = get_field( 'hero_image' );
$cta_text   = get_field( 'hero_cta_text' ) ?: 'View My Work';
$cta_link   = get_field( 'hero_cta_link' ) ?: '#works';
$github     = get_field( 'social_github' );
$linkedin   = get_field( 'social_linkedin' );
$email      = get_field( 'social_email' );
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
      <span id="typed-text"><?php echo esc_html($name); ?></span>
      <span class="cursor">|</span>
    </h2>
    <p>Confident in creating functional and user-friendly systems, while always striving to learn, grow, and improve.</p>
    <?php if ($desc) : ?>
    <p><?php echo esc_html($desc); ?></p>
    <?php endif; ?>
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
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/smoke.png" alt="About Image">
    </div>

    <!-- CONTENT -->
    <div class="about-content">

      <div class="about-title">
        <span class="line"></span>
        ABOUT ME
        </div>

      <!-- BORDER ONLY -->
        <div class="about-box"></div>

        <!-- TEXT (SEPARATE LAYER) -->
        <div class="about-text">
        <p>
            I started my journey in IT with a curiosity about how systems and applications work.
            Over time, I developed a passion for building user-friendly interfaces and creating
            visually clean designs.
        </p>

        <p>
            As a BSIT student, I continue to learn and improve my skills in frontend development
            and creative design.
        </p>
        </div>

    <a href="#" class="btn-premium about-btn">LEARN MORE <i class="fas fa-arrow-right"></i></a>

    </div>

  </div>

</section>

<!-- WORKS SECTION -->
<section class="works-section" id="works">
  <h2 class="works-title">MY WORKS</h2>

  <div class="works-slider">
    <!-- PREV BUTTON -->
    <button class="nav-btn prev">&#10094;</button>

    <div class="slider-wrapper">

      <div class="work-item left2">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/work1.png">
        <div class="work-overlay">The Love we've Made</div>
      </div>

      <div class="work-item left1">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/work2.png">
        <div class="work-overlay">Whispers of Desire</div>
      </div>

      <div class="work-item center">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/work3.png">
        <div class="work-overlay">FUCK OFF!</div>
      </div>

      <div class="work-item right1">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/work4.png">
        <div class="work-overlay">murag TALA</div>
      </div>

      <div class="work-item right2">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/work5.png">
        <div class="work-overlay">mirro shock</div>
      </div>

    </div>

    <!-- NEXT BUTTON -->
    <button class="nav-btn next">&#10095;</button>
  </div>

  <a href="<?php echo get_post_type_archive_link( 'work' ); ?>" class="btn-premium works-btn">VIEW PROJECT <i class="fas fa-up-right-from-square"></i></a>
</section>

<!-- SKILLS SECTION -->
<section class="skills-section" id="skills">
  <!-- GIF IMAGE -->
  <div class="skills-image">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/error.gif" alt="Skills Image">
  </div>

  <!-- TITLE -->
  <h2 class="skills-title">MY SKILLS</h2>

  <!-- SKILLS LIST -->
  <div class="skills-content">
    <?php
    $skills = new WP_Query( array(
        'post_type'      => 'skill',
        'posts_per_page' => 8,
        'meta_key'       => 'skill_order',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
    ) );
    
    if ( $skills->have_posts() ) : 
      while ( $skills->have_posts() ) : $skills->the_post(); 
        $skill_name = get_the_title();
        $skill_level = get_field('skill_level') ?: 75;
        $skill_icon = get_field('skill_icon') ?: 'fas fa-code';
    ?>
    <div class="skill">
      <span class="skill-name"><i class="<?php echo esc_attr($skill_icon); ?>"></i> <?php echo esc_html($skill_name); ?></span>
      <div class="skill-bar">
        <div class="skill-progress" style="width: <?php echo esc_attr($skill_level); ?>%"></div>
      </div>
      <span class="skill-percent"><?php echo esc_html($skill_level); ?>%</span>
    </div>
    <?php 
      endwhile; 
      wp_reset_postdata(); 
    else :
      // Fallback skills if none exist
      $fallback_skills = array(
        array('name' => 'FRONTEND DEVELOPMENT', 'icon' => 'fas fa-code', 'level' => 90),
        array('name' => 'UI/UX DESIGN', 'icon' => 'fas fa-pencil-ruler', 'level' => 80),
        array('name' => 'JAVASCRIPT', 'icon' => 'fab fa-js', 'level' => 75),
        array('name' => 'REACT', 'icon' => 'fab fa-react', 'level' => 65),
      );
      
      foreach ($fallback_skills as $skill) {
    ?>
    <div class="skill">
      <span class="skill-name"><i class="<?php echo esc_attr($skill['icon']); ?>"></i> <?php echo esc_html($skill['name']); ?></span>
      <div class="skill-bar">
        <div class="skill-progress" style="width: <?php echo esc_attr($skill['level']); ?>%"></div>
      </div>
      <span class="skill-percent"><?php echo esc_html($skill['level']); ?>%</span>
    </div>
    <?php
      }
    endif;
    ?>
  </div>

  <!-- BUTTON -->
  <a href="<?php echo get_post_type_archive_link( 'skill' ); ?>" class="btn-premium skills-btn">
    SEE MORE <i class="fas fa-arrow-right"></i>
  </a>
</section>

<!-- CERTIFICATES SECTION -->
<section class="cert-section" id="certificates">
  <!-- TITLE -->
  <h2 class="cert-title">MY CERTIFICATES</h2>

  <!-- LINE -->
  <div class="cert-line"></div>

  <!-- YEARS -->
  <div class="cert-years">
    <div class="year active" data-target="cert-2026">2026</div>
    <div class="year" data-target="cert-2025">2025</div>
    <div class="year" data-target="cert-2024">2024</div>
  </div>

  <div class="cert-end">+ more</div>

  <!-- LEFT CONTENT -->
  <div class="cert-left">
    <?php
    $certs = new WP_Query( array(
        'post_type'      => 'certificate',
        'posts_per_page' => 12,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );
    
    $certs_by_year = array();
    if ( $certs->have_posts() ) : 
      while ( $certs->have_posts() ) : $certs->the_post(); 
        $cert_year = get_the_date('Y');
        if (!isset($certs_by_year[$cert_year])) {
          $certs_by_year[$cert_year] = array();
        }
        $certs_by_year[$cert_year][] = array(
          'title' => get_the_title(),
          'organization' => get_field('organization') ?: 'Certificate',
          'image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
          'description' => get_field('description') ?: ''
        );
      endwhile; 
      wp_reset_postdata(); 
    endif;
    
    // Display certificates by year
    $years = array('2026', '2025', '2024');
    foreach ($years as $year) {
      $active_class = ($year == '2026') ? 'active' : '';
    ?>
    <div class="cert-content <?php echo esc_attr($active_class); ?>" id="cert-<?php echo esc_attr($year); ?>">
      <?php
      if (isset($certs_by_year[$year])) {
        $first = true;
        foreach ($certs_by_year[$year] as $index => $cert) {
          $active_item = $first ? 'active' : '';
          $cert_image = $cert['image'] ?: get_template_directory_uri() . '/assets/images/placeholder.jpg';
      ?>
      <div class="cert-item <?php echo esc_attr($active_item); ?>"
        data-img="<?php echo esc_url($cert_image); ?>"
        data-desc="<?php echo esc_attr($cert['description']); ?>">
        <h3><?php echo esc_html($cert['title']); ?></h3>
        <p><?php echo esc_html($cert['organization']); ?></p>
        <div class="cert-desc"></div>
      </div>
      <?php
          $first = false;
        }
      } else {
        // Fallback certificates
        $fallback_certs = array(
          '2026' => array(
            array('title' => 'IN-CAMPUS INTERNSHIP', 'org' => 'DICT Verified', 'desc' => 'Completed internship under DICT where I handled real-world IT support, troubleshooting systems, and assisting in technical operations.'),
            array('title' => 'AI CLASS TRAINING', 'org' => 'AI Fundamentals', 'desc' => 'Studied Artificial Intelligence fundamentals including machine learning concepts, data handling, and real-world applications.')
          ),
          '2025' => array(
            array('title' => 'UI/UX DESIGN', 'org' => 'Figma Practice', 'desc' => 'Focused on UI/UX design principles using Figma, creating clean and user-friendly interface layouts.')
          ),
          '2024' => array(
            array('title' => 'FRONTEND LEARNING', 'org' => 'HTML, CSS, JS', 'desc' => 'Built strong foundation in frontend development including HTML, CSS, and JavaScript through hands-on practice.')
          )
        );
        
        if (isset($fallback_certs[$year])) {
          $first = true;
          foreach ($fallback_certs[$year] as $cert) {
            $active_item = $first ? 'active' : '';
            $cert_image = ($year == '2026' && $first) ? get_template_directory_uri() . '/assets/images/dict.png' : get_template_directory_uri() . '/assets/images/placeholder.jpg';
          ?>
          <div class="cert-item <?php echo esc_attr($active_item); ?>"
            data-img="<?php echo esc_url($cert_image); ?>"
            data-desc="<?php echo esc_attr($cert['desc']); ?>">
            <h3><?php echo esc_html($cert['title']); ?></h3>
            <p><?php echo esc_html($cert['org']); ?></p>
            <div class="cert-desc"></div>
          </div>
          <?php
            $first = false;
          }
        }
      }
      ?>
      <a href="<?php echo get_post_type_archive_link( 'certificate' ); ?>" class="cert-more">
        + view all certificates →
      </a>
    </div>
    <?php
    }
    ?>
  </div>

  <!-- RIGHT PREVIEW -->
  <div class="cert-preview">
    <img id="cert-image" src="<?php echo get_template_directory_uri(); ?>/assets/images/cert1.jpg">
  </div>

  <!-- BUTTON -->
  <a href="<?php echo get_post_type_archive_link( 'certificate' ); ?>" class="btn-premium cert-btn">
    VIEW ALL CERTIFICATES <i class="fas fa-arrow-right"></i>
  </a>
</section>

<!-- CTA SECTION -->
<section class="cta-section" id="cta">
  <!-- FLOATING HEAD IMAGE -->
  <div class="cta-head">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/head.png" alt="Head">
  </div>

  <!-- MAIN CTA CARD -->
  <div class="cta-card">
    <!-- BACKGROUND IMAGE -->
    <img 
      src="<?php echo get_template_directory_uri(); ?>/assets/images/cta.png" 
      alt="CTA Background" 
      class="cta-bg"
    >

    <!-- OVERLAY -->
    <div class="cta-overlay"></div>

    <!-- CONTENT -->
    <div class="cta-content">
      <h2>LET'S BUILD SOMETHING GREAT TOGETHER</h2>
      <p>
        I'm open to projects, collaborations, and creative ideas.  
        Let's turn your vision into reality.
      </p>

      <a href="#contact" class="btn-premium cta-btn">
        CONTACT <i class="fas fa-up-right-from-square"></i>
      </a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
