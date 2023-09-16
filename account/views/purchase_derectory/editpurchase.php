<?php include __DIR__ . '/../topheader.php'; ?>
<?php include __DIR__ . '/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <style>
            #example_length {
                display: none
            }

            .dataTables_filter {
                width: 100%
            }
        </style>
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
        
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">Edit Purchase Voucher ID: <?php echo "Pur-" . sprintf("%06d", $purchasesummary->id); ?></div>
                        <div class="panel-body">
                            <div class="panel">
                                <div class="panel-body">

                                    <!-- tempsell strat -->
                                    <hr>
                                    <!-- tempsell end -->
                                    <!-- sellproduct start -->
                                    <form method="post" action="<?php echo site_url('purchase/updatepurchase'); ?>" class="form-horizontal" style="border-radius: 5px">
                                        <input type="hidden" name="randsellid" value="<?php echo $randsellid; ?>" />
                                        <div class="clearfix"></div>

                                        <div class="panel-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">Serial#</th>
                                                        <th style="text-align: center;">Product Name</th>
                                                        <th style="text-align: center;">Unit Price</th>
                                                        <th style="text-align: center;">Quantity</th>
                                                        <th style="text-align: center;">Unit</th>
                                                        <th style="max-width: 20%;text-align: center;">Total Price</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $demototal = 0;
                                                    $sn = 1;
                                                    #$ransellid = $randsellid;

                                                    if (sizeof($purchasedata) > 0) :
                                                        foreach ($purchasedata as $fsell) :
                                                    ?>
                                                            <tr>
                                                                <td><?php echo ($sn++); ?>
                                                                </td>
                                                                <td><?php echo $fsell->name;
                                                                    echo (($fsell->full_package == 1) ? "(Package)" : (($fsell->full_package == 2) ? "(Empty)" : (($fsell->full_package == 0) ? "(Refill)" : ""))); ?></td>
                                                                <td class="t-r"><?php echo ($fsell->buyprice); ?></td>
                                                                <td class="t-r"><?php echo ($fsell->quantity); ?></td>
                                                                <td><?php echo $fsell->unit_name; ?></td>
                                                                <td class="t-r"><?php echo ($fsell->buyprice * $fsell->quantity); ?></td>
                                                                <td style="text-align: center;"><a onclick="return confirm('Are you sure to Permanently delete this Item <?php echo $fsell->name; ?> !!')" class="btn btn-danger" href="<?php echo site_url('purchase/deletesp/' . $fsell->id); ?>"><i class="fa fa-trash-o"></i>&nbsp; Delete</a>&nbsp;&nbsp;&nbsp;<a href="#" data-toggle="modal" data-target="#editsell<?php echo $fsell->id ?>" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp; Update</a></td>
                                                            </tr>
                                                    <?php
                                                            $demototal = $demototal + $fsell->buyprice * $fsell->quantity;
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th class="t-r" style="background: greenyellow"><?php echo ($demototal); ?>
                                                            <input type="hidden" id="totalprice" name="totalprice" value="<?php echo ($demototal); ?>" />
                                                        </th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        
                                        <div class="row">
                                            
                                            <div class="col-lg-6">
                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Supplier Name</label>
                                                    <div class="col-lg-6">
                                                        <select tabindex="8" class="form-control selectpicker" data-live-search="true" name="supplier_id" id="supplier_id" required onchange="getuserdetails(this.value)">
                                                            <option value="">Select Supplier</option>
                                                            <?php
                                                            if (sizeof($getsupplier) > 0) :
                                                                foreach ($getsupplier as $list1) :

                                                            ?>
                                                                    <option <?php echo $list1->id == $supplier ? ' selected ' :  ''; ?> value="<?php echo $list1->id; ?>"><?php echo $list1->ledgername; ?></option>
                                                            <?php
                                                                endforeach;
                                                            endif;
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>   

                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Date</label>
                                                    <div class="col-lg-6">
                                                        <input tabindex="9" class="form-control" type="text" name="date" value="<?php echo $purchasesummary->date; ?>" <?php echo $this->session->userdata('role') == 'admin' ? 'id="purdate"' : 'readonly'; ?> />
                                                        <input type="hidden" name="pdate" value="<?php echo $purchasesummary->date; ?>">
                                                    </div>
                                                </div>    
                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Total</label>
                                                    <div class="col-lg-6">
                                                        <input style="background-color: greenyellow" type="number" step=".01" name="total" id="total" value="<?php echo $purchasesummary->total_purchase; ?>" class="form-control t-r" readonly="" />
                                                        <input type="hidden" name="ptotal" value="<?php echo $purchasesummary->total_purchase; ?>">

                                                    </div>
                                                </div>

                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Payment</label>
                                                    <div class="col-lg-6">
                                                        <input type="number" step=".01" name="payment" id="payment" required value="<?php echo $purchasesummary->payment; ?>" class="form-control t-r" />
                                                        <input type="hidden" name="ppayment" value="<?php echo $purchasesummary->payment; ?>">
                                                    </div>
                                                </div>


                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="margin-top: 20px;">
                                                    <div class="col-lg-12" style="float: right">

                                                        <input type="hidden" name="psupplier_id" value="<?php echo $supplier; ?>">
                                                        <button tabindex="16" type="submit" class="btn btn-primary">Update</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <?php
                                    if (sizeof($purchasedata) > 0) : $i = 1;
                                        foreach ($purchasedata as $selld) :
                                    ?>
                                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editsell<?php echo $selld->id; ?>" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                            <h4 class="modal-title">Purchase Edit</h4>
                                                        </div>

                                                        <div class="modal-body">
                                                            <?php if($selld->a_quantity==$selld->quantity):?>
                                                            <form  class="form-horizontal" role="form" action="<?php echo site_url('purchase/sppedit'); ?>" method="post" enctype="multipart/form-data">
                                                                <input type="hidden" class="form-control" name="invoiceid" id="invoiceid" value="<?php echo ($selld->invoiceid); ?>" readonly="" required="">



                                                                <?php if ($this->session->userdata('fcategory') == 'true') : ?>
                                                                    <div class="form-group">

                                                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Category</label>
                                                                        <div class="col-lg-8 col-sm-8">
                                                                            <select tabindex="1" name="category_id" id="category_id_<?php echo $i; ?>" class="form-control selectpicker" data-live-search="true" onchange="getSubCatrgory(this.value,<?php echo $i; ?>)" required>
                                                                                <option value="">Select Category</option>

                                                                                <?php

                                                                                if (sizeof($allcategory) > 0) :
                                                                                    foreach ($allcategory as $cate) :
                                                                                ?>
                                                                                        <option <?php if ($selld->category_id == $cate->id) echo ' selected '; ?> value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                                                <?php
                                                                                    endforeach;
                                                                                endif;
                                                                                ?>
                                                                            </select>
                                                                            <input type="hidden" name="pcategory_id" value="<?php echo $selld->category_id; ?>">
                                                                        </div>
                                                                        <?php if ($this->session->userdata('fsubcategory') != 'true') : ?>
                                                                            <input type="hidden" name="category_to_product" id="category_to_product" value="1">
                                                                        <?php else : ?>
                                                                            <input type="hidden" name="category_to_product" id="category_to_product" value="0">
                                                                        <?php endif; ?>
                                                                    </div>

                                                                <?php else : ?>
                                                                    <input type="hidden" name="category_id" value="1">
                                                                <?php endif; ?>
                                                                <?php if ($this->session->userdata('fsubcategory') == 'true') : ?>

                                                                    <div class="form-group">
                                                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Sub Category</label>
                                                                        <div class="col-lg-8 col-sm-8">
                                                                            <select tabindex="2" name="sub_category" id="sub_category_<?php echo $i; ?>" class="form-control selectpicker" data-live-search="true" onchange="getProduct(this.value,<?php echo $i; ?>)" required="">
                                                                                <option <?php echo $sub_category == -1 ? 'selected ' : ''; ?> value="-1">All</option>
                                                                                <?php

                                                                                if (sizeof($subCategory) > 0) :
                                                                                    foreach ($subCategory as $cate) :
                                                                                ?>
                                                                                        <option <?php echo $selld->sub_category == $cate->id ? 'selected ' : ''; ?> value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                                                <?php
                                                                                    endforeach;
                                                                                endif;
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                <?php else : ?>
                                                                    <input type="hidden" name="sub_category" value="1">
                                                                <?php endif; ?>

                                                                <div class="form-group">

                                                                    <label for="name" class="col-lg-4 col-sm-4 control-label">Product</label>
                                                                    <div class="col-lg-7 col-sm-7">
                                                                        <select tabindex="3" required class="form-control selectpicker" data-live-search="true" name="product_id" id="product_id_<?php echo $i; ?>" onchange="saveproduct(this.value)">
                                                                            <?php
                                                                            $productlist = $this->db->query("select * from products where category_id='$selld->category_id' AND sub_category='$selld->sub_category'")->result();
                                                                            if (sizeof($productlist) > 0) :
                                                                                foreach ($productlist as $allpro) :
                                                                            ?>
                                                                                    <option <?php echo $allpro->id == $selld->product_id ? ' selected ' :  ''; ?> value="<?php echo $allpro->id; ?>"><?php echo $allpro->product_name; ?></option>
                                                                            <?php
                                                                                endforeach;
                                                                            endif;
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                </div>


                                                                <div class="form-group">
                                                                    <label for="name" class="col-lg-4 col-sm-4 control-label">Unit Price</label>
                                                                    <div class="col-lg-3 col-sm-3">
                                                                        <input type="text" class="form-control t-r" name="buyprice" id="buyprice" value="<?php echo ($selld->buyprice); ?>" required="">
                                                                    </div>
                                                                </div>


                                                                <div class="form-group">
                                                                    <label for="name" class="col-lg-4 col-sm-4 control-label">Quentity</label>
                                                                    <div class="col-lg-3 col-sm-3">
                                                                        <input type="text" class="form-control t-r" name="quantity" id="quantity<?php echo $selld->id; ?>" value="<?php echo ($selld->quantity); ?>" required="">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="col-lg-offset-4 col-lg-8 col-sm-offset-4 col-sm-8">
                                                                        <input type="hidden" name="pproduct_id" value="<?php echo $selld->product_id; ?>" />
                                                                        <input type="hidden" name="randsellid" value="<?php echo $randsellid; ?>" />
                                                                        <input type="hidden" name="dailysellid" value="<?php echo $selld->id; ?>" />
                                                                        <input type="hidden" name="pprice" value="<?php echo $selld->buyprice; ?>" />
                                                                        <input type="hidden" name="pquantity" value="<?php echo $selld->quantity; ?>" />
                                                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                                        <input type="submit" name="SPSubmit" class="btn btn-default" value="Update" onclick="return checkqty('<?php echo $selld->id; ?>')" />
                                                                        <input type="hidden" id="totalprice" name="totalprice" value="<?php echo ($demototal); ?>" />
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>

                                                            </form>
                                                            <?php else:?>
                                                                <label for="">Oops! Already You Have Sold This Purchase Items</label>
                                                            <?php endif;?>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                    <?php $i++;
                                        endforeach;
                                    endif;
                                    ?>

                                    <!-- sellproduct end -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- page end-->
    </section>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#column-filtering-update').dataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: 25
        });
    });

    function checkproduct() {
        if ($("#productid").val() == '') {
            $(".selectpicker").css('border', '2px solid red');
        } else {
            $(".selectpicker").css('border', '2px solid gray');
        }
    }

    function finalcalculation() {

        var discount = ($("#discount").val());
        var labour_charge = ($("#labour_charge").val());
        var othercost = ($("#othercost").val());
        var shipping_cost = ($("#shipping_cost").val());
        var totalprice = ($("#totalprice").val());

        //if (vat=='')vat=0;
        if (discount == '') discount = 0;
        if (labour_charge == '') labour_charge = 0;
        if (othercost == '') othercost = 0;
        if (shipping_cost == '') shipping_cost = 0;
        if (totalprice == '') totalprice = 0;

        //var wvat = vat * demototal / 100;
        $("#total").val((parseFloat(totalprice) - parseFloat(discount) + parseFloat(labour_charge) + parseFloat(othercost) + parseFloat(shipping_cost)).toString());

    }

    function saveproduct(product_id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'product_id=' + product_id + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("product/getpdetails") ?>',
            data: datastring,
            success: function(response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#price").val(jsonObject.retail_sale_price);
                $("#pprice").val(jsonObject.retail_sale_price);
                $("#freeqty").val(jsonObject.available_quantity + ' ' + jsonObject.unit);

            }
        });
    }

    function refresh() {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';

        var acgid = tokenname + '=' + tokenvalue;

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("sell/getCustomer"); ?>',
            data: acgid,
            success: function(response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#customer_id").find('option').remove().end();

                $("#customer_id").append($('<option>', {
                    value: '',
                    text: 'Select Customer'
                }));

                $.each(jsonObject, function(r, v) {

                    $("#customer_id").append($('<option>', {
                        value: v.id,
                        text: v.ledgername
                    }));
                });

                $('.selectpicker').selectpicker('refresh');
            }
        });
    }

    function getuserdetails(ledgernameOrid) {

        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'ledgerid=' + ledgernameOrid + '&' + tokenname + '=' + tokenvalue;
        //console.log(datastring);
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("purchase/getcustomerdetails") ?>',
            data: datastring,
            success: function(response) {
                var dataob = JSON.parse(response);
                console.log(dataob);
                $("#preDue").val((dataob.due).toString());
                finalcalculation();
            }
        });
    }

    function getSubCatrgory(id, id2) {
        console.log(id2);
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var category_to_product = $('#category_to_product').val();
        if (category_to_product == '0') {
            var acgid = id + '&' + tokenname + '=' + tokenvalue;
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('product/getSubCategory'); ?>",
                data: 'catagoryId=' + acgid,
                success: function(data) {

                    var jsonObject = jQuery.parseJSON(data);

                    $("#sub_category_" + id2).find('option').remove().end();

                    $("#sub_category_" + id2).append($('<option>', {
                        value: '',
                        text: 'Select Sub Category'
                    }));

                    $("#sub_category_" + id2).append($('<option>', {
                        value: '-1',
                        text: 'All'
                    }));

                    $.each(jsonObject, function(r, v) {

                        $("#sub_category_" + id2).append($('<option>', {
                            value: v.id,
                            text: v.name
                        }));
                    });
                    $('.selectpicker').selectpicker('refresh');
                }
            });
        } else {
            var acgid = 'category_id=' + id + '&' + 'sub_category=1' + tokenname + '=' + tokenvalue;

            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("sell/getProduct"); ?>',
                data: acgid,
                success: function(response) {
                    var jsonObject = jQuery.parseJSON(response);
                    $("#product_id").find('option').remove().end();

                    $("#product_id").append($('<option>', {
                        value: '',
                        text: 'select product'
                    }));

                    $.each(jsonObject, function(r, v) {

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

    function getProduct(id, id2) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var category_id = $('#category_id_' + id2).val();

        var acgid = 'category_id=' + category_id + '&' + 'sub_category=' + id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("sell/getProduct"); ?>',
            data: acgid,
            success: function(response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#product_id_" + id2).find('option').remove().end();

                $("#product_id_" + id2).append($('<option>', {
                    value: '',
                    text: 'select product'
                }));

                $.each(jsonObject, function(r, v) {

                    $("#product_id_" + id2).append($('<option>', {
                        value: v.id,
                        text: v.product_name
                    }));
                });

                $('.selectpicker').selectpicker('refresh');
            }
        });
    }

    function setdiscountprice(price) {
        $("#pprice").val(price);
    }

    function setprice(disc) {

        if (disc == '')
            disc = 0.00;
        else
            var disc = parseFloat((disc));
        if ($("#pprice").val() == '')
            price = 0.00;
        else
            var price = parseFloat(($("#pprice").val()));

        var p = price - (price * disc) / 100;
        console.log(p);
        $("#price").val((p.toString()));
    }

    window.addEventListener("pageshow", function(event) {
        var historyTraversal = event.persisted ||
            (typeof window.performance != "undefined" &&
                window.performance.navigation.type === 2);
        if (historyTraversal) {
            window.close();
        }
    });
</script>