<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const isCollapsed = ref(false);

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
    if (isCollapsed.value) {
        document.body.classList.add('sidebar-collapse');
        document.body.classList.remove('sidebar-open');
    } else {
        document.body.classList.remove('sidebar-collapse');
        document.body.classList.add('sidebar-open');
    }
};

onMounted(() => {
    // Add required body classes for AdminLTE v4 layout with sidebar-mini support
    document.body.className = 'layout-fixed sidebar-expand-lg sidebar-mini bg-body-tertiary';
    
    // Auto collapse on small screens
    const handleResize = () => {
        if (window.innerWidth < 992) {
            isCollapsed.value = true;
            document.body.classList.add('sidebar-collapse');
            document.body.classList.remove('sidebar-open');
        } else {
            isCollapsed.value = false;
            document.body.classList.remove('sidebar-collapse');
            document.body.classList.remove('sidebar-open');
        }
    };
    
    window.addEventListener('resize', handleResize);
    handleResize(); // run initially
});

onUnmounted(() => {
    // Clean up classes
    document.body.className = 'font-sans antialiased';
});
</script>

<template>
    <div class="app-wrapper">
        <!-- Header -->
        <nav class="app-header navbar navbar-expand bg-white border-bottom shadow-xs">
            <div class="container-fluid">
                <!-- Left Links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a 
                            class="nav-link" 
                            href="#" 
                            @click.prevent="toggleSidebar" 
                            role="button"
                            style="cursor: pointer;"
                        >
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-inline-block">
                        <Link :href="route('dashboard')" class="nav-link text-dark">ড্যাসবোর্ড</Link>
                    </li>
                    <li class="nav-item d-none d-md-inline-block">
                        <a href="/" target="_blank" class="nav-link text-primary fw-semibold">
                            <i class="fas fa-external-link-alt me-1"></i> মূল সাইট
                        </a>
                    </li>
                </ul>

                <!-- Right Links -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a 
                            href="#" 
                            class="nav-link dropdown-toggle d-flex align-items-center" 
                            data-bs-toggle="dropdown"
                        >
                            <span class="d-none d-md-inline me-2 fw-semibold">{{ $page.props.auth.user.name }}</span>
                            <i class="fas fa-user-circle fs-4 text-primary"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow-sm">
                            <!-- User Header -->
                            <li class="user-header bg-primary text-white text-center p-3">
                                <i class="fas fa-user-circle fs-1 mb-2"></i>
                                <h5>{{ $page.props.auth.user.name }}</h5>
                                <p class="small mb-0">{{ $page.props.auth.user.email }}</p>
                            </li>
                            <!-- Menu Footer -->
                            <li class="user-footer d-flex justify-content-between p-3 bg-light">
                                <Link 
                                    :href="route('profile.edit')" 
                                    class="btn btn-sm btn-outline-secondary px-3"
                                >
                                    প্রোফাইল
                                </Link>
                                <Link 
                                    :href="route('logout')" 
                                    method="post" 
                                    as="button" 
                                    class="btn btn-sm btn-danger px-3"
                                >
                                    লগ আউট
                                </Link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="app-sidebar bg-dark navbar-dark" data-bs-theme="dark">
            <!-- Sidebar Brand -->
            <div class="sidebar-brand d-flex align-items-center justify-content-center py-3 border-bottom border-secondary">
                <Link :href="route('dashboard')" class="brand-link text-decoration-none d-flex align-items-center gap-2">
                    <i class="fas fa-gem text-warning fs-4"></i>
                    <span class="brand-text fw-bold text-white fs-5">হীরক জয়ন্তী ২০২৭</span>
                </Link>
            </div>
            
            <!-- Sidebar Menu -->
            <div class="sidebar-wrapper py-3">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column gap-1 px-2" role="menu">
                        <li class="nav-item">
                            <Link 
                                :href="route('dashboard')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-primary text-white': route().current('dashboard') }"
                            >
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <span>ড্যাসবোর্ড</span>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link 
                                :href="route('admin.alumni.index')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-primary text-white': route().current('admin.alumni.index') }"
                            >
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <span>নিবন্ধিত অ্যালামনাই</span>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link 
                                :href="route('admin.users.index')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-primary text-white': route().current('admin.users.*') }"
                            >
                                <i class="nav-icon fas fa-users-cog"></i>
                                <span>ইউজার ব্যবস্থাপনা</span>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link 
                                :href="route('admin.feedback.index')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-primary text-white': route().current('admin.feedback.index') }"
                            >
                                <i class="nav-icon fas fa-comment-dots"></i>
                                <span>অভিযোগ ও পরামর্শ</span>
                            </Link>
                        </li>

                        <!-- সাইটের বিভাজন লাইন -->
                        <li class="nav-item pt-2 pb-1">
                            <small class="text-white-25 text-uppercase px-3" style="font-size:0.65rem;letter-spacing:0.08em;">সাইট ব্যবস্থাপনা</small>
                        </li>

                        <li class="nav-item">
                            <Link 
                                :href="route('admin.hero.index')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-warning text-dark': route().current('admin.hero.*') }"
                            >
                                <i class="nav-icon fas fa-image"></i>
                                <span>হিরো সেটিং</span>
                            </Link>
                        </li>

                        <li class="nav-item">
                            <Link 
                                :href="route('admin.gallery.index')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-warning text-dark': route().current('admin.gallery.*') }"
                            >
                                <i class="nav-icon fas fa-images"></i>
                                <span>গ্যালারি ব্যবস্থাপনা</span>
                            </Link>
                        </li>

                        <li class="nav-item">
                            <Link 
                                :href="route('admin.committee.index')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-warning text-dark': route().current('admin.committee.*') }"
                            >
                                <i class="nav-icon fas fa-users-gear"></i>
                                <span>কমিটি ব্যবস্থাপনা</span>
                            </Link>
                        </li>

                        <li class="nav-item">
                            <Link 
                                :href="route('admin.sponsors.index')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-warning text-dark': route().current('admin.sponsors.*') }"
                            >
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <span>স্পন্সর ব্যবস্থাপনা</span>
                            </Link>
                        </li>

                        <li class="nav-item">
                            <Link 
                                :href="route('admin.faq.index')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-warning text-dark': route().current('admin.faq.*') }"
                            >
                                <i class="nav-icon fas fa-question-circle"></i>
                                <span>সচরাচর জিজ্ঞাসা (FAQ)</span>
                            </Link>
                        </li>

                        <li class="nav-item">
                            <Link 
                                :href="route('admin.settings.edit')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-warning text-dark': route().current('admin.settings.*') }"
                            >
                                <i class="nav-icon fas fa-cogs"></i>
                                <span>অনুষ্ঠান সেটিংস</span>
                            </Link>
                        </li>

                        <li class="nav-item">
                            <Link 
                                :href="route('profile.edit')" 
                                class="nav-link px-3 py-2 rounded-2 text-decoration-none d-flex align-items-center gap-3 text-white-50" 
                                :class="{ 'active bg-primary text-white': route().current('profile.edit') }"
                            >
                                <i class="nav-icon fas fa-user-cog"></i>
                                <span>প্রোফাইল সেটিংস</span>
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="app-main py-4 bg-light min-vh-100">
            <div class="container-fluid px-4">
                <slot />
            </div>
        </main>

        <!-- Footer -->
        <footer class="app-footer text-center py-3 border-top bg-white small text-muted">
            <span>© ২০২৭ জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয় হীরক জয়ন্তী উৎসব | সর্বস্বত্ব সংরক্ষিত</span>
        </footer>

        <!-- Sidebar Overlay (for mobile collapse) -->
        <div 
            v-if="!isCollapsed" 
            class="sidebar-overlay d-lg-none" 
            @click="toggleSidebar"
        ></div>
    </div>
</template>

<style>
@import 'bootstrap/dist/css/bootstrap.min.css';
@import 'admin-lte/dist/css/adminlte.min.css';
@import '@fortawesome/fontawesome-free/css/all.min.css';

/* Custom styling to ensure proper layout and font */
body.layout-fixed {
    font-family: 'Kalpurush', 'Hind Siliguri', sans-serif !important;
}

.brand-link:hover {
    opacity: 0.9;
}
</style>
