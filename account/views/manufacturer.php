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
                        All Manufacturer
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
                            <span style="float: right"><a href="#" data-toggle="modal" data-target="#addmanu"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Manufacturer</button></a></span>
                            <table class="display table table-bordered table-striped dataTable" id="example11">
                                <thead>
                                    <tr>                                    
                                        <th>ID</th>                                       
                                        <th>Manufacturer Name</th>
                                        <th>Under Category</th>
                                        <th>Details</th>                                                                         
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (sizeof($branddata) > 0):
                                        foreach ($branddata as $brand):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>   
                                                <td><?php echo $brand->name; ?></td>
                                                <td>
                                                    <?php
                                                    echo $this->db->get_where('category', array('id' => $brand->category_under))->row()->name;
                                                    ?>
                                                </td> 
                                                <td><?php echo $brand->details; ?></td>                                                                                       
                                                <td><a href="#" data-toggle="modal" data-target="#editmanu<?php echo $brand->id ?>"><i class="fa fa-edit"></i></a>&nbsp; <a href="<?php echo site_url('master/deletemanu/' . $brand->id); ?>" onclick="return confirm('Are you sure want to delete this manufacturer !!')"><i class="fa fa-trash-o"></i></a></td>
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
        if (sizeof($branddata) > 0):
            foreach ($branddata as $manu):
                ?>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editmanu<?php echo $manu->id; ?>" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Update Manufacturer Information</h4>
                            </div>

                            <div class="modal-body">

                                <form class="form-horizontal" role="form"action="<?php echo site_url('master/updatemanufacturer'); ?>" method="post" enctype="multipart/form-data">

                                    <!-- <div class="form-group">
                                        <label for="category" class="col-lg-4 col-sm-4 control-label">Category</label>
                                        <div class="col-lg-8">
                                            <select class="form-control" name="category" id="category">
                                                <?php
                                                $getCategory = $this->db->get('category')->result();
                                                if (sizeof($getCategory) > 0):
                                                    foreach ($getCategory as $allcat):
                                                        ?>
                                                        <option <?php echo ($allcat->id == $manu->category_under) ? 'selected' : ''; ?> value="<?php echo $allcat->id ?>"><?php echo $allcat->name; ?></option>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>
                                            </select>
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Manufacturer Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="unitname" id="unitname" value="<?php echo $manu->name; ?>" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="details" class="col-lg-4 col-sm-4 control-label">Details</label>
                                        <div class="col-lg-8">
                                            <textarea type="text" class="form-control" name="details" id="details" required=""><?php echo $manu->details; ?></textarea>
                                        </div>
                                    </div>                                    


                                    <div class="form-group">
                                        <div class="col-lg-offset-4 col-lg-8">
                                            <input type="hidden" name="manufacturer_id" value="<?php echo $manu->id; ?>"/>
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
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addmanu" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New Manufacturer</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form"action="<?php echo site_url('master/addnewmanu'); ?>" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="category" class="col-lg-4 col-sm-4 control-label">Category</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="category" id="category">
                                        <?php
                                        $getCategory = $this->db->get('category')->result();
                                        if (sizeof($getCategory) > 0):
                                            foreach ($getCategory as $allcat):
                                                ?>
                                                <option value="<?php echo $allcat->id ?>"><?php echo $allcat->name; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Manufacturer Name</label>
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
        $('#example11').dataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: 25
        });
    });
</script>