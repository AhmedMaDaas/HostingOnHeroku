<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
   <!--Made with love by Mutiullah Samim -->
   
  <!--Bootsrap 4 CDN-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

  <!--Custom styles-->
  <link rel="stylesheet" type="text/css" href="{{ url('/admin_design') }}/assets/css/login.css">
</head>
<body>
<div class="container">
  <div class="d-flex justify-content-center h-100">
    <div class="card">
      <div class="card-header">
        <h3>Sign In</h3>

      </div>
      <div class="card-body">
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li> {{ $error }} </li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{ Form::open(['url' => url('shipping/login'), 'method' => 'POST']) }}
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="email" name="email" class="form-control" placeholder="E-mail">
            
          </div>
          <div class="input-group form-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-key"></i></span>
            </div>
            <input type="password" name="password" class="form-control" placeholder="password">
          </div>
          <div class="row align-items-center remember">
            <input name="remember" type="checkbox">Remember Me
          </div>
          <div class="form-group">
            <input type="submit" value="Login" class="btn float-right login_btn">
          </div>
        {{ Form::close() }}
      </div>
      <div class="card-footer">
        <div class="d-flex justify-content-center">
          <a href="{{ url('/shipping') }}/resset/password">Forgot your password?</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>