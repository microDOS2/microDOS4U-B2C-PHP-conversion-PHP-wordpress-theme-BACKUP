<?php
/*
Template Name: Mateo's Story
*/
get_header();

?>

<style>
    .story-page-body {
        font-family: 'Inter', sans-serif;
        background-color: #0a0514;
    }
    .story-page-body h1, .story-page-body h2 {
        font-family: 'Lora', serif;
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

<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">

<div class="story-page-body bg-gray-50 text-gray-800">
    <div class="container mx-auto p-4 sm:p-8 max-w-5xl">

        <h1 class="text-3xl md:text-4xl font-bold text-center mb-8" style="font-family:'Lora',serif;color:#fff;">The Professor's Spark</h1>

        <main class="space-y-16">
            <!-- Story Section 1 -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image2.png" alt="Professor Mateo Garcia in his office surrounded by books." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        Professor Mateo Garcia loved literature. The towering stacks of books in his office were a testament to a life spent in the company of great stories. But lately, the stories had started to feel distant, their words like faded ink on a forgotten page. The spark he once felt was gone, replaced by a quiet, persistent weariness.
                    </p>
                </div>
            </section>

            <!-- Story Section 2 -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image3.png" alt="Professor Garcia looking disheartened in a lecture hall." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        In the lecture hall, his voice, which once boomed with passion, now felt like a monotone drone. He saw the glazed-over eyes of his students and felt a familiar pang of disappointment. The connection he cherished, the shared joy of discovery, felt miles away.
                    </p>
                </div>
            </section>

            <!-- Story Section 3 -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image4.png" alt="Mateo talking with his friend Dr. Anya Sharma at a cafe." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        "You've lost your light, Mateo," his friend and mentor, Dr. Anya Sharma, said over coffee. She had seen it before - the slow dimming of a brilliant mind under the weight of routine. "Sometimes," she said, sliding a discreetly folded note across the table, "you need a new lens to see the old world."
                    </p>
                </div>
            </section>

            <!-- Story Section 4 -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image5.png" alt="Mateo looking at his laptop in his study." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        Back in the quiet of his study, Mateo unfolded the note. On it was a single web address. A flicker of skepticism battled with a spark of hope. He opened his laptop, his fingers hovering over the keyboard, and began to read. He found articles, studies, and stories of people just like him.
                    </p>
                </div>
            </section>

            <!-- Story Section 5 -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image6.png" alt="Mateo receiving a discreet package at his door." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        A few days later, a small, unassuming package arrived. The packaging was simple, professional, and discreet. Holding the box, Mateo felt a flutter of nervous excitement. This felt different. It felt like a choice, a step toward reclaiming the person he used to be.
                    </p>
                </div>
            </section>

            <!-- Story Section 6 -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image7.png" alt="Mateo walking in a park, looking up at the trees with a sense of wonder." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        He began his journey with a quarter tablet, as the guide suggested. The change wasn't a lightning strike, but a quiet sunrise. On his walk through the campus park, the green of the leaves seemed deeper, the birdsong clearer. A gentle warmth spread through his chest - a feeling he hadn't realized he'd been missing.
                    </p>
                </div>
            </section>

            <!-- Story Section 7 -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image8.png" alt="Mateo lecturing to an engaged and interested class of students." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        The next day in class, the words flowed effortlessly. He connected a centuries-old poem to the lyrics of a modern song, and for the first time in months, he saw it: the spark of understanding in his students' eyes. They were leaning forward, engaged, asking questions. The connection was back.
                    </p>
                </div>
            </section>

            <!-- Story Section 8 -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image9.png" alt="Mateo talking one-on-one with a student after class." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        After class, a student who had never spoken before stayed behind. "Professor," she began shyly, "I never thought I'd get poetry. But the way you explained it today... it just clicked." Mateo listened, truly listened, and felt a profound sense of purpose wash over him.
                    </p>
                </div>
            </section>

            <!-- Story Section 9 -->
            <section class="flex flex-col md:flex-row items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image10.png" alt="Mateo joyfully painting on an easel in his study." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        The renewed energy spilled over into his life outside the university. He pulled out an old, dusty easel and began to paint again, a passion he had long since abandoned. Colors bloomed on the canvas, vibrant and alive. He wasn't just teaching stories anymore; he was living his own.
                    </p>
                </div>
            </section>

            <!-- Story Section 10 -->
            <section class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white p-8 rounded-lg shadow-lg">
                <div class="md:w-1/2">
                    <img src="/images/stories/mateo/image11.png" alt="A confident and happy Professor Garcia standing in front of his full lecture hall." class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="md:w-1/2">
                    <p class="text-lg leading-relaxed text-black">
                        Professor Garcia still loved literature. But now, the stories felt like his own. The lecture hall was no longer a stage, but a shared space of discovery. He had found his light again, and it was brighter than ever, illuminating the path for him and for the young minds he was privileged to teach.
                    </p>
                </div>
            </section>
        </main>

        <!-- Call to Action Button -->
        <footer class="text-center my-16">
            <a href="/"
               class="inline-block bg-blue-600 text-white font-bold text-lg px-8 py-4 rounded-lg shadow-lg hover:bg-blue-700 transition-transform transform hover:scale-105 duration-300 ease-in-out">
                Start Your Trial
            </a>
        </footer>

    </div>
</div>

<?php get_footer(); ?>
