<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    alumni: Object,
    filters: Object,
    batches: Array
});

// Filters state
const search = ref(props.filters.search || '');
const batch = ref(props.filters.batch || '');
const status = ref(props.filters.status || '');

// Modal state
const showEditModal = ref(false);
const currentAlumnus = ref(null);

// Forms
const editForm = useForm({
    name: '',
    batch: '',
    phone: '',
    email: '',
    address: '',
    status: 'pending'
});

const statusForm = useForm({
    status: ''
});

// Watch filters to trigger router get requests with debounce
let debounceTimer = null;
const applyFilters = () => {
    router.get(route('admin.alumni.index'), {
        search: search.value,
        batch: batch.value,
        status: status.value
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

watch([batch, status], () => {
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
    statusForm.patch(route('admin.alumni.status', id), {
        preserveScroll: true
    });
};

const openEditModal = (alumnus) => {
    currentAlumnus.value = alumnus;
    editForm.name = alumnus.name;
    editForm.batch = alumnus.batch;
    editForm.phone = alumnus.phone;
    editForm.email = alumnus.email || '';
    editForm.address = alumnus.address || '';
    editForm.status = alumnus.status;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    currentAlumnus.value = null;
    editForm.reset();
};

const submitEdit = () => {
    editForm.put(route('admin.alumni.update', currentAlumnus.value.id), {
        onSuccess: () => closeEditModal(),
        preserveScroll: true
    });
};

const deleteAlumnus = (id) => {
    if (confirm('আপনি কি নিশ্চিত যে এই নিবন্ধনটি মুছে ফেলতে চান?')) {
        router.delete(route('admin.alumni.destroy', id), {
            preserveScroll: true
        });
    }
};

const clearFilters = () => {
    search.value = '';
    batch.value = '';
    status.value = '';
};
</script>

<template>
    <Head title="অ্যালামনাই তালিকা" />

    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">নিবন্ধিত অ্যালামনাই তালিকা</h1>
                <p class="text-muted mb-0">পুনর্মিলনীতে অংশ নেওয়া শিক্ষার্থীদের তালিকা ও অনুমোদন ব্যবস্থা</p>
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
                    <div class="col-12 col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input 
                                v-model="search" 
                                type="text" 
                                class="form-control border-start-0" 
                                placeholder="নাম, মোবাইল অথবা ইমেইল দিয়ে খুঁজুন..."
                            />
                        </div>
                    </div>
                    
                    <!-- Batch Filter -->
                    <div class="col-6 col-md-3">
                        <select v-model="batch" class="form-select">
                            <option value="">সকল ব্যাচ</option>
                            <option v-for="b in batches" :key="b" :value="b">ব্যাচ {{ b }}</option>
                        </select>
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
                    <div class="col-12 col-md-2 d-grid">
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
                                <th class="px-4 py-3">নাম ও ব্যাচ</th>
                                <th class="py-3">মোবাইল নম্বর</th>
                                <th class="py-3">ইমেইল</th>
                                <th class="py-3">বর্তমান ঠিকানা</th>
                                <th class="py-3">স্ট্যাটাস</th>
                                <th class="px-4 py-3 text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="alumnus in alumni.data" :key="alumnus.id">
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar bg-light text-secondary rounded-circle p-2 d-flex justify-content-center align-items-center" style="width: 38px; height: 38px;">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ alumnus.name }}</div>
                                            <small class="badge bg-secondary-subtle text-secondary-emphasis">ব্যাচ: {{ alumnus.batch }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 font-monospace">
                                    {{ alumnus.phone }}
                                </td>
                                <td class="py-3 text-muted">
                                    {{ alumnus.email || '—' }}
                                </td>
                                <td class="py-3 text-truncate" style="max-width: 200px;" :title="alumnus.address">
                                    {{ alumnus.address || '—' }}
                                </td>
                                <td class="py-3">
                                    <span class="badge rounded-pill px-2.5 py-1" :class="{
                                        'bg-success bg-opacity-10 text-success border border-success border-opacity-25': alumnus.status === 'approved',
                                        'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25': alumnus.status === 'pending',
                                        'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25': alumnus.status === 'rejected',
                                    }">
                                        {{ alumnus.status === 'approved' ? 'অনুমোদিত' : (alumnus.status === 'pending' ? 'পেন্ডিং' : 'বাতিল') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-1.5 flex-wrap">
                                        <!-- Quick Toggle Action -->
                                        <button 
                                            v-if="alumnus.status !== 'approved'"
                                            @click="toggleStatus(alumnus.id, 'approved')" 
                                            class="btn btn-sm btn-success px-2 py-1"
                                            title="অনুমোদন করুন"
                                        >
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button 
                                            v-if="alumnus.status !== 'rejected'"
                                            @click="toggleStatus(alumnus.id, 'rejected')" 
                                            class="btn btn-sm btn-warning px-2 py-1 text-white"
                                            title="বাতিল করুন"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <!-- Edit Action -->
                                        <button 
                                            @click="openEditModal(alumnus)" 
                                            class="btn btn-sm btn-outline-primary px-2 py-1"
                                            title="সম্পাদনা করুন"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Delete Action -->
                                        <button 
                                            @click="deleteAlumnus(alumnus.id)" 
                                            class="btn btn-sm btn-outline-danger px-2 py-1"
                                            title="মুছে ফেলুন"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="alumni.data.length === 0">
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-search-minus fs-1 mb-2 d-block text-secondary"></i>
                                    কোনো নিবন্ধিত অ্যালামনাই খুঁজে পাওয়া যায়নি।
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div class="card-footer bg-white border-top py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="small text-muted">
                        মোট {{ alumni.total }} জনের মধ্যে {{ alumni.from || 0 }}-{{ alumni.to || 0 }} দেখানো হচ্ছে
                    </div>
                    <nav v-if="alumni.links.length > 3">
                        <ul class="pagination pagination-sm mb-0">
                            <li 
                                v-for="(link, k) in alumni.links" 
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

        <!-- Custom Edit Modal -->
        <div v-if="showEditModal" class="modal fade show" style="display: block; background: rgba(0,0,0,0.5); z-index: 1050;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-user-edit me-1"></i> অ্যালামনাই তথ্য সংশোধন
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="closeEditModal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="submitEdit">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                                <input v-model="editForm.name" type="text" class="form-control" required />
                                <div v-if="editForm.errors.name" class="text-danger small mt-1">{{ editForm.errors.name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">ব্যাচ <span class="text-danger">*</span></label>
                                    <input v-model="editForm.batch" type="text" class="form-control" required />
                                    <div v-if="editForm.errors.batch" class="text-danger small mt-1">{{ editForm.errors.batch }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input v-model="editForm.phone" type="text" class="form-control" required />
                                    <div v-if="editForm.errors.phone" class="text-danger small mt-1">{{ editForm.errors.phone }}</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">ইমেইল ঠিকানা</label>
                                <input v-model="editForm.email" type="email" class="form-control" />
                                <div v-if="editForm.errors.email" class="text-danger small mt-1">{{ editForm.errors.email }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">বর্তমান ঠিকানা</label>
                                <textarea v-model="editForm.address" class="form-control" rows="2.5"></textarea>
                                <div v-if="editForm.errors.address" class="text-danger small mt-1">{{ editForm.errors.address }}</div>
                            </div>
                            <div class="mb-1">
                                <label class="form-label fw-semibold">স্ট্যাটাস</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="pending" v-model="editForm.status" id="statusPending" />
                                        <label class="form-check-label" for="statusPending">পেন্ডিং</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="approved" v-model="editForm.status" id="statusApproved" />
                                        <label class="form-check-label text-success fw-semibold" for="statusApproved">অনুমোদিত</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="rejected" v-model="editForm.status" id="statusRejected" />
                                        <label class="form-check-label text-danger fw-semibold" for="statusRejected">বাতিল</label>
                                    </div>
                                </div>
                                <div v-if="editForm.errors.status" class="text-danger small mt-1">{{ editForm.errors.status }}</div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" @click="closeEditModal">বাতিল</button>
                            <button type="submit" class="btn btn-primary" :disabled="editForm.processing">
                                <i class="fas fa-save me-1"></i> সংরক্ষণ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.avatar {
    width: 38px;
    height: 38px;
}
.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}
</style>
