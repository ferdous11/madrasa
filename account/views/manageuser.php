<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->                    
        <div class="row">
            <div class="col-lg-5">                

                <section class="panel">
                    <header class="panel-heading">
                        Add User
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
                            <form class="cmxform form-horizontal" method="post" action="<?php echo site_url('home/addnewuser'); ?>">
                                <br/>


                                <div class="form-group ">
                                    <label class="control-label col-lg-3">Select Company</label>
                                    <div class="col-lg-8">
                                        <?php
                                        $comlist = $this->db->query("select * from company")->result();
                                        ?>
                                        <select class="form-control selectpicker" data-live-search="true" name="companyid" id="companyid" required="">                                           
                                            <?php
                                            if (sizeof($comlist) > 0):
                                                foreach ($comlist as $allpro):
                                                    ?>
                                                    <option value="<?php echo $allpro->company_id; ?>"><?php echo $allpro->company_name ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>                                                    
                                        </select>                                        
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="mobile" class="control-label col-lg-3">Role</label>
                                    <div class="col-lg-8">
                                    <?php $role =  $this->db->get_where('role',array('company_id'=>$this->session->userdata('company_id')))->result(); ?>
                                        <select class="form-control" name="userrole" id="userrole" required="">
                                        <option value="">---select role---</option>
                                            <?php foreach ($role as $key) { ?>
                                                <option value="<?php echo $key->title; ?>"><?php echo $key->title; ?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="fullname" class="control-label col-lg-3">Full Name</label>
                                    <div class="col-lg-8">
                                        <input class=" form-control" id="fullname" name="fullname" type="text" required="" placeholder="Full Name" maxlength="50"/>  
                                        <span style="color: red"><?php echo form_error('fullname'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="slider" class="control-label col-lg-3">User ID</label>
                                    <div class="col-lg-8">
                                        <input class=" form-control" id="userid" name="userid" type="text" required="" placeholder="Login ID" maxlength="50"/>  
                                        <span style="color: red"><?php echo form_error('userid'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="password" class="control-label col-lg-3">Password</label>
                                    <div class="col-lg-8">
                                        <input class=" form-control" id="password" name="password" type="password" required="" minlength="4" maxlength="30"/>    
                                        <span style="color: red" id="error_password"><?php echo form_error('password'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="password" class="control-label col-lg-3">Confirm Password</label>
                                    <div class="col-lg-8">
                                        <input class=" form-control" minlength="4" id="cpassword" name="cpassword" type="password" required="" maxlength="30"/> 
                                        <span style="color: red" id="error_cpassword"><?php echo form_error('cpassword'); ?></span>
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label for="mobile" class="control-label col-lg-3">Mobile</label>
                                    <div class="col-lg-8">
                                        <input class=" form-control" id="mobile" name="mobile" type="number" maxlength="11" required="" placeholder="0173******"/> 
                                        <span style="color: red"><?php echo form_error('mobile'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-3">Address</label>
                                    <div class="col-lg-8">
                                        <textarea class=" form-control" id="address" name="address" required=""/></textarea> 
                                        <span style="color: red"><?php echo form_error('address'); ?></span>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-8">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button class="btn btn-primary" type="submit" onclick="return checkpassword()">Submit</button>&nbsp;&nbsp;
                                        <button class="btn btn-default" type="button">Cancel</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </section>
            </div> 

            <div class="col-lg-7">                

                <section class="panel">
                    <header class="panel-heading">
                        User List
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
                            <table class="display table table-bordered table-striped dataTable" id="example">

                                <thead>
                                    <tr>                                    
                                        <th>ID</th>                                    
                                        <th>Name</th>
                                        <th>Login ID</th>
                                        <th>Mobile</th>
                                        <th>Address</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $users = $this->db->get("alluser")->result();
                                    if (sizeof($users) > 0):
                                        foreach ($users as $user):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $user->fullname; ?></td>
                                                <td><?php echo $user->login; ?></td>
                                                <td><?php echo $user->mobile; ?></td>
                                                <td><?php echo $user->address; ?></td>
                                                <td><span style="border-radius: 5px;background-color: green;color: #fff;padding: 2px 5px 2px 5px"><?php echo $user->role; ?></span></td>
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#edituser<?php echo $user->id ?>"><i class="fa fa-edit" title="Edit users"></i></a>
                                                    <!-- <a href="<?php echo site_url('home/deleteusers/' . $user->id); ?>"><i class="fa fa-trash-o" style="color: red" title="Delete users"></i></a> -->
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div> 



            <?php
            if (sizeof($users) > 0):
                foreach ($users as $user):
                    ?>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edituser<?php echo $user->id; ?>" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 class="modal-title">Update User Information</h4>
                                </div>

                                <div class="modal-body">

                                    <form class="cmxform form-horizontal" method="post" action="<?php echo site_url('home/updateuser'); ?>">
                                        <br/>

                                        <div class="form-group ">
                                            <label for="mobile" class="control-label col-lg-3">Role<span style="color: red">*</span></label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="userrole" id="userrole">

                                                <?php foreach ($role as $key) { ?>
                                                <option <?php echo ($key->title == $user->role) ? 'selected' : ''; ?> value="<?php echo $key->title; ?>"><?php echo $key->title; ?></option>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="fullname" class="control-label col-lg-3">Full Name<span style="color: red">*</span></label>
                                            <div class="col-lg-8">
                                                <input type="hidden" name="uid" value="<?php echo $user->id; ?>"/>
                                                <input class=" form-control" id="fullname" name="fullname" type="text" required="" value="<?php echo $user->fullname; ?>"/>                                                
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="slider" class="control-label col-lg-3">User ID<span style="color: red">*</span></label>
                                            <div class="col-lg-8">
                                                <input class=" form-control" id="userid" name="userid" type="text" required="" value="<?php echo $user->login; ?>"/> 
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="password" class="control-label col-lg-3">Password<span style="color: red">*</span></label>
                                            <div class="col-lg-8">
                                                <input class=" form-control" id="password" name="password" type="password" required="" value="<?php echo $user->password; ?>"/>  
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="password" class="control-label col-lg-3">Confirm Password<span style="color: red">*</span></label>
                                            <div class="col-lg-8">
                                                <input class=" form-control" id="cpassword" name="cpassword" type="password" required="" value="<?php echo $user->password; ?>"/>                                                 
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="mobile" class="control-label col-lg-3">Mobile<span style="color: red">*</span></label>
                                            <div class="col-lg-8">
                                                <input class=" form-control" id="mobile" name="mobile" type="number" maxlength="11" required="" value="<?php echo $user->mobile; ?>"/>                                                
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label class="control-label col-lg-3">Address<span style="color: red">*</span></label>
                                            <div class="col-lg-8">
                                                <textarea class=" form-control" id="address" name="address" required=""/><?php echo $user->address; ?></textarea>                                                 
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <div class="col-lg-offset-3 col-lg-8">
                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                <button class="btn btn-primary" type="submit">Submit</button>&nbsp;&nbsp;
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>

                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif;
            ?>

        </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
<script>
    function checkpassword() {
        if ($("#password").val() != $("#cpassword").val()) {
            $("#error_cpassword").text("Password mis-match");
            return false;
        } else {
            $("#error_cpassword").text("");
            return true;
        }
    }
</script>