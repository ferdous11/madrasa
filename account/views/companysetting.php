<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->   
        <div class="row">
            <div class="col-lg-5">
                <div class="panel">
                    <div class="panel-heading">Company Information Update</div>
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
                        <form class="form-horizontal" role="form"action="<?php echo site_url('company/addcompany'); ?>" method="post" enctype="multipart/form-data">
                            <br/>
                            <div class="form-group">
                                <label for="brand" class="col-lg-3 col-sm-3 control-label">Company ID</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="companyid" id="companyid" required="" value="<?php echo time(); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-lg-3 col-sm-3 control-label">Company Name</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="sname" id="sname" placeholder="Product name" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-lg-3 col-sm-3 control-label">Address</label>
                                <div class="col-lg-6">
                                    <textarea type="text" class="form-control" name="address" id="address" required=""></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="brand" class="col-lg-3 col-sm-3 control-label">Mobile</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="mobile" id="mobile" required="">
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="brand" class="col-lg-3 col-sm-3 control-label">Email</label>
                                <div class="col-lg-6">
                                    <input type="email" class="form-control" name="email" id="email" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="brand" class="col-lg-3 col-sm-3 control-label">Web Site</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="website" id="website" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="brand" class="col-lg-3 col-sm-3 control-label">Status</label>
                                <div class="col-lg-6">
                                    <select class="form-control" name="status" id="status">
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-3 col-lg-6">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <button type="submit" class="btn btn-primary">Submit</button> 
                                    <a href="<?php echo site_url('home/products'); ?>"><button type="button" class="btn btn-info">Back</button></a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">                

                <section class="panel">
                    <header class="panel-heading">
                        Company List
                    </header>
                    <div class="panel-body">                       
                        <div class="form">
                            <table class="display table table-bordered table-striped dataTable" id="example">

                                <thead>
                                    <tr>                                    
                                        <th>ID</th>                                    
                                        <th>Name</th>
                                        <th>Company ID</th>
                                        <th>Mobile</th>
                                        <th>Address</th>  
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $companydata = $this->db->get("company")->result();
                                    if (sizeof($companydata) > 0):
                                        foreach ($companydata as $comp):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $comp->company_name; ?></td>
                                                <td><?php echo $comp->company_id; ?></td>
                                                <td><?php echo $comp->mobile; ?></td>
                                                <td><?php echo $comp->address; ?></td>
                                                <td><?php echo ($comp->status == '1')?'Active':'In-Active'; ?></td>
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#editcompany<?php echo $comp->id ?>"><i class="fa fa-edit" title="Edit company"></i></a>
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
            if (sizeof($companydata) > 0):
                foreach ($companydata as $user):
                    ?>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editcompany<?php echo $user->id; ?>" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 class="modal-title">Update Company Information</h4>
                                </div>

                                <div class="modal-body">

                                    <form class="cmxform form-horizontal" method="post" action="<?php echo site_url('company/updatecompany'); ?>">
                                        <br/>
                                        <div class="form-group">
                                            <label for="brand" class="col-lg-3 col-sm-3 control-label">Company ID</label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="companyid" id="companyid" required="" value="<?php echo $user->company_id; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-lg-3 col-sm-3 control-label">Company Name</label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="sname" id="sname" placeholder="Product name" required="" value="<?php echo $user->company_name; ?>"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="col-lg-3 col-sm-3 control-label">Address</label>
                                            <div class="col-lg-6">
                                                <textarea type="text" class="form-control" name="address" id="address" required=""><?php echo $user->address; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="brand" class="col-lg-3 col-sm-3 control-label">Mobile</label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="mobile" id="mobile" required="" value="<?php echo $user->mobile; ?>">
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <label for="brand" class="col-lg-3 col-sm-3 control-label">Email</label>
                                            <div class="col-lg-6">
                                                <input type="email" class="form-control" name="email" id="email" required="" value="<?php echo $user->email; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="brand" class="col-lg-3 col-sm-3 control-label">Web Site</label>
                                            <div class="col-lg-6">
                                                <input type="text" class="form-control" name="website" id="website" required="" value="<?php echo $user->website; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="brand" class="col-lg-3 col-sm-3 control-label">Status</label>
                                            <div class="col-lg-6">
                                                <select class="form-control" name="status" id="status">
                                                    <option <?php echo ($user->status == '1')?'selected':''; ?> value="1">Active</option>
                                                    <option <?php echo ($user->status == '0')?'selected':''; ?> value="0">In-Active</option>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-3 col-lg-8">
                                                <input type="hidden" name="id" value="<?php echo $user->id; ?>"/>
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
        <!-- page end-->

    </section>
</section>

<?php include 'footer.php'; ?>
