<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="লগইন | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়" />

    <div class="login-wrapper min-h-screen w-full flex flex-col items-center justify-center bg-[#07222B] relative overflow-hidden px-4 py-12">
        <!-- Background decorative blobs -->
        <div class="absolute top-[-10%] right-[-10%] w-[300px] sm:w-[450px] h-[300px] sm:h-[450px] rounded-full bg-[#D4AF37] opacity-10 blur-[80px] pointer-events-none"></div>
        <div class="absolute bottom-[-15%] left-[-15%] w-[350px] sm:w-[500px] h-[350px] sm:h-[500px] rounded-full bg-[#0F4C5C] opacity-30 blur-[100px] pointer-events-none"></div>
        
        <!-- Login Card -->
        <div class="w-full max-w-[440px] bg-white/5 backdrop-blur-2xl border border-white/10 shadow-2xl rounded-3xl p-6 sm:p-10 z-10 transition-all duration-300 hover:border-white/15">
            
            <!-- Logo and School Title -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-block group">
                    <img 
                        src="/images/hirak-jayanti-logo.png" 
                        alt="জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়" 
                        class="h-20 w-auto mx-auto mb-4 drop-shadow-[0_8px_16px_rgba(0,0,0,0.3)] group-hover:scale-105 transition-transform duration-300" 
                    />
                </Link>
                <h2 class="text-2xl font-bold text-white tracking-wide font-sans">হীরক জয়ন্তী ২০২৭</h2>
                <p class="text-[#D4AF37] text-sm font-medium mt-1 font-sans">প্রাক্তন শিক্ষার্থী পোর্টাল লগইন</p>
            </div>

            <!-- Session Status Alert -->
            <div v-if="status" class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm text-center font-medium font-sans">
                {{ status }}
            </div>

            <!-- Login Form -->
            <form @submit.prevent="submit" class="space-y-6">
                
                <!-- Email/Username Input -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-300 font-sans">
                        ইমেল ঠিকানা / ইউজারনেম
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <!-- Icon -->
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="example@mail.com"
                            class="block w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent transition-all duration-300 text-sm font-sans"
                        />
                    </div>
                    <InputError class="mt-1 text-xs" :message="form.errors.email" />
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-semibold text-gray-300 font-sans">
                            পাসওয়ার্ড
                        </label>
                    </div>
                    <div class="relative rounded-xl shadow-sm">
                        <!-- Icon -->
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="block w-full pl-11 pr-11 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:border-transparent transition-all duration-300 text-sm font-sans"
                        />
                        <!-- Password toggle button -->
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-white transition-colors duration-200 bg-transparent border-0 outline-none eye-toggle-btn"
                        >
                            <svg v-if="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <InputError class="mt-1 text-xs" :message="form.errors.password" />
                </div>

                <!-- Remember me and Forgot Password -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            v-model="form.remember"
                            class="h-4.5 w-4.5 rounded border-white/20 bg-white/5 text-[#D4AF37] focus:ring-offset-[#07222B] focus:ring-[#D4AF37]"
                        />
                        <span class="ml-2 text-gray-300 font-medium font-sans">আমায় মনে রাখুন</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-[#D4AF37] hover:text-[#f3d060] font-semibold underline-offset-4 hover:underline transition-colors duration-200 font-sans"
                    >
                        পাসওয়ার্ড ভুলে গেছেন?
                    </Link>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="relative w-full py-3 bg-gradient-to-r from-[#D4AF37] to-[#F3D060] text-[#07222B] font-bold rounded-xl shadow-lg shadow-[#D4AF37]/10 hover:shadow-[#D4AF37]/25 hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#D4AF37] focus:ring-offset-2 focus:ring-offset-[#07222B] disabled:opacity-50 disabled:pointer-events-none transition-all duration-300 font-sans flex items-center justify-center gap-2"
                >
                    <svg v-if="form.processing" class="animate-spin h-5 w-5 text-[#07222B]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>লগইন করুন</span>
                </button>
            </form>

            <!-- Back to Home -->
            <div class="mt-8 pt-6 border-t border-white/10 text-center">
                <Link
                    href="/"
                    class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-white transition-colors duration-200 font-medium font-sans"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    মূল ওয়েবসাইটে ফিরে যান
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Scoped overrides to enforce high premium feel and font settings */
.login-wrapper {
    font-family: 'Kalpurush', 'Hind Siliguri', sans-serif;
}
.login-wrapper *,
.login-wrapper *::before,
.login-wrapper *::after {
    box-sizing: border-box !important;
}
.login-wrapper button.eye-toggle-btn {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    outline: none !important;
    cursor: pointer;
}
.login-wrapper input {
    box-sizing: border-box !important;
}
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus {
    -webkit-text-fill-color: white !important;
    -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.05) inset !important;
    transition: background-color 5000s ease-in-out 0s;
}
</style>
