<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title','todo app')</title>
  
  <link rel="stylesheet" href="/css/app.css">
  <script src="/js/app.js"></script>

</head>
<body class="d-flex flex-column">
  <div id="page-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          @yield('content')

        </div>
      </div>
    </div>
  </div>


  <footer id="sticky-footer" class="py-4 bg-dark text-white-50">
    <div class="container text-center">
      <small>Copyright &copy; twkhjl</small>
    </div>
  </footer>
  
</body>
</html>