<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
<base href="../" />
		<title>Admin SIPPUS - Sistem Informasi Presensi Pustakawan</title>
		<meta charset="utf-8" />
		<meta name="description" content="Admin Website Perpustakaan Ibrahimy hanya dapat diakses oleh pengelola dan staff yang diberi izin oleh pengelola" />
		<meta name="keywords" content="Admin Website Perpustakaan Ibrahimy hanya dapat diakses oleh pengelola dan staff yang diberi izin oleh pengelola" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Admin Website - Perpustakaan Ibrahimy" />
		<meta property="og:url" content="https://keenthemes.com/metronic" />
		<meta property="og:site_name" content="Metronic by Keenthemes" />

		<link rel="canonical" href="http://preview.keenthemes.comlayouts/light-sidebar.html" />
		<link rel="shortcut icon" href="admin/assets/media/logos/logo perpus icon.png" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="admin/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link href="admin/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="admin/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="admin/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>

        <style>
            body  {
                background-image: url("admin/assets/media/svg/bg.svg");
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-position: center;
                background-size: cover;
            }
        </style>

    </head>
	<!--end::Head-->
	<!--begin::Body-->
	<body onload="realtimeClock()" id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="true">
					<!--begin::Header container-->
					<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
						<!--begin::Sidebar mobile toggle-->
						<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
							<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
								<i class="ki-outline ki-abstract-14 fs-2 fs-md-1"></i>
							</div>
						</div>
						<!--end::Sidebar mobile toggle-->
						<!--begin::Mobile logo-->
						<div class="flex-1 d-flex align-items-center flex-lg-grow-0">
							<a href="/admin/home" class="d-lg-none">
								<img alt="Logo" src="admin/assets/media/logos/default-small.svg" class="h-30px" />
							</a>
						</div>
						<!--end::Mobile logo-->
						<!--begin::Header wrapper-->
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
							<!--begin::Menu wrapper-->
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
								<!--begin::Menu-->
								<div class="px-2 my-5 menu menu-rounded menu-column menu-lg-row my-lg-0 align-items-stretch fw-semibold px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
									<!--begin:Menu item-->
									<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
										<!--begin:Menu link-->
										<span class="menu-link">
											<span class="menu-title">Sistem Manajemen Website Perpustakaan Ibrahimy</span>
										</span>
										<!--end:Menu link-->
									</div>
									<!--end:Menu item-->
								</div>
								<!--end::Menu-->
							</div>
							<!--end::Menu wrapper-->
							<!--begin::Navbar-->
							<div class="shrink-0 app-navbar">
                                <div class="app-navbar-item ms-1 ms-md-4">
									<!--begin::Menu wrapper-->
                                    <?php
                                        $hari = Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y');
                                    ?>
                                    <span class="px-4 py-3 badge fs-7 badge-light-primary">
										<span class="bullet bullet-dot bg-primary h-6px w-6px animation-blink me-2"></span>
										<label class=""><?php echo e($hari); ?> - </label>
                                        <label id="clock" class="ms-2"></label>
                                    </span>
									<!--end::Menu wrapper-->
								</div>
								<!--begin::Theme mode-->
								<div class="app-navbar-item ms-1 ms-md-4">
									<!--begin::Menu toggle-->
									<a href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<i class="ki-outline ki-night-day theme-light-show fs-1"></i>
										<i class="ki-outline ki-moon theme-dark-show fs-1"></i>
									</a>
									<!--begin::Menu toggle-->
									<!--begin::Menu-->
									<div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
										<!--begin::Menu item-->
										<div class="px-3 my-0 menu-item">
											<a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="light">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-outline ki-night-day fs-2"></i>
												</span>
												<span class="menu-title">Terang</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="px-3 my-0 menu-item">
											<a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="dark">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-outline ki-moon fs-2"></i>
												</span>
												<span class="menu-title">Gelap</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="px-3 my-0 menu-item">
											<a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="system">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-outline ki-screen fs-2"></i>
												</span>
												<span class="menu-title">Sistem</span>
											</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu-->
								</div>
								<!--end::Theme mode-->
                                <?php
                                    $user = Auth::user();
                                ?>
								<!--begin::User menu-->
								<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
									<!--begin::Menu wrapper-->
									<div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<img src="<?php echo e(asset('admin/assets/media/' . $user->pustakawan->foto)); ?>" class="rounded-3" alt="user" />
									</div>
									<!--begin::User account menu-->
									<div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-275px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="px-3 menu-item">
											<div class="px-3 menu-content d-flex align-items-center">
												<!--begin::Avatar-->
												<div class="symbol symbol-50px me-5">
													<img src="<?php echo e(asset('/admin/assets/media/' . ($user->pustakawan->foto ))); ?>" class="w-100" />
												</div>
												<!--end::Avatar-->
												<!--begin::Username-->
												<div class="d-flex flex-column">
													<div class="fw-bold d-flex align-items-center fs-5">
                                                        <span class="px-2 py-1 badge badge-light-success fw-bold fs-8 ms-2">AKtif</span>
                                                    </div>
													<a href="#" class="fw-semibold text-muted text-hover-primary fs-7"></a>
												</div>
												<!--end::Username-->
											</div>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="my-2 separator"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="px-5 menu-item">
											<a href="#" class="px-5 menu-link">Profil</a>
										</div>
										<!--end::Menu item-->

										<!--begin::Menu item-->
										<div class="px-5 menu-item">
											<a href="<?php echo e(route('logout')); ?>" class="px-5 menu-link">Keluar</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::User account menu-->
									<!--end::Menu wrapper-->
								</div>
								<!--end::User menu-->
								<!--begin::Header menu toggle-->
								<div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
									<div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
										<i class="ki-outline ki-element-4 fs-1"></i>
									</div>
								</div>
								<!--end::Header menu toggle-->
								<!--begin::Aside toggle-->
								<!--end::Header menu toggle-->
							</div>
							<!--end::Navbar-->
						</div>
						<!--end::Header wrapper-->
					</div>
					<!--end::Header container-->
				</div>
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Sidebar-->
					<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
						<!--begin::Logo-->
						<div class="px-6 app-sidebar-logo" id="kt_app_sidebar_logo">
							<!--begin::Logo image-->
							<a href="index.html">
								<img alt="Logo" src="admin/assets/media/logos/default.svg" class="h-35px app-sidebar-logo-default theme-light-show" />
								<img alt="Logo" src="admin/assets/media/logos/default-dark.svg" class="h-35px app-sidebar-logo-default theme-dark-show" />
								<img alt="Logo" src="admin/assets/media/logos/default-small.svg" class="h-35px app-sidebar-logo-minimize" />
							</a>
							<!--end::Logo image-->
							<!--begin::Sidebar toggle-->
							<!--begin::Minimized sidebar setup:
            if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
                1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
            }
        -->
							<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
								<i class="rotate-180 ki-outline ki-black-left-line fs-3"></i>
							</div>
							<!--end::Sidebar toggle-->
						</div>
						<!--end::Logo-->
						<!--begin::sidebar menu-->
						<div class="overflow-hidden app-sidebar-menu flex-column-fluid">
							<!--begin::Menu wrapper-->
							<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
								<!--begin::Scroll wrapper-->
								<div id="kt_app_sidebar_menu_scroll" class="mx-3 my-5 scroll-y" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
									<!--begin::Menu-->
									<div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
										<!--begin:Menu item-->
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                            <a class="menu-link <?php echo e(request()->is('admin/home') ? 'active' : ''); ?>" href="admin/home">
                                                <span class="menu-icon">
													<i class="ki-outline ki-home fs-2"></i>
												</span>
												<span class="menu-title">Beranda</span>
											</a>
										</div>
										<!--end:Menu item-->
                                        <!--begin:Menu item-->
										<div class="pt-5 menu-item">
											<!--begin:Menu content-->
											<div class="menu-content">
												<span class="menu-heading fw-bold text-uppercase fs-7">Aplikasi</span>
											</div>
											<!--end:Menu content-->
										</div>
                                        <?php
											$absenPermission = [
												'absen ruang-lihat'
											];

											$absenActive = request()->is('admin/master-*');
										?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($absenPermission)): ?>
										<!--begin:Menu item-->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('absen ruang-lihat')): ?>
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link <?php echo e(request()->is('admin/absen-ruang') ? 'active' : ''); ?>" href="admin/absen-ruang">
												<span class="menu-icon">
													<i class="ki-duotone ki-archive-tick fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</span>
												<span class="menu-title">Ruang Absen</span>
											</a>
											<!--end:Menu link-->
										</div>
                                        <?php endif; ?>
										<!--end:Menu item-->
                                        <?php endif; ?>

										<?php
                                            $strukturalPermission = [
                                                'struktural geofencing-lihat',
                                                'struktural izin-lihat',
                                                'struktural barokah-lihat',
                                                'struktural rekap-lihat',
                                            ];

											$strukturalActive = request()->is('admin/struktural-*');
										?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($strukturalPermission)): ?>
                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion <?php echo e($strukturalActive ? 'here show' : ''); ?>">
                                            <span class="menu-link">
                                                <span class="menu-icon">
                                                    <i class="ki-outline ki-profile-circle fs-2"></i>
                                                </span>
                                                <span class="menu-title">Struktural</span>
                                                <span class="menu-arrow"></span>
                                            </span>
                                            <div class="menu-sub menu-sub-accordion">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('struktural geofencing-lihat')): ?>
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/struktural-geofencing') ? 'active' : ''); ?>" href="admin/struktural-geofencing">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Geofencing</span>
													</a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('struktural izin-lihat')): ?>
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/struktural-izin') ? 'active' : ''); ?>" href="admin/struktural-izin">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Izin</span>
													</a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('struktural barokah-lihat')): ?>
												<div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/struktural-barokah') ? 'active' : ''); ?>" href="admin/struktural-barokah">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Barokah</span>
													</a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('struktural rekap-lihat')): ?>
												<div class="menu-item">
													<!--begin:Menu link-->
													<a class="menu-link <?php echo e(request()->is('admin/struktural-rekap') ? 'active' : ''); ?>" href="admin/struktural-rekap">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Rekap</span>
													</a>
													<!--end:Menu link-->
												</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php
                                            $khidmahPermission = [
                                                'khidmah izin-lihat',
                                                'khidmah barokah-lihat',
                                                'khidmah rekap-lihat',
                                            ];

											$khidmahActive = request()->is('admin/khidmah-*');
										?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($khidmahPermission)): ?>
                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion <?php echo e($khidmahActive ? 'here show' : ''); ?>">
                                            <span class="menu-link">
                                                <span class="menu-icon">
                                                    <i class="ki-outline ki-user-tick fs-2"></i>
                                                </span>
                                                <span class="menu-title">Tenaga Khidmah</span>
                                                <span class="menu-arrow"></span>
                                            </span>
                                            <div class="menu-sub menu-sub-accordion">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('khidmah izin-lihat')): ?>
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/khidmah-izin') ? 'active' : ''); ?>" href="admin/khidmah-izin">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span class="menu-title">Izin Khidmah</span>
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('khidmah barokah-lihat')): ?>
												<div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/khidmah-barokah') ? 'active' : ''); ?>" href="<?php echo e(route('khidmah.barokah.index')); ?>">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Barokah</span>
													</a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('khidmah rekap-lihat')): ?>
												<div class="menu-item">
													<!--begin:Menu link-->
                                                    <a class="menu-link <?php echo e(request()->is('admin/khidmah-rekap') ? 'active' : ''); ?>" href="admin/khidmah-rekap">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Rekap</span>
													</a>
													<!--end:Menu link-->
												</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php
                                            $viarPermission = [
                                                'viar izin-lihat',
                                                'viar barokah-lihat',
                                                'viar rekap-lihat'
                                            ];

											$viarActive = request()->is('admin/viar-*');
										?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($viarPermission)): ?>
                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion <?php echo e($viarActive ? 'here show' : ''); ?>">
                                            <span class="menu-link">
                                                <span class="menu-icon">
                                                    <i class="ki-outline ki-delivery-time fs-2"></i>
                                                </span>
                                                <span class="menu-title">Viar</span>
                                                <span class="menu-arrow"></span>
                                            </span>
                                            <div class="menu-sub menu-sub-accordion">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viar izin-lihat')): ?>
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->routeIs('viar.izin.index') ? 'active' : ''); ?>" href="<?php echo e(route('viar.izin.index')); ?>">
                                                        <span class="menu-bullet">
                                                            <span class="bullet bullet-dot"></span>
                                                        </span>
                                                        <span class="menu-title">Izin Viar</span>
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viar barokah-lihat')): ?>
												<div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->routeIs('viar.barokah.index') ? 'active' : ''); ?>" href="<?php echo e(route('viar.barokah.index')); ?>">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Barokah</span>
													</a>
                                                </div>
                                                <?php endif; ?>
                                                <?php if(auth()->user()->hasPermissionTo('viar rekap-lihat')): ?>
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->routeIs('viar.rekap.absen') ? 'active' : ''); ?>" href="<?php echo e(route('viar.rekap.absen')); ?>">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Rekap</span>
													</a>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

										<div class="pt-5 menu-item">
											<!--begin:Menu content-->
											<div class="menu-content">
												<span class="menu-heading fw-bold text-uppercase fs-7">Data Master</span>
											</div>
											<!--end:Menu content-->
										</div>

										<?php
                                            $masterPermission = [
                                                'master pustakawan-lihat',
                                                'master jadwal-lihat',
                                                'master libur-lihat',
                                                'master ruang-lihat',
                                                'master jabatan-lihat'
                                            ];

											$masterActive = request()->is('admin/master-*');
										?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($masterPermission)): ?>
										<!--begin:Menu item-->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('master pustakawan-lihat')): ?>
										<div class="menu-item">
											<!--begin:Menu link-->
											<a class="menu-link <?php echo e(request()->is('admin/master-pustakawan') ? 'active' : ''); ?>" href="admin/master-pustakawan">
												<span class="menu-icon">
													<i class="ki-duotone ki-user fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</span>
												<span class="menu-title">Pustakawan</span>
											</a>
											<!--end:Menu link-->
										</div>
                                        <?php endif; ?>
										<!--end:Menu item-->
										<!--begin:Menu item-->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('master jadwal-lihat')): ?>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                            <a class="menu-link <?php echo e(request()->is('admin/master-jadwal') ? 'active' : ''); ?>" href="admin/master-jadwal">
                                                <span class="menu-icon">
													<i class="ki-outline ki-book fs-2"></i>
												</span>
												<span class="menu-title">Jadwal</span>
											</a>
										</div>
                                        <?php endif; ?>
										<!--end:Menu item-->
										<!--begin:Menu item-->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('master libur-lihat')): ?>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                            <a class="menu-link <?php echo e(request()->is('admin/master-libur') ? 'active' : ''); ?>" href="admin/master-libur">
                                                <span class="menu-icon">
													<i class="ki-outline ki-abstract-16 fs-2"></i>
												</span>
												<span class="menu-title">Libur</span>
											</a>
										</div>
                                        <?php endif; ?>
										<!--end:Menu item-->
										<!--begin:Menu item-->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('master ruang-lihat')): ?>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                            <a class="menu-link <?php echo e(request()->is('admin/master-ruang') ? 'active' : ''); ?>" href="admin/master-ruang">
                                                <span class="menu-icon">
													<i class="ki-outline ki-cheque fs-2"></i>
												</span>
												<span class="menu-title">Ruang</span>
											</a>
										</div>
                                        <?php endif; ?>
										<!--end:Menu item-->
										<!--begin:Menu item-->
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('master jabatan-lihat')): ?>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                            <a class="menu-link <?php echo e(request()->is('admin/master-jabatan') ? 'active' : ''); ?>" href="admin/master-jabatan">
                                                <span class="menu-icon">
													<i class="ki-outline ki-user-square fs-2"></i>
												</span>
												<span class="menu-title">Jabatan</span>
											</a>
										</div>
                                        <?php endif; ?>
										<!--end:Menu item-->
                                        <?php endif; ?>


										<div class="pt-5 menu-item">
											<!--begin:Menu content-->
											<div class="menu-content">
												<span class="menu-heading fw-bold text-uppercase fs-7">Payroll</span>
											</div>
											<!--end:Menu content-->
										</div>

										<?php
                                            $tunjanganPermission = [
                                                'tunjangan kehadiran-lihat',
                                                'tunjangan jabatan-lihat',
                                                'tunjangan pengabdian-lihat',
                                                'tunjangan tunkel-lihat',
                                                'tunjangan kehormatan-lihat',
                                                'tunjangan anak-lihat',
                                                'tunjangan rank-lihat',
                                            ];

											$tunjanganActive = request()->is('admin/payroll-*');
										?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($tunjanganPermission)): ?>
										<!--begin:Menu item-->
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion <?php echo e($tunjanganActive ? 'here show' : ''); ?>">
											<span class="menu-link">
                                                <span class="menu-icon">
                                                    <i class="ki-outline ki-financial-schedule fs-2"></i>
                                                </span>
                                                <span class="menu-title">Tunjangan</span>
                                                <span class="menu-arrow"></span>
                                            </span>
											<!--begin:Menu sub-->
                                            <div class="menu-sub menu-sub-accordion">
												<!--begin:Menu item-->
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tunjangan kehadiran-lihat')): ?>
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/payroll-kehadiran') ? 'active' : ''); ?>" href="admin/payroll-kehadiran">
                                                        <span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                                        <span class="menu-title">Kehadiran</span>
                                                    </a>
                                                </div>
                                                <?php endif; ?>
												<!--end:Menu item-->
												<!--begin:Menu item-->
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('tunjangan jabatan-lihat')): ?>
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/payroll-jabatan') ? 'active' : ''); ?>" href="admin/payroll-jabatan">
                                                        <span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                                        <span class="menu-title">Jabatan</span>
                                                    </a>
                                                </div>
                                                <?php endif; ?>
												<!--end:Menu item-->
												<!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/payroll-pengabdian') ? 'active' : ''); ?>" href="admin/payroll-pengabdian">
                                                        <span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                                        <span class="menu-title">Pengabdian</span>
                                                    </a>
                                                </div>
												<!--end:Menu item-->
												<!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/payroll-tunkel') ? 'active' : ''); ?>" href="admin/payroll-tunkel">
                                                        <span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                                        <span class="menu-title">Tunkel</span>
                                                    </a>
                                                </div>
												<!--end:Menu item-->
												<!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/payroll-kehormatan') ? 'active' : ''); ?>" href="admin/payroll-kehormatan">
                                                        <span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                                        <span class="menu-title">Kehormatan</span>
                                                    </a>
                                                </div>
												<!--end:Menu item-->
												<!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/payroll-anak') ? 'active' : ''); ?>" href="admin/payroll-anak">
                                                        <span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                                        <span class="menu-title">Anak</span>
                                                    </a>
                                                </div>
												<!--end:Menu item-->
												<!--begin:Menu item-->
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/payroll-rankDosen') ? 'active' : ''); ?>" href="admin/payroll-rankDosen">
                                                        <span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
                                                        <span class="menu-title">Rank Dosen</span>
                                                    </a>
                                                </div>
												<!--end:Menu item-->
                                            </div>
											<!--end:Menu sub-->
                                        </div>
										<!--end:Menu item-->
                                        <?php endif; ?>

										<div class="pt-5 menu-item">
											<!--begin:Menu content-->
											<div class="menu-content">
												<span class="menu-heading fw-bold text-uppercase fs-7">Hak Akses</span>
											</div>
											<!--end:Menu content-->
										</div>

										<?php
                                            $penggunaActive = request()->is('admin/pengguna*');
                                        ?>
                                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion <?php echo e($penggunaActive ? 'here show' : ''); ?>">
                                            <span class="menu-link">
                                                <span class="menu-icon">
                                                    <i class="ki-outline ki-address-book fs-2"></i>
                                                </span>
                                                <span class="menu-title">Pengguna</span>
                                                <span class="menu-arrow"></span>
                                            </span>
                                            <div class="menu-sub menu-sub-accordion">
                                                <div class="menu-item">
                                                    <a class="menu-link <?php echo e(request()->is('admin/pengguna') ? 'active' : ''); ?>" href="admin/pengguna">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Data Pengguna</span>
													</a>
                                                </div>
												<div class="menu-item">
													<!--begin:Menu link-->
													<a class="menu-link <?php echo e(request()->is('admin/pengguna-akses') ? 'active' : ''); ?>" href="admin/pengguna-akses">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Akses</span>
													</a>
													<!--end:Menu link-->
												</div>
                                            </div>
                                        </div>
										<!--end:Menu item-->
									</div>
									<!--end::Menu-->
								</div>
								<!--end::Scroll wrapper-->
							</div>
							<!--end::Menu wrapper-->
						</div>
						<!--end::sidebar menu-->
						<!--begin::Footer-->

						<!--end::Footer-->
					</div>
					<!--end::Sidebar-->
					<!--begin::Main-->


					<style>
						.maintenance-alert {
							background-color: #f59e0b; /* Amber */
							animation: pulse 1.5s ease-in-out infinite;
							font-weight: bold;
							position: relative;
							z-index: 9999;
						}

						@keyframes pulse {
							0%, 100% {
								opacity: 1;
							}
							50% {
								opacity: 0.6;
							}
						}
					</style>

					<?php echo $__env->yieldContent('admin-konten'); ?>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
        <?php if(session('success')): ?>
        <script>
            Swal.fire({
                title: 'Alhamdulillah!',
                text: '<?php echo e(session('success')); ?>',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            });
        </script>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <script>
            Swal.fire({
                title: 'Astaghfirullah!',
                text: '<?php echo e(session('error')); ?>',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
        </script>
        <?php endif; ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.delete-button').forEach(function (button) {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const url = this.getAttribute('href'); // Ambil URL dari atribut href

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data ini akan dihapus dan tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            customClass: {
                                confirmButton: 'btn btn-danger', // Gaya tombol
                                cancelButton: 'btn btn-secondary'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect ke URL untuk menghapus data
                                window.location.href = url;
                            }
                        });
                    });
                });
            });
        </script>
		<!--beign::Script Maintenance-->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.maintenance').forEach(function (button) {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const url = this.getAttribute('href'); // Ambil URL dari atribut href

                        Swal.fire({
                            title: 'Mohon maaf',
                            text: "Halaman ini sedang dalam perbaikan",
                            icon: 'warning',
                            showCancelButton: false,
                            cancelButtonText: 'Kembali',
                            customClass: {
                                confirmButton: 'btn btn-danger', // Gaya tombol
                                cancelButton: 'btn btn-secondary'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect ke URL untuk menghapus data
                                window.location.href = url;
                            }
                        });
                    });
                });
            });
        </script>
		<!--end::Script Maintenance-->
		<!--begin::Script Warning-->
		<script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.warning').forEach(function (button) {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const url = this.getAttribute('href'); // Ambil URL dari atribut href

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Setelah data ini disimpan tidak bisa diedit kembali!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, simpan!',
                            cancelButtonText: 'Batal',
                            customClass: {
                                confirmButton: 'btn btn-danger', // Gaya tombol
                                cancelButton: 'btn btn-secondary'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect ke URL untuk menghapus data
                                window.location.href = url;
                            }
                        });
                    });
                });
            });
        </script>
		<!--end::Javascript-->

		<!--begin::Javascript-->
		<script>var hostUrl = "admin/assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="admin/assets/plugins/global/plugins.bundle.js"></script>
		<script src="admin/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="admin/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		<script src="admin/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="admin/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="<?php echo e(asset('admin/assets/js/jam.js')); ?>"></script>
		<script src="<?php echo e(asset('admin/assets/js/custom/widgets.js')); ?>"></script>
		<script src="<?php echo e(asset('admin/assets/js/custom/widgets.js')); ?>"></script>
		<script src="<?php echo e(asset('admin/assets/js/custom/apps/chat/chat.js')); ?>"></script>
		<script src="<?php echo e(asset('admin/assets/js/custom/utilities/modals/upgrade-plan.js')); ?>"></script>
		<script src="<?php echo e(asset('admin/assets/js/custom/utilities/modals/create-app.js')); ?>"></script>
		<script src="<?php echo e(asset('admin/assets/js/custom/utilities/modals/users-search.js')); ?>"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
<?php /**PATH /home/sever/ols-docker-env/sites/sippus.lib.ibrahimy.ac.id/html/resources/views/layout/sidebarnavbar.blade.php ENDPATH**/ ?>