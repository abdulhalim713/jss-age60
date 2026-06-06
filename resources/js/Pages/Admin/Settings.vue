<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    event_date: props.settings.event_date ?? '2027-04-10',
});

const submit = () => {
    form.put(route('admin.settings.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="অনুষ্ঠান সেটিংস" />
    <AdminLayout>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i class="fas fa-cogs text-warning me-2"></i> অনুষ্ঠান সেটিংস
            </h4>
        </div>

        <!-- Success Alert -->
        <div v-if="$page.props.flash?.success" class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-xs" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ $page.props.flash.success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="row g-4">
            <!-- Settings Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-calendar-alt me-2 text-warning"></i> অনুষ্ঠান তারিখ নির্ধারণ
                    </div>
                    <div class="card-body p-4">
                        <form @submit.prevent="submit">
                            <!-- Event Date -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">অনুষ্ঠানের তারিখ (Event Date) <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.event_date"
                                    type="date"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.event_date }"
                                    required
                                />
                                <div class="form-text text-muted mt-1">
                                    নির্ধারিত তারিখটি হোমপেজে কতদিন বাকি তা দেখানোর জন্য ব্যবহার করা হবে।
                                </div>
                                <div v-if="form.errors.event_date" class="invalid-feedback">{{ form.errors.event_date }}</div>
                            </div>

                            <!-- Submit -->
                            <div class="d-flex gap-3">
                                <button
                                    type="submit"
                                    class="btn btn-warning px-5 fw-semibold text-dark"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing">
                                        <i class="fas fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে...
                                    </span>
                                    <span v-else>
                                        <i class="fas fa-save me-1"></i> সেটিংস সংরক্ষণ করুন
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Description / Info Card -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 bg-light">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i> দিন গণনা কিভাবে কাজ করে?
                        </h5>
                        <p class="text-secondary small mb-3">
                            ১. আপনি এখানে অনুষ্ঠানের যে তারিখটি সংরক্ষণ করবেন, ওয়েবসাইট হোমপেজে লাইভ কাউন্টডাউন টাইমারটি সেই অনুযায়ী কত দিন বাকি তা স্বয়ংক্রিয়ভাবে গণনা করবে।
                        </p>
                        <p class="text-secondary small mb-3">
                            ২. সংরক্ষিত তারিখের চেয়ে বর্তমান তারিখটি পার হয়ে গেলে হোমপেজে দিন বাকি "০" (শূন্য) দেখাবে।
                        </p>
                        <hr />
                        <div class="text-center p-3 bg-white rounded-3 border">
                            <i class="fas fa-gem text-warning fa-2x mb-2"></i>
                            <h6 class="fw-bold mb-1">হীরক জয়ন্তী ২০২৭</h6>
                            <span class="badge bg-dark text-warning px-3 py-1 rounded-pill mt-1">
                                {{ form.event_date }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.shadow-xs {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}
</style>
