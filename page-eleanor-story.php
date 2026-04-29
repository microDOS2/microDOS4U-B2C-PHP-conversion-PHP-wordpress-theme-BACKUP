<?php
/*
Template Name: Eleanor's Story
*/
get_header();

<nav class="main-navigation hidden md:flex items-center space-x-8">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-300 hover:text-white transition">Home</a>
            <a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="text-slate-300 hover:text-white transition">Articles & Studies</a>
            <a href="<?php echo esc_url(home_url('/metocin-info')); ?>" class="text-slate-300 hover:text-white transition">Metocin Info</a>
            <a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="text-slate-300 hover:text-white transition">Dosage Guide</a>
        </nav>
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

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .story-page-body .row { flex-direction: column; align-items: flex-start; }
        .story-page-body h1 { font-size: 20px; margin-bottom: 10px; }
        .story-page-body .nav-links {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 8px;
        }
        .story-page-body .nav-links .btn {
            width: 100%;
            text-align: center;
            padding: 12px;
            font-size: 14px;
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
                <h2 class="text-2xl font-bold text-center mb-4">Eleanor's Story</h2>
                <img src="/images/stories/eleanor/elenor-cover.png" alt="Eleanor looking serene over a city skyline" class="rounded-lg shadow-md w-full">
            </div>

            <!-- Section 1 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image1.png" alt="Eleanor looking overwhelmed at her desk" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    Eleanor's life was a constant, buzzing hum of anxiety. As a project manager, her days were a blur of notifications, urgent emails, and the relentless pressure of deadlines. Her own creative projects gathered dust, and her world felt muted, as if the color had been slowly drained from everything. She was running on a treadmill of stress, and the off switch was nowhere in sight.
                </p>
            </div>

            <!-- Section 2 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image2.png" alt="Eleanor at her breaking point" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    One Monday, the hum crescendoed into a roar. A major project she was leading hit a critical, unforeseen snag. The panic was like a physical weight on her chest. That night, sleep offered no escape. She felt stuck, not just in her job, but in a life that felt like it was happening to her. She needed more than a vacation; she needed a new perspective.
                </p>
            </div>

            <!-- Section 3 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image3.png" alt="Eleanor discovering the microDOS(2) website" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    A friend had once mentioned microdosing. On a whim, Eleanor found the website: <span style="color:#000000">microDOS4U</span>. She read about Metocin (4-HO-MET) and its potential benefits: enhanced focus, increased creativity, and reduced anxiety. A spark of hope flickered. She ordered the trial pack.
                </p>
            </div>

            <!-- Section 4 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image4.png" alt="Eleanor preparing her first dose" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    The discreet package arrived. On a quiet Saturday morning, she prepared for her first dose, remembering the guide's advice. Set and setting were key, so she chose her calm, sunlit apartment. She started low, using a small splitter to divide one of the precisely scored tablets in half and took it with a full glass of water.
                </p>
            </div>

            <!-- Section 5 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image5.png" alt="Eleanor feeling the shift and seeing colors" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    An hour later, the change was subtle, yet profound. It wasn't a high; it was a hush. The frantic, buzzing hum in her head had simply... stopped. The anxiety receded, leaving a quiet space. She looked at the painting on her wall and saw the brushstrokes, the texture, the vibrant dance of colors she hadn't truly noticed in years.
                </p>
            </div>

            <!-- Section 6 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image6.png" alt="Eleanor having a creative breakthrough at work" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    With this newfound calm, she sat down with the problem from work. Instead of the usual panic, she felt a clear, gentle focus. She saw the project not as a tangled mess, but as a system of interconnected parts. A creative, elegant solution unfolded in her mind, a path she had been completely blind to before.
                </p>
            </div>

            <!-- Section 7 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image7.png" alt="Eleanor on a dose day" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    She began a new routine, taking a dose every other day. On dose days, she experienced that same clarity and creative flow, allowing her to tackle her most challenging work with ease. The world seemed more vibrant, her thoughts more organized, her outlook more positive.
                </p>
            </div>

            <!-- Section 8 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image8.png" alt="Eleanor rediscovering her hobbies and painting on an off day" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    The "off" days were just as transformative. The effects weren't a switch that flipped off; a sense of calm and clarity lingered. She found it easier to manage stress and stay present. The microdose wasn't a constant crutch; it was a tool that was teaching her a new way to operate.
                </p>
            </div>

            <!-- Section 9 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image9.png" alt="Eleanor presenting confidently to her team" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    Weeks turned into months. Eleanor was no longer just surviving her life; she was living it. Her innovative solutions at work earned her recognition, but the real prize was internal. The constant, buzzing hum of anxiety had been replaced by quiet confidence. She was the one in control.
                </p>
            </div>

            <!-- Section 10 -->
            <div class="story-block">
                <img src="/images/stories/eleanor/image10.png" alt="Eleanor looking out at the city, serene and hopeful" class="rounded-lg shadow-md w-full mb-6">
                <p class="text-gray-700 leading-relaxed text-lg">
                    Microdosing didn't magically solve her problems. It quieted the noise, allowing her to access the focus, creativity, and calm she had within her all along. Her world was full of color again, not because something was added, but because she could finally see what was already there.
                </p>
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
</div>

<?php get_footer(); ?>
