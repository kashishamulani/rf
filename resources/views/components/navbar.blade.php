<style>
/* Navbar */
.navbar {
    position: fixed;
    top: 0;
    left: 200px;
    right: 0;
    height: 72px;
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(18px);
    border-bottom: 1px solid rgba(0,0,0,0.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 15px;
    z-index: 20;
    transition: 0.4s ease;
}

.navbar.full{
    left:0;
}

/* Title */
.navbar .title{
    font-size:22px;
    font-weight:700;
    color:#111827;
}

/* User box */
.user-box{
    position:relative;
}

/* Profile icon */
.profile-btn{
    width:40px;
    height:40px;
    border-radius:50%;
    border:none;
    cursor:pointer;
    background:#4f46e5;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
}

/* Dropdown */
.dropdown-menu{
    position:absolute;
    right:0;
    top:50px;
    background:white;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    width:170px;
    display:none;
    flex-direction:column;
    overflow:hidden;
}

/* Dropdown items */
.dropdown-menu a,
.dropdown-menu button{
    padding:12px 16px;
    border:none;
    background:none;
    text-align:left;
    font-size:14px;
    cursor:pointer;
    width:100%;
}

.dropdown-menu a:hover,
.dropdown-menu button:hover{
    background:#f3f4f6;
}

.logout{
    color:#ef4444;
}

/* Show dropdown */
.dropdown-menu.show{
    display:flex;
}
</style>


<header class="navbar">

<div style="display:flex; align-items:center; gap:14px;">

<!-- Hamburger -->
<button id="sidebarToggle" style="background:none;border:none;font-size:22px;cursor:pointer;color:#4f46e5;">
<i class="fas fa-bars"></i>
</button>

<div class="title">
@yield('title','Dashboard')
</div>

</div>

<!-- User Dropdown -->
<div class="user-box">

<button class="profile-btn" id="profileToggle">
<i class="fas fa-user"></i>
</button>

<div class="dropdown-menu" id="profileMenu">

<a href="{{ route('change.form') }}">
<i class="fas fa-key"></i> Change Password
</a>

<form action="{{ route('logout') }}" method="POST">
@csrf
<button type="submit" class="logout">
<i class="fas fa-sign-out-alt"></i> Logout
</button>
</form>

</div>

</div>

</header>


<script>
const toggleBtn = document.getElementById("profileToggle");
const menu = document.getElementById("profileMenu");

toggleBtn.onclick = function () {
menu.classList.toggle("show");
};

window.onclick = function(e){
if(!toggleBtn.contains(e.target) && !menu.contains(e.target)){
menu.classList.remove("show");
}
}
</script>