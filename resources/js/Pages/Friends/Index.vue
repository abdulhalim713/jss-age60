<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import '@hixbe/kalpurush';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/css/all.min.css';
import '../../../css/jss-age60-diamond.css';

const props = defineProps({
    alumni: Object,
    filters: Object,
    batches: Array
});

const search = ref(props.filters.search || '');
const batch = ref(props.filters.batch || '');
const isMenuOpen = ref(false);
const isDropdownOpen = ref(false);
const logoUrl = '/images/hirak-jayanti-logo.png';

const feedbackForm = ref({ name: '', contact: '', type: 'suggestion', message: '' });
const feedbackSuccess = ref('');

// বাংলায় সংখ্যা রূপান্তর
const toBengaliDigits = (num) => {
    const bengaliDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return String(num).replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

let debounceTimer = null;
const applyFilters = () => {
    router.get(route('friends.index'), {
        search: search.value,
        batch: batch.value
    }, {
        preserveState: true,
        replace: true,
        preserveScroll: true
    });
};

watch([search, batch], () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => applyFilters(), 350);
});

const isSubmittingFeedback = ref(false);
const submitFeedback = () => {
    if (isSubmittingFeedback.value) return;
    isSubmittingFeedback.value = true;
    router.post(route('feedback.store'), feedbackForm.value, {
        preserveScroll: true,
        onSuccess: () => {
            feedbackSuccess.value = `<i class="fas fa-check-circle"></i> আপনার মতামত/অভিযোগ সফলভাবে গ্রহণ করা হয়েছে। ধন্যবাদ!`;
            setTimeout(() => {
                feedbackSuccess.value = '';
                const modalEl = document.getElementById('feedbackModal');
                if (modalEl) {
                    const modal = window.bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }
                feedbackForm.value = { name: '', contact: '', type: 'suggestion', message: '' };
            }, 2500);
        },
        onError: (errors) => {
            const errorMsgs = Object.values(errors).join('\n');
            alert('ভুল হয়েছে:\n' + errorMsgs);
        },
        onFinish: () => {
            isSubmittingFeedback.value = false;
        }
    });
};

const showAccountsMessage = () => {
    alert('আয়-ব্যয় হিসাব: হীরক জয়ন্তী উদযাপনের আয়-ব্যয় হিসাব কার্যক্রম শেষ হলে এখানে বিস্তারিত প্রকাশ করা হবে। ধন্যবাদ!');
};
</script>

<template>
    <Head>
        <title>বন্ধু তালিকা | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</title>
        <link
            href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        <meta name="description" content="জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের হীরক জয়ন্তী ২০২৭ উপলক্ষে নিবন্ধিত প্রাক্তন বন্ধুদের তালিকা ও অনুসন্ধান প্রাঙ্গণ।" />
        <meta property="og:title" content="বন্ধু তালিকা | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়" />
        <meta property="og:description" content="জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের হীরক জয়ন্তী ২০২৭ উপলক্ষে নিবন্ধিত প্রাক্তন বন্ধুদের তালিকা ও অনুসন্ধান প্রাঙ্গণ।" />
        <meta property="og:image" content="/images/hirak-jayanti-logo.png" />
        <meta property="og:type" content="website" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="বন্ধু তালিকা | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়" />
        <meta name="twitter:description" content="জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের হীরক জয়ন্তী ২০২৭ উপলক্ষে নিবন্ধিত প্রাক্তন বন্ধুদের তালিকা ও অনুসন্ধান প্রাঙ্গণ।" />
        <meta name="twitter:image" content="/images/hirak-jayanti-logo.png" />
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
                    <Link
                        class="navbar-brand d-flex align-items-center gap-2 text-decoration-none"
                        href="/"
                    >
                        <img :src="logoUrl" alt="হীরক জয়ন্তী" class="site-logo" />
                        <div class="d-flex flex-column site-title-container">
                            <span class="brand-subtext text-white fw-bold" style="font-size: 1.25rem; line-height: 1.1; letter-spacing: 0.5px;">হীরক জয়ন্তী</span>
                            <span class="site-title" style="font-size: 1.15rem; font-weight: 700; line-height: 1.1;">জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</span>
                        </div>
                    </Link>
                    <button
                        class="navbar-toggler"
                        type="button"
                        @click="isMenuOpen = !isMenuOpen"
                    >
                        <span class="navbar-toggler-icon"><i class="fas fa-bars text-white"></i></span>
                    </button>
                    <div class="collapse navbar-collapse" :class="{ show: isMenuOpen }" id="diamondNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <Link class="nav-link" href="/#home">হোম</Link>
                            </li>
                            <li class="nav-item dropdown">
                                <a
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    id="diamondDropdown"
                                    role="button"
                                    @click.prevent="isDropdownOpen = !isDropdownOpen"
                                    :aria-expanded="isDropdownOpen ? 'true' : 'false'"
                                >
                                    হীরক জয়ন্তী
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" :class="{ show: isDropdownOpen }" aria-labelledby="diamondDropdown">
                                    <li><Link class="dropdown-item" href="/#about">বিস্তারিত পরিকল্পনা</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">উপদেষ্টা কমিটি</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">উদযাপন কমিটি</Link></li>
                                    <li><Link class="dropdown-item" href="/#committee">খাতভিত্তিক উপ কমিটি</Link></li>
                                    <li><Link class="dropdown-item" href="/batch-representatives">ব্যাচ ভিত্তিক প্রতিনিধি</Link></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" @click.prevent="showAccountsMessage()">আয় ব্যয় হিসাব</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link" href="/#register">স্মৃতিচারণ প্রবন্ধ</Link>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link active" href="/friends">বন্ধু তালিকা</Link>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#feedbackModal" @click="isMenuOpen = false">অভিযোগ/পরামর্শ</a>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link" href="/#footer">যোগাযোগ</Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- হিরো / ব্যানার সেকশন -->
        <section class="hero-diamond p-0 overflow-hidden" style="min-height: 250px; padding: 60px 0 80px !important;">
            <div class="container text-center text-white" style="margin-top: 2rem;">
                <div class="d-inline-block diamond-badge rounded-pill px-4 py-2 mb-3">
                    <i class="fas fa-gem gold-text me-2"></i> বন্ধু প্রাঙ্গণ
                </div>
                <h1 class="display-5 fw-bold text-white">
                    <span class="gold-text">নিবন্ধিত বন্ধুদের</span> তালিকা
                </h1>
                <p class="lead mx-auto mt-2" style="max-width: 700px; font-size: 1.1rem; opacity: 0.9;">
                    জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের হীরক জয়ন্তী উপলক্ষে নিবন্ধিত বন্ধুদের তথ্য ও সন্ধান
                </p>
            </div>
        </section>

        <!-- মূল কন্টেন্ট -->
        <main class="container my-5">
            <!-- সার্চ ও ফিল্টার প্যানেল (গ্লাস কার্ড) -->
            <div class="glass-card mb-5">
                <div class="card-header-diamond">
                    <i class="fas fa-search me-2"></i> বন্ধুদের খুঁজুন ও ফিল্টার করুন
                </div>
                <div class="p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-6">
                            <div class="input-group search-input-group">
                                <span class="input-group-text border-end-0 text-muted bg-white">
                                    <i class="fas fa-search text-primary"></i>
                                </span>
                                <input v-model="search" type="search" class="form-control border-start-0 py-3 ps-1" placeholder="নাম, মোবাইল অথবা ইমেইল দিয়ে খুঁজুন..." />
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <select v-model="batch" class="form-select py-3">
                                <option value="">সকল ব্যাচ</option>
                                <option v-for="b in batches" :key="b" :value="b">ব্যাচ {{ toBengaliDigits(b) }}</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3 d-grid">
                            <button class="btn btn-outline-secondary py-3 btn-reset-custom" @click="search=''; batch=''">
                                <i class="fas fa-sync-alt me-2"></i> রিসেট ফিল্টার
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- বন্ধুদের তালিকা -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold gold-text mb-0"><i class="fas fa-users me-2"></i> সকল বন্ধু</h4>
                <div class="badge bg-primary text-white px-3 py-2 rounded-pill fs-6" style="background-color: var(--diamond-blue) !important; border: 1px solid var(--diamond-gold);">
                    মোট: {{ toBengaliDigits(alumni.total) }} জন
                </div>
            </div>

            <!-- টেবিল -->
            <div class="friends-table-wrapper glass-card p-0 overflow-hidden mb-4">
                <div class="table-responsive">
                    <table class="table friends-table mb-0">
                        <thead>
                            <tr>
                                <th class="col-serial text-center"><i class="fas fa-hashtag me-1"></i> ক্রমিক</th>
                                <th><i class="fas fa-user me-1"></i> নাম</th>
                                <th><i class="fas fa-graduation-cap me-1"></i> ব্যাচ নাম</th>
                                <th><i class="fas fa-map-marker-alt me-1"></i> ঠিকানা</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="alumni.data.length > 0">
                                <tr v-for="(alumnus, index) in alumni.data" :key="alumnus.id" class="friend-row">
                                    <td class="text-center fw-semibold serial-cell">
                                        {{ toBengaliDigits((alumni.current_page - 1) * alumni.per_page + index + 1) }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="friend-avatar-sm d-flex justify-content-center align-items-center shadow-sm">
                                                <span class="fw-bold text-white">{{ (alumnus.name || '').split(' ').map(n=>n[0]).join('').slice(0,2) }}</span>
                                            </div>
                                            <span class="friend-name-table fw-semibold">{{ alumnus.name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-batch px-3 py-2 rounded-pill fw-semibold">
                                            ব্যাচ {{ toBengaliDigits(alumnus.batch || '—') }}
                                        </span>
                                    </td>
                                    <td class="address-cell">
                                        <span v-if="alumnus.address">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>{{ alumnus.address }}
                                        </span>
                                        <span v-else class="text-muted fst-italic">ঠিকানা উল্লেখ নেই</span>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="4" class="text-center py-5">
                                    <div class="p-5">
                                        <i class="fas fa-search-minus fs-1 mb-3 text-muted"></i>
                                        <h4 class="fw-bold text-muted">কোনো বন্ধু পাওয়া যায়নি</h4>
                                        <p class="text-muted mb-0">অনুগ্রহ করে ভিন্ন নাম বা ব্যাচ দিয়ে পুনরায় অনুসন্ধান করুন।</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- পেজিনেশন -->
            <div class="d-flex justify-content-between align-items-center mt-5">
                <div class="small text-muted fw-semibold">পৃষ্ঠা: {{ toBengaliDigits(alumni.current_page) }} / {{ toBengaliDigits(alumni.last_page) }}</div>
                <nav v-if="alumni.links.length > 3">
                    <ul class="pagination mb-0">
                        <li v-for="(link, k) in alumni.links" :key="k" class="page-item" :class="{ 'active': link.active, 'disabled': !link.url }">
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
                        <h4 class="fw-bold mb-3"><i class="fas fa-school gold-text me-2"></i> জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</h4>
                        <p class="mb-2">
                            হীরক জয়ন্তী উদযাপন কমিটি, গাংনী, মেহেরপুর।
                        </p>
                        <p class="mb-0 text-white-50">
                            <i class="fas fa-phone-alt gold-text me-2"></i> +৮৮০১৭১৩-৯১৩০৭৬, +৮৮০১৭৫৮-৮৯৩৪৩২<br />
                            <i class="fas fa-envelope gold-text me-2"></i> age60@myjss.edu.bd
                        </p>
                    </div>
                    <div class="col-md-3">
                        <h5 class="fw-bold mb-3 gold-text">দ্রুত লিংক</h5>
                        <ul class="list-unstyled text-white">
                            <li class="mb-2"><Link href="/#schedule" class="text-white-50 text-decoration-none hover-link">সময়সূচি</Link></li>
                            <li class="mb-2"><Link href="/#committee" class="text-white-50 text-decoration-none hover-link">কমিটি</Link></li>
                            <li class="mb-2"><Link href="/#register" class="text-white-50 text-decoration-none hover-link">স্মৃতিচারণ প্রবন্ধ</Link></li>
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
                    © ২০২৭ জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয় হীরক জয়ন্তী উদযাপন | ৬০ বছর, সাফল্যের হীরকখণ্ড
                </div>
            </div>
        </footer>

        <!-- অভিযোগ ও পরামর্শ মডাল (Mock) -->
        <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content glass-card border-0 text-white" style="background: rgba(15, 76, 92, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(212, 175, 55, 0.2) !important;">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title gold-text fw-bold" id="feedbackModalLabel"><i class="fas fa-comment-dots me-2"></i> অভিযোগ / পরামর্শ পাঠান</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form @submit.prevent="submitFeedback">
                            <div class="mb-3">
                                <label class="form-label text-white-50 small fw-semibold">আপনার নাম</label>
                                <input v-model="feedbackForm.name" type="text" class="form-control rounded-pill bg-light bg-opacity-10 border-light border-opacity-25 text-white px-3" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50 small fw-semibold">মোবাইল নম্বর / ইমেল</label>
                                <input v-model="feedbackForm.contact" type="text" class="form-control rounded-pill bg-light bg-opacity-10 border-light border-opacity-25 text-white px-3" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50 small fw-semibold">ধরণ</label>
                                <select v-model="feedbackForm.type" class="form-select rounded-pill bg-light bg-opacity-10 border-light border-opacity-25 text-white px-3" style="color: #fff; background-color: rgba(15, 76, 92, 0.95);" required>
                                    <option value="suggestion" style="background-color: #0F4C5C;">পরামর্শ</option>
                                    <option value="complaint" style="background-color: #0F4C5C;">অভিযোগ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50 small fw-semibold">আপনার বার্তা</label>
                                <textarea v-model="feedbackForm.message" class="form-control rounded-4 bg-light bg-opacity-10 border-light border-opacity-25 text-white px-3 py-2" rows="4" required></textarea>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-diamond px-5 py-2" :disabled="isSubmittingFeedback">
                                    <span v-if="isSubmittingFeedback" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    <i v-else class="fas fa-paper-plane me-2"></i> সাবমিট করুন
                                </button>
                            </div>
                        </form>
                        <div v-if="feedbackSuccess" class="alert alert-success mt-3 text-center" v-html="feedbackSuccess"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.hero-diamond {
    background: linear-gradient(135deg, #0F4C5C 0%, #1B6B82 100%);
    position: relative;
}
.hero-diamond::before {
    content: "💎";
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
.search-input-group .form-control {
    border: none;
    outline: none;
    box-shadow: none;
}
.search-input-group .input-group-text {
    border: none;
}

.form-select {
    border-radius: 50px;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.form-select:focus {
    border-color: var(--diamond-blue);
    box-shadow: 0 0 0 0.25rem rgba(15, 76, 92, 0.1);
}

.btn-reset-custom {
    border-radius: 50px;
    font-weight: 500;
    transition: all 0.2s ease;
}
.btn-reset-custom:hover {
    background-color: var(--diamond-blue);
    border-color: var(--diamond-blue);
    color: #fff;
}

/* Friends Table Styling */
.friends-table-wrapper {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #E9EEF3;
    box-shadow: 0 10px 30px rgba(15, 76, 92, 0.05);
}
.friends-table th {
    background-color: var(--diamond-blue) !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: 18px 24px;
    border: none;
    font-size: 0.95rem;
}
.friends-table th:first-child {
    border-top-left-radius: 20px;
}
.friends-table th:last-child {
    border-top-right-radius: 20px;
}
.friends-table td {
    padding: 16px 24px;
    vertical-align: middle;
    color: #334155;
    border-bottom: 1px solid #F1F5F9;
    font-size: 0.95rem;
}
.friend-row {
    transition: background-color 0.2s ease;
}
.friend-row:hover {
    background-color: rgba(15, 76, 92, 0.02);
}
.friend-row:last-child td {
    border-bottom: none;
}
.friend-avatar-sm {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--diamond-blue) 0%, #1B6B82 100%);
    border: 1.5px solid var(--diamond-gold);
    color: #ffffff;
}
.friend-name-table {
    font-family: 'Kalpurush', 'Hind Siliguri', sans-serif;
    color: #0F4C5C;
}
.col-serial {
    width: 100px;
}
.serial-cell {
    color: #64748B !important;
}
.address-cell {
    color: #475569;
}
.badge-batch {
    background-color: rgba(212, 175, 55, 0.12) !important;
    color: #9A7B1C !important;
    border: 1px solid rgba(212, 175, 55, 0.25);
    font-size: 0.82rem;
}

/* Pagination Overrides */
.pagination {
    gap: 5px;
}
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
.pagination .page-item.disabled .page-link {
    color: #94A3B8;
    background-color: #F1F5F9;
    border-color: #E2E8F0;
}

.hover-link:hover {
    color: var(--diamond-gold) !important;
    padding-left: 5px;
    transition: all 0.2s ease;
}

.hover-social {
    transition: all 0.2s ease;
}
.hover-social:hover {
    color: var(--diamond-gold) !important;
    transform: scale(1.15);
}

/* Dropdown Submenu style adjustments if needed */
.navbar-diamond .dropdown-menu {
    border: 1px solid rgba(212, 175, 55, 0.2) !important;
    background-color: rgba(15, 76, 92, 0.95) !important;
    backdrop-filter: blur(10px);
}
.navbar-diamond .dropdown-item {
    color: rgba(255, 255, 255, 0.8) !important;
    transition: all 0.2s ease;
}
.navbar-diamond .dropdown-item:hover {
    color: #D4AF37 !important;
    background-color: rgba(255, 255, 255, 0.1) !important;
    padding-left: 1.25rem;
}

@media (max-width: 768px) {
    .hero-diamond {
        padding: 40px 0 50px !important;
    }
}
</style>
