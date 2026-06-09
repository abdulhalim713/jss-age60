<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    representatives: Array,
    batches: Array,
    filters: Object,
});

const deletingId = ref(null);
const filterBatch = ref(props.filters?.batch || '');

const applyFilters = () => {
    router.get(route('admin.batch-representatives.index'), {
        batch: filterBatch.value,
    }, { preserveState: true, replace: true, preserveScroll: true });
};

const confirmDelete = (rep) => {
    if (confirm(`"${rep.name}" কে প্রতিনিধি তালিকা থেকে মুছে ফেলবেন?`)) {
        deletingId.value = rep.id;
        router.delete(route('admin.batch-representatives.destroy', rep.id), {
            onFinish: () => { deletingId.value = null; },
        });
    }
};

const toggleActive = (rep) => {
    router.patch(route('admin.batch-representatives.toggle-active', rep.id), {}, {
        preserveScroll: true,
    });
};

const getImageUrl = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return `/storage/${path}`;
};

// Group by batch for display
const groupedByBatch = (reps) => {
    const groups = {};
    reps.forEach(r => {
        if (!groups[r.batch]) groups[r.batch] = [];
        groups[r.batch].push(r);
    });
    return groups;
};

const logoUrl = '/images/hirak-jayanti-logo.png';

const printPage = () => {
    window.print();
};
</script>

<template>
    <Head title="ব্যাচ ভিত্তিক প্রতিনিধি" />
    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-user-shield text-warning me-2"></i>
                ব্যাচ ভিত্তিক প্রতিনিধি ব্যবস্থাপনা
            </h4>
            <div class="d-flex gap-2">
                <button @click="printPage" class="btn btn-outline-secondary fw-semibold">
                    <i class="fas fa-print me-1"></i> প্রিন্ট
                </button>
                <Link :href="route('admin.batch-representatives.create')" class="btn btn-warning fw-semibold">
                    <i class="fas fa-plus me-1"></i> নতুন প্রতিনিধি যোগ করুন
                </Link>
            </div>
        </div>

        <!-- Print Header -->
        <div class="print-header d-none d-print-flex">
            <img :src="logoUrl" alt="হীরক জয়ন্তী লোগো" />
            <div>
                <h2>জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয় হীরক জয়ন্তী ২০২৭</h2>
                <h4>ব্যাচ ভিত্তিক প্রতিনিধি তালিকা <span v-if="filterBatch">({{ filterBatch }} ব্যাচ)</span></h4>
            </div>
        </div>

        <!-- Flash Message -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible rounded-3 mb-4 d-print-none">
            <i class="fas fa-check-circle me-2"></i>{{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-filter text-muted me-2"></i>
                    <span class="fw-semibold me-2">ফিল্টার:</span>
                </div>
                <div style="width: 200px;">
                    <select v-model="filterBatch" @change="applyFilters" class="form-select form-select-sm rounded-pill">
                        <option value="">সকল ব্যাচ</option>
                        <option v-for="b in batches" :key="b" :value="b">ব্যাচ {{ b }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm rounded-4 table-card-print">
            <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4 d-flex align-items-center justify-content-between d-print-none">
                <span><i class="fas fa-list me-2"></i> প্রতিনিধিদের তালিকা</span>
                <span class="badge bg-warning text-dark">মোট: {{ representatives.length }} জন</span>
            </div>
            <div class="card-body p-0">
                <div v-if="representatives.length === 0" class="text-center text-muted py-5">
                    <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                    <p>এখনো কোনো প্রতিনিধি যোগ করা হয়নি।</p>
                    <Link :href="route('admin.batch-representatives.create')" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus me-1"></i> প্রথম প্রতিনিধি যোগ করুন
                    </Link>
                </div>
                <div v-else class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:60px;" class="ps-4">ক্রম</th>
                                <th style="width:70px;">ছবি</th>
                                <th>নাম</th>
                                <th style="width:110px;">ব্যাচ</th>
                                <th>মোবাইল</th>
                                <th>ঠিকানা</th>
                                <th style="width:110px;" class="d-print-none">অবস্থা</th>
                                <th class="text-center d-print-none" style="width:130px;">কার্যক্রম</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(rep, index) in representatives" :key="rep.id">
                                <td class="ps-4">
                                    <span class="fw-semibold text-muted">{{ index + 1 }}</span>
                                </td>
                                <td>
                                    <img
                                        v-if="rep.image"
                                        :src="getImageUrl(rep.image)"
                                        class="rounded-circle border"
                                        style="width:42px;height:42px;object-fit:cover;"
                                        :alt="rep.name"
                                    />
                                    <div v-else
                                        class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                                        style="width:42px;height:42px;background:linear-gradient(135deg,#0F4C5C,#1B6B82);font-size:0.85rem;"
                                    >
                                        {{ (rep.name || '').split(' ').map(n => n[0]).join('').slice(0, 2) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ rep.name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border">
                                        <i class="fas fa-graduation-cap me-1"></i>{{ rep.batch }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ rep.mobile || '—' }}</td>
                                <td class="text-muted small">
                                    <span class="d-inline-block text-truncate" style="max-width:160px;" :title="rep.address">
                                        {{ rep.address || '—' }}
                                    </span>
                                </td>
                                <td class="d-print-none">
                                    <span
                                        class="badge px-3 py-2 rounded-pill"
                                        :class="rep.is_active ? 'bg-success' : 'bg-secondary'"
                                        style="cursor:pointer;"
                                        @click="toggleActive(rep)"
                                        title="ক্লিক করে পরিবর্তন করুন"
                                    >
                                        <i :class="rep.is_active ? 'fas fa-eye' : 'fas fa-eye-slash'" class="me-1"></i>
                                        {{ rep.is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </span>
                                </td>
                                <td class="text-center d-print-none">
                                    <Link
                                        :href="route('admin.batch-representatives.edit', rep.id)"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="সম্পাদনা"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        @click="confirmDelete(rep)"
                                        :disabled="deletingId === rep.id"
                                        title="মুছুন"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Info Note -->
        <div class="alert alert-info border-0 rounded-3 mt-3 small d-print-none">
            <i class="fas fa-info-circle me-2"></i>
            <strong>টিপস:</strong> "ক্রম" নম্বর ছোট হলে প্রতিনিধি সাইটে আগে দেখাবে। অবস্থার উপর ক্লিক করে সক্রিয়/নিষ্ক্রিয় করা যাবে।
        </div>
    </AdminLayout>
</template>

<style scoped>
@media print {
    body, html {
        background: white !important;
        color: black !important;
    }

    /* Hide the admin sidebar and header based on AdminLTE v4 classes */
    :deep(.app-sidebar), :deep(.app-header), .d-print-none {
        display: none !important;
    }
    
    :deep(.app-wrapper), :deep(.app-main) {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        background-color: white !important;
    }

    .table-card-print {
        border: none !important;
        box-shadow: none !important;
        background-color: transparent !important;
    }

    .table {
        border-collapse: collapse !important;
        width: 100% !important;
    }

    .table th, .table td {
        border: 1px solid #000 !important;
        padding: 8px !important;
        color: #000 !important;
    }

    .table th {
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .badge {
        border: none !important;
        color: #000 !important;
        background: transparent !important;
        padding: 0 !important;
    }

    /* Print Header */
    .print-header {
        display: flex !important;
        align-items: center;
        border-bottom: 2px solid #000;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .print-header img {
        width: 80px;
        height: auto;
        margin-right: 20px;
    }

    .print-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
        color: #000;
    }

    .print-header h4 {
        margin: 5px 0 0 0;
        font-size: 18px;
        color: #333;
    }
}
</style>
