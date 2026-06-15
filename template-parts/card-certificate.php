<?php
/**
 * Template Part: Certificate Card
 *
 * @package NatsumePortfolio
 */

$issuer        = (string) get_field( 'cert_issuer' );
$date          = (string) get_field( 'cert_date' );
$credential    = (string) get_field( 'cert_credential_id' );
$cert_image    = get_field( 'cert_image' );
$details       = (string) get_field( 'certificate_details' );
$skills        = (string) get_field( 'cert_skills' );
$featured      = (bool) get_field( 'cert_featured' );
$image_url     = '';
$image_alt     = get_the_title();

if ( is_array( $cert_image ) && ! empty( $cert_image['url'] ) ) {
    $image_url = $cert_image['url'];
    $image_alt = ! empty( $cert_image['alt'] ) ? $cert_image['alt'] : get_the_title();
}
?>

<article
    class="certificate-vault-card"
    data-certificate-card
    data-title="<?php echo esc_attr( get_the_title() ); ?>"
    data-issuer="<?php echo esc_attr( $issuer ); ?>"
    data-date="<?php echo esc_attr( $date ); ?>"
    data-credential="<?php echo esc_attr( $credential ); ?>"
    data-details="<?php echo esc_attr( wp_strip_all_tags( $details ) ); ?>"
    data-skills="<?php echo esc_attr( wp_strip_all_tags( $skills ) ); ?>"
    data-featured="<?php echo esc_attr( $featured ? '1' : '0' ); ?>"
    data-image="<?php echo esc_url( $image_url ); ?>"
    data-image-alt="<?php echo esc_attr( $image_alt ); ?>"
>
    <button class="certificate-vault-card__button" type="button" data-certificate-open>
        <span class="certificate-vault-card__media">
            <?php if ( $image_url ) : ?>
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
            <?php else : ?>
                <span class="certificate-vault-card__placeholder" aria-hidden="true">
                    <i class="fa-solid fa-certificate"></i>
                </span>
            <?php endif; ?>
            <span class="certificate-vault-card__badge">VERIFIED</span>
        </span>

        <span class="certificate-vault-card__body">
            <span class="certificate-vault-card__eyebrow">Credential Record</span>
            <strong><?php echo esc_html( get_the_title() ); ?></strong>

            <span class="certificate-vault-card__meta">
                <span>
                    <em>Issuer</em>
                    <?php echo esc_html( $issuer ? $issuer : 'Issuing organization unavailable' ); ?>
                </span>
                <span>
                    <em>Date Earned</em>
                    <?php echo esc_html( $date ? $date : 'Date unavailable' ); ?>
                </span>
                <span>
                    <em>Credential ID</em>
                    <?php echo esc_html( $credential ? $credential : 'Not specified' ); ?>
                </span>
            </span>
        </span>
    </button>
</article>
