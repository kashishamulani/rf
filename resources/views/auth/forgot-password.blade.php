<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: "Inter", sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(120deg,#0f172a,#020617,#020617,#1e293b);
    overflow:hidden;
}

/* BACKGROUND BLOBS */
.bg-shape{
    position:absolute;
    width:380px;
    height:380px;
    border-radius:50%;
    filter: blur(120px);
    opacity:0.5;
    animation: float 12s infinite alternate;
}

.bg1{
    background:#6366f1;
    top:10%;
    left:10%;
}

.bg2{
    background:#ec4899;
    bottom:10%;
    right:10%;
    animation-delay:3s;
}

@keyframes float{
    from{ transform: translateY(0px);}
    to{ transform: translateY(-80px);}
}

/* CARD */
.login-card{
    position:relative;
    width:380px;
    padding:38px 34px;
    border-radius:22px;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(18px);
    border:1px solid rgba(255,255,255,0.15);
    color:#fff;
    z-index:2;
    animation: fadeIn 0.8s ease;
}

@keyframes fadeIn{
    from{opacity:0; transform: translateY(20px);}
    to{opacity:1; transform: translateY(0);}
}

/* TITLE */
.login-card h2{
    text-align:center;
    font-size:24px;
    font-weight:600;
    margin-bottom:8px;
}

.subtitle{
    text-align:center;
    font-size:13px;
    color:#cbd5f5;
    margin-bottom:28px;
}

/* ERROR */
.error{
    background: rgba(239,68,68,0.2);
    border:1px solid rgba(239,68,68,0.4);
    color:#fff;
    padding:10px;
    border-radius:10px;
    font-size:13px;
    text-align:center;
    margin-bottom:15px;
}

/* INPUT */
.input-box{
    position:relative;
    margin-bottom:22px;
}

.input-box input{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,0.2);
    background: transparent;
    color:#fff;
    outline:none;
    font-size:14px;
}

.input-box input:focus{
    border-color:#6366f1;
}

/* FLOAT LABEL */
.input-box label{
    position:absolute;
    top:50%;
    left:14px;
    transform:translateY(-50%);
    font-size:14px;
    color:#94a3b8;
    pointer-events:none;
    transition:0.3s;
}

.input-box input:focus + label,
.input-box input:not(:placeholder-shown) + label{
    top:-8px;
    left:10px;
    font-size:12px;
    background:#020617;
    padding:0 6px;
    border-radius:6px;
    color:#6366f1;
}

/* BUTTON */
button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background: linear-gradient(135deg,#6366f1,#8b5cf6);
    color:#fff;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
}

button:hover{
    transform: translateY(-2px);
    opacity:0.95;
}

/* FOOTER */
.footer-text{
    text-align:center;
    margin-top:20px;
    font-size:12px;
    color:#94a3b8;
}

.back-link{
    text-align:center;
    margin-top:15px;
}

.back-link a{
    color:#cbd5f5;
    font-size:13px;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="bg-shape bg1"></div>
<div class="bg-shape bg2"></div>

<div class="login-card">

<h2>Ebiz Reliance Sourcing Portal</h2>
<div class="subtitle">Reset your password via OTP</div>

@if($errors->any())
<div class="error">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('send.otp') }}">
@csrf

<div class="input-box">
<input type="email" name="email" required placeholder=" ">
<label>Email Address</label>
</div>

<button type="submit">Send OTP</button>

</form>

<div class="back-link">
<a href="{{ route('login') }}">← Back to Login</a>
</div>

<div class="footer-text">
© {{ date('Y') }} e Biz Technocrats Pvt Ltd
</div>

</div>

</body>
</html>