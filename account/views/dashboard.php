<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->   
        <?php if ($this->session->userdata('success')): ?>
            <div class="alert alert-block alert-success fade in">
                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                <strong>আলহামদুলিল্লাহ,&nbsp;</strong> <?php
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
        <div class="row state-overview  my-3">
        <?php if($pmonth<$month && $this->session->userdata("user_id")==1):?>
        <div class="col-md-4 ">
            <div class="">
            <a style="text-decoration: none;" href="<?php echo site_url('teacher/assign_fee'); ?>">
                
                <section class="panel terques">
                    <div class="symbol">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="value">                        
                        <h1 class="dashbordh1">এই মাসের বেতন নির্ধারণ করুন</h1>
                    </div>
                </section>
            </a>
            </div>
        </div>
        <?php endif;?>   

        <div class="col-md-4 ">
            <div class="">
            <a style="text-decoration: none;" href="<?php echo site_url('teacher/attendance'); ?>">
                
                <section class="panel terques">
                    <div class="symbol">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="value">                        
                        <h1 class="dashbordh1">তালেবে এলেম হাজিরা</h1>
                    </div>
                </section>
            </a>
            </div>
        </div>   
        
        <div class="col-md-4 ">
            <div class="">
            <a style="text-decoration: none;" href="<?php echo site_url('teacher/students_list'); ?>">
                
                <section class="panel terques">
                    <div class="symbol">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="value">                        
                        <h1 class="dashbordh1">তালেবে এলেমের তালিকা</h1>
                    </div>
                </section>
                
            </a>
            </div>
        </div>

        <div class="col-md-4 ">
            <div class="">
            <a style="text-decoration: none;" href="<?php echo site_url('teacher/report/'); ?>">
                
                <section class="panel terques">
                    <div class="symbol">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="value">                        
                        <h1 class="dashbordh1"> ফি আদায় রিপোর্ট </h1>
                    </div>
                </section>
            </a>
            </div>
        </div>

        <div class="col-md-4 ">
            <div class="">
            <a style="text-decoration: none;" href="<?php echo site_url('sell'); ?>">
                
                <section class="panel terques">
                    <div class="symbol">
                        <i class="fa fa-plus"></i>
                    </div>
                    <div class="value">                        
                        <h1 class="dashbordh1">বিক্রয়</h1>
                    </div>
                </section>
            </a>
            </div>
        </div>

        <!-- <div class="col-md-4 ">
            <div class="">
            <a style="text-decoration: none;" href="<?php echo site_url('teacher/income'); ?>">
                
                <section class="panel terques">
                    <div class="symbol">
                        <i class="fa fa-plus"></i>
                    </div>
                    <div class="value">                        
                        <h1 class="dashbordh1">আয়</h1>
                    </div>
                </section>
                
            </a>
            </div>
        </div> -->
            
        <div class="col-md-4 ">
            <div class="">
            <a style="text-decoration: none;" href="<?php echo site_url('payments'); ?>">
                
                <section class="panel  red">
                    <div class="symbol">
                        <i class="fa fa-minus"></i>
                    </div>
                    <div class="value">                        
                        <h1 class="dashbordh1">ব্যয়</h1>
                    </div>
                </section>
            </a>
            </div>
        </div>

        <!-- <div class="col-md-4 ">
            <div class="">
            <a style="text-decoration: none;" href="<?php echo site_url('purchase'); ?>">
                
                <section class="panel  red">
                    <div class="symbol">
                        <i class="fa fa-minus"></i>
                    </div>
                    <div class="value">                        
                        <h1 class="dashbordh1">ক্রয়</h1>
                    </div>
                </section>
            </a>
            </div>
        </div> -->

        </div>
    </section>
</section>
<?php include 'footer.php'; ?>
