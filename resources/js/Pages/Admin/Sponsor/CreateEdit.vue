<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    item: Object, // null for create, object for edit
});

const isEditing = computed(() => !!props.item);

const form = useForm({
    name:       props.item?.name       ?? '',
    logo:       null, // for file upload
    link:       props.item?.link       ?? '',
    sort_order: props.item?.sort_order ?? 0,
});

const previewUrl = ref(props.item?.logo ? `/storage/${props.item.logo}` : null);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const clearLogo = () => {
    form.logo = null;
    previewUrl.value = props.item?.logo ? `/storage/${props.item.logo}` : null;
};

const submit = () => {
    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.sponsors.update', props.item.id), {
            forceFormData: true,
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.sponsors.store'), {
            forceFormData: true,
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'স্পন্সর সম্পাদনা' : 'নতুন স্পন্সর যোগ'" />
    <AdminLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i :class="isEditing ? 'fas fa-edit text-primary' : 'fas fa-plus-circle text-success'" class="me-2"></i>
                {{ isEditing ? 'সহযোগী ও স্পন্সর সম্পাদনা' : 'নতুন সহযোগী ও স্পন্সর যোগ করুন' }}
            </h4>
            <Link :href="route('admin.sponsors.index')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরুন
            </Link>
        </div>

        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-handshake me-2"></i> স্পন্সরের তথ্য
                    </div>
                    <div class="card-body p-4">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <!-- Name -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">সহযোগী/স্পন্সরের নাম <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.name }"
                                    placeholder="যেমন: সোনালী ব্যাংক, ডিজিটাল বাংলা"
                                    required
                                />
                                <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>

                            <!-- Logo Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">লোগো আপলোড করুন <span class="text-danger" v-if="!isEditing">*</span></label>
                                <input
                                    type="file"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.logo }"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    @change="handleFileChange"
                                    :required="!isEditing"
                                />
                                <div class="form-text text-muted">সর্বোচ্চ ৪ MB। অনুভূমিক লোগো (যেমন: 200x80px) বেশি উপযুক্ত। JPG, PNG, WebP সমর্থিত।</div>
                                <div v-if="form.errors.logo" class="invalid-feedback">{{ form.errors.logo }}</div>

                                <!-- Clear selected logo -->
                                <div v-if="previewUrl && form.logo" class="mt-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        @click="clearLogo"
                                    >
                                        <i class="fas fa-trash me-1"></i> লোগো রিসেট করুন
                                    </button>
                                </div>
                            </div>

                            <!-- Link -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">ওয়েবসাইট লিংক (ঐচ্ছিক)</label>
                                <input
                                    v-model="form.link"
                                    type="url"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.link }"
                                    placeholder="যেমন: https://sonalibank.com.bd"
                                />
                                <div v-if="form.errors.link" class="invalid-feedback">{{ form.errors.link }}</div>
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
                                        <i :class="isEditing ? 'fas fa-save' : 'fas fa-plus'" class="me-1"></i>
                                        {{ isEditing ? 'আপডেট করুন' : 'যোগ করুন' }}
                                    </span>
                                </button>
                                <Link :href="route('admin.sponsors.index')" class="btn btn-outline-secondary px-4">
                                    বাতিল
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-eye me-2"></i> প্রিভিউ
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="rounded-4 p-4 d-flex flex-column align-items-center justify-content-center bg-light" style="min-height: 180px;">
                            <img 
                                v-if="previewUrl"
                                :src="previewUrl"
                                class="img-fluid rounded border shadow-sm p-1 bg-white"
                                style="max-height:80px; max-width: 200px; object-fit:contain;"
                            />
                            <div v-else class="text-center text-muted">
                                <i class="fas fa-handshake fa-3x mb-2 text-secondary"></i>
                                <p class="small mb-0">কোনো লোগো আপলোড করা নেই</p>
                            </div>

                            <h6 class="mt-3 fw-bold mb-1 text-truncate" style="max-width: 100%;">{{ form.name || 'স্পন্সরের নাম' }}</h6>
                            <a v-if="form.link" :href="form.link" target="_blank" class="small text-primary text-truncate d-block" style="max-width: 100%;">
                                {{ form.link }}
                            </a>
                        </div>
                        <p class="text-muted small mt-3 mb-0">
                            <i class="fas fa-sort-numeric-down me-1"></i> ক্রম: {{ form.sort_order }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
