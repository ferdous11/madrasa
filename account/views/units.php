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
                        All Units
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
                            <span style="float: right"><a href="#" data-toggle="modal" data-target="#addunit"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Unit</button></a></span>


                            <table class="display table table-bordered table-striped dataTable" id="suppliertableid">
                                <thead>
                                    <tr>                                    
                                        <th>ID</th>                                       
                                        <th>Unit Name</th>
                                        <th>Details</th> 
                                        <th>Inserted By</th>                        
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (sizeof($unitdata) > 0):
                                        foreach ($unitdata as $unit):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>   
                                                <td><?php echo $unit->name; ?></td>                                                                                             
                                                <td><?php echo $unit->description; ?></td>
                                                <td><?php echo $this->db->get_where('alluser', array('id' => $unit->user_id))->row()->fullname; ?></td>                                                                                               
                                                <td><a href="#" data-toggle="modal" data-target="#editunit<?php echo $unit->id ?>"><i class="fa fa-edit"></i></a>

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
        </div>


        <?php
        if (sizeof($unitdata) > 0):
            foreach ($unitdata as $unite):
                ?>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editunit<?php echo $unite->id; ?>" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Update Unit Information</h4>
                            </div>

                            <div class="modal-body">

                                <form class="form-horizontal" role="form"action="<?php echo site_url('master/updateunit'); ?>" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Unit Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="unitname" id="unitname" value="<?php echo $unite->name; ?>" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="details" class="col-lg-4 col-sm-4 control-label">Details</label>
                                        <div class="col-lg-8">
                                            <textarea type="text" class="form-control" name="details" id="details" ><?php echo $unite->description; ?></textarea>
                                        </div>
                                    </div>                                    


                                    <div class="form-group">
                                        <div class="col-lg-offset-4 col-lg-8">
                                            <input type="hidden" name="unitid" value="<?php echo $unite->id; ?>"/>
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



        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addunit" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New Unit</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form"action="<?php echo site_url('master/addnewunit'); ?>" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Unit Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="unitname" id="unitname" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="details" class="col-lg-4 col-sm-4 control-label">Details</label>
                                <div class="col-lg-8">
                                    <textarea type="text" class="form-control" name="details" id="details"></textarea>
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