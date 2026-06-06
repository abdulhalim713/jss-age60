<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    user: Object, // null for create, object for edit
});

const isEditing = computed(() => !!props.user);

const form = useForm({
    name:                  props.user?.name  ?? '',
    email:                 props.user?.email ?? '',
    password:              '',
    password_confirmation: '',
});

const submit = () => {
    if (isEditing.value) {
        form.put(route('admin.users.update', props.user.id), {
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.users.store'), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'ইউজার সম্পাদনা' : 'নতুন ইউজার তৈরি'" />
    <AdminLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i :class="isEditing ? 'fas fa-edit text-primary' : 'fas fa-user-plus text-success'" class="me-2"></i>
                {{ isEditing ? 'ইউজার সম্পাদনা' : 'নতুন ইউজার তৈরি করুন' }}
            </h4>
            <Link :href="route('admin.users.index')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরুন
            </Link>
        </div>

        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-user-cog me-2"></i> ইউজারের তথ্য
                    </div>
                    <div class="card-body p-4">
                        <form @submit.prevent="submit">
                            <!-- Name -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.name }"
                                    placeholder="পূর্ণ নাম লিখুন"
                                    required
                                />
                                <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">ইমেইল <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.email }"
                                    placeholder="example@email.com"
                                    required
                                />
                                <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    পাসওয়ার্ড
                                    <span v-if="!isEditing" class="text-danger">*</span>
                                    <small v-if="isEditing" class="text-muted fw-normal">(পরিবর্তন করতে চাইলে পূরণ করুন, অন্যথায় ফাঁকা রাখুন)</small>
                                </label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.password }"
                                    placeholder="নতুন পাসওয়ার্ড"
                                    :required="!isEditing"
                                />
                                <div v-if="form.errors.password" class="invalid-feedback">{{ form.errors.password }}</div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    পাসওয়ার্ড নিশ্চিত করুন
                                    <span v-if="!isEditing" class="text-danger">*</span>
                                </label>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.password_confirmation }"
                                    placeholder="পাসওয়ার্ড পুনরায় লিখুন"
                                    :required="!isEditing"
                                />
                                <div v-if="form.errors.password_confirmation" class="invalid-feedback">{{ form.errors.password_confirmation }}</div>
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
                                        <i :class="isEditing ? 'fas fa-save' : 'fas fa-user-plus'" class="me-1"></i>
                                        {{ isEditing ? 'আপডেট করুন' : 'ইউজার তৈরি করুন' }}
                                    </span>
                                </button>
                                <Link :href="route('admin.users.index')" class="btn btn-outline-secondary px-4">
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
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info-subtle mb-3"
                                 style="width:80px;height:80px;">
                                <i class="fas fa-user-circle text-info" style="font-size:3rem;"></i>
                            </div>
                            <h6 class="mt-2 fw-semibold mb-1">{{ form.name || 'ইউজারের নাম' }}</h6>
                            <div class="text-muted small mb-2">{{ form.email || 'email@example.com' }}</div>
                            <div class="badge bg-info text-white px-3 py-1">
                                <i class="fas fa-shield-alt me-1"></i> এডমিন
                            </div>
                        </div>
                        <p v-if="isEditing" class="text-muted small mt-3 mb-0">
                            <i class="fas fa-clock me-1"></i> তৈরির তারিখ: {{ new Date(user.created_at).toLocaleDateString('bn-BD') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
