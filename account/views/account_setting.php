<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->                    
        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Change Password
                    </header>
                    <div class="panel-body">
                        <?php if ($this->session->userdata('success')): ?>
                            <div class="alert alert-block alert-success fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Congratulation!</strong> <?php
                                echo $this->session->userdata('success');
                                $this->session->unset_userdata('success');
                                ?>
                            </div> 
                        <?php endif; ?>
                        <?php if ($this->session->userdata('failed')): ?>
                            <div class="alert alert-block alert-danger fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Oops!</strong> <?php
                                echo $this->session->userdata('failed');
                                $this->session->unset_userdata('failed');
                                ?>
                            </div> 
                        <?php endif; ?>
                        <div class="form">
                            <form class="cmxform form-horizontal" method="post" action="<?php echo site_url('home/updatepassword'); ?>">
                                <br/>
                                <div class="form-group ">
                                    <label for="slider" class="control-label col-lg-3">Current Password</label>
                                    <div class="col-lg-5">
                                        <input class=" form-control" id="current_password" name="current_password" type="password"/>  
                                        <span style="color: red"><?php echo form_error('current_password'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="newpassword" class="control-label col-lg-3">New Password</label>
                                    <div class="col-lg-5">
                                        <input class=" form-control" id="newpassword" name="newpassword" type="password"/>    
                                        <span style="color: red"><?php echo form_error('newpassword'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="cnewpassword" class="control-label col-lg-3">Confirm New Password</label>
                                    <div class="col-lg-5">
                                        <input class=" form-control" id="cnewpassword" name="cnewpassword" type="password"/> 
                                        <span style="color: red"><?php echo form_error('cnewpassword'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-8">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button class="btn btn-primary" type="submit" onclick="return checkfiled()">Update Password</button>&nbsp;&nbsp;
                                        <button class="btn btn-info" type="button">Cancel</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </section>
            </div> 

        </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
<script>
    function checkfiled() {
        var flag = 0;
        if ($("#current_password").val() == '') {
            $('#current_password').css('border', '1px solid red');
            return false;
            flag = 0;
        } else {
            $('#current_password').css('border', '');
            flag = 1;
        }

        if ($("#newpassword").val() == '') {
            $('#newpassword').css('border', '1px solid red');
            return false;
            flag = 0;
        } else {
            $('#newpassword').css('border', '');
            flag = 1;
        }

        if ($("#cnewpassword").val() == '') {
            $('#cnewpassword').css('border', '1px solid red');
            return false;
            flag = 0;
        } else {
            $('#cnewpassword').css('border', '');
            flag = 1;
        }
        if ($("#newpassword").val() != $("#cnewpassword").val()) {
            $('#cnewpassword').css('border', '1px solid red');
            return false;
            flag = 0;
        } else {
            $('#cnewpassword').css('border', '');
            flag = 1;
        }
        if (flag == 1) {
            return true;
        }
    }
</script>
