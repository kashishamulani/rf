<form method="POST" action="{{ route('verify.otp') }}">
@csrf

<h2>Verify OTP</h2>

<input type="text" name="otp" placeholder="Enter OTP">

<button type="submit">Verify</button>

</form>