<?php
/*
Template Name: Jalen's Story
*/
get_header();
?>

<style>
    .story-page-body {
        font-family: 'Inter', sans-serif;
        background-color: #0a0514;
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

<div class="story-page-body bg-gray-100 text-gray-800">
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <h1 class="text-3xl md:text-4xl font-bold text-center text-white mb-2">The Quiet Score</h1>
        <h2 class="text-2xl md:text-3xl font-bold text-center text-gray-300 mb-8">Jalen's Story</h2>

        <!-- Cover -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden mb-8">
            <div class="p-6 flex items-center justify-center">
                <img src="/images/stories/jalen/cover-image.png" alt="Cover image for The Quiet Score storyboard" class="rounded-xl shadow-lg max-w-full md:max-w-2xl h-auto object-contain">
            </div>
        </div>

        <!-- Scene 1 -->
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden mb-8">
            <div class="grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-5 p-6 flex items-center justify-center">
                    <img src="/images/stories/jalen/image1.png" alt="A basketball player sits in a locker room with his head in his hands." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> The Crushing Weight</h3>
                    <p class="text-base leading-relaxed">
                        Jalen's world was the relentless squeak of sneakers on polished hardwood, the roar of the crowd a constant, crushing weight. He was a machine built for basketball, all power and grace, but inside, his mind was a tangled mess of anxiety. Every missed shot, every critical comment from the commentators, echoed in his head, a deafening chorus of failure. The joy he once found in the game was fading, replaced by a gnawing dread.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Scene 2 -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center order-2 md:order-1">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> The Final Buzzer</h3>
                    <p class="text-base leading-relaxed">
                        It was the fourth quarter, seconds on the clock, and the ball was in his hands. The pressure was a physical force, blurring his vision, tightening his muscles. He hesitated, a fatal moment of indecision, and the opportunity was gone. The final buzzer sounded like a death knell. In the ensuing silence of the locker room, the weight of his failure was suffocating. He wasn't just letting down his team; he was letting down himself.
                    </p>
                </div>
                <div class="md:col-span-5 p-6 flex items-center justify-center order-1 md:order-2">
                    <img src="/images/stories/jalen/image2.png" alt="A basketball player looks defeated in a locker room." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
            </div>

            <!-- Scene 3 -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-5 p-6 flex items-center justify-center">
                    <img src="/images/stories/jalen/image3.png" alt="Two men sitting at a table, one is showing the other something on his phone." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> A Friend's Advice</h3>
                    <p class="text-base leading-relaxed">
                        A few weeks later, nursing a sprained ankle and a bruised ego, he met up with an old teammate, David, who'd left the league to launch a successful tech startup. David listened patiently, then told him about his own battles with burnout. He mentioned something called microdosing, a way to find clarity and focus without the high. He pointed Jalen to a website: <span style="color:#000000">microDOS4U</span>.
                    </p>
                </div>
            </div>

            <!-- Scene 4 -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center order-2 md:order-1">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> The Research</h3>
                    <p class="text-base leading-relaxed">
                        That night, Jalen found himself scrolling through the <span style="color:#000000">microDOS4U</span> site. He read about Metocin (4-HO-MET), a compound described as offering a "cleaner" experience than mushrooms, with benefits like enhanced focus and reduced anxiety. He read user testimonials from professionals in high-pressure fields. The site was clear: this was for adults only and was legal to order. A flicker of hope ignited in the darkness. He ordered the trial pack.
                    </p>
                </div>
                <div class="md:col-span-5 p-6 flex items-center justify-center order-1 md:order-2">
                    <img src="/images/stories/jalen/image4.png" alt="A man sits at a table in his apartment, looking thoughtfully at a laptop screen." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
            </div>

            <!-- Scene 5 -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-5 p-6 flex items-center justify-center">
                    <img src="/images/stories/jalen/image5.png" alt="A man in his kitchen takes a tablet out of a blister pack, with a box labeled microDOS(2) on the counter." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> The First Step</h3>
                    <p class="text-base leading-relaxed">
                        The package arrived, discreet and unassuming. On a quiet morning, with no practice scheduled, he followed the guide's advice. Set and setting were paramount. He was in his calm, sunlit apartment. He started low, using a small splitter to divide one of the scored tablets in half. He drank a full glass of water and waited, a knot of apprehension and hope in his stomach.
                    </p>
                </div>
            </div>

            <!-- Scene 6 -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center order-2 md:order-1">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> A Quiet Mind</h3>
                    <p class="text-base leading-relaxed">
                        An hour passed. It wasn't a high, not even close. It was quiet. The relentless, critical voice in his head had fallen silent. The anxiety that had been his constant companion for years simply...receded. He looked out his window at the city skyline and saw the intricate details of the architecture, the play of light and shadow, with a clarity he'd never experienced before. He felt present, grounded, and calm.
                    </p>
                </div>
                <div class="md:col-span-5 p-6 flex items-center justify-center order-1 md:order-2">
                    <img src="/images/stories/jalen/image6.png" alt="A man stands looking out a large window at a city skyline, looking calm and contemplative." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
            </div>

            <!-- Scene 7 -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-5 p-6 flex items-center justify-center">
                    <img src="/images/stories/jalen/image7.png" alt="A basketball player dribbles intensely, guarded by an opponent in a bright arena." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> A New Routine</h3>
                    <p class="text-base leading-relaxed">
                        He started a new routine: a microdose every other day. On dose days, he felt a heightened sense of focus and creativity. During practice, he saw the court differently, not as a series of threats and potential failures, but as a canvas of opportunities. He was anticipating plays, moving with a fluid, intuitive grace that had been locked away by his anxiety.
                    </p>
                </div>
            </div>

            <!-- Scene 8 -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center order-2 md:order-1">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> Transformative Days</h3>
                    <p class="text-base leading-relaxed">
                        The "off" days were just as transformative. The calm and clarity lingered, a new baseline for his mental state. He found it easier to manage the daily pressures, to let go of mistakes and stay in the moment. The microdose wasn't a crutch; it was a tool that was recalibrating his mind, teaching him a new, more resilient way to operate. His recovery was faster, his sleep deeper.
                    </p>
                </div>
                <div class="md:col-span-5 p-6 flex items-center justify-center order-1 md:order-2">
                    <img src="/images/stories/jalen/image8.png" alt="A man meditates on the floor of his sunlit apartment." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
            </div>

            <!-- Scene 9 -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-5 p-6 flex items-center justify-center">
                    <img src="/images/stories/jalen/image9.png" alt="A basketball player stands on the court, smiling joyfully while holding a basketball." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> The Joyful Return</h3>
                    <p class="text-base leading-relaxed">
                        Weeks turned into months. Jalen returned to the court a different man. The raw talent was still there, but now it was guided by a quiet confidence and a sharp, uncluttered focus. He was playing with a joy and freedom he hadn't felt since he was a kid. The media called it a comeback, a story of renewed mental toughness. Jalen knew it was something deeper.
                    </p>
                </div>
            </div>

            <!-- Scene 10 -->
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-7 p-6 md:p-8 flex flex-col justify-center order-2 md:order-1">
                    <h3 class="text-2xl font-bold mb-3 text-gray-800"> The Game is His</h3>
                    <p class="text-base leading-relaxed mb-4">
                        Microdosing with <span style="color:#000000">microDOS(2)</span> hadn't been a magic pill. It had been a key, unlocking the potential that was within him all along. It had quieted the noise, allowing him to hear his own instincts, to trust his own talent. He was more than just a better player; he was a more whole person, in control of his mind, his body, and his destiny. The game was his again.
                    </p>
                </div>
                <div class="md:col-span-5 p-6 flex items-center justify-center order-1 md:order-2">
                    <img src="/images/stories/jalen/image10.png" alt="A confident basketball player holds a basketball and looks forward with a focused expression in an arena." class="rounded-xl shadow-lg w-full h-auto object-cover">
                </div>
            </div>
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

<?php get_footer(); ?>
