<form method="POST" action="{{ route('reset.password') }}">
@csrf

<input type="password" name="password" placeholder="New Password">

<input type="password" name="password_confirmation" placeholder="Confirm Password">

<button type="submit">Reset Password</button>

</form>