<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">

                    <header class="panel-heading">
                        Update Employee Salary
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
                        <form class="form-horizontal" role="form"action="<?php echo site_url('employee/updatesalary'); ?>" method="post" enctype="multipart/form-data">


                               <div class="form-group">
                                    <label for="name" class="col-lg-4 col-sm-4 control-label">Employee Name</label>
                                    <div class="col-lg-7">
                                        <input readonly type="text" class="form-control" name="ledgername"  value="<?php echo $salarylist->ledgername; ?>" required="">
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label for="address" class="col-lg-4 col-sm-4 control-label">Employee ID</label>
                                    <div class="col-lg-7">
                                        <input type="text" name ="employee_id" value="<?php echo $salarylist->description; ?>" required="">
                                    </div>
                                </div> 

                                <div   class="form-group">
                                <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Type</label>
                                <div class="col-lg-7">
                                    <select class="form-control"  name="type"  required="">
                                        <option <?php echo $salarylist->type=='Monthly'?'selected':''; ?> value="Monthly">Monthly</option>
                                        <option <?php echo $salarylist->type=='Weekly'?'selected':''; ?> value="Weekly">Weekly</option>
                                        <option <?php echo $salarylist->type=='Daily'?'selected':''; ?> value="Daily">Daily</option>
                                        <option <?php echo $salarylist->type=='Hourly'?'selected':''; ?> value="Hourly">Hourly</option>
                                    </select>
                                    <span style="color: red" id="error_acgroup"></span>
                                </div>
                                </div> 
                                <div class="form-group">
                                    <label for="starttime" class="col-lg-4 col-sm-4 control-label">Start Time</label>
                                    <div class="col-lg-7">
                                        <input type="time" name ="start" value="<?php echo date('H:i',strtotime($salarylist->start));?>">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="endtime" class="col-lg-4 col-sm-4 control-label">End Time</label>
                                    <div class="col-lg-7">
                                        <input type="time" name ="end" value="<?php echo date('H:i',strtotime($salarylist->end));?>">
                                    </div>
                                </div>  
                                <div class="form-group">
                                <label for="address" class="col-lg-4 col-sm-4 control-label">Salary</label>
                                <div class="col-lg-7">
                                    <input type="number" step=".01" value="<?php echo $salarylist->salary;?>" name ="salary">
                                </div>
                                </div> 

                                <input type="hidden" name="employee" value="<?php echo $salarylist->employee_id;?>">

                                    <div class="form-group">
                                        <div class="col-lg-offset-4 col-lg-7">
                                            <input type="hidden" name="ledgerid" value="<?php echo $salarylist->id;?>"/>
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                            <input type="submit" class="btn btn-success" value="Submit">
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </section>

        <?php include __DIR__ .'/../footer.php'; ?>
