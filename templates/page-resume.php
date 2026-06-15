<?php
/*
Template Name: Resume Page
*/

get_header();

$resume = natsume_portfolio_get_resume_file_data();
$resume_url = ! empty( $resume['url'] ) ? $resume['url'] : '';
$resume_title = ! empty( $resume['title'] ) ? $resume['title'] : 'Resume PDF';
?>

<main class="resume-page">
    <section class="resume-page__hero" aria-labelledby="resume-page-title">
        <div class="resume-page__container">
            <header class="resume-page__header">
                <span class="resume-page__eyebrow">Resume Viewer</span>
                <h1 id="resume-page-title">Open the Portfolio Resume</h1>
                <p>Review the embedded PDF in place, then download the same file with one clean click.</p>
            </header>

            <div class="resume-page__panel">
                <?php if ( $resume_url ) : ?>
                    <div class="resume-page__viewer" aria-label="<?php echo esc_attr( $resume_title ); ?>">
                        <iframe
                            src="<?php echo esc_url( $resume_url ); ?>"
                            title="<?php echo esc_attr( $resume_title ); ?>"
                            loading="lazy"
                        ></iframe>
                    </div>

                    <div class="resume-page__actions">
                        <a href="<?php echo esc_url( $resume_url ); ?>" class="btn-premium hero-primary resume-download" download>
                            <i class="fa-solid fa-download" aria-hidden="true"></i>
                            <span>Download PDF</span>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="resume-page__empty">
                        <h2>Resume file not available</h2>
                        <p>The viewer is ready, but no PDF has been attached yet. Add the resume file in the front page hero or About page resume field.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
