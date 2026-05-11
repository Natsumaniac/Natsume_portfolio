<?php
/**
 * Template Part: Skill Card
 *
 * @package NatsumePortfolio
 */

$icon        = get_field( 'skill_icon' );
$level       = get_field( 'skill_level' ) ?: 75;
$description = get_field( 'skill_description' );
$categories  = get_the_terms( get_the_ID(), 'skill_category' );
?>

<div class="skill">
    <span class="skill-name">
        <?php if ( $icon ) : ?>
            <i class="<?php echo esc_attr($icon); ?>"></i>
        <?php else : ?>
            <i class="fas fa-code"></i>
        <?php endif; ?>
        <?php the_title(); ?>
    </span>
    <div class="skill-bar">
        <div class="skill-progress" style="width: <?php echo esc_attr($level); ?>%"></div>
    </div>
    <span class="skill-percent"><?php echo esc_html($level); ?>%</span>
</div>
