<?php
/**
 * Template Name: Metocin Info
 *
 * @package microDOS4U
 */

get_header();

<nav class="main-navigation hidden md:flex items-center space-x-8">
            <a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="text-slate-300 hover:text-white transition">Articles & Studies</a>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-300 hover:text-white transition">Home</a>
            <a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="text-slate-300 hover:text-white transition">Dosage Guide</a>
            <a href="#safety" class="text-slate-300 hover:text-white transition">Safety Notes</a>
        </nav>

<style>
    /* ===== Metocin page dark UI ===== */
    .metocin-page {
        --slate-900: #0a0514;
        --slate-850: #150f24;
        --slate-800: #150f24;
        --slate-750: #1f1a2e;
        --slate-700: #1a1329;
        --slate-600: #475569;
        --slate-500: #64748b;
        --slate-400: #94a3b8;
        --slate-300: #cbd5e1;
        --white: #ffffff;
        --sky-300: #7dd3fc;
        --sky-400: #38bdf8;
        --violet-500: #8b5cf6;
        --pink-500: #ec4899;
        --cyan-500: #06b6d4;
        --green-500: #22c55e;
    }
    .metocin-page {
        font-family: system-ui, -apple-system, Segoe UI, Roboto, Inter, Arial, sans-serif;
        background: var(--slate-900);
        color: var(--slate-300);
    }
    .metocin-page .container {
        max-width: 1100px;
        margin: auto;
        padding: 0 24px;
    }
    .metocin-page .section {
        padding: 68px 0;
    }
    .metocin-page .title {
        color: #fff;
        font-weight: 900;
        margin: 0 0 8px;
        font-size: 40px;
        text-align: center;
        letter-spacing: -0.02em;
    }
    .metocin-page .subtitle {
        max-width: 860px;
        margin: 0 auto;
        color: var(--slate-300);
        text-align: center;
        line-height: 1.6;
    }
    .metocin-page .grad {
        background: linear-gradient(90deg, var(--sky-400), var(--violet-500));
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }
    .metocin-page .card {
        background: var(--slate-800);
        border: 1px solid #150f24;
        border-radius: 16px;
        padding: 24px;
    }
    .metocin-page .grid {
        display: grid;
        gap: 24px;
    }
    .metocin-page .grid-3 {
        grid-template-columns: 2fr 1fr;
    }
    .metocin-page .grid-4 {
        grid-template-columns: repeat(4, 1fr);
    }
    .metocin-page .grid-3eq {
        grid-template-columns: repeat(3, 1fr);
    }
    .metocin-page .kpi {
        background: rgba(21, 15, 36, 0.6);
        border-radius: 12px;
        padding: 16px;
    }
    .metocin-page .kpi .label {
        color: var(--slate-400);
        font-size: 14px;
        margin-bottom: 4px;
    }
    .metocin-page .kpi .value {
        color: #fff;
        font-weight: 800;
        font-size: 20px;
    }
    .metocin-page h2 {
        color: #fff;
        margin: 0 0 10px;
        font-size: 28px;
    }
    .metocin-page h3 {
        color: #fff;
        margin: 0 0 10px;
        font-size: 22px;
    }
    .metocin-page ul {
        padding-left: 18px;
        margin: 0;
    }
    .metocin-page li {
        margin: 6px 0;
    }
    /* --- Infographic widgets --- */
    .metocin-page .badge {
        display: inline-block;
        padding: 10px 14px;
        border-radius: 12px;
        color: #fff;
        font-weight: 800;
        letter-spacing: 0.4px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    }
    .metocin-page .b-green {
        background: linear-gradient(90deg, #10b981, #34d399);
    }
    .metocin-page .b-purple {
        background: linear-gradient(90deg, #7c3aed, #a78bfa);
    }
    .metocin-page .b-blue {
        background: linear-gradient(90deg, #0ea5e9, #60a5fa);
    }
    .metocin-page .b-pink {
        background: linear-gradient(90deg, #ec4899, #f472b6);
    }
    .metocin-page .dosebar {
        height: 10px;
        border-radius: 999px;
        background: linear-gradient(90deg, #10b981, #a78bfa, #0ea5e9, #ec4899);
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.4);
        position: relative;
        margin: 16px 0;
    }
    .metocin-page .dosebar::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: -8px;
        height: 26px;
        border-radius: 999px;
        background: linear-gradient(90deg, rgba(16, 185, 129, 0.15), rgba(167, 139, 250, 0.15), rgba(14, 165, 233, 0.15), rgba(236, 72, 153, 0.15));
        filter: blur(8px);
    }
    .metocin-page .pillrow {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-top: 14px;
    }
    .metocin-page .pill {
        background: var(--slate-750);
        border: 1px solid #1a1329;
        border-radius: 14px;
        padding: 16px;
    }
    .metocin-page .pill .tag {
        font-size: 11px;
        text-transform: uppercase;
        color: var(--slate-400);
        margin-bottom: 4px;
    }
    .metocin-page .pill .name {
        color: #fff;
        font-weight: 800;
        margin-top: 2px;
        font-size: 18px;
    }
    .metocin-page .pill .desc {
        color: var(--slate-300);
        font-size: 14px;
        margin-top: 6px;
        line-height: 1.4;
    }
    .metocin-page .hyperlinked-item {
        display: block;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s ease-in-out;
    }
    .metocin-page .hyperlinked-item:hover {
        transform: scale(1.03);
        cursor: pointer;
    }
    .metocin-page .muted {
        color: var(--slate-500);
        font-size: 12px;
        margin-top: 16px;
    }
    .metocin-page .btn-metocin {
        display: inline-block;
        padding: 10px 16px;
        border-radius: 12px;
        background: linear-gradient(90deg, var(--sky-400), var(--violet-500));
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        margin: 5px;
        transition: transform 0.2s ease;
    }
    .metocin-page .btn-metocin:hover {
        transform: scale(1.05);
    }
    .metocin-page .btn-large {
        padding: 16px 32px;
        font-size: 20px;
    }

    @media (max-width: 768px) {
        .metocin-page .title {
            font-size: 28px;
        }
        .metocin-page .grid-3,
        .metocin-page .grid-4,
        .metocin-page .grid-3eq,
        .metocin-page .pillrow {
            grid-template-columns: 1fr;
        }
        .metocin-page .section {
            padding: 40px 0;
        }
    }
</style>

<div class="metocin-page">
    <main>
        <!-- Hero -->
        <section class="section" style="padding-bottom: 32px;">
            <div class="container">
                <h2 class="title">Metocin <span class="grad">(4‑HO‑MET)</span></h2>
                <p class="subtitle">A precision psychedelic tryptamine first synthesized by Alexander "Sasha" Shulgin in the 1970s. Fast onset, predictable duration, and effects comparable to psilocin from magic mushrooms.</p>
            </div>
        </section>

        <!-- What is Metocin -->
        <section class="section" style="padding-top: 32px; padding-bottom: 32px;">
            <div class="container">
                <div class="grid grid-3">
                    <div class="card">
                        <h3>What is Metocin?</h3>
                        <ul style="color: var(--slate-300);">
                            <li><strong style="color: #fff;">Chemical:</strong> 4‑hydroxy‑N‑methyl‑N‑ethyltryptamine (4‑HO‑MET)</li>
                            <li><strong style="color: #fff;">Class:</strong> Psychedelic tryptamine</li>
                            <li><strong style="color: #fff;">Origin:</strong> First synthesized in the 1970s by Alexander "Sasha" Shulgin</li>
                            <li><strong style="color: #fff;">Relation:</strong> Produces effects comparable to psilocin from psilocybin mushrooms</li>
                        </ul>
                    </div>
                    <div class="card">
                        <h3>Core Specs</h3>
                        <div class="grid" style="grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div class="kpi">
                                <div class="label">Onset</div>
                                <div class="value">~15 min</div>
                            </div>
                            <div class="kpi">
                                <div class="label">Peak</div>
                                <div class="value">1.5–2 hrs</div>
                            </div>
                            <div class="kpi">
                                <div class="label">Duration</div>
                                <div class="value">≈4 hrs</div>
                            </div>
                            <div class="kpi">
                                <div class="label">Form</div>
                                <div class="value">2 mg scored tab</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- What does it do? -->
        <section class="section" style="padding-top: 32px; padding-bottom: 32px;">
            <div class="container">
                <div class="card">
                    <h3>What does it do?</h3>
                    <p style="color: var(--slate-300); line-height: 1.7; margin-bottom: 16px;">Metocin engages serotonin receptors (notably 5‑HT<sub>2A</sub>) similar to psilocin, yielding psychedelic changes in perception, mood, and cognition. Users often report a clear, energetic headspace with vivid sensory enhancement.</p>
                    <div class="grid grid-3eq" style="text-align: center; margin-top: 16px;">
                        <div class="kpi">
                            <div class="label">Comparable to</div>
                            <div class="value">Psilocin</div>
                        </div>
                        <div class="kpi">
                            <div class="label">Onset speed</div>
                            <div class="value">Fast (~15m)</div>
                        </div>
                        <div class="kpi">
                            <div class="label">Total window</div>
                            <div class="value">~4 hours</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dosage Guide -->
        <section class="section" id="dose" style="padding-top: 32px; padding-bottom: 32px;">
            <div class="container">
                <div class="card" style="margin-bottom: 18px; text-align: center;">
                    <h3 style="margin-bottom: 6px;">Dosage Guide</h3>
                    <p style="color: var(--slate-300);">Effects begin around <strong style="color: #fff;">1–2 mg</strong>. Each <span style="color: #44f80c;">micro</span><span style="color: #9a02d0;">DOS</span><span style="color: #ff66c4;">(2)</span> tablet contains <strong style="color: #fff;">2 mg</strong> of Metocin (≈ the experience of <strong style="color: #fff;">½ gram</strong> of dried mushrooms). Start low and assess.</p>
                </div>

                <!-- Neon-style badges -->
                <div class="grid grid-4" style="align-items: start; text-align: center; margin-bottom: 12px;">
                    <div>
                        <a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="hyperlinked-item">
                            <span class="badge b-green">LIGHT</span>
                            <div style="margin-top: 8px; color: var(--slate-300);">1 tab</div>
                        </a>
                    </div>
                    <div>
                        <span class="badge b-purple">MODERATE</span>
                        <div style="margin-top: 8px; color: var(--slate-300);">2 tabs</div>
                    </div>
                    <div>
                        <span class="badge b-blue">TRIPPY</span>
                        <div style="margin-top: 8px; color: var(--slate-300);">4 tabs</div>
                    </div>
                    <div>
                        <span class="badge b-pink">TRANSCEND</span>
                        <div style="margin-top: 8px; color: var(--slate-300);">6 tabs</div>
                    </div>
                </div>

                <!-- Gradient dose bar -->
                <div class="dosebar"></div>

                <!-- Detail cards -->
                <div class="pillrow">
                    <a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="hyperlinked-item">
                        <div class="pill">
                            <div class="tag">1 tab • 2 mg</div>
                            <div class="name">Light</div>
                            <div class="desc">Social, playful, subtle lift.</div>
                        </div>
                    </a>
                    <div class="pill">
                        <div class="tag">2 tabs • 4 mg</div>
                        <div class="name">Moderate</div>
                        <div class="desc">Noticeable effects, gentle visuals.</div>
                    </div>
                    <div class="pill">
                        <div class="tag">4 tabs • 8 mg</div>
                        <div class="name">Trippy</div>
                        <div class="desc">Clear trip characteristics; strong perceptual changes.</div>
                    </div>
                    <div class="pill">
                        <div class="tag">6+ tabs • 12 mg+</div>
                        <div class="name">Transcend</div>
                        <div class="desc">Deep, potentially transcendental experiences.</div>
                    </div>
                </div>

                <!-- Equivalence callout -->
                <div class="card" style="margin-top: 18px; text-align: center;">
                    <strong style="color: #fff;">Equivalence:</strong> <span style="color: #fff;">1 tablet ≈ the experience of 500 mg dried mushrooms</span>. Quarter‑ or half‑tab microdosing provides gentler effects.
                </div>
            </div>
        </section>

        <!-- Further Reading Section -->
        <section class="section" id="reading" style="padding-top: 32px; padding-bottom: 0;">
            <div class="container">
                <div class="card" style="text-align: center;">
                    <h3>Further Reading</h3>
                    <a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="btn-metocin" target="_blank">Articles &amp; Studies</a>
                    <a href="<?php echo esc_url(home_url('/user-stories')); ?>" class="btn-metocin" target="_blank">User Stories</a>
                </div>
            </div>
        </section>

        <!-- Feel -->
        <section class="section" id="feel" style="padding-top: 32px; padding-bottom: 32px;">
            <div class="container">
                <div class="card">
                    <h3>What does it feel like?</h3>
                    <div class="grid grid-3eq">
                        <div class="kpi"><span style="font-weight: 800; color: #fff;">Euphoria</span> and mood lift</div>
                        <div class="kpi">Tingling somatic sensations</div>
                        <div class="kpi">Perceptual enhancement (color, texture)</div>
                        <div class="kpi">Open/closed‑eye visuals</div>
                        <div class="kpi">Synesthesia (mixing of senses)</div>
                        <div class="kpi">Time dilation &amp; cognitive shifts</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Safety -->
        <section class="section" id="safety" style="padding-top: 32px; padding-bottom: 32px;">
            <div class="container">
                <div class="card">
                    <h3>Safety &amp; Use Notes</h3>
                    <ul style="color: var(--slate-300);">
                        <li><strong style="color: #fff;">Legal:</strong> 4‑HO‑MET is not federally scheduled in the U.S.; verify local laws.</li>
                        <li><strong style="color: #fff;">Set &amp; setting:</strong> Comfortable, safe environment; avoid driving or operating machinery.</li>
                        <li><strong style="color: #fff;">Plan your window:</strong> Allocate ~4 hours (onset ~15 min; peak 1.5‑2 hrs).</li>
                        <li><strong style="color: #fff;">Hydration:</strong> Drink water; avoid alcohol mixing.</li>
                        <li><strong style="color: #fff;">Adults only:</strong> Intended for 21+; consult a healthcare professional if you have medical conditions or take medications.</li>
                    </ul>
                    <p class="muted">These statements have not been evaluated by the FDA. This content is informational and not medical advice.</p>
                </div>
            </div>
        </section>

        <!-- Order Now CTA -->
        <section class="section" style="padding-top: 32px; padding-bottom: 32px; text-align: center;">
            <a href="<?php echo esc_url(home_url('/#pricing')); ?>" class="btn-metocin btn-large">Order Now</a>
        </section>
    </main>
</div>

<?php get_footer(); ?>