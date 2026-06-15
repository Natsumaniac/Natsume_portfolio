<?php
/**
 * Template Name: Contact Page
 *
 * @package NatsumePortfolio
 */

get_header();

$heading       = get_field( 'contact_heading' ) ?: 'Start a Project Conversation';
$description   = get_field( 'contact_description' ) ?: 'For freelance builds, internship opportunities, and thoughtful collaborations, send the brief directly. I keep communication focused, practical, and fast.';
$email         = get_field( 'contact_email' ) ?: 'neilroyomongos0523@gmail.com';
$phone         = get_field( 'contact_phone' ) ?: '09487145146';
$location      = get_field( 'contact_location' ) ?: 'Lupon, Davao Oriental, Philippines';
$shortcode     = get_field( 'contact_form_shortcode' );
$response_time = '24–48 Hours';
$works_link    = get_post_type_archive_link( 'work' ) ?: home_url( '/work/' );
?>

<main class="contact-terminal">
    <section class="contact-location-hero" aria-labelledby="contact-location-title">
        <div class="contact-location-map" aria-hidden="true">
            <iframe
                class="contact-location-map__iframe"
                title="Google Map showing Lupon, Davao Oriental, Philippines"
                src="https://www.google.com/maps?q=Lupon,+Davao+Oriental,+Philippines&z=12&output=embed"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </div>

        <div class="contact-terminal__container contact-location-hero__overlay">
            <div class="contact-location-hero__widget contact-location-card">
                <div class="contact-location-hero__widget-head">
                    <span class="contact-terminal__label">Location</span>
                    <div class="contact-location-hero__widget-location">
                        <strong>Lupon, Davao Oriental</strong>
                        <span>Philippines</span>
                    </div>
                </div>

                <div class="contact-location-hero__widget-list" aria-label="Contact availability">
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Available for Freelance Work</span>
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Open for Collaborations</span>
                    <span><i class="fa-solid fa-check" aria-hidden="true"></i> Internship Opportunities</span>
                    <strong>Response Time: <?php echo esc_html( $response_time ); ?></strong>
                </div>

                <a class="contact-terminal__button btn-premium hero-secondary contact-location-hero__cta" href="#contact-form">
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                    Send Message
                </a>
            </div>
        </div>
    </section>

    <section class="contact-hub" aria-labelledby="contact-form-title">
        <div class="contact-terminal__container">
            <div class="contact-hub__grid">
                <aside class="contact-hub__intro">
                    <div>
                        <span class="contact-terminal__label">Get In Touch</span>
                        <h2 id="contact-form-title">Let&rsquo;s talk about the next project</h2>
                        <p>Share the goal, timeline, and what kind of support you need. I bring clarity, speed, and polished front-end execution for WordPress, portfolio systems, and developer-facing interfaces.</p>
                    </div>

<article class="contact-hub__status-card" aria-label="Current availability">
                    <span class="contact-hub__status-label">Current Availability</span>
                    <div class="contact-hub__status-grid">
                        <div class="contact-hub__status-item">
                            <strong><i class="fa-solid fa-envelope" aria-hidden="true"></i> Email</strong>
                            <?php if ( $email ) : ?>
                                <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                            <?php else : ?>
                                <span>Not configured</span>
                            <?php endif; ?>
                        </div>
                        <div class="contact-hub__status-item">
                            <strong><i class="fa-solid fa-phone" aria-hidden="true"></i> Phone Number</strong>
                            <?php if ( $phone ) : ?>
                                <a href="tel:<?php echo esc_attr( preg_replace( '/\D+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                            <?php else : ?>
                                <span>Not configured</span>
                            <?php endif; ?>
                        </div>
                        <div class="contact-hub__status-item">
                            <strong><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Location</strong>
                            <span><?php echo esc_html( $location ); ?></span>
                        </div>
                        <div class="contact-hub__status-item">
                            <strong><i class="fa-solid fa-clock" aria-hidden="true"></i> Response Time</strong>
                            <span><?php echo esc_html( $response_time ); ?></span>
                        </div>
                        <div class="contact-hub__status-item">
                            <strong><i class="fa-solid fa-briefcase" aria-hidden="true"></i> Freelance Status</strong>
                            <span>Available</span>
                        </div>
                        <div class="contact-hub__status-item">
                            <strong><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i> Internship Status</strong>
                            <span>Open</span>
                        </div>
                        <div class="contact-hub__status-item">
                            <strong><i class="fa-solid fa-handshake-angle" aria-hidden="true"></i> Collaboration Status</strong>
                            <span>Welcome</span>
                        </div>
                    </div>
                </article>

                </aside>

                <div class="contact-hub__panel" id="contact-form">
                    <div class="contact-form-panel">
                        <div class="contact-form-panel__bar" aria-hidden="true">
                            <span></span><span></span><span></span>
                            <strong>message://compose</strong>
                        </div>
                        <?php if ( $shortcode ) : ?>
                            <?php echo do_shortcode( $shortcode ); ?>
                        <?php else : ?>
                            <p class="contact-terminal__placeholder">Contact form shortcode not configured. Add it in the Contact Page settings.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php natsume_portfolio_render_section_transition( 'section-transition--circuit' ); ?>

    <section class="contact-final-cta" aria-labelledby="contact-final-title">
        <div class="contact-terminal__container contact-final-cta__inner">
            <div class="contact-final-cta__copy">
                <span class="contact-terminal__label">Ready to Start a Conversation?</span>
                <h2 id="contact-final-title">Ready to Start a Conversation?</h2>
                <p>Whether it is a freelance project, internship opportunity, collaboration, or portfolio discussion, I am open to meaningful conversations.</p>
                <div class="contact-terminal__actions">
                    <a class="contact-terminal__button btn-premium hero-secondary" href="#contact-form">
                        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                        Start Contact
                    </a>
                    <a class="contact-terminal__button btn-premium hero-secondary" href="<?php echo esc_url( $works_link ); ?>">
                        <i class="fa-solid fa-table-cells-large" aria-hidden="true"></i>
                        View Projects
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="contact-feedback" data-contact-feedback aria-live="polite" aria-hidden="true">
    <div class="contact-feedback__loader" data-contact-loader>
        <span></span>
    </div>
    <div class="contact-feedback__toast" data-contact-toast>
        Thank you for your message. It has been sent.
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var feedback = document.querySelector('[data-contact-feedback]');
    var toastTimer;

    function setFeedbackState(state) {
        if (!feedback) return;

        feedback.dataset.state = state || '';
        feedback.setAttribute('aria-hidden', state ? 'false' : 'true');

        if (state === 'success') {
            window.clearTimeout(toastTimer);
            toastTimer = window.setTimeout(function () {
                setFeedbackState('');
            }, 4600);
        }
    }

    document.querySelectorAll('.contact-form-panel input[type="submit"]').forEach(function (input) {
        if (input.dataset.iconified === '1') return;

        var button = document.createElement('button');
        button.type = 'submit';
        button.className = input.className || '';
        button.innerHTML = '<i class="fa-solid fa-paper-plane" aria-hidden="true"></i><span>' + (input.value || 'Submit') + '</span>';
        input.dataset.iconified = '1';
        input.replaceWith(button);
    });

    document.querySelectorAll('.contact-form-panel form').forEach(function (form) {
        form.addEventListener('submit', function () {
            setFeedbackState('loading');
        });
    });

    document.addEventListener('wpcf7mailsent', function (event) {
        if (!event.target.closest('.contact-form-panel')) return;
        setFeedbackState('success');
    });

    document.addEventListener('wpcf7invalid', function (event) {
        if (!event.target.closest('.contact-form-panel')) return;
        setFeedbackState('');
    });

    document.addEventListener('wpcf7mailfailed', function (event) {
        if (!event.target.closest('.contact-form-panel')) return;
        setFeedbackState('');
    });

    document.addEventListener('wpcf7spam', function (event) {
        if (!event.target.closest('.contact-form-panel')) return;
        setFeedbackState('');
    });
});
</script>

<?php get_footer(); ?>
