<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Mosaddek">
        <meta name="keyword" content="Inventory Software">
        <meta http-equiv="refresh" content="600000">
        <link rel="shortcut icon" href="<?php echo $baseurl; ?>assets/img/favicon.ico">

        <title>মাদ্রাসা পরিচালনা ও হিসাব সহজিকরণ</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo $baseurl; ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $baseurl; ?>assets/css/bootstrap-reset.css" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo $baseurl; ?>assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="<?php echo $baseurl; ?>assets/css/style.css" rel="stylesheet">
        <link href="<?php echo $baseurl; ?>assets/css/style-responsive.css" rel="stylesheet" />

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="login-body">

        <div class="container">

            <div style="margin-left: auto;margin-right: auto;display: block;margin-top: 300px;margin-bottom: -100px;text-align: center">
                <!--<img src="<?php echo $baseurl; ?>assets/img/logo.png" style="height: 150px;width: 200px;padding-bottom: 10px"/>-->
            </div>

            <form class="form-signin" action="<?php echo site_url('home/login'); ?>" method="post">

                <h2 class="form-signin-heading Bold"><span style="float: left"><img src="<?php echo $baseurl;?>assets/img/icon_inn.png" style="height: 25px"/></span>মাদ্রাসা পরিচালনা ও হিসাব সহজিকরণ</h2>

                <div class="login-wrap">

                    <?php if ($this->session->userdata('failed')): ?>
                        <div class="alert alert-block alert-danger fade in">
                            <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                            <strong>Oops!</strong> <?php
                            echo $this->session->userdata('failed');
                            $this->session->unset_userdata('failed');
                            ?>
                        </div> 
                    <?php endif; ?>
                    <?php
                    $companydd = $this->db->get_where("company",array('status' => '1'))->result();
                    ?>
                    <input type="text" class="form-control" name="userid" id="userid" placeholder="User ID" autofocus autocomplete="off">
                    <span style="color: red" id="username_error"></span>
                    <input type="password" class="form-control" name="userpassword" id="userpassword" placeholder="Password" autocomplete="off">
                    <span style="color: red" id="password_error"></span>
                    <select name="company_name" id="company_name" class="form-control" style="margin-bottom: 15px">
                        <?php
                        if (sizeof($companydd) > 0):
                            foreach ($companydd as $comdata):
                                ?>
                                <option value="<?php echo $comdata->company_id ?>"><?php echo $comdata->company_name ?></option>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </select>

                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button class="btn btn-lg btn-login btn-block" type="submit" onclick="return checklogin()">Sign in</button>                   

                </div>

                <!-- Modal -->
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="forgotpass" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Forgot Password ?</h4>
                            </div>

                            <div class="modal-body">
                                <p>Enter your e-mail address below to reset your password.</p>
                                <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
                            </div>

                            <div class="modal-footer">                                
                                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                                <button class="btn btn-success" type="button">Submit</button>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- modal -->

            </form>

        </div>
        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo $baseurl; ?>assets/js/jquery.js"></script>
        <script src="<?php echo $baseurl; ?>assets/js/bootstrap.min.js"></script>
    </body>

    <script>
                        function checklogin() {
                            var flag = 0;
                            if ($("#userid").val() == '') {                               
                                $("#userid").css('border', '2px solid red');
                                return false;
                                flag = 0;
                            } else {
                                $("#username_error").text('');
                                $("#userid").css('border', '2px solid gray');
                                flag = 1;
                            }

                            if ($("#userpassword").val() == '') {                               
                                $("#userpassword").css('border', '2px solid red');
                                return false;
                                flag = 0;
                            } else {
                                $("#password_error").text('');
                                $("#userpassword").css('border', '2px solid gray');
                                flag = 1;
                            }
                            if (flag == 1) {
                                return true;
                            }
                        }
    </script>

</html>
