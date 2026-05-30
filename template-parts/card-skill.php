<?php
/**
 * Template Part: Skill Card
 *
 * @package NatsumePortfolio
 */

$skill_icon         = get_field( 'skill_icon' );
$skill_proficiency  = get_field( 'skill_proficiency' );
$skill_percentage   = (int) get_field( 'skill_percentage' );
$skill_description  = get_field( 'skill_description' );
$skill_experience   = get_field( 'skill_experience' );
$skill_color        = get_field( 'skill_color' );
$skill_level        = get_field( 'skill_level' );
$related_tech_value = get_field( 'related_technologies' );
$skill_terms        = get_the_terms( get_the_ID(), 'skill_category' );

$skill_percentage = max( 0, min( 100, $skill_percentage ) );
$skill_color      = $skill_color ? sanitize_hex_color( $skill_color ) : '';
$skill_color      = $skill_color ? $skill_color : '#ff7a00';
$card_inline_css  = '--skill-accent:' . esc_attr( $skill_color ) . '; --skill-progress:' . esc_attr( $skill_percentage ) . '%;';

$icon_url = '';
$icon_alt = '';

if ( is_array( $skill_icon ) && ! empty( $skill_icon['url'] ) ) {
    $icon_url = $skill_icon['url'];
    $icon_alt = ! empty( $skill_icon['alt'] ) ? $skill_icon['alt'] : get_the_title();
} elseif ( is_numeric( $skill_icon ) ) {
    $icon_url = wp_get_attachment_image_url( (int) $skill_icon, 'thumbnail' );
    $icon_alt = get_the_title();
} elseif ( is_string( $skill_icon ) ) {
    $icon_url = esc_url_raw( $skill_icon );
    $icon_alt = get_the_title();
}

$skill_categories = array();
if ( $skill_terms && ! is_wp_error( $skill_terms ) ) {
    foreach ( $skill_terms as $term ) {
        $skill_categories[] = $term->slug;
    }
}

$tech_tags = array();
if ( ! empty( $related_tech_value ) ) {
    if ( function_exists( 'natsume_portfolio_parse_lines' ) ) {
        $tech_tags = natsume_portfolio_parse_lines( $related_tech_value );
    } else {
        $tech_tags = preg_split( '/\r\n|\r|\n|,/', $related_tech_value );
        $tech_tags = array_filter( array_map( 'trim', $tech_tags ) );
    }
}
?>

<article
    class="skill-card reveal-on-scroll"
    data-skill-card
    data-categories="<?php echo esc_attr( implode( ' ', $skill_categories ) ); ?>"
    style="<?php echo esc_attr( $card_inline_css ); ?>">

    <header class="skill-card__header">
        <div class="skill-card__icon-wrap">
            <?php if ( $icon_url ) : ?>
                <img class="skill-card__icon" src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $icon_alt ); ?>" loading="lazy">
            <?php else : ?>
                <i class="fa-solid fa-code skill-card__icon-fallback"></i>
            <?php endif; ?>
        </div>

        <div class="skill-card__title-wrap">
            <h3 class="skill-card__title"><?php the_title(); ?></h3>
            <?php if ( $skill_level ) : ?>
                <p class="skill-card__level"><?php echo esc_html( $skill_level ); ?></p>
            <?php endif; ?>
        </div>

        <div class="skill-card__percent"><?php echo esc_html( $skill_percentage ); ?>%</div>
    </header>

    <?php if ( $skill_description ) : ?>
        <p class="skill-card__description"><?php echo esc_html( $skill_description ); ?></p>
    <?php endif; ?>

    <div class="skill-card__meta-row">
        <?php if ( $skill_experience ) : ?>
            <span class="skill-chip"><i class="fa-regular fa-clock"></i><?php echo esc_html( $skill_experience ); ?></span>
        <?php endif; ?>
        <?php if ( $skill_proficiency ) : ?>
            <span class="skill-chip"><i class="fa-solid fa-gauge-high"></i><?php echo esc_html( $skill_proficiency ); ?></span>
        <?php endif; ?>
    </div>

    <div class="skill-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr( $skill_percentage ); ?>">
        <span class="skill-progress__fill js-progress-fill" data-progress="<?php echo esc_attr( $skill_percentage ); ?>"></span>
    </div>

    <?php if ( $tech_tags ) : ?>
        <div class="skill-card__tags" aria-label="Related technologies">
            <?php foreach ( $tech_tags as $tech ) : ?>
                <span class="skill-tag"><?php echo esc_html( $tech ); ?></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</article>
