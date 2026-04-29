<?php
/**
 * Template Name: Articles and Studies
 *
 * @package microDOS4U
 */

get_header();

<nav class="main-navigation hidden md:flex items-center space-x-8">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-300 hover:text-white transition">Home</a>
            <a href="<?php echo esc_url(home_url('/metocin-info')); ?>" class="text-slate-300 hover:text-white transition">Metocin Info</a>
            <a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="text-slate-300 hover:text-white transition">Dosage Guide</a>
            <a href="<?php echo esc_url(home_url('/user-stories')); ?>" class="text-slate-300 hover:text-white transition">User Stories</a>
        </nav>
?>

<main>
    <!-- Hero Section -->
    <section class="section" style="padding:68px 0;">
        <div class="container" style="max-width:1100px;margin:auto;padding:0 24px;">
            <h2 style="color:#fff;font-weight:900;margin:0 0 8px;font-size:40px;text-align:center;letter-spacing:-.02em;">Microdosing Research &amp; Commentary</h2>
            <p style="max-width:860px;margin:0 auto;color:#94a3b8;text-align:center;">An overview of recent articles, studies, and anecdotal reports on the topic of microdosing psychedelics.</p>
        </div>
    </section>

    <!-- Articles Section -->
    <section class="section" id="articles" style="padding:68px 0;">
        <div class="container" style="max-width:1100px;margin:auto;padding:0 24px;">
            <h3 style="color:#fff;font-weight:800;font-size:24px;margin-bottom:16px;border-bottom:1px solid #1a1329;padding-bottom:8px;">Articles (All Free Access)</h3>
            <div class="grid" style="display:grid;gap:24px;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));">
                <a href="https://www.verywellhealth.com/microdosing-5271962" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Microdosing: Everything You Need to Know (Verywell Health)</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">A clear guide covering how microdosing works, possible mood-boosting effects, risks, and scientific skepticism.</p>
                </a>
                <a href="https://apnews.com/article/microdosing-lsd-mushrooms-psychedelic-psilocybin-390c99ba54ef9d75727f39e2ec78fb34" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Believers say microdosing psychedelics helps them. Scientists are trying to measure the claims (AP News)</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">Balances heartfelt testimonials with sober scientific perspective—highlighting anecdotal benefits and placebo considerations.</p>
                </a>
                <a href="https://coloradosun.com/2022/08/07/microdosing-shrooms-psilocybin-anxiety-depression/" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Eight months ago I started microdosing shrooms to relieve crippling anxiety and depression. It's working. (The Colorado Sun)</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">A deeply personal narrative describing how daily microdosing (~0.14 g psilocybin) helped the author overcome chronic anxiety and depression.</p>
                </a>
                <a href="https://www.theguardian.com/science/2019/may/03/psychedelic-drugs-women-taking-tiny-doses-hattie-garlick" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">"It makes me enjoy playing with the kids": is microdosing mushrooms going mainstream? (The Guardian)</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">Rosie's story of tiny dosing (~0.12 g) reveals newfound calm, confidence, and presence—without discernible intoxication.</p>
                </a>
                <a href="https://www.news-medical.net/health/Microdosing-for-Mental-Health-Hype-or-Hope.aspx" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Microdosing for Mental Health: Hype or Hope? (News-Medical)</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">Examines the neurobiological mechanisms—such as 5-HT2A receptor engagement—and weighs self-reported cognitive benefits against the lack of robust clinical consensus.</p>
                </a>
                <a href="https://news.ok.ubc.ca/2025/12/11/ubco-study-finds-microdosing-can-temporarily-improve-mood-creativity/" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">UBCO study finds microdosing can temporarily improve mood, creativity (UBC News)</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">Summarizes an observational daily diary study showing that perceived benefits in mood and focus are acute (occurring only on dosing days) and do not carry over to non-dosing days.</p>
                </a>
                <a href="https://www.cpr.org/2025/04/17/microdosing-health-benefits-lsd-psilocybin-research/" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Microdosing Health Benefits: LSD &amp; Psilocybin Research (CPR News)</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">Discusses current research on the health benefits of microdosing psychedelics, situated within the context of recent state-level legalization shifts and clinical observation.</p>
                </a>
                <a href="https://nyulangone.org/news/psychedelic-drug-therapy-may-help-treat-alcohol-addiction" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Psychedelic Drug Therapy May Help Treat Alcohol Addiction (NYU Langone Health)</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">Highlights clinical trial results demonstrating that psilocybin-assisted therapy can significantly reduce heavy drinking days in individuals diagnosed with alcohol use disorder.</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Scientific Studies Section -->
    <section class="section" id="studies" style="padding:0 0 68px 0;">
        <div class="container" style="max-width:1100px;margin:auto;padding:0 24px;">
            <h3 style="color:#fff;font-weight:800;font-size:24px;margin-bottom:16px;border-bottom:1px solid #1a1329;padding-bottom:8px;">Scientific Studies (All Free Access)</h3>
            <div class="grid" style="display:grid;gap:24px;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));">
                <a href="https://www.nature.com/articles/s41598-021-01811-4" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Scientific Reports (2021) — Adults who microdose psychedelics report health related motivations…</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">Open-access research revealing microdosers frequently cite health-driven motivations and report reduced symptoms of anxiety and depression compared to non-users.</p>
                </a>
                <a href="https://harmreductionjournal.biomedcentral.com/articles/10.1186/s12954-019-0308-4" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Harm Reduction Journal (2019) — Psychedelic microdosing benefits and challenges: an empirical codebook</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">A qualitative analysis of microdosers' experiences—detailing both benefits like improved mood and challenges like physical discomfort.</p>
                </a>
                <a href="https://journals.plos.org/plosone/article?id=10.1371%2Fjournal.pone.0211023" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">PLOS One (2019) — A systematic study of microdosing psychedelics</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">An observational six-week study of microdosers noting well-being gains paired with expectancy effects.</p>
                </a>
                <a href="https://www.nature.com/articles/s41598-022-14512-3" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Scientific Reports (2022) — Psilocybin microdosers demonstrate greater observed improvements…</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">A longitudinal study finding small- to medium-sized improvements in mood and mental health in microdosers relative to a control group.</p>
                </a>
                <a href="https://pmc.ncbi.nlm.nih.gov/articles/PMC11787777/" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">PMC (2025) — Mushrooms, Microdosing, and Mental Illness: The Effect of Psilocybin on Neuroinflammation</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">Analyzes how sub-hallucinogenic psilocybin targets neuroinflammation without immune suppression, acting as a serotonin agonist to increase neuroplasticity.</p>
                </a>
                <a href="https://www.psychiatrist.com/pcc/modern-psychedelic-microdosing-research-mental-health-systematic-review/" target="_blank" rel="noopener noreferrer" style="display:block;text-decoration:none;color:inherit;transition:transform 0.2s ease-in-out;background:#150f24;border:1px solid #1a1329;border-radius:12px;padding:24px;height:100%;display:flex;flex-direction:column;justify-content:space-between;" onmouseover="this.style.transform='scale(1.03)';this.querySelector('h4').style.color='#7dd3fc'" onmouseout="this.style.transform='scale(1)';this.querySelector('h4').style.color='#38bdf8'">
                    <h4 style="color:#38bdf8;font-weight:700;font-size:18px;margin:0;">Primary Care Companion (2024) — Modern Psychedelic Microdosing Research on Mental Health: A Systematic Review</h4>
                    <p style="margin-top:8px;font-size:14px;line-height:1.5;color:#94a3b8;">A systematic review evaluating reported benefits alongside challenges (e.g., physiological discomfort), highlighting the strong role of expectancy effects and the need for double-blind trials.</p>
                </a>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
