<?php
/**
 * Template Name: Dosage Guide
 *
 * @package microDOS4U
 */

get_header();

?>
<main>
    <!-- Hero Section -->
    <section class="section" style="padding:68px 0;">
        <div class="container" style="max-width:1100px;margin:auto;padding:0 24px;">
            <h2 style="color:#fff;font-weight:900;margin:0 0 8px;font-size:40px;text-align:center;letter-spacing:-.02em;">Microdosing Metocin <span style="color:#94a3b8;font-size:24px;display:block;margin-top:8px;font-weight:400;">(4&#8209;HO&#8209;MET)</span></h2>
            <p style="max-width:860px;margin:0 auto;color:#94a3b8;text-align:center;">A guide to sub-perceptual and gentle doses for enhanced clarity, creativity, and focus, without the full psychedelic experience.</p>
        </div>
    </section>

    <!-- Visual Dosing Guide -->
    <section class="section" id="visual-guide" style="padding:0 0 68px 0;">
        <div class="container" style="max-width:1100px;margin:auto;padding:0 24px;">
            <h3 style="color:#fff;font-weight:800;font-size:24px;margin-bottom:16px;border-bottom:1px solid #1a1329;padding-bottom:8px;text-align:center;">Visual Dosing Guide</h3>
            <p style="color:#94a3b8;margin-bottom:24px;text-align:center;">Each tablet is scored for easy and precise dosing. The guide below shows the equivalence for each portion.</p>
            <div style="display:grid;gap:24px;grid-template-columns:repeat(3, 1fr);">
                <div style="background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;text-align:center;">
                    <div style="font-size:48px;margin-bottom:12px;">&#128138;</div>
                    <h4 style="color:#fff;font-weight:700;margin:0 0 8px;">1 Tablet</h4>
                    <p style="color:#38bdf8;font-weight:600;margin:0 0 4px;">~500mg Shrooms</p>
                    <p style="color:#94a3b8;font-size:14px;margin:0;">A creative or recreational dose.</p>
                </div>
                <div style="background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;text-align:center;">
                    <div style="font-size:48px;margin-bottom:12px;">&#9707;</div>
                    <h4 style="color:#fff;font-weight:700;margin:0 0 8px;">1/2 Tablet</h4>
                    <p style="color:#38bdf8;font-weight:600;margin:0 0 4px;">~250mg Microdose</p>
                    <p style="color:#94a3b8;font-size:14px;margin:0;">Standard microdose for creative lift.</p>
                </div>
                <div style="background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;text-align:center;">
                    <div style="font-size:48px;margin-bottom:12px;">&#8857;</div>
                    <h4 style="color:#fff;font-weight:700;margin:0 0 8px;">1/4 Tablet</h4>
                    <p style="color:#38bdf8;font-weight:600;margin:0 0 4px;">~125mg Light Microdose</p>
                    <p style="color:#94a3b8;font-size:14px;margin:0;">Sub-perceptual for focus and energy.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Standard / Light Microdose -->
    <section class="section" style="padding:0 0 32px 0;">
        <div class="container" style="max-width:1100px;margin:auto;padding:0 24px;">
            <div style="display:grid;gap:24px;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));">
                <div style="background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;">
                    <h4 style="color:#38bdf8;font-weight:700;margin:0 0 12px;">Standard Microdose &#8212; 1/2 Tablet</h4>
                    <p style="color:#94a3b8;margin:0;font-size:14px;line-height:1.6;">Provides a noticeable creative and mood lift. Equivalent to ~250mg of dried mushrooms.</p>
                </div>
                <div style="background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;">
                    <h4 style="color:#38bdf8;font-weight:700;margin:0 0 12px;">Light Microdose &#8212; 1/4 Tablet</h4>
                    <p style="color:#94a3b8;margin:0;font-size:14px;line-height:1.6;">Offers a subtle, sub-perceptual boost in focus and energy. Equivalent to ~125mg of dried mushrooms.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Potential Benefits -->
    <section class="section" id="benefits" style="padding:32px 0 32px 0;">
        <div class="container" style="max-width:1100px;margin:auto;padding:0 24px;">
            <h3 style="color:#fff;font-weight:800;font-size:24px;margin-bottom:16px;">Potential Benefits of Microdosing</h3>
            <p style="color:#94a3b8;margin-bottom:16px;">While individual experiences vary, users often microdose to seek:</p>
            <ul style="color:#94a3b8;padding-left:20px;line-height:1.8;">
                <li>Enhanced focus and concentration</li>
                <li>Increased creativity and divergent thinking</li>
                <li>Improved mood and a more positive outlook</li>
                <li>Greater presence and sensory awareness</li>
                <li>Reduced anxiety in social or professional settings</li>
            </ul>
            <div style="display:grid;grid-template-columns:repeat(2, 1fr);gap:32px 300px;margin-top:32px;max-width:1000px;">
                <a href="<?php echo esc_url(home_url('/user-stories')); ?>" style="text-decoration:none;text-align:center;padding:12px 24px;border-radius:8px;background:linear-gradient(90deg, #38bdf8, #8b5cf6);color:#fff;">
                    <span style="font-size:16px;display:block;">Joe's Story</span>
                    <span style="font-size:12px;opacity:0.85;font-weight:400;display:block;margin-top:4px;">The Day the Colors Came Back</span>
                </a>
                <a href="<?php echo esc_url(home_url('/user-stories')); ?>" style="text-decoration:none;text-align:center;padding:12px 24px;border-radius:8px;background:linear-gradient(90deg, #38bdf8, #8b5cf6);color:#fff;">
                    <span style="font-size:16px;display:block;">Elanor's Story</span>
                    <span style="font-size:12px;opacity:0.85;font-weight:400;display:block;margin-top:4px;">A Quiet Revolution</span>
                </a>
                <a href="<?php echo esc_url(home_url('/user-stories')); ?>" style="text-decoration:none;text-align:center;padding:12px 24px;border-radius:8px;background:linear-gradient(90deg, #38bdf8, #8b5cf6);color:#fff;">
                    <span style="font-size:16px;display:block;">Jalen's Story</span>
                    <span style="font-size:12px;opacity:0.85;font-weight:400;display:block;margin-top:4px;">A Journey Back to Self</span>
                </a>
                <a href="<?php echo esc_url(home_url('/user-stories')); ?>" style="text-decoration:none;text-align:center;padding:12px 24px;border-radius:8px;background:linear-gradient(90deg, #38bdf8, #8b5cf6);color:#fff;">
                    <span style="font-size:16px;display:block;">Lena's Story</span>
                    <span style="font-size:12px;opacity:0.85;font-weight:400;display:block;margin-top:4px;">Finding Flow in the Everyday</span>
                </a>
                <a href="<?php echo esc_url(home_url('/user-stories')); ?>" style="text-decoration:none;text-align:center;padding:12px 24px;border-radius:8px;background:linear-gradient(90deg, #38bdf8, #8b5cf6);color:#fff;">
                    <span style="font-size:16px;display:block;">Mateo's Story</span>
                    <span style="font-size:12px;opacity:0.85;font-weight:400;display:block;margin-top:4px;">The Professor's Spark</span>
                </a>
                <a href="<?php echo esc_url(home_url('/user-stories')); ?>" style="text-decoration:none;text-align:center;padding:12px 24px;border-radius:8px;background:linear-gradient(90deg, #38bdf8, #8b5cf6);color:#fff;">
                    <span style="font-size:16px;display:block;">Banyu's Story</span>
                    <span style="font-size:12px;opacity:0.85;font-weight:400;display:block;margin-top:4px;">Blueprint for Clarity</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Safety & Use Notes -->
    <section class="section" id="safety" style="padding:32px 0 32px 0;">
        <div class="container" style="max-width:1100px;margin:auto;padding:0 24px;">
            <div style="background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;">
                <h3 style="color:#fff;font-weight:800;font-size:24px;margin-bottom:16px;">Safety &amp; Use Notes</h3>
                <ul style="color:#94a3b8;padding-left:20px;line-height:1.8;">
                    <li><strong style="color:#fff;">Start Low (Titration):</strong> If you are new to Metocin, begin with a light microdose (1/4 tab) to assess your individual sensitivity before increasing the amount.</li>
                    <li><strong style="color:#fff;">Plan your day:</strong> Allocate a window of at least 4 hours. Expect onset within ~15 minutes, with peak effects occurring between 1.5–2 hours.</li>
                    <li><strong style="color:#fff;">Set &amp; Setting:</strong> Ensure a comfortable, safe environment. While microdosing is intended to be sub-perceptual, be mindful of your surroundings. Do not drive or operate heavy machinery until you have fully assessed how the substance affects your cognitive and motor functions.</li>
                    <li><strong style="color:#fff;">Hydration &amp; Interactions:</strong> Maintain adequate water intake. Avoid mixing with alcohol or other substances, as interactions can alter the experience or safety profile.</li>
                    <li><strong style="color:#fff;">Legal Status:</strong> 4‑HO‑MET is not federally scheduled in the U.S.; always verify your local laws.</li>
                    <li><strong style="color:#fff;">Requirements &amp; Consultation:</strong> Intended for adults 21+ only. Consult a healthcare professional prior to use if you have underlying medical conditions or are currently taking other medications.</li>
                </ul>
                <div style="color:#64748b;font-size:14px;margin-top:16px;line-height:1.6;">
                    <p style="margin-bottom:8px;"><strong style="color:#94a3b8;">Disclaimers:</strong></p>
                    <p style="margin-bottom:8px;">Products sold on this website are not intended for human or animal use, clinical purposes, diagnostic procedures, or any form of consumption or application.</p>
                    <p style="margin-bottom:8px;">This content is for informational purposes only and does not constitute medical advice.</p>
                    <p>These statements have not been evaluated by the FDA. This product is not intended to diagnose, treat, cure, or prevent any disease.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Now CTA -->
    <section style="padding:0 0 32px 0;text-align:center;">
        <div class="container" style="max-width:1100px;margin:auto;padding:0 24px;">
            <a href="<?php echo esc_url(home_url('/#pricing')); ?>" style="text-decoration:none;display:inline-block;color:#fff;font-weight:bold;border-radius:8px;background:linear-gradient(90deg, #38bdf8, #8b5cf6);padding:16px 32px;font-size:20px;">Order Now</a>
        </div>
    </section>
</main>

<?php
get_footer();
?>