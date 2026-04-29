<?php
/**
 * Template Name: User Stories
 *
 * @package microDOS4U
 */

get_header();

<nav class="main-navigation hidden md:flex items-center space-x-8">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-300 hover:text-white transition">Home</a>
            <a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="text-slate-300 hover:text-white transition">Articles & Studies</a>
            <a href="<?php echo esc_url(home_url('/metocin-info')); ?>" class="text-slate-300 hover:text-white transition">Metocin Info</a>
            <a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="text-slate-300 hover:text-white transition">Dosage Guide</a>
        </nav>
?>

<main>
    <div class="container mx-auto px-6 py-16 md:py-24">

        <!-- Header Section -->
        <header class="text-center mb-16 md:mb-20">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4" style="text-shadow: 0 0 8px rgba(99, 102, 241, 0.5), 0 0 20px rgba(99, 102, 241, 0.3);">Real Stories. Real Clarity.</h1>
            <p class="text-lg md:text-xl text-gray-400 max-w-3xl mx-auto">
                Discover how professionals from all walks of life are cutting through the noise, overcoming creative blocks, and finding a new level of focus.
            </p>
        </header>

        <!-- Stories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 md:gap-12">

            <!-- Jalen's Story Card -->
            <div style="background-color: #150f24; border: 1px solid #1a1329; border-radius: 16px; padding: 32px; display: flex; flex-direction: column;">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/images/userstories/jalen.png'); ?>" alt="Portrait of Jalen" class="rounded-lg mb-6 w-full h-64 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/1f2937/9ca3af?text=Image+Not+Found';" />
                <h2 class="text-3xl font-bold text-white mb-3">Jalen's Quiet Score</h2>
                <p class="text-gray-400 mb-6 flex-grow">
                    A professional basketball player, Jalen was buckling under the crushing weight of performance anxiety. The roar of the crowd and the sting of every missed shot were deafening. He found a way to quiet the noise and rediscover the joy and intuitive grace in his game.
                </p>
                <blockquote class="border-l-4 border-indigo-500 pl-4 text-gray-300 italic mb-6">
                    "It had quieted the noise, allowing him to hear his own instincts, to trust his own talent."
                </blockquote>
                <a href="<?php echo esc_url(home_url('/jalens-story/')); ?>" class="text-center w-full font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105" style="background: linear-gradient(90deg, #38bdf8, #8b5cf6); color: #fff;">
                    Read Jalen's Full Story
                </a>
            </div>

            <!-- Eleanor's Story Card -->
            <div style="background-color: #150f24; border: 1px solid #1a1329; border-radius: 16px; padding: 32px; display: flex; flex-direction: column;">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/images/userstories/elanor.png'); ?>" alt="Portrait of Eleanor" class="rounded-lg mb-6 w-full h-64 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/1f2937/9ca3af?text=Image+Not+Found';" />
                <h2 class="text-3xl font-bold text-white mb-3">Eleanor's Quiet Revolution</h2>
                <p class="text-gray-400 mb-6 flex-grow">
                    Overwhelmed by a constant hum of anxiety and the relentless pressure of deadlines, project manager Eleanor felt her world losing its color. See how she traded stress for strategy and rediscovered her creative problem-solving abilities.
                </p>
                <blockquote class="border-l-4 border-indigo-500 pl-4 text-gray-300 italic mb-6">
                    "The anxiety receded, leaving a quiet space. I saw the project not as a tangled mess, but as a system of interconnected parts."
                </blockquote>
                <a href="<?php echo esc_url(home_url('/eleanors-story/')); ?>" class="text-center w-full font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105" style="background: linear-gradient(90deg, #38bdf8, #8b5cf6); color: #fff;">
                    Read Eleanor's Full Story
                </a>
            </div>

            <!-- Joe's Story Card -->
            <div style="background-color: #150f24; border: 1px solid #1a1329; border-radius: 16px; padding: 32px; display: flex; flex-direction: column;">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/images/userstories/pic9.png'); ?>" alt="Portrait of Joe" class="rounded-lg mb-6 w-full h-64 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/1f2937/9ca3af?text=Image+Not+Found';" />
                <h2 class="text-3xl font-bold text-white mb-3">The Day the Colors Came Back</h2>
                <p class="text-gray-400 mb-6 flex-grow">
                    For graphic designer Joe, creative burnout had turned his vibrant world grayscale. Faced with blank screens and looming deadlines, he'd lost the joy in his work. Discover how he lifted the fog and got back into his creative flow.
                </p>
                <blockquote class="border-l-4 border-indigo-500 pl-4 text-gray-300 italic mb-6">
                    "It wasn't a change, but a tuning... The creative voice I thought I had lost had just been speaking too quietly."
                </blockquote>
                <a href="<?php echo esc_url(home_url('/joes-story/')); ?>" class="text-center w-full font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105" style="background: linear-gradient(90deg, #38bdf8, #8b5cf6); color: #fff;">
                    Read Joe's Full Story
                </a>
            </div>

            <!-- Lena's Story Card -->
            <div style="background-color: #150f24; border: 1px solid #1a1329; border-radius: 16px; padding: 32px; display: flex; flex-direction: column;">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/images/userstories/imagelena.png'); ?>" alt="Portrait of Lena" class="rounded-lg mb-6 w-full h-64 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/1f2937/9ca3af?text=Image+Not+Found';" />
                <h2 class="text-3xl font-bold text-white mb-3">Lena's Luminous Code</h2>
                <p class="text-gray-400 mb-6 flex-grow">
                    Dr. Lena Kwon, a brilliant computational biologist, hit a wall, her search for a hidden genomic signal stalled for months. Facing creative burnout, she embarked on a personal, data-driven experiment with microdosing. This new protocol helped her break through the noise, leading to a fresh perspective and the breakthrough she had been relentlessly pursuing.
                </p>
                <blockquote class="border-l-4 border-indigo-500 pl-4 text-gray-300 italic mb-6">
                    "The protocol correlated with a period of increased creativity... culminating in a research breakthrough. Data point of one."
                </blockquote>
                <a href="<?php echo esc_url(home_url('/lenas-story/')); ?>" class="text-center w-full font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105" style="background: linear-gradient(90deg, #38bdf8, #8b5cf6); color: #fff;">
                    Read Lena's Full Story
                </a>
            </div>

            <!-- Banyu's Story Card -->
            <div style="background-color: #150f24; border: 1px solid #1a1329; border-radius: 16px; padding: 32px; display: flex; flex-direction: column;">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/images/userstories/Banyu.png'); ?>" alt="Portrait of Banyu" class="rounded-lg mb-6 w-full h-64 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/1f2937/9ca3af?text=Image+Not+Found';" />
                <h2 class="text-3xl font-bold text-white mb-3">Banyu's Blueprint for Clarity</h2>
                <p class="text-gray-400 mb-6 flex-grow">
                    A gifted architect, Banyu felt trapped by creative block, the city's vibrant energy turning into noise. Stalled on a prestigious project, he found a way to quiet the static and reconnect with his inspiration, leading to a brilliant design fusion of heritage and home.
                </p>
                <blockquote class="border-l-4 border-indigo-500 pl-4 text-gray-300 italic mb-6">
                    "The static was gone. In its place: quiet confidence and a renewed connection to creativity."
                </blockquote>
                <a href="<?php echo esc_url(home_url('/banyus-story/')); ?>" class="text-center w-full font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105" style="background: linear-gradient(90deg, #38bdf8, #8b5cf6); color: #fff;">
                    Read Banyu's Full Story
                </a>
            </div>

            <!-- Mateo's Story Card -->
            <div style="background-color: #150f24; border: 1px solid #1a1329; border-radius: 16px; padding: 32px; display: flex; flex-direction: column;">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/images/userstories/Mateo.png'); ?>" alt="Portrait of Mateo" class="rounded-lg mb-6 w-full h-64 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/1f2937/9ca3af?text=Image+Not+Found';" />
                <h2 class="text-3xl font-bold text-white mb-3">The Professor's Spark</h2>
                <p class="text-gray-400 mb-6 flex-grow">
                    Professor Mateo Garcia, a literature professor, had lost his passion for teaching. The connection with his students was gone, and his world felt weary. He discovered a way to reignite his inner spark, transforming his lectures and rediscovering his joy for the stories he loved.
                </p>
                <blockquote class="border-l-4 border-indigo-500 pl-4 text-gray-300 italic mb-6">
                    "He had found his light again, and it was brighter than ever, illuminating the path for him and for the young minds he was privileged to teach."
                </blockquote>
                <a href="<?php echo esc_url(home_url('/mateos-story/')); ?>" class="text-center w-full font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg transform hover:scale-105" style="background: linear-gradient(90deg, #38bdf8, #8b5cf6); color: #fff;">
                    Read Mateo's Full Story
                </a>
            </div>

        </div>

        <!-- CTA Section -->
        <div class="text-center mt-20">
            <a href="<?php echo esc_url(home_url('/#pricing')); ?>" class="inline-block text-white font-bold text-lg py-4 px-10 rounded-lg transition duration-300 shadow-lg transform hover:scale-105" style="background: linear-gradient(90deg, #38bdf8, #8b5cf6);">
                Start Your Trial Today
            </a>
        </div>

    </div>
</main>

<?php
get_footer();
