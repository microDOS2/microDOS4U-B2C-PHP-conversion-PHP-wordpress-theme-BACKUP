<?php
/**
 * Downloads Template - Custom Styled
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

$downloads = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;
?>

<div class="woocommerce-downloads">

    <h2 class="text-2xl font-bold text-white mb-6"><?php esc_html_e('Available Downloads', 'woocommerce'); ?></h2>

    <?php if ($has_downloads) : ?>

        <div class="overflow-x-auto">
            <table class="w-full text-left" style="color: #94a3b8;">
                <thead>
                    <tr style="border-bottom: 2px solid #1f2b47;">
                        <th class="py-4 px-4" style="color: #fff;"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                        <th class="py-4 px-4" style="color: #fff;"><?php esc_html_e('Downloads Remaining', 'woocommerce'); ?></th>
                        <th class="py-4 px-4" style="color: #fff;"><?php esc_html_e('Expires', 'woocommerce'); ?></th>
                        <th class="py-4 px-4" style="color: #fff;"><?php esc_html_e('Download', 'woocommerce'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($downloads as $download) : ?>
                        <tr style="border-bottom: 1px solid #1a1329;">
                            <td class="py-4 px-4" style="color: #fff; font-weight: 600;">
                                <?php echo esc_html($download['product_name']); ?>
                            </td>
                            <td class="py-4 px-4">
                                <?php echo is_numeric($download['downloads_remaining']) ? esc_html($download['downloads_remaining']) : esc_html__('Unlimited', 'woocommerce'); ?>
                            </td>
                            <td class="py-4 px-4">
                                <?php echo !empty($download['access_expires']) ? esc_html(date_i18n(wc_date_format(), strtotime($download['access_expires']))) : esc_html__('Never', 'woocommerce'); ?>
                            </td>
                            <td class="py-4 px-4">
                                <a href="<?php echo esc_url($download['download_url']); ?>" 
                                   class="inline-flex items-center justify-center px-4 py-2 rounded-lg font-semibold text-sm transition-all duration-200"
                                   style="background-color: #44f80c; color: #0a0514;">
                                    <?php esc_html_e('Download', 'woocommerce'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else : ?>

        <div class="p-8 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <p class="text-slate-400 text-lg mb-4">No downloads available yet.</p>
            <p class="text-slate-500 text-sm">Downloads will appear here when you purchase downloadable products.</p>
        </div>

    <?php endif; ?>

</div>
