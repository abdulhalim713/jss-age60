<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, watch, computed } from 'vue';

const props = defineProps({
    alumni: Object,   // paginated alumni list
    batches: Array,   // distinct batches for filter
    filters: Object,  // current search filters
});

// ---- Search filters ----
const filterBatch  = ref(props.filters.batch  || '');
const filterSearch = ref(props.filters.search || '');

let debounce = null;
const applyFilters = () => {
    router.get(route('admin.batch-representatives.create'), {
        batch:  filterBatch.value,
        search: filterSearch.value,
    }, { preserveState: true, replace: true, preserveScroll: true });
};

watch([filterBatch, filterSearch], () => {
    clearTimeout(debounce);
    debounce = setTimeout(applyFilters, 350);
});

// ---- Bulk Selection ----
const selectedAlumniList = ref([]);

const toggleSelection = (alumnus) => {
    const index = selectedAlumniList.value.findIndex(a => a.id === alumnus.id);
    if (index > -1) {
        selectedAlumniList.value.splice(index, 1);
    } else {
        selectedAlumniList.value.push(alumnus);
    }
};

const isSelected = (id) => {
    return selectedAlumniList.value.some(a => a.id === id);
};

const removeSelected = (id) => {
    selectedAlumniList.value = selectedAlumniList.value.filter(a => a.id !== id);
};

const clearSelection = () => {
    selectedAlumniList.value = [];
};

const form = useForm({
    alumni_ids: [],
});

const submitBulk = () => {
    form.alumni_ids = selectedAlumniList.value.map(a => a.id);
    form.post(route('admin.batch-representatives.store'), {
        onSuccess: () => {
            selectedAlumniList.value = [];
        }
    });
};
</script>

<template>
    <Head title="নতুন ব্যাচ প্রতিনিধি যোগ" />
    <AdminLayout>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-users text-success me-2"></i> একাধিক প্রতিনিধি একসাথে যোগ
            </h4>
            <Link :href="route('admin.batch-representatives.index')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরুন
            </Link>
        </div>

        <!-- Info Note -->
        <div class="alert alert-info border-0 rounded-3 mb-4 small">
            <i class="fas fa-info-circle me-2"></i>
            <strong>টিপস:</strong> বাম দিকের তালিকা থেকে এক বা একাধিক অ্যালামনাই সিলেক্ট করুন। ডান দিকে নির্বাচিতদের তালিকা দেখা যাবে। একসাথে "সংরক্ষণ করুন" ক্লিক করলে সবাই প্রতিনিধি হিসেবে যুক্ত হবে। পরবর্তীতে তাদের ছবি বা তথ্য পরিবর্তন করতে হলে মূল তালিকা থেকে 'সম্পাদনা' করুন।
        </div>

        <div class="row g-4">
            <!-- ======= LEFT: Alumni Search Panel ======= -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-search me-2"></i> অ্যালামনাই নির্বাচন করুন
                    </div>
                    <div class="card-body p-4">
                        <!-- Search filters -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-5">
                                <select v-model="filterBatch" class="form-select rounded-pill">
                                    <option value="">সকল ব্যাচ</option>
                                    <option v-for="b in batches" :key="b" :value="b">ব্যাচ {{ b }}</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input
                                        v-model="filterSearch"
                                        type="search"
                                        class="form-control border-start-0 rounded-end-pill"
                                        placeholder="নাম বা মোবাইল দিয়ে খুঁজুন..."
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Alumni table -->
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-hover align-middle mb-0" style="font-size:0.93rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3" style="width:50px;">
                                            <i class="fas fa-check-square text-muted"></i>
                                        </th>
                                        <th>নাম</th>
                                        <th>ব্যাচ</th>
                                        <th>মোবাইল</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="alumni.data.length === 0">
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-search mb-2 d-block fs-4 opacity-50"></i>
                                            কোনো অ্যালামনাই পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                    <tr
                                        v-for="a in alumni.data"
                                        :key="a.id"
                                        :class="{ 'table-success': isSelected(a.id) }"
                                        @click="toggleSelection(a)"
                                        style="cursor: pointer;"
                                    >
                                        <td class="ps-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" :checked="isSelected(a.id)" @click.stop="toggleSelection(a)" style="cursor: pointer;">
                                            </div>
                                        </td>
                                        <td class="fw-semibold">{{ a.name }}</td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border">{{ a.batch }}</span>
                                        </td>
                                        <td class="text-muted small">{{ a.phone || '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="alumni.last_page > 1" class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">পৃষ্ঠা {{ alumni.current_page }} / {{ alumni.last_page }}</small>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li v-for="(link, k) in alumni.links" :key="k"
                                        class="page-item"
                                        :class="{ 'active': link.active, 'disabled': !link.url }"
                                    >
                                        <Link v-if="link.url" :href="link.url" class="page-link" preserve-scroll v-html="link.label" />
                                        <span v-else class="page-link" v-html="link.label"></span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======= RIGHT: Selected Panel ======= -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top:80px;">
                    <div class="card-header bg-success text-white rounded-top-4 py-3 px-4 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-clipboard-check me-2"></i> নির্বাচিত অ্যালামনাই</span>
                        <span class="badge bg-light text-success">{{ selectedAlumniList.length }} জন</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column" style="max-height: 600px;">
                        
                        <div v-if="selectedAlumniList.length === 0" class="text-center text-muted py-5 my-auto">
                            <i class="fas fa-hand-pointer fs-1 mb-3 opacity-25"></i>
                            <p>বাম দিকের তালিকা থেকে<br>প্রতিনিধি নির্বাচন করুন।</p>
                        </div>
                        
                        <div v-else class="flex-grow-1 overflow-auto pe-2 mb-4">
                            <div class="d-flex justify-content-end mb-2">
                                <button class="btn btn-sm btn-link text-danger text-decoration-none p-0" @click="clearSelection">
                                    <i class="fas fa-trash-alt me-1"></i> সব মুছুন
                                </button>
                            </div>
                            
                            <ul class="list-group list-group-flush border-top border-bottom">
                                <li v-for="sel in selectedAlumniList" :key="sel.id" class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ sel.name }}</div>
                                        <div class="small text-muted">
                                            <span class="badge bg-light text-dark border me-1">ব্যাচ {{ sel.batch }}</span>
                                            <i class="fas fa-phone-alt ms-1 me-1 text-secondary" style="font-size:10px;"></i>{{ sel.phone || '—' }}
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger border-0" @click="removeSelected(sel.id)" title="তালিকা থেকে বাদ দিন">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Submit -->
                        <div class="mt-auto pt-3 border-top" v-if="selectedAlumniList.length > 0">
                            <button
                                @click="submitBulk"
                                class="btn btn-success fw-bold py-3 w-100 rounded-pill shadow-sm"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">
                                    <i class="fas fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে...
                                </span>
                                <span v-else>
                                    <i class="fas fa-save me-1"></i> {{ selectedAlumniList.length }} জনকে সংরক্ষণ করুন
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
