<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const activeTab = ref('info'); // 'info' | 'password'
</script>

<template>
    <Head title="প্রোফাইল সেটিংস" />
    <AdminLayout>
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-user-cog text-primary me-2"></i>
                প্রোফাইল সেটিংস
            </h4>
        </div>

        <div class="row g-4">
            <!-- Left: User Info Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-id-badge me-2"></i> ব্যবহারকারীর তথ্য
                    </div>
                    <div class="card-body p-4 text-center">
                        <!-- Avatar -->
                        <div
                            class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-white fw-bold fs-1 shadow"
                            style="width:90px;height:90px;background:linear-gradient(135deg,#0F4C5C,#1B6B82);border:3px solid #D4AF37;"
                        >
                            {{ user.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                        </div>
                        <h5 class="fw-bold mb-1">{{ user.name }}</h5>
                        <p class="text-muted small mb-3">{{ user.email }}</p>
                        <span class="badge text-white px-3 py-2 rounded-pill" style="background:#0F4C5C;border:1px solid #D4AF37;">
                            <i class="fas fa-graduation-cap me-1"></i> প্রাক্তন শিক্ষার্থী
                        </span>

                        <hr class="my-4 opacity-25" />

                        <!-- Tab Selector Buttons -->
                        <div class="d-flex flex-column gap-2 text-start">
                            <button
                                @click="activeTab = 'info'"
                                class="btn w-100 text-start py-2 px-4 rounded-3 d-flex align-items-center gap-2 border-0 transition"
                                :class="activeTab === 'info'
                                    ? 'text-white fw-bold'
                                    : 'btn-light text-secondary'"
                                :style="activeTab === 'info' ? 'background:#0F4C5C;' : ''"
                            >
                                <i class="fas fa-user-edit fa-fw"></i>
                                প্রোফাইল তথ্য
                            </button>

                            <button
                                @click="activeTab = 'password'"
                                class="btn w-100 text-start py-2 px-4 rounded-3 d-flex align-items-center gap-2 border-0 transition"
                                :class="activeTab === 'password'
                                    ? 'text-white fw-bold'
                                    : 'btn-light text-secondary'"
                                :style="activeTab === 'password' ? 'background:#0F4C5C;' : ''"
                            >
                                <i class="fas fa-key fa-fw"></i>
                                পাসওয়ার্ড পরিবর্তন
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i v-if="activeTab === 'info'" class="fas fa-user-edit me-2"></i>
                        <i v-else class="fas fa-key me-2"></i>
                        {{ activeTab === 'info' ? 'প্রোফাইল তথ্য আপডেট' : 'পাসওয়ার্ড পরিবর্তন' }}
                    </div>
                    <div class="card-body p-4">
                        <Transition
                            mode="out-in"
                            enter-active-class="transition-opacity duration-200"
                            enter-from-class="opacity-0"
                            enter-to-class="opacity-100"
                            leave-active-class="transition-opacity duration-150"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <div v-if="activeTab === 'info'" key="info">
                                <!-- Profile Info heading inside card -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-1">
                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                        আপনার প্রোফাইল তথ্য পরিবর্তন করুন
                                    </h6>
                                    <p class="text-muted small mb-0">নাম এবং ইমেইল ঠিকানা আপডেট করা যাবে।</p>
                                </div>
                                <hr class="mb-4 opacity-15" />
                                <UpdateProfileInformationForm
                                    :must-verify-email="mustVerifyEmail"
                                    :status="status"
                                />
                            </div>

                            <div v-else-if="activeTab === 'password'" key="password">
                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-1">
                                        <i class="fas fa-shield-alt text-warning me-2"></i>
                                        নিরাপদ পাসওয়ার্ড ব্যবহার করুন
                                    </h6>
                                    <p class="text-muted small mb-0">দীর্ঘ এবং শক্তিশালী পাসওয়ার্ড নিরাপত্তা নিশ্চিত করে।</p>
                                </div>
                                <hr class="mb-4 opacity-15" />
                                <UpdatePasswordForm />
                            </div>
                        </Transition>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
