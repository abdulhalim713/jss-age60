<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    users: Array,
});

const deleteUser = (id, name) => {
    if (confirm(`"${name}" কে মুছে ফেলতে চান? এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।`)) {
        router.delete(route('admin.users.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="ইউজার ব্যবস্থাপনা" />
    <AdminLayout>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-users-cog text-info me-2"></i> ইউজার ব্যবস্থাপনা
            </h4>
            <Link :href="route('admin.users.create')" class="btn btn-info text-white fw-semibold">
                <i class="fas fa-user-plus me-1"></i> নতুন ইউজার তৈরি
            </Link>
        </div>

        <!-- Flash Messages -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <div v-if="$page.props.flash?.error" class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ $page.props.flash.error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Users Table -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4 d-flex align-items-center justify-content-between">
                <span><i class="fas fa-list me-2"></i> ইউজার তালিকা</span>
                <span class="badge bg-info">মোট: {{ users.length }} জন</span>
            </div>
            <div class="card-body p-0">
                <div v-if="users.length === 0" class="text-center py-5 text-muted">
                    <i class="fas fa-users fa-3x mb-3 d-block text-secondary"></i>
                    <p>কোনো ইউজার পাওয়া যায়নি।</p>
                    <Link :href="route('admin.users.create')" class="btn btn-info btn-sm text-white">
                        <i class="fas fa-user-plus me-1"></i> প্রথম ইউজার তৈরি করুন
                    </Link>
                </div>
                <div v-else class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width:60px;">#</th>
                                <th>নাম</th>
                                <th>ইমেইল</th>
                                <th>তৈরির তারিখ</th>
                                <th class="text-center" style="width:140px;">কার্যক্রম</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td class="ps-4">
                                    <span class="badge bg-secondary rounded-pill">{{ index + 1 }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-info-subtle"
                                             style="width:36px;height:36px;">
                                            <i class="fas fa-user-circle text-info fs-5"></i>
                                        </div>
                                        <span class="fw-semibold">{{ user.name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ user.email }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ new Date(user.created_at).toLocaleDateString('bn-BD') }}</small>
                                </td>
                                <td class="text-center">
                                    <Link
                                        :href="route('admin.users.edit', user.id)"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="সম্পাদনা"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        title="মুছুন"
                                        @click="deleteUser(user.id, user.name)"
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
            <strong>দ্রষ্টব্য:</strong> আপনি নিজের অ্যাকাউন্ট মুছে ফেলতে পারবেন না। পাবলিক রেজিস্ট্রেশন বন্ধ থাকায় শুধু এডমিনই নতুন ইউজার তৈরি করতে পারবেন।
        </div>
    </AdminLayout>
</template>
