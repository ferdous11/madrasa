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
                        Add New products
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
                            <form class="form-horizontal" role="form"action="<?php echo site_url('product/addproduct'); ?>" method="post" enctype="multipart/form-data">
                                <br/>

                                
                                

                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Product Category</label>
                                    <div class="col-lg-3">
                                        <select name="category_id" id="category_id" class="form-control selectpicker" data-live-search="true" onchange="getSubCatrgory(this.value)" required="">
                                            <option value="">Select Category</option>
                                            <?php
                                            $getcategory = $this->db->get_where('category', array('company_id' => $this->session->userdata('company_id')))->result();
                                            if (sizeof($getcategory) > 0):
                                                foreach ($getcategory as $cate):
                                                    ?>
                                                    <option value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Sub Category</label>
                                    <div class="col-lg-3">
                                        <select name="sub_category" id="sub_category" class="form-control selectpicker" data-live-search="true" required="">
                                            <option value="">Sub Category</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Product ID</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" name="product_id" id="product_id" value="" required="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Product Name</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" name="product_name" id="product_name" required="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">purchase Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control" name="purchase_price" id="purchase_price" value="০" required>
                                    </div>
                                    <label for="brand"  style="width: 50px;margin-left: 20px;!important" class="col-lg-1 col-sm-1 control-label">Unit</label>                                  
                                    <div class="col-lg-2">
                                        <select onchange="setPerchaseUnit(this)" name="purchase_unit" id="purchase_unit" class="form-control">
                                            <?php
                                            $getunit = $this->db->get_where('product_unit', array('company_id' => $this->session->userdata('company_id')))->result();
                                            if (sizeof($getunit) > 0):
                                                foreach ($getunit as $cate):
                                                    ?>
                                                    <option value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Sales Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control" name="retail_sale_price" id="retail_sale_price" value="০" required>
                                    </div>

                                    <label for="brand" style="width: 50px;margin-left: 20px;!important" class="col-lg-1 col-sm-1 control-label">Unit</label>                                  
                                    <div class="col-lg-2">
                                        <label id="sellingUnit" style="width: 50px;margin-left: 0px;!important" class="col-lg-1 col-sm-1 control-label"><?php echo $getunit[0]->name?></label>
                                        <!-- <select name="retail_sale_unit" id="retail_sale_unit" class="form-control">
                                            <?php
                                            $getunit = $this->db->get_where('product_unit', array('company_id' => $this->session->userdata('company_id')))->result();
                                            if (sizeof($getunit) > 0):
                                                foreach ($getunit as $cate):
                                                    ?>
                                                    <option value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select> -->
                                    </div>

                                </div>


                                <!-- <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Gross Selling Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control" name="gross_sale_price" id="gross_sale_price"  required>
                                    </div>

                                    <label for="brand" style="width: 50px;margin-left: 20px;!important" class="col-lg-1 col-sm-1 control-label">Unit </label>                                  
                                    <div class="col-lg-2">
                                        <select name="gross_sale_unit" id="gross_sale_unit" class="form-control">
                                            <?php
                                            $getunit = $this->db->get_where('product_unit', array('company_id' => $this->session->userdata('company_id')))->result();
                                            if (sizeof($getunit) > 0):
                                                foreach ($getunit as $cate):
                                                    ?>
                                                    <option value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>

                                </div> -->


                                


                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Opening Quantity</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control" name="opening_quantity" id="opening_quantity" value="০" required>
                                    </div>
                                    
                                </div>

                                
                                <!-- <div class="form-group">
                                    <label for="brand" class="col-lg-3 col-sm-3 control-label">Product Picture(Optional)</label>
                                    <div class="col-lg-5">
                                        <input type="file" class="form-control" name="productpicture" id="productpicture">
                                        [ Product image maximum height: 400px and width: 600px]
                                    </div>
                                </div> -->

                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-5">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-primary">Submit</button>

                                        <button type="close" onclick="go_back()"  class="btn btn-danger">Close</button>
                                    </div>
                                </div>

                            </form>

                        </div>

                        <div style="margin-bottom: 20px">

                        <span style="float: left;margin-left: 50px;"><a href="#" data-toggle="modal" data-target="#addcsv"><button class="btn btn-primary"><i class="fa fa-upload"></i>&nbsp;Upload CSV</button></a></span>

                        <span style="float: left;margin-left: 50px;"><a href="#" data-toggle="modal" data-target="#addcsv"><button class="btn btn-primary"><i class="fa fa-download"></i>&nbsp;Demo</button></a></span>

                        </div>

                    </div>

                    

            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addcsv" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Upload CSV File</h4>
                    </div>

                    <div class="modal-body">
                        <form class="form-horizontal" role="form"action="<?php echo site_url('master/ucsv'); ?>" method="post" enctype="multipart/form-data">
                        
                              <!-- BEGIN FORM-->

                                <div class="form-group">
                                    <label for="upload_data_file" class="col-lg-3 col-sm-3 control-label">Upload File<span class="required">*</span></label>
                                    <div class="col-lg-7">
                                        <input type="file" name="upload_data_file" class="input-file uniform_on form-control" id="upload_data_file" required>
                                    </div>
                                      
                                </div>
                                      
                                <div class="form-group">
                                    <div class="col-lg-offset-4 col-lg-8">
                                            
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <input type="submit" class="btn btn-primary" name="csv"  value="Submit"/>&nbsp;&nbsp;
                                        <button type="button" class="btn btn-danger"  data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                        </form>   
                    </div>
                </div>
            </div>
        </div>
<!-- asdfasdfasdf -->
                </section>
            </div>          
        </div>

        <!-- page end-->
    </section>
</section>

<script>

    function getSubCatrgory(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('home/getSubCategory'); ?>",
            data: 'catagoryId=' + acgid,
            success: function (data) {
                
                 var jsonObject = jQuery.parseJSON(data);
                 
                $("#sub_category").find('option').remove().end();
                
                $("#sub_category").append($('<option>', {
                        value: '' ,
                        text: '----Select One----'
                }));

                $.each( jsonObject, function( r,v) {
                    
                    $("#sub_category").append($('<option>', {
                        value: v.id,
                        text: v.name
                    }));
                });
                 $('.selectpicker').selectpicker('refresh');
            }
        });
    }

    function go_back(){
        window.history.back();
    }
    
    function setPerchaseUnit(sel){
       var t= sel.options[sel.selectedIndex].text;
       $("#sellingUnit").text(t);
    }


</script>
<?php include 'footer.php'; ?>
