<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/4emus.png') }}" alt="logo" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/4emus.png') }}" alt="" height="50">
            </span>
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/4emus.png') }}" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/4emus.png') }}" alt="" height="50">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link menu-link @if (Route::current()->getName() == 'admin.dashboard') active @endif"
                       aria-expanded="false">
                        <i class="ph-gauge"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)"
                       class="nav-link scheduleMenuBtn menu-link @if (Route::current()->getName() == 'schedules.index') active @endif"
                       aria-expanded="false">
                        <i class="ph-calendar-check"></i>
                        <span data-key="t-dashboards">Schedule</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)"
                       class="nav-link menu-link technicianMenuBtn @if (Route::current()->getName() == 'technicians.index') active @endif"
                       aria-expanded="false">
                        <i class="ph ph-gear"></i>
                        <span data-key="t-dashboards">Todays Live Schedule</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#sidebarCompany"
                       class="nav-link menu-link"
                       data-bs-toggle="collapse" role="button"
                       aria-expanded="false"
                    >
                        <i class="ph-buildings"></i> <span data-key="t-company-information">Prestarts</span>
                    </a>
                    <div class="menu-dropdown collapse"
                         id="sidebarCompany" style="">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="https://forms.4emus.com/light-vehicle-prestart-schedule-version?type=light-vehicle"
                                   target="_blank"
                                   class="nav-link"
                                   data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="right"
                                   data-key="t-company">Light Vehicle Prestart manual prestart</a>
                            </li>
                            <li class="nav-item">
                                <a href=""
                                   class="nav-link"
                                   data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="right"
                                   data-key="t-company">Backhoe Prestart or upload manual prestart</a>
                            </li>
                            <li class="nav-item">
                                <a href=""
                                   class="nav-link"
                                   data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="right"
                                   data-key="t-company">Bobcat Prestart or upload manual prestart</a>
                            </li>
                            <li class="nav-item">
                                <a href=""
                                   class="nav-link"
                                   data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="right"
                                   data-key="t-company">Dozer Prestart or upload manual prestart</a>
                            </li>
                            <li class="nav-item">
                                <a href=""
                                   class="nav-link"
                                   data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="right"
                                   data-key="t-company">Excavator Prestart or upload manual prestart</a>
                            </li>
                            <li class="nav-item">
                                <a href=""
                                   class="nav-link"
                                   data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="right"
                                   data-key="t-company">Forklift Prestart or upload manual prestart</a>
                            </li>
                            <li class="nav-item">
                                <a href=""
                                   class="nav-link"
                                   data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="right"
                                   data-key="t-company">Loader Prestart or upload manual prestart</a>
                            </li>
                            <li class="nav-item">
                                <a href=""
                                   class="nav-link"
                                   data-bs-container="body" data-bs-toggle="tooltip" data-bs-placement="right"
                                   data-key="t-company">Pumps, Generators, Telehandlers coming</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('koormal.contact') }}"
                       class="nav-link menu-link @if (Route::current()->getName() == 'koormal.contact') active @endif"
                       aria-expanded="false">
                        <i class="ph ph-phone-call"></i>
                        <span data-key="t-contact-koormal">Contact Koormal</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('4emus.contact') }}"
                       class="nav-link menu-link @if (Route::current()->getName() == '4emus.contact') active @endif"
                       aria-expanded="false">
                        <i class="ph ph-phone-call"></i>
                        <span data-key="t-contact-4emus">Contact 4Emus</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('database.backups.index') }}"
                       class="nav-link menu-link @if (Route::current()->getName() == 'database.backups.index') active @endif"
                       aria-expanded="false">
                        <i class="ph ph-database"></i>
                        <span data-key="t-database-backup">Database Backup</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
