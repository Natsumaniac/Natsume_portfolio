<?php
/**
 * Archive: Skills
 *
 * @package NatsumePortfolio
 */

get_header();

if ( ! function_exists( 'natsume_portfolio_sanitize_skill_logo_svg' ) ) {
    /**
     * Sanitize inline technology logos from ACF while preserving SVG icon markup.
     *
     * @param string $svg Raw SVG markup.
     * @return string
     */
    function natsume_portfolio_sanitize_skill_logo_svg( $svg ) {
        $svg = trim( (string) $svg );
        if ( '' === $svg ) {
            return '';
        }

        $svg_attributes = array(
            'aria-hidden'       => true,
            'aria-label'        => true,
            'class'             => true,
            'clip-path'         => true,
            'clip-rule'         => true,
            'cx'                => true,
            'cy'                => true,
            'd'                 => true,
            'fill'              => true,
            'fill-opacity'      => true,
            'fill-rule'         => true,
            'focusable'         => true,
            'height'            => true,
            'href'              => true,
            'id'                => true,
            'mask'              => true,
            'offset'            => true,
            'opacity'           => true,
            'points'            => true,
            'preserveAspectRatio' => true,
            'r'                 => true,
            'role'              => true,
            'rx'                => true,
            'ry'                => true,
            'stop-color'        => true,
            'stop-opacity'      => true,
            'stroke'            => true,
            'stroke-dasharray'  => true,
            'stroke-linecap'    => true,
            'stroke-linejoin'   => true,
            'stroke-miterlimit' => true,
            'stroke-opacity'    => true,
            'stroke-width'      => true,
            'style'             => true,
            'transform'         => true,
            'viewBox'           => true,
            'viewbox'           => true,
            'width'             => true,
            'x'                 => true,
            'x1'                => true,
            'x2'                => true,
            'xlink:href'        => true,
            'xmlns'             => true,
            'xmlns:xlink'       => true,
            'y'                 => true,
            'y1'                => true,
            'y2'                => true,
        );

        $allowed_svg = array(
            'svg'            => $svg_attributes,
            'g'              => $svg_attributes,
            'path'           => $svg_attributes,
            'circle'         => $svg_attributes,
            'rect'           => $svg_attributes,
            'line'           => $svg_attributes,
            'polyline'       => $svg_attributes,
            'polygon'        => $svg_attributes,
            'ellipse'        => $svg_attributes,
            'defs'           => $svg_attributes,
            'clipPath'       => $svg_attributes,
            'clippath'       => $svg_attributes,
            'linearGradient' => $svg_attributes,
            'lineargradient' => $svg_attributes,
            'radialGradient' => $svg_attributes,
            'radialgradient' => $svg_attributes,
            'stop'           => $svg_attributes,
            'mask'           => $svg_attributes,
            'pattern'        => $svg_attributes,
            'title'          => array(),
            'desc'           => array(),
            'use'            => $svg_attributes,
        );

        return wp_kses( $svg, $allowed_svg );
    }
}

if ( ! function_exists( 'natsume_portfolio_render_keyboard_key_content' ) ) {
    /**
     * Render keyboard key content with SVG-first skill logo support.
     *
     * @param array|null $skill Skill data.
     * @param string     $fallback_label Keyboard fallback label.
     * @return void
     */
    function natsume_portfolio_render_keyboard_key_content( $skill, $fallback_label ) {
        $skill_title = is_array( $skill ) && ! empty( $skill['title'] ) ? (string) $skill['title'] : '';
        $logo_svg    = is_array( $skill ) && ! empty( $skill['logo_svg'] )
            ? natsume_portfolio_sanitize_skill_logo_svg( $skill['logo_svg'] )
            : '';

        if ( '' !== $skill_title && '' !== $logo_svg ) {
            ?>
            <span class="keycap__logo" aria-hidden="true">
              <?php echo $logo_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </span>
            <span class="screen-reader-text"><?php echo esc_html( $skill_title ); ?></span>
            <?php
            return;
        }
        ?>
        <span class="keycap__label">
          <?php echo esc_html( $skill_title ? $skill_title : $fallback_label ); ?>
        </span>
        <?php
    }
}

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
        $logo_svg          = (string) get_field( 'skill_logo_svg', $skill_id );
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
            'logo_svg'    => $logo_svg,
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

    <section class="stack-balls reveal-on-scroll">
      <div class="balls-layout js-balls-layout">
        <div class="balls-stage-col">
          <div class="stack-balls__stage js-balls-stage">
            <div class="stack-balls__plate" aria-label="Skills section title">
              <span class="stack-balls__plate-eyebrow">Developer Operating System</span>
              <h1 class="stack-balls__plate-title">Skills Kernel</h1>
              <p class="stack-balls__plate-copy">Inspect technologies, tools, experience, and execution patterns across the development ecosystem.</p>
            </div>
            <?php foreach ( $ball_skills as $index => $ball ) : ?>
              <div
                class="stack-ball skill-basketball"
                style="--label-tilt: <?php echo esc_attr( ( ( $index % 5 ) - 2 ) * 1.35 ); ?>deg;"
                data-ball
                data-index="<?php echo esc_attr( $index ); ?>"
                data-skill-id="<?php echo esc_attr( $ball['id'] ); ?>"
                data-skill-color="<?php echo esc_attr( $ball['color'] ); ?>">
                <span class="stack-ball__label"><?php echo esc_html( $ball['title'] ); ?></span>
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

    <?php natsume_portfolio_render_section_transition( 'section-transition--circuit' ); ?>

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
      <div class="os-section-heading">
        <span class="line" aria-hidden="true"></span>
        <div>
          <p>Input Device</p>
          <h2>Developer Keyboard</h2>
          <span>A workstation-style command surface where every mapped key loads a real skill profile into the monitor.</span>
        </div>
      </div>
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
                      <?php natsume_portfolio_render_keyboard_key_content( $skill, $key_def['label'] ); ?>
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
                        <?php natsume_portfolio_render_keyboard_key_content( $skill, $key_def['label'] ); ?>
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
                        <?php natsume_portfolio_render_keyboard_key_content( $skill, $key_def['label'] ); ?>
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

    <?php natsume_portfolio_render_section_transition( 'section-transition--scan' ); ?>

    <?php
    ksort( $timeline_skills );
    $learning_years = array_filter( array_map( 'intval', array_keys( $timeline_skills ) ) );
    $years_experience = ! empty( $learning_years ) ? max( 1, (int) gmdate( 'Y' ) - min( $learning_years ) + 1 ) : 0;
    $resume = get_field( 'hero_resume', get_option( 'page_on_front' ) );
    $resume_url = is_array( $resume ) && ! empty( $resume['url'] ) ? $resume['url'] : home_url( '/resume/' );

    $terminal_skills = array_map(
        static function ( $skill ) {
            return array(
                'title'       => $skill['title'],
                'level'       => $skill['proficiency'] ?: $skill['level'],
                'percentage'  => (int) $skill['percentage'],
                'experience'  => $skill['experience'],
                'description' => wp_strip_all_tags( $skill['description'] ),
                'categories'  => $skill['category_names'],
                'tags'        => array_slice( (array) $skill['tags'], 0, 5 ),
            );
        },
        $skills_data
    );

    $terminal_projects_query = new WP_Query(
        array(
            'post_type'      => 'work',
            'posts_per_page' => 5,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        )
    );

    $terminal_projects = array();
    if ( $terminal_projects_query->have_posts() ) {
        while ( $terminal_projects_query->have_posts() ) {
            $terminal_projects_query->the_post();
            $project_tech_raw = (string) get_field( 'work_technologies', get_the_ID() );
            $project_tech = function_exists( 'natsume_portfolio_parse_lines' )
                ? natsume_portfolio_parse_lines( $project_tech_raw )
                : preg_split( '/\r\n|\r|\n|,/', $project_tech_raw );

            $terminal_projects[] = array(
                'title'       => get_the_title(),
                'url'         => get_permalink(),
                'description' => wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 18, '...' ),
                'tech'        => array_slice( array_values( array_filter( array_map( 'trim', (array) $project_tech ) ) ), 0, 4 ),
            );
        }
        wp_reset_postdata();
    }

    $terminal_categories = array();
    if ( ! empty( $skill_categories ) && ! is_wp_error( $skill_categories ) ) {
        foreach ( $skill_categories as $category ) {
            $terminal_categories[] = array(
                'name'  => $category->name,
                'count' => (int) $category->count,
            );
        }
    }

    $terminal_data = array(
        'stats' => array(
            'projects'     => $total_projects,
            'skills'       => $total_skills,
            'technologies' => count( $tech_set ),
            'years'        => $years_experience,
        ),
        'skills'     => $terminal_skills,
        'tools'      => array_values( array_map( static function ( $tool ) {
            return $tool['title'];
        }, $tool_skills ) ),
        'projects'   => $terminal_projects,
        'categories' => $terminal_categories,
        'links'      => array(
            'projects' => get_post_type_archive_link( 'work' ),
            'contact'  => home_url( '/contact/' ),
            'resume'   => $resume_url,
            'about'    => home_url( '/about/' ),
        ),
        'about'      => array(
            'name'    => get_bloginfo( 'name' ),
            'tagline' => get_bloginfo( 'description' ),
        ),
    );
    ?>

    <?php
    $terminal_command_icons = array(
        'help'       => 'fa-solid fa-circle-question',
        'skills'     => 'fa-solid fa-microchip',
        'projects'   => 'fa-solid fa-diagram-project',
        'experience' => 'fa-solid fa-chart-line',
        'resume'     => 'fa-solid fa-file-lines',
        'contact'    => 'fa-solid fa-satellite-dish',
        'about'      => 'fa-solid fa-user-astronaut',
    );
    ?>

    <section class="natsume-os-terminal reveal-on-scroll" data-natsume-terminal>
      <div class="terminal-particles" aria-hidden="true">
        <?php for ( $particle_index = 0; $particle_index < 22; $particle_index++ ) : ?>
          <span style="--particle-index: <?php echo esc_attr( $particle_index ); ?>; --particle-x: <?php echo esc_attr( ( $particle_index * 37 ) % 100 ); ?>%; --particle-y: <?php echo esc_attr( ( $particle_index * 23 ) % 100 ); ?>%;"></span>
        <?php endfor; ?>
      </div>

      <div class="natsume-os-terminal__header">
        <div class="skills-page-heading">
          <span class="line" aria-hidden="true"></span>
          <p class="skills-page-heading__eyebrow">Boot Sequence Complete</p>
          <h2>NATSUME OS Terminal</h2>
        </div>
        <p>Portfolio kernel initialized. Type commands, run modules, and inspect live project telemetry from a focused developer console.</p>
      </div>

      <div class="os-terminal-shell">
        <div class="os-terminal-topbar" aria-hidden="true">
          <span></span><span></span><span></span>
          <strong>natsume-os://skills/kernel</strong>
          <em>SYSTEM STATUS: ONLINE</em>
        </div>

        <div class="os-terminal-body">
          <nav class="os-command-rail" aria-label="Terminal commands">
            <?php foreach ( array( 'help', 'skills', 'projects', 'experience', 'resume', 'contact', 'about' ) as $command ) : ?>
              <button type="button" data-terminal-command="<?php echo esc_attr( $command ); ?>">
                <i class="<?php echo esc_attr( $terminal_command_icons[ $command ] ); ?>" aria-hidden="true"></i>
                <span>./<?php echo esc_html( $command ); ?></span>
                <em>module</em>
              </button>
            <?php endforeach; ?>
          </nav>

          <div class="os-console">
            <div class="os-console__scanline" aria-hidden="true"></div>
            <div class="os-console__corners" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
            <div class="os-console__history js-terminal-history" aria-live="polite"></div>
            <label class="os-console__prompt" for="natsume-terminal-input">
              <span>visitor@natsume-os:~$</span>
              <input id="natsume-terminal-input" class="js-terminal-input" type="text" autocomplete="off" spellcheck="false" value="help">
              <i aria-hidden="true"></i>
            </label>
          </div>

          <aside class="os-status-stream" aria-label="Portfolio status panel">
            <div class="os-status-stream__header">
              <span><i></i> MONITOR</span>
              <strong>LIVE</strong>
            </div>
            <p class="is-online"><span>system.online</span><strong>true</strong><em></em></p>
            <p style="--meter: <?php echo esc_attr( min( 100, max( 8, $total_projects * 8 ) ) ); ?>%;"><span>projects.completed</span><strong class="js-counter" data-target="<?php echo esc_attr( $total_projects ); ?>">0</strong><em></em></p>
            <p style="--meter: <?php echo esc_attr( min( 100, max( 12, $total_skills * 6 ) ) ); ?>%;"><span>skills.mastered</span><strong class="js-counter" data-target="<?php echo esc_attr( $total_skills ); ?>">0</strong><em></em></p>
            <p style="--meter: <?php echo esc_attr( min( 100, max( 18, count( $tech_set ) * 2 ) ) ); ?>%;"><span>technologies.loaded</span><strong class="js-counter" data-target="<?php echo esc_attr( count( $tech_set ) ); ?>">0</strong><em></em></p>
            <p style="--meter: <?php echo esc_attr( min( 100, max( 10, $years_experience * 12 ) ) ); ?>%;"><span>experience.years</span><strong class="js-counter" data-target="<?php echo esc_attr( $years_experience ); ?>">0</strong><em></em></p>
          </aside>
        </div>

        <div class="os-terminal-actions" aria-label="Primary terminal actions">
          <a href="<?php echo esc_url( get_post_type_archive_link( 'work' ) ); ?>" data-terminal-action="projects"><span>$</span> run projects <em>launch://works</em></a>
          <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" data-terminal-action="contact"><span>$</span> open comms <em>open://contact</em></a>
          <a href="<?php echo esc_url( $resume_url ); ?>" data-terminal-action="resume"><span>$</span> cat resume <em>resume://cv</em></a>
        </div>
      </div>

      <script type="application/json" id="natsume-terminal-data">
        <?php echo wp_json_encode( $terminal_data ); ?>
      </script>
    </section>

  </div>
</section>

<?php get_footer(); ?>
