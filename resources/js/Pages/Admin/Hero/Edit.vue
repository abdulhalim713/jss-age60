<script setup>
import { ref } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    hero: Object,
});

const form = useForm({
    title:    props.hero?.title    ?? '',
    subtitle: props.hero?.subtitle ?? '',
    bg_image: null,
});

const previewUrl   = ref(props.hero?.bg_image ? `/storage/${props.hero.bg_image}` : null);
const removeImage  = ref(false);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.bg_image = file;
        previewUrl.value = URL.createObjectURL(file);
        removeImage.value = false;
    }
};

const clearImage = () => {
    form.bg_image = null;
    previewUrl.value = null;
    removeImage.value = true;
};

const submit = () => {
    form.post(route('admin.hero.update'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="হিরো সেটিং" />
    <AdminLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-image text-warning me-2"></i> হিরো সেকশন সেটিং
            </h4>
            <Link :href="route('dashboard')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> ড্যাসবোর্ড
            </Link>
        </div>

        <!-- Success Alert -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <div class="row g-4">
            <!-- Edit Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-edit me-2"></i> তথ্য সম্পাদনা
                    </div>
                    <div class="card-body p-4">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <!-- Title -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">শিরোনাম (Title)</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.title }"
                                    placeholder="যেমন: ৬০ বছর উজ্জ্বল দিগন্ত"
                                    required
                                />
                                <div v-if="form.errors.title" class="invalid-feedback">{{ form.errors.title }}</div>
                            </div>

                            <!-- Subtitle -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">উপশিরোনাম (Subtitle)</label>
                                <textarea
                                    v-model="form.subtitle"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.subtitle }"
                                    rows="3"
                                    placeholder="হিরো সেকশনের বিবরণ লিখুন..."
                                ></textarea>
                                <div v-if="form.errors.subtitle" class="invalid-feedback">{{ form.errors.subtitle }}</div>
                            </div>

                            <!-- Background Image Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">ব্যাকগ্রাউন্ড ছবি</label>
                                <input
                                    type="file"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.bg_image }"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    @change="handleFileChange"
                                />
                                <div class="form-text text-muted">সর্বোচ্চ ৪ MB। JPG, PNG, WebP ফরম্যাট সাপোর্টেড।</div>
                                <div v-if="form.errors.bg_image" class="invalid-feedback">{{ form.errors.bg_image }}</div>

                                <!-- Remove existing image -->
                                <div v-if="previewUrl && hero?.bg_image && !form.bg_image" class="mt-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        @click="clearImage"
                                    >
                                        <i class="fas fa-trash me-1"></i> ছবি সরিয়ে ফেলুন
                                    </button>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="d-flex gap-3">
                                <button
                                    type="submit"
                                    class="btn btn-warning px-5 fw-semibold"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">
                                        <i class="fas fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে...
                                    </span>
                                    <span v-else>
                                        <i class="fas fa-save me-1"></i> পরিবর্তন সংরক্ষণ করুন
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Live Preview -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-eye me-2"></i> লাইভ প্রিভিউ
                    </div>
                    <div
                        class="hero-preview d-flex flex-column align-items-center justify-content-center text-white text-center p-4"
                        :style="previewUrl
                            ? `background: linear-gradient(135deg, rgba(15,76,92,0.82) 0%, rgba(27,107,130,0.82) 100%), url('${previewUrl}') center/cover no-repeat`
                            : 'background: linear-gradient(135deg, #0F4C5C 0%, #1B6B82 100%)'"
                        style="min-height: 260px;"
                    >
                        <div class="badge rounded-pill px-3 py-2 mb-3" style="background:rgba(212,175,55,0.2); border:1px solid #D4AF37;">
                            <i class="fas fa-gem text-warning me-1"></i> হীরক জয়ন্তী ২০২৭
                        </div>
                        <h4 class="fw-bold" style="color:#D4AF37;">
                            {{ form.title || '৬০ বছর উজ্জ্বল দিগন্ত' }}
                        </h4>
                        <p class="small mt-2 text-white-75" style="max-width:320px;">
                            {{ form.subtitle || 'উপশিরোনাম এখানে দেখাবে...' }}
                        </p>
                    </div>
                </div>

                <!-- Current Image Info -->
                <div v-if="hero?.bg_image" class="card border-0 shadow-sm rounded-4 mt-3">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i> বর্তমান ব্যাকগ্রাউন্ড ছবি:</small>
                        <img
                            :src="`/storage/${hero.bg_image}`"
                            class="img-fluid rounded-3 w-100"
                            style="max-height:120px; object-fit:cover;"
                            alt="Hero Background"
                        />
                    </div>
                </div>
                <div v-else class="card border-0 shadow-sm rounded-4 mt-3">
                    <div class="card-body p-3 text-center text-muted">
                        <i class="fas fa-image fa-2x mb-2 d-block text-secondary"></i>
                        <small>কোনো ব্যাকগ্রাউন্ড ছবি নেই। ডিফল্ট গ্রেডিয়েন্ট ব্যবহার হচ্ছে।</small>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.hero-preview {
    transition: background 0.4s ease;
}
</style>
