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
$keyboard_chunks = array_chunk(
    $keyboard_skills,
    3
);

if ( empty( $keyboard_skills ) ) {
    $keyboard_chunks = array_chunk( $skills_data, 3 );
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

    <section class="skill-keyboard reveal-on-scroll">
      <div class="section-title"><h2>INTERACTIVE SKILL KEYBOARD</h2></div>
      <div class="keyboard-wrap">
        <?php foreach ($keyboard_chunks as $row) : ?>
          <div class="keyboard-row">
            <?php foreach ($row as $skill) :
              $tip_percent = $skill['percentage'];
              $tip_title   = $skill['title'];
              $tip_prof    = $skill['proficiency'];
              $tip_desc    = $skill['description'];
            ?>
              <button class="keycap" type="button" data-key data-tip-title="<?php echo esc_attr( $tip_title ); ?>" data-tip-percent="<?php echo esc_attr( $tip_percent ); ?>" data-tip-prof="<?php echo esc_attr( $tip_prof ); ?>" data-tip-desc="<?php echo esc_attr( $tip_desc ); ?>">
                <?php echo esc_html( $skill['title'] ); ?>
              </button>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
        <div class="key-tooltip" data-key-tooltip></div>
      </div>
    </section>

    <section class="complete-categories reveal-on-scroll">
  <div class="section-title"><h2>COMPLETE SKILL CATEGORIES</h2></div>
  <div class="category-grid js-category-grid">

    <?php foreach ( $skill_categories as $category ) : ?>

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
      ?>

      <article class="category-card"
          data-category-card
          data-search="<?php echo esc_attr( strtolower( $category->name ) ); ?>">

          <h3><?php echo esc_html( $category->name ); ?></h3>

          <div class="category-tags">
              <?php foreach ( $skills_in_category as $skill ) : ?>
                  <span><?php echo esc_html( $skill->post_title ); ?></span>
              <?php endforeach; ?>
          </div>

      </article>

    <?php endforeach; ?>

  </div>
</section>

    <section class="tools-section reveal-on-scroll">
      <div class="section-title"><h2>TOOLS &amp; SOFTWARE</h2></div>
      <div class="tools-grid">

    <?php if ( empty( $tool_skills ) ) : ?>

        <p>No tools added yet.</p>

    <?php else : ?>

        <?php foreach ($tool_skills as $tool) : ?>

            <article class="tool-item">
                <div class="tool-item__icon">
                    <i class="fa-solid fa-toolbox"></i>
                </div>

                <h3><?php echo esc_html($tool['title']); ?></h3>
            </article>

        <?php endforeach; ?>

    <?php endif; ?>

</div>
    </section>

    <section class="learning-journey reveal-on-scroll">
    <div class="section-title">
        <h2>LEARNING JOURNEY</h2>
    </div>

    <div class="journey-timeline">

        <?php
        krsort($timeline_skills);

        foreach ($timeline_skills as $year => $items) :
        ?>

            <article>
                <h3><?php echo esc_html($year); ?></h3>

                <?php foreach ($items as $skill) : ?>

                    <div class="timeline-skill">
                        <strong>
                            <?php echo esc_html($skill['title']); ?>
                        </strong>

                        <p>
                            <?php
                            echo esc_html(
                                get_field(
                                    'learning_description',
                                    $skill['id']
                                )
                            );
                            ?>
                        </p>
                    </div>

                <?php endforeach; ?>

            </article>

        <?php endforeach; ?>

    </div>
</section>

    <section class="skills-cta reveal-on-scroll">
      <h2>Interested in working together?</h2>
      <div class="skills-cta__actions">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'work' ) ); ?>">View My Projects</a>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact Me</a>
      </div>
    </section>

  </div>
</section>

<?php get_footer(); ?>
