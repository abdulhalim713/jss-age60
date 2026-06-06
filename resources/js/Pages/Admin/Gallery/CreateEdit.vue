<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    item: Object, // null for create, object for edit
});

const isEditing = computed(() => !!props.item);

const form = useForm({
    type:       props.item?.type       ?? 'image',
    title:      props.item?.title      ?? '',
    file_path:  null, // for upload
    video_url:  props.item?.video_url  ?? '',
    sort_order: props.item?.sort_order ?? 0,
});

const previewUrl = ref(props.item?.type === 'image' && props.item?.file_path ? `/storage/${props.item.file_path}` : null);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.file_path = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const clearImage = () => {
    form.file_path = null;
    previewUrl.value = null;
};

// Computed property to display live YouTube video previews in the iframe
const previewVideoUrl = computed(() => {
    if (!form.video_url) return null;
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = form.video_url.match(regExp);
    if (match && match[2].length === 11) {
        return 'https://www.youtube.com/embed/' + match[2];
    }
    return form.video_url;
});

const submit = () => {
    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.gallery.update', props.item.id), {
            forceFormData: true,
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.gallery.store'), {
            forceFormData: true,
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'মিডিয়া সম্পাদনা' : 'নতুন মিডিয়া যোগ'" />
    <AdminLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i :class="isEditing ? 'fas fa-edit text-primary' : 'fas fa-plus-circle text-success'" class="me-2"></i>
                {{ isEditing ? 'গ্যালারি মিডিয়া সম্পাদনা' : 'নতুন গ্যালারি মিডিয়া যোগ করুন' }}
            </h4>
            <Link :href="route('admin.gallery.index')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরুন
            </Link>
        </div>

        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-photo-film me-2"></i> মিডিয়ার তথ্য
                    </div>
                    <div class="card-body p-4">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <!-- Type -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">মিডিয়ার ধরণ <span class="text-danger">*</span></label>
                                <div class="d-flex gap-4 mt-1">
                                    <div class="form-check">
                                        <input
                                            v-model="form.type"
                                            class="form-check-input"
                                            type="radio"
                                            name="media_type"
                                            id="typeImage"
                                            value="image"
                                        />
                                        <label class="form-check-label fw-medium" for="typeImage">
                                            <i class="fas fa-image text-success me-1"></i> ছবি (Photo)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            v-model="form.type"
                                            class="form-check-input"
                                            type="radio"
                                            name="media_type"
                                            id="typeVideo"
                                            value="video"
                                        />
                                        <label class="form-check-label fw-medium" for="typeVideo">
                                            <i class="fas fa-video text-danger me-1"></i> ভিডিও (YouTube URL)
                                        </label>
                                    </div>
                                </div>
                                <div v-if="form.errors.type" class="text-danger small mt-1">{{ form.errors.type }}</div>
                            </div>

                            <!-- Title -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">শিরোনাম (ক্যাপশন/টাইটেল)</label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.title }"
                                    placeholder="যেমন: বিদ্যালয় প্রাঙ্গণ, প্রাক্তন ছাত্রদের শুভেচ্ছা"
                                />
                                <div v-if="form.errors.title" class="invalid-feedback">{{ form.errors.title }}</div>
                            </div>

                            <!-- Image Upload (Condition) -->
                            <div v-if="form.type === 'image'" class="mb-4 animate-fade">
                                <label class="form-label fw-semibold">ছবি আপলোড করুন <span class="text-danger">*</span></label>
                                <input
                                    type="file"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.file_path }"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    @change="handleFileChange"
                                    :required="!isEditing"
                                />
                                <div class="form-text text-muted">সর্বোচ্চ ৪ MB। JPG, PNG, WebP ফরম্যাট সাপোর্টেড।</div>
                                <div v-if="form.errors.file_path" class="invalid-feedback">{{ form.errors.file_path }}</div>

                                <!-- Clear selected image -->
                                <div v-if="previewUrl && form.file_path" class="mt-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        @click="clearImage"
                                    >
                                        <i class="fas fa-trash me-1"></i> ছবি সরিয়ে ফেলুন
                                    </button>
                                </div>
                            </div>

                            <!-- YouTube Link Input (Condition) -->
                            <div v-if="form.type === 'video'" class="mb-4 animate-fade">
                                <label class="form-label fw-semibold">ইউটিউব ভিডিও লিংক <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.video_url"
                                    type="url"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.video_url }"
                                    placeholder="যেমন: https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                                    required
                                />
                                <div class="form-text text-muted">ইউটিউব শেয়ার লিংক অথবা ইউআরএল পেস্ট করুন। এটি স্বয়ংক্রিয়ভাবে এমবেড লিংকে রূপান্তরিত হবে।</div>
                                <div v-if="form.errors.video_url" class="invalid-feedback">{{ form.errors.video_url }}</div>
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
                                <Link :href="route('admin.gallery.index')" class="btn btn-outline-secondary px-4">
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
                        <div class="rounded-4 p-4" style="background:#F5F5F5;">
                            <!-- Photo Preview -->
                            <div v-if="form.type === 'image'">
                                <img 
                                    v-if="previewUrl"
                                    :src="previewUrl"
                                    class="img-fluid rounded border shadow-sm mb-3"
                                    style="max-height:160px; object-fit:cover;"
                                />
                                <div v-else class="text-center text-muted py-4">
                                    <i class="fas fa-image fa-3x mb-2 text-secondary"></i>
                                    <p class="small mb-0">কোনো ছবি সিলেক্ট করা নেই</p>
                                </div>
                            </div>

                            <!-- YouTube Embed Preview -->
                            <div v-if="form.type === 'video'">
                                <div v-if="previewVideoUrl" class="ratio ratio-16x9 rounded overflow-hidden shadow-sm mb-3">
                                    <iframe
                                        :src="previewVideoUrl"
                                        title="YouTube video player"
                                        allowfullscreen
                                    ></iframe>
                                </div>
                                <div v-else class="text-center text-muted py-4">
                                    <i class="fab fa-youtube fa-3x mb-2 text-danger"></i>
                                    <p class="small mb-0">কোনো ভিডিও লিংক দেওয়া নেই</p>
                                </div>
                            </div>

                            <h6 class="mt-2 fw-semibold mb-1 text-truncate">{{ form.title || 'মিডিয়া শিরোনাম' }}</h6>
                            <span class="badge px-3 py-1" :class="form.type === 'image' ? 'bg-success' : 'bg-danger'">
                                {{ form.type === 'image' ? 'ছবি' : 'ভিডিও' }}
                            </span>
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
