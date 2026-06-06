<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    feedbacks: Object,
    filters: Object
});

// Filters state
const search = ref(props.filters.search || '');
const type = ref(props.filters.type || '');

// Modal state for viewing details
const showViewModal = ref(false);
const activeFeedback = ref(null);

// Watch filters to trigger router get requests with debounce
let debounceTimer = null;
const applyFilters = () => {
    router.get(route('admin.feedback.index'), {
        search: search.value,
        type: type.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

watch(type, () => {
    applyFilters();
});

watch(search, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        applyFilters();
    }, 400);
});

const clearFilters = () => {
    search.value = '';
    type.value = '';
};

const openViewModal = (feedback) => {
    activeFeedback.value = feedback;
    showViewModal.value = true;
};

const closeViewModal = () => {
    showViewModal.value = false;
    activeFeedback.value = null;
};

const deleteFeedback = (id) => {
    if (confirm('আপনি কি নিশ্চিত যে এই বার্তাটি মুছে ফেলতে চান?')) {
        router.delete(route('admin.feedback.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                if (showViewModal.value && activeFeedback.value?.id === id) {
                    closeViewModal();
                }
            }
        });
    }
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
</script>

<template>
    <Head title="অভিযোগ ও পরামর্শ তালিকা" />

    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">অভিযোগ ও পরামর্শ তালিকা</h1>
                <p class="text-muted mb-0">ব্যবহারকারী ও প্রাক্তন শিক্ষার্থীদের পাঠানো বার্তা ও অভিযোগ সমূহ</p>
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
                                placeholder="নাম, যোগাযোগ অথবা বার্তার অংশ দিয়ে খুঁজুন..."
                            />
                        </div>
                    </div>
                    
                    <!-- Type Filter -->
                    <div class="col-6 col-md-3">
                        <select v-model="type" class="form-select">
                            <option value="">সকল বার্তা</option>
                            <option value="suggestion">পরামর্শ</option>
                            <option value="complaint">অভিযোগ</option>
                        </select>
                    </div>

                    <!-- Reset Filters Button -->
                    <div class="col-6 col-md-3 d-grid">
                        <button @click="clearFilters" class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-undo me-1"></i> রিসেট ফিল্টার
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
                                <th class="px-4 py-3" style="width: 20%;">প্রেরক</th>
                                <th class="py-3" style="width: 20%;">যোগাযোগ (মোবাইল/ইমেইল)</th>
                                <th class="py-3" style="width: 15%;">ধরণ</th>
                                <th class="py-3" style="width: 25%;">বার্তা</th>
                                <th class="py-3" style="width: 10%;">সময়</th>
                                <th class="px-4 py-3 text-end" style="width: 10%;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="feedback in feedbacks.data" :key="feedback.id">
                                <td class="px-4 py-3 fw-bold text-dark">
                                    {{ feedback.name }}
                                </td>
                                <td class="py-3">
                                    {{ feedback.contact }}
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1" :class="{
                                        'bg-success bg-opacity-10 text-success border border-success border-opacity-25': feedback.type === 'suggestion',
                                        'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25': feedback.type === 'complaint',
                                    }">
                                        {{ feedback.type === 'suggestion' ? 'পরামর্শ' : 'অভিযোগ' }}
                                    </span>
                                </td>
                                <td class="py-3 text-truncate" style="max-width: 250px;" :title="feedback.message">
                                    {{ feedback.message }}
                                </td>
                                <td class="py-3 text-muted small">
                                    {{ formatDate(feedback.created_at) }}
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- View Details Action -->
                                        <button 
                                            @click="openViewModal(feedback)" 
                                            class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                            title="বিস্তারিত দেখুন"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <!-- Delete Action -->
                                        <button 
                                            @click="deleteFeedback(feedback.id)" 
                                            class="btn btn-sm btn-outline-danger px-2.5 py-1"
                                            title="মুছে ফেলুন"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="feedbacks.data.length === 0">
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-comment-slash fs-1 mb-2 d-block text-secondary"></i>
                                    কোনো বার্তা খুঁজে পাওয়া যায়নি।
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div class="card-footer bg-white border-top py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="small text-muted">
                        মোট {{ feedbacks.total }}টি বার্তার মধ্যে {{ feedbacks.from || 0 }}-{{ feedbacks.to || 0 }} দেখানো হচ্ছে
                    </div>
                    <nav v-if="feedbacks.links.length > 3">
                        <ul class="pagination pagination-sm mb-0">
                            <li 
                                v-for="(link, k) in feedbacks.links" 
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

        <!-- Custom Details Modal -->
        <div v-if="showViewModal" class="modal fade show" style="display: block; background: rgba(0,0,0,0.5); z-index: 1050;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header" :class="activeFeedback.type === 'suggestion' ? 'bg-success text-white' : 'bg-danger text-white'">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-comment-dots me-1"></i> বার্তা বিস্তারিত
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="closeViewModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <span class="text-muted d-block small">প্রেরকের নাম</span>
                                <strong class="fs-5 text-dark">{{ activeFeedback.name }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block small">যোগাযোগের নম্বর/ইমেইল</span>
                                <strong class="fs-5 text-dark">{{ activeFeedback.contact }}</strong>
                            </div>
                        </div>

                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <span class="text-muted d-block small">বার্তা ধরণ</span>
                                <span class="badge rounded-pill px-3 py-1.5 mt-1" :class="activeFeedback.type === 'suggestion' ? 'bg-success text-white' : 'bg-danger text-white'">
                                    {{ activeFeedback.type === 'suggestion' ? 'পরামর্শ' : 'অভিযোগ' }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted d-block small">প্রাপ্তির সময়</span>
                                <span class="text-dark fw-semibold">{{ formatDate(activeFeedback.created_at) }}</span>
                            </div>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted d-block small mb-1">প্রেরিত বার্তা</span>
                            <div class="p-3 bg-light rounded-3 text-dark border" style="white-space: pre-line; max-height: 350px; overflow-y: auto;">
                                {{ activeFeedback.message }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-4" @click="closeViewModal">বন্ধ করুন</button>
                        <button type="button" class="btn btn-danger px-4" @click="deleteFeedback(activeFeedback.id)">
                            <i class="fas fa-trash-alt me-1"></i> মুছে ফেলুন
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}
</style>
