<?php
/**
 * Archive: Certificates
 *
 * @package NatsumePortfolio
 */

get_header();
?>

<main class="certificate-vault">
    <?php
    $orbit_query = new WP_Query(
        array(
            'post_type'      => 'certificate',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        )
    );
    ?>

    <?php
    $certificate_items = array();
    if ( $orbit_query->have_posts() ) {
        while ( $orbit_query->have_posts() ) {
            $orbit_query->the_post();
            $cert_image = get_field( 'cert_image' );
            $image_url  = '';
            $image_alt  = get_the_title();

            if ( is_array( $cert_image ) && ! empty( $cert_image['url'] ) ) {
                $image_url = $cert_image['url'];
                $image_alt = ! empty( $cert_image['alt'] ) ? $cert_image['alt'] : get_the_title();
            }

            $certificate_items[] = array(
                'id'          => get_the_ID(),
                'title'       => get_the_title(),
                'issuer'      => (string) get_field( 'cert_issuer' ),
                'date'        => (string) get_field( 'cert_date' ),
                'credential'  => (string) get_field( 'cert_credential_id' ),
                'url'         => (string) get_field( 'cert_url' ),
                'details'     => (string) get_field( 'certificate_details' ),
                'skills'      => (string) get_field( 'cert_skills' ),
                'featured'    => (bool) get_field( 'cert_featured' ),
                'image_url'   => $image_url,
                'image_alt'   => $image_alt,
            );
        }
        wp_reset_postdata();
    }
    ?>

    <?php if ( ! empty( $certificate_items ) ) : ?>
        <section class="certificate-vault-orbit" aria-label="Certificate carousel hero">
            <div class="certificate-vault-orbit__intro">
                <span class="certificate-vault__label">Certification Vault</span>
                <h1>Verified Skills &amp; Credentials</h1>
            </div>

            <div class="certificate-vault-orbit__carousel" data-certificate-carousel>
                <div class="certificate-vault-orbit__track" data-certificate-track>
                    <?php for ( $copy = 0; $copy < 2; $copy++ ) : ?>
                        <div class="certificate-vault-orbit__group" aria-hidden="<?php echo $copy ? 'true' : 'false'; ?>">
                            <?php foreach ( $certificate_items as $index => $certificate_item ) : ?>
                                <button
                                    type="button"
                                    class="certificate-vault-orbit-card"
                                    data-orbit-card
                                    data-index="<?php echo esc_attr( $index ); ?>"
                                    data-copy="<?php echo esc_attr( $copy ); ?>"
                                    data-title="<?php echo esc_attr( $certificate_item['title'] ); ?>"
                                    data-issuer="<?php echo esc_attr( $certificate_item['issuer'] ); ?>"
                                    data-date="<?php echo esc_attr( $certificate_item['date'] ); ?>"
                                    data-credential="<?php echo esc_attr( $certificate_item['credential'] ); ?>"
                                    data-url="<?php echo esc_url( $certificate_item['url'] ); ?>"
                                    data-details="<?php echo esc_attr( wp_strip_all_tags( $certificate_item['details'] ) ); ?>"
                                    data-skills="<?php echo esc_attr( wp_strip_all_tags( $certificate_item['skills'] ) ); ?>"
                                    data-featured="<?php echo esc_attr( $certificate_item['featured'] ? '1' : '0' ); ?>"
                                    data-image="<?php echo esc_url( $certificate_item['image_url'] ); ?>"
                                    data-image-alt="<?php echo esc_attr( $certificate_item['image_alt'] ); ?>"
                                >
                                    <span class="certificate-vault-orbit-card__surface">
                                        <?php if ( $certificate_item['image_url'] ) : ?>
                                            <img src="<?php echo esc_url( $certificate_item['image_url'] ); ?>" alt="<?php echo esc_attr( $certificate_item['image_alt'] ); ?>">
                                        <?php else : ?>
                                            <span class="certificate-vault-orbit-card__placeholder" aria-hidden="true">
                                                <i class="fa-solid fa-certificate"></i>
                                            </span>
                                        <?php endif; ?>
                                        <span class="certificate-vault-orbit-card__badge"><?php echo $certificate_item['featured'] ? esc_html__( 'FEATURED', 'natsume-portfolio' ) : esc_html__( 'VERIFIED', 'natsume-portfolio' ); ?></span>
                                    </span>
                                    <span class="certificate-vault-orbit-card__title"><?php echo esc_html( $certificate_item['title'] ); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="certificate-vault-selected" aria-label="Selected certificate details">
        <div class="certificate-vault__container">
            <div class="certificate-vault-orbit__details" id="certificate-details" aria-hidden="true">
                <div class="certificate-vault-orbit__details-close-wrap">
                    <button class="certificate-vault-orbit__details-close" type="button" data-orbit-details-close aria-label="Close certificate details">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        Close
                    </button>
                </div>

                <div class="certificate-vault-orbit__details-grid">
                    <div class="certificate-vault-orbit__details-image">
                        <img data-orbit-detail-image src="" alt="">
                    </div>
                    <div class="certificate-vault-orbit__details-copy">
                        <h3 data-orbit-detail-title>Selected Credential</h3>

                        <div class="certificate-vault-orbit__detail-meta">
                            <div>
                                <span>Issuer</span>
                                <strong data-orbit-detail-issuer>Issuing organization unavailable</strong>
                            </div>
                            <div>
                                <span>Date Earned</span>
                                <strong data-orbit-detail-date>Date unavailable</strong>
                            </div>
                            <div>
                                <span>Credential ID</span>
                                <strong data-orbit-detail-credential>Not specified</strong>
                            </div>
                        </div>

                        <p class="certificate-vault-orbit__details-text" data-orbit-detail-description>Select a credential to review the description and metadata.</p>

                        <div class="certificate-vault-orbit__skills">
                            <span>Skills Acquired</span>
                            <p data-orbit-detail-skills>Learning outcomes are included with the selected credential.</p>
                        </div>

                        <div class="certificate-vault-orbit__verification">
                            <span>Verification URL</span>
                            <a href="#" target="_blank" rel="noopener" data-orbit-detail-url>No verification URL available</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.certificate-vault {
    --cert-bg: #05070B;
    --cert-bg-2: #080A0F;
    --cert-panel: rgba(10, 13, 18, .78);
    --cert-border: rgba(255, 138, 0, .25);
    --cert-glow: rgba(255, 138, 0, .35);
    --cert-accent: #FF8A00;
    --cert-accent-2: #FF9D1F;
    --cert-text: #FFFFFF;
    --cert-muted: #D0D0D0;
    --cert-soft: #A0A0A0;
    background:
        radial-gradient(circle at 18% 0%, rgba(255,138,0,.16), transparent 30%),
        radial-gradient(circle at 86% 18%, rgba(255,157,31,.08), transparent 34%),
        linear-gradient(180deg, #05070B 0%, #080A0F 48%, #0A0D12 100%);
    color: var(--cert-text);
    overflow: hidden;
}

.certificate-vault__container {
    width: min(1180px, calc(100% - 40px));
    margin: 0 auto;
    margin-top: 50px;
    margin-bottom: 100px;
}

.certificate-vault__label {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: var(--cert-accent-2);
    font-size: .76rem;
    font-weight: 900;
    letter-spacing: .16em;
    text-transform: uppercase;
    margin-top: 100px;
}

.certificate-vault__label::before {
    content: "";
    width: 34px;
    height: 2px;
    background: linear-gradient(90deg, var(--cert-accent), transparent);
    box-shadow: 0 0 18px var(--cert-glow);
}

.certificate-vault h1,
.certificate-vault p {
    margin-top: 0;
}

.certificate-vault-orbit {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 24px;
    padding: clamp(40px, 5vw, 60px) 0;
    overflow: hidden;
}

.certificate-vault-orbit__intro {
    width: min(1180px, calc(100% - 40px));
    margin: 0 auto;
    text-align: center;
}

.certificate-vault-orbit__intro h1 {
    margin: 14px 0 10px;
    font-size: clamp(2.1rem, 4.8vw, 4.2rem);
    line-height: 1;
    font-weight: 900;
    color: #fff;
}

/* ==========================================================================
   FIXED & ALIGNED CAROUSEL TRACK
   ========================================================================== */
.certificate-vault-orbit__carousel {
    width: 100%;
    overflow: hidden;
    position: relative;
    padding: 20px 0;
}

.certificate-vault-orbit__track {
    display: flex;
    width: max-content;
    will-change: transform;
    transform: translate3d(0, 0, 0);
    align-items: stretch; /* Forces all groups to match height */
}

.certificate-vault-orbit__group {
    display: flex;
    align-items: flex-start; /* Aligns all cards cleanly from the top boundary */
    gap: 30px;
    padding-right: 30px;
}

.certificate-vault-orbit-card {
    flex: 0 0 auto;
    width: clamp(240px, 20vw, 280px);
    border: 0;
    background: transparent;
    cursor: pointer;
    color: inherit;
    text-align: left;
    padding: 0;
    display: flex;
    flex-direction: column; /* Allows internal elements to stack predictably */
    transition: transform .26s ease;
}

.certificate-vault-orbit-card__surface {
    position: relative;
    display: block;
    width: 100%;
    aspect-ratio: 4 / 3; /* Enforces a uniform crop container */
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid rgba(255, 138, 0, .18);
    background: linear-gradient(180deg, rgba(8, 10, 14, .96), rgba(15, 17, 22, .98));
    box-shadow: 0 12px 34px rgba(0, 0, 0, .34);
    transition: box-shadow .26s ease, border-color .26s ease;
}

/* This rule forces different sized images to crop evenly without breaking layout sizes */
.certificate-vault-orbit-card__surface img {
    width: 100%;
    height: 100%;
    object-fit: cover; 
    object-position: center;
}

.certificate-vault-orbit-card__placeholder {
    display: grid;
    width: 100%;
    height: 100%;
    place-items: center;
    color: rgba(255, 138, 0, .72);
    font-size: 2.5rem;
}

.certificate-vault-orbit-card__badge {
    position: absolute;
    left: 12px;
    top: 12px;
    padding: 4px 10px;
    border-radius: 999px;
    color: #080A0F;
    font-size: .62rem;
    font-weight: 900;
    text-transform: uppercase;
    background: linear-gradient(135deg, rgba(255, 138, 0, .98), rgba(255, 177, 74, .9));
}

/* Fixed title wrapping baseline alignments */
.certificate-vault-orbit-card__title {
    display: block;
    margin-top: 12px;
    color: #fff;
    font-size: .95rem;
    font-weight: 600;
    line-height: 1.4;
    text-align: center;
    width: 100%;
    /* Reserves uniform space for up to 2 lines of text so 1-line titles don't misalign cards */
    min-height: 2.8em; 
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.certificate-vault-orbit-card:hover {
    transform: translateY(-5px);
}

.certificate-vault-orbit-card:hover .certificate-vault-orbit-card__surface {
    border-color: rgba(255, 177, 74, .7);
    box-shadow: 0 20px 40px rgba(255, 138, 0, 0.15);
}

/* DETAILS SECTION */
.certificate-vault-orbit__details {
    padding: 0;
    border-radius: 24px;
    background: rgba(4, 6, 10, .9);
    border: 1px solid rgba(255, 138, 0, .16);
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    transform: translateY(15px);
    transition: all .4s cubic-bezier(0.25, 1, 0.5, 1);
    pointer-events: none;
}

.certificate-vault-orbit__details.is-open {
    padding: 32px;
    max-height: 1200px;
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.certificate-vault-orbit__details-close-wrap {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 20px;
}

.certificate-vault-orbit__details-close {
    padding: 8px 16px;
    border: 1px solid rgba(255, 138, 0, .24);
    border-radius: 999px;
    background: rgba(0, 0, 0, .3);
    color: #fff;
    cursor: pointer;
    font-size: .85rem;
}

.certificate-vault-orbit__details-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 32px;
    align-items: start;
}

.certificate-vault-orbit__details-image {
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.05);
}

.certificate-vault-orbit__details-image img {
    width: 100%;
    height: auto;
    display: block;
}

.certificate-vault-orbit__details-copy {
    display: grid;
    gap: 20px;
}

.certificate-vault-orbit__detail-meta {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.certificate-vault-orbit__detail-meta span {
    display: block;
    color: var(--cert-soft);
    font-size: .7rem;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.certificate-vault-orbit__detail-meta strong {
    color: #fff;
    font-size: .95rem;
}

.certificate-vault-orbit__details-text {
    color: var(--cert-muted);
    line-height: 1.6;
}

.certificate-vault-orbit__skills span,
.certificate-vault-orbit__verification span {
    display: block;
    color: var(--cert-soft);
    font-size: .7rem;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.certificate-vault-orbit__verification a {
    color: var(--cert-accent-2);
    text-decoration: none;
}

.certificate-vault-orbit__verification a:hover {
    text-decoration: underline;
}

@media (max-width: 868px) {
    .certificate-vault-orbit__details-grid {
        grid-template-columns: 1fr;
    }
    .certificate-vault-orbit__detail-meta {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('[data-certificate-track]');
    const carousel = document.querySelector('[data-certificate-carousel]');

    if (track && carousel) {
        const groups = track.querySelectorAll('.certificate-vault-orbit__group');
        
        if (groups.length >= 2) {
            const firstGroup = groups[0];
            let scrollPosition = 0;
            let groupWidth = firstGroup.getBoundingClientRect().width;
            const scrollSpeed = 0.7; // Speed variable
            let isAnimating = true;

            function updateGroupWidth() {
                groupWidth = firstGroup.getBoundingClientRect().width;
            }

            function animate() {
                if (isAnimating) {
                    scrollPosition += scrollSpeed;
                    if (scrollPosition >= groupWidth) {
                        scrollPosition = 0; // Seamless jump back without blinking
                    }
                    track.style.transform = `translate3d(-${scrollPosition}px, 0, 0)`;
                }
                requestAnimationFrame(animate);
            }

            carousel.addEventListener('mouseenter', () => isAnimating = false);
            carousel.addEventListener('mouseleave', () => isAnimating = true);
            
            // Touch responsiveness for mobile handling safely
            carousel.addEventListener('touchstart', () => isAnimating = false, {passive: true});
            carousel.addEventListener('touchend', () => isAnimating = true, {passive: true});

            window.addEventListener('resize', updateGroupWidth);
            
            // Small timeout allows runtime to completely layout engine calculations
            setTimeout(() => {
                updateGroupWidth();
                animate();
            }, 100);
        }
    }

    // Details Grid dynamic management code logic
    const detailsContainer = document.getElementById('certificate-details');
    const detailImage = document.querySelector('[data-orbit-detail-image]');
    const detailTitle = document.querySelector('[data-orbit-detail-title]');
    const detailIssuer = document.querySelector('[data-orbit-detail-issuer]');
    const detailDate = document.querySelector('[data-orbit-detail-date]');
    const detailCredential = document.querySelector('[data-orbit-detail-credential]');
    const detailDesc = document.querySelector('[data-orbit-detail-description]');
    const detailSkills = document.querySelector('[data-orbit-detail-skills]');
    const detailUrl = document.querySelector('[data-orbit-detail-url]');
    const closeBtn = document.querySelector('[data-orbit-details-close]');

    document.querySelectorAll('[data-orbit-card]').forEach(card => {
        card.addEventListener('click', function() {
            // Populate matching data down into detail layout blocks
            if(detailImage) detailImage.src = this.dataset.image;
            if(detailTitle) detailTitle.textContent = this.dataset.title;
            if(detailIssuer) detailIssuer.textContent = this.dataset.issuer || 'Unavailable';
            if(detailDate) detailDate.textContent = this.dataset.date || 'Unavailable';
            if(detailCredential) detailCredential.textContent = this.dataset.credential || 'Not specified';
            if(detailDesc) detailDesc.textContent = this.dataset.details || '';
            if(detailSkills) detailSkills.textContent = this.dataset.skills || 'None explicitly listed';
            
            if(detailUrl && this.dataset.url) {
                detailUrl.href = this.dataset.url;
                detailUrl.textContent = "Verify Live Credential →";
                detailUrl.style.display = "inline-block";
            } else if(detailUrl) {
                detailUrl.style.display = "none";
            }

            detailsContainer.classList.add('is-open');
            detailsContainer.setAttribute('aria-hidden', 'false');
            
            // Smoothly auto-scroll browser window down to show detail blocks cleanly
            detailsContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });

    if(closeBtn && detailsContainer) {
        closeBtn.addEventListener('click', () => {
            detailsContainer.classList.remove('is-open');
            detailsContainer.setAttribute('aria-hidden', 'true');
        });
    }
});
</script>

<?php get_footer(); ?>