<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ url('/admin_design') }}/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="{{ url('/admin_design') }}/assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
  {{ settings()->{'sitename_' . lang()} }} | {{ trans('admin.dashboard') }}
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  
  <!-- Shipping -->
  <!-- PLUGINS CSS STYLE -->
  <link href="{{ url('shipping_design') }}/assets/plugins/toaster/toastr.min.css" rel="stylesheet" />
  <link href="{{ url('shipping_design') }}/assets/plugins/nprogress/nprogress.css" rel="stylesheet" />
  <link href="{{ url('shipping_design') }}/assets/plugins/flag-icons/css/flag-icon.min.css" rel="stylesheet"/>
  <link href="{{ url('shipping_design') }}/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
  <link href="{{ url('shipping_design') }}/assets/plugins/ladda/ladda.min.css" rel="stylesheet" />
  <link href="{{ url('shipping_design') }}/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
  <link id="sleek-css" rel="stylesheet" href="{{ url('shipping_design') }}/assets/css/jam.min.css" />
  <!-- SLEEK CSS -->
  <link id="sleek-css" rel="stylesheet" href="{{ url('shipping_design') }}/assets/css/sleek.css" />
  <link id="sleek-css" rel="stylesheet" href="{{ url('shipping_design') }}/assets/css/dashboard.css" />
  
  <script src="{{ url('shipping_design') }}/assets/plugins/nprogress/nprogress.js"></script>
  <!-- Shipping -->

  <!-- CSS Files -->
  <link href="{{ url('/admin_design') }}/assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="{{ url('/admin_design') }}/assets/demo/demo.css" rel="stylesheet" />

  <link rel="stylesheet" href="{{url('/')}}/admin_design/jstree/themes/default/style.css">

  <link rel="stylesheet" href="{{ url('/admin_design') }}/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{url('/')}}/admin_design/assets/css/simplepicker.css">

  @stack('css')
  <link rel="stylesheet" href="{{ url('/admin_design') }}/assets/css/main_all.css">
  
</head>

<body class="dark-edition">
  <div class="wrapper ">