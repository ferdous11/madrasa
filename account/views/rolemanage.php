<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-5">    

                 <span style="float: right"><a href="#" data-toggle="modal" data-target="#addunit"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Role</button></a></span>

                <div class="panel">

                    <div class="panel-heading">
                        <i class="fa fa-th-large"></i>&nbsp; Role List
                    </div>                                
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

                        <table class="display table table-bordered table-striped" cellspacing="0" width="100%">
                            <thead>
                                <tr>    
                                    <th>ID</th>                             
                                    <th>Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $k = 0;
                                if (count($adminlist) > 0):
                                    foreach ($adminlist as $users):
                                        ?>
                                        <tr>                                    
                                            <td><?php echo $users->id; ?></td>
                                            <td><a href="<?php echo site_url('rolemanagement/selfmenu?uid=' . $users->company_id . '&rolle=' . $users->id) ?>"><?php echo $users->title; ?></a></td>   
                                        </tr>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </tbody>                    
                        </table>                                  

                    </div>  
                </div> 
            </div>

            <div class="col-lg-7">
                <div class="panel">
                    <div class="panel-heading">
                        <i class="fa fa-th-large"></i>&nbsp; Assign menu for: <?php //if($rolle!='') echo $this->db->query("select title from role where id=$rolle")->row()->title; ?>
                    </div> 
                    <div class="panel-body">
                        <form class="cmxform form-horizontal" method="post" action="<?php echo site_url('rolemanagement/saveusermanu'); ?>">
                            <table class="table table-bordered">
                                <?php
                                $menuarry = array();
                                
                                $getsuportmenu = $this->db->get_where("usermenu", array('userid' => $companyname));
                                if ($getsuportmenu->num_rows() > 0):
                                    $smenu = $getsuportmenu->row()->menu;
                                    $seperatedata = explode(',', $smenu);
                                    for ($i = 0; $i < count($seperatedata); $i++):
                                        $menuarry[] = $seperatedata[$i];
                                    endfor;
                                endif;
                                echo "<tr><th><b>Master</b></th></tr>";
                                foreach ($menuArrayMaster as $key) {
                                ?>
                                    <tr>
                                        <th><input  style="margin-left:40px;" type= "checkbox" <?php echo in_array($key->link, $select_menu)?'checked':'' ?> name="menu[]" value="<?php echo $key->link; ?>">&nbsp;<?php echo $key->title; ?></th>                                                
                                    </tr>
                                <?php
                                };
                                ?>
                                <?php 
                                echo "<tr><th><b>Transection</b></th></tr>";
                                foreach ($menuArrayTransection as $key) {
                                ?>
                                    <tr>
                                        <th><input  style="margin-left:40px;" type= "checkbox" <?php echo in_array($key->link, $select_menu)?'checked':'' ?> name="menu[]" value="<?php echo $key->link; ?>">&nbsp;<?php echo $key->title; ?></th>                                                
                                    </tr>
                                <?php
                                };
                                ?>
                                <?php 
                                echo "<tr><th><b>Account Statement</b></th></tr>";
                                foreach ($menuArrayAccountStatement as $key) {
                                ?>
                                    <tr>
                                        <th><input style="margin-left:40px;"  type= "checkbox" <?php echo in_array($key->link, $select_menu)?'checked':'' ?> name="menu[]" value="<?php echo $key->link; ?>">&nbsp;<?php echo $key->title; ?></th>                                                
                                    </tr>
                                <?php
                                };
                                ?>
                                <?php 
                                echo "<tr><th><b>Employee</b></th></tr>";
                                foreach ($menuArrayEmployee as $key) {
                                ?>
                                    <tr>
                                        <th><input style="margin-left:40px;"  type= "checkbox" <?php echo in_array($key->link, $select_menu)?'checked':'' ?> name="menu[]" value="<?php echo $key->link; ?>">&nbsp;<?php echo $key->title; ?></th>                                                
                                    </tr>
                                <?php
                                };
                                ?>
                                <?php 
                                echo "<tr><th><b>Reports</b></th></tr>";
                                foreach ($menuArrayReports as $key) {
                                ?>
                                    <tr>
                                        <th><input style="margin-left:40px;"  type= "checkbox" <?php echo in_array($key->link, $select_menu)?'checked':'' ?> name="menu[]" value="<?php echo $key->link; ?>">&nbsp;<?php echo $key->title; ?></th>                                                
                                    </tr>
                                <?php
                                };
                                ?>
                                <?php 
                                echo "<tr><th><b>Settings</b></th></tr>";
                                foreach ($menuArraySettings as $key) {
                                ?>
                                    <tr>
                                        <th><input style="margin-left:40px;" type= "checkbox" <?php echo in_array($key->link, $select_menu)?'checked':'' ?> name="menu[]" value="<?php echo $key->link; ?>">&nbsp;<?php echo $key->title; ?></th>                                                
                                    </tr>
                                <?php
                                };
                                ?>


                                <tr>
                                    <th>
                                        <input type="hidden" name="companyname" value="<?php echo $companyname; ?>"/>
                                        <input type="hidden" name="rolle" value="<?php echo $rolle; ?>"/>
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <input type="submit" class="btn btn-primary" value="Submit">
                                    </th>
                                </tr>
                            </table> 
                        </form>
                    </div>
                </div>
            </div>        
        </div>

                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addunit" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New Role</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form"action="<?php echo site_url('rolemanagement/addnewrole'); ?>" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Role Title</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title" id="title" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-4 col-lg-8">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <button type="submit" class="btn btn-default">Submit</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>


        <!-- page end-->
    </section>
</section>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#suppliertableid').dataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: 25
        });
    });
</script>
<?php include 'footer.php'; ?>
