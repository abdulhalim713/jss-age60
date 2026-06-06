<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    items: Array,
});

const deleteItem = (id, title) => {
    const displayName = title || `মিডিয়া #${id}`;
    if (confirm(`"${displayName}" গ্যালারি আইটেমটি মুছে ফেলতে চান?`)) {
        router.delete(route('admin.gallery.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="গ্যালারি ব্যবস্থাপনা" />
    <AdminLayout>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-images text-warning me-2"></i> স্মৃতি গ্যালারি ব্যবস্থাপনা
            </h4>
            <Link :href="route('admin.gallery.create')" class="btn btn-warning fw-semibold">
                <i class="fas fa-plus me-1"></i> নতুন মিডিয়া যোগ
            </Link>
        </div>

        <!-- Flash Message -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Gallery Table -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4 d-flex align-items-center justify-content-between">
                <span><i class="fas fa-photo-film me-2"></i> মিডিয়া গ্যালারি তালিকা</span>
                <span class="badge bg-warning text-dark">মোট: {{ items.length }} টি</span>
            </div>
            <div class="card-body p-0">
                <div v-if="items.length === 0" class="text-center py-5 text-muted">
                    <i class="fas fa-images fa-3x mb-3 d-block text-secondary"></i>
                    <p>কোনো গ্যালারি আইটেম পাওয়া যায়নি।</p>
                    <Link :href="route('admin.gallery.create')" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus me-1"></i> প্রথম মিডিয়া যোগ করুন
                    </Link>
                </div>
                <div v-else class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width:80px;">ক্রম</th>
                                <th style="width:120px;">প্রিভিউ</th>
                                <th>ধরণ</th>
                                <th>শিরোনাম</th>
                                <th>ভিডিও লিংক / ফাইল পাথ</th>
                                <th class="text-center" style="width:140px;">কার্যক্রম</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items" :key="item.id">
                                <td class="ps-4">
                                    <span class="badge bg-secondary rounded-pill">{{ item.sort_order }}</span>
                                </td>
                                <td>
                                    <!-- Image Thumbnail -->
                                    <img 
                                        v-if="item.type === 'image' && item.file_path" 
                                        :src="`/storage/${item.file_path}`" 
                                        class="img-thumbnail rounded"
                                        style="width: 80px; height: 50px; object-fit: cover;"
                                    />
                                    <!-- Video Embed / Placeholder -->
                                    <div 
                                        v-else-if="item.type === 'video' && item.video_url" 
                                        class="d-flex align-items-center justify-content-center bg-danger text-white rounded position-relative"
                                        style="width: 80px; height: 50px;"
                                    >
                                        <i class="fab fa-youtube fs-4"></i>
                                    </div>
                                    <div 
                                        v-else 
                                        class="d-flex align-items-center justify-content-center bg-secondary text-white rounded"
                                        style="width: 80px; height: 50px; font-size: 0.7rem;"
                                    >
                                        নো মিডিয়া
                                    </div>
                                </td>
                                <td>
                                    <span 
                                        class="badge px-3 py-1 rounded-pill"
                                        :class="item.type === 'image' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle'"
                                    >
                                        <i :class="item.type === 'image' ? 'fas fa-image me-1' : 'fas fa-video me-1'"></i>
                                        {{ item.type === 'image' ? 'ছবি' : 'ভিডিও' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ item.title || 'শিরোনামহীন' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small d-inline-block text-truncate" style="max-width: 280px;">
                                        {{ item.type === 'image' ? item.file_path : item.video_url }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <Link
                                        :href="route('admin.gallery.edit', item.id)"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="সম্পাদনা"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        title="মুছুন"
                                        @click="deleteItem(item.id, item.title)"
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
            <strong>টিপস:</strong> প্রদর্শন ক্রমানুযায়ী ছোট সংখ্যার মিডিয়াটি ল্যান্ডিং পেজে আগে দেখা যাবে। ছবিগুলো লাইটবক্স আকারে এবং ভিডিওগুলো ইউটিউব প্লেয়ার হিসেবে লোড হবে।
        </div>
    </AdminLayout>
</template>
