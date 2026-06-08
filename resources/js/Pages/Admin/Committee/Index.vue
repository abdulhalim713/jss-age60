<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    members: Array,
});

const deleteMember = (id, name) => {
    if (confirm(`"${name}" কে মুছে ফেলতে চান? এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।`)) {
        router.delete(route('admin.committee.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="কমিটি ব্যবস্থাপনা" />
    <AdminLayout>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-users-gear text-warning me-2"></i> হীরক জয়ন্তী কমিটি ব্যবস্থাপনা
            </h4>
            <Link :href="route('admin.committee.create')" class="btn btn-warning fw-semibold">
                <i class="fas fa-plus me-1"></i> নতুন সদস্য যোগ
            </Link>
        </div>

        <!-- Flash Message -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Members Table -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4 d-flex align-items-center justify-content-between">
                <span><i class="fas fa-list me-2"></i> কমিটি সদস্যের তালিকা</span>
                <span class="badge bg-warning text-dark">মোট: {{ members.length }} জন</span>
            </div>
            <div class="card-body p-0">
                <div v-if="members.length === 0" class="text-center py-5 text-muted">
                    <i class="fas fa-users fa-3x mb-3 d-block text-secondary"></i>
                    <p>কোনো কমিটি সদস্য পাওয়া যায়নি।</p>
                    <Link :href="route('admin.committee.create')" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus me-1"></i> প্রথম সদস্য যোগ করুন
                    </Link>
                </div>
                <div v-else class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width:60px;">ক্রম</th>
                                <th style="width:80px;">ছবি</th>
                                <th>নাম</th>
                                <th>পদবি / ভূমিকা</th>
                                <th>ব্যাচ</th>
                                <th>মোবাইল নম্বর</th>
                                <th>ঠিকানা</th>
                                <th class="text-center" style="width:140px;">কার্যক্রম</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="member in members" :key="member.id">
                                <td class="ps-4">
                                    <span class="badge bg-secondary rounded-pill">{{ member.sort_order }}</span>
                                </td>
                                <td>
                                    <img 
                                        v-if="member.image" 
                                        :src="`/storage/${member.image}`" 
                                        class="rounded-circle border"
                                        style="width:42px;height:42px;object-fit:cover;"
                                    />
                                    <div v-else class="icon-preview d-flex align-items-center justify-content-center rounded-circle bg-warning-subtle"
                                         style="width:42px;height:42px;">
                                        <i :class="member.icon + ' text-warning fs-5'"></i>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ member.name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ member.role }}</span>
                                </td>
                                <td>
                                    <span v-if="member.batch" class="badge bg-primary-subtle text-primary border">
                                        <i class="fas fa-graduation-cap me-1"></i>{{ member.batch }}
                                    </span>
                                    <span v-else class="text-muted small">—</span>
                                </td>
                                <td>
                                    <span>{{ member.mobile || '—' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small d-inline-block text-truncate" style="max-width: 180px;">
                                        {{ member.address || '—' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <Link
                                        :href="route('admin.committee.edit', member.id)"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="সম্পাদনা"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        title="মুছুন"
                                        @click="deleteMember(member.id, member.name)"
                                    >
                                        <i class="fas fa-trash"></i>
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
            <strong>টিপস:</strong> "ক্রম" নম্বর ছোট হলে সদস্যটি সাইটে আগে দেখাবে।
        </div>
    </AdminLayout>
</template>
