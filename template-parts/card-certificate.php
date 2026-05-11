<?php
/**
 * Template Part: Certificate Card
 *
 * @package NatsumePortfolio
 */

$issuer       = get_field( 'organization' ) ?: get_field( 'cert_issuer' );
$date         = get_field( 'cert_date' );
$credential   = get_field( 'cert_credential_id' );
$url          = get_field( 'cert_url' );
$cert_image   = get_field( 'cert_image' );
$description  = get_field( 'description' );
?>

<div class="cert-item" 
     data-img="<?php echo $cert_image ? esc_url($cert_image['url']) : get_template_directory_uri() . '/assets/images/placeholder.jpg'; ?>"
     data-desc="<?php echo esc_attr($description ?: ''); ?>">
    <h3><?php the_title(); ?></h3>
    <p><?php echo esc_html($issuer ?: 'Certificate'); ?></p>
    <div class="cert-desc"></div>
</div>
