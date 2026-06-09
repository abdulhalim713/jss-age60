<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import '@hixbe/kalpurush';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../../../css/jss-age60-diamond.css';

const props = defineProps({
    representatives: Array,
    batches: Array,
});

const isMenuOpen = ref(false);
const isDropdownOpen = ref(false);
const logoUrl = '/images/hirak-jayanti-logo.png';
const selectedBatch = ref('');

const filtered = computed(() => {
    if (!selectedBatch.value) return props.representatives;
    return props.representatives.filter(r => r.batch === selectedBatch.value);
});

const toBengaliDigits = (num) => {
    const bengaliDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return String(num).replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

const getImageUrl = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
};

const getInitials = (name) => (name || '').split(' ').map(n => n[0]).join('').slice(0, 2).toUpperCase();

const showAccountsMessage = () => {
    alert('আয়-ব্যয় হিসাব: হীরক জয়ন্তী উদযাপনের আয়-ব্যয় হিসাব কার্যক্রম শেষ হলে এখানে বিস্তারিত প্রকাশ করা হবে। ধন্যবাদ!');
};
</script>

<template>
    <Head>
        <title>ব্যাচ ভিত্তিক প্রতিনিধি | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</title>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        <meta name="description" content="জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের হীরক জয়ন্তী ২০২৭ উপলক্ষে মনোনীত ব্যাচ ভিত্তিক প্রতিনিধিদের তালিকা।" />
        <meta property="og:title" content="ব্যাচ ভিত্তিক প্রতিনিধি | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়" />
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
                            <span class="brand-subtext text-white fw-bold" style="font-size: 1.25rem; line-height: 1.1;">হীরক জয়ন্তী</span>
                            <span class="site-title" style="font-size: 1.15rem; font-weight: 700; line-height: 1.1;">জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</span>
                        </div>
                    </Link>
                    <button class="navbar-toggler" type="button" @click="isMenuOpen = !isMenuOpen">
                        <span class="navbar-toggler-icon"><i class="fas fa-bars text-white"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" :class="{ show: isMenuOpen }" id="diamondNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><Link class="nav-link" href="/">হোম</Link></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" @click.prevent="isDropdownOpen = !isDropdownOpen" :aria-expanded="isDropdownOpen">
                                    হীরক জয়ন্তী
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" :class="{ show: isDropdownOpen }">
                                    <li><Link class="dropdown-item" href="/#about">বিস্তারিত পরিকল্পনা</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">উপদেষ্টা কমিটি</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">উদযাপন কমিটি</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">খাত ভিত্তিক উপকমিটি</Link></li>
                                    <li><Link class="dropdown-item active" href="/batch-representatives">ব্যাচ ভিত্তিক প্রতিনিধি</Link></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" @click.prevent="showAccountsMessage()">আয় ব্যয় হিসাব</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><Link class="nav-link" href="/essays">স্মৃতিচারণ প্রবন্ধ</Link></li>
                            <li class="nav-item"><Link class="nav-link" href="/friends">বন্ধু তালিকা</Link></li>
                            <li class="nav-item"><Link class="nav-link" href="/#footer">যোগাযোগ</Link></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- হিরো ব্যানার -->
        <section class="hero-diamond p-0 overflow-hidden" style="min-height: 250px; padding: 60px 0 80px !important;">
            <div class="container text-center text-white" style="margin-top: 2rem;">
                <div class="d-inline-block diamond-badge rounded-pill px-4 py-2 mb-3">
                    <i class="fas fa-user-shield gold-text me-2"></i> প্রতিনিধি পরিচিতি
                </div>
                <h1 class="display-5 fw-bold text-white">
                    <span class="gold-text">ব্যাচ ভিত্তিক</span> প্রতিনিধি
                </h1>
                <p class="lead mx-auto mt-2" style="max-width: 700px; font-size: 1.1rem; opacity: 0.9;">
                    জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের হীরক জয়ন্তী উদযাপনে বিভিন্ন ব্যাচের মনোনীত প্রতিনিধিদের তালিকা
                </p>
            </div>
        </section>

        <!-- মূল কন্টেন্ট -->
        <main class="container my-5">
            <!-- ফিল্টার প্যানেল -->
            <div class="glass-card mb-5">
                <div class="card-header-diamond">
                    <i class="fas fa-filter me-2"></i> ব্যাচ অনুযায়ী ফিল্টার করুন
                </div>
                <div class="p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-5">
                            <select v-model="selectedBatch" class="form-select py-3 rounded-pill">
                                <option value="">সকল ব্যাচ দেখুন</option>
                                <option v-for="b in batches" :key="b" :value="b">ব্যাচ {{ b }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <button class="btn py-3 px-4 rounded-pill btn-reset-custom w-100" @click="selectedBatch=''">
                                <i class="fas fa-sync-alt me-2"></i> রিসেট
                            </button>
                        </div>
                        <div class="col-12 col-md-4 text-end">
                            <div class="badge px-3 py-2 rounded-pill fs-6" style="background-color: var(--diamond-blue); border: 1px solid var(--diamond-gold); color: white;">
                                মোট: {{ toBengaliDigits(filtered.length) }} জন প্রতিনিধি
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- প্রতিনিধিদের তালিকা -->
            <div v-if="filtered.length === 0" class="glass-card p-5 text-center">
                <i class="fas fa-user-shield fs-1 mb-3 gold-text"></i>
                <h4 class="fw-bold text-muted">কোনো প্রতিনিধি পাওয়া যায়নি</h4>
                <p class="text-muted mb-0">অনুগ্রহ করে ভিন্ন ব্যাচ নির্বাচন করুন।</p>
            </div>
            <div v-else>
                <!-- Header row -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold gold-text mb-0">
                        <i class="fas fa-users me-2"></i>
                        {{ selectedBatch ? `ব্যাচ ${selectedBatch} প্রতিনিধি` : 'সকল ব্যাচের প্রতিনিধি' }}
                    </h4>
                </div>

                <!-- Representatives Table -->
                <div class="rep-table-wrapper glass-card p-0 overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table class="table rep-table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:70px;"><i class="fas fa-hashtag me-1"></i> ক্রম</th>
                                    <th style="width:80px;">ছবি</th>
                                    <th><i class="fas fa-user me-1"></i> নাম</th>
                                    <th><i class="fas fa-graduation-cap me-1"></i> ব্যাচ</th>
                                    <th><i class="fas fa-phone me-1"></i> মোবাইল</th>
                                    <th><i class="fas fa-map-marker-alt me-1"></i> ঠিকানা</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(rep, index) in filtered" :key="rep.id" class="rep-row">
                                    <td class="text-center fw-semibold serial-cell">
                                        {{ toBengaliDigits(index + 1) }}
                                    </td>
                                    <td>
                                        <img
                                            v-if="rep.image"
                                            :src="getImageUrl(rep.image)"
                                            class="rep-avatar-img rounded-circle border"
                                            style="width:42px;height:42px;object-fit:cover;border:2px solid var(--diamond-gold) !important;"
                                            :alt="rep.name"
                                        />
                                        <div v-else class="rep-avatar-sm d-flex justify-content-center align-items-center shadow-sm">
                                            <span class="fw-bold text-white">{{ getInitials(rep.name) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="rep-name-table fw-semibold">{{ rep.name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-batch px-3 py-2 rounded-pill fw-semibold">
                                            ব্যাচ {{ rep.batch }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ rep.mobile || '—' }}</td>
                                    <td class="address-cell">
                                        <span v-if="rep.address">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>{{ rep.address }}
                                        </span>
                                        <span v-else class="text-muted fst-italic">ঠিকানা উল্লেখ নেই</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                            <li class="mb-2"><Link href="/batch-representatives" class="text-white-50 text-decoration-none hover-link">ব্যাচ প্রতিনিধি</Link></li>
                            <li class="mb-2"><Link href="/friends" class="text-white-50 text-decoration-none hover-link">বন্ধু তালিকা</Link></li>
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
    content: "🛡️";
    position: absolute;
    bottom: 10px;
    right: 20px;
    font-size: 60px;
    opacity: 0.08;
}

/* Table */
.rep-table-wrapper {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #E9EEF3;
    box-shadow: 0 10px 30px rgba(15, 76, 92, 0.05);
}
.rep-table th {
    background-color: var(--diamond-blue) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: 18px 24px;
    border: none;
    font-size: 0.95rem;
}
.rep-table th:first-child { border-top-left-radius: 20px; }
.rep-table th:last-child  { border-top-right-radius: 20px; }
.rep-table td {
    padding: 14px 20px;
    vertical-align: middle;
    color: #334155;
    border-bottom: 1px solid #F1F5F9;
    font-size: 0.93rem;
}
.rep-row { transition: background-color 0.2s ease; }
.rep-row:hover { background-color: rgba(15, 76, 92, 0.03); }
.rep-row:last-child td { border-bottom: none; }

.rep-avatar-sm {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--diamond-blue) 0%, #1B6B82 100%);
    border: 2px solid var(--diamond-gold);
    color: #ffffff;
    font-size: 0.85rem;
}
.rep-name-table {
    font-family: 'Kalpurush', 'Hind Siliguri', sans-serif;
    color: #0F4C5C;
}
.serial-cell { color: #64748B !important; }
.address-cell { color: #475569; }
.badge-batch {
    background-color: rgba(212, 175, 55, 0.12) !important;
    color: #9A7B1C !important;
    border: 1px solid rgba(212, 175, 55, 0.25);
    font-size: 0.82rem;
}

/* Filter select */
.form-select {
    border-radius: 50px !important;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.form-select:focus {
    border-color: var(--diamond-blue);
    box-shadow: 0 0 0 0.25rem rgba(15, 76, 92, 0.1);
}

.btn-reset-custom {
    border-radius: 50px !important;
    font-weight: 500;
    border: 1px solid #dee2e6;
    transition: all 0.2s ease;
}
.btn-reset-custom:hover {
    background-color: var(--diamond-blue);
    border-color: var(--diamond-blue);
    color: #fff;
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
.navbar-diamond .dropdown-item:hover,
.navbar-diamond .dropdown-item.active { color: #D4AF37 !important; background-color: rgba(255, 255, 255, 0.1) !important; }

@media (max-width: 768px) {
    .hero-diamond { padding: 40px 0 50px !important; }
}
</style>
