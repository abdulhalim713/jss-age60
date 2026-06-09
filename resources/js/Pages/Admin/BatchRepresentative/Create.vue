<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, watch } from 'vue';

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

// ---- Form ----
const form = useForm({
    alumni_id:  null,
    name:       '',
    batch:      '',
    mobile:     '',
    address:    '',
    image:      null,
    sort_order: 0,
    is_active:  true,
});

const imagePreview = ref(null);
const selectedAlumni = ref(null);

const selectAlumni = (alumnus) => {
    selectedAlumni.value = alumnus;
    form.alumni_id = alumnus.id;
    form.name      = alumnus.name    || '';
    form.batch     = alumnus.batch   || '';
    form.mobile    = alumnus.phone   || '';
    form.address   = alumnus.address || '';
};

const clearSelection = () => {
    selectedAlumni.value = null;
    form.alumni_id = null;
    form.name      = '';
    form.batch     = '';
    form.mobile    = '';
    form.address   = '';
};

const onImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('admin.batch-representatives.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="নতুন ব্যাচ প্রতিনিধি যোগ" />
    <AdminLayout>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-plus-circle text-success me-2"></i> নতুন ব্যাচ ভিত্তিক প্রতিনিধি যোগ
            </h4>
            <Link :href="route('admin.batch-representatives.index')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরুন
            </Link>
        </div>

        <div class="row g-4">
            <!-- ======= LEFT: Alumni Search Panel ======= -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-search me-2"></i> অ্যালামনাই তালিকা থেকে অনুসন্ধান ও নির্বাচন করুন
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
                                        <th class="ps-3">নাম</th>
                                        <th>ব্যাচ</th>
                                        <th>মোবাইল</th>
                                        <th class="text-center" style="width:90px;">নির্বাচন</th>
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
                                        :class="{ 'table-warning': selectedAlumni?.id === a.id }"
                                    >
                                        <td class="ps-3 fw-semibold">{{ a.name }}</td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border">{{ a.batch }}</span>
                                        </td>
                                        <td class="text-muted small">{{ a.phone || '—' }}</td>
                                        <td class="text-center">
                                            <button
                                                v-if="selectedAlumni?.id !== a.id"
                                                type="button"
                                                class="btn btn-sm btn-outline-success"
                                                @click="selectAlumni(a)"
                                            >
                                                <i class="fas fa-check me-1"></i> বেছে নিন
                                            </button>
                                            <span v-else class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> নির্বাচিত
                                            </span>
                                        </td>
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

            <!-- ======= RIGHT: Form Panel ======= -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top:80px;">
                    <div class="card-header bg-success text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-user-edit me-2"></i> প্রতিনিধির তথ্য
                    </div>
                    <div class="card-body p-4">
                        <!-- Selected alumni indicator -->
                        <div v-if="selectedAlumni" class="alert alert-success border-0 rounded-3 mb-3 d-flex align-items-center justify-content-between py-2">
                            <span class="small fw-semibold">
                                <i class="fas fa-check-circle me-1"></i>
                                নির্বাচিত: <strong>{{ selectedAlumni.name }}</strong> (ব্যাচ {{ selectedAlumni.batch }})
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" @click="clearSelection">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div v-else class="alert alert-warning border-0 rounded-3 mb-3 small">
                            <i class="fas fa-info-circle me-1"></i>
                            বাম দিক থেকে একজন অ্যালামনাই বেছে নিন। তথ্য স্বয়ংক্রিয়ভাবে পূরণ হবে।
                        </div>

                        <form @submit.prevent="submit">
                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-control rounded-pill px-3"
                                    :class="{ 'is-invalid': form.errors.name }"
                                    placeholder="প্রতিনিধির নাম"
                                    required
                                />
                                <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>

                            <!-- Batch -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">ব্যাচ (সাল) <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.batch"
                                    type="text"
                                    class="form-control rounded-pill px-3"
                                    :class="{ 'is-invalid': form.errors.batch }"
                                    placeholder="যেমন: ১৯৯৫"
                                    required
                                />
                                <div v-if="form.errors.batch" class="invalid-feedback">{{ form.errors.batch }}</div>
                            </div>

                            <!-- Mobile -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">মোবাইল নম্বর</label>
                                <input
                                    v-model="form.mobile"
                                    type="tel"
                                    class="form-control rounded-pill px-3"
                                    :class="{ 'is-invalid': form.errors.mobile }"
                                    placeholder="০১XXXXXXXXX"
                                />
                                <div v-if="form.errors.mobile" class="invalid-feedback">{{ form.errors.mobile }}</div>
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">ঠিকানা</label>
                                <input
                                    v-model="form.address"
                                    type="text"
                                    class="form-control rounded-pill px-3"
                                    :class="{ 'is-invalid': form.errors.address }"
                                    placeholder="বর্তমান ঠিকানা"
                                />
                                <div v-if="form.errors.address" class="invalid-feedback">{{ form.errors.address }}</div>
                            </div>

                            <!-- Image -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">ছবি (ঐচ্ছিক)</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center rounded-circle border overflow-hidden flex-shrink-0"
                                        style="width:56px;height:56px;background:#f8f9fa;"
                                    >
                                        <img v-if="imagePreview" :src="imagePreview" class="w-100 h-100" style="object-fit:cover;" />
                                        <i v-else class="fas fa-user text-muted fs-4"></i>
                                    </div>
                                    <input
                                        type="file"
                                        class="form-control rounded-pill"
                                        accept="image/*"
                                        @change="onImageChange"
                                    />
                                </div>
                                <div v-if="form.errors.image" class="text-danger small mt-1">{{ form.errors.image }}</div>
                            </div>

                            <!-- Sort Order + Active -->
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">ক্রম (Sort)</label>
                                    <input
                                        v-model.number="form.sort_order"
                                        type="number"
                                        min="0"
                                        class="form-control rounded-pill px-3"
                                    />
                                </div>
                                <div class="col-6 d-flex align-items-end">
                                    <div class="form-check form-switch pb-1">
                                        <input
                                            v-model="form.is_active"
                                            class="form-check-input"
                                            type="checkbox"
                                            id="isActiveSwitch"
                                        />
                                        <label class="form-check-label fw-semibold" for="isActiveSwitch">
                                            সক্রিয় রাখুন
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button
                                    type="submit"
                                    class="btn btn-success fw-semibold py-2"
                                    :disabled="form.processing || !form.name"
                                >
                                    <span v-if="form.processing">
                                        <i class="fas fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে...
                                    </span>
                                    <span v-else>
                                        <i class="fas fa-save me-1"></i> প্রতিনিধি সংরক্ষণ করুন
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
