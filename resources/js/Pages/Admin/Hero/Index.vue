<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    heroes: Array,
});

const deleteHero = (id, title) => {
    if (confirm(`"${title}" স্লাইডটি মুছে ফেলতে চান?`)) {
        router.delete(route('admin.hero.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="হিরো স্লাইড সমূহ" />
    <AdminLayout>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-image text-warning me-2"></i> হিরো স্লাইড সমূহ
            </h4>
            <Link :href="route('admin.hero.create')" class="btn btn-warning fw-semibold">
                <i class="fas fa-plus me-1"></i> নতুন স্লাইড যোগ
            </Link>
        </div>

        <!-- Flash Message -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <!-- Heroes Table -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4 d-flex align-items-center justify-content-between">
                <span><i class="fas fa-list me-2"></i> স্লাইডের তালিকা</span>
                <span class="badge bg-warning text-dark">মোট: {{ heroes.length }} টি</span>
            </div>
            <div class="card-body p-0">
                <div v-if="heroes.length === 0" class="text-center py-5 text-muted">
                    <i class="fas fa-image fa-3x mb-3 d-block text-secondary"></i>
                    <p>কোনো হিরো স্লাইড পাওয়া যায়নি।</p>
                    <Link :href="route('admin.hero.create')" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus me-1"></i> প্রথম স্লাইড যোগ করুন
                    </Link>
                </div>
                <div v-else class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width:80px;">ক্রম</th>
                                <th style="width:120px;">ছবি</th>
                                <th>শিরোনাম</th>
                                <th>উপশিরোনাম</th>
                                <th class="text-center" style="width:160px;">কার্যক্রম</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="hero in heroes" :key="hero.id">
                                <td class="ps-4">
                                    <span class="badge bg-secondary rounded-pill">{{ hero.sort_order }}</span>
                                </td>
                                <td>
                                    <img 
                                        v-if="hero.bg_image" 
                                        :src="`/storage/${hero.bg_image}`" 
                                        class="img-thumbnail rounded"
                                        style="width: 80px; height: 50px; object-fit: cover;"
                                    />
                                    <div 
                                        v-else 
                                        class="d-flex align-items-center justify-content-center bg-secondary text-white rounded"
                                        style="width: 80px; height: 50px; font-size: 0.7rem;"
                                    >
                                        নো ইমেজ
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ hero.title }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small d-inline-block text-truncate" style="max-width: 250px;">
                                        {{ hero.subtitle }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <Link
                                        :href="route('admin.hero.edit', hero.id)"
                                        class="btn btn-sm btn-outline-primary me-1"
                                        title="সম্পাদনা"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </Link>
                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        title="মুছুন"
                                        @click="deleteHero(hero.id, hero.title)"
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
    </AdminLayout>
</template>
