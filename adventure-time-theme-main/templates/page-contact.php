<?php
/**
 * Template Name: Contact Page
 *
 * @package DevPortfolio
 */

get_header();

$heading   = get_field( 'contact_heading' ) ?: 'Get In Touch';
$desc      = get_field( 'contact_description' );
$email     = get_field( 'contact_email' );
$phone     = get_field( 'contact_phone' );
$location  = get_field( 'contact_location' );
$shortcode = get_field( 'contact_form_shortcode' );
?>

<section class="section section--contact">
    <div class="container">
        <h2 class="section__title"><?php echo esc_html( $heading ); ?></h2>
        <?php if ( $desc ) : ?>
            <p class="section__subtitle"><?php echo esc_html( $desc ); ?></p>
        <?php endif; ?>

        <div class="contact-grid">
            <div class="contact__info">
                <?php if ( $email ) : ?>
                    <div class="contact__item">
                        <span class="contact__icon">&#9993;</span>
                        <div>
                            <strong>Email</strong>
                            <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ( $phone ) : ?>
                    <div class="contact__item">
                        <span class="contact__icon">&#9742;</span>
                        <div>
                            <strong>Phone</strong>
                            <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ( $location ) : ?>
                    <div class="contact__item">
                        <span class="contact__icon">&#9906;</span>
                        <div>
                            <strong>Location</strong>
                            <p><?php echo esc_html( $location ); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="contact__form">
                <?php if ( $shortcode ) : ?>
                    <?php echo do_shortcode( $shortcode ); ?>
                <?php else : ?>
                    <p class="contact__placeholder">Contact form shortcode not configured. Add it in the Contact Page settings.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
