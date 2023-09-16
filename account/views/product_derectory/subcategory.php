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
                        All Sub Category
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
                            <span style="float: right"><a href="#" data-toggle="modal" data-target="#addcategory"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Sub Category</button></a></span>
                            <table class="display table table-bordered table-striped dataTable" id="example11">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sub Category</th>

                                    <th>Category</th>
                                    <th>Category Id</th>
                                    <th>Inserted By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                
                                if (sizeof($categorydata) > 0):
                                    foreach ($categorydata as $category):
                                        ?>
                                        <tr>
                                            <td><?php echo $category->id ?></td>
                                            <td><?php echo $category->name; ?></td>
                                            <td><?php echo $category->category_name; ?></td>
                                            <td><?php echo $category->category_id; ?></td>
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
                                <h4 class="modal-title">Update Sub Category Information</h4>
                            </div>

                            <div class="modal-body">

                                <form class="form-horizontal" role="form"action="<?php echo site_url('product/updatesubcategory'); ?>" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Sub Category Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $cat->name; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Category</label>
                                        <div class="col-lg-7">
                                            <select class="form-control"  name="category_id" id="category_id" required="">
                                                <?php
                                                $acgroup = $this->db->get('category')->result();
                                                if (sizeof($acgroup) > 0):
                                                    foreach ($acgroup as $acg):
                                                        ?>
                                                        <option <?php echo $acg->id==$cat->category_id?"selected":"";?> value="<?php echo $acg->id; ?>"><?php echo $acg->name ?></option>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>
                                            </select>
                                            <span style="color: red" id="error_acgroup"></span>
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
                        <h4 class="modal-title">Add New Sub Category</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form" action="<?php echo site_url('product/addsubcategory'); ?>" method="post"  enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Sub Category Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" id="name" required="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Category</label>
                                <div class="col-lg-7">
                                    <select class="form-control"  name="category_id" id="category_id" required="">
                                        <?php
                                        $acgroup = $this->db->get('category')->result();
                                        if (sizeof($acgroup) > 0):
                                            foreach ($acgroup as $acg):
                                                ?>
                                                <option value="<?php echo $acg->id; ?>"><?php echo $acg->name ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span style="color: red" id="error_acgroup"></span>
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
