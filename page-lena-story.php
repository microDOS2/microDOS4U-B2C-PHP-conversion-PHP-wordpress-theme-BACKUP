<?php
/*
Template Name: Lena's Story
*/
get_header();
?>

<style>
    .story-page-body {
        font-family: 'Inter', sans-serif;
        background-color: #0a0514;
    }
    .story-block {
        background-color: #ffffff;
        border-radius: 0.75rem;
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }

    @media (max-width: 768px) {
        .story-page-body .row { flex-direction: column; align-items: flex-start; }
        .story-page-body h1 { font-size: 20px; margin-bottom: 10px; }
        .story-page-body .nav-links {
            display: flex; flex-direction: column; width: 100%; gap: 8px;
        }
        .story-page-body .nav-links .btn {
            width: 100%; text-align: center; padding: 12px; font-size: 14px;
        }
        .story-page-body .container { padding: 0 16px; }
    }
</style>

<div class="story-page-body text-gray-800">
    <div class="container mx-auto max-w-3xl px-4 py-8 md:py-12">

        <!-- Story Content -->
        <div class="space-y-12">

            <!-- Cover Image -->
            <div class="story-block">
                <h2 class="text-2xl font-bold text-center mb-4">Lena's Story</h2>
                <img src="/images/stories/lena/coverimage.png" alt="Lena in a server room" class="rounded-lg shadow-md w-full">
            </div>

            <!-- Section 1 -->
            <div class="story-block">
                <img src="/images/stories/lena/image1.png" alt="Lena looking at complex data on multiple monitors" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    The noise was winning!? For Dr. Lena Kwon, a computational biologist of formidable intellect, this was an unfamiliar and deeply frustrating sensation. Her monitors displayed elegant, chaotic swirls of genomic data, a vast digital ocean that was supposed to contain a signal—a hidden regulatory network. But after six months of relentless work, she was no closer to finding it. Her logic was sound, her algorithms powerful, but she was creatively stalled, staring at the same wall of data day after day.
                </p>
            </div>

            <!-- Section 2 -->
            <div class="story-block">
                <img src="/images/stories/lena/image2.png" alt="Lena researching at her desk late at night" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    One night, abandoning her own dead-end simulations, Lena dove into the wider ocean of scientific literature. She searched for cognitive enhancers, nootropics, anything that might offer a new angle. Deep in the archives, she found it: a small cluster of studies on psychedelic microdosing. A particular synthetic tryptamine, 4-HO-MET, caught her eye. It was a psilocin analog, and the papers, though limited, spoke of its potential cognitive benefits. Her search led her from the studies to a company called <span style="color:#000000">microDOS(2)</span>, which seemed to be commercializing this exact compound.
                </p>
            </div>

            <!-- Section 3 -->
            <div class="story-block">
                <img src="/images/stories/lena/image3.png" alt="Lena looking at the microDOS(2) website" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    The scientist in her scoffed. The studies were sparse, the potential for a placebo effect enormous. But the phrases "increased divergent thinking" and "enhanced focus" on the company's website resonated with her predicament. The site was professional, referencing the very studies she had just read and offering the product in precisely dosed tablets via a subscription model. The idea solidified. She wouldn't be a user. She would conduct an experiment, with a sample size of one.
                </p>
            </div>

            <!-- Section 4 -->
            <div class="story-block">
                <img src="/images/stories/lena/image4.png" alt="Lena examining the microDOS(2) package" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    A week later, a discreet, minimalist package arrived. Inside, nestled in clean packaging, was a box labeled "<span style="color:#000000">microDOS(2)</span>." There was no powder, no solvent. Instead, she found a blister pack containing small, white, professionally manufactured tablets, each scored down the middle. Lena examined one under her desk lamp, her scientist's eye appreciating the precision. This was not a clandestine chemical; it was a tool, manufactured to a standard. This was a protocol she could respect.
                </p>
            </div>

            <!-- Section 5 -->
            <div class="story-block">
                <img src="/images/stories/lena/image5a.png" alt="Lena writing in her lab journal" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    She initiated the Fadiman protocol: one dose every three days. On the first morning, she carefully split one of the 2mg tablets in half. She waited. There was no sudden insight, no wash of color. The world remained stubbornly itself. She opened a new lab journal, its cover stark white, and wrote: "Day 1. Dose: 1mg (1/2 tablet). No perceptible psychoactive effects. Placebo possibility remains high."
                </p>
            </div>

            <!-- Section 6 -->
            <div class="story-block">
                <img src="/images/stories/lena/image6.png" alt="Lena focused on her work at the computer" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    She turned to the wall of data. The familiar dread was absent, replaced by a quiet neutrality. The work itself felt different—smoother. The usual cognitive friction, the mental effort required to hold complex models in her head, seemed reduced. She worked for hours, absorbed, the usual distractions of the world fading into a muted background hum.
                </p>
            </div>

            <!-- Section 7 -->
            <div class="story-block">
                <img src="/images/stories/lena/image7a.png" alt="Lena sketching a new idea on a whiteboard" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    Day two. No dose. While reviewing the previous day's work, a stray thought surfaced. An anomaly in the data, a clustering pattern she had previously dismissed as noise, bore a striking resemblance to a fractal structure she'd seen in a lecture on chaos theory. The connection was tenuous, illogical even. But it was the first truly new idea she'd had in months. She sketched it on her whiteboard, a flicker of genuine curiosity in her eyes.
                </p>
            </div>

            <!-- Section 8 -->
            <div class="story-block">
                <img src="/images/stories/lena/image8.png" alt="Lena coding a new analytical model" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    The cycles continued. The subtle focus on dose days was consistently followed by a quiet loosening of her cognitive rigidity on the days after. More unconventional ideas began to surface. She started coding a new analytical model from scratch, one that controversially incorporated principles from network dynamics into her biological dataset. It was a wild, cross-disciplinary gambit, but it felt like the only way forward.
                </p>
            </div>

            <!-- Section 9 -->
            <div class="story-block">
                <img src="/images/stories/lena/image9b.png" alt="Lena celebrating a breakthrough at her computer" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    She compiled the code and ran the simulation. She braced for the familiar cascade of errors or, worse, the usual meaningless noise. But this time, something different happened. On the screen, the chaotic data points began to shift, to align, to self-organize. A structure emerged, an elegant and undeniable signal rising from the static. It was the network. It was the breakthrough.
                </p>
            </div>

            <!-- Section 10 -->
            <div class="story-block">
                <img src="/images/stories/lena/image10.png" alt="Lena looking at her completed work" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    Lena picked up her white journal one last time. Her final entry was as precise as her first. "Conclusion: The protocol correlated with a period of increased creativity and novel problem-solving, culminating in a research breakthrough. While causality cannot be proven, the hypothesis that sub-perceptual doses of 4-HO-MET can act as a tool to overcome cognitive rigidity is supported. Data point of one. Further research is warranted."
                </p>
            </div>

        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-12">
            <div class="container mx-auto px-4 text-center">
                <h3 class="text-2xl font-bold text-white mb-4">Ready to Transform Your Performance?</h3>
                <p class="text-blue-100 mb-6 max-w-2xl mx-auto">Join thousands who have discovered mental clarity and peak performance through microdosing.</p>
                <a href="/" class="inline-block bg-white text-blue-600 font-bold py-4 px-8 rounded-lg hover:bg-gray-100 transition duration-300 shadow-lg transform hover:scale-105 text-lg">
                    Start Your Trial Today
                </a>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>
