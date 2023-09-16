<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-8 col-sm-8">                
                
                    <section class="panel">
                        <header class="panel-heading">
                            Purchase Record
                            <span style="float: right;margin-right: 20px;padding-top: -10px;"><a target="_blank" href="<?php echo site_url('product/addproduct_form'); ?>"><button class="btn btn-primary btn-xs"><i class="fa fa-plus"></i>&nbsp;Add New Product</button></a></span>
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
                            <form  class="form-horizontal" role="form"action="<?php echo site_url('purchase/save_temp'); ?>" method="post" id="addform" enctype="multipart/form-data">
                            <a href=""></a>
                            <div class="form">
                                <br/>

                                <div class="form-group">
                                    <label for="invoiceid" class="col-lg-3 col-sm-4 control-label">Invoice Id</label>
                                    <div class="col-lg-3 col-sm-3">
                                        <input readonly style="font-weight: 600;background-color: rgba(173, 255, 47, 0.33)" type="text" class="form-control" name="invoiceid" id="invoiceid" value="<?php echo $randomkey; ?>" required="">
                                    </div>  

                                </div>
                                    
                                
                                <div class="form-group">
                                    <label for="class_id" class="col-lg-3 col-sm-4 control-label">Class</label> 
                                    <div class="col-sm-5">

                                    <select tabindex="1" name="class_id" id="class_id" class="form-control selectpicker" data-live-search="true"  onchange="getProduct(this.value)" required>
                                        <option value="">Select Class</option>
                                        <?php
                                        if (sizeof($classes) > 0):$i=0;
                                            foreach ($classes as $cate):
                                                ?>
                                                <option <?php  if($class_id==$cate->id) echo ' selected '; ?>  value="<?php echo $cate->id; ?>"><?php echo $cate->class_name; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    </div>
                                </div>
                               
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
                                    
                                </div>

                                <div class="form-group">
                                    <label for="brand" class="col-lg-3 col-sm-4 control-label">Quantity</label>
                                    <div class="col-lg-3 col-sm-3">
                                        <input tabindex="5" type="number" step=".01" class="form-control" name="quantity" id="quantity" required>
                                    </div>
                                    <div class="col-lg-2 col-sm-2">
                                        <input style="color: #000;font-weight: bold"  class="form-control" type="text" name="unit" id="unit" readonly="">
                                    </div>
                                </div> 

                                <div class="form-group ">
                                    <label for="buyprice" class="col-lg-3 col-sm-4 control-label">Purchase Price</label>
                                    <div class="col-lg-3 col-sm-3">
                                        <input type="number" step="0.01" tabindex="4"  class="form-control" name="buyprice" id="buyprice" required/>
                                    </div>
                                    <div class="col-lg-2 col-sm-2">
                                        
                                        <input type="text"  style="font-weight: bold" class="form-control" value="Tk." readonly="">
                                    </div>
                                </div> 

                                <div class="form-group ">
                                    <label for="sales_price" class="col-lg-3 col-sm-4 control-label">Sales Price</label>
                                    <div class="col-lg-3 col-sm-3">
                                        <input type="number" step="0.01" tabindex="4"  class="form-control" name="sales_price" id="sales_price" required/>
                                    </div>
                                    <div class="col-lg-2 col-sm-2">
                                        
                                        <input type="text"  style="font-weight: bold" class="form-control" value="Tk." readonly="">
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label for="comment" class="col-lg-3 col-sm-4 control-label">Comment</label>
                                    <div class="col-lg-3 col-sm-3">
                                        <input type="text" tabindex="6"  class="form-control" name="comment" />
                                    </div>
                                </div>                         


                                <div class="form-group">
                                    <div class="col-lg-offset-4 col-sm-offset-4 col-lg-6 col-sm-6">
                                        <input  type="hidden" name="randomkey" value="<?php echo $randomkey; ?>"/>
                                        <input type="hidden" name="tax" value="0"/>
                                       
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button tabindex="6" type="submit" class="btn btn-primary" tabindex="-1" id="addbutton">Add</button>                                        
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
                                    <td><a href="<?php echo site_url('purchase/showtemp?randomkey=' .$item->randomkey ); ?>"><?php echo $item->randomkey;?></a></td>
                                    <td><?php echo $item->titem;?></td>
                                    <td><?php echo $item->tprice;?></td>
                                    <td><a href="<?php echo site_url('purchase/tempremove?randomkey=' .$item->randomkey ); ?>"><i class="fa fa-trash-o" title="Remove purchase"></i></a></td>
                                </tr>
                            <?php endforeach;?>

                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <!-- Temp Table Data -->
            <div class="col-lg-12 col-sm-12" style="margin-bottom: 60px">                
                <form class="form-horizontal" role="form"action="<?php echo site_url('purchase/savepurchase'); ?>" method="post" id="submitform" enctype="multipart/form-data">
                    <section class="panel">
                        <header class="panel-heading">
                            Purchase Record
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
                                <table id="temptable" class="display table table-bordered table-striped dataTable">
                                    <thead>
                                        <tr>                                    
                                            <th>SN</th>  
                                            <th>Product Name</th> 
                                            <th>Comment</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Purchase Price</th>
                                            <th>Sales Price</th>
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
                                                    <td><?php echo ($s); ?></td>  

                                                    <td><?php echo $buy->product_name;?></td>    <input type="hidden" name="product_idl[]" value="<?php echo $buy->product_id;?>"> 
                                               
                                                    <td><input type="text" name="comment[]" value="<?php echo $buy->comment; ?>"  id="com_<?php echo($s);?>" onchange="tableData(<?php echo($s);?>)"/>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0.01" name="qtyl[]" value="<?php echo $buy->qty;?>" id="qt_<?php echo($s);?>" onchange="tableData(<?php echo($s);?>)" required >
                                                    </td> 
                                                    <td>
                                                        <?php echo $buy->unit; ?>
                                                    </td>                                           
                                                    <td><input type="number"  step="0.01" min="0.01" name="buyprice[]" value="<?php echo$buy->unit_price;?>" onchange="tableData(<?php echo($s);?>)" id="bp_<?php echo($s);?>" required >
                                                    </td>
                                                    <td><input type="number"  step="0.01" min="0.01" name="sales_price[]" value="<?php echo $buy->sales_price;?>" onchange="tableData(<?php echo($s);?>)" id="sp_<?php echo($s);?>" required ></td>                                  
                                                    <td>
                                                        <input type="text" id="tp_<?php echo($s);?>" value="<?php echo ($buy->qty * $buy->unit_price); ?>" readonly />
                                                        <input type="hidden" id="tempid_<?php echo($s++);?>" value="<?php echo $buy->id; ?>">
                                                    </td>                                                      
                                                    <td><a href="<?php echo site_url('purchase/removedata?id=' . $buy->id . '&randomkey=' . $randomkey.'&class_id='.$class_id.'&date='.$date.'&suppliers='.$suppliers); ?>"><i class="fa fa-trash-o" title="Remove purchase"></i></a>
                                                </td>
                                                </tr>
                                                <?php
                                                $totalprice = $totalprice + ($buy->qty * $buy->unit_price);
                                            endforeach;
                                       
                                        ?>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td>
                                                <input  style="font-weight: bold" type="number" readonly id="totalprice" name="t_price" value="<?php echo $totalprice;?>">
                                            </td>
                                            <td></td>
                                        </tr>
                                        <?php 
                                        
                                        endif;
                                        ?>
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
                                    <input tabindex="7" <?php echo $this->session->userdata('role') == 'admin'?'id="purdate"':'readonly';?> type="text" class="form-control" name="date" required="" value="<?php echo date('Y-m-d h:i:s');?>">
                                </div>
                            </div>
                            <div class="form-group">
                                    <label for="name" class="col-lg-3 col-sm-4 control-label">Supplier</label>
                                    <div class="col-lg-7 col-sm-6">

                                        <select tabindex="8" name="suppliers" id="suppliers" class="form-control selectpicker" data-live-search="true" required=""> 
                                        <?php 
                                        $companyid = $this->session->userdata('company_id');
                                        ?>
                                                     <option value="">Select One</option>
                                                    <?php
                                                    
                                                    $supplier = $this->db->query("select a.*,d.name as district_name from accountledger as a left join districts as d on a.district=d.id where a.accountgroupid = 5 AND a.company_id = '$companyid' and a.status<>0 order by a.ledgername asc")->result();

                                                    if (sizeof($supplier) > 0 ):
                                                        foreach ($supplier as $suppr):
                                                            ?>
                                                            <option <?php echo ($suppr->id == $suppliers)? 'selected' : ''; ?> value="<?php echo $suppr->id; ?>"><?php echo substr($suppr->ledgername."(" . $suppr->mobile.") (" . $suppr->address . ", " . $suppr->district_name . ")",0,145); ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif; 
                                                    ?>
                                            
                                        </select>
                                    </div>
                            </div>

                            <div style="margin-top: 30px;" class="form-group">
                                <label for="ptotal" class="col-lg-3 col-sm-4 control-label">Total</label>
                                <div class="col-lg-7 col-sm-6">
                                    <input readonly tabindex="15" type="number" class="form-control" name="ptotal" id="ptotal" required value="<?php echo $totalprice;?>" style=" height: 35px; font-size: 30px;"  />
                                    <input id="pprice" hidden type="text" value="<?php echo $totalprice;?>">
                                    
                                    <input id="empty_cylinder_price" hidden type="text" value="0">
                                    <input type="hidden" id="gas_price" value="0">
                                </div>
                            </div>
                            <div style="margin-top: 30px;" class="form-group">
                                <label for="ptotal" class="col-lg-3 col-sm-4 control-label">Payment</label>
                                <div class="col-lg-7 col-sm-6">
                                    <input  tabindex="15" type="text" class="form-control" name="payment" value="0" style=" height: 35px; font-size: 30px;"  />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-3 col-sm-offset-3 col-lg-8 col-sm-8">  
                                    <input type="hidden" name="randomkey" value="<?php echo $randomkey; ?>"/>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <button tabindex="15" type="submit" class="btn btn-primary" name="stype" value="onlysave" id="submitbutton">Confirm Purchase</button> 
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
<?php include __DIR__ .'/../footer.php'; ?>
<script>


    function getProduct(class_id){
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        
        var acgid = 'class_id=' + class_id + '&' + tokenname + '=' + tokenvalue;

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

    function saveproduct(product_id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'product_id=' + product_id + '&' + tokenname + '=' + tokenvalue;
        
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("product/getpdetails") ?>',
            data: datastring,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#unit").val(jsonObject.unit);
                $("#sales_price").val(jsonObject.sale_price);
                $("#buyprice").val(parseFloat(jsonObject.purchase_price).toFixed(2))
            }
        });
    }

    function sumall(){
        if($("#discount").val()=='')
            discount = 0.00;
        else
        var discount = parseFloat(($("#discount").val()));

        if($("#labourcost").val()=='')
            labourcost = 0.00;
        else
        var labourcost = parseFloat(($("#labourcost").val()));

        if($("#shippingcost").val()=='')
            shippingcost = 0.00;
        else
        var shippingcost = parseFloat(($("#shippingcost").val()));

        if($("#othercost").val()=='')
            othercost = 0.00;
        else
        var othercost = parseFloat(($("#othercost").val()));

        if($("#pprice").val()=='')
            pprice = 0.00;
        else
        var pprice = parseFloat(($("#pprice").val()));

        var ptotal2 = pprice + othercost + shippingcost + labourcost - discount;
        $("#ptotal").val(ptotal2);
    }

    function setdiscount(){
        if($("#pprice").val()=='')
            pprice = 0.00;
        else
        var pprice = parseFloat(($("#pprice").val()));

        if($("#discountp").val()=='')
            discountp = 0.00;
        else
        var discountp = parseFloat(($("#discountp").val()));

        $("#discount").val((discountp/100)*pprice);
        sumall();
    }

    $('#addform').submit(function(){
        $("#addbutton", this)
          .html("Please Wait...")
          .attr('disabled', 'disabled');
        return true;
    });

    $('#submitform').submit(function(){
        $("#submitbutton", this)
          .html("Please Wait...")
          .attr('disabled', 'disabled');
        return true;
    });


    function tableData(rownumber){
        
        var quantity = parseFloat($("#qt_"+rownumber).val());

        console.log(quantity);

        if(quantity>0){

            var x;
            var totalprice = 0.0;
            var subtotal=0.0;
            for (x = 1; x < document.getElementById("temptable").rows.length-1; x++) {
              subtotal = $("#qt_"+x).val() *  $("#bp_"+x).val();
              $("#tp_"+x).val(subtotal);
              totalprice=totalprice + subtotal;
            }
            $("#totalprice").val(totalprice);
            $("#ptotal").val(totalprice);
            $("#pprice").val(totalprice);

            sumall();
            

            var tempid = $("#tempid_"+rownumber).val(); 
            var comment = $("#com_"+rownumber).val(); 
            var bprice = $("#bp_"+rownumber).val(); 
            var sprice = $("#sp_"+rownumber).val(); 
               
            var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
            
            
            var acgid = 'tempid=' + tempid + '&' + 'comment=' + comment + '&' + 'sprice=' + sprice + '&' + 'bprice=' + bprice + '&' + 'quantity=' + quantity + '&' + tokenname + '=' + tokenvalue;

            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("purchase/updatetemppurchase") ?>',
                data: acgid,
                success: function (response) {
                }
            });

        }
               
    }

</script>