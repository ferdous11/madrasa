<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->                    
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-5">
                    <section class="panel">
                        <header class="panel-heading">
                            Update home page slider
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
                                <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('admin/addslider'); ?>" enctype="multipart/form-data">
                                    <div class="form-group ">
                                        <label for="slider" class="control-label col-lg-2">Add new slider</label>
                                        <div class="col-lg-9">
                                            <input class=" form-control" id="slider" name="slider" type="file" required=""/>
                                            [Image size must be minimum height: 400px and width 1100px And Maximum height: 600px and width: 1920px]
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <button class="btn btn-primary" type="submit">Save</button>
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
                            Slider List
                        </header>

                        <div class="panel-body">

                            <table class="table-bordered table">
                                <thead>
                                    <tr>                                         
                                        <th>file path</th>
                                        <th>Image</th>
                                        <th>Action</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $getsliderlist = $this->db->query("select * from uploads where type = 'slider'")->result();
                                    if (sizeof($getsliderlist) > 0):
                                        foreach ($getsliderlist as $slider):
                                            ?>
                                            <tr>                                                   
                                                <td><?php echo $slider->filepath; ?></td>
                                                <td><img src="<?php echo $baseurl; ?>assets/uploads/<?php echo $slider->name; ?>" height="100"/></td>
                                                <td><a href="<?php echo site_url('admin/deleteslider/' . $slider->id); ?>" onclick="return confirm('Are you sure want to delete this image!!');"><i class="fa fa-trash-o"></i> Delete</a></td>                                               
                                            </tr> 
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>                                   
                            </table>

                        </div>

                    </section>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-5">
                    <section class="panel">
                        <header class="panel-heading">
                            Update home page product/work images
                        </header>
                        <div class="panel-body">
                            <?php if ($this->session->userdata('success_work')): ?>
                                <div class="alert alert-block alert-success fade in">
                                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                    <strong>Congratulation!</strong> <?php
                                    echo $this->session->userdata('success_work');
                                    $this->session->unset_userdata('success_work');
                                    ?>
                                </div> 
                            <?php endif; ?>
                            <?php if ($this->session->userdata('failed_work')): ?>
                                <div class="alert alert-block alert-danger fade in">
                                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                    <strong>Oops!</strong> <?php
                                    echo $this->session->userdata('failed_work');
                                    $this->session->unset_userdata('failed_work');
                                    ?>
                                </div> 
                            <?php endif; ?>
                            <div class="form">
                                <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('product/addproduct'); ?>" enctype="multipart/form-data">
                                    <div class="form-group ">
                                        <label for="slider" class="control-label col-lg-2">Add new product/work</label>
                                        <div class="col-lg-9">
                                            <input class=" form-control" id="slider" name="work" type="file" required=""/>
                                            [Image size must be minimum height: 400px and width 1100px And Maximum height: 600px and width: 1920px]
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <button class="btn btn-primary" type="submit">Save</button>
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
                            Product List
                        </header>

                        <div class="panel-body">

                            <table class="table-bordered table">
                                <thead>
                                    <tr>                                         
                                        <th>file path</th>
                                        <th>Image</th>
                                        <th>Action</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $getproductlist = $this->db->query("select * from uploads where type = 'product'")->result();
                                    if (sizeof($getproductlist) > 0):
                                        foreach ($getproductlist as $product):
                                            ?>
                                            <tr>                                                   
                                                <td><?php echo $product->filepath; ?></td>
                                                <td><img src="<?php echo $baseurl; ?>assets/uploads/<?php echo $product->name; ?>" height="100"/></td>
                                                <td><a href="<?php echo site_url('admin/deleteslider_work/' . $product->id); ?>" onclick="return confirm('Are you sure want to delete this image!!');"><i class="fa fa-trash-o"></i> Delete</a></td>                                          
                                            </tr> 
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>                                   
                            </table>

                        </div>

                    </section>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-5">
                    <section class="panel">
                        <header class="panel-heading">
                           Photo gallery picture upload
                        </header>
                        <div class="panel-body">
                            <?php if ($this->session->userdata('success_g')): ?>
                                <div class="alert alert-block alert-success fade in">
                                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                    <strong>Congratulation!</strong> <?php
                                    echo $this->session->userdata('success_g');
                                    $this->session->unset_userdata('success_g');
                                    ?>
                                </div> 
                            <?php endif; ?>
                            <?php if ($this->session->userdata('failed_g')): ?>
                                <div class="alert alert-block alert-danger fade in">
                                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                    <strong>Oops!</strong> <?php
                                    echo $this->session->userdata('failed_g');
                                    $this->session->unset_userdata('failed_g');
                                    ?>
                                </div> 
                            <?php endif; ?>
                            <div class="form">
                                <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('admin/addgalaryphoto'); ?>" enctype="multipart/form-data">
                                    <div class="form-group ">
                                        <label for="slider" class="control-label col-lg-2">Add new picture</label>
                                        <div class="col-lg-9">
                                            <input class=" form-control" id="slider" name="galaryphoto" type="file" required=""/>
                                            [Image size must be minimum height: 400px and width 1100px And Maximum height: 600px and width: 1920px]
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <button class="btn btn-primary" type="submit">Save</button>
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
                            Slider List
                        </header>

                        <div class="panel-body">

                            <table class="table-bordered table">
                                <thead>
                                    <tr>     
                                        <th>Id</th>
                                        <th>file path</th>
                                        <th>Image</th>
                                        <th>Action</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    $getgalarylist = $this->db->query("select * from uploads where type = 'gallary'")->result();
                                    if (sizeof($getgalarylist) > 0):
                                        foreach ($getgalarylist as $gallary):
                                            ?>
                                            <tr>                                                   
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $gallary->filepath; ?></td>
                                                <td><img src="<?php echo $baseurl; ?>assets/uploads/<?php echo $gallary->name; ?>" height="100"/></td>
                                                <td><a href="<?php echo site_url('admin/deleteslider/' . $gallary->id); ?>" onclick="return confirm('Are you sure want to delete this image!!');"><i class="fa fa-trash-o"></i> Delete</a></td>                                               
                                            </tr> 
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>                                   
                            </table>

                        </div>

                    </section>
                </div>
            </div>
        </div>



        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
