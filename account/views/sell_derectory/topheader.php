<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Yousuf">
        <link rel="shortcut icon" href="<?php echo $baseurl; ?>assets/img/favicon.png">
        <meta name="keyword" content="Inventory Software">
        <link rel="shortcut icon" href="<?php echo $baseurl; ?>assets/img/favicon.ico">

        <title>Dashboard</title>

        

        <!-- Bootstrap core CSS -->
        <link href="<?php echo $baseurl; ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $baseurl; ?>assets/css/bootstrap-reset.css" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo $baseurl; ?>assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="<?php echo $baseurl; ?>assets/css/style.css" rel="stylesheet">
        <link href="<?php echo $baseurl; ?>assets/css/style-responsive.css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo $baseurl; ?>assets/css/datepicker.css" type="text/css">
        <link href="<?php echo $baseurl; ?>assets/css/demo_page.css" rel="stylesheet" />
        <link href="<?php echo $baseurl; ?>assets/css/demo_table.css" rel="stylesheet" />
        <link href="<?php echo $baseurl; ?>assets/css/jquery.datetimepicker.css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo $baseurl; ?>assets/css/chosen.css">

        
        <link rel="stylesheet" type="text/css" href="<?php echo $baseurl; ?>assets/customsearch/css/bootstrap-select.css">
        <script type="text/javascript" src="<?php echo $baseurl; ?>assets/js/jquery-1.8.3.min.js"></script>   
        <script type="text/javascript" src="<?php echo $baseurl; ?>assets/customsearch/js/bootstrap-select.js"></script>
        <script type="text/javascript" src="<?php echo $baseurl; ?>assets/customsearch/js/bootstrap-min.js"></script>
        <!-- custom search endhere here-->

        <script type="text/javascript" language="javascript" src="<?php echo $baseurl; ?>assets/js/jquery.datetimepicker.js"></script>
        
    </head>

    <body>

        <section id="container" class="">
            <!--header start-->
            <header class="header white-bg">
                <div class="sidebar-toggle-box">
                    <div data-original-title="Toggle Navigation" data-placement="right" class="fa fa-bars tooltips"></div>
                </div>
                <!--logo start-->
                <a href="<?php echo base_url(); ?>" class="logo" style="color: #FFF">NeoTech</a>
                <!--<h2 style="padding-left: 200px;margin-top: 3px;text-align: center;color: rgb(244, 247, 247);text-shadow: rgb(3, 3, 3) 4px 4px 2px;">Inventory Management System</h2> -->
                <!--logo end-->
                <?php
                if ($this->session->userdata('username') != ''):
                    ?>
                    <div class="top-nav ">                    
                        <ul class="nav pull-right top-menu">  

                            <li class="dropdown open" style="background: #fff;border-radius: 5px">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#" aria-expanded="true">                                
                                    <span class="username"><i class="fa fa-user"></i> <?php echo $this->session->userdata('username'); ?></span>                                
                                </a>                           
                            </li>

                            <li class="dropdown open" style="background: #fff;border-radius: 5px">
                                <a href="<?php echo site_url('home/logout'); ?>" aria-expanded="true">                                
                                    <span class="username"><i class="fa fa-sign-out"></i> Logout</span>                                
                                </a>                           
                            </li>

                        </ul>                    
                    </div>
                    <?php
                endif;
                ?>
            </header>
            <!--header end-->
            <!--sidebar start-->