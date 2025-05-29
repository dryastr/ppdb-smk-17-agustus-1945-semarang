<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Sekolah | Pendaftaran Peserta Didik Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#D32024',
                        secondary: '#00ADEF',
                        primarySoft: '#F8D7D8',
                        secondarySoft: '#E1F5FE',
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .text-shadow {
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            }

            .card-hover {
                @apply transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg;
            }
        }

        * {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="font-poppins bg-gray-50">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="https://smk17smg.sch.id/wp-content/uploads/2020/06/logo.png" class="h-20 mr-3"
                        alt="">
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-primary font-medium">Beranda</a>
                    <a href="#" class="text-gray-600 hover:text-primary transition">Persyaratan</a>
                    <a href="#" class="text-gray-600 hover:text-primary transition">Alur Pendaftaran</a>
                    <a href="#faq" class="text-gray-600 hover:text-primary transition">FAQ</a>
                    <a href="{{ route('login') }}"
                        class="bg-primary hover:bg-red-700 text-white px-4 py-2 rounded-md transition">Masuk</a>
                </div>
                <div class="md:hidden">
                    <button class="text-gray-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- {{dd($heroSection)}} --}}
    <section class="py-16 md:py-24 bg-gradient-to-r from-primarySoft to-secondarySoft">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 leading-tight mb-4">
                        {{ $heroSection->title ?? 'Pendaftaran Peserta Didik Baru' }}
                    </h1>
                    <p class="text-gray-600 mb-8 text-lg">
                        {{ $heroSection->description ?? 'Selamat datang di portal PPDB online sekolah kami. Daftarkan putra/putri Anda untuk bergabung dengan tempat belajar yang inspiratif dan penuh prestasi.' }}
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}"
                            class="bg-primary hover:bg-red-700 text-white px-6 py-3 rounded-md text-center font-medium transition shadow-md">Daftar
                            Sekarang</a>
                        <a href="{{ $heroSection->link_persyaratan ?? '#' }}"
                            class="border border-primary text-primary hover:bg-primary hover:text-white px-6 py-3 rounded-md text-center font-medium transition shadow-md">Lihat
                            Persyaratan</a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <img src="{{ $heroSection->image ? Storage::url($heroSection->image) : 'http://via.placeholder.com/600x400' }}"
                        alt="{{ $heroSection->title ?? 'Hero Section Image' }}"
                        class="rounded-lg shadow-xl w-full max-w-md">
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white" id="registration-flow">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Alur Pendaftaran</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-primary to-secondary mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($registrationFlows as $flow)
                    @php
                        $borderColorClass = $flow->step_number % 2 == 1 ? 'border-primary' : 'border-secondary';
                        $bgColorClass = $flow->step_number % 2 == 1 ? 'bg-primarySoft' : 'bg-secondarySoft';
                        $textColorClass = $flow->step_number % 2 == 1 ? 'text-primary' : 'text-secondary';
                    @endphp
                    <div class="bg-white rounded-lg shadow-md p-6 border-t-4 {{ $borderColorClass }} card-hover">
                        <div
                            class="w-12 h-12 {{ $bgColorClass }} rounded-full flex items-center justify-center {{ $textColorClass }} font-bold text-xl mb-4">
                            @if ($flow->icon)
                                <i class="{{ $flow->icon }}"></i>
                            @else
                                {{-- {{ $flow->step_number }} --}}
                            @endif
                            {{ $loop->iteration }}
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $flow->title }}</h3>
                        <p class="text-gray-600">{{ $flow->description }}</p>
                    </div>
                @empty
                    <div class="md:col-span-4 text-center text-gray-600 py-10">Belum ada Alur Pendaftaran yang diatur.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50" id="faq">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Frequently Asked Questions</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-primary to-secondary mx-auto"></div>
            </div>

            <div class="max-w-3xl mx-auto">
                @forelse ($sortedFaqs as $faq)
                    <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden faq-item">
                        <button
                            class="w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 transition faq-button">
                            <span class="font-medium text-gray-800">{{ $faq->question }}</span>
                            <svg class="w-5 h-5 text-primary faq-icon" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="p-4 bg-gray-50 text-gray-600 hidden faq-content">
                            <p>{!! nl2br(e($faq->answer)) !!}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-600">Tidak ada FAQ yang tersedia saat ini.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-16 bg-gradient-to-r from-primary to-secondary">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Siap Mendaftarkan Putra/Putri Anda?</h2>
            <p class="text-white mb-8 max-w-2xl mx-auto">Bergabunglah dengan tempat belajar yang inspiratif dan penuh
                prestasi. Daftarkan sekarang sebelum kuota penuh!</p>
            <a href="#"
                class="bg-white text-primary hover:bg-gray-100 px-8 py-3 rounded-md font-medium text-lg transition shadow-lg inline-block">Daftar
                Sekarang</a>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-r from-primary to-secondary flex items-center justify-center text-white font-bold text-lg">
                            S</div>
                        <span class="ml-3 text-xl font-semibold">PPDB Sekolah</span>
                    </div>
                    <p class="text-gray-400">Portal resmi pendaftaran peserta didik baru sekolah kami. Memberikan
                        pendidikan berkualitas untuk generasi penerus bangsa.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Beranda</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Persyaratan</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Alur Pendaftaran</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak Kami</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            (021) 1234567
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            ppdb@sekolah.example
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Jl. Pendidikan No. 123, Jakarta
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Jam Operasional</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex justify-between">
                            <span>Senin - Jumat</span>
                            <span>08:00 - 16:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Sabtu</span>
                            <span>08:00 - 12:00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Minggu</span>
                            <span>Libur</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 PPDB Sekolah. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                if (content.style.display === 'block') {
                    content.style.display = 'none';
                } else {
                    content.style.display = 'block';
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqButtons = document.querySelectorAll('.faq-button');

            faqButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const faqItem = this.closest('.faq-item');
                    const faqContent = faqItem.querySelector('.faq-content');
                    const faqIcon = faqItem.querySelector('.faq-icon');

                    faqButtons.forEach(otherButton => {
                        const otherFaqItem = otherButton.closest('.faq-item');
                        if (otherFaqItem !== faqItem) {
                            otherFaqItem.querySelector('.faq-content').classList.add(
                                'hidden');
                            otherFaqItem.querySelector('.faq-icon').style.transform =
                                'rotate(0deg)';
                        }
                    });

                    faqContent.classList.toggle('hidden');
                });
            });
        });
    </script>
</body>

</html>
