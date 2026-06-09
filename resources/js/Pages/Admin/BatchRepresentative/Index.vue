<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    representatives: Array,
});

const deletingId = ref(null);

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
</script>

<template>
    <Head title="ব্যাচ ভিত্তিক প্রতিনিধি" />
    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-user-shield text-warning me-2"></i>
                ব্যাচ ভিত্তিক প্রতিনিধি ব্যবস্থাপনা
            </h4>
            <Link :href="route('admin.batch-representatives.create')" class="btn btn-warning fw-semibold">
                <i class="fas fa-plus me-1"></i> নতুন প্রতিনিধি যোগ করুন
            </Link>
        </div>

        <!-- Flash Message -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible rounded-3 mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4 d-flex align-items-center justify-content-between">
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
                                <th style="width:110px;">অবস্থা</th>
                                <th class="text-center" style="width:130px;">কার্যক্রম</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="rep in representatives" :key="rep.id">
                                <td class="ps-4">
                                    <span class="badge bg-secondary rounded-pill">{{ rep.sort_order }}</span>
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
                                <td>
                                    <span class="text-muted small d-inline-block text-truncate" style="max-width:160px;">
                                        {{ rep.address || '—' }}
                                    </span>
                                </td>
                                <td>
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
                                <td class="text-center">
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
        <div class="alert alert-info border-0 rounded-3 mt-3 small">
            <i class="fas fa-info-circle me-2"></i>
            <strong>টিপস:</strong> "ক্রম" নম্বর ছোট হলে প্রতিনিধি সাইটে আগে দেখাবে। অবস্থার উপর ক্লিক করে সক্রিয়/নিষ্ক্রিয় করা যাবে।
        </div>
    </AdminLayout>
</template>
