<?php
/**
 * Template Name: Affiliate Creatives
 *
 * Clean marketing creatives page for affiliates.
 * Uses [affiliate_creatives] shortcode with custom template override.
 *
 * @package microDOS4U
 */

get_header();
?>

<main id="primary" class="site-main" style="background-color: #0a0514; min-height: 100vh; padding: 40px 0 60px;">
    <div class="container mx-auto px-4 max-w-6xl">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Marketing Creatives</h1>
            <p class="text-slate-400 text-sm">Click any button to copy. Paste into social media, email, or anywhere you share.</p>
        </div>

        <!-- Creatives Grid -->
        <div class="microdos-creatives-grid">
            <?php echo do_shortcode('[affiliate_creatives status="active"]'); ?>
        </div>

    </div>
</main>

<style>
.microdos-creatives-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

@media (min-width: 768px) {
    .microdos-creatives-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1100px) {
    .microdos-creatives-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.microdos-creative-card {
    background: #150f24;
    border: 1px solid #1f2b47;
    border-radius: 10px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: border-color 0.2s, transform 0.2s;
}

.microdos-creative-card:hover {
    border-color: #44f80c40;
    transform: translateY(-2px);
}

.microdos-creative-preview {
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    border-radius: 6px;
    background: #0a0514;
    display: flex;
    align-items: center;
    justify-content: center;
}

.microdos-creative-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    display: block;
}

.microdos-creative-desc {
    color: #d1d5db;
    font-size: 14px;
    font-weight: 500;
    margin: 0;
    line-height: 1.4;
}

.microdos-copy-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.microdos-copy-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid #1f2b47;
    background: #0a0514;
    color: #e2e8f0;
    transition: all 0.2s;
}

.microdos-copy-btn:hover {
    border-color: #44f80c;
    color: #44f80c;
}

.microdos-copy-btn:active {
    transform: scale(0.97);
}

.microdos-copy-feedback {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #44f80c;
    font-size: 12px;
    font-weight: 600;
    animation: microdos-fadein 0.2s ease;
}

@keyframes microdos-fadein {
    from { opacity: 0; transform: translateY(4px); }
    to   { opacity: 1; transform: translateY(0); }
}

.affwp-creatives { display: contents; }
</style>

<?php get_footer(); ?>
