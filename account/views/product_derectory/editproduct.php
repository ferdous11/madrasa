<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->   
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading">Product Update</div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="submitform" role="form"action="<?php echo site_url('product/saveupdateproduct'); ?>" method="post" enctype="multipart/form-data">

                                
                            <?php if($this->session->userdata('fcategory')=='true'):?>
                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Product Category</label>
                                    <div <?php if($pdata->category_id==6) echo "hidden"; ?> class="col-lg-5">
                                        <select  onchange="getSubCatrgory(this.value)" name="category_id" id="category_id" class="form-control selectpicker" data-live-search="true" required="">
                                            <option value="">Select Category</option>
                                            <?php
                                            $getcategory = $this->db->get_where('category', array('company_id' => $company_id))->result();
                                            if (sizeof($getcategory) > 0):
                                                foreach ($getcategory as $cate):
                                                    ?>
                                                    <option <?php echo $pdata->category_id==$cate->id?'selected ':''; ?>  value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>

                                        
                                    </div>
                                    <div <?php if($pdata->category_id!=6) echo "hidden"; ?> class="col-lg-5">
                                    <input  type="text" class="form-control"  value="LP Gas" readonly>
                                    </div>
                                </div>
                            <?php else:?>
                                <input type="hidden" name="category_id" value="1">
                            <?php endif;?>
                            <?php if($this->session->userdata('fsubcategory')=='true'):?>
                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Sub Category</label>
                                    <div class="col-lg-5">
                                        <select  name="sub_category" id="sub_category" class="form-control selectpicker" data-live-search="true" required="">
                                            <option value="">Select Sub Category</option>
                                            <?php
                                            if (sizeof($getsubcategory) > 0):
                                                foreach ($getsubcategory as $cate):
                                                    ?>
                                                    <option <?php echo $pdata->sub_category==$cate->id?'selected ':''; ?> value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php else:?>
                                <input type="hidden" name="sub_category" value="1">
                            <?php endif;?>
                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Product Name</label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" name="product_name" id="product_name" value="<?php echo $pdata->product_name;?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Product Id</label>
                                    <div class="col-lg-2">
                                        <input type="text" onchange="checkproducid()" class="form-control" name="product_id" id="product_id" value="<?php echo $pdata->product_id;?>" required>
                                        <span style="color:red;" id="productexit"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="purchase_unit" class="col-lg-3 col-sm-3 control-label">Product Unit</label>                                  
                                    <div class="col-lg-2">
                                        <select name="purchase_unit" id="purchase_unit" class="form-control">
                                            <?php
                                            $getunit = $this->db->get_where('product_unit', array('company_id' => $company_id))->result();
                                            if (sizeof($getunit) > 0):
                                                foreach ($getunit as $cate):
                                                    ?>
                                                    <option <?php echo $pdata->unit==$cate->id?'selected ':''; ?>  value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div <?php if($pdata->category_id==6) echo "hidden" ?> class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Opening Quantity</label>
                                    <div class="col-lg-2">
                                        <input type="number" <?php echo $pdata->opening_quantity!=0? "readonly":"";?> step="0.01" class="form-control fut-r" name="opening_quantity" id="opening_quantity" value="<?php echo ($pdata->opening_quantity);?>" required>
                                    </div>
                                </div>

                                <div <?php if($pdata->category_id==6) echo "hidden" ?> class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Purchase Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control fut-r" name="purchase_price" id="purchase_price" value="<?php echo number_format($pdata->purchase_price,2,'.', '');?>" required>
                                    </div>
                                </div>

                                <div <?php if($pdata->category_id==6) echo "hidden" ?> class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Sales Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control fut-r" name="sale_price" id="sale_price" value="<?php echo ($pdata->sale_price);?>" required>
                                    </div>
                                </div>

                                <!-- <div class="forgas form-group" <?php if($pdata->category_id!=6) echo "hidden" ?> >
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Empty Cylinder Quantity</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control fut-r" name="empty_cylinder" id="empty_cylinder" value="<?php echo number_format(($pdata->empty_cylinder),2,'.', '');?>" required>
                                    </div>
                                </div> -->

                                <div <?php if($pdata->category_id!=6) echo "hidden"?> class="form-group forgas">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Cylinder Purchase Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control fut-r" name="cylinder_p_p" id="cylinder_p_p" value="<?php echo number_format(($pdata->cylinder_p_p),2,'.', '');?>" required>
                                    </div>
                                </div>

                                <div  <?php if($pdata->category_id!=6) echo "hidden"?> class="form-group forgas">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Gas Purchase Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control fut-r" name="gas_price" id="gas_price" value="<?php echo number_format(($pdata->gas_price),2,'.', '');?>" required>
                                    </div>
                                    
                                </div>

                                
                                <div <?php if($pdata->category_id!=6) echo "hidden"?> class="form-group forgas">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Cylinder Sales Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control fut-r" name="cylinder_s_p" id="cylinder_s_p" value="<?php echo number_format(($pdata->cylinder_s_p),2,'.', '');?>" required>
                                    </div>
                                </div>

                                <div  <?php if($pdata->category_id!=6) echo "hidden"?> class="form-group forgas">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Gas Sales Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control fut-r" name="gas_sales_price" id="gas_sales_price" value="<?php echo number_format(($pdata->gas_sales_price),2,'.', '');?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Warning Quantity</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control fut-r" name="warning_quantity" id="warning_quantity" value="<?php echo ($pdata->warning_quantity);?>" required>
                                    </div>
                                </div>

                                <div <?php if($pdata->category_id==6) echo "hidden" ?> class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Decimale Multiplier</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control fut-r" name="decimale_multiplier"  value="<?php echo ($pdata->decimale_multiplier);?>" required>
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
                                <div class="col-lg-offset-3 col-lg-6">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input type="hidden" name="preopening_quantity" value="<?php echo $pdata->opening_quantity;?>">
                                    <input type="hidden" name="id" id="productID" value="<?php echo $pdata->id;?>">
                                    <input type="hidden" name="pretotal_quantity" value="<?php echo $pdata->total_quantity;?>">
                                    <input type="hidden" name="preavailable_quantity" value="<?php echo $pdata->available_quantity;?>">
                                    <button type="submit" id="submibtn" class="btn btn-primary">Submit</button> 
                                    <a href="<?php echo site_url('product'); ?>"><button type="button" class="btn btn-info">Back</button></a>
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

<script>

    function getCategory(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('home/getCategory'); ?>",
            data: 'ledger_id=' + acgid,
            success: function (data) {
                
                 var jsonObject = jQuery.parseJSON(data);
                 
                $("#supplier").find('option').remove().end();
                
                $("#supplier").append($('<option>', {
                        value: '' ,
                        text: '----Select One----'
                }));

                $.each( jsonObject, function( r,v) {
                    
                    $("#supplier").append($('<option>', {
                        value: v.id,
                        text: v.ledgername
                    }));
                });
                 $('.selectpicker').selectpicker('refresh');
            }
        });
    }

    function getSubCatrgory(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = id + '&' + tokenname + '=' + tokenvalue;
        if(id==6) $('#forgas').show();
        else $('#forgas').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('product/getSubCategory'); ?>",
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
    function checkproducid(){
        var values = $('#product_id').val();
        var pid= $("#productID").val();
        
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var product_id = values + '& pid=' + pid + '&' + tokenname + '=' + tokenvalue;
        var exitid = 0;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('product/ifproductidexistedit'); ?>",
            data: 'product_id=' + product_id,
            success: function (data) {
                 var jsonObject = jQuery.parseJSON(data); 
              
                 if(jsonObject==1){
                    $("#productexit").text("This Product ID '"+values+"' already Exist!! পণ্য নম্বর '"+values+"' আগে থেকে আছে !!");
                    $("#product_id").val("");
                }
                 else{
                    $("#productexit").text("");
                 }
            }
                 
        });
    }

    $('#submitform').submit(function(){
        $("#submibtn", this)
      .html("Please Wait...")
      .attr('disabled', 'disabled');
        return true;
    });


</script>