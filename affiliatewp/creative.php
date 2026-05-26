<?php
/**
 * AffiliateWP Creative Template Override
 *
 * Shows creatives with 3 copy buttons each.
 * No raw HTML code displayed. Clean grid layout.
 *
 * @package microDOS4U
 */

global $affwp_creative_atts;

$creative_id      = $affwp_creative_atts['id'];
$url              = $affwp_creative_atts['url'];
$id_class         = $affwp_creative_atts['id_class'];
$desc             = $affwp_creative_atts['desc'];
$preview          = $affwp_creative_atts['preview'];
$image_attributes = $affwp_creative_atts['image_attributes'];
$image_link       = $affwp_creative_atts['image_link'];
$text             = $affwp_creative_atts['text'];

$affiliate_url = affwp_get_affiliate_referral_url(array('base_url' => $url));

if ($image_attributes) {
    $img_src = $image_attributes[0];
} elseif ($image_link) {
    $img_src = $image_link;
} else {
    $img_src = '';
}

// Build HTML for email copy (renders as clickable image in Gmail/Outlook)
if ($image_attributes) {
    $image_or_text = '<img src="' . esc_attr($image_attributes[0]) . '" alt="' . esc_attr($text) . '" />';
} elseif ($image_link) {
    $image_or_text = '<img src="' . esc_attr($image_link) . '" alt="' . esc_attr($text) . '" />';
} else {
    $image_or_text = esc_attr($text);
}

$email_html = '<a href="' . esc_url($affiliate_url) . '" title="' . esc_attr($text) . '">' . $image_or_text . '</a>';

$uid = 'affwp-copy-' . ($creative_id ? $creative_id : uniqid());
?>
<div class="affwp-creative microdos-creative-card<?php echo esc_attr($id_class); ?>">

    <?php if (!empty($desc)) : ?>
        <p class="microdos-creative-desc"><?php echo esc_html($desc); ?></p>
    <?php endif; ?>

    <?php if ($preview !== 'no' && $img_src) : ?>
        <div class="microdos-creative-preview">
            <img src="<?php echo esc_attr($img_src); ?>" alt="<?php echo esc_attr($text); ?>" loading="lazy">
        </div>
    <?php endif; ?>

    <div class="microdos-copy-buttons" data-creative-id="<?php echo esc_attr($creative_id); ?>">

        <?php if ($img_src) : ?>
        <button type="button" class="microdos-copy-btn microdos-copy-img" data-uid="<?php echo esc_attr($uid); ?>-img" title="Copy banner image URL for social media">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
            Copy Image URL
        </button>
        <?php endif; ?>

        <button type="button" class="microdos-copy-btn microdos-copy-link" data-uid="<?php echo esc_attr($uid); ?>-link" title="Copy your referral link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
            Copy My Link
        </button>

        <?php if ($img_src) : ?>
        <button type="button" class="microdos-copy-btn microdos-copy-email" data-uid="<?php echo esc_attr($uid); ?>-email" title="Copy as clickable image for Gmail/Outlook">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            Copy for Email
        </button>
        <?php endif; ?>

        <span class="microdos-copy-feedback" id="<?php echo esc_attr($uid); ?>-feedback" style="display:none;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Copied!
        </span>
    </div>

    <!-- Hidden data for JavaScript -->
    <script type="text/template" id="<?php echo esc_attr($uid); ?>-img-data" style="display:none;"><?php echo esc_html($img_src); ?></script>
    <script type="text/template" id="<?php echo esc_attr($uid); ?>-link-data" style="display:none;"><?php echo esc_html($affiliate_url); ?></script>
    <script type="text/template" id="<?php echo esc_attr($uid); ?>-email-data" style="display:none;"><?php echo esc_html($email_html); ?></script>

</div>
