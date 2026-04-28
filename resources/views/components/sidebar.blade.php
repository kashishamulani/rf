<style>
/* ================= SIDEBAR ================= */
.sidebar {
    width: 200px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    padding: 22px 18px;
    background: linear-gradient(160deg, #6366f1, #8b5cf6, #ec4899);
    color: #fff;
    box-shadow: 0 20px 60px rgba(99, 102, 241, 0.35);
    z-index: 1000;
    overflow-y: auto;
    transition: 0.4s ease;
}

/* Sidebar hidden */
.sidebar.closed {
    transform: translateX(-100%);
}

/* Scrollbar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.4);
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.7);
}

/* Logo */
.sidebar .logo {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 30px;
    text-align: center;
}

/* Menu */
.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar li {
    margin-bottom: 12px;
}

/* All Links - Base Style */
.sidebar a {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 10px;
    width: 100%;
    padding: 12px 16px;
    border-radius: 12px;
    text-decoration: none;
    color: #ffffff !important;
    /* Force white text by default */
    font-size: 14px;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.08);
}

/* Text inside link */
.sidebar a .arrow {
    margin-left: auto;
    color: #ffffff;
    /* White arrow by default */
}

/* ================= MAIN MENU HOVER STYLES ================= */
/* Hover state for all menu items */
.sidebar a:hover {
    background: #ffffff !important;
    color: #4f46e5 !important;
    /* Dark indigo for text */
    font-weight: 600;
}

/* Hover state for icons and arrows */
.sidebar a:hover span:not(.arrow),
.sidebar a:hover .arrow,
.sidebar a:hover i {
    color: #4f46e5 !important;
    /* Dark indigo for icons and arrows */
}

/* Specific hover for main menu items */
.menu-group>a:hover {
    background: #ffffff !important;
    color: #4f46e5 !important;
    font-weight: 600;
}

/* Hover state for main menu arrow */
.menu-group>a:hover .arrow {
    color: #4f46e5 !important;
}

/* Active state */
.sidebar ul>li>a.active {
    background: #ffffff;
    color: #4f46e5 !important;
    font-weight: 600;
}

.sidebar ul>li>a.active span,
.sidebar ul>li>a.active .arrow {
    color: #4f46e5 !important;
}

/* ================= DROPDOWN STYLES ================= */
.menu-group {
    margin-bottom: 10px;
}

.menu-group>a {
    cursor: pointer;
    user-select: none;
    justify-content: space-between;
    align-items: center;
    display: flex;
    color: #ffffff;
}

.menu-group .submenu {
    margin-top: 6px;
    margin-left: 8px;
    display: none;
    flex-direction: column;
    gap: 8px;
}

.menu-group.active .submenu {
    display: flex;
}

.menu-group.active>a {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-weight: 600;
}

/* Submenu links */
.menu-group .submenu a {
    font-size: 13px;
    padding: 10px 14px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    color: #ffffff !important;
}

/* Submenu hover */
.menu-group .submenu a:hover {
    background: #ffffff !important;
    color: #4f46e5 !important;
    font-weight: 600;
    transform: translateX(5px);
}

/* Submenu active */
.menu-group .submenu a.active {
    background: #ffffff !important;
    color: #4f46e5 !important;
    font-weight: 600;
}

.menu-group .submenu a.active span {
    color: #4f46e5 !important;
}

/* Arrow rotation */
.menu-group>a span.arrow {
    transition: transform 0.3s ease;
    color: #ffffff;
}

.menu-group.active>a span.arrow {
    transform: rotate(180deg);
}

/* Fix for text overflow */
.sidebar a span:not(.arrow) {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: inherit;
    /* Inherit color from parent */
}

/* Dashboard link specific */
li:not(.menu-group)>a:hover {
    background: #ffffff !important;
    color: #4f46e5 !important;
}

li:not(.menu-group)>a:hover span {
    color: #4f46e5 !important;
}

/* Debug styles - remove after fixing */
.sidebar a {
    border: 1px solid transparent;
    /* For debugging */
}
</style>

<aside class="sidebar" id="sidebar">
    <div class="logo">⚡ Reliance</div>

    <ul>
        {{-- DASHBOARD --}}
        <li>
            <a href="{{ route('dashboard') }}" class="{{ Route::is('dashboard') ? 'active' : '' }}">
                <span>🏠 Dashboard</span>
            </a>
        </li>

        {{-- PROJECT --}}
        <li class="menu-group" id="projectMenu">
            <a id="projectToggle">
                <span>📁 Project</span>
                <span class="arrow">▾</span>
            </a>
            <div class="submenu">
                <a href="{{ route('assignments.index') }}"
                    class="{{ Route::is('assignments.*') ? 'active' : '' }}"><span>📦 Assignments</span></a>
                <a href="{{ route('batches.index') }}" class="{{ Route::is('batches.*') ? 'active' : '' }}"><span>🧩
                        Batches</span></a>
                <a href="{{ route('invoices.index') }}" class="{{ Route::is('invoices.*') ? 'active' : '' }}"><span>🧾
                        Invoices</span></a>
                <a href="{{ route('payments.index') }}" class="{{ Route::is('payments.*') ? 'active' : '' }}"><span>💰
                        Payments</span></a>

            </div>
        </li>

        {{-- PROGRESS --}}
        <!-- <li class="menu-group {{ Route::is('activity-assignments.*','team.task.report','reporting.log','assignments.progress') ? 'active' : '' }}"
            id="progressMenu"> -->
        <li class="menu-group" id="progressMenu">
            <a id="progressToggle">
                <span>📊 Progress</span>
                <span class="arrow">▾</span>
            </a>
            <div class="submenu">
                <a href="{{ route('activity-assignments.index') }}"
                    class="{{ Route::is('activity-assignments.*') ? 'active' : '' }}"><span>📌 Activity
                        Assignments</span></a>
                <a href="{{ route('team.task.report') }}"
                    class="{{ Route::is('team.task.report') ? 'active' : '' }}"><span>📋 Team Task Report</span></a>
                <a href="{{ route('reporting.log') }}" class="{{ Route::is('reporting.log') ? 'active' : '' }}"><span>📝
                        Reporting Log</span></a>
                <a href="{{ route('assignments.progress') }}"
                    class="{{ Route::is('assignments.progress') ? 'active' : '' }}"><span>📈 Assignment
                        Progress</span></a>
            </div>
        </li>

        {{-- CANDIDATE --}}
        <li class="menu-group" id="candidateMenu">
            <a id="candidateToggle">
                <span>👨‍🎓 Candidate</span>
                <span class="arrow">▾</span>
            </a>
            <div class="submenu">

                <a href="{{ route('links.index') }}" class="{{ Route::is('links.*') ? 'active' : '' }}"><span>🔗 Forms
                        template</span></a>

                <a href="{{ route('link-assignments.index') }}"class="{{ Route::is('link-assignments.*') ? 'active' : '' }}">
                    <span>🔗 Assignment Forms</span>
                </a>

                <a href="{{ route('mobilizations.index') }}"class="{{ Route::is('mobilizations.*') ? 'active' : '' }}"><span>🗄 Mobilization</span></a>
            </div>
        </li>

        {{-- MASTER --}}
       
        <li class="menu-group" id="managementMenu">
            <a id="managementToggle">
                <span>⚙️ Master</span>
                <span class="arrow">▾</span>
            </a>
            <div class="submenu">
                <a href="{{ route('formats.index') }}" class="{{ Route::is('formats.*') ? 'active' : '' }}"><span>📝
                        Formats</span></a>
                <a href="{{ route('hrs.index') }}" class="{{ Route::is('hrs.*') ? 'active' : '' }}"><span>👤
                        HR</span></a>
                <a href="{{ route('po.index') }}" class="{{ Route::is('po.*') ? 'active' : '' }}"><span>📄 PO</span></a>
                <a href="{{ route('phase.index') }}" class="{{ Route::is('phase.*') ? 'active' : '' }}"><span>🧱
                        Phase</span></a>
                <a href="{{ route('activities.index') }}"
                    class="{{ Route::is('activities.*') ? 'active' : '' }}"><span>🔁 Activities</span></a>
                <a href="{{ route('team-members.index') }}"
                    class="{{ Route::is('team-members.*') ? 'active' : '' }}"><span>👥 Team Members</span></a>
                <a href="{{ route('roles.index') }}" class="{{ Route::is('roles.*') ? 'active' : '' }}"><span>🛡️ Job
                        Roles</span></a>
            </div>
        </li>
    </ul>
</aside>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const menus = document.querySelectorAll(".menu-group");

    menus.forEach(menu => {

        const toggle = menu.querySelector("a");
        const activeLink = menu.querySelector(".submenu a.active");

        // 🔹 Open menu if child is active
        if (activeLink) {
            menu.classList.add("active");
        }

        toggle.addEventListener("click", function(e) {
            e.preventDefault();

            menus.forEach(m => {
                if (m !== menu) {
                    m.classList.remove("active");
                }
            });

            menu.classList.toggle("active");
        });

    });

});
</script>