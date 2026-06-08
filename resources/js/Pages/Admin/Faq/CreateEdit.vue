<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    faq: Object, // null for create, object for edit
});

const isEditing = computed(() => !!props.faq);

const form = useForm({
    question:   props.faq?.question   ?? '',
    answer:     props.faq?.answer     ?? '',
    sort_order: props.faq?.sort_order ?? 0,
    is_active:  props.faq?.is_active  ?? true,
});

const submit = () => {
    if (isEditing.value) {
        form.put(route('admin.faq.update', props.faq.id), {
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.faq.store'), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'প্রশ্ন সম্পাদনা' : 'নতুন প্রশ্ন যোগ'" />
    <AdminLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <i :class="isEditing ? 'fas fa-edit text-primary' : 'fas fa-plus-circle text-success'" class="me-2"></i>
                {{ isEditing ? 'প্রশ্ন সম্পাদনা' : 'নতুন প্রশ্ন (FAQ) যোগ করুন' }}
            </h4>
            <Link :href="route('admin.faq.index')" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> তালিকায় ফিরুন
            </Link>
        </div>

        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white rounded-top-4 py-3 px-4">
                        <i class="fas fa-question-circle me-2"></i> প্রশ্নের বিবরণ
                    </div>
                    <div class="card-body p-4">
                        <form @submit.prevent="submit">
                            <!-- Question -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">প্রশ্ন <span class="text-danger">*</span></label>
                                <input
                                    v-model="form.question"
                                    type="text"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.question }"
                                    placeholder="যেমন: পুনর্মিলনী উৎসব কখন অনুষ্ঠিত হবে?"
                                    required
                                />
                                <div v-if="form.errors.question" class="invalid-feedback">{{ form.errors.question }}</div>
                            </div>

                            <!-- Answer -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">উত্তর <span class="text-danger">*</span></label>
                                <textarea
                                    v-model="form.answer"
                                    class="form-control rounded-3"
                                    :class="{ 'is-invalid': form.errors.answer }"
                                    rows="5"
                                    placeholder="প্রশ্নের বিস্তারিত উত্তর এখানে লিখুন..."
                                    required
                                ></textarea>
                                <div v-if="form.errors.answer" class="invalid-feedback">{{ form.errors.answer }}</div>
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

                            <!-- Is Active Toggle -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input
                                        v-model="form.is_active"
                                        class="form-check-input"
                                        type="checkbox"
                                        id="isActiveSwitch"
                                    />
                                    <label class="form-check-label fw-semibold" for="isActiveSwitch">
                                        সক্রিয় রাখুন (ওয়েবসাইটে দেখাবে)
                                    </label>
                                </div>
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
                                        {{ isEditing ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}
                                    </span>
                                </button>
                                <Link
                                    :href="route('admin.faq.index')"
                                    class="btn btn-outline-secondary px-4 fw-semibold"
                                >
                                    বাতিল
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-light">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-info-circle text-info me-2"></i>নির্দেশিকা</h5>
                        <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
                            <li class="mb-2">প্রশ্ন ও উত্তরগুলো সুন্দর ও সাবলীল বাংলায় লিখুন।</li>
                            <li class="mb-2">প্রদর্শন ক্রম নির্ধারণে ছোট মান দিলে সেটি FAQ তালিকার শুরুতে প্রদর্শিত হবে।</li>
                            <li class="mb-2">সাময়িকভাবে নিষ্ক্রিয় করতে চাইলে 'সক্রিয় রাখুন' টিকচিহ্নটি তুলে দিন।</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
