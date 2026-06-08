<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <form @submit.prevent="form.patch(route('profile.update'))">
        <!-- Name Field -->
        <div class="mb-4">
            <label for="name" class="form-label fw-semibold">
                <i class="fas fa-user me-1 text-secondary"></i> নাম
                <span class="text-danger">*</span>
            </label>
            <input
                id="name"
                type="text"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
                placeholder="আপনার পূর্ণ নাম লিখুন"
                class="form-control rounded-3"
                :class="{ 'is-invalid': form.errors.name }"
            />
            <InputError :message="form.errors.name" class="invalid-feedback d-block" />
        </div>

        <!-- Email Field -->
        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">
                <i class="fas fa-envelope me-1 text-secondary"></i> ইমেইল ঠিকানা
                <span class="text-danger">*</span>
            </label>
            <input
                id="email"
                type="email"
                v-model="form.email"
                required
                autocomplete="username"
                placeholder="example@mail.com"
                class="form-control rounded-3"
                :class="{ 'is-invalid': form.errors.email }"
            />
            <InputError :message="form.errors.email" class="invalid-feedback d-block" />
        </div>

        <!-- Email Verification Notice -->
        <div v-if="mustVerifyEmail && user.email_verified_at === null" class="alert alert-warning rounded-3 mb-4 py-2 small">
            <i class="fas fa-exclamation-triangle me-2"></i>
            আপনার ইমেইল ঠিকানাটি ভেরিফাই করা হয়নি।
            <Link
                :href="route('verification.send')"
                method="post"
                as="button"
                class="btn btn-link btn-sm p-0 ms-1 fw-semibold text-warning"
            >
                ভেরিফিকেশন ইমেইল পুনরায় পাঠান
            </Link>
        </div>

        <div
            v-show="status === 'verification-link-sent'"
            class="alert alert-success rounded-3 mb-4 py-2 small"
        >
            <i class="fas fa-check-circle me-2"></i>
            আপনার ইমেইলে একটি নতুন ভেরিফিকেশন লিঙ্ক পাঠানো হয়েছে।
        </div>

        <!-- Submit -->
        <div class="d-flex align-items-center gap-3">
            <button
                type="submit"
                class="btn px-5 fw-semibold"
                :disabled="form.processing"
                style="background:#0F4C5C; color:#fff; border:none;"
            >
                <span v-if="form.processing">
                    <i class="fas fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে...
                </span>
                <span v-else>
                    <i class="fas fa-save me-1"></i> সংরক্ষণ করুন
                </span>
            </button>

            <Transition
                enter-active-class="transition-opacity duration-300"
                enter-from-class="opacity-0"
                leave-active-class="transition-opacity duration-300"
                leave-to-class="opacity-0"
            >
                <span
                    v-if="form.recentlySuccessful"
                    class="text-success small fw-semibold"
                >
                    <i class="fas fa-check-circle me-1"></i> সফলভাবে সংরক্ষিত হয়েছে।
                </span>
            </Transition>
        </div>
    </form>
</template>
