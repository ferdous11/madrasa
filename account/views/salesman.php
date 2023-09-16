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
                        All Sales Man
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
                            <span style="float: right"><a href="#" data-toggle="modal" data-target="#addsalesman"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New SalesMan</button></a></span>
                            <table class="display table table-bordered table-striped dataTable" id="example">
                                <thead>
                                    <tr>                                    
                                        <th>ID</th>                                       
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Address</th>                                         
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (sizeof($salesmandata) > 0):
                                        foreach ($salesmandata as $salesman):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>   
                                                <td><?php echo $salesman->fullname; ?></td>                                                                                             
                                                <td><?php echo $salesman->mobile; ?></td>
                                                <td><?php echo $salesman->email; ?></td>
                                                <td><?php echo $salesman->address; ?></td>                                                                                                                                          
                                                <td><a href="#" data-toggle="modal" data-target="#editsalesman<?php echo $salesman->id ?>"><i class="fa fa-edit"></i></a>&nbsp; <a href="<?php echo site_url('master/deletesalesman/' . $salesman->id); ?>" onclick="return confirm('Are you sure want to delete this Sales MAn !!')"><i class="fa fa-trash-o"></i></a></td>
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
        </div>


        <?php
        if (sizeof($salesmandata) > 0):
            foreach ($salesmandata as $saless):
                ?>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editsalesman<?php echo $saless->id; ?>" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Update Sales Man Information</h4>
                            </div>

                            <div class="modal-body">

                                <form class="form-horizontal" role="form"action="<?php echo site_url('master/updatesalesman'); ?>" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Sales Man Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $saless->fullname; ?>" required="">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label for="mobile" class="col-lg-4 col-sm-4 control-label">Mobile</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="mobile" id="mobile" value="<?php echo $saless->mobile; ?>" required="">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label for="email" class="col-lg-4 col-sm-4 control-label">Email</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $saless->email; ?>" required="">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label for="address" class="col-lg-4 col-sm-4 control-label">Address</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="address" id="address" value="<?php echo $saless->address; ?>" required="">
                                        </div>
                                    </div> 

                                    

                                    <div class="form-group">
                                        <div class="col-lg-offset-4 col-lg-8">
                                            <input type="hidden" name="sell_id" value="<?php echo $saless->id; ?>"/>
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
                <?php
            endforeach;
        endif;
        ?>
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addsalesman" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New Sales Man</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form"action="<?php echo site_url('master/addsalesman'); ?>" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Salesman Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" maxlength="100" id="name" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="mobile" class="col-lg-4 col-sm-4 control-label">Mobile</label>
                                <div class="col-lg-8">
                                    <input type="number" class="form-control" maxlength="11" name="mobile" id="mobile" required="">
                                </div>
                            </div> 
                            
                            <div class="form-group">
                                <label for="mobile" class="col-lg-4 col-sm-4 control-label">Login ID</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" maxlength="30" name="userid" id="userid" required="">
                                </div>
                            </div> 
                            
                            <div class="form-group">
                                <label for="mobile" class="col-lg-4 col-sm-4 control-label">Password</label>
                                <div class="col-lg-8">
                                    <input type="password" class="form-control" maxlength="50" name="password" id="password" required="">
                                </div>
                            </div> 
                            

                            <div class="form-group">
                                <label for="email" class="col-lg-4 col-sm-4 control-label">Email</label>
                                <div class="col-lg-8">
                                    <input type="email" class="form-control" name="email" maxlength="50" id="email" required="">
                                </div>
                            </div> 

                            <div class="form-group">
                                <label for="address" class="col-lg-4 col-sm-4 control-label">Address</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="address" id="address" maxlength="150" required="">
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
<?php include 'footer.php'; ?>
