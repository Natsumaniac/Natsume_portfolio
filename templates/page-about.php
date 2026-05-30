<?php
/**
 * Template Name: About Page
 *
 * @package NatsumePortfolio
 */

get_header();
?>

<section class="about-hero" id="about-hero">

  <div class="mouth-smoke" aria-hidden="true"></div>

  <!-- SMOKE BACKGROUND -->
  <div class="smoke-bg">

    <?php 
    $background_image = get_field('background_image');

    if ($background_image): ?>

      <img
        src="<?php echo esc_url($background_image['url']); ?>"
        alt="<?php echo esc_attr($background_image['alt']); ?>"
      >

    <?php endif; ?>

  </div>

  <!-- LOGO OVERLAY -->
  <div class="logo-overlay">

    <?php 
    $hero_logo = get_field('hero_logo');

    if ($hero_logo): ?>

      <img
        src="<?php echo esc_url($hero_logo['url']); ?>"
        alt="<?php echo esc_attr($hero_logo['alt']); ?>"
      >

    <?php endif; ?>

  </div>

  <!-- LEFT CONTENT -->
  <div class="left-content">

    <div class="content-box">

      <p class="natsume-sentence">

        <span class="natsume-title">

          <?php 
          echo esc_html(
            get_field('natsume_title')
          ); 
          ?>

        </span>

        <span class="stands-text">

          <?php 
          echo esc_html(
            get_field('stands_text')
          ); 
          ?>

        </span>

        <span class="emphasis-text">

          <?php 
          echo esc_html(
            get_field('emphasis_text')
          ); 
          ?>

        </span>

      </p>

    </div>

  </div>

  <!-- RIGHT CONTENT -->
  <div class="right-content">

    <div class="content-box">

      <p>

        <?php 
        echo esc_html(
          get_field('evolution_description')
        ); 
        ?>

      </p>

    </div>

  </div>

</section>

<!-- =========================
     ABOUT INTRO SECTION
========================= -->

<section class="about-intro-section" id="about-intro">

  <!-- SECTION HEADER -->
  <div class="about-intro-header">

    <h2>

      <?php 
      echo esc_html(
        get_field('section_title')
      ); 
      ?>

    </h2>

    <p>

      <?php 
      echo esc_html(
        get_field('section_subtitle')
      ); 
      ?>

    </p>

  </div>

  <!-- CONTENT -->
  <div class="about-intro-content">

    <!-- LEFT -->
    <div class="about-intro-left reveal-left">

      <div class="about-intro-box">

        <?php
        $about_description = get_field('about_description');
        if ($about_description) {
          echo wp_kses_post(
            wpautop(
              esc_html($about_description)
            )
          );
        }
        ?>

      </div>

    </div>

    <!-- RIGHT -->
    <div class="about-intro-right reveal-right">

      <div class="profile-wrapper">

        <!-- IMAGE -->
        <div class="profile-frame">

          <?php 
          $profile_image = get_field('profile_image');

          if ($profile_image): ?>

            <img
              src="<?php echo esc_url($profile_image['url']); ?>"
              alt="<?php echo esc_attr($profile_image['alt']); ?>"
            >

          <?php endif; ?>

        </div>

        <!-- NAME -->
        <h3>

          <?php 
          echo esc_html(
            get_field('full_name')
          ); 
          ?>

        </h3>

        <!-- ROLES -->
        <div class="about-roles">

          <span>

            <?php 
            echo esc_html(
              get_field('role_1')
            ); 
            ?>

          </span>

          <span class="role-dot">•</span>

          <span>

            <?php 
            echo esc_html(
              get_field('role_2')
            ); 
            ?>

          </span>

          <span class="role-dot">•</span>

          <span>

            <?php 
            echo esc_html(
              get_field('role_3')
            ); 
            ?>

          </span>

        </div>

      </div>

    </div>

  </div>

</section>

<!-- =========================
     IDENTITY SECTION
========================= -->

<section class="identity-section" id="identity">

  <!-- BACKGROUND -->
  <div class="identity-bg">

    <?php 
    $identity_bg = get_field('identity_background_image');

    if ($identity_bg): ?>

      <img
        src="<?php echo esc_url($identity_bg['url']); ?>"
        alt="<?php echo esc_attr($identity_bg['alt']); ?>"
      >

    <?php endif; ?>

  </div>

  <!-- DARK OVERLAY -->
  <div class="identity-overlay"></div>

  <!-- CONTENT -->
  <div class="identity-container">

    <!-- LEFT SIDE -->
    <div class="identity-left">

      <!-- CARD 1 -->
      <div class="identity-card left-card">

        <div class="card-icon">
          <i class="fa-solid fa-pencil"></i>
        </div>

        <div class="card-text">

          <h3>
            <?php echo esc_html(get_field('left_card_1_title')); ?>
          </h3>

          <p>
            <?php echo esc_html(get_field('left_card_1_description')); ?>
          </p>

        </div>

      </div>

      <!-- CARD 2 -->
      <div class="identity-card left-card">

        <div class="card-icon">
          <i class="fa-solid fa-arrow-trend-up"></i>
        </div>

        <div class="card-text">

          <h3>
            <?php echo esc_html(get_field('left_card_2_title')); ?>
          </h3>

          <p>
            <?php echo esc_html(get_field('left_card_2_description')); ?>
          </p>

        </div>

      </div>

      <!-- CARD 3 -->
      <div class="identity-card left-card">

        <div class="card-icon">
          <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>

        <div class="card-text">

          <h3>
            <?php echo esc_html(get_field('left_card_3_title')); ?>
          </h3>

          <p>
            <?php echo esc_html(get_field('left_card_3_description')); ?>
          </p>

        </div>

      </div>

      <!-- CARD 4 -->
      <div class="identity-card left-card">

        <div class="card-icon">
          <i class="fa-solid fa-pen-ruler"></i>
        </div>

        <div class="card-text">

          <h3>
            <?php echo esc_html(get_field('left_card_4_title')); ?>
          </h3>

          <p>
            <?php echo esc_html(get_field('left_card_4_description')); ?>
          </p>

        </div>

      </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="identity-right">

      <!-- TOP RIGHT -->
      <div class="right-top">

        <!-- EDUCATION -->
        <div class="identity-card right-card">

          <div class="card-icon">
            <i class="fa-solid fa-graduation-cap"></i>
          </div>

          <div class="card-text">

            <h3>
              <?php echo esc_html(get_field('right_card_1_title')); ?>
            </h3>

            <h2>
              <?php echo esc_html(get_field('right_card_1_main_text')); ?>
            </h2>

            <span>
              <?php echo esc_html(get_field('right_card_1_small_text')); ?>
            </span>

          </div>

        </div>

        <!-- AGE -->
        <div class="identity-card right-card small-card">

          <div class="card-icon">
            <i class="fa-solid fa-briefcase"></i>
          </div>

          <div class="card-text">

            <h3>
              <?php echo esc_html(get_field('right_card_2_title')); ?>
            </h3>

            <h2>
              <?php echo esc_html(get_field('right_card_2_main_text')); ?>
            </h2>

            <span>
              <?php echo esc_html(get_field('right_card_2_small_text')); ?>
            </span>

          </div>

        </div>

      </div>

      <!-- BOTTOM RIGHT -->
      <div class="right-bottom">

        <!-- BIRTHDAY -->
        <div class="identity-card right-card">

          <div class="card-icon">
            <i class="fa-solid fa-cake-candles"></i>
          </div>

          <div class="card-text">

            <h3>
              <?php echo esc_html(get_field('right_card_3_title')); ?>
            </h3>

            <h2>
              <?php echo esc_html(get_field('right_card_3_main_text')); ?>
            </h2>

            <span>
              <?php echo esc_html(get_field('right_card_3_small_text')); ?>
            </span>

          </div>

        </div>

        <!-- ADDRESS -->
        <div class="identity-card right-card">

          <div class="card-icon">
            <i class="fa-solid fa-location-dot"></i>
          </div>

          <div class="card-text">

            <h3>Address</h3>

            <h2>Lupon Davao Oriental</h2>

            <span>Philippines</span>

          </div>

        </div>

      </div>

      <!-- ZODIAC -->
      <div class="zodiac-wrapper">

        <?php 
        $zodiac = get_field('zodiac_image');

        if ($zodiac): ?>

          <img
            src="<?php echo esc_url($zodiac['url']); ?>"
            alt="<?php echo esc_attr($zodiac['alt']); ?>"
          >

        <?php endif; ?>

      </div>

      <!-- BUTTON -->
      <?php 
      $resume = get_field('resume_file');

      if ($resume): ?>

        <a
          href="<?php echo esc_url($resume['url']); ?>"
          class="btn-premium hero-secondary resume-btn"
          download
        >
          <?php echo esc_html(get_field('resume_button_text')); ?>
          <i class="fa-solid fa-download"></i>
        </a>

      <?php endif; ?>

    </div>

  </div>

</section>

<!-- =========================
     DRIVES SECTION
========================= -->

<section class="drives-section" id="drives">

  <div class="drives-container">

    <!-- TITLE -->
    <h2 class="drives-title">

      <?php 
      echo esc_html(
        get_field('drives_section_title')
      ); 
      ?>

    </h2>

    <!-- GRID -->
    <div class="drives-grid">

      <!-- CARD 1 -->
      <div class="drive-card">

        <div class="drive-icon">
          <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>

        <div class="drive-content">

          <h3>
            <?php echo esc_html(get_field('drives_card_1_title')); ?>
          </h3>

          <p>
            <?php echo esc_html(get_field('drives_card_1_description')); ?>
          </p>

        </div>

      </div>

      <!-- CARD 2 -->
      <div class="drive-card">

        <div class="drive-icon">
          <i class="fa-solid fa-code"></i>
        </div>

        <div class="drive-content">

          <h3>
            <?php echo esc_html(get_field('drives_card_2_title')); ?>
          </h3>

          <p>
            <?php echo esc_html(get_field('drives_card_2_description')); ?>
          </p>

        </div>

      </div>

      <!-- CARD 3 -->
      <div class="drive-card">

        <div class="drive-icon">
          <i class="fa-solid fa-arrow-trend-up"></i>
        </div>

        <div class="drive-content">

          <h3>
            <?php echo esc_html(get_field('drives_card_3_title')); ?>
          </h3>

          <p>
            <?php echo esc_html(get_field('drives_card_3_description')); ?>
          </p>

        </div>

      </div>

      <!-- CARD 4 -->
      <div class="drive-card">

        <div class="drive-icon">
          <i class="fa-solid fa-brain"></i>
        </div>

        <div class="drive-content">

          <h3>
            <?php echo esc_html(get_field('drives_card_4_title')); ?>
          </h3>

          <p>
            <?php echo esc_html(get_field('drives_card_4_description')); ?>
          </p>

        </div>

      </div>

    </div>

  </div>

</section>

<!-- =========================
     BEYOND SECTION
========================= -->

<section class="beyond-section" id="beyond">

  <div class="beyond-container">

    <!-- TITLE -->
    <h2 class="beyond-title">

      <?php 
      echo esc_html(
        get_field('beyond_section_title')
      ); 
      ?>

    </h2>

    <div class="beyond-content">

      <!-- LEFT -->
      <div class="beyond-left">

        <div class="beyond-image-wrapper">

          <?php 
          $beyond_image = get_field('beyond_image');

          if ($beyond_image): ?>

            <img
              src="<?php echo esc_url($beyond_image['url']); ?>"
              alt="<?php echo esc_attr($beyond_image['alt']); ?>"
            >

          <?php endif; ?>

          <!-- OVERLAY -->
          <div class="beyond-overlay">

            <p>

              <?php 
              echo esc_html(
                get_field('beyond_overlay_text')
              ); 
              ?>

            </p>

          </div>

        </div>

      </div>

      <!-- RIGHT -->
      <div class="beyond-right">

        <!-- CARD 1 -->
        <?php
        // Build music items array from ACF fields (music_1..music_6)
        $music_items = array();
        for ($m = 1; $m <= 6; $m++) {
          $title = get_field("music_{$m}_title");
          $cover = get_field("music_{$m}_cover");
          $audio = get_field("music_{$m}_audio");
          if ($audio && is_array($audio) && !empty($audio['url'])) {
            $music_items[] = array(
              'title' => $title ? $title : '',
              'cover' => $cover && is_array($cover) ? $cover['url'] : '',
              'audio' => $audio['url']
            );
          }
        }
        $music_json = esc_attr( wp_json_encode($music_items) );
        $first = !empty($music_items) ? $music_items[0] : null;
        ?>

        <div class="beyond-card music-card" data-music="<?php echo $music_json; ?>">

          <div class="beyond-card-content music-card-content">

            <div class="card-header">
              <i class="fa-solid fa-music"></i>
              <h3>
                <?php echo esc_html(get_field('beyond_card_1_title')); ?>
              </h3>
            </div>

            <p class="music-desc">
              <?php echo esc_html(get_field('beyond_card_1_description')); ?>
            </p>

            <div class="music-cover">
              <?php if ($first && $first['cover']): ?>
                <img src="<?php echo esc_url($first['cover']); ?>" alt="">
              <?php endif; ?>
            </div>

            <div class="music-title">
              <?php echo $first ? esc_html($first['title']) : ''; ?>
            </div>

            <audio id="music-player" src="<?php echo $first ? esc_url($first['audio']) : ''; ?>"></audio>

            <div class="music-controls">
              <button class="music-prev" aria-label="Previous">
                <i class="fa-solid fa-backward"></i>
              </button>

              <button class="music-play" aria-label="Play/Pause">
                <i class="fa-solid fa-play"></i>
              </button>

              <button class="music-next" aria-label="Next">
                <i class="fa-solid fa-forward"></i>
              </button>

              <button class="music-fav" aria-label="Favorite">
                <i class="fa-solid fa-heart"></i>
              </button>
            </div>

          </div>

        </div>

        <!-- CARD 6 -->
        <div class="beyond-card">

          <div class="beyond-card-content">

            <div class="card-header">
              <i class="fa-solid fa-pen-nib"></i>
              <h3>
                <?php echo esc_html(get_field('beyond_card_6_title')); ?>
              </h3>
            </div>

            <p>
              <?php echo esc_html(get_field('beyond_card_6_description')); ?>
            </p>

            <div class="quote-rotator">
              <?php
              for ($i = 1; $i <= 6; $i++) {
                $q = get_field("quote_{$i}");
                if ($q) {
                  echo '<p class="quote-slide">' . esc_html($q) . '</p>';
                }
              }
              ?>
            </div>

          </div>

        </div>

        <!-- CARD 3 -->
        <div class="beyond-card">

          <div class="beyond-card-content">

            <div class="card-header">
              <i class="fa-solid fa-book-open"></i>
              <h3>
                <?php echo esc_html(get_field('beyond_card_3_title')); ?>
              </h3>
            </div>

            <p>
              <?php echo esc_html(get_field('beyond_card_3_description')); ?>
            </p>

            <div class="book-carousel">

              <?php
              for($i=1;$i<=6;$i++):

                $book = get_field("book_{$i}");

                if($book):
              ?>

                <img
                  src="<?php echo esc_url($book['url']); ?>"
                  class="book-slide">

              <?php endif; endfor; ?>

            </div>

          </div>

        </div>

        <!-- CARD 2 -->
        <div class="beyond-card">

          <div class="beyond-card-content">

            <div class="card-header">
              <i class="fa-solid fa-film"></i>
              <h3>
                <?php echo esc_html(get_field('beyond_card_2_title')); ?>
              </h3>
            </div>

            <p>
              <?php echo esc_html(get_field('beyond_card_2_description')); ?>
            </p>

            <div class="movie-carousel">

              <?php
              for($i=1;$i<=6;$i++):

                $movie = get_field("movie_{$i}");

                if($movie):
              ?>

                <img
                  src="<?php echo esc_url($movie['url']); ?>"
                  class="movie-slide">

              <?php endif; endfor; ?>

            </div>

          </div>

        </div>

        <!-- CARD 4 -->
        <div class="beyond-card">

          <div class="beyond-card-content">

            <div class="card-header">
              <i class="fa-solid fa-person-rays"></i>
              <h3>
                <?php echo esc_html(get_field('beyond_card_4_title')); ?>
              </h3>
            </div>

            <p>
              <?php echo esc_html(get_field('beyond_card_4_description')); ?>
            </p>

            <?php
            $dance_videos = array();
            for ($i = 1; $i <= 6; $i++) {
              $d = get_field("dance_{$i}");
              if ($d && is_array($d) && !empty($d['url'])) {
                $dance_videos[] = $d['url'];
              }
            }
            $dance_json = esc_attr( wp_json_encode($dance_videos) );
            ?>

            <div class="dance-wrapper" data-videos="<?php echo $dance_json; ?>">
              <video controls class="dance-player">
                <?php if (!empty($dance_videos)): ?>
                  <source src="<?php echo esc_url($dance_videos[0]); ?>">
                <?php endif; ?>
              </video>

              <div class="dance-controls-overlay">
                <button class="dance-prev" aria-label="Previous">
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="dance-next" aria-label="Next">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
              </div>
            </div>

          </div>

        </div>

        <!-- CARD 5 -->
        <div class="beyond-card">

          <div class="beyond-card-content">

            <div class="card-header">
              <i class="fa-solid fa-microphone"></i>
              <h3>
                <?php echo esc_html(get_field('beyond_card_5_title')); ?>
              </h3>
            </div>

            <p>
              <?php echo esc_html(get_field('beyond_card_5_description')); ?>
            </p>

            <div class="wave-player centered-wave-player">

              <h4 class="singing-header"><?php echo esc_html(get_field('singing_title')); ?></h4>

              <p class="singing-desc"><?php echo esc_html(get_field('singing_description')); ?></p>

              <div class="waveform waveform-large" aria-hidden="true">
                <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
              </div>

              <audio id="singing-player" preload="none" src="<?php echo esc_url(get_field('singing_audio')['url']); ?>"></audio>

              <div class="singing-controls">
                <button class="singing-play" aria-label="Play/Pause"><i class="fa-solid fa-play"></i></button>
                <button class="singing-fav" aria-label="Favorite"><i class="fa-solid fa-heart"></i></button>
              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</section>

<!-- =========================
     QUOTE SECTION
========================= -->

<section class="quote-section" id="quote">

  <div class="quote-container">

    <!-- QUOTE -->
    <blockquote class="quote-text">

      <?php 
      echo esc_html(
        get_field('quote_text')
      ); 
      ?>

    </blockquote>

    <!-- AUTHOR -->
    <div class="quote-author">

      <span class="line"></span>

      <p>

        <?php 
        echo esc_html(
          get_field('quote_author')
        ); 
        ?>

      </p>

      <span class="line"></span>

    </div>

  </div>

</section>

<?php get_footer(); ?>
