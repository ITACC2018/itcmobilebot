<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SB Admin - Start Bootstrap Template</title>
  <link href="<?php echo e(asset('admin/vendor/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('admin/vendor/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css">
  <link href="<?php echo e(asset('admin/vendor/datatables/dataTables.bootstrap4.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('admin/css/sb-admin.css')); ?>" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
 
    <?php echo $__env->yieldContent('content'); ?>


    <script src="admin/vendor/jquery/jquery.min.js"></script>
    <script src="admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="admin/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="admin/vendor/chart.js/Chart.min.js"></script>
    <script src="admin/vendor/datatables/jquery.dataTables.js"></script>
    <script src="admin/vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="admin/js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="admin/js/sb-admin-datatables.min.js"></script>
    <script src="admin/js/sb-admin-charts.min.js"></script>
</body>

</html>
