<style>
.forms-box{
    max-width:600px;
    margin:30px auto;
    background:#fff;
    padding:25px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,0.06);
}

.forms-title{
    font-size:22px;
    font-weight:700;
    margin-bottom:20px;
}

.btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
    color:#fff;
    transition:0.2s;
}

.btn-primary{
    background:linear-gradient(135deg,#6366f1,#ec4899);
}

.btn-success{
    background:linear-gradient(135deg,#16a34a,#22c55e);
}

.btn:hover{
    transform:translateY(-1px);
    opacity:.9;
}

.btn-row{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}
</style>

<div class="forms-box">

    <div class="forms-title">Training Forms</div>

    <div class="btn-row">

        <a href="{{ route('attendance.pdf', $batch->id) }}" class="btn btn-primary">
            📄 Attendance Sheet
        </a>

        <a href="{{ route('tracking.pdf') }}" class="btn btn-success">
            📊 Placement Tracking
        </a>

    </div>

</div>