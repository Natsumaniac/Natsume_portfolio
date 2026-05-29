<?php
/*
Template Name: Resume Page
*/

get_header();

$resume = get_field('hero_resume', get_option('page_on_front'));
?>

<section class="resume-page">

    <div class="container">

        <h1>Resume</h1>

        <?php if ($resume) : ?>

            <div class="resume-actions">
                <a href="<?php echo esc_url($resume['url']); ?>" 
                   download
                   class="btn primary-btn">
                   Download Resume
                </a>
            </div>

            <iframe 
                src="<?php echo esc_url($resume['url']); ?>" 
                width="100%" 
                height="900px">
            </iframe>

        <?php endif; ?>

    </div>

</section>

<?php get_footer(); ?>