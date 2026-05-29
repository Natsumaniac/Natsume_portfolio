<footer class="footer">

  <!-- BACKGROUND -->

  <?php 
  $front_page_id = (int) get_option('page_on_front');
  $section_base = is_front_page() ? '' : home_url('/');

  $footer_field = static function ($key) use ($front_page_id) {
    $value = null;

    if ($front_page_id > 0) {
      $value = get_field($key, $front_page_id);
    }

    if (empty($value)) {
      $value = get_field($key);
    }

    return $value;
  };

  $footer_bg = $footer_field('footer_background');
  $footer_logo = $footer_field('footer_logo');
  $footer_description = $footer_field('footer_description');
  $footer_cta_text = $footer_field('footer_cta_text');
  $github_url = $footer_field('github_url');
  $instagram_url = $footer_field('instagram_url');
  $facebook_url = $footer_field('facebook_url');
  $email_url = $footer_field('email_url');
  $footer_copyright = $footer_field('footer_copyright');
  ?>

  <div 
    class="footer-bg"

    <?php if ($footer_bg): ?>

      style="
        background-image:
        url('<?php echo esc_url($footer_bg['url']); ?>');
      "

    <?php endif; ?>

  ></div>

  <div class="footer-content">

    <!-- TOP -->

    <div class="footer-top">

      <!-- LEFT -->

      <div class="footer-brand">

        <?php if ($footer_logo): ?>

          <a
            href="<?php echo esc_url(is_front_page() ? '#hero' : home_url('/#hero')); ?>"
            class="footer-logo-link"
            aria-label="Back to Hero section"
          >
            <img
              src="<?php echo esc_url($footer_logo['url']); ?>"
              alt="Footer Logo"
            >
          </a>

        <?php endif; ?>

        <p>

          <?php 
          echo esc_html(
            $footer_description
          ); 
          ?>

        </p>

      </div>

      <!-- CENTER -->

      <div class="footer-links">

        <h3>Quick Links</h3>

        <ul>

          <li>
            <a href="<?php echo esc_url($section_base . '#about'); ?>">
              <i class="fa-solid fa-user"></i>
              <span>About</span>
            </a>
          </li>

          <li>
            <a href="<?php echo esc_url($section_base . '#works'); ?>">
              <i class="fa-solid fa-briefcase"></i>
              <span>Works</span>
            </a>
          </li>

          <li>
            <a href="<?php echo esc_url($section_base . '#skills'); ?>">
              <i class="fa-solid fa-lightbulb"></i>
              <span>Skills</span>
            </a>
          </li>

          <li>
            <a href="<?php echo esc_url($section_base . '#certificates'); ?>">
              <i class="fa-solid fa-certificate"></i>
              <span>Certificates</span>
            </a>
          </li>

          <li>
            <a href="<?php echo esc_url($section_base . '#cta'); ?>">
              <i class="fa-solid fa-paper-plane"></i>
              <span>Contact</span>
            </a>
          </li>

        </ul>

      </div>

      <!-- RIGHT -->

      <div class="footer-connect">

        <h3>Connect</h3>

        <p>

          <?php 
          echo esc_html(
            $footer_cta_text
          ); 
          ?>

        </p>

        <div class="footer-social">

          <?php if ($github_url): ?>

            <a 
              href="<?php echo esc_url($github_url); ?>"
              target="_blank"
            >
              <i class="fab fa-github"></i>
            </a>

          <?php endif; ?>

          <?php if ($instagram_url): ?>

            <a 
              href="<?php echo esc_url($instagram_url); ?>"
              target="_blank"
            >
              <i class="fab fa-instagram"></i>
            </a>

          <?php endif; ?>

          <?php if ($facebook_url): ?>

            <a 
              href="<?php echo esc_url($facebook_url); ?>"
              target="_blank"
            >
              <i class="fab fa-facebook"></i>
            </a>

          <?php endif; ?>

          <?php if ($email_url): ?>

            <a 
              href="mailto:<?php echo esc_attr($email_url); ?>"
            >
              <i class="fas fa-envelope"></i>
            </a>

          <?php endif; ?>

        </div>

      </div>

    </div>

    <!-- LINE -->

    <div class="footer-line"></div>

    <!-- BOTTOM -->

    <div class="footer-bottom">

      <p>

        <?php 
        echo esc_html(
          $footer_copyright ?: '© 2026 Natsume Portfolio'
        ); 
        ?>

      </p>

    </div>

  </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
