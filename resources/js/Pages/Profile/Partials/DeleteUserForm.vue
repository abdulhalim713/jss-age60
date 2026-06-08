<script setup>
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const showDeletePassword = ref(false);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    showDeletePassword.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-bold text-red-600 font-sans">
                অ্যাকাউন্ট মুছুন
            </h2>

            <p class="mt-1 text-sm text-gray-500 font-sans">
                আপনার অ্যাকাউন্টটি মুছে ফেলা হলে, এর সমস্ত সংস্থান এবং ডেটা স্থায়ীভাবে মুছে ফেলা হবে। অ্যাকাউন্টটি ডিলিট করার আগে, অনুগ্রহ করে আপনার প্রয়োজনীয় কোনো তথ্য ব্যাকআপ বা সংরক্ষণ করে রাখুন।
            </p>
        </header>

        <!-- Red Warning Button -->
        <button 
            @click="confirmUserDeletion"
            class="py-3 px-6 bg-gradient-to-r from-red-600 to-rose-500 hover:from-red-700 hover:to-rose-600 text-white font-bold rounded-xl shadow-md shadow-red-500/10 hover:shadow-red-500/25 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 font-sans focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
        >
            অ্যাকাউন্ট মুছুন
        </button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6 sm:p-8 space-y-6 bg-white rounded-3xl border border-gray-100">
                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-gray-900 font-sans">
                        আপনি কি নিশ্চিত যে আপনার অ্যাকাউন্টটি মুছে ফেলতে চান?
                    </h2>

                    <p class="text-sm text-gray-500 font-sans leading-relaxed">
                        আপনার অ্যাকাউন্টটি মুছে ফেলা হলে, এর সমস্ত সংস্থান এবং ডেটা স্থায়ীভাবে মুছে ফেলা হবে। অ্যাকাউন্টটি স্থায়ীভাবে মুছে ফেলার বিষয়টি নিশ্চিত করতে অনুগ্রহ করে আপনার অ্যাকাউন্টের পাসওয়ার্ডটি লিখুন।
                    </p>
                </div>

                <!-- Password input inside Modal -->
                <div class="space-y-2 max-w-md">
                    <label for="delete_password" class="block text-sm font-semibold text-gray-700 font-sans">
                        পাসওয়ার্ড
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <!-- Icon -->
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            id="delete_password"
                            ref="passwordInput"
                            :type="showDeletePassword ? 'text' : 'password'"
                            v-model="form.password"
                            placeholder="পাসওয়ার্ড লিখুন"
                            @keyup.enter="deleteUser"
                            class="block w-full pl-11 pr-11 py-3 bg-white border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 text-sm font-sans"
                        />
                        <!-- Password toggle button -->
                        <button
                            type="button"
                            @click="showDeletePassword = !showDeletePassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200 bg-transparent border-0 outline-none eye-toggle-btn"
                        >
                            <svg v-if="showDeletePassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <InputError :message="form.errors.password" class="mt-1 text-xs" />
                </div>

                <!-- Modal Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button
                        type="button"
                        @click="closeModal"
                        class="py-2.5 px-5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all duration-200 font-sans focus:outline-none"
                    >
                        বাতিল করুন
                    </button>

                    <button
                        type="button"
                        :disabled="form.processing"
                        @click="deleteUser"
                        class="py-2.5 px-5 bg-gradient-to-r from-red-600 to-rose-500 hover:from-red-700 hover:to-rose-600 text-white font-bold rounded-xl shadow-md shadow-red-500/10 hover:shadow-red-500/25 disabled:opacity-50 disabled:pointer-events-none transition-all duration-200 font-sans flex items-center gap-2"
                    >
                        <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>স্থায়ীভাবে মুছুন</span>
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>

