<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Admin Dashboard</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

 <meta name="csrf-token" content="{{ csrf_token() }}">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Poppins",sans-serif;
}

body{
    background: radial-gradient(circle at top left,#eef2ff,#f8fafc);
    color:#111827;
}

.app-container{
    display:flex;
    min-height:100vh;
}

/* ================= MAIN ================= */
.main-content{
    flex:1;
    padding: 90px 15px 15px 210px;
    transition:0.4s ease;
    animation: fadeUp 0.6s ease;
}

/* when sidebar closed */
.main-content.full{
    padding-left:40px;
}

/* ================= CARDS GRID ================= */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:28px;
}

/* ================= CARD ================= */
.card{
    position:relative;
    background:rgba(255,255,255,0.75);
    backdrop-filter: blur(18px);
    border-radius:22px;
    padding:26px 24px;
    border:1px solid rgba(99,102,241,0.18);
    box-shadow:0 15px 45px rgba(0,0,0,0.08);
    transition:all 0.45s cubic-bezier(.4,0,.2,1);
    overflow:hidden;
}

.card::before{
    content:"";
    position:absolute;
    inset:-2px;
    background:linear-gradient(135deg,#6366f1,#ec4899,#22c55e);
    opacity:0;
    transition:0.4s;
    z-index:-1;
}

.card:hover::before{
    opacity:0.18;
}

.card:hover{
    transform:translateY(-10px) scale(1.03);
    box-shadow:0 30px 80px rgba(0,0,0,0.16);
}

.card h3{
    font-size:14px;
    font-weight:500;
    color:#6b7280;
    letter-spacing:0.3px;
}

.card .value{
    font-size:34px;
    font-weight:700;
    margin-top:14px;
    background:linear-gradient(135deg,#4f46e5,#ec4899);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}


html, body {
    overflow-x: hidden;
}
/* ================= ANIMATION ================= */
@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* ================= RESPONSIVE ================= */
@media(max-width:900px){
    .main-content{
        padding-left:100px;
    }
}
</style>
</head>
<body>

<div class="app-container">

    @include('components.sidebar')
    @include('components.navbar')

    <main class="main-content" id="mainContent">
        @yield('content')
    </main>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.querySelector('.sidebar');
    const main = document.querySelector('.main-content');
    const navbar = document.querySelector('.navbar');
    const toggleBtn = document.getElementById('sidebarToggle');

    // ✅ Load sidebar state from localStorage
    const sidebarState = localStorage.getItem("sidebarClosed");

    if (sidebarState === "true") {
        sidebar.classList.add("closed");
        main.classList.add("full");
        navbar.classList.add("full");
    }

    // ✅ Toggle sidebar on click
    toggleBtn.addEventListener("click", function () {
        sidebar.classList.toggle("closed");
        main.classList.toggle("full");
        navbar.classList.toggle("full");

        // ✅ Save state
        localStorage.setItem("sidebarClosed", sidebar.classList.contains("closed"));
    });

});
</script>

</body>
</html>
