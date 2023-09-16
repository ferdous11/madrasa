<?php include  __DIR__ .'/../topheader.php'; ?>
<?php include  __DIR__ .'/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        All Category
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
                            <span style="float: right"><a href="#" data-toggle="modal" data-target="#addcategory"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Category</button></a></span>
                            <table class="display table table-bordered table-striped dataTable" id="example11">
                                <thead>
                                    <tr>                                    
                                        <th>ID</th>                                       
                                        <th>Category Name</th>
                                        <th>Details</th>
                                        <th>Inserted By</th>                                            <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (sizeof($categorydata) > 0):
                                        foreach ($categorydata as $category):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>   
                                                <td><?php echo $category->name; ?></td>                                               
                                                <td><?php echo $category->description; ?></td>
                                                <td><?php echo $category->fullname; ?></td>
                                                <td><a href="#" data-toggle="modal" data-target="#editcategory<?php echo $category->id ?>"><i class="fa fa-edit"></i></a></td>
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
        if (sizeof($categorydata) > 0):
            foreach ($categorydata as $cat):
                ?>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editcategory<?php echo $cat->id; ?>" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Update Category Information</h4>
                            </div>

                            <div class="modal-body">

                                <form class="form-horizontal" role="form"action="<?php echo site_url('product/updatecategory'); ?>" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Category Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $cat->name; ?>" required>
                                        </div>
                                    </div>

                                   

                                    <div class="form-group">
                                        <label for="description" class="col-lg-4 col-sm-4 control-label">Details</label>
                                        <div class="col-lg-8">
                                            <textarea type="text" class="form-control" name="description" id="description" ><?php echo $cat->description; ?></textarea>
                                        </div>
                                    </div>                                    


                                    <div class="form-group">
                                        <div class="col-lg-offset-4 col-lg-8">
                                            <input type="hidden" name="cat_id" value="<?php echo $cat->id; ?>"/>
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
        
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addcategory" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New Category</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form" action="<?php echo site_url('product/addcategory'); ?>" method="post"  enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Category Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" id="name" required="">
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="details" class="col-lg-4 col-sm-4 control-label">Details</label>
                                <div class="col-lg-8">
                                    <textarea type="text" class="form-control" name="description" id="description"></textarea>
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
<?php include __DIR__ .'/../footer.php'; ?>
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
