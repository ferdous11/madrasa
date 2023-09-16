<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-8 col-sm-8">                
                
                    <section class="panel">
                        <header class="panel-heading">
                            Sales Return
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
                            <form class="form-horizontal" role="form"action="<?php echo site_url('sellreturn/save_temp'); ?>" method="post" enctype="multipart/form-data">
                                <div class="form" id="addform">
                                <br/>
     
                                <input  type="hidden" class="form-control" name="invoiceid" id="invoiceid" value="<?php echo $randomkey; ?>" required="">
                                <?PHP if(empty($customer)):?>
                                <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-4 control-label">Customer</label>
                                    <div class="col-lg-5 col-sm-6">

                                        <select tabindex="8" name="customer_id" id="suppliers" class="form-control selectpicker" data-live-search="true" required="" onchange="getuserdetails(this.value)"> 
                                        <?php 
                                        $companyid = $this->session->userdata('company_id');
                                        ?>
                                        <option value="">Select One</option>
                                        <?php
                                        
                                        $supplier = $this->db->query("select l.*,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.accountgroupid = 16 AND l.company_id = '$companyid' and l.status<>0 order by l.ledgername asc")->result();

                                        if (sizeof($supplier) > 0 ):
                                            foreach ($supplier as $suppr):
                                                ?>
                                                <option  value="<?php echo $suppr->id; ?>"><?php echo $suppr->ledgername;                 echo $suppr->mobile!=null?" (" . $suppr->mobile.")":"";
                                                echo " (" . $suppr->address . ", " . $suppr->district_name . ")"; ?></option>
                                                <?php
                                            endforeach;
                                        endif; 
                                        ?>
                                            
                                        </select>
                                    </div>
                                </div>
                                <?php else:?>
                                    <div class="form-group">
                                    <div class="col-lg-12 col-sm-12" style="border: 1px solid red;background-color: yellow;">
                                        <h3><?php echo $customer->ledgername;     echo $customer->mobile!=null?" (" . $customer->mobile.")":"";
                                        echo " (" . $customer->address . ", " . $customer->district_name . ")"; ?> </h3></div>
                                        <input  type="hidden" class="form-control" name="customer_id"  value="<?php echo $customer->id; ?>">
                                    </div>
                                <?php endif;?>

                                <div class="form-group">
                                    <label for="buyprice" class="col-lg-3 col-sm-4 control-label">Product Id</label>
                                    <div class="col-lg-3 col-sm-3">
                                        <input type="text" tabindex="6"  class="form-control"id="productuniqid" onchange="productbyid()" />
                                    </div>
                                </div> 
 
                                <?php if($this->session->userdata('fcategory')=='true'):?>
                                <div class="form-group">
                                    <label for="category_id" class="col-lg-3 col-sm-4 control-label">Category</label> 
                                    <div class="col-sm-5">

                                    <select tabindex="1" name="category_id" id="category_id" class="form-control selectpicker" data-live-search="true"  onchange="getSubCatrgory(this.value)" required>
                                        <option value="">Select Category</option>
                                        
                                        <?php

                                        if (sizeof($allcategory) > 0):$i=0;
                                            foreach ($allcategory as $cate):
                                                ?>
                                                <option <?php  if($category_id==$cate->id) echo ' selected '; ?>  value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    </div>
                                    <?php if($this->session->userdata('fsubcategory')!='true'):?>
                                        <input type="hidden" name="category_to_product" id="category_to_product" value="1">
                                    <?php else:?>
                                        <input type="hidden" name="category_to_product" id="category_to_product" value="0"> 
                                    <?php endif;?>
                                </div>
                                <?php else:?>
                                <input type="hidden" name="category_id" value="1">     
                                <?php endif;?>
                                <?php if($this->session->userdata('fsubcategory')=='true'):?>

                                <div class="form-group">
                                    <label for="sub_category" class="col-lg-3 col-sm-4 control-label">Sub Category</label> 
                                    <div class="col-sm-5">
                                
                                    <select tabindex="2" name="sub_category" id="sub_category" class="form-control selectpicker" data-live-search="true" onchange="getProduct(this.value)" required="">
                                        <option value="">Select Sub Category</option>
                                        <option <?php echo $sub_category==-1?'selected ':''; ?> value="-1">All</option>
                                        <?php

                                        if (sizeof($subCategory) > 0):
                                            foreach ($subCategory as $cate):
                                                ?>
                                                <option <?php echo $sub_category==$cate->id?'selected ':''; ?> value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
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
                                    <label for="product_id" class="col-lg-3 col-sm-4 control-label"> Product</label> 
                                    <div class="col-sm-5">
                                    <select tabindex="3" required class="form-control selectpicker" data-live-search="true" name="product_id" id="product_id" onchange="saveproduct(this.value)">
                                        <option value="">Select Product</option>
                                        <?php
                                        if (sizeof($productlist) > 0):
                                            foreach ($productlist as $allpro):
                                                ?>
                                                <option value="<?php echo $allpro->id;?>"><?php echo $allpro->product_name; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>                                                    
                                    </select>                                        
                           
                                    </div>
                                    
                                    <div  <?php echo($category_id!=6)?"hidden":"";?> class="col-lg-2 col-sm-2 forgas">
                                        
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="RadioOptions" id="inlineRadio2" value="0" checked>
                                          <label class="form-check-label" for="inlineRadio2">Refill</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="RadioOptions" id="inlineRadio1" value="1" >
                                          <label class="form-check-label" for="inlineRadio1">Full Package</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                          <input class="form-check-input" type="radio" name="RadioOptions" id="inlineRadio3" value="2" >
                                          <label class="form-check-label" for="inlineRadio3">Empty</label>
                                        </div>     
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="brand" class="col-lg-3 col-sm-4 control-label">Quantity</label>
                                    <div class="col-lg-3 col-sm-3">
                                        <input tabindex="5" type="number" step=".01" class="form-control" name="quantity" id="quantity" required>
                                    </div>
                                    <div class="col-lg-2 col-sm-2">
                                        <input style="color: #000;font-weight: bold"  class="form-control" type="text" name="unit" id="unit" readonly="">
                                        <input type="hidden" class="form-control" name="unit_id" id="unit_id" />
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label for="buyprice" class="col-lg-3 col-sm-4 control-label">Return Price</label>
                                    <div class="col-lg-3 col-sm-3">
                                        <input type="number" step="0.01" tabindex="4"  class="form-control" name="buyprice" id="sellprice" required/>
                                        <input type="hidden"  id="empty_cylinder_price" />
                                        <input type="hidden"  id="gas_price" />
                                        <input type="hidden"  id="empty_cylinder_sales_price" />
                                        <input type="hidden"  id="gas_sales_price" />
                                        <input type="hidden"  id="pprice" />
                                    </div>
                                    <div class="col-lg-2 col-sm-2">
                                        <input  type="hidden" name="randomkey" value="<?php echo $randomkey; ?>"/>
                                        <input type="text"  style="font-weight: bold" class="form-control" value="Tk." readonly="">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                        <label for="name" class="col-lg-3 col-sm-4 control-label">Comment</label>
                                        <div class="col-lg-3 col-sm-3">
                                            <input type="text"  class="form-control" name="comment" >
                                        </div>
                                       
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-4 col-sm-offset-4 col-lg-6 col-sm-6">
                                        
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button tabindex="6" type="submit" id="addbutton" class="btn btn-primary" tabindex="-1">Add</button>                                        
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>
                </form>
            </div>
            <div class="col-lg-4 col-sm-4"> 
                <section class="panel">
                    <header class="panel-heading">
                        Incomplete Record
                    </header>
                    <div class="panel-body">
                        <table class="display table table-bordered table-striped dataTable">
                            <thead>
                                <tr>                                    
                                    <th>Invoice Id</th>  
                                    <th>Total Item</th> 
                                    <th>Total Price</th>
                                    <th>Action</th>
                                </tr>
                                <?php foreach ($uncomlitelist as $item): ?>
                                <tr>
                                    <td><a href="<?php echo site_url('sellreturn/showtemp?randomkey=' .$item->randomkey ); ?>"><?php echo $item->randomkey;?></a></td>
                                    <td><?php echo $item->titem;?></td>
                                    <td><?php echo $item->tprice;?></td>
                                    <td><a href="<?php echo site_url('sellreturn/tempremove?randomkey=' .$item->randomkey ); ?>"><i class="fa fa-trash-o" title="Remove purchase"></i></a></td>
                                </tr>
                            <?php endforeach;?>

                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="col-lg-12 col-sm-12" style="margin-bottom: 60px">                
                <form class="form-horizontal" role="form"action="<?php echo site_url('sellreturn/savepurchase'); ?>" method="post" enctype="multipart/form-data" onsubmit="stype.disabled = true; return true;" id="submitform">
                    <section class="panel">
                        <header class="panel-heading">
                            Sales Return Record
                        </header>
                        <div class="panel-body">
                           
                            <div class="form">
                                <table class="display table table-bordered table-striped dataTable">
                                    <thead>
                                        <tr>                                    
                                            <th>SN</th>  
                                            <th>Product Name</th> 
                                            <th>Comment</th> 
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $s = 1;
                                        $totalbuy = 0;
                                        $totalsell = 0;
                                        $totalbill = 0;
                                        $Grosstotalbutcost = 0;
                                        $totalprice = 0;

                                        if (sizeof($purchasedata) > 0):
                                            foreach ($purchasedata as $buy):
                                                ?>
                                                <tr>
                                                    <td><?php echo ($s++); ?></td>  

                                                    <td><?php echo $buy->product_name;echo (($buy->full_package==1)? "(Package)" :(($buy->full_package==2)?"(Empty)":(($buy->full_package==0)?"(Refill)":""))); ?></td>    <input type="hidden" name="product_idl[]" value="<?php echo $buy->product_id;?>"> 
                                                    <td><?php echo ($buy->comment);?><input type="hidden" name="comment[]" value="<?php echo $buy->comment;?>"></td>
                                                    <td><?php echo ($buy->qty) . ' ' . $buy->unit; ?>
                                                        <input type="hidden" name="qtyl[]" value="<?php echo $buy->qty;?>">
                                                        <input type="hidden" name="unitl[]" value="<?php echo $buy->unit_id;?>">
                                                    </td>                                           
                                                    <td><?php echo (number_format($buy->unit_price, 2));
                                                    ?><input type="hidden" name="pricel[]" value="<?php echo $buy->unit_price;?>"></td>
                                                    <input type="hidden" name="full_package[]" value="<?php echo $buy->full_package; ?>"/>                                  
                                                    <td><?php echo ($buy->qty * $buy->unit_price); ?></td>                                                      
                                                    <td><a href="<?php echo site_url('sellreturn/removedata?id=' . $buy->id . '&randomkey=' . $randomkey.'&sub_category='.$sub_category.'&category_id='.$category_id.'&date='.$date.'&suppliers='.$customerid); ?>"><i class="fa fa-trash-o" title="Remove purchase"></i></a></td>
                                                </tr>
                                                <?php
                                                $totalprice = $totalprice + ( $buy->qty * $buy->unit_price );
                                            endforeach;
                                        endif;
                                        ?>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td style="font-weight: bold"><?php echo number_format($totalprice, 2)." Tk."; ?>
                                                <input type="hidden" name="t_price" value="<?php echo $totalprice;?>">
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tbody>                                
                                </table>
                            </div>
                        </div>
                    </section>

                    <div class="form">
                        <div class="col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label for="sdate" class="col-lg-3 col-sm-4 control-label">Date</label>
                                <div class="col-lg-7 col-sm-6">
                                    <input tabindex="7" type="text" class="form-control" name="date" <?php echo $this->session->userdata('role') == 'admin'?'id="purdate"':'readonly';?> required="" value="<?php echo date('Y-m-d H:i:s');?>">
                                </div>
                            </div>
                            
                            <div style="margin-top: 30px;" class="form-group">
                                <label for="ptotal" class="col-lg-3 col-sm-4 control-label">Discount Back</label>
                                <div class="col-lg-7 col-sm-6">
                                    <input tabindex="15" type="number" onchange="settotalamount(this.value)" step="0.01" class="form-control" name="discount" value="" style=" height: 35px; font-size: 30px;"  />
                                </div>
                            </div>

                            <div style="margin-top: 30px;" class="form-group">
                                <label for="ptotal" class="col-lg-3 col-sm-4 control-label">Total</label>
                                <div class="col-lg-7 col-sm-6">
                                    <input readonly tabindex="15" type="text" class="form-control" name="ptotal" id="ptotal" required="" value="<?php echo $totalprice;?>" style=" height: 35px; font-size: 30px;"  />
                                    <input id="tprice" hidden type="text" value="<?php echo $totalprice;?>">
                                </div>
                            </div>

                            <div style="margin-top: 30px;" class="form-group">
                                <label for="ptotal" class="col-lg-3 col-sm-4 control-label">Payment Amount</label>
                                <div class="col-lg-7 col-sm-6">
                                    <input tabindex="15" oninput="finalcalculation()" type="number" step="0.01" class="form-control" name="payment_amount" id="payment_amount" value="" style=" height: 35px; font-size: 30px;"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">  
                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Previous Due</label>
                                <div class="col-lg-4 col-sm-4">
                                    <input readonly style=" height: 35px; font-size: 30px;" type="text"  class="form-control" value="<?php echo $due;?>"  id="pdue" name="pdue" >
                                </div>
                            </div>  

                            <div class="form-group">
                                <label for="name" class="col-lg-4 col-sm-4 control-label">Current Due</label>
                                <div class="col-lg-4 col-sm-4">
                                    <input readonly style=" height: 35px; font-size: 30px;" type="text"  class="form-control" value="<?php echo $due-$totalprice;?>" id="cdue" name="cdue" >
                                </div>
                            </div>  

                            <div class="form-group">
                                <label for="comments" class="col-lg-4 col-sm-4 control-label">Comments</label>
                                <div class="col-lg-6 col-sm-6">
                                    <textarea tabindex="13" class="form-control" name="comments" id="comments" ></textarea>
                                </div>                                
                            </div>
                            

                            <div class="form-group">
                                <div class="col-lg-offset-3 col-sm-offset-3 col-lg-8 col-sm-8">  
                                    <input type="hidden" name="randomkey" value="<?php echo $randomkey; ?>"/>
                                    <input  type="hidden" class="form-control" name="customer_id"  value="<?php if(!empty($customer)) echo $customer->id; ?>">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <button tabindex="14" type="submit" class="btn btn-primary" name="stype" id="submitbutton" value="onlysave">Confirm</button>
                                     
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
<script>

    function getuserdetails(ledgernid) {
        console.log(ledgernid);
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'ledgerid=' + ledgernid + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("sell/getcustomerdetails") ?>',
            data: datastring,
            success: function(response) {
                var dataob = JSON.parse(response);
                $("#pdue").val((dataob.due).toString());
                finalcalculation();
            }
        });
    }

    function finalcalculation() {
        var ptotal = ($("#ptotal").val());
        var payment_amount = ($("#payment_amount").val());
        var pdue = ($("#pdue").val());
        
        if (ptotal == '') ptotal = 0;
        if (payment_amount == '') payment_amount = 0;
        if (pdue == '') pdue = 0;

        $("#cdue").val((parseFloat(pdue) - parseFloat(ptotal) + parseFloat(payment_amount)).toString());
    }

    function getSubCatrgory(id,subcatagoryid=0) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var category_to_product= $('#category_to_product').val();
        if(id==6)
            $(".forgas").show();
        else
            $(".forgas").hide();
        if(category_to_product=='0'){
            var acgid = id + '&' + tokenname + '=' + tokenvalue;
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('product/getSubCategory'); ?>",
                data: 'catagoryId=' + acgid,
                success: function (data) {
                    
                     var jsonObject = jQuery.parseJSON(data);
                 
                $("#sub_category").find('option').remove().end();

                $("#sub_category").append($('<option>', {
                        value: '' ,
                        text: 'Select Sub Category'
                }));

                $("#sub_category").append($('<option>', {
                        value: '-1' ,
                        text: 'All'
                }));

                $.each( jsonObject, function( r,v) {
                    if(v.id!=subcatagoryid)
                    $("#sub_category").append($('<option>', {
                        value: v.id,
                        text: v.name
                    }));
                else
                    $('#sub_category').append('<option value='+v.id+' selected="selected">'+v.name+'</option>');
                });
                 $('.selectpicker').selectpicker('refresh');
            }
            });
        }
        else{
            
            var acgid = 'category_id=' + id + '&' + 'sub_category=1' + tokenname + '=' + tokenvalue;
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("sell/getProduct"); ?>',
                data: acgid,
                success: function (response) {
                    var jsonObject = jQuery.parseJSON(response);
                    $("#product_id").find('option').remove().end();
                    
                    $("#product_id").append($('<option>', {
                            value: '' ,
                            text: 'select product'
                    }));
                    
                    $.each( jsonObject, function( r,v) {
                        
                        $("#product_id").append($('<option>', {
                            value: v.id,
                            text: v.product_name
                        }));
                    });

                    $('.selectpicker').selectpicker('refresh');            
                }
            });
        }
    }
    function getProduct(id,productid=0){
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var category_id =  $('#category_id').val();
        var acgid = 'category_id=' + category_id + '&' + 'sub_category=' + id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("sell/getProduct"); ?>',
            data: acgid,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#product_id").find('option').remove().end();
                
                $("#product_id").append($('<option>', {
                        value: '' ,
                        text: 'select product'
                }));
                
                $.each( jsonObject, function( r,v) {
                    if(v.id!=productid)
                    $("#product_id").append($('<option>', {
                        value: v.id,
                        text: v.product_name
                    }));
                else
                    $('#product_id').append('<option value="'+v.id+'" selected="selected">'+v.product_name+'</option>');
                });
                
                $('.selectpicker').selectpicker('refresh');  
                saveproduct(productid);          
            }
        });
    }

    function saveproduct(product_id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'product_id=' + product_id + '&' + tokenname + '=' + tokenvalue;
        console.log("sadfa");
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("product/getpdetails") ?>',
            data: datastring,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#unit").val(jsonObject.unit);
                $("#unit_id").val(jsonObject.purchase_unit);
                $("#pprice").val(jsonObject.retail_sale_price);
                if(jsonObject.category_id==6)
                    $("#sellprice").val(jsonObject.gas_sales_price);
                else
                    $("#sellprice").val(jsonObject.retail_sale_price);
                $("#tprice").val(jsonObject.retail_sale_price)
                $("#buyprice").val(jsonObject.purchase_price);
                $("#empty_cylinder_price").val(jsonObject.cylinder_p_p);
                $("#gas_price").val(jsonObject.gas_price);
                $("#empty_cylinder_sales_price").val(jsonObject.cylinder_s_p);
                $("#gas_sales_price").val(jsonObject.gas_sales_price);
                
            }
        });
    }

    function settotalamount(discount){

        $("#ptotal").val($("#tprice").val()-discount);
        finalcalculation();
    }

    $('input[type="radio"]').click(function(){
            
            if($('input[name=RadioOptions]:checked').val()==0){
            
                $("#sellprice").val($("#gas_sales_price").val());
            }
            else if($('input[name=RadioOptions]:checked').val()==1){
               
                $("#sellprice").val($("#pprice").val());
            }
            else{
               
                $("#sellprice").val($("#empty_cylinder_sales_price").val());
            }

    });

    $('#addform').submit(function(){
    $("#addbutton", this)
      .html("Please Wait...")
      .attr('disabled', 'disabled');
    return true;});

    $('#submitform').submit(function(){
    $("#submitbutton", this)
      .html("Please Wait...")
      .attr('disabled', 'disabled');
    return true;});

    function productbyid()  {
        var productuniqid = $("#productuniqid").val();
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'product_id=' + productuniqid + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("product/getpdetailsbyuniqid") ?>',
            data: datastring,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                
                $("#unit").val(jsonObject.unit);
                $("#unit_id").val(jsonObject.purchase_unit);
                
                if(jsonObject.category_id==6)
                    $("#sellprice").val(jsonObject.gas_sales_price);
                else
                    $("#sellprice").val(jsonObject.retail_sale_price);
                $("#product_sales_price").val(jsonObject.retail_sale_price);
                $("#buyprice").val(jsonObject.purchase_price);
                $("#ppprice").val(jsonObject.purchase_price);
                $("#empty_cylinder_price").val(jsonObject.cylinder_p_p);
                $("#gas_price").val(jsonObject.gas_price);
                $("#empty_cylinder_sales_price").val(jsonObject.cylinder_s_p);
                $("#gas_sales_price").val(jsonObject.gas_sales_price);
                $('select[name=category_id]').val(jsonObject.category_id);
                if(jsonObject.category_id==6){

                }
                if(jsonObject.decimale_multiplier!='10.00') $("#isd_m").show();else $("#isd_m").hide();
                if(jsonObject.category_id=='6') $("#isgas").show();else $("#isgas").hide();
                getSubCatrgory(jsonObject.category_id,jsonObject.sub_category);
                getProduct(jsonObject.sub_category,jsonObject.id);

            }
        });

    }
   

</script>