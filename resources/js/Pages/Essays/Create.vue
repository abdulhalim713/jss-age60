<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import '@hixbe/kalpurush';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../../../css/jss-age60-diamond.css';

const isMenuOpen = ref(false);
const isDropdownOpen = ref(false);
const logoUrl = '/images/hirak-jayanti-logo.png';

const form = ref({
    title: '',
    author_name: '',
    author_batch: '',
    author_phone: '',
    author_email: '',
    content: ''
});

const isSubmitting = ref(false);
const errors = ref({});

const toBengaliDigits = (num) => {
    const bengaliDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return String(num).replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

// ব্যাচ বছরের তালিকা (১৯৭০ - ২০২৭)
const batchYears = [];
for (let y = 1970; y <= 2027; y++) {
    batchYears.push({ value: toBengaliDigits(y), label: toBengaliDigits(y) });
}

const submitEssay = () => {
    if (isSubmitting.value) return;
    isSubmitting.value = true;
    errors.value = {};

    router.post(route('essays.store'), form.value, {
        preserveScroll: true,
        onError: (errs) => {
            errors.value = errs;
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

const showAccountsMessage = () => {
    alert('আয়-ব্যয় হিসাব: হীরক জয়ন্তী উদযাপনের আয়-ব্যয় হিসাব কার্যক্রম শেষ হলে এখানে বিস্তারিত প্রকাশ করা হবে। ধন্যবাদ!');
};
</script>

<template>
    <Head>
        <title>স্মৃতিচারণ প্রবন্ধ লিখুন | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</title>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    </Head>

    <div id="app" class="jss-age60-root">
        <header class="site-header">
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
                                <a class="nav-link dropdown-toggle" href="#" @click.prevent="isDropdownOpen = !isDropdownOpen">হীরক জয়ন্তী</a>
                                <ul class="dropdown-menu dropdown-menu-dark" :class="{ show: isDropdownOpen }">
                                    <li><Link class="dropdown-item" href="/#about">বিস্তারিত পরিকল্পনা</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">কমিটি</Link></li>
                                    <li><Link class="dropdown-item" href="/batch-representatives">ব্যাচ ভিত্তিক প্রতিনিধি</Link></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" @click.prevent="showAccountsMessage()">আয় ব্যয় হিসাব</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><Link class="nav-link active" href="/essays">স্মৃতিচারণ প্রবন্ধ</Link></li>
                            <li class="nav-item"><Link class="nav-link" href="/friends">বন্ধু তালিকা</Link></li>
                            <li class="nav-item"><Link class="nav-link" href="/#footer">যোগাযোগ</Link></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- হিরো ব্যানার -->
        <section class="hero-diamond p-0 overflow-hidden" style="min-height: 220px; padding: 50px 0 70px !important;">
            <div class="container text-center text-white" style="margin-top: 2rem;">
                <div class="d-inline-block diamond-badge rounded-pill px-4 py-2 mb-3">
                    <i class="fas fa-pen-fancy gold-text me-2"></i> লেখনী
                </div>
                <h1 class="display-5 fw-bold text-white">
                    <span class="gold-text">স্মৃতিচারণ</span> প্রবন্ধ লিখুন
                </h1>
                <p class="lead mx-auto mt-2" style="max-width: 650px; font-size: 1.05rem; opacity: 0.9;">
                    বিদ্যালয়ের সাথে আপনার মধুর স্মৃতি, অনুভূতি ও কৃতজ্ঞতা প্রকাশ করুন লেখনীর মাধ্যমে
                </p>
            </div>
        </section>

        <!-- ফর্ম -->
        <main class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="glass-card overflow-hidden">
                        <div class="card-header-diamond">
                            <i class="fas fa-feather-alt me-2"></i> প্রবন্ধের তথ্য পূরণ করুন
                        </div>
                        <div class="p-4 p-md-5">
                            <form @submit.prevent="submitEssay">
                                <!-- শিরোনাম -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark">প্রবন্ধের শিরোনাম <span class="text-danger">*</span></label>
                                    <input v-model="form.title" type="text" class="form-control form-control-lg rounded-pill px-4" placeholder="যেমন: সেই সোনালী দিনগুলো" required />
                                    <div v-if="errors.title" class="text-danger small mt-1">{{ errors.title }}</div>
                                </div>

                                <!-- লেখক তথ্য -->
                                <div class="p-4 bg-light rounded-4 mb-4">
                                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-user gold-text me-2"></i> লেখকের তথ্য</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">পূর্ণ নাম <span class="text-danger">*</span></label>
                                            <input v-model="form.author_name" type="text" class="form-control rounded-pill px-3" placeholder="আপনার পূর্ণ নাম" required />
                                            <div v-if="errors.author_name" class="text-danger small mt-1">{{ errors.author_name }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">ব্যাচ <span class="text-danger">*</span></label>
                                            <select v-model="form.author_batch" class="form-control form-select-pill px-3" required>
                                                <option value="" disabled>ব্যাচ নির্বাচন করুন</option>
                                                <option v-for="yr in batchYears" :key="yr.value" :value="yr.value">{{ yr.label }}</option>
                                            </select>
                                            <div v-if="errors.author_batch" class="text-danger small mt-1">{{ errors.author_batch }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                            <input v-model="form.author_phone" type="tel" class="form-control rounded-pill px-3" placeholder="০১XXXXXXXXX" required />
                                            <div v-if="errors.author_phone" class="text-danger small mt-1">{{ errors.author_phone }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">ইমেইল (ঐচ্ছিক)</label>
                                            <input v-model="form.author_email" type="email" class="form-control rounded-pill px-3" placeholder="example@email.com" />
                                            <div v-if="errors.author_email" class="text-danger small mt-1">{{ errors.author_email }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- প্রবন্ধ -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark">আপনার প্রবন্ধ লিখুন <span class="text-danger">*</span></label>
                                    <textarea v-model="form.content" class="form-control rounded-4 px-4 py-3 essay-textarea" rows="15" placeholder="এখানে আপনার স্মৃতিচারণ প্রবন্ধ লিখুন... &#10;&#10;বিদ্যালয়ের সাথে আপনার স্মৃতি, শিক্ষক-শিক্ষিকার কথা, বন্ধুদের সাথে কাটানো সময়, বিদ্যালয়ের প্রতি আপনার ভালোবাসা — সবকিছু লিখুন।" required></textarea>
                                    <div v-if="errors.content" class="text-danger small mt-1">{{ errors.content }}</div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">সর্বনিম্ন ৫০ অক্ষর, সর্বোচ্চ ৫০,০০০ অক্ষর</small>
                                        <small class="text-muted fw-semibold">{{ toBengaliDigits(form.content.length) }} অক্ষর</small>
                                    </div>
                                </div>

                                <!-- তথ্য বার্তা -->
                                <div class="alert alert-info border-0 rounded-4 mb-4" style="background: rgba(15, 76, 92, 0.06);">
                                    <i class="fas fa-info-circle me-2 gold-text"></i>
                                    <small>আপনার প্রবন্ধটি এডমিন অনুমোদনের পর ওয়েবসাইটে প্রকাশিত হবে। প্রকাশিত হলে আপনার নাম ও ব্যাচ প্রদর্শিত হবে।</small>
                                </div>

                                <!-- সাবমিট -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-diamond btn-lg px-5" :disabled="isSubmitting">
                                        <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        <i v-else class="fas fa-paper-plane me-2"></i>
                                        প্রবন্ধ জমা দিন
                                    </button>
                                    <div class="mt-3">
                                        <Link :href="route('essays.index')" class="text-muted text-decoration-none">
                                            <i class="fas fa-arrow-left me-1"></i> প্রবন্ধ তালিকায় ফিরে যান
                                        </Link>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- ফুটার -->
        <footer id="footer" class="py-5">
            <div class="container">
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

.essay-textarea {
    font-size: 1.05rem;
    line-height: 1.9;
    font-family: 'Kalpurush', 'Hind Siliguri', sans-serif;
    border: 2px solid #E2E8F0;
    transition: border-color 0.2s ease;
    resize: vertical;
}
.essay-textarea:focus {
    border-color: var(--diamond-gold);
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.navbar-diamond .dropdown-menu {
    border: 1px solid rgba(212, 175, 55, 0.2) !important;
    background-color: rgba(15, 76, 92, 0.95) !important;
    backdrop-filter: blur(10px);
}
.navbar-diamond .dropdown-item { color: rgba(255, 255, 255, 0.8) !important; transition: all 0.2s ease; }
.navbar-diamond .dropdown-item:hover { color: #D4AF37 !important; background-color: rgba(255, 255, 255, 0.1) !important; }

@media (max-width: 768px) {
    .hero-diamond { padding: 40px 0 50px !important; }
}
</style>
