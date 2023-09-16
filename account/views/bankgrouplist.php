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
                        Bank Group List
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

                        <span style="float: right"><a href="#" data-toggle="modal" data-target="#addnewbankgroup"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Bank Group</button></a></span>

                        <table class="display table table-bordered table-striped" id="example">

                            <thead>
                                <tr>   
                                    <th>S.N</th>
                                    <th>Bank Group Name</th>    
                                    <th>Credit Limit</th>                                                                 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                $i = 1;
                                if (sizeof($bankgroupdata) > 0):
                                    foreach ($bankgroupdata as $bankk):
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><a href="<?php echo site_url('bankmanagement/allaccount/'.$bankk->id);?>"><?php echo $bankk->groupname; ?></a></td>
                                            <td><?php echo $bankk->creditlimit; ?></td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#editbankgroup<?php echo $bankk->id; ?>"><i class="fa fa-edit" title="Edit bank group"></i></a>&nbsp;&nbsp;
                                                <a href="<?php echo site_url('bankmanagement/deletebankgroup/' . $bankk->id); ?>" title="Delete bank group" onclick="return confirm('Are you sure want to delete this bank group!!')"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;
                                                <a href="<?php echo site_url('bankmanagement/allaccount/' . $bankk->id); ?>"><i class="fa fa-eye" title="Transection history"></i></a>
                                            </td>                                                                                                              
                                        </tr>
                                        <?php
                                        $total = $total + $bankk->creditlimit;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>   
                                    <th></th>
                                    <th></th>
                                    <th><?php echo 'Total=' . number_format($total, 2); ?></th>
                                    <th></th>                 
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>
            </div>   

            <!-- 
            Add new bank
            -->
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addnewbankgroup" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Add New Bank Group</h4>
                        </div>

                        <div class="modal-body">

                            <form class="form-horizontal" role="form"action="<?php echo site_url('bankmanagement/addbankgroup'); ?>" method="post" enctype="multipart/form-data">


                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Bank Group Name</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" name="groupname" id="groupname" required="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Credit Limit</label>
                                    <div class="col-lg-7">
                                        <input type="number" class="form-control" name="creditlimit" id="creditlimit" required="">
                                    </div>
                                </div>                       

                                <div class="form-group">
                                    <div class="col-lg-offset-4 col-lg-7">                                       
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
            <!-- 
            End add new bank
            -->

            <?php
            $i = 1;
           
            if (sizeof($bankgroupdata) > 0):
                foreach ($bankgroupdata as $cost):
                    ?>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editbankgroup<?php echo $cost->id; ?>" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 class="modal-title">Update Bank Group</h4>
                                </div>

                                <div class="modal-body">

                                    <form class="form-horizontal" role="form"action="<?php echo site_url('bankmanagement/updatebankgroup'); ?>" method="post" enctype="multipart/form-data">


                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Bank Group Name</label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" name="groupname" id="groupname" required="" value="<?php echo $cost->groupname;?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Credit Limit</label>
                                            <div class="col-lg-7">
                                                <input type="number" class="form-control" name="creditlimit" id="creditlimit" required="" value="<?php echo $cost->creditlimit;?>">
                                            </div>
                                        </div>   


                                        <div class="form-group">
                                            <div class="col-lg-offset-4 col-lg-7">   
                                                <input type="hidden" name="id" value="<?php echo $cost->id; ?>"/>
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

        </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
