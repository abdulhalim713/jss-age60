<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    hero: Object, // null for create, object for edit
});

const isEditing = computed(() => !!props.hero);

const form = useForm({
    title:      props.hero?.title      ?? '',
    subtitle:   props.hero?.subtitle   ?? '',
    bg_image:   null,
    sort_order: props.hero?.sort_order ?? 0,
});

const previewUrl = ref(props.hero?.bg_image ? `/storage/${props.hero.bg_image}` : null);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.bg_image = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const clearImage = () => {
    form.bg_image = null;
    previewUrl.value = null;
};

const submit = () => {
    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.hero.update', props.hero.id), {
            forceFormData: true,
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.hero.store'), {
            forceFormData: true,
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'স্লাইড সম্পাদনা' : 'নতুন স্লাইড যোগ'" />
    <AdminLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i :class="isEditing ? 'fas fa-edit text-primary' : 'fas fa-plus-circle text-success'" class="me-2"></i>
                {{ isEditing ? 'হিরো স্লাইড সম্পাদনা' : 'নতুন হিরো স্লাইড যোগ করুন' }}
            </h4>
            <Link :href="route('admin.hero.index')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরুন
            </Link>
        </div>

        <div class="row g-4">
            <!-- Edit Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-edit me-2"></i> স্লাইডের বিবরণী
                    </div>
                    <div class="card-body p-4">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <!-- Title -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">শিরোনাম (Title) <span class="text-danger">*</span></label>
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
                                    placeholder="স্লাইডের বিবরণ লিখুন..."
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

                                <!-- Clear selected image -->
                                <div v-if="previewUrl && form.bg_image" class="mt-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        @click="clearImage"
                                    >
                                        <i class="fas fa-trash me-1"></i> ছবি সরিয়ে ফেলুন
                                    </button>
                                </div>
                            </div>

                            <!-- Sort Order -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">প্রদর্শন ক্রম (Sort Order)</label>
                                <input
                                    v-model.number="form.sort_order"
                                    type="number"
                                    min="0"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.sort_order }"
                                    placeholder="0"
                                    required
                                />
                                <div class="form-text text-muted">ছোট সংখ্যা আগে দেখাবে।</div>
                                <div v-if="form.errors.sort_order" class="invalid-feedback">{{ form.errors.sort_order }}</div>
                            </div>

                            <!-- Submit -->
                            <div class="d-flex gap-3">
                                <button
                                    type="submit"
                                    class="btn px-5 fw-semibold"
                                    :class="isEditing ? 'btn-primary' : 'btn-success'"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">
                                        <i class="fas fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে...
                                    </span>
                                    <span v-else>
                                        <i class="fas fa-save me-1"></i> সংরক্ষণ করুন
                                    </span>
                                </button>
                                <Link :href="route('admin.hero.index')" class="btn btn-outline-secondary px-4">
                                    বাতিল
                                </Link>
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
                            {{ form.title || 'শিরোনাম এখানে দেখাবে' }}
                        </h4>
                        <p class="small mt-2 text-white-75" style="max-width:320px;">
                            {{ form.subtitle || 'উপশিরোনাম এখানে দেখাবে...' }}
                        </p>
                    </div>
                </div>

                <!-- Current Image Info -->
                <div v-if="isEditing && props.hero?.bg_image" class="card border-0 shadow-sm rounded-4 mt-3">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i> সংরক্ষিত ব্যাকগ্রাউন্ড ছবি:</small>
                        <img
                            :src="`/storage/${props.hero.bg_image}`"
                            class="img-fluid rounded-3 w-100"
                            style="max-height:150px; object-fit:cover;"
                            alt="Hero Background"
                        />
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
