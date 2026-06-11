<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import '@hixbe/kalpurush';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../../../css/jss-age60-diamond.css';

const props = defineProps({
    essay: Object,
});

const isMenuOpen = ref(false);
const isDropdownOpen = ref(false);
const logoUrl = '/images/hirak-jayanti-logo.png';

const toBengaliDigits = (num) => {
    const bengaliDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return String(num).replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('bn-BD', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const showAccountsMessage = () => {
    alert('আয়-ব্যয় হিসাব: হীরক জয়ন্তী উদযাপনের আয়-ব্যয় হিসাব কার্যক্রম শেষ হলে এখানে বিস্তারিত প্রকাশ করা হবে। ধন্যবাদ!');
};
</script>

<template>
    <Head>
        <title>{{ essay.title }} | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</title>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    </Head>

    <div id="app" class="jss-age60-root bg-light min-vh-100">
        <header class="site-header">
            <!-- টপবার -->
            <div class="topbar-diamond">
                <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="topbar-contact">
                        <a href="tel:+8801713913076">
                            <i class="fas fa-phone-alt"></i><span> +৮৮০১৭১৩-৯১৩০৭৬, +৮৮০১৭৫৮-৮৯৩৪৩২</span>
                        </a>
                        <a href="mailto:age60@myjss.edu.bd">
                            <i class="fas fa-envelope"></i><span> age60@myjss.edu.bd</span>
                        </a>
                    </div>
                    <div class="topbar-actions">
                        <Link href="/login" class="btn-topbar">লগইন</Link>
                    </div>
                </div>
            </div>

            <!-- নেভবার -->
            <nav class="navbar navbar-expand-lg navbar-dark navbar-diamond">
                <div class="container">
                    <Link class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="/">
                        <img :src="logoUrl" alt="হীরক জয়ন্তী" class="site-logo" />
                        <div class="d-flex flex-column site-title-container">
                            <span class="brand-subtext text-white fw-bold" style="font-size: 1.25rem; line-height: 1.1; letter-spacing: 0.5px;">হীরক জয়ন্তী</span>
                            <span class="site-title" style="font-size: 1.15rem; font-weight: 700; line-height: 1.1;">জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</span>
                        </div>
                    </Link>
                    <button class="navbar-toggler" type="button" @click="isMenuOpen = !isMenuOpen">
                        <span class="navbar-toggler-icon"><i class="fas fa-bars text-white"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" :class="{ show: isMenuOpen }" id="diamondNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <Link class="nav-link" href="/">হোম</Link>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link active" href="/essays">স্মৃতিচারণ প্রবন্ধ</Link>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link" href="/friends">বন্ধু তালিকা</Link>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link" href="/#footer">যোগাযোগ</Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- মূল কন্টেন্ট -->
        <main class="container py-5 mt-4">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <!-- ব্যাক বাটন -->
                    <div class="mb-4">
                        <Link :href="route('essays.index')" class="text-decoration-none fw-bold" style="color: var(--diamond-blue);">
                            <i class="fas fa-arrow-left me-2"></i> প্রবন্ধ তালিকায় ফিরে যান
                        </Link>
                    </div>

                    <div class="glass-card overflow-hidden">
                        <!-- প্রবন্ধ হেডার -->
                        <div class="bg-white p-4 p-md-5 border-bottom">
                            <h1 class="fw-bold text-dark mb-4" style="line-height: 1.4; color: #0F4C5C;">{{ essay.title }}</h1>
                            
                            <div class="d-flex flex-wrap align-items-center gap-4 text-muted">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="essay-avatar-large d-flex justify-content-center align-items-center shadow-sm">
                                        <span class="fw-bold text-white fs-5">{{ (essay.author_name || '').split(' ').map(n=>n[0]).join('').slice(0,2) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ essay.author_name }}</div>
                                        <div class="small">ব্যাচ {{ toBengaliDigits(essay.author_batch) }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 small">
                                    <i class="fas fa-calendar-alt text-secondary"></i>
                                    <span>প্রকাশিত: {{ formatDate(essay.created_at) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- প্রবন্ধের মূল অংশ -->
                        <div class="p-4 p-md-5 bg-white essay-content-body">
                            <div style="white-space: pre-line; line-height: 1.9; font-size: 1.15rem; color: #334155;">
                                {{ essay.content }}
                            </div>
                        </div>

                        <!-- প্রবন্ধ ফুটার -->
                        <div class="bg-light p-4 border-top text-center">
                            <p class="text-muted mb-0 fw-semibold">
                                <i class="fas fa-feather-alt gold-text me-2"></i> হীরক জয়ন্তী ২০২৭ - স্মৃতির ক্যানভাস
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- ফুটার -->
        <footer id="footer" class="py-5 bg-dark mt-auto">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-5">
                        <h4 class="fw-bold mb-3"><i class="fas fa-school gold-text me-2"></i> জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</h4>
                        <p class="mb-2 text-white-50">হীরক জয়ন্তী উদযাপন কমিটি, গাংনী, মেহেরপুর।</p>
                        <p class="mb-0 text-white-50">
                            <i class="fas fa-phone-alt gold-text me-2"></i> +৮৮০১৭১৩-৯১৩০৭৬<br />
                            <i class="fas fa-envelope gold-text me-2"></i> age60@myjss.edu.bd
                        </p>
                    </div>
                    <div class="col-md-3">
                        <h5 class="fw-bold mb-3 gold-text">দ্রুত লিংক</h5>
                        <ul class="list-unstyled text-white">
                            <li class="mb-2"><Link href="/#schedule" class="text-white-50 text-decoration-none hover-link">সময়সূচি</Link></li>
                            <li class="mb-2"><Link href="/#committee" class="text-white-50 text-decoration-none hover-link">কমিটি</Link></li>
                            <li class="mb-2"><Link href="/essays" class="text-white-50 text-decoration-none hover-link">স্মৃতিচারণ প্রবন্ধ</Link></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5 class="fw-bold mb-3 gold-text">সোশ্যাল সংযোগ</h5>
                        <div class="d-flex gap-3 mb-3">
                            <a href="#" class="text-white-50 fs-4 hover-social"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="text-white-50 fs-4 hover-social"><i class="fab fa-youtube"></i></a>
                        </div>
                        <p class="small text-white-50 mb-0">#JSSDiamond60 #JorepukuriaReunion</p>
                    </div>
                </div>
                <hr class="bg-secondary my-4" />
                <div class="text-center small text-white-50">
                    © ২০২৭ জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয় হীরক জয়ন্তী উদযাপন | ৬০ বছর, সাফল্যের হীরকখণ্ড
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.essay-avatar-large {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--diamond-blue) 0%, #1B6B82 100%);
    border: 2px solid var(--diamond-gold);
    flex-shrink: 0;
}
.essay-content-body::first-letter {
    font-size: 3rem;
    font-weight: bold;
    color: var(--diamond-gold);
    float: left;
    margin-right: 12px;
    line-height: 1;
}

.hover-link:hover { color: var(--diamond-gold) !important; padding-left: 5px; transition: all 0.2s ease; }
.hover-social { transition: all 0.2s ease; }
.hover-social:hover { color: var(--diamond-gold) !important; transform: scale(1.15); }
</style>
