<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    essays: Object,
    filters: Object,
});

// Filters state
const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

// Forms
const statusForm = useForm({
    status: ''
});

// Watch filters to trigger router get requests with debounce
let debounceTimer = null;
const applyFilters = () => {
    router.get(route('admin.essays.index'), {
        search: search.value,
        status: status.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

watch(status, () => {
    applyFilters();
});

watch(search, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        applyFilters();
    }, 400);
});

// Actions
const toggleStatus = (id, newStatus) => {
    statusForm.status = newStatus;
    statusForm.patch(route('admin.essays.status', id), {
        preserveScroll: true
    });
};

const deleteEssay = (id) => {
    if (confirm('আপনি কি নিশ্চিত যে এই প্রবন্ধটি মুছে ফেলতে চান?')) {
        router.delete(route('admin.essays.destroy', id), {
            preserveScroll: true
        });
    }
};

const clearFilters = () => {
    search.value = '';
    status.value = '';
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('bn-BD', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<template>
    <Head title="স্মৃতিচারণ প্রবন্ধ তালিকা" />

    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">স্মৃতিচারণ প্রবন্ধ তালিকা</h1>
                <p class="text-muted mb-0">প্রাক্তন শিক্ষার্থীদের পাঠানো প্রবন্ধসমূহ ও অনুমোদন ব্যবস্থা</p>
            </div>
        </div>

        <!-- Alerts -->
        <div v-if="$page.props.flash && $page.props.flash.success" class="alert alert-success alert-dismissible fade show border-0 shadow-xs mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Filter Panel -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="row g-3 align-items-center">
                    <!-- Search Field -->
                    <div class="col-12 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input 
                                v-model="search" 
                                type="text" 
                                class="form-control border-start-0" 
                                placeholder="শিরোনাম, লেখক বা ব্যাচ দিয়ে খুঁজুন..."
                            />
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-6 col-md-3">
                        <select v-model="status" class="form-select">
                            <option value="">সকল স্ট্যাটাস</option>
                            <option value="pending">পেন্ডিং</option>
                            <option value="approved">অনুমোদিত</option>
                            <option value="rejected">বাতিল</option>
                        </select>
                    </div>

                    <!-- Reset Filters Button -->
                    <div class="col-6 col-md-3 d-grid">
                        <button @click="clearFilters" class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-undo me-1"></i> রিসেট
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- List Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">প্রবন্ধের শিরোনাম</th>
                                <th class="py-3">লেখক ও ব্যাচ</th>
                                <th class="py-3">যোগাযোগ</th>
                                <th class="py-3">তারিখ</th>
                                <th class="py-3">স্ট্যাটাস</th>
                                <th class="px-4 py-3 text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="essay in essays.data" :key="essay.id">
                                <td class="px-4 py-3" style="max-width: 250px;">
                                    <div class="fw-bold text-dark text-truncate" :title="essay.title">{{ essay.title }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar bg-light text-secondary rounded-circle p-2 d-flex justify-content-center align-items-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <i class="fas fa-pen-nib"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ essay.author_name }}</div>
                                            <small class="text-muted">ব্যাচ: {{ essay.author_batch }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="font-monospace small">{{ essay.author_phone }}</div>
                                    <div class="text-muted small text-truncate" style="max-width: 150px;">{{ essay.author_email || '—' }}</div>
                                </td>
                                <td class="py-3 text-muted small">
                                    {{ formatDate(essay.created_at) }}
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1" :class="{
                                        'bg-success bg-opacity-10 text-success border border-success border-opacity-25': essay.status === 'approved',
                                        'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25': essay.status === 'pending',
                                        'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25': essay.status === 'rejected',
                                    }">
                                        {{ essay.status === 'approved' ? 'অনুমোদিত' : (essay.status === 'pending' ? 'পেন্ডিং' : 'বাতিল') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-1.5 flex-wrap">
                                        <!-- Quick Toggle Action -->
                                        <button 
                                            v-if="essay.status !== 'approved'"
                                            @click="toggleStatus(essay.id, 'approved')" 
                                            class="btn btn-sm btn-success px-2 py-1"
                                            title="অনুমোদন করুন"
                                        >
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button 
                                            v-if="essay.status !== 'rejected'"
                                            @click="toggleStatus(essay.id, 'rejected')" 
                                            class="btn btn-sm btn-warning px-2 py-1 text-white"
                                            title="বাতিল করুন"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                        
                                        <!-- View Action (Public Route) -->
                                        <a 
                                            :href="route('essays.show', essay.id)" 
                                            target="_blank"
                                            v-if="essay.status === 'approved'"
                                            class="btn btn-sm btn-outline-info px-2 py-1"
                                            title="প্রবন্ধ দেখুন"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Delete Action -->
                                        <button 
                                            @click="deleteEssay(essay.id)" 
                                            class="btn btn-sm btn-outline-danger px-2 py-1"
                                            title="মুছে ফেলুন"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="essays.data.length === 0">
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-search-minus fs-1 mb-2 d-block text-secondary"></i>
                                    কোনো প্রবন্ধ খুঁজে পাওয়া যায়নি।
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div class="card-footer bg-white border-top py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="small text-muted">
                        মোট {{ essays.total }} টির মধ্যে {{ essays.from || 0 }}-{{ essays.to || 0 }} দেখানো হচ্ছে
                    </div>
                    <nav v-if="essays.links.length > 3">
                        <ul class="pagination pagination-sm mb-0">
                            <li 
                                v-for="(link, k) in essays.links" 
                                :key="k" 
                                class="page-item" 
                                :class="{ 'active': link.active, 'disabled': !link.url }"
                            >
                                <Link 
                                    v-if="link.url"
                                    :href="link.url" 
                                    class="page-link" 
                                    v-html="link.label"
                                    preserve-scroll
                                />
                                <span v-else class="page-link" v-html="link.label"></span>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.avatar {
    width: 32px;
    height: 32px;
}
</style>
