<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import '@hixbe/kalpurush';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../../../css/jss-age60-diamond.css';

const props = defineProps({
    essays: Object,
    filters: Object
});

const search = ref(props.filters.search || '');
const isMenuOpen = ref(false);
const isDropdownOpen = ref(false);
const logoUrl = '/images/hirak-jayanti-logo.png';

const toBengaliDigits = (num) => {
    const bengaliDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return String(num).replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

let debounceTimer = null;
const applyFilters = () => {
    router.get(route('essays.index'), {
        search: search.value
    }, {
        preserveState: true,
        replace: true,
        preserveScroll: true
    });
};

watch(search, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => applyFilters(), 350);
});

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('bn-BD', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const truncate = (text, len = 180) => {
    if (!text) return '';
    const stripped = text.replace(/\n/g, ' ');
    return stripped.length > len ? stripped.substring(0, len) + '...' : stripped;
};

const showAccountsMessage = () => {
    alert('আয়-ব্যয় হিসাব: হীরক জয়ন্তী উদযাপনের আয়-ব্যয় হিসাব কার্যক্রম শেষ হলে এখানে বিস্তারিত প্রকাশ করা হবে। ধন্যবাদ!');
};
</script>

<template>
    <Head>
        <title>স্মৃতিচারণ প্রবন্ধ | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</title>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        <meta name="description" content="জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের প্রাক্তন শিক্ষার্থীদের স্মৃতিচারণমূলক প্রবন্ধ ও রচনা সমূহ।" />
        <meta property="og:title" content="স্মৃতিচারণ প্রবন্ধ | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়" />
        <meta property="og:description" content="জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের প্রাক্তন শিক্ষার্থীদের স্মৃতিচারণমূলক প্রবন্ধ ও রচনা সমূহ।" />
        <meta property="og:image" content="/images/hirak-jayanti-logo.png" />
    </Head>

    <div id="app" class="jss-age60-root">
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
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="diamondDropdown" role="button" @click.prevent="isDropdownOpen = !isDropdownOpen" :aria-expanded="isDropdownOpen ? 'true' : 'false'">
                                    হীরক জয়ন্তী
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" :class="{ show: isDropdownOpen }" aria-labelledby="diamondDropdown">
                                    <li><Link class="dropdown-item" href="/#about">বিস্তারিত পরিকল্পনা</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">উপদেষ্টা কমিটি</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">উদযাপন কমিটি</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">খাতভিত্তিক উপ কমিটি</Link></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" @click.prevent="showAccountsMessage()">আয় ব্যয় হিসাব</a></li>
                                </ul>
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

        <!-- হিরো ব্যানার -->
        <section class="hero-diamond p-0 overflow-hidden" style="min-height: 250px; padding: 60px 0 80px !important;">
            <div class="container text-center text-white" style="margin-top: 2rem;">
                <div class="d-inline-block diamond-badge rounded-pill px-4 py-2 mb-3">
                    <i class="fas fa-feather-alt gold-text me-2"></i> স্মৃতির পাতা
                </div>
                <h1 class="display-5 fw-bold text-white">
                    <span class="gold-text">স্মৃতিচারণ</span> প্রবন্ধ
                </h1>
                <p class="lead mx-auto mt-2" style="max-width: 700px; font-size: 1.1rem; opacity: 0.9;">
                    জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের প্রাক্তন শিক্ষার্থীদের মধুর স্মৃতি ও প্রবন্ধ সমূহ
                </p>
                <div class="mt-4">
                    <Link :href="route('essays.create')" class="btn btn-diamond btn-lg">
                        <i class="fas fa-pen-fancy me-2"></i> স্মৃতিচারণ প্রবন্ধ লিখুন
                    </Link>
                </div>
            </div>
        </section>

        <!-- মূল কন্টেন্ট -->
        <main class="container my-5">
            <!-- সফলতা বার্তা -->
            <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ $page.props.flash.success }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- সার্চ প্যানেল -->
            <div class="glass-card mb-5">
                <div class="card-header-diamond">
                    <i class="fas fa-search me-2"></i> প্রবন্ধ অনুসন্ধান করুন
                </div>
                <div class="p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-8">
                            <div class="input-group search-input-group">
                                <span class="input-group-text border-end-0 text-muted bg-white">
                                    <i class="fas fa-search text-primary"></i>
                                </span>
                                <input v-model="search" type="search" class="form-control border-start-0 py-3 ps-1" placeholder="শিরোনাম, লেখক অথবা বিষয়বস্তু দিয়ে খুঁজুন..." />
                            </div>
                        </div>
                        <div class="col-12 col-md-4 d-grid">
                            <Link :href="route('essays.create')" class="btn btn-diamond py-3">
                                <i class="fas fa-pen-fancy me-2"></i> নতুন প্রবন্ধ লিখুন
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- প্রবন্ধ তালিকা -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold gold-text mb-0"><i class="fas fa-book-open me-2"></i> প্রকাশিত প্রবন্ধ</h4>
                <div class="badge bg-primary text-white px-3 py-2 rounded-pill fs-6" style="background-color: var(--diamond-blue) !important; border: 1px solid var(--diamond-gold);">
                    মোট: {{ toBengaliDigits(essays.total) }}টি
                </div>
            </div>

            <div v-if="essays.data.length > 0" class="row g-4">
                <div v-for="essay in essays.data" :key="essay.id" class="col-md-6 col-lg-4">
                    <Link :href="route('essays.show', essay.id)" class="text-decoration-none">
                        <div class="essay-card glass-card p-0 overflow-hidden h-100">
                            <div class="essay-card-accent"></div>
                            <div class="p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="essay-avatar d-flex justify-content-center align-items-center shadow-sm">
                                        <span class="fw-bold text-white" style="font-size: 0.85rem;">{{ (essay.author_name || '').split(' ').map(n=>n[0]).join('').slice(0,2) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ essay.author_name }}</div>
                                        <small class="text-muted">ব্যাচ {{ toBengaliDigits(essay.author_batch) }}</small>
                                    </div>
                                </div>
                                <h5 class="fw-bold essay-title mb-2">{{ essay.title }}</h5>
                                <p class="text-muted mb-3" style="font-size: 0.9rem; line-height: 1.7;">
                                    {{ truncate(essay.content) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i> {{ formatDate(essay.created_at) }}
                                    </small>
                                    <span class="read-more-link">
                                        পড়ুন <i class="fas fa-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- খালি অবস্থা -->
            <div v-else class="glass-card p-5 text-center">
                <i class="fas fa-book-open fs-1 mb-3 gold-text"></i>
                <h4 class="fw-bold text-muted">কোনো প্রকাশিত প্রবন্ধ পাওয়া যায়নি</h4>
                <p class="text-muted mb-4">আপনিই প্রথম স্মৃতিচারণ প্রবন্ধটি লিখুন!</p>
                <Link :href="route('essays.create')" class="btn btn-diamond px-5">
                    <i class="fas fa-pen-fancy me-2"></i> প্রবন্ধ লিখুন
                </Link>
            </div>

            <!-- পেজিনেশন -->
            <div v-if="essays.data.length > 0" class="d-flex justify-content-between align-items-center mt-5">
                <div class="small text-muted fw-semibold">পৃষ্ঠা: {{ toBengaliDigits(essays.current_page) }} / {{ toBengaliDigits(essays.last_page) }}</div>
                <nav v-if="essays.links.length > 3">
                    <ul class="pagination mb-0">
                        <li v-for="(link, k) in essays.links" :key="k" class="page-item" :class="{ 'active': link.active, 'disabled': !link.url }">
                            <Link v-if="link.url" :href="link.url" class="page-link py-2 px-3" v-html="link.label" preserve-scroll />
                            <span v-else class="page-link py-2 px-3" v-html="link.label"></span>
                        </li>
                    </ul>
                </nav>
            </div>
        </main>

        <!-- ফুটার -->
        <footer id="footer" class="py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-5">
                        <h4 class="fw-bold mb-3"><i class="fas fa-school gold-text me-2"></i> জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</h4>
                        <p class="mb-2">হীরক জয়ন্তী উদযাপন কমিটি, গাংনী, মেহেরপুর।</p>
                        <p class="mb-0 text-white-50">
                            <i class="fas fa-phone-alt gold-text me-2"></i> +৮৮০১৭১৩-৯১৩০৭৬, +৮৮০১৭৫৮-৮৯৩৪৩২<br />
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
                            <a href="#" class="text-white-50 fs-4 hover-social"><i class="fab fa-instagram"></i></a>
                        </div>
                        <p class="small text-white-50 mb-0">#JSSDiamond60 #JorepukuriaReunion #JSS #JSSAGE60</p>
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
.hero-diamond {
    background: linear-gradient(135deg, #0F4C5C 0%, #1B6B82 100%);
    position: relative;
}
.hero-diamond::before {
    content: "✍️";
    position: absolute;
    bottom: 10px;
    right: 20px;
    font-size: 60px;
    opacity: 0.08;
}

.search-input-group {
    border-radius: 50px;
    overflow: hidden;
    border: 1px solid #dee2e6;
    background-color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.search-input-group .form-control { border: none; outline: none; box-shadow: none; }
.search-input-group .input-group-text { border: none; }

.essay-card {
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}
.essay-card:hover {
    transform: translateY(-8px) !important;
    box-shadow: 0 20px 40px rgba(15, 76, 92, 0.15) !important;
}
.essay-card-accent {
    height: 5px;
    background: linear-gradient(90deg, var(--diamond-blue), var(--diamond-gold));
}
.essay-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--diamond-blue) 0%, #1B6B82 100%);
    border: 2px solid var(--diamond-gold);
    flex-shrink: 0;
}
.essay-title {
    color: #0F4C5C;
    font-size: 1.1rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.read-more-link {
    color: var(--diamond-gold);
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}
.essay-card:hover .read-more-link {
    color: var(--diamond-blue);
}

.pagination { gap: 5px; }
.pagination .page-link {
    color: var(--diamond-blue);
    background-color: #fff;
    border-radius: 8px !important;
    border: 1px solid #E2E8F0;
    font-weight: 500;
    transition: all 0.2s ease;
}
.pagination .page-item.active .page-link {
    background-color: var(--diamond-blue);
    border-color: var(--diamond-blue);
    color: #fff !important;
}
.pagination .page-link:hover {
    background-color: var(--diamond-gold);
    color: #0F4C5C !important;
    border-color: var(--diamond-gold);
}

.hover-link:hover { color: var(--diamond-gold) !important; padding-left: 5px; transition: all 0.2s ease; }
.hover-social { transition: all 0.2s ease; }
.hover-social:hover { color: var(--diamond-gold) !important; transform: scale(1.15); }

.navbar-diamond .dropdown-menu {
    border: 1px solid rgba(212, 175, 55, 0.2) !important;
    background-color: rgba(15, 76, 92, 0.95) !important;
    backdrop-filter: blur(10px);
}
.navbar-diamond .dropdown-item { color: rgba(255, 255, 255, 0.8) !important; transition: all 0.2s ease; }
.navbar-diamond .dropdown-item:hover { color: #D4AF37 !important; background-color: rgba(255, 255, 255, 0.1) !important; padding-left: 1.25rem; }

@media (max-width: 768px) {
    .hero-diamond { padding: 40px 0 50px !important; }
}
</style>
