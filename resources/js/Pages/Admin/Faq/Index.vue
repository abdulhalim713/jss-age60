<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    faqs: Array,
});

const deletingId = ref(null);

const confirmDelete = (faq) => {
    if (confirm(`"${faq.question}" — এই প্রশ্নটি মুছে ফেলবেন?`)) {
        deletingId.value = faq.id;
        router.delete(route('admin.faq.destroy', faq.id), {
            onFinish: () => { deletingId.value = null; },
        });
    }
};

const toggleActive = (faq) => {
    router.patch(route('admin.faq.update', faq.id), {
        ...faq,
        is_active: !faq.is_active,
    }, { preserveScroll: true });
};
</script>

<template>
    <Head title="FAQ ব্যবস্থাপনা" />
    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-question-circle text-warning me-2"></i>
                সচরাচর জিজ্ঞাসা (FAQ) ব্যবস্থাপনা
            </h4>
            <Link :href="route('admin.faq.create')" class="btn btn-warning fw-semibold">
                <i class="fas fa-plus me-1"></i> নতুন প্রশ্ন যুক্ত করুন
            </Link>
        </div>

        <!-- Flash Message -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible rounded-3 mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                <i class="fas fa-list me-2"></i> প্রশ্নসমূহের তালিকা
                <span class="badge bg-warning text-dark ms-2">{{ faqs.length }}</span>
            </div>
            <div class="card-body p-0">
                <div v-if="faqs.length === 0" class="text-center text-muted py-5">
                    <i class="fas fa-question-circle fa-3x mb-3 opacity-25"></i>
                    <p>এখনো কোনো প্রশ্ন যুক্ত করা হয়নি।</p>
                </div>
                <div v-else class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width:50px;">ক্রম</th>
                                <th>প্রশ্ন</th>
                                <th style="width:120px;">অবস্থা</th>
                                <th class="text-center" style="width:140px;">কার্যক্রম</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="faq in faqs" :key="faq.id">
                                <td class="ps-4 text-muted">{{ faq.sort_order }}</td>
                                <td>
                                    <div class="fw-semibold">{{ faq.question }}</div>
                                    <small class="text-muted">{{ faq.answer.substring(0, 80) }}...</small>
                                </td>
                                <td>
                                    <span
                                        class="badge px-3 py-2 rounded-pill"
                                        :class="faq.is_active ? 'bg-success' : 'bg-secondary'"
                                        style="cursor:pointer;"
                                        @click="toggleActive(faq)"
                                        title="ক্লিক করে পরিবর্তন করুন"
                                    >
                                        <i :class="faq.is_active ? 'fas fa-eye' : 'fas fa-eye-slash'" class="me-1"></i>
                                        {{ faq.is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <Link
                                        :href="route('admin.faq.edit', faq.id)"
                                        class="btn btn-sm btn-outline-primary me-1"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        @click="confirmDelete(faq)"
                                        :disabled="deletingId === faq.id"
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
    </AdminLayout>
</template>
