<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    member: Object, // null for create, object for edit
});

const isEditing = computed(() => !!props.member);

const iconOptions = [
    { value: 'fas fa-user-tie',        label: 'টাই (সভাপতি)' },
    { value: 'fas fa-user-graduate',    label: 'গ্র্যাজুয়েট' },
    { value: 'fas fa-chalkboard-user',  label: 'শিক্ষক' },
    { value: 'fas fa-hand-sparkles',    label: 'সমন্বয়ক' },
    { value: 'fas fa-camera',           label: 'ক্যামেরা' },
    { value: 'fas fa-music',            label: 'সঙ্গীত' },
    { value: 'fas fa-dollar-sign',      label: 'অর্থ' },
    { value: 'fas fa-running',          label: 'ক্রীড়া' },
    { value: 'fas fa-crown',            label: 'মুকুট' },
    { value: 'fas fa-pen-fancy',        label: 'কলম' },
    { value: 'fas fa-gavel',            label: 'গ্যাভেল' },
    { value: 'fas fa-star',             label: 'তারকা' },
    { value: 'fas fa-shield-alt',       label: 'ঢাল' },
    { value: 'fas fa-heart',            label: 'হৃদয়' },
    { value: 'fas fa-book',             label: 'বই' },
    { value: 'fas fa-bullhorn',         label: 'প্রচার' },
    { value: 'fas fa-user',             label: 'ব্যবহারকারী' },
    { value: 'fas fa-hands-helping',    label: 'সাহায্য' },
];

const form = useForm({
    name:       props.member?.name       ?? '',
    role:       props.member?.role       ?? '',
    icon:       props.member?.icon       ?? 'fas fa-user-tie',
    sort_order: props.member?.sort_order ?? 0,
    image:      null,
    mobile:     props.member?.mobile     ?? '',
    address:    props.member?.address    ?? '',
});

const previewUrl = ref(props.member?.image ? `/storage/${props.member.image}` : null);

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const clearImage = () => {
    form.image = null;
    previewUrl.value = null;
};

const submit = () => {
    if (isEditing.value) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.committee.update', props.member.id), {
            forceFormData: true,
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.committee.store'), {
            forceFormData: true,
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'সদস্য সম্পাদনা' : 'নতুন সদস্য যোগ'" />
    <AdminLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i :class="isEditing ? 'fas fa-edit text-primary' : 'fas fa-plus-circle text-success'" class="me-2"></i>
                {{ isEditing ? 'কমিটি সদস্য সম্পাদনা' : 'নতুন কমিটি সদস্য যোগ করুন' }}
            </h4>
            <Link :href="route('admin.committee.index')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরুন
            </Link>
        </div>

        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-user-plus me-2"></i> সদস্যের তথ্য
                    </div>
                    <div class="card-body p-4">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <!-- Name -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">সদস্যের নাম <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.name }"
                                    placeholder="যেমন: মোঃ সিরাজুল ইসলাম"
                                    required
                                />
                                <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>

                            <!-- Role -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">পদবি / ভূমিকা <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.role"
                                    type="text"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.role }"
                                    placeholder="যেমন: সভাপতি, সচিব, কোষাধ্যক্ষ"
                                    required
                                />
                                <div v-if="form.errors.role" class="invalid-feedback">{{ form.errors.role }}</div>
                            </div>

                            <!-- Mobile -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">মোবাইল নম্বর</label>
                                <input
                                    v-model="form.mobile"
                                    type="text"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.mobile }"
                                    placeholder="যেমন: ০১৭১২৩৪৫৬৭৮"
                                />
                                <div v-if="form.errors.mobile" class="invalid-feedback">{{ form.errors.mobile }}</div>
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">ঠিকানা</label>
                                <textarea
                                    v-model="form.address"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.address }"
                                    rows="2"
                                    placeholder="সদস্যের ঠিকানা লিখুন..."
                                ></textarea>
                                <div v-if="form.errors.address" class="invalid-feedback">{{ form.errors.address }}</div>
                            </div>

                            <!-- Member Image Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">সদস্যের ছবি</label>
                                <input
                                    type="file"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.image }"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    @change="handleFileChange"
                                />
                                <div class="form-text text-muted">সর্বোচ্চ ৪ MB। স্কয়ার সাইজ (যেমন: ৩০০x৩০০) ছবি রিকমেন্ডেড।</div>
                                <div v-if="form.errors.image" class="invalid-feedback">{{ form.errors.image }}</div>

                                <!-- Clear selected image -->
                                <div v-if="previewUrl && form.image" class="mt-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        @click="clearImage"
                                    >
                                        <i class="fas fa-trash me-1"></i> ছবি সরিয়ে ফেলুন
                                    </button>
                                </div>
                            </div>

                            <!-- Icon Selector (used as fallback if no image uploaded) -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">ডিফল্ট আইকন (ছবি না থাকলে এটি প্রদর্শিত হবে) <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div
                                        v-for="opt in iconOptions"
                                        :key="opt.value"
                                        class="col-4 col-md-3"
                                    >
                                        <div
                                            class="icon-option d-flex flex-column align-items-center justify-content-center p-2 rounded-3 border text-center"
                                            :class="{
                                                'border-warning bg-warning-subtle shadow-sm': form.icon === opt.value,
                                                'border-light bg-light': form.icon !== opt.value
                                            }"
                                            style="cursor:pointer; transition:all 0.2s;"
                                            @click="form.icon = opt.value"
                                        >
                                            <i :class="opt.value" class="fs-4 mb-1" :style="form.icon === opt.value ? 'color:#D4AF37' : 'color:#6c757d'"></i>
                                            <small style="font-size:0.7rem;">{{ opt.label }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="form.errors.icon" class="text-danger small mt-1">{{ form.errors.icon }}</div>
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
                                        {{ isEditing ? 'আপডেট করুন' : 'সদস্য যোগ করুন' }}
                                    </span>
                                </button>
                                <Link :href="route('admin.committee.index')" class="btn btn-outline-secondary px-4">
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
                            <!-- Photo or Fallback Icon -->
                            <img 
                                v-if="previewUrl"
                                :src="previewUrl"
                                class="rounded-circle border mb-3 shadow-sm"
                                style="width:100px;height:100px;object-fit:cover;"
                            />
                            <i v-else :class="form.icon + ' fa-3x mb-3'" style="color:#D4AF37;"></i>

                            <h6 class="mt-2 fw-semibold mb-1">{{ form.name || 'সদস্যের নাম' }}</h6>
                            <div class="badge bg-warning text-dark mb-3 px-3 py-1">{{ form.role || 'পদবি' }}</div>
                            
                            <div v-if="form.mobile" class="small text-muted mb-1">
                                <i class="fas fa-phone-alt me-1 text-secondary"></i> {{ form.mobile }}
                            </div>
                            <div v-if="form.address" class="small text-muted text-truncate px-2">
                                <i class="fas fa-map-marker-alt me-1 text-secondary"></i> {{ form.address }}
                            </div>
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

<style scoped>
.icon-option:hover {
    background-color: #f8f9fa;
}
</style>
