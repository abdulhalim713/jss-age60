<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    items: Array,
});

const deleteItem = (id, name) => {
    const displayName = name || `স্পন্সর #${id}`;
    if (confirm(`"${displayName}" সহযোগী/স্পন্সর আইটেমটি মুছে ফেলতে চান?`)) {
        router.delete(route('admin.sponsors.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="সহযোগী ও স্পন্সর ব্যবস্থাপনা" />
    <AdminLayout>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-hand-holding-usd text-warning me-2"></i> সহযোগী ও স্পন্সর ব্যবস্থাপনা
            </h4>
            <Link :href="route('admin.sponsors.create')" class="btn btn-warning fw-semibold">
                <i class="fas fa-plus me-1"></i> নতুন স্পন্সর যোগ
            </Link>
        </div>

        <!-- Flash Message -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Sponsors Table -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4 d-flex align-items-center justify-content-between">
                <span><i class="fas fa-handshake me-2"></i> সহযোগী ও স্পন্সর তালিকা</span>
                <span class="badge bg-warning text-dark">মোট: {{ items.length }} টি</span>
            </div>
            <div class="card-body p-0">
                <div v-if="items.length === 0" class="text-center py-5 text-muted">
                    <i class="fas fa-hand-holding-dollar fa-3x mb-3 d-block text-secondary"></i>
                    <p>কোনো সহযোগী বা স্পন্সর পাওয়া যায়নি।</p>
                    <Link :href="route('admin.sponsors.create')" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus me-1"></i> প্রথম স্পন্সর যোগ করুন
                    </Link>
                </div>
                <div v-else class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width:80px;">ক্রম</th>
                                <th style="width:150px;">লোগো</th>
                                <th>প্রতিষ্ঠানের নাম</th>
                                <th>ওয়েবসাইট লিংক</th>
                                <th class="text-center" style="width:140px;">কার্যক্রম</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td class="ps-4">
                                    <span class="badge bg-secondary rounded-pill">{{ item.sort_order }}</span>
                                </td>
                                <td>
                                    <img 
                                        v-if="item.logo" 
                                        :src="`/storage/${item.logo}`" 
                                        class="img-thumbnail rounded bg-light p-1"
                                        style="max-width: 120px; max-height: 50px; object-fit: contain;"
                                    />
                                    <div 
                                        v-else 
                                        class="d-flex align-items-center justify-content-center bg-secondary text-white rounded"
                                        style="width: 120px; height: 50px; font-size: 0.7rem;"
                                    >
                                        নো লোগো
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ item.name }}</span>
                                </td>
                                <td>
                                    <a v-if="item.link" :href="item.link" target="_blank" class="text-decoration-none text-truncate d-inline-block" style="max-width: 250px;">
                                        {{ item.link }} <i class="fas fa-external-link-alt ms-1 small"></i>
                                    </a>
                                    <span v-else class="text-muted small">নেই</span>
                                </td>
                                <td class="text-center">
                                    <Link
                                        :href="route('admin.sponsors.edit', item.id)"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="সম্পাদনা"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        title="মুছুন"
                                        @click="deleteItem(item.id, item.name)"
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

        <!-- Tips -->
        <div class="alert alert-info border-0 rounded-3 mt-3 small">
            <i class="fas fa-info-circle me-2"></i>
            <strong>টিপস:</strong> ছোট প্রদর্শন ক্রম অনুযায়ী স্পন্সরদের লোগো ল্যান্ডিং পেজে আগে দেখা যাবে। লোগোর সাইজ সাধারণত অনুভূমিক (Landscape) হওয়া বাঞ্ছনীয়।
        </div>
    </AdminLayout>
</template>
