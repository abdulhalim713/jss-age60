<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    metrics: Object,
    batchStats: Array,
    recentAlumni: Array
});

// For quick status change
const form = useForm({
    status: ''
});

const updateStatus = (id, newStatus) => {
    form.status = newStatus;
    form.patch(route('admin.alumni.status', id), {
        preserveScroll: true
    });
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('bn-BD', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Calculate max batch count to compute relative percentages for custom progress bars
const maxBatchCount = computed(() => {
    if (!props.batchStats || props.batchStats.length === 0) return 1;
    return Math.max(...props.batchStats.map(stat => stat.total));
});
</script>

<template>
    <Head title="এডমিন ড্যাসবোর্ড" />

    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">ড্যাসবোর্ড ওভারভিউ</h1>
                <p class="text-muted mb-0">হীরক জয়ন্তী উৎসব ২০২৭ এর নিবন্ধন পরিসংখ্যান</p>
            </div>
            <div>
                <Link :href="route('admin.alumni.index')" class="btn btn-primary">
                    <i class="fas fa-list me-1"></i> সকল নিবন্ধন দেখুন
                </Link>
            </div>
        </div>

        <!-- Info Cards Row -->
        <div class="row g-4 mb-4">
            <!-- Total Registrations Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm bg-primary text-white h-100 overflow-hidden position-relative card-hover">
                    <div class="card-body p-4 z-1 position-relative">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-semibold mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">মোট নিবন্ধন</h6>
                                <h2 class="display-6 fw-bold mb-0">{{ metrics.total }}</h2>
                            </div>
                            <div class="fs-1 opacity-25">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-black bg-opacity-10 border-0 p-3 text-center">
                        <Link :href="route('admin.alumni.index')" class="text-white text-decoration-none small d-flex align-items-center justify-content-center gap-1">
                            বিস্তারিত দেখুন <i class="fas fa-arrow-circle-right"></i>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Pending Registrations Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100 overflow-hidden position-relative card-hover">
                    <div class="card-body p-4 z-1 position-relative">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-dark-50 text-uppercase fw-semibold mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">অনুমোদন পেন্ডিং</h6>
                                <h2 class="display-6 fw-bold mb-0">{{ metrics.pending }}</h2>
                            </div>
                            <div class="fs-1 opacity-25">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-black bg-opacity-5 border-0 p-3 text-center">
                        <Link :href="route('admin.alumni.index', { status: 'pending' })" class="text-dark text-decoration-none small d-flex align-items-center justify-content-center gap-1">
                            অনুমোদন করুন <i class="fas fa-arrow-circle-right"></i>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Approved Registrations Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm bg-success text-white h-100 overflow-hidden position-relative card-hover">
                    <div class="card-body p-4 z-1 position-relative">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-semibold mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">অনুমোদিত অ্যালামনাই</h6>
                                <h2 class="display-6 fw-bold mb-0">{{ metrics.approved }}</h2>
                            </div>
                            <div class="fs-1 opacity-25">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-black bg-opacity-10 border-0 p-3 text-center">
                        <Link :href="route('admin.alumni.index', { status: 'approved' })" class="text-white text-decoration-none small d-flex align-items-center justify-content-center gap-1">
                            তালিকা দেখুন <i class="fas fa-arrow-circle-right"></i>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Registrations Today Card -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm bg-info text-white h-100 overflow-hidden position-relative card-hover">
                    <div class="card-body p-4 z-1 position-relative">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase fw-semibold mb-2" style="font-size: 0.8rem; letter-spacing: 1px;">আজকের নিবন্ধন</h6>
                                <h2 class="display-6 fw-bold mb-0">{{ metrics.today }}</h2>
                            </div>
                            <div class="fs-1 opacity-25">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-black bg-opacity-10 border-0 p-3 text-center">
                        <div class="text-white-50 small">
                            ২৪ ঘণ্টার আপডেট
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent Registrations Table -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-history text-secondary me-2"></i> সাম্প্রতিক নিবন্ধনসমূহ
                        </h5>
                        <span class="badge bg-light text-dark border">সর্বশেষ ১০ জন</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3">নাম ও ব্যাচ</th>
                                        <th class="py-3">মোবাইল</th>
                                        <th class="py-3">সময়</th>
                                        <th class="py-3">স্ট্যাটাস</th>
                                        <th class="px-4 py-3 text-end">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="alumnus in recentAlumni" :key="alumnus.id">
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar bg-light-primary text-primary rounded-circle p-2 d-flex justify-content-center align-items-center" style="width: 38px; height: 38px;">
                                                    <i class="fas fa-user-graduate"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">{{ alumnus.name }}</div>
                                                    <small class="text-muted">ব্যাচ: {{ alumnus.batch }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <a :href="'tel:' + alumnus.phone" class="text-decoration-none text-secondary">
                                                <i class="fas fa-phone-alt me-1 text-muted" style="font-size: 0.8rem"></i>
                                                {{ alumnus.phone }}
                                            </a>
                                        </td>
                                        <td class="py-3 small text-muted">
                                            {{ formatDate(alumnus.created_at) }}
                                        </td>
                                        <td class="py-3">
                                            <span class="badge rounded-pill px-2.5 py-1 text-uppercase font-monospace" :class="{
                                                'bg-success bg-opacity-10 text-success': alumnus.status === 'approved',
                                                'bg-warning bg-opacity-10 text-warning': alumnus.status === 'pending',
                                                'bg-danger bg-opacity-10 text-danger': alumnus.status === 'rejected',
                                            }">
                                                {{ alumnus.status === 'approved' ? 'অনুমোদিত' : (alumnus.status === 'pending' ? 'পেন্ডিং' : 'বাতিল') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <div class="d-flex justify-content-end gap-1" v-if="alumnus.status === 'pending'">
                                                <button 
                                                    @click="updateStatus(alumnus.id, 'approved')" 
                                                    class="btn btn-xs btn-success d-flex align-items-center gap-1"
                                                    title="অনুমোদন করুন"
                                                >
                                                    <i class="fas fa-check"></i> অনুমোদন
                                                </button>
                                                <button 
                                                    @click="updateStatus(alumnus.id, 'rejected')" 
                                                    class="btn btn-xs btn-danger d-flex align-items-center gap-1"
                                                    title="বাতিল করুন"
                                                >
                                                    <i class="fas fa-times"></i> বাতিল
                                                </button>
                                            </div>
                                            <div v-else>
                                                <Link :href="route('admin.alumni.index', { search: alumnus.phone })" class="btn btn-xs btn-outline-secondary">
                                                    <i class="fas fa-eye"></i> বিস্তারিত
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="!recentAlumni || recentAlumni.length === 0">
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open fs-1 mb-2 d-block text-secondary"></i>
                                            কোনো নিবন্ধন পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Batch Distribution Card -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-chart-bar text-secondary me-2"></i> ব্যাচ-ভিত্তিক নিবন্ধন
                        </h5>
                    </div>
                    <div class="card-body py-2">
                        <div v-for="stat in batchStats" :key="stat.batch" class="mb-3 py-1 border-bottom border-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-secondary">ব্যাচ {{ stat.batch }}</span>
                                <span class="badge bg-light-primary text-primary fw-bold">{{ stat.total }} জন</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div 
                                    class="progress-bar bg-primary rounded" 
                                    role="progressbar" 
                                    :style="{ width: (stat.total / maxBatchCount * 100) + '%' }" 
                                    :aria-valuenow="stat.total" 
                                    aria-valuemin="0" 
                                    :aria-valuemax="maxBatchCount"
                                ></div>
                            </div>
                        </div>
                        <div v-if="!batchStats || batchStats.length === 0" class="text-center py-5 text-muted">
                            <i class="fas fa-chart-pie fs-1 mb-2 d-block text-secondary"></i>
                            কোনো পরিসংখ্যান নেই।
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1);
}
.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
}
.card-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}
</style>
