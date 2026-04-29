<?php
/*
Template Name: Joe's Story
*/
get_header();

?>

<style>
    .story-page-body {
        font-family: 'Inter', sans-serif;
        background-color: #0a0514;
    }
    /* Adding a subtle animation for sections */
    .fade-in-section {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeIn 1s ease-out forwards;
    }
    .fade-in-section:nth-child(2) { animation-delay: 0.2s; }
    .fade-in-section:nth-child(3) { animation-delay: 0.4s; }
    .fade-in-section:nth-child(4) { animation-delay: 0.6s; }
    .fade-in-section:nth-child(5) { animation-delay: 0.8s; }
    .fade-in-section:nth-child(6) { animation-delay: 1.0s; }
    .fade-in-section:nth-child(7) { animation-delay: 1.2s; }
    .fade-in-section:nth-child(8) { animation-delay: 1.4s; }
    .fade-in-section:nth-child(9) { animation-delay: 1.6s; }
    .fade-in-section:nth-child(10) { animation-delay: 1.8s; }
    .fade-in-section:nth-child(11) { animation-delay: 2.0s; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
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

<div class="story-page-body bg-gray-50 text-gray-800">
    <div class="container mx-auto px-4 py-8 md:py-16">

        <h1 class="text-3xl md:text-4xl font-bold text-center mb-2" style="color:#fff;">The Day the Colors Came Back</h1>
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-12" style="color:#ccc;">Joe's Story</h2>

        <!-- Story Sections -->
        <div class="max-w-3xl mx-auto space-y-16">

            <!-- Section 1: Introduction -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="w-full">
                    <img src="/images/stories/joe/pic-1.png" alt="Joe looking out over a city skyline at dusk" class="rounded-lg shadow-xl w-full">
                </div>
            </section>

            <!-- Section 2: The Problem -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="md:w-1/2">
                    <img src="/images/stories/joe/pic-2.png" alt="Joe looking stressed at his computer" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">The Problem - Creative Burnout</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Joe's world had become a grayscale sketch of its former self. The vibrant colors he once commanded on screen now felt miles away, trapped behind a fog of creative burnout. Deadlines loomed like skyscrapers, casting long shadows over his messy desk, and the cursor on his blank monitor blinked, a tiny, mocking heartbeat counting down his failure.
                    </p>
                </div>
            </section>

            <!-- Section 3: The Inciting Incident -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="md:w-1/2">
                    <img src="/images/stories/joe/pic-3.png" alt="Joe looking at his phone with a hopeful expression" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">A Glimmer of Hope</h2>
                    <p class="text-gray-700 leading-relaxed">
                        He escaped into the endless scroll of his phone, a digital anesthetic for his anxious mind. Then, something cut through the noise. It wasn't loud or flashy. It was an ad, clean and minimalist: '<span style="color:#000000">microDOS(2)</span> | Precision Psychedelics, Simplified.' The words 'Pure Clarity' and 'Creative Flow' seemed to hum with a quiet promise.
                    </p>
                </div>
            </section>

            <!-- Section 4: The Debate -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="md:w-1/2">
                    <img src="/images/stories/joe/pic-4.png" alt="Joe looking at a website on his computer" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">The Debate - Skepticism vs. Desperation</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Skepticism, his old, familiar coat, settled on his shoulders. He'd heard promises before. But as he clicked through to the website, the words resonated with his logical mind: 'Rapid Onset,' 'No Nausea,' 'Precision Dose.' It wasn't about escaping reality, it seemed, but refining it. His mouse hovered over a button: 'Start Your $9.99 Trial.' For the price of lunch, what did he have to lose?
                    </p>
                </div>
            </section>

            <!-- Section 5: Universe of Potential -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="md:w-1/2">
                    <img src="/images/stories/joe/pic-5.png" alt="Joe holding a small, plain white box" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">Universe of Potential</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Two days later, a small, plain box arrived. No logos, no fanfare. Just a simple package that held a universe of potential. He turned it over in his hands. It felt heavier than it should, weighted with the possibility of change. The fog in his mind felt a little thicker, a last stand against the coming light.
                    </p>
                </div>
            </section>

            <!-- Section 6: A Calculated Move -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="md:w-1/2">
                    <img src="/images/stories/joe/pic-6.png" alt="A hand holding a small pill" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">A Calculated Move</h2>
                    <p class="text-gray-700 leading-relaxed">
                        In his kitchen, with the morning sun trying to break through the blinds, he opened the box. Inside was a container that looked more like it belonged in a high-tech lab than a head shop. He tapped one small, scored pill into his palm. It was unassuming, precise. This wasn't a leap of faith into the unknown; it felt like a calculated step toward clarity.
                    </p>
                </div>
            </section>

            <!-- Section 7: The Wait -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="md:w-1/2">
                    <img src="/images/stories/joe/pic-7.png" alt="Joe sitting at his desk, looking thoughtful" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">The Wait - A Quiet Beginning</h2>
                    <p class="text-gray-700 leading-relaxed">
                        He swallowed the pill with a sip of water and sat back at his desk, expecting a thunderclap that never came. There was no jolt, no sudden rush. He simply sat, watching the blinking cursor, feeling... normal. He picked up his stylus, the familiar weight a comfort in his hand, and hovered it over the screen.
                    </p>
                </div>
            </section>

            <!-- Section 8: The Shift -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="md:w-1/2">
                    <img src="/images/stories/joe/pic-8.png" alt="Joe looking more focused with a stylus in hand" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">The Shift - A Gentle Tuning</h2>
                    <p class="text-gray-700 leading-relaxed">
                        Then, it began. Not a change, but a tuning. The edges of the icons on his screen seemed sharper. The hum of the city outside his window became a background rhythm rather than a distraction. An idea, timid at first, peeked out from behind the fog. A line, a curve, a splash of color. His hand started to move, not forced, but flowing.
                    </p>
                </div>
            </section>

            <!-- Section 9: The Flow -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="md:w-1/2">
                    <img src="/images/stories/joe/pic-9.png" alt="Joe smiling and working on his computer, immersed in his creation" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">The Flow - Immersed in Creation</h2>
                    <p class="text-gray-700 leading-relaxed">
                        The blinking cursor was forgotten. The looming deadlines dissolved. There was only the work. The fog had lifted, not burned away by a harsh sun, but evaporated by a gentle, persistent clarity. Ideas connected, colors harmonized, and projects that had been his tormentors became his playground. The sun dipped below the horizon, but he barely noticed, bathed in the glow of his own creation.
                    </p>
                </div>
            </section>

            <!-- Section 10: The Resolution -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg fade-in-section">
                <div class="md:w-1/2">
                    <img src="/images/stories/joe/pic-10.png" alt="Joe looking proudly at his finished work on the computer screen" class="rounded-lg shadow-xl w-full">
                </div>
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900">The Resolution - Amplified Self</h2>
                    <p class="text-gray-700 leading-relaxed">
                        The next morning, he looked at the finished designs, and for the first time in months, he felt a surge of pride. It was his work, his style, but amplified. The creative voice he thought he had lost had just been speaking too quietly. <span style="color:#000000">microDOS(2)</span> hadn't given him the answers; it had simply cleared the room so he could hear them himself.
                    </p>
                </div>
            </section>

            <!-- Section 11: New Beginning & CTA -->
            <section class="text-center bg-white p-8 md:p-12 rounded-lg shadow-lg fade-in-section">
                <img src="/images/stories/joe/pic-11.png" alt="Joe subscribing to microDOS(2) on his computer" class="rounded-lg shadow-xl w-full max-w-2xl mx-auto mb-8">
                <h2 class="text-3xl font-bold mb-4 text-gray-900">A New Beginning - An Investment in Self</h2>
                <p class="text-gray-700 leading-relaxed max-w-2xl mx-auto mb-8">
                    It wasn't just about meeting deadlines anymore. It was about rediscovering the joy in his work... It was an investment in clarity. An investment in himself.
                </p>
                <a href="/" class="inline-block">
                    <button class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg transform hover:scale-105">
                        Start Your Trial Today
                    </button>
                </a>
            </section>

        </div>

        <!-- Footer -->
        <footer class="text-center mt-16 text-gray-500">
            <p></p>
        </footer>

    </div>
</div>

<?php get_footer(); ?>
