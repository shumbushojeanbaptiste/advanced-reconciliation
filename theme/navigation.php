<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../img/xode.png">
      <title> <?php include('../inc/title.php');?> | <?php echo $title; ?></title>
    <?php include('../inc/links.php');?>
   
  </head>
   
  <body class="vertical">
      
    <div class="wrapper">
      
        <!-- top navigation -->
         <?php include('../inc/J_top.php');?> 
        <?php include ('../inc/topbar.php');?>
        <!-- /top navigation -->
       <!-- Sidebar -->
       
        <?php include ('../inc/sidebar.php');?>
        <!-- /Sidebar -->
        <main role="main" class="main-content">
       <?php 
        require_once $content;
        ?>
        <?php include '../inc/rightnav.php';?>
        </main> <!-- main -->
    </div> <!-- .wrapper -->
   
    <?php include('../inc/script.php');?> 
    <?php include('../inc/template_end.php');?> 
  