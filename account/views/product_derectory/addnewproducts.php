<?php include   __DIR__ .'/../topheader.php'; ?>
<?php include   __DIR__ .'/../menu.php'; ?>
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
                        <?php if ($this->session->userdata('success_message')): ?>
                            <div class="alert alert-block alert-success fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Congratulation!</strong> <?php
                                echo $this->session->userdata('success_message');
                                $this->session->unset_userdata('success_message');
                                ?>
                            </div> 
                        <?php endif; ?>
                        <?php if ($this->session->userdata('failed_message')): ?>
                            <div class="alert alert-block alert-danger fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Oops!</strong> <?php
                                echo $this->session->userdata('failed_message');
                                $this->session->unset_userdata('failed_message');
                                ?>
                            </div> 
                        <?php endif; ?>

                        <div class="form">
                            <form class="form-horizontal" role="form"action="<?php echo site_url('product/addproduct'); ?>" id="submitform" method="post" enctype="multipart/form-data">
                                <br/>

                                
                                
                               
                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Class</label>
                                    <div class="col-lg-3">
                                        <select name="class_id" id="class" class="form-control selectpicker" data-live-search="true" required="">
                                       
                                            <?php
                                            $getcategory = $this->db->get('classes')->result();
                                            if (sizeof($getcategory) > 0):
                                                foreach ($getcategory as $cate):
                                                    ?>
                                                    <option value="<?php echo $cate->id;?>"><?php echo $cate->class_name; ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                               
                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Product Name</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" name="product_name" id="product_name" required="">
                                    </div>
                                </div>

                                

                                <div class="form-group">
                                <label for="opening_quantity" class="col-lg-3 col-sm-3 control-label">Quantity</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="1" class="form-control fut-r" name="quantity" id="quantity" value="0" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                <label for="opening_quantity" class="col-lg-3 col-sm-3 control-label">Unit</label>                                  
                                    <div class="col-lg-2">
                                        <select name="unit_id" id="unit" class="form-control">
                                            <?php
                                            $getunit = $this->db->get('product_unit')->result();
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


                                <div class="form-group hide-for-gas">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Purchase Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="1" class="form-control fut-r" name="purchase_price" id="purchase_price" value="0" required>
                                    </div>
                                </div>
                                
                                <div  class="form-group hide-for-gas">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Sales Price</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="1" class="form-control  fut-r" name="sale_price" id="sale_price" value="0" required>
                                    </div>
                                </div>

                               
                                <div class="form-group ">
                                    <label for="name" class="col-lg-3 col-sm-3 control-label">Warning Quantity</label>
                                    <div class="col-lg-2">
                                        <input type="number" step="0.01" class="form-control  fut-r" name="warning_quantity" id="warning_quantity" value="0" required>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-5">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" id="submibtn" class="btn btn-primary">Submit</button>

                                        <button type="close" onclick="go_back()"  class="btn btn-danger">Close</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
<!-- asdfasdfasdf -->
                </section>
            </div>          
        </div>

        <!-- page end-->
    </section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script>

    function getSubCatrgory(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var acgid = id + '&' + tokenname + '=' + tokenvalue;
        if(id==6) 
        {$('.show-for-gas').show();
        $('.hide-for-gas').hide();}
        else 
        {$('.show-for-gas').hide();
        $('.hide-for-gas').show();}
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

    window.addEventListener( "pageshow", function ( event ) {
      var historyTraversal = event.persisted || 
                             ( typeof window.performance != "undefined" && 
                                  window.performance.navigation.type === 2 );
      if ( historyTraversal ) {
        // Handle page restore.
        var url= window.location.href;
            var pos = url.indexOf("addproduct"); 
            var res = url.substring(0, pos);  
            var res = res+'addproduct_form';  
       window.location.assign(res);
      }
    });

    $('#submitform').submit(function(){
        $("#submibtn", this)
      .html("Please Wait...")
      .attr('disabled', 'disabled');
        return true;
    });


</script>

