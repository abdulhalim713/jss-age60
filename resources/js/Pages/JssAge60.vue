<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    initialAlumni: Array,
    initialRegisteredCount: Number,
    approvedCount: Number,
    heroSettings: Array,
    committeeMembers: Array,
    galleryItems: Array,
    eventDate: String,
    sponsors: Array,
    faqs: Array,
});
import '@hixbe/kalpurush';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'aos/dist/aos.css';
import AOS from 'aos';
import 'lightbox2/dist/css/lightbox.min.css';
import '../lightbox-init.js';
import '../../css/jss-age60-diamond.css';


const logoUrl = '/images/hirak-jayanti-logo.png';

// বাংলায় সংখ্যা রূপান্তর
const toBengaliDigits = (num) => {
    const bengaliDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
    return String(num).replace(/[0-9]/g, (d) => bengaliDigits[parseInt(d)]);
};

// ব্যাচ বছরের তালিকা (১৯৭০ - ২০২৭)
const batchYears = [];
for (let y = 1970; y <= 2027; y++) {
    batchYears.push({ value: toBengaliDigits(y), label: toBengaliDigits(y) });
}

const getHeroBgStyle = (hero) => {
    if (hero?.bg_image) {
        return {
            background: `linear-gradient(135deg, rgba(15,76,92,0.82) 0%, rgba(27,107,130,0.82) 100%), url('/storage/${hero.bg_image}') center/cover no-repeat`,
        };
    }
    return {
        background: `linear-gradient(135deg, rgba(15,76,92,0.82) 0%, rgba(27,107,130,0.82) 100%)`
    };
};

const getImageUrl = (path) => {
    if (!path) return '';
    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }
    return `/storage/${path}`;
};



const targetDate = computed(() => {
    if (props.eventDate) {
        const parts = props.eventDate.split('-');
        if (parts.length === 3) {
            return new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]), 0, 0, 0);
        }
        return new Date(props.eventDate);
    }
    return new Date(2027, 3, 10, 0, 0, 0);
});
const daysLeft = ref(0);
const alumniList = ref([]);
const registeredCount = ref(0);
const form = ref({ name: '', batch: '', phone: '', email: '', address: '' });
const message = ref('');
const isSubmitting = ref(false);
const isMenuOpen = ref(false);
const isDropdownOpen = ref(false);

const selectedMember = ref(null);
const activeFaqId = ref(null);
const toggleFaq = (id) => {
    activeFaqId.value = activeFaqId.value === id ? null : id;
};
const feedbackForm = ref({ name: '', contact: '', type: 'suggestion', message: '' });
const feedbackSuccess = ref('');

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

const schedule = ref([
    {
        dayNum: 1,
        date: '১ম দিন',
        events: [
            { time: 'সকাল ১০টা', name: 'শোভাযাত্রা ও পতাকা উত্তোলন' },
            { time: 'সকাল ১১টা', name: 'হীরক জয়ন্তী স্মরণিকা প্রকাশ' },
            { time: 'সকাল ১২টা', name: 'স্মৃতি বৃক্ষরোপণ' },
            { time: 'দুপুর ২টা', name: 'প্রাক্তন শিক্ষক-শিক্ষার্থী মিলনমেলা' },
            { time: 'সন্ধ্যা ৭টা', name: 'সাংস্কৃতিক সন্ধ্যা' },
        ],
    },
    {
        dayNum: 2,
        date: '২য় দিন',
        events: [
            { time: 'সকাল ৯টা', name: 'ক্রীড়া প্রতিযোগিতা' },
            { time: 'দুপুর ১১টা', name: 'প্রবীণ শিক্ষক সংবর্ধনা' }, 
            { time: 'দুপুর ১২টা', name: 'মেধাবী ও কৃতী প্রাক্তন সম্মাননা' },
            { time: 'দুপুর ১টা', name: 'মধ্যাহ্ন ভোজ' },   
            { time: 'বিকেল ৪টা', name: 'স্মৃতিচারণ সেশন' },
            { time: 'সন্ধ্যা ৬টা', name: 'সমাপনী ও আতশবাজি' },
        ],
    },
]);

const committee = computed(() => {
    if (props.committeeMembers && props.committeeMembers.length > 0) {
        return props.committeeMembers;
    }
    return [
        { name: 'অধ্যক্ষ মোঃ সিরাজুল ইসলাম', role: 'সভাপতি', icon: 'fas fa-user-tie', mobile: '০১৭১৩-৯১৩০৭৬', address: 'গাংনী, মেহেরপুর' },
        { name: 'মোঃ ফারুক হোসেন', role: 'সদস্য সচিব', icon: 'fas fa-user-graduate', mobile: '০১৭১৩-৯১৩০৭৭', address: 'গাংনী, মেহেরপুর' },
        { name: 'শামসুন্নাহার বেগম', role: 'প্র প্রাক্তন শিক্ষক প্রতিনিধি', icon: 'fas fa-chalkboard-user', mobile: '', address: 'গাংনী, মেহেরপুর' },
        { name: 'আলমগীর কবির', role: 'প্র প্রাক্তন সমন্বয়ক', icon: 'fas fa-hand-sparkles', mobile: '', address: 'গাংনী, মেহেরপুর' },
        { name: 'রোজী আক্তার', role: 'মিডিয়া উপকমিটি', icon: 'fas fa-camera', mobile: '', address: 'গাংনী, মেহেরপুর' },
        { name: 'নাজমুল হুদা', role: 'সাংস্কৃতিক সম্পাদক', icon: 'fas fa-music', mobile: '', address: 'গাংনী, মেহেরপুর' },
        { name: 'মাহবুবা সুলতানা', role: 'অর্থ ও স্পন্সর', icon: 'fas fa-dollar-sign', mobile: '', address: 'গাংনী, মেহেরপুর' },
        { name: 'মঞ্জুরুল ইসলাম', role: 'ক্রীড়া সম্পাদক', icon: 'fas fa-running', mobile: '', address: 'গাংনী, মেহেরপুর' },
    ];
});

const galleryImages = computed(() => {
    if (props.galleryItems && props.galleryItems.length > 0) {
        const imgs = props.galleryItems.filter(item => item.type === 'image');
        if (imgs.length > 0) return imgs;
    }
    return [
        { file_path: 'https://placehold.co/800x600/0F4C5C/D4AF37?text=বিদ্যালয়+প্রাঙ্গণ', title: 'বিদ্যালয় প্রাঙ্গণ' },
        { file_path: 'https://placehold.co/800x600/1B6B82/D4AF37?text=বিজ্ঞান+মেলা', title: 'বিজ্ঞান মেলা' },
        { file_path: 'https://placehold.co/800x600/2C7DA0/D4AF37?text=ক্রীড়া+প্রতিযোগিতা', title: 'ক্রীড়া প্রতিযোগিতা' },
        { file_path: 'https://placehold.co/800x600/4A235A/D4AF37?text=প্র প্রাক্তন+মিলন', title: 'প্র প্রাক্তন মিলনমেলা' },
    ];
});

const galleryVideos = computed(() => {
    if (props.galleryItems && props.galleryItems.length > 0) {
        const vids = props.galleryItems.filter(item => item.type === 'video');
        if (vids.length > 0) return vids;
    }
    return [
        { video_url: 'https://www.youtube.com/embed/9bZkp7q19f0?si=diamond', title: 'ষাট বছরের গৌরবগাথা' },
        { video_url: 'https://www.youtube.com/embed/tgbNymZ7vqY?si=alumni60', title: 'প্র প্রাক্তনদের শুভেচ্ছা' },
    ];
});

const sponsorsList = computed(() => {
    if (props.sponsors && props.sponsors.length > 0) {
        return props.sponsors;
    }
    return [
        { name: 'সোনালী ব্যাংক', logo: 'https://placehold.co/200x80/D4AF37/0F4C5C?text=Sonali+Bank' },
        { name: 'একুশে শিক্ষা', logo: 'https://placehold.co/200x80/0F4C5C/D4AF37?text=Ekushe+Trust' },
        { name: 'মেহেরপুর শিল্পগোষ্ঠী', logo: 'https://placehold.co/200x80/1B6B82/D4AF37?text=Meherpur+Group' },
        { name: 'ডিজিটাল বাংলা', logo: 'https://placehold.co/200x80/D4AF37/1B6B82?text=Digital+Bangla' },
    ];
});

let countdownInterval = null;

const updateDays = () => {
    const diff = targetDate.value - new Date();
    if (diff <= 0) {
        daysLeft.value = 0;
    } else {
        daysLeft.value = Math.floor(diff / (1000 * 60 * 60 * 24));
    }
};

const loadAlumni = () => {
    if (props.initialAlumni && props.initialAlumni.length > 0) {
        alumniList.value = props.initialAlumni;
    } else {
        alumniList.value = [
            { id: 1, name: 'মোঃ আব্দুল মতিন', batch: '১৯৮৫' },
            { id: 2, name: 'ফাতেমা খাতুন', batch: '১৯৯২' },
            { id: 3, name: 'হাসান আহমেদ', batch: '২০০০' },
            { id: 4, name: 'শাহিনুর রহমান', batch: '১৯৯৫' },
        ];
    }
    registeredCount.value = props.approvedCount !== undefined ? props.approvedCount : alumniList.value.length;
};

const registerAlumni = () => {
    if (isSubmitting.value) return;
    if (!form.value.name || !form.value.batch || !form.value.phone) {
        alert('নাম, ব্যাচ ও মোবাইল নম্বর অবশ্যই পূরণ করুন।');
        return;
    }
    isSubmitting.value = true;
    router.post(route('alumni.register'), form.value, {
        preserveScroll: true,
        onSuccess: () => {
            message.value = `<i class="fas fa-check-circle"></i> ধন্যবাদ! ${form.value.name}, আপনার আবেদনটি সফলভাবে জমা হয়েছে। এডমিন অনুমোদনের পর তা তালিকাভুক্ত হবে।`;
            form.value = { name: '', batch: '', phone: '', email: '', address: '' };
            setTimeout(() => {
                message.value = '';
            }, 6000);
        },
        onError: (errors) => {
            const errorMsgs = Object.values(errors).join('\n');
            alert('নিবন্ধন ব্যর্থ হয়েছে:\n' + errorMsgs);
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

const scrollTo = (id) => {
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    }
};

const onScroll = () => {
    let current = '';
    const sections = document.querySelectorAll('.jss-age60-root section[id]');
    sections.forEach((sec) => {
        const sectionTop = sec.offsetTop - 200;
        if (window.pageYOffset >= sectionTop) {
            current = sec.getAttribute('id');
        }
    });
    document.querySelectorAll('.navbar-diamond .nav-link').forEach((link) => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
};

onMounted(() => {
    updateDays();
    countdownInterval = setInterval(updateDays, 86400000);
    loadAlumni();
    AOS.init({ duration: 800, once: true });
    window.addEventListener('scroll', onScroll);


});

onUnmounted(() => {
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <Head>
        <title>হীরক জয়ন্তী | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</title>
        <link
            href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
        <meta name="description" content="১৯৬৭-২০২৭: জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের ৬০ বছর গৌরবময় পথচলার হীরক জয়ন্তী উদযাপন। প্রাক্তন শিক্ষার্থীদের পুনর্মিলনী নিবন্ধন ও উৎসবের বিস্তারিত সূচি।" />
        <meta property="og:title" content="হীরক জয়ন্তী | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়" />
        <meta property="og:description" content="১৯৬৭-২০২৭: জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের ৬০ বছর গৌরবময় পথচলার হীরক জয়ন্তী উদযাপন। প্রাক্তন শিক্ষার্থীদের পুনর্মিলনী নিবন্ধন ও উৎসবের বিস্তারিত সূচি।" />
        <meta property="og:image" content="/images/hirak-jayanti-logo.png" />
        <meta property="og:type" content="website" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="হীরক জয়ন্তী | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়" />
        <meta name="twitter:description" content="১৯৬৭-২০২৭: জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের ৬০ বছর গৌরবময় পথচলার হীরক জয়ন্তী উদযাপন। প্রাক্তন শিক্ষার্থীদের পুনর্মিলনী নিবন্ধন ও উৎসবের বিস্তারিত সূচি।" />
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
                    <a
                        class="navbar-brand d-flex align-items-center gap-2 text-decoration-none"
                        href="#home"
                        @click.prevent="scrollTo('home'); isMenuOpen = false;"
                    >
                        <img :src="logoUrl" alt="হীরক জয়ন্তী" class="site-logo" />
                        <div class="d-flex flex-column site-title-container">
                            <span class="brand-subtext text-white fw-bold" style="font-size: 1.25rem; line-height: 1.1; letter-spacing: 0.5px;">হীরক জয়ন্তী</span>
                            <span class="site-title" style="font-size: 1.15rem; font-weight: 700; line-height: 1.1;">জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</span>
                        </div>
                    </a>
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
                                <a class="nav-link" href="#home" @click.prevent="scrollTo('home'); isMenuOpen = false;">হোম</a>
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
                                    <li><a class="dropdown-item" href="#about" @click.prevent="scrollTo('about'); isMenuOpen = false; isDropdownOpen = false;">বিস্তারিত পরিকল্পনা</a></li>
                                    <li><a class="dropdown-item" href="#committee" @click.prevent="scrollTo('committee'); isMenuOpen = false; isDropdownOpen = false;">উপদেষ্টা কমিটি</a></li>
                                    <li><a class="dropdown-item" href="#committee" @click.prevent="scrollTo('committee'); isMenuOpen = false; isDropdownOpen = false;">উদযাপন কমিটি</a></li>
                                    <li><a class="dropdown-item" href="#committee" @click.prevent="scrollTo('committee'); isMenuOpen = false; isDropdownOpen = false;">খাতভিত্তিক উপ কমিটি</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#" @click.prevent="showAccountsMessage(); isMenuOpen = false; isDropdownOpen = false;">আয় ব্যয় হিসাব</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#register" @click.prevent="scrollTo('register'); isMenuOpen = false;">স্মৃতিচারণ প্রবন্ধ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/friends">বন্ধু তালিকা</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#feedbackModal" @click="isMenuOpen = false">অভিযোগ/পরামর্শ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#footer" @click.prevent="scrollTo('footer'); isMenuOpen = false;">যোগাযোগ</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- হিরো সেকশন -->
        <section id="home" class="hero-diamond p-0 overflow-hidden">
            <div id="heroCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel" data-bs-interval="6000">
                <!-- Indicators if multiple slides -->
                <div class="carousel-indicators" style="margin-bottom: 2.5rem;" v-if="props.heroSettings && props.heroSettings.length > 1">
                    <button 
                        v-for="(hero, idx) in props.heroSettings" 
                        :key="hero.id" 
                        type="button" 
                        data-bs-target="#heroCarousel" 
                        :data-bs-slide-to="idx" 
                        :class="{ active: idx === 0 }"
                        :aria-current="idx === 0 ? 'true' : 'false'"
                        :aria-label="'Slide ' + (idx + 1)"
                    ></button>
                </div>

                <div class="carousel-inner h-100">
                    <!-- Default slide if empty -->
                    <div 
                        v-if="!props.heroSettings || props.heroSettings.length === 0" 
                        class="carousel-item active"
                        style="padding: 100px 0 120px;"
                    >
                        <div class="container text-center text-white" data-aos="fade-up">
                            <div class="d-inline-block diamond-badge rounded-pill px-4 py-2 mb-3">
                                <i class="fas fa-gem gold-text me-2"></i> হীরক জয়ন্তী ২০২৭
                            </div>
                            <h1 class="display-3 fw-bold">
                                <span class="gold-text">৬০ বছর</span> <br />
                                উজ্জ্বল দিগন্ত
                            </h1>
                            <p class="lead mx-auto mt-3" style="max-width: 700px">
                                ১৯৬৭-২০২৭: শিক্ষার আলো, বন্ধনের হীরকখণ্ড। প্রাক্তন-বর্তমান মিলনমেলা ও সাংস্কৃতিক উৎসব।
                            </p>
                            <div class="mt-4">
                                <a href="#register" class="btn btn-diamond btn-lg me-3" @click.prevent="scrollTo('register')">
                                    <i class="fas fa-user-check"></i> নিবন্ধন করুন
                                </a>
                                <a href="#about" class="btn btn-outline-light btn-lg" @click.prevent="scrollTo('about')">
                                    <i class="fas fa-info-circle"></i> বিস্তারিত
                                </a>
                            </div>
                            <!-- Statistics Cards -->
                            <div class="row mt-5 g-4">
                                <div class="col-md-4">
                                    <div class="counter-card">
                                        <div class="counter-number">{{ toBengaliDigits(daysLeft) }}</div>
                                        <p>দিন বাকি</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="counter-card">
                                        <div class="counter-number">{{ toBengaliDigits(registeredCount) }}</div>
                                        <p>নিবন্ধিত প্রাক্তন</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="counter-card">
                                        <div class="counter-number">৩ দিন</div>
                                        <p>ব্যাপী উৎসব</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DB Slides -->
                    <div 
                        v-for="(hero, idx) in props.heroSettings" 
                        :key="hero.id" 
                        class="carousel-item"
                        :class="{ active: idx === 0 }"
                        :style="[getHeroBgStyle(hero), { padding: '100px 0 120px' }]"
                    >
                        <div class="container text-center text-white" data-aos="fade-up">
                            <div class="d-inline-block diamond-badge rounded-pill px-4 py-2 mb-3">
                                <i class="fas fa-gem gold-text me-2"></i> হীরক জয়ন্তী ২০২৭
                            </div>
                            <h1 class="display-3 fw-bold">
                                {{ hero.title }}
                            </h1>
                            <p class="lead mx-auto mt-3" style="max-width: 700px">
                                {{ hero.subtitle }}
                            </p>
                            <div class="mt-4">
                                <a href="#register" class="btn btn-diamond btn-lg me-3" @click.prevent="scrollTo('register')">
                                    <i class="fas fa-user-check"></i> নিবন্ধন করুন
                                </a>
                                <a href="#about" class="btn btn-outline-light btn-lg" @click.prevent="scrollTo('about')">
                                    <i class="fas fa-info-circle"></i> বিস্তারিত
                                </a>
                            </div>
                            <!-- Statistics Cards -->
                            <div class="row mt-5 g-4">
                                <div class="col-md-4">
                                    <div class="counter-card">
                                        <div class="counter-number">{{ toBengaliDigits(daysLeft) }}</div>
                                        <p>দিন বাকি</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="counter-card">
                                        <div class="counter-number">{{ toBengaliDigits(registeredCount) }}</div>
                                        <p>নিবন্ধিত প্রাক্তন</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="counter-card">
                                        <div class="counter-number">২ দিন</div>
                                        <p>ব্যাপী উৎসব</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controls if multiple slides -->
                <button 
                    v-if="props.heroSettings && props.heroSettings.length > 1" 
                    class="carousel-control-prev" 
                    type="button" 
                    data-bs-target="#heroCarousel" 
                    data-bs-slide="prev"
                >
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button 
                    v-if="props.heroSettings && props.heroSettings.length > 1" 
                    class="carousel-control-next" 
                    type="button" 
                    data-bs-target="#heroCarousel" 
                    data-bs-slide="next"
                >
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <!-- হীরক জয়ন্তী তথ্য -->
        <section id="about" class="container my-5 py-4" data-aos="fade-up">
            <div class="glass-card">
                <div class="card-header-diamond">
                    <i class="fas fa-diamond me-2"></i> হীরক জয়ন্তী: ষাট বছরের মহিমা
                </div>
                <div class="p-4 p-md-5">
                    <p class="fs-5 fst-italic">
                        "১৯৬৭ সালে স্থাপিত জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয় আজ এক উজ্জ্বল নক্ষত্র। শিক্ষার মান, কৃতি
                        শিক্ষার্থী ও অসামান্য সাফল্যে অর্জিত হয়েছে হীরকখণ্ডের দ্যুতি।"
                    </p>
                    <p>
                        ষাট বছর পূর্ণ হওয়ার এই শুভলগ্নে আমরা স্মরণ করি বিদ্যালয়ের গৌরবময় যাত্রাপথ। কত প্রজন্মের মেধা
                        বিকাশের কেন্দ্রবিন্দু এই বিদ্যালয়। হীরক জয়ন্তী উপলক্ষে আয়োজিত তিন দিনব্যাপি অনুষ্ঠানে সকল
                        প্রাক্তন শিক্ষার্থী, শিক্ষক, শুভানুধ্যায়ী ও স্থানীয় গণ্যমান্য ব্যক্তিরা অংশ নেবেন। আলোচনা সভা,
                        সাংস্কৃতিক অনুষ্ঠান, পুনর্মিলনী ও সম্মাননা প্রদান থাকছে প্রতিদিন।
                    </p>
                    <div class="row mt-4 text-center">
                        <div class="col-md-3 col-6">
                            <i class="fas fa-school fa-3x gold-text"></i>
                            <h5>১৯৬৭ প্রতিষ্ঠা</h5>
                        </div>
                        <div class="col-md-3 col-6">
                            <i class="fas fa-trophy fa-3x gold-text"></i>
                            <h5>৫বার জেলা শ্রেষ্ঠ</h5>
                        </div>
                        <div class="col-md-3 col-6">
                            <i class="fas fa-users fa-3x gold-text"></i>
                            <h5>৫০০০+ প্রাক্তন</h5>
                        </div>
                        <div class="col-md-3 col-6">
                            <i class="fas fa-gem fa-3x gold-text"></i>
                            <h5>হীরক জয়ন্তী</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- সময়সূচি -->
        <section id="schedule" class="container my-5" data-aos="fade-up">
            <h2 class="text-center fw-bold mb-5">
                <span class="gold-text">✨ হীরক উৎসবের সূচি ✨</span><br />
                
            </h2>
            <div class="row g-4 justify-content-center">
                <div v-for="day in schedule" :key="day.dayNum" class="col-md-4">
                    <div class="glass-card p-4">
                        <div class="text-center">
                            <span class="badge bg-dark text-gold px-4 py-2 rounded-pill mb-3">
                                <i class="fas fa-calendar-alt me-2"></i> দিন {{ day.dayNum }}: {{ day.date }}
                            </span>
                        </div>
                        <ul class="list-unstyled mt-2">
                            <li v-for="item in day.events" :key="item.time" class="mb-3">
                                <i class="fas fa-circle gold-text me-2" style="font-size: 8px"></i>
                                <strong>{{ item.time }}</strong> - {{ item.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- উদযাপন কমিটি -->
        <section id="committee" class="container my-5" data-aos="fade-up">
            <div class="glass-card p-4">
                <h3 class="text-center gold-text fw-bold">
                    <i class="fas fa-crown"></i> হীরক জয়ন্তী উদযাপন কমিটি
                </h3>
                <p class="text-center">নিবেদিত কর্মী ও অভিজ্ঞ ব্যক্তিত্ব</p>
                <div class="row g-4 mt-2">
                    <div v-for="member in committee" :key="member.id || member.name" class="col-md-3 col-6">
                        <div 
                            class="text-center p-3 rounded-4 bg-light committee-card shadow-sm h-100"
                            style="cursor: pointer; transition: all 0.3s ease; border: 1px solid transparent;"
                            data-bs-toggle="modal"
                            data-bs-target="#memberProfileModal"
                            @click="selectedMember = member"
                        >
                            <div class="member-avatar-wrapper mb-2 d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm" style="width: 70px; height: 70px; overflow: hidden; border: 2px solid #D4AF37;">
                                <img v-if="member.image" :src="getImageUrl(member.image)" :alt="member.name" class="w-100 h-100" style="object-fit: cover;" />
                                <i v-else :class="member.icon + ' fa-2x gold-text'"></i>
                            </div>
                            <h6 class="mt-2 mb-1 text-dark fw-bold" style="font-size: 0.95rem;">{{ member.name }}</h6>
                            <small class="text-muted d-block" style="font-size: 0.8rem;">{{ member.role }}</small>
                            <small v-if="member.batch" class="d-block mt-1" style="font-size: 0.75rem; color: #0F4C5C; font-weight: 600;">
                                <i class="fas fa-graduation-cap me-1"></i>ব্যাচ-{{ toBengaliDigits(member.batch) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ফটো ও ভিডিও গ্যালারি -->
        <section id="gallery" class="container my-5" data-aos="fade-up">
            <div class="glass-card p-4">
                <h3 class="text-center gold-text fw-bold">
                    <i class="fas fa-images"></i> স্মৃতি গ্যালারি (সাম্প্রতিক কার্যক্রম)
                </h3>
                <div class="row g-4 mt-2">
                    <div v-for="(img, idx) in galleryImages" :key="idx" class="col-md-3 col-6">
                        <a :href="getImageUrl(img.file_path)" data-lightbox="diamondGallery">
                            <img :src="getImageUrl(img.file_path)" class="gallery-img" :alt="img.title" />
                        </a>
                    </div>
                </div>
                <div class="text-center mt-5" v-if="galleryVideos && galleryVideos.length > 0">
                    <h4 class="gold-text"><i class="fas fa-video"></i> ভিডিও বার্তা</h4>
                    <div class="row mt-3 g-4 justify-content-center">
                        <div v-for="(vid, idx) in galleryVideos" :key="idx" class="col-md-6">
                            <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm">
                                <iframe
                                    :src="vid.video_url"
                                    :title="vid.title"
                                    allowfullscreen
                                ></iframe>
                            </div>
                            <p class="mt-2">{{ vid.title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- স্পন্সর তালিকা -->
        <section id="sponsors" class="container my-5" data-aos="fade-up">
            <div class="glass-card p-4">
                <h3 class="text-center gold-text fw-bold">
                    <i class="fas fa-hand-holding-usd"></i> সহযোগী ও স্পন্সর
                </h3>
                <p class="text-center">হীরক জয়ন্তী আয়োজনে পাশে থাকা প্রতিষ্ঠান</p>
                <div class="row align-items-center justify-content-center g-4 mt-2">
                    <div v-for="sp in sponsorsList" :key="sp.name" class="col-md-3 col-6 text-center">
                        <a v-if="sp.link" :href="sp.link" target="_blank">
                            <img :src="getImageUrl(sp.logo)" class="sponsor-logo img-fluid" :alt="sp.name" />
                        </a>
                        <img v-else :src="getImageUrl(sp.logo)" class="sponsor-logo img-fluid" :alt="sp.name" />
                    </div>
                </div>
            </div>
        </section>

        <!-- সচরাচর জিজ্ঞাসা (FAQ) -->
        <section id="faq" class="container my-5" data-aos="fade-up" v-if="props.faqs && props.faqs.length > 0">
            <div class="glass-card p-4">
                <h3 class="text-center gold-text fw-bold mb-2">
                    <i class="fas fa-question-circle"></i> সচরাচর জিজ্ঞাসা (FAQ)
                </h3>
                <p class="text-center text-muted">উৎসব সম্পর্কিত সাধারণ কিছু প্রশ্ন ও উত্তর</p>
                <div class="accordion accordion-flush mt-4" id="faqAccordion">
                    <div v-for="(faq, index) in props.faqs" :key="faq.id" class="accordion-item bg-transparent border-bottom border-secondary-subtle py-2">
                        <h2 class="accordion-header" :id="'heading' + faq.id">
                            <button
                                class="accordion-button bg-transparent text-dark fw-bold fs-6 shadow-none"
                                :class="{ 'collapsed': activeFaqId !== faq.id }"
                                type="button"
                                @click="toggleFaq(faq.id)"
                            >
                                <span class="me-3 gold-text">{{ toBengaliDigits(index + 1) }}.</span> {{ faq.question }}
                            </button>
                        </h2>
                        <div
                            :id="'collapse' + faq.id"
                            class="accordion-collapse collapse"
                            :class="{ 'show': activeFaqId === faq.id }"
                        >
                            <div class="accordion-body text-secondary px-4 pt-2 pb-3" style="line-height: 1.7;">
                                {{ faq.answer }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- প্রাক্তন নিবন্ধন ফর্ম + লাইভ তালিকা -->
        <section id="register" class="container my-5" data-aos="fade-up">
            <div class="glass-card p-4 p-md-5">
                <div class="text-center">
                    <span class="badge bg-dark text-gold px-4 py-2 rounded-pill">
                        <i class="fas fa-pen-fancy"></i> প্রাক্তন নিবন্ধন
                    </span>
                    <h2 class="mt-3 fw-bold">পুনর্মিলনীতে অংশ নিন</h2>
                    <p>আপনার তথ্য সংরক্ষণ করুন, হীরক জয়ন্তী উৎসবের অংশ হয়ে উঠুন।</p>
                </div>
                <form class="row g-4" @submit.prevent="registerAlumni">
                    <div class="col-md-6">
                        <input
                            v-model="form.name"
                            type="text"
                            class="form-control rounded-pill p-3"
                            placeholder="পূর্ণ নাম"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <select
                            v-model="form.batch"
                            class="form-control form-select-pill p-3"
                            required
                        >
                            <option value="" disabled>ব্যাচ নির্বাচন করুন</option>
                            <option v-for="yr in batchYears" :key="yr.value" :value="yr.value">
                                {{ yr.label }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input
                            v-model="form.phone"
                            type="tel"
                            class="form-control rounded-pill p-3"
                            placeholder="মোবাইল নম্বর"
                            required
                        />
                    </div>
                    <div class="col-md-6">
                        <input
                            v-model="form.email"
                            type="email"
                            class="form-control rounded-pill p-3"
                            placeholder="ইমেইল (ঐচ্ছিক)"
                        />
                    </div>
                    <div class="col-12">
                        <textarea
                            v-model="form.address"
                            class="form-control rounded-4 p-3"
                            rows="2"
                            placeholder="বর্তমান ঠিকানা"
                        ></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-diamond px-5 py-2" :disabled="isSubmitting">
                            <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            <i v-else class="fas fa-check-double"></i> নিবন্ধন জমা দিন
                        </button>
                    </div>
                </form>
                <div v-if="message" class="alert alert-success mt-4" v-html="message"></div>
                <hr class="my-4" />
                <h4 class="text-center gold-text"><i class="fas fa-users"></i> নিবন্ধিত প্রাক্তন শিক্ষার্থী</h4>
                <div class="row g-3 mt-2">
                    <div v-for="al in alumniList.slice(0, 9)" :key="al.id" class="col-md-4">
                        <div class="bg-light rounded-4 p-2 text-center shadow-sm">
                            <i class="fas fa-user-graduate fa-2x gold-text"></i>
                            <h6 class="mt-1">{{ al.name }}</h6>
                            <small>ব্যাচ {{ al.batch }}</small>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small>মোট নিবন্ধিত: {{ toBengaliDigits(alumniList.length) }} জন</small>
                </div>
            </div>
        </section>

        <!-- ফুটার -->
        <footer id="footer" class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <h4><i class="fas fa-school gold-text"></i> জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়</h4>
                        <p>
                            হীরক জয়ন্তী উদযাপন কমিটি, গাংনী, মেহেরপুর। <br />
                            <i class="fas fa-phone-alt"></i> +৮৮০১৭১৩-৯১৩০৭৬, +৮৮০১৭৫৮-৮৯৩৪৩২ &nbsp;
                            <i class="fas fa-envelope"></i> age60@myjss.edu.bd
                        </p>
                    </div>
                    <div class="col-md-3">
                        <h5>দ্রুত লিংক</h5>
                        <p>
                            <a href="#schedule" class="text-white-50 text-decoration-none" @click.prevent="scrollTo('schedule')">সময়সূচি</a>
                        </p>
                        <p>
                            <a href="#committee" class="text-white-50 text-decoration-none" @click.prevent="scrollTo('committee')">কমিটি</a>
                        </p>
                        <p>
                            <a href="#register" class="text-white-50 text-decoration-none" @click.prevent="scrollTo('register')">স্মৃতিচারণ প্রবন্ধ</a>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h5>সোশ্যাল সংযোগ</h5>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-white-50 fs-4"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="text-white-50 fs-4"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="text-white-50 fs-4"><i class="fab fa-instagram"></i></a>
                        </div>
                        <p class="mt-3">#JSSDiamond60 #JorepukuriaReunion #JSS #JSSAGE60</p>
                    </div>
                </div>
                <hr class="bg-secondary" />
                <div class="text-center small">
                    © ২০২৭ জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয় হীরক জয়ন্তী উদযাপন | ৬০ বছর, সাফল্যের হীরকখণ্ড
                </div>
            </div>
        </footer>

        <!-- Committee Member Profile Modal -->
        <div class="modal fade" id="memberProfileModal" tabindex="-1" aria-labelledby="memberProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content glass-card border-0 text-white" style="background: rgba(15, 76, 92, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(212, 175, 55, 0.2) !important;">
                    <div class="modal-header border-bottom-0 pb-0">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center pt-0 px-4 pb-4" v-if="selectedMember">
                        <div class="member-profile-avatar mb-3 d-inline-flex align-items-center justify-content-center bg-white text-dark rounded-circle shadow" style="width: 120px; height: 120px; overflow: hidden; border: 3px solid #D4AF37;">
                            <img v-if="selectedMember.image" :src="getImageUrl(selectedMember.image)" :alt="selectedMember.name" class="w-100 h-100" style="object-fit: cover;" />
                            <i v-else :class="selectedMember.icon + ' fa-4x gold-text'"></i>
                        </div>
                        <h4 class="fw-bold gold-text mb-1">{{ selectedMember.name }}</h4>
                        <div class="badge bg-dark text-gold mb-4 px-3 py-2 rounded-pill fw-semibold" style="border: 1px solid #D4AF37;">{{ selectedMember.role }}</div>
                        
                        <div class="profile-details text-start bg-light bg-opacity-10 rounded-4 p-3 border border-light border-opacity-10">
                            <div class="d-flex align-items-center mb-3" v-if="selectedMember.mobile">
                                <div class="icon-circle bg-gold text-dark me-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; flex-shrink: 0; background-color: #D4AF37;">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <small class="text-white-50 d-block">মোবাইল নম্বর</small>
                                    <a :href="'tel:' + selectedMember.mobile" class="text-white text-decoration-none fw-medium">{{ toBengaliDigits(selectedMember.mobile) }}</a>
                                </div>
                            </div>
                            <div class="d-flex align-items-center" v-if="selectedMember.address">
                                <div class="icon-circle bg-gold text-dark me-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 36px; height: 36px; flex-shrink: 0; background-color: #D4AF37;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <small class="text-white-50 d-block">ঠিকানা</small>
                                    <span class="text-white fw-medium">{{ selectedMember.address }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaints & Suggestions Modal -->
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
                                <label class="form-label text-white-50">আপনার নাম</label>
                                <input v-model="feedbackForm.name" type="text" class="form-control rounded-pill bg-light bg-opacity-10 border-light border-opacity-25 text-white px-3" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50">মোবাইল নম্বর / ইমেল</label>
                                <input v-model="feedbackForm.contact" type="text" class="form-control rounded-pill bg-light bg-opacity-10 border-light border-opacity-25 text-white px-3" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50">ধরণ</label>
                                <select v-model="feedbackForm.type" class="form-select rounded-pill bg-light bg-opacity-10 border-light border-opacity-25 text-white px-3" style="color: #fff; background-color: rgba(15, 76, 92, 0.95);" required>
                                    <option value="suggestion" style="background-color: #0F4C5C;">পরামর্শ</option>
                                    <option value="complaint" style="background-color: #0F4C5C;">অভিযোগ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white-50">আপনার বার্তা</label>
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

<style>
/* Dropdown hover behavior for desktop */
@media (min-width: 992px) {
    .navbar-diamond .dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0;
        animation: fadeIn 0.2s ease-in-out;
    }
}

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

.committee-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12) !important;
    border: 1px solid rgba(212, 175, 55, 0.5) !important;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom FAQ Accordion styling */
#faqAccordion .accordion-button {
    color: #0f4c5c !important;
}
#faqAccordion .accordion-button:not(.collapsed) {
    background-color: transparent !important;
    color: #D4AF37 !important;
    box-shadow: none !important;
}
#faqAccordion .accordion-button::after {
    filter: none !important;
}
#faqAccordion .accordion-button:not(.collapsed)::after {
    filter: sepia(100%) saturate(1000%) hue-rotate(340deg) brightness(85%) contrast(85%) !important;
}
#faqAccordion .accordion-body {
    color: #2c3e50 !important;
}
#faqAccordion .accordion-collapse {
    display: none !important;
}
#faqAccordion .accordion-collapse.show {
    display: block !important;
}
</style>
