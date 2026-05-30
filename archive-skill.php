<?php
/**
 * Archive: Skills
 *
 * @package NatsumePortfolio
 */

get_header();

$skills_query = new WP_Query(
    array(
        'post_type'      => 'skill',
        'posts_per_page' => -1,
        'meta_key'       => 'skill_order',
        'orderby'        => array(
            'meta_value_num' => 'ASC',
            'title'          => 'ASC',
        ),
        'order'          => 'ASC',
    )
);

$skills_data   = array();
$skill_lookup  = array();
$tech_set      = array();

$ball_skills = array();
$featured_skills = array();
$keyboard_skills = array();
$tool_skills = array();
$timeline_skills = array();

if ( $skills_query->have_posts() ) {
    while ( $skills_query->have_posts() ) {
        $skills_query->the_post();

        $skill_id          = get_the_ID();
        $title             = get_the_title();
        $icon              = get_field( 'skill_icon', $skill_id );
        $proficiency       = get_field( 'skill_proficiency', $skill_id );
        $percentage        = (int) get_field( 'skill_percentage', $skill_id );
        $description       = (string) get_field( 'skill_description', $skill_id );
        $experience        = (string) get_field( 'skill_experience', $skill_id );
        $color             = sanitize_hex_color( (string) get_field( 'skill_color', $skill_id ) );
        $level             = (string) get_field( 'skill_level', $skill_id );
        $related_raw       = (string) get_field( 'related_technologies', $skill_id );
        $terms             = get_the_terms( $skill_id, 'skill_category' );

        $icon_url = '';
        $icon_alt = $title;

        if ( is_array( $icon ) && ! empty( $icon['url'] ) ) {
            $icon_url = $icon['url'];
            $icon_alt = ! empty( $icon['alt'] ) ? $icon['alt'] : $title;
        } elseif ( is_numeric( $icon ) ) {
            $icon_url = wp_get_attachment_image_url( (int) $icon, 'thumbnail' );
        } elseif ( is_string( $icon ) && ! empty( $icon ) ) {
            $icon_url = esc_url_raw( $icon );
        }

        $tech_tags = function_exists( 'natsume_portfolio_parse_lines' )
            ? natsume_portfolio_parse_lines( $related_raw )
            : preg_split( '/\r\n|\r|\n|,/', $related_raw );

        $tech_tags = array_values( array_filter( array_map( 'trim', (array) $tech_tags ) ) );

        $term_slugs = array();
        $term_names = array();
        if ( $terms && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $term_slugs[] = $term->slug;
                $term_names[] = $term->name;
            }
        }

        foreach ( $tech_tags as $tag ) {
            $tech_set[ strtolower( $tag ) ] = $tag;
        }
        $tech_set[ strtolower( $title ) ] = $title;

        $item = array(
            'id'          => $skill_id,
            'title'       => $title,
            'icon_url'    => $icon_url,
            'icon_alt'    => $icon_alt,
            'proficiency' => $proficiency,
            'percentage'  => max( 0, min( 100, $percentage ) ),
            'description' => $description,
            'experience'  => $experience,
            'color'       => $color ? $color : '#ff7a00',
            'level'       => $level,
            'tags'        => $tech_tags,
            'cats'        => $term_slugs,
            'category_names' => $term_names,
        );

        $featured      = get_field('featured_skill', $skill_id);
        $is_tool       = get_field('is_tool', $skill_id);
        $learning_year = get_field('learning_year', $skill_id);

        $ball_skills[] = $item;

        $display_types = get_field('display_type', $skill_id);
        if (is_array($display_types) && in_array('Keyboard', $display_types)) {
            $keyboard_skills[] = $item;
        }

        if ($featured) {
            $featured_skills[] = $item;
        }

        if ($is_tool) {
            $tool_skills[] = $item;
        }

        if (!empty($learning_year)) {
            $timeline_skills[$learning_year][] = $item;
        }

        $skills_data[] = $item;
        $skill_lookup[ strtolower( $title ) ] = $item;
        foreach ( $tech_tags as $tag ) {
            $key = strtolower( $tag );
            if ( ! isset( $skill_lookup[ $key ] ) ) {
                $skill_lookup[ $key ] = $item;
            }
        }
    }
    wp_reset_postdata();
}
$total_skills = count( $skills_data );
$total_technologies = count( $ball_skills );
$total_projects = (int) wp_count_posts( 'work' )->publish;

$skill_categories = get_terms(
    array(
        'taxonomy' => 'skill_category',
        'hide_empty' => true,
    )
);

?>

<section id="skills-portal" class="skills-portal">
  <div class="skills-container">

    <header class="skills-hero reveal-on-scroll">
      <h1>MY SKILLS</h1>
      <p>Technologies, tools, and expertise I use to build web, mobile, database, analytics, and security solutions.</p>
      <div class="skills-hero__tools">
        <label for="skills-search" class="screen-reader-text">Search Skills</label>
        <input id="skills-search" class="js-skills-search" type="search" placeholder="Search Skills">
      </div>
      <div class="skills-counters">
        <article><strong class="js-counter" data-target="<?php echo esc_attr( $total_skills ); ?>">0</strong><span>+ Skills</span></article>
        <article><strong class="js-counter" data-target="<?php echo esc_attr( $total_technologies ); ?>">0</strong><span>Technologies</span></article>
        <article><strong class="js-counter" data-target="<?php echo esc_attr( $total_projects ); ?>">0</strong><span>+ Projects</span></article>
      </div>
    </header>

    <section class="stack-balls reveal-on-scroll">
      <div class="balls-layout js-balls-layout">
        <div class="balls-stage-col">
          <div class="stack-balls__stage js-balls-stage">
            <?php foreach ( $ball_skills as $index => $ball ) : ?>
              <div
                class="stack-ball skill-basketball"
                data-ball
                data-index="<?php echo esc_attr( $index ); ?>"
                data-skill-id="<?php echo esc_attr( $ball['id'] ); ?>"
                data-skill-color="<?php echo esc_attr( $ball['color'] ); ?>">
                <span><?php echo esc_html( $ball['title'] ); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <aside class="ball-info-panel js-ball-info-panel" aria-live="polite">
          <button class="ball-info-panel__close js-ball-info-close" type="button">✕</button>

          <div class="ball-info-panel__section ball-info-panel__section--summary">
            <div class="ball-info-panel__summary-head">
              <div class="ball-info-panel__summary-copy">
                <span class="ball-info-panel__section-label">Skill Name</span>
                <strong class="ball-info-panel__title">Skill Details</strong>
                <div class="ball-info-panel__mini-meta">
                  <div class="ball-info-panel__detail">
                    <span>Category</span>
                    <strong class="ball-info-panel__category">Uncategorized</strong>
                  </div>
                  <div class="ball-info-panel__detail">
                    <span>Experience</span>
                    <strong class="ball-info-panel__experience">Experience not specified</strong>
                  </div>
                  <div class="ball-info-panel__detail">
                    <span>Level</span>
                    <strong class="ball-info-panel__badge">Proficient</strong>
                  </div>
                </div>
              </div>

              <div class="ball-info-panel__showcase ball-info-panel__showcase--compact">
                <div class="ball-info-panel__ring" aria-hidden="true">
                  <div class="ball-info-panel__ring-track"></div>
                  <div class="ball-info-panel__ring-progress"></div>
                  <div class="ball-info-panel__ball skill-basketball"></div>
                </div>
                <div class="ball-info-panel__hero-meta">
                  <p class="ball-info-panel__eyebrow">Selected Skill Showcase</p>
                  <strong class="ball-info-panel__hero-percent">0%</strong>
                </div>
              </div>
            </div>

          </div>

          <div class="ball-info-panel__section ball-info-panel__section--description">
            <span class="ball-info-panel__section-label">Description</span>
            <p class="ball-info-panel__description">Click a basketball to view skill info.</p>
          </div>

          <div class="ball-info-panel__section ball-info-panel__section--tags">
            <span class="ball-info-panel__section-label">Related Technologies</span>
            <div class="ball-info-panel__tags"></div>
          </div>
        </aside>
      </div>
    </section>

    <?php
    $skill_viewer_data = array();
    foreach ( $skills_data as $skill_view_item ) {
        $skill_viewer_data[] = array(
            'id'           => (int) $skill_view_item['id'],
            'title'        => $skill_view_item['title'],
            'description'  => $skill_view_item['description'],
            'percentage'   => (int) $skill_view_item['percentage'],
            'experience'   => $skill_view_item['experience'],
            'color'        => $skill_view_item['color'],
            'icon_url'     => $skill_view_item['icon_url'],
            'proficiency'  => $skill_view_item['proficiency'],
            'level'        => $skill_view_item['level'],
            'tags'         => array_values( (array) $skill_view_item['tags'] ),
            'categories'   => array_values( (array) $skill_view_item['cats'] ),
            'category_names' => array_values( (array) $skill_view_item['category_names'] ),
        );
    }
    ?>

    <script type="application/json" id="skill-viewer-data">
      <?php echo wp_json_encode( $skill_viewer_data ); ?>
    </script>

    <?php
    $keyboard_pool = array();
    $keyboard_seen = array();
    $preferred_keyboard_skills = ! empty( $keyboard_skills ) ? $keyboard_skills : $skills_data;
    foreach ( array_merge( $preferred_keyboard_skills, $skills_data ) as $skill_item ) {
        $skill_key = (int) $skill_item['id'];
        if ( isset( $keyboard_seen[ $skill_key ] ) ) {
            continue;
        }

        $keyboard_seen[ $skill_key ] = true;
        $keyboard_pool[] = $skill_item;
    }

    shuffle( $keyboard_pool );

    $keyboard_layout = array(
        'main' => array(
            array(
                array( 'label' => 'ESC', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'F1', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'F2', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'F3', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'F4', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'F5', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'F6', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'F7', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'F8', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'F9', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'F10', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'F11', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'F12', 'size' => 'sm', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => '~', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '1', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '2', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '3', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '4', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '5', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '6', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '7', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '8', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '9', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '0', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '-', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '=', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'Backspace', 'size' => 'xl', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => 'Tab', 'size' => 'lg', 'skill_slot' => false ),
                array( 'label' => 'Q', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'W', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'E', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'R', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'T', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'Y', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'U', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'I', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'O', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'P', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '[', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => ']', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '\\', 'size' => 'lg', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => 'Caps Lock', 'size' => 'xl', 'skill_slot' => false ),
                array( 'label' => 'A', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'S', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'D', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'F', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'G', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'H', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'J', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'K', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'L', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => ';', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => "'", 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'Enter', 'size' => 'xl', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => 'Shift', 'size' => 'xxl', 'skill_slot' => false ),
                array( 'label' => 'Z', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'X', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'C', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'V', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'B', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'N', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'M', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => ',', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '.', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '/', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'Shift', 'size' => 'xxl', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => 'Ctrl', 'size' => 'lg', 'skill_slot' => false ),
                array( 'label' => 'Fn', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'Win', 'size' => 'lg', 'skill_slot' => false ),
                array( 'label' => 'Alt', 'size' => 'lg', 'skill_slot' => false ),
                array( 'label' => 'Space', 'size' => 'space', 'skill_slot' => false ),
                array( 'label' => 'Alt', 'size' => 'lg', 'skill_slot' => false ),
                array( 'label' => 'Menu', 'size' => 'lg', 'skill_slot' => false ),
                array( 'label' => 'Ctrl', 'size' => 'lg', 'skill_slot' => false ),
            ),
        ),
        'nav' => array(
            'system' => array(
                array( 'label' => 'Print Screen', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'Scroll Lock', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'Pause Break', 'size' => 'sm', 'skill_slot' => false ),
            ),
            'navigation' => array(
            array(
                array( 'label' => 'Ins', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'Home', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'PgUp', 'size' => 'sm', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => 'Del', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'End', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => 'PgDn', 'size' => 'sm', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => '↑', 'size' => 'sm', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => '←', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '↓', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '→', 'size' => 'sm', 'skill_slot' => false ),
            ),
            ),
        ),
        'numpad' => array(
            array(
                array( 'label' => 'Num Lock', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '/', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '*', 'size' => 'sm', 'skill_slot' => false ),
                array( 'label' => '-', 'size' => 'sm', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => '7', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '8', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '9', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '+', 'size' => 'tall', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => '4', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '5', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '6', 'size' => 'sm', 'skill_slot' => true ),
            ),
            array(
                array( 'label' => '1', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '2', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => '3', 'size' => 'sm', 'skill_slot' => true ),
                array( 'label' => 'Enter', 'size' => 'tall', 'skill_slot' => false ),
            ),
            array(
                array( 'label' => '0', 'size' => 'wide', 'skill_slot' => true ),
                array( 'label' => '.', 'size' => 'sm', 'skill_slot' => false ),
            ),
        ),
    );

    $keyboard_skill_index = 0;
    ?>

    <section class="skill-keyboard reveal-on-scroll">
      <div class="keyboard-wrap js-developer-keyboard">
        <div class="keyboard-display" aria-live="polite">
          <div class="keyboard-display__screen-frame">
            <div class="keyboard-display__header">
              <div class="keyboard-display__brand">
                <span class="keyboard-display__status-light"></span>
                <strong>PORTFOLIO OS</strong>
              </div>
              <div class="keyboard-display__indicators" aria-label="Monitor status indicators">
                <span><i></i>ONLINE</span>
                <span><i></i>SYSTEM ACTIVE</span>
              </div>
            </div>
            <div class="keyboard-display__screen">
              <output
                class="keyboard-display__output js-keyboard-display"
                data-prompt="visitor@natsume:~$ "
                data-placeholder="Awaiting keyboard input..."
              ></output>
            </div>
          </div>
          <div class="keyboard-display__neck" aria-hidden="true"></div>
          <div class="keyboard-display__base" aria-hidden="true"></div>
        </div>
        <div class="keyboard-connector" aria-hidden="true">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <div class="keyboard-shell">
          <div class="keyboard-layout">
            <div class="keyboard-main">
              <?php foreach ( $keyboard_layout['main'] as $row ) : ?>
                <div class="keyboard-row">
                  <?php foreach ( $row as $key_def ) : ?>
                    <?php
                    $skill = null;
                    $is_skill_key = ! empty( $key_def['skill_slot'] ) && isset( $keyboard_pool[ $keyboard_skill_index ] );
                    if ( $is_skill_key ) {
                        $skill = $keyboard_pool[ $keyboard_skill_index ];
                        $keyboard_skill_index++;
                    }

                    $key_classes = array( 'keycap', 'keycap--' . $key_def['size'] );
                    if ( $is_skill_key ) {
                        $key_classes[] = 'keycap--skill';
                    }

                    $skill_title = $skill ? $skill['title'] : '';
                    $skill_percent = $skill ? (int) $skill['percentage'] : 0;
                    $skill_prof = $skill ? ( $skill['proficiency'] ?: $skill['level'] ) : '';
                    $skill_desc = $skill ? $skill['description'] : '';
                    $skill_experience = $skill ? $skill['experience'] : '';
                    $skill_color = $skill ? $skill['color'] : '';
                    ?>
                    <button
                      class="<?php echo esc_attr( implode( ' ', $key_classes ) ); ?>"
                      type="button"
                      data-key
                      data-key-label="<?php echo esc_attr( $key_def['label'] ); ?>"
                      <?php if ( $is_skill_key ) : ?>
                        data-skill-id="<?php echo esc_attr( $skill['id'] ); ?>"
                        data-skill-title="<?php echo esc_attr( $skill_title ); ?>"
                        data-skill-percent="<?php echo esc_attr( $skill_percent ); ?>"
                        data-skill-prof="<?php echo esc_attr( $skill_prof ); ?>"
                        data-skill-desc="<?php echo esc_attr( $skill_desc ); ?>"
                        data-skill-exp="<?php echo esc_attr( $skill_experience ); ?>"
                        data-skill-color="<?php echo esc_attr( $skill_color ); ?>"
                      <?php endif; ?>
                    >
                      <span class="keycap__label">
                        <?php echo esc_html( $is_skill_key ? $skill_title : $key_def['label'] ); ?>
                      </span>
                    </button>
                  <?php endforeach; ?>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="keyboard-side">
              <div class="keyboard-system">
                <?php foreach ( $keyboard_layout['nav']['system'] as $key_def ) : ?>
                  <button
                    class="keycap keycap--<?php echo esc_attr( $key_def['size'] ); ?>"
                    type="button"
                    data-key
                    data-key-label="<?php echo esc_attr( $key_def['label'] ); ?>"
                  >
                    <span class="keycap__label"><?php echo esc_html( $key_def['label'] ); ?></span>
                  </button>
                <?php endforeach; ?>
              </div>

              <div class="keyboard-leds" aria-label="Keyboard status indicators">
                <span class="keyboard-led" title="Num Lock" aria-label="Num Lock"></span>
                <span class="keyboard-led" title="Caps Lock" aria-label="Caps Lock"></span>
                <span class="keyboard-led" title="Scroll Lock" aria-label="Scroll Lock"></span>
                <span class="screen-reader-text">Num Lock, Caps Lock, and Scroll Lock indicators</span>
              </div>

              <div class="keyboard-nav">
                <?php foreach ( $keyboard_layout['nav']['navigation'] as $row ) : ?>
                  <div class="keyboard-row keyboard-row--nav">
                    <?php foreach ( $row as $key_def ) : ?>
                      <?php
                      $skill = null;
                      $is_skill_key = ! empty( $key_def['skill_slot'] ) && isset( $keyboard_pool[ $keyboard_skill_index ] );
                      if ( $is_skill_key ) {
                          $skill = $keyboard_pool[ $keyboard_skill_index ];
                          $keyboard_skill_index++;
                      }

                      $key_classes = array( 'keycap', 'keycap--' . $key_def['size'] );
                      if ( $is_skill_key ) {
                          $key_classes[] = 'keycap--skill';
                      }

                      $skill_title = $skill ? $skill['title'] : '';
                      $skill_percent = $skill ? (int) $skill['percentage'] : 0;
                      $skill_prof = $skill ? ( $skill['proficiency'] ?: $skill['level'] ) : '';
                      $skill_desc = $skill ? $skill['description'] : '';
                      $skill_experience = $skill ? $skill['experience'] : '';
                      $skill_color = $skill ? $skill['color'] : '';
                      ?>
                      <button
                        class="<?php echo esc_attr( implode( ' ', $key_classes ) ); ?>"
                        type="button"
                        data-key
                        data-key-label="<?php echo esc_attr( $key_def['label'] ); ?>"
                        <?php if ( $is_skill_key ) : ?>
                          data-skill-id="<?php echo esc_attr( $skill['id'] ); ?>"
                          data-skill-title="<?php echo esc_attr( $skill_title ); ?>"
                          data-skill-percent="<?php echo esc_attr( $skill_percent ); ?>"
                          data-skill-prof="<?php echo esc_attr( $skill_prof ); ?>"
                          data-skill-desc="<?php echo esc_attr( $skill_desc ); ?>"
                          data-skill-exp="<?php echo esc_attr( $skill_experience ); ?>"
                          data-skill-color="<?php echo esc_attr( $skill_color ); ?>"
                        <?php endif; ?>
                      >
                        <span class="keycap__label">
                          <?php echo esc_html( $is_skill_key ? $skill_title : $key_def['label'] ); ?>
                        </span>
                      </button>
                    <?php endforeach; ?>
                  </div>
                <?php endforeach; ?>
              </div>

              <div class="keyboard-numpad">
                <?php foreach ( $keyboard_layout['numpad'] as $row ) : ?>
                  <div class="keyboard-row keyboard-row--numpad">
                    <?php foreach ( $row as $key_def ) : ?>
                      <?php
                      $skill = null;
                      $is_skill_key = ! empty( $key_def['skill_slot'] ) && isset( $keyboard_pool[ $keyboard_skill_index ] );
                      if ( $is_skill_key ) {
                          $skill = $keyboard_pool[ $keyboard_skill_index ];
                          $keyboard_skill_index++;
                      }

                      $key_classes = array( 'keycap', 'keycap--' . $key_def['size'] );
                      if ( $is_skill_key ) {
                          $key_classes[] = 'keycap--skill';
                      }

                      $skill_title = $skill ? $skill['title'] : '';
                      $skill_percent = $skill ? (int) $skill['percentage'] : 0;
                      $skill_prof = $skill ? ( $skill['proficiency'] ?: $skill['level'] ) : '';
                      $skill_desc = $skill ? $skill['description'] : '';
                      $skill_experience = $skill ? $skill['experience'] : '';
                      $skill_color = $skill ? $skill['color'] : '';
                      ?>
                      <button
                        class="<?php echo esc_attr( implode( ' ', $key_classes ) ); ?>"
                        type="button"
                        data-key
                        data-key-label="<?php echo esc_attr( $key_def['label'] ); ?>"
                        <?php if ( $is_skill_key ) : ?>
                          data-skill-id="<?php echo esc_attr( $skill['id'] ); ?>"
                          data-skill-title="<?php echo esc_attr( $skill_title ); ?>"
                          data-skill-percent="<?php echo esc_attr( $skill_percent ); ?>"
                          data-skill-prof="<?php echo esc_attr( $skill_prof ); ?>"
                          data-skill-desc="<?php echo esc_attr( $skill_desc ); ?>"
                          data-skill-exp="<?php echo esc_attr( $skill_experience ); ?>"
                          data-skill-color="<?php echo esc_attr( $skill_color ); ?>"
                        <?php endif; ?>
                      >
                        <span class="keycap__label">
                          <?php echo esc_html( $is_skill_key ? $skill_title : $key_def['label'] ); ?>
                        </span>
                      </button>
                    <?php endforeach; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
        <div class="key-tooltip" data-key-tooltip aria-hidden="true"></div>
      </div>
    </section>

    <section class="tech-network reveal-on-scroll">
      <div class="section-title"><h2>TECH NETWORK CLUSTERS</h2></div>
      <div class="tech-network__grid js-category-grid">
        <?php foreach ( $skill_categories as $category_index => $category ) : ?>
          <?php
          $skills_in_category = get_posts(
              array(
                  'post_type'   => 'skill',
                  'numberposts' => -1,
                  'meta_key'    => 'skill_order',
                  'orderby'     => 'meta_value_num',
                  'order'       => 'ASC',
                  'tax_query'   => array(
                      array(
                          'taxonomy' => 'skill_category',
                          'field'    => 'term_id',
                          'terms'    => $category->term_id,
                      ),
                  ),
              )
          );

          $node_count = max( 1, count( $skills_in_category ) );
          $network_search = strtolower( $category->name . ' ' . implode( ' ', wp_list_pluck( $skills_in_category, 'post_title' ) ) );
          ?>

          <article
            class="tech-cluster"
            data-category-card
            data-search="<?php echo esc_attr( $network_search ); ?>"
            style="--cluster-index: <?php echo esc_attr( $category_index ); ?>;">
            <svg class="tech-cluster__map" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
              <?php foreach ( $skills_in_category as $skill_index => $skill ) : ?>
                <?php
                $angle = ( -90 + ( 360 / $node_count ) * $skill_index ) * pi() / 180;
                $x_pos = 50 + cos( $angle ) * 37;
                $y_pos = 50 + sin( $angle ) * 32;
                ?>
                <line
                  class="tech-cluster__line"
                  data-network-line="<?php echo esc_attr( $skill_index ); ?>"
                  x1="50"
                  y1="50"
                  x2="<?php echo esc_attr( round( $x_pos, 2 ) ); ?>"
                  y2="<?php echo esc_attr( round( $y_pos, 2 ) ); ?>" />
              <?php endforeach; ?>
            </svg>

            <div class="tech-cluster__hub">
              <span>Cluster</span>
              <strong><?php echo esc_html( $category->name ); ?></strong>
              <em><?php echo esc_html( count( $skills_in_category ) ); ?> nodes</em>
            </div>

            <?php foreach ( $skills_in_category as $skill_index => $skill ) : ?>
              <?php
              $angle = ( -90 + ( 360 / $node_count ) * $skill_index ) * pi() / 180;
              $x_pos = 50 + cos( $angle ) * 37;
              $y_pos = 50 + sin( $angle ) * 32;
              ?>
              <span
                class="tech-cluster__node"
                data-network-node="<?php echo esc_attr( $skill_index ); ?>"
                style="--node-x: <?php echo esc_attr( round( $x_pos, 2 ) ); ?>%; --node-y: <?php echo esc_attr( round( $y_pos, 2 ) ); ?>%;">
                <?php echo esc_html( $skill->post_title ); ?>
              </span>
            <?php endforeach; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="developer-workspace reveal-on-scroll">
      <div class="section-title"><h2>DEVELOPER WORKSPACE</h2></div>
      <div class="workspace-console">
        <div class="workspace-console__monitor">
          <span>MONITOR AREA</span>
          <strong class="js-workspace-spotlight">Hover a tool to inspect the workspace stack.</strong>
        </div>

        <div class="workspace-console__surface">
          <?php if ( empty( $tool_skills ) ) : ?>
            <p>No tools added yet.</p>
          <?php else : ?>
            <?php foreach ( $tool_skills as $tool_index => $tool ) : ?>
              <article
                class="workspace-tool"
                data-tool-item
                data-tool-title="<?php echo esc_attr( $tool['title'] ); ?>"
                data-tool-prof="<?php echo esc_attr( $tool['proficiency'] ?: $tool['level'] ); ?>"
                style="--tool-index: <?php echo esc_attr( $tool_index ); ?>;">
                <div class="workspace-tool__icon">
                  <?php if ( ! empty( $tool['icon_url'] ) ) : ?>
                    <img src="<?php echo esc_url( $tool['icon_url'] ); ?>" alt="<?php echo esc_attr( $tool['icon_alt'] ); ?>">
                  <?php else : ?>
                    <i class="fa-solid fa-toolbox"></i>
                  <?php endif; ?>
                </div>
                <strong><?php echo esc_html( $tool['title'] ); ?></strong>
                <?php if ( ! empty( $tool['proficiency'] ) || ! empty( $tool['level'] ) ) : ?>
                  <span><?php echo esc_html( $tool['proficiency'] ?: $tool['level'] ); ?></span>
                <?php endif; ?>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="workspace-console__dock" aria-hidden="true">
          <span>WORKSPACE AREA</span>
          <span>DOCK AREA</span>
          <span>UTILITY AREA</span>
        </div>
        <div class="workspace-tooltip js-workspace-tooltip" aria-hidden="true"></div>
      </div>
    </section>

    <section class="rpg-skill-tree reveal-on-scroll">
      <div class="section-title"><h2>RPG SKILL TREE</h2></div>
      <div class="skill-tree journey-timeline">
        <?php
        ksort( $timeline_skills );
        $timeline_years = array_keys( $timeline_skills );
        $latest_year = ! empty( $timeline_years ) ? max( $timeline_years ) : '';

        foreach ( $timeline_skills as $year => $items ) :
        ?>
          <article class="skill-tree__checkpoint <?php echo (string) $year === (string) $latest_year ? 'is-current' : 'is-complete'; ?>">
            <div class="skill-tree__year">
              <span>Checkpoint</span>
              <strong><?php echo esc_html( $year ); ?></strong>
            </div>

            <div class="skill-tree__nodes">
              <?php foreach ( $items as $skill_index => $skill ) : ?>
                <?php $learning_description = (string) get_field( 'learning_description', $skill['id'] ); ?>
                <button
                  class="skill-tree__node"
                  type="button"
                  data-tree-node
                  data-tree-title="<?php echo esc_attr( $skill['title'] ); ?>"
                  data-tree-year="<?php echo esc_attr( $year ); ?>"
                  data-tree-desc="<?php echo esc_attr( $learning_description ); ?>"
                  style="--node-index: <?php echo esc_attr( $skill_index ); ?>;">
                  <span><?php echo esc_html( $skill['title'] ); ?></span>
                </button>
              <?php endforeach; ?>
            </div>
          </article>
        <?php endforeach; ?>
        <div class="skill-tree-tooltip js-skill-tree-tooltip" aria-hidden="true"></div>
      </div>
    </section>

    <?php
    $learning_years = array_filter( array_map( 'intval', array_keys( $timeline_skills ) ) );
    $years_experience = ! empty( $learning_years ) ? max( 1, (int) gmdate( 'Y' ) - min( $learning_years ) + 1 ) : 0;
    $resume = get_field( 'hero_resume', get_option( 'page_on_front' ) );
    $resume_url = is_array( $resume ) && ! empty( $resume['url'] ) ? $resume['url'] : home_url( '/resume/' );
    ?>
    <section class="mission-control reveal-on-scroll">
      <div class="mission-control__shell">
        <div class="mission-control__header">
          <div>
            <span>COMMAND CENTER</span>
            <h2>MISSION CONTROL</h2>
          </div>
          <strong><i></i>READY FOR NEW PROJECTS</strong>
        </div>

        <div class="mission-control__grid">
          <article><span>Projects Completed</span><strong class="js-counter" data-target="<?php echo esc_attr( $total_projects ); ?>">0</strong></article>
          <article><span>Skills Learned</span><strong class="js-counter" data-target="<?php echo esc_attr( $total_skills ); ?>">0</strong></article>
          <article><span>Technologies Used</span><strong class="js-counter" data-target="<?php echo esc_attr( $total_technologies ); ?>">0</strong></article>
          <article><span>Years of Experience</span><strong class="js-counter" data-target="<?php echo esc_attr( $years_experience ); ?>">0</strong></article>
        </div>

        <div class="mission-control__actions">
          <a href="<?php echo esc_url( get_post_type_archive_link( 'work' ) ); ?>">🚀 Launch Projects</a>
          <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">📨 Open Communication</a>
          <a href="<?php echo esc_url( $resume_url ); ?>">📄 View Resume</a>
        </div>
      </div>
    </section>

  </div>
</section>

<?php get_footer(); ?>
