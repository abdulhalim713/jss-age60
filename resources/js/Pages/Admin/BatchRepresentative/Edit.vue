<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    representative: Object,
});

const form = useForm({
    name:       props.representative.name       || '',
    batch:      props.representative.batch      || '',
    mobile:     props.representative.mobile     || '',
    address:    props.representative.address    || '',
    image:      null,
    sort_order: props.representative.sort_order || 0,
    is_active:  props.representative.is_active,
    _method:    'PUT', // Because we use post to send files but need PUT method logic
});

const imagePreview = ref(props.representative.image ? `/storage/${props.representative.image}` : null);

const onImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    // We send via POST with _method=PUT to support file uploads in Laravel Inertia
    form.post(route('admin.batch-representatives.update', props.representative.id), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="প্রতিনিধি সম্পাদনা" />
    <AdminLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-user-edit text-primary me-2"></i> প্রতিনিধি সম্পাদনা
            </h4>
            <Link :href="route('admin.batch-representatives.index')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরুন
            </Link>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-pen me-2"></i> প্রতিনিধির তথ্য আপডেট করুন
                    </div>
                    <div class="card-body p-4">
                        <form @submit.prevent="submit">
                            <!-- Name -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-control rounded-pill px-3"
                                    :class="{ 'is-invalid': form.errors.name }"
                                    required
                                />
                                <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>

                            <!-- Batch -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">ব্যাচ (সাল) <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.batch"
                                    type="text"
                                    class="form-control rounded-pill px-3"
                                    :class="{ 'is-invalid': form.errors.batch }"
                                    required
                                />
                                <div v-if="form.errors.batch" class="invalid-feedback">{{ form.errors.batch }}</div>
                            </div>

                            <!-- Mobile -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">মোবাইল নম্বর</label>
                                <input
                                    v-model="form.mobile"
                                    type="tel"
                                    class="form-control rounded-pill px-3"
                                    :class="{ 'is-invalid': form.errors.mobile }"
                                />
                                <div v-if="form.errors.mobile" class="invalid-feedback">{{ form.errors.mobile }}</div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">ঠিকানা</label>
                                <input
                                    v-model="form.address"
                                    type="text"
                                    class="form-control rounded-pill px-3"
                                    :class="{ 'is-invalid': form.errors.address }"
                                />
                                <div v-if="form.errors.address" class="invalid-feedback">{{ form.errors.address }}</div>
                            </div>

                            <!-- Image -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">ছবি</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center rounded-circle border overflow-hidden flex-shrink-0"
                                        style="width:64px;height:64px;background:#f8f9fa;"
                                    >
                                        <img v-if="imagePreview" :src="imagePreview" class="w-100 h-100" style="object-fit:cover;" />
                                        <i v-else class="fas fa-user text-muted fs-3"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input
                                            type="file"
                                            class="form-control rounded-pill"
                                            accept="image/*"
                                            @change="onImageChange"
                                        />
                                        <small class="text-muted d-block mt-1 ms-2">ছবি পরিবর্তন করতে নতুন ছবি নির্বাচন করুন।</small>
                                    </div>
                                </div>
                                <div v-if="form.errors.image" class="text-danger small mt-1">{{ form.errors.image }}</div>
                            </div>

                            <!-- Sort Order + Active -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">ক্রম (Sort)</label>
                                    <input
                                        v-model.number="form.sort_order"
                                        type="number"
                                        min="0"
                                        class="form-control rounded-pill px-3"
                                    />
                                    <small class="text-muted d-block mt-1 ms-2">ছোট সংখ্যা আগে দেখাবে</small>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="form-check form-switch pt-2">
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

                            <hr class="my-4">

                            <!-- Submit -->
                            <div class="d-flex justify-content-end gap-3">
                                <Link :href="route('admin.batch-representatives.index')" class="btn btn-outline-secondary rounded-pill px-4">
                                    বাতিল
                                </Link>
                                <button
                                    type="submit"
                                    class="btn btn-primary rounded-pill px-5 fw-semibold"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">
                                        <i class="fas fa-spinner fa-spin me-1"></i> আপডেট হচ্ছে...
                                    </span>
                                    <span v-else>
                                        <i class="fas fa-save me-1"></i> আপডেট করুন
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
