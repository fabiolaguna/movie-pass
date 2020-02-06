<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>MoviePass</title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo (ASSETS_PATH) ?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="<?php echo (ASSETS_PATH) ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
  <link href="<?php echo (ASSETS_PATH) ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <?php 
    if (isset($_SESSION["loggedRole"]) &&  $_SESSION["loggedRole"] == "cliente"){
      ?>
    <link href="<?php echo (ASSETS_PATH) ?>/css/agency.css" rel="stylesheet">
  <?php
    } else {
      if ($_SERVER["REQUEST_URI"] == "/movie-pass/home/index") {?>
        <link href="<?php echo (ASSETS_PATH) ?>/css/agency.css" rel="stylesheet">
      <?php } 
      else { ?>
      <link href="<?php echo (ASSETS_PATH) ?>/css/agency.css" rel="stylesheet">
      <link href="<?php echo (ASSETS_PATH) ?>/css/sb-admin-2.css" rel="stylesheet">
  <?php }
    } ?>


  <!-- Custom styles for this page -->
  <link href="<?php echo (ASSETS_PATH) ?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<!--
<style>
	html body {
    background-image: url("https://getreelcinemas.com//wp-content/uploads/2015/02/Background-Narrow.jpg");
    background-position: center center;
	background-size: cover;
	height: 100vh;
	min-height: 600px;
	}
	.table{
		color:white;
		background-color: rgba(0,0,0,50%);
	}
	.modal-title{
		color:white;
	}
	.modal-content{
		background-color: rgba(0,0,0,50%);
		color:white;
	}
	.mb-5{
		color:white;
	}
</style>
-->

<body id="page-top">