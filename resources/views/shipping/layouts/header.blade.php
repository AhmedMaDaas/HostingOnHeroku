<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>{{ settings()->{'sitename_' . lang()} }} | {{ trans('admin.dashboard') }}</title>

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet"/>
  <link href="https://cdn.materialdesignicons.com/3.0.39/css/materialdesignicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

  <!-- PLUGINS CSS STYLE -->
  <link href="{{ url('shipping_design') }}/assets/plugins/toaster/toastr.min.css" rel="stylesheet" />
  <link href="{{ url('shipping_design') }}/assets/plugins/nprogress/nprogress.css" rel="stylesheet" />
  <link href="{{ url('shipping_design') }}/assets/plugins/flag-icons/css/flag-icon.min.css" rel="stylesheet"/>
  <link href="{{ url('shipping_design') }}/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
  <link href="{{ url('shipping_design') }}/assets/plugins/ladda/ladda.min.css" rel="stylesheet" />
  <link href="{{ url('shipping_design') }}/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
  <link href="{{ url('shipping_design') }}/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
  <link id="sleek-css" rel="stylesheet" href="{{ url('shipping_design') }}/assets/css/jam.min.css" />
  <!-- SLEEK CSS -->
  <link id="sleek-css" rel="stylesheet" href="{{ url('shipping_design') }}/assets/css/sleek.css" />
  <link id="sleek-css" rel="stylesheet" href="{{ url('shipping_design') }}/assets/css/dashboard.css" />
  

  <!-- FAVICON -->
  <link href="{{ url('shipping_design') }}/assets/img/Bazar%20AL%20Seeb%20final%20logo.png" rel="shortcut icon" />

  <!--
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script src="{{ url('shipping_design') }}/assets/plugins/nprogress/nprogress.js"></script>
  @stack('css')
</head>


  <body class="header-fixed sidebar-fixed sidebar-dark header-dark" id="body">
    <script>
      NProgress.configure({ showSpinner: false });
      NProgress.start();
    </script>