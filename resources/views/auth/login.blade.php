
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | VMS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Battambang" rel="stylesheet">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../dist/css/custom.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">

  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
        <div class="login-image">
            <img src="{{asset('assets/images/MEF.png')}}" class="mef-logo"
                alt="Ministry of economy and finance">
        </div>
      <p class="login-box-msg">សូមបំពេញព័ត៌មានដើម្បីចូលប្រើប្រាស់ប្រព័ន្ធ</p>
      <p class="hide text-center" id="auth-fail"></p>
      <form id="loginform" action="{{ url('/login') }}" method="post">
         {{ csrf_field() }}
		<div class="form-group {{$errors->has('username')?'error':''}}">
			<div class="col-xs-12">
				<input class="form-control" type="text" name="username" required placeholder="ឈ្មោះអ្នកប្រើប្រាស់">
					@if ($errors->has('username'))
                        <div class="help-block">
                            <ul role="alert">
                            <li>{{ $errors->first('username') }}</li>
                            </ul>
                        </div>
					@endif
					</div>
			</div>
	    <div class="form-group {{$errors->has('password')?'error':''}}">
			<div class="col-xs-12">
				<input class="form-control" type="password" name="password" required placeholder="ពាក្យសម្ងាត់">
					@if ($errors->has('password'))
						<div class="help-block">
							<ul role="alert">
							    <li>{{ $errors->first('password') }}</li>
							</ul>
						</div>
					@endif
			</div>
		</div>
		<div class="row">
			<div class="col-8">
				<div class="checkbox checkbox-primary pull-left p-t-0">
					<input id="checkbox-signup" type="checkbox">
						<label for="checkbox-signup">ចងចាំខ្ញុំ</label>
				</div>
			</div>
            <div class="col-4">
                <button type="submit" class="btn btn-success btn-block">ចូលប្រព័ន្ធ</button>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
	<script>
		$(document).ready(function () {
			$("#loginform").validate({
				rules: {
					username: 'required',
					password: 'required'
				},
				messages: {
					username: 'សូមបំពេញឈ្មោះអ្នកប្រើប្រាស់',
					password: 'សូមបំពេញលេខសម្ងាត់',
				},
				errorClass: 'text-danger',

				submitHandler: function(form) {

					event.preventDefault();
					url = "{{ url('/login') }}";

					$.ajax({
						type: "POST",
						url: url,

						data: $("#loginform").serialize(),
						success: function(data){

							if (data.status==0){
								$('#auth-fail').removeClass('hide').addClass('text-danger').text(data.error)
							} else {
								window.location.reload(data.redirect)
							}

						}
					});
				}
			});
		})
	</script>
</body>
</html>

