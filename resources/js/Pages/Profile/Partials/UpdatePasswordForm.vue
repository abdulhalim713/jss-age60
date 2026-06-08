<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <form @submit.prevent="updatePassword">
        <!-- Current Password -->
        <div class="mb-4">
            <label for="current_password" class="form-label fw-semibold">
                <i class="fas fa-lock me-1 text-secondary"></i> বর্তমান পাসওয়ার্ড
                <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input
                    id="current_password"
                    ref="currentPasswordInput"
                    :type="showCurrentPassword ? 'text' : 'password'"
                    v-model="form.current_password"
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="form-control rounded-start-3"
                    :class="{ 'is-invalid': form.errors.current_password }"
                />
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    @click="showCurrentPassword = !showCurrentPassword"
                    tabindex="-1"
                    style="border-left:0;"
                >
                    <i :class="showCurrentPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <InputError :message="form.errors.current_password" class="text-danger small mt-1 d-block" />
        </div>

        <!-- New Password -->
        <div class="mb-4">
            <label for="password" class="form-label fw-semibold">
                <i class="fas fa-key me-1 text-secondary"></i> নতুন পাসওয়ার্ড
                <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input
                    id="password"
                    ref="passwordInput"
                    :type="showNewPassword ? 'text' : 'password'"
                    v-model="form.password"
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="form-control rounded-start-3"
                    :class="{ 'is-invalid': form.errors.password }"
                />
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    @click="showNewPassword = !showNewPassword"
                    tabindex="-1"
                    style="border-left:0;"
                >
                    <i :class="showNewPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <InputError :message="form.errors.password" class="text-danger small mt-1 d-block" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">
                <i class="fas fa-check-circle me-1 text-secondary"></i> পাসওয়ার্ড নিশ্চিত করুন
                <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input
                    id="password_confirmation"
                    :type="showConfirmPassword ? 'text' : 'password'"
                    v-model="form.password_confirmation"
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="form-control rounded-start-3"
                    :class="{ 'is-invalid': form.errors.password_confirmation }"
                />
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    @click="showConfirmPassword = !showConfirmPassword"
                    tabindex="-1"
                    style="border-left:0;"
                >
                    <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <InputError :message="form.errors.password_confirmation" class="text-danger small mt-1 d-block" />
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
                    <i class="fas fa-spinner fa-spin me-1"></i> আপডেট হচ্ছে...
                </span>
                <span v-else>
                    <i class="fas fa-save me-1"></i> পাসওয়ার্ড আপডেট করুন
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
                    <i class="fas fa-check-circle me-1"></i> পাসওয়ার্ড সফলভাবে পরিবর্তিত হয়েছে।
                </span>
            </Transition>
        </div>
    </form>
</template>
