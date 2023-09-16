<?php include  __DIR__ . "../../topheader.php"; ?>
<?php include  __DIR__ . "../../menu.php"; ?>
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
        <?php
        if ($this->session->userdata('success')) :
            echo '<div class="alert alert-dismissable alert-success"><a class="close" data-dismiss="alert">×</a><i class="icon icon-warning-sign"></i> ' . $this->session->userdata('success') . '</i></div>';
            $this->session->unset_userdata('success');
        endif;
        if ($this->session->userdata('failed')) :
            echo '<div class="alert alert-dismissable alert-danger"><a class="close" data-dismiss="alert">×</a><i class="icon icon-warning-sign"></i> ' . $this->session->userdata('failed') . '</i></div>';
            $this->session->unset_userdata('failed');
        endif;
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">Sales Product <span style="float: right;margin-left: 20px"><button class="btn btn-primary" value="Print" onclick="chalan()">Print Chalan</button></span>&nbsp;&nbsp;</div>
                        <div class="panel-body">
                            <div class="panel">
                                <div class="panel-body">

                                    <!-- tempsell strat -->
                                    <form method="post" action="<?php echo site_url('sell/savetempsell'); ?>" id="temsellform">

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label for="customer_id">Customer Name</label>
                                            <select tabindex="5" class="form-control selectpicker" data-live-search="true" name="customer_id" id="customer_id" required onchange="getuserdetails(this.value)">
                                                <option value="">Select Customer</option>
                                                <?php
                                                if (sizeof($getcustomer) > 0) :
                                                    foreach ($getcustomer as $customer) :
                                                ?>
                                                        <option <?php echo $customer->id == $customer_id ? " selected " : ""; ?> value="<?php echo $customer->id; ?>"><?php echo $customer->ledgername;echo $customer->mobile != null ? " (" . $customer->mobile . ")" : "";echo " (" . $customer->address . ", " . $customer->district_name . ")"; ?></option>
                                                <?php
                                                    endforeach;
                                                endif;
                                                ?>

                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <label for="date">Date:</label>
                                            <input tabindex="9" class="form-control" type="text" name="date" id="date" onchange="setdate(this.value)" value="<?php if (isset($date)) echo $date;else echo date("Y-m-d H:i:s"); ?>" <?php echo $this->session->userdata('role') == 'admin' ? 'id="purdate"' : 'readonly'; ?> />
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-2">
                                                <label for="category_id">Product ID</label>
                                                <input style="height: 40px;font-size: 30px;" type="text" class="form-control" id="productuniqid" onchange="productbyid()" tabindex="1" />
                                        </div>

                                        <div  class="col-sm-2">
                                            <label for="freeqty">Quantity</label>
                                            <input style="height: 40px;font-size: 30px;"  type="number" step=".01" onchange="setquantity(this)" class="form-control" name="freeqty" id="typeqty" required="" autocomplete="off" tabindex="2" />
                                        </div>

                                        <div  class="col-sm-2">
                                            <label for="freeqty">Available</label>
                                            <input style="height: 40px;font-size: 20px;" type="text" class="form-control" id="freeqty" readonly="" />
                                        </div>

                                        <div  class="col-sm-2">
                                            <label for="price">Price</label>
                                            <input style="height: 40px;font-size: 30px;" onkeyup="setdiscountprice(this)" tabindex="3" class="form-control" name="price" id="price" required="" type="number" step="5" min='0' max="" autocomplete="off" />
                                        </div>

                                        <div class="col-sm-2">
                                            <label for="comment">Comment</label>
                                            <input style="height: 40px;font-size: 30px;" type="text" class="form-control" id="comment" name="comment" tabindex="6" />
                                        </div>

                                        <div  class="col-sm-2">
                                            <label style="color: white;" for="submit">Submit</label>

                                            <input type="hidden" name="randsellid" value="<?php echo $randsellid; ?>" />
                                            <input type="hidden" name="available_quantity" id="available_quantity" value="0" />

                                            <input style="height: 50px;font-size: 25px;" tabindex="4" type="submit" class="form-control btn btn-sm btn-primary" value="Add" id="addbutton" onclick="return checkproduct()" />
                                        </div>

                                            <input type="hidden" id="pprice" value="0">
                                            <input type="hidden" id="unitname" value="">
                                    </div>

                                    <div class="row">
                                            <div  class="form-group col-sm-7">
                                                <div class="col-sm-3">

                                                    <label for="category_id">Category</label>
                                                    <select tabindex="7" name="category_id" id="category_id" class="form-control selectpicker" data-live-search="true" onchange="getSubCatrgory(this.value)" required>
                                                        <option value="">Select Category</option>

                                                        <?php

                                                        if (sizeof($allcategory) > 0) : $i = 0;
                                                            foreach ($allcategory as $cate) :
                                                        ?>
                                                                <option <?php if ($category_id == $cate->id) echo ' selected '; ?> value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                        <?php
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </select>
                                                    <?php if ($this->session->userdata('fsubcategory') != 'true') : ?>
                                                        <input type="hidden" name="category_to_product" id="category_to_product" value="1">
                                                    <?php else : ?>
                                                        <input type="hidden" name="category_to_product" id="category_to_product" value="0">
                                                    <?php endif; ?>

                                                </div>

                                            
                                           

                                                <div class="col-sm-3">
                                                    <label for="sub_category">Sub Category</label>
                                                    <select tabindex="8" name="sub_category" id="sub_category" class="form-control selectpicker" data-live-search="true" onchange="getProduct(this.value)" required="">
                                                        <option value="">Select Sub Category</option>
                                                        <option <?php echo $sub_category == -1 ? 'selected ' : ''; ?> value="-1">All</option>
                                                        <?php

                                                        if (sizeof($subCategory) > 0) :
                                                            foreach ($subCategory as $cate) :
                                                        ?>
                                                                <option <?php echo $sub_category == $cate->id ? 'selected ' : ''; ?> value="<?php echo $cate->id; ?>"><?php echo $cate->name; ?></option>
                                                        <?php
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </select>
                                                </div>
                                            

                                            <div class="col-sm-6">

                                                <label for="product_id">Product</label>
                                                <select tabindex="9" required class="form-control selectpicker" data-live-search="true" name="product_id" id="product_id" onchange="saveproduct(this.value)">
                                                    <option value="">Select Product</option>
                                                    <?php
                                                    if (sizeof($productlist) > 0) :
                                                        foreach ($productlist as $allpro) :
                                                    ?>
                                                            <option value="<?php echo $allpro->id; ?>"><?php echo $allpro->product_name; ?></option>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        

                                        <div id="isd_m" hidden style="padding-left: 0px" class="form-group col-sm-2">
                                            <div style="padding-right: 0px;padding-left:0px;" class="col-sm-8">
                                                <label for="freeqty">Q.Decimal Value</label>
                                                <input type="text" class="form-control" id="d_m_v" />
                                                <input type="hidden" id="d_m" />
                                            </div>
                                        </div>
                                    </div>

                                    </form>
                                    <hr>
                                    <!-- tempsell end -->
                                    <!-- sellproduct start -->
                                    <form method="post" action="<?php echo site_url('Sell/sellsave'); ?>" id="submitform" class="form-horizontal" style="border-radius: 5px" name="submitform" onsubmit="required()">
                                        <input type="hidden" name="randsellid" value="<?php echo $randsellid; ?>" />
                                        <div class="clearfix"></div>

                                        <div class="panel-body" id="chalan">
                                            <table id="temptable" class="table table-bordered" >
                                                <thead>
                                                    <tr>
                                                        <th>Serial#</th>
                                                        <th>Product Name</th>
                                                        <th>Comment</th>
                                                        <th>Quantity</th>
                                                        <th class="hidetoprint">Sales Price</th>

                                                        <th>Unit</th>
                                                        <th class="hidetoprint" style="max-width: 20%">Total Price</th>
                                                        <th class="hidetoprint">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $demototal = 0;
                                                    $sn = 1;
                                                    #$ransellid = $randsellid;
                                                    $selldata = $this->db->query("select s.*,p.product_name as name,p.unit as unit_id ,u.name as unit_name from tempsell as s left join products as p on s.product_id=p.id left join product_unit as u on p.unit=u.id  where randsellid='$randsellid' order by id asc")->result();
                                                    echo '<input type="hidden" id="productlistifempty" value="' . sizeof($selldata) . '" />';
                                                    if (sizeof($selldata) > 0) : $i = 0;
                                                        foreach ($selldata as $fsell) :
                                                    ?>
                                                            <tr>
                                                                <td><?php echo ($sn); ?></td>
                                                                <td><?php echo $fsell->name;?></td>
                                                                <td><input type="text" name="comment[]" value="<?php echo $fsell->comment; ?>" id="com_<?php echo ($sn); ?>" onchange="tableData(<?php echo ($sn); ?>)" /></td>
                                                                <td><input type="number" step=".01" name="qty[]" id="qt_<?php echo ($sn); ?>" value="<?php echo $fsell->qty; ?>" onchange="tableData(<?php echo ($sn); ?>)" required max='<?php echo $fsell->available_quantity; ?>' min='.01' /></td>
                                                                <td class="hidetoprint"><input type="number" step=".01" min="0" id="up_<?php echo ($sn); ?>" name="unit_price[]" value="<?php echo $fsell->unit_price; ?>" onchange="tableData(<?php echo ($sn); ?>)" /></td>

                                                                <td><?php echo $fsell->unit_name; ?></td>
                                                                <td class="hidetoprint">
                                                                    <input type="text" id="tp_<?php echo ($sn); ?>" value="<?php echo ceil($fsell->unit_price * $fsell->qty); ?>" readonly />
                                                                    <input type="hidden" id="tempid_<?php echo ($sn++); ?>" value="<?php echo $fsell->id; ?>">
                                                                </td>
                                                                <td class="hidetoprint"><a href="<?php echo site_url('sell/removesell/' . $fsell->id); ?>"><i class="fa fa-trash-o"></i></a></td>
                                                            </tr>


                                                            <input type="hidden" name="product_id[]" value="<?php echo $fsell->product_id; ?>" />
                                                            <input type="hidden" name="unit[]" value="<?php echo $fsell->unit_id; ?>" />
                                                            <input type="hidden" name="product_name[]" value="<?php echo $fsell->name; ?>" />
                                                

                                                    <?php
                                                            $demototal = $demototal + ceil($fsell->unit_price * $fsell->qty);
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </tbody>

                                                <tfoot class="hidetoprint">
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th><input type="text" style="max-width: 200px;background: greenyellow" class="form-control col-lg-2" id="totalprice" name="totalprice" value="<?php 
                                                        echo moneyFormatIndia($demototal);
                                                        ?>" /></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <!-- <div class="col-lg-6">
                                                <section class="panel">
                                                    <header class="panel-heading">
                                                        Incomplete Record
                                                    </header>
                                                    <div class="panel-body">
                                                        <table class="display table table-bordered table-striped dataTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>Customer Name</th>
                                                                    <th>Date</th>
                                                                    <th>Total Item</th>
                                                                    <th>Total Price</th>
                                                                    <th>Action</th>
                                                                </tr>

                                                                <?php foreach ($uncomlitelist as $item) : ?>
                                                                    <tr>
                                                                        <td><a href="<?php echo site_url('sell/showtemp?randomkey=' . $item->randsellid); ?>"><?php $incustomer = $this->db->query("select al.*,ds.name as district_name from accountledger as al left join districts as ds on al.district=ds.id where al.id='$item->customer_id'")->row();
                                                                                                                                                                echo        $incustomer->ledgername . " (" . $incustomer->address . ", " . $incustomer->district_name . ")"; ?></a>
                                                                        </td>

                                                                        <td><?php echo $item->date; ?></td>
                                                                        <td><?php echo $item->titem; ?></td>
                                                                        <td><?php echo $item->tprice; ?></td>
                                                                        <td><a onclick="return confirm('Are you sure to Permanently delete this Incomplete Record !!')"  href="<?php echo site_url('sell/tempremove?randomkey=' . $item->randsellid); ?>"><i class="fa fa-trash-o" title="Remove purchase"></i></a></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </section>
                                            </div> -->

                                            <div class="col-lg-6">
                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Comment</label>
                                                    <div class="col-lg-6">
                                                        <input type="text" tabindex="12" name="sumComment" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Discount</label>
                                                    <div class="col-lg-6">
                                                        <input  tabindex="12" pattern="[0-9,]+" name="discount" id="discount" value="0" class="form-control" required onchange="finalcalculation()" />
                                                    </div>
                                                </div>

                                                <div hidden class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Labour Charge</label>
                                                    <div class="col-lg-6">
                                                        <input  required tabindex="13" pattern="[0-9,]+" name="labour_charge" id="labour_charge" value="0" class="form-control" onchange="finalcalculation()" />
                                                    </div>

                                                </div>

                                                <div hidden class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Transport Cost</label>
                                                    <div class="col-lg-6">
                                                        <input required tabindex="13" pattern="[0-9,]+" name="transport_cost" id="transport_cost" value="0" class="form-control" onchange="finalcalculation()" />
                                                    </div>

                                                </div>

                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Total</label>
                                                    <div class="col-lg-6">
                                                        <input style="background-color: greenyellow; font-family: AdorshoLipi;font-size: 22px;" name="totaldue" id="totaldue" value="<?php echo moneyFormatIndia($demototal); ?>" class="form-control" readonly="" />
                                                    </div>
                                                </div>

                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Paid Amount</label>
                                                    <div class="col-lg-6"><b>
                                                            <input tabindex="15" style="font-family: AdorshoLipi;font-size: 22px;" pattern="[0-9,]+" name="paidAmount" id="paidAmount" value="0" class="form-control" required onkeyup="setcomma(this)" /></b>
                                                    </div>
                                                </div>
                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Previous Due </label>
                                                    <div class="col-lg-6">
                                                        <input style="background-color: greenyellow" type="text" name="preDue" id="preDue" value="0" class="form-control" readonly="" />
                                                    </div>
                                                </div>

                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="background: #f1f2f7;color: #000;">
                                                    <label class="control-label col-lg-5">Current Due</label>
                                                    <div class="col-lg-6">
                                                        <input style="background-color: greenyellow" type="text" name="fTotalDue" id="fTotalDue" value="0" class="form-control" readonly="" />
                                                    </div>
                                                </div>
                                                <div class="panel-heading col-lg-offset-2 col-lg-10" style="margin-top: 20px;">
                                                    <div class="col-lg-12" style="float: right">
                                                        <input type="hidden" name="mobile" id="mobile" />
                                                        <input type="hidden" name="name" id="name" />
                                                        <input type="hidden" name="address" id="address" />
                                                        <input type="hidden" name="finaldate" id="finaldate" value="<?php echo $date; ?>" />
                                                        <input type="hidden" name="finalcustomer" id="finalcustomer" value="<?php echo $customer_id; ?>" />
                                                        <button tabindex="16" id="submitbutton" type="submit" class="btn btn-primary">Save</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- sellproduct end -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="customeradd" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Add New Customer</h4>
                            </div>

                            <div class="modal-body">

                                <form class="form-horizontal" role="form" action="<?php echo site_url('master/addledger_sell'); ?>" method="post" enctype="multipart/form-data">

                                    <div class="form-group">
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Customer Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="ledger_name" maxlength="100" id="ledger_name" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="mobile" class="col-lg-4 col-sm-4 control-label">Mobile</label>
                                        <div class="col-lg-8">
                                            <input type="number" class="form-control" maxlength="11" name="mobile" id="mobile">
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label for="opbalance" class="col-lg-4 col-sm-4 control-label">Opening Balance</label>
                                        <div class="col-lg-4">
                                            <input type="number" class="form-control" maxlength="11" name="opbalance" id="opbalance" value="0">
                                        </div>
                                        <div class="col-lg-3">
                                            <select class="form-control" name="baltype">
                                                <option value="credit">Credit</option>
                                                <option value="debit" selected="">Debit</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-lg-4 col-sm-4 control-label">Email</label>
                                        <div class="col-lg-8">
                                            <input type="email" class="form-control" name="email" maxlength="50" id="email">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="address" class="col-lg-4 col-sm-4 control-label">Address</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="address" id="address" maxlength="150">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-lg-offset-4 col-lg-8">
                                            <input type="hidden" class="form-control" maxlength="16" name="bankacc" id="bankacc" value="N/A">
                                            <input type="hidden" class="form-control" maxlength="20" name="accountgroup" id="accountgroup" value="25">
                                            <input type="hidden" name="fromname" value="customer" />
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


            </div>
        </div>

        <!-- page end-->
    </section>
</section>

<?php include  __DIR__ . '/../footer.php'; ?>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        var customer_id = "<?php echo $customer_id; ?>";
        $('#column-filtering-update').dataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: 25
        });
        if (customer_id != '')
            getuserdetails(customer_id);
    });

    function checkproduct() {
        if ($("#productid").val() == '') {
            $(".selectpicker").css('border', '2px solid red');
        } else {
            $(".selectpicker").css('border', '2px solid gray');
        }
    }

    function finalcalculation() {
        var demototal = $("#totalprice").val();
        if (demototal == '') demototal = 0;
        else
            demototal = demototal.replaceAll(',','');
            
            
        var discount = $("#discount").val();

        if (discount == '') discount = 0;
        else{
            discount = discount.replaceAll(',','');
            $("#discount").val(Number(discount).toLocaleString('en-IN'));}

        var labour_charge = $("#labour_charge").val();
        if (labour_charge == '') labour_charge = 0;
        else{
            labour_charge = labour_charge.replaceAll(',','');
            $("#labour_charge").val(Number(labour_charge).toLocaleString('en-IN'));}
        var transport_cost =$("#transport_cost").val();
        if (transport_cost == '') transport_cost = 0;
        else{
            transport_cost = transport_cost.replaceAll(',','');
            $("#transport_cost").val(Number(transport_cost).toLocaleString('en-IN'));}
        var paidAmount = $("#paidAmount").val();
        if (paidAmount == '') paidAmount = 0;
        else{
            paidAmount = paidAmount.replaceAll(',','');
            $("#paidAmount").val(Number(paidAmount).toLocaleString('en-IN'));}
        var preDue = $("#preDue").val();
        if (preDue == '') preDue = 0;
        else{
            preDue = preDue.replaceAll(',','');
            $("#preDue").val(Number(preDue).toLocaleString('en-IN'));}


        $("#totaldue").val((parseFloat(demototal) - parseFloat(discount) + parseFloat(labour_charge) + parseFloat(transport_cost)).toLocaleString('en-IN'));

        $("#fTotalDue").val((parseFloat(demototal) - parseFloat(discount) + parseFloat(labour_charge) + parseFloat(preDue) + parseFloat(transport_cost) - parseFloat(paidAmount)).toLocaleString('en-IN'));
        
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
                
                var rej = Number(jsonObject.sale_price);
                $("#price").attr('max',rej);
                let temp = rej.toLocaleString('en-IN');
                $("#price").val(temp);
                
                    
                $("#pprice").val(jsonObject.sale_price);
                $("#d_m").val(jsonObject.decimale_multiplier);
                if (jsonObject.decimale_multiplier != '10.00') $("#isd_m").show();
                else $("#isd_m").hide();
                
                $("#pprice").val(jsonObject.sale_price);
                $("#d_m").val(jsonObject.decimale_multiplier);
                $("#freeqty").val(jsonObject.available_quantity + ' ' + jsonObject.unit);
                $('select[name=category_id]').val(jsonObject.category_id);
                $("#available_quantity").val(jsonObject.available_quantity);
                $('#unitname').val(jsonObject.unit);
                $("#productuniqid").val(jsonObject.product_id);
                console.log()

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

        if (ledgernameOrid == 'newcustomer') {
            $('#customeradd').modal('show');
        } else {

            var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
            var datastring = 'ledgerid=' + ledgernameOrid + '&' + tokenname + '=' + tokenvalue;
            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("sell/getcustomerdetails") ?>',
                data: datastring,
                success: function(response) {
                    var dataob = JSON.parse(response);
                    $("#mobile").val(dataob.mobile);
                    $("#name").val(dataob.name);
                    $("#address").val(dataob.address);
                    $("#preDue").val((dataob.due).toString());
                    $("#finalcustomer").val(ledgernameOrid);
                    finalcalculation();
                }
            });
        }
    }

    function getSubCatrgory(id, subcatagoryid = 0) {
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

                    $("#sub_category").find('option').remove().end();

                    $("#sub_category").append($('<option>', {
                        value: '',
                        text: 'Select Sub Category'
                    }));

                    $("#sub_category").append($('<option>', {
                        value: '-1',
                        text: 'All'
                    }));

                    $.each(jsonObject, function(r, v) {
                        if (v.id != subcatagoryid)
                            $("#sub_category").append($('<option>', {
                                value: v.id,
                                text: v.name
                            }));
                        else
                            $('#sub_category').append('<option value=' + v.id + ' selected="selected">' + v.name + '</option>');
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

    function getProduct(id, productid = 0) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var category_id = $('#category_id').val();

        var acgid = 'category_id=' + category_id + '&' + 'sub_category=' + id + '&' + tokenname + '=' + tokenvalue;

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
                    if (v.id != productid)
                        $("#product_id").append($('<option>', {
                            value: v.id,
                            text: v.product_name
                        }));
                    else
                        $('#product_id').append('<option value="' + v.id + '" selected="selected">' + v.product_name + '</option>');
                });


                $('.selectpicker').selectpicker('refresh');
            }
        });
    }

    function setdiscountprice(e) {
        if(e.value.slice(-1)!='.' && e.value!=''){
            let tmp = e.value.replaceAll(',','');
            let num = Number(tmp);
            $("#pprice").val(num);
            var rej = num.toLocaleString('en-IN'); 
            $("#price").val(rej);
        }
    }
    
    function setcomma(e) {
        if(e.value.slice(-1)!='.' && e.value!=''){
            let tmp = e.value.replaceAll(',','');
            let num = Number(tmp);
            var rej = num.toLocaleString('en-IN'); 
            e.value=rej;
            finalcalculation();
        }
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

    function chalan() {
        $(".hidetoprint").hide();
       
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';
        
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=0, top=0";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        var customer = $("#customer_id option:selected").text();
               
        oTable = document.getElementById("chalan");
        docprint.document.open();
        docprint.document.write('<html>');
        docprint.document.write('<head><style>');
        docprint.document.write('@media print {  @page {size: 8.3in 11.67in;margin-top:0cm !important; margin-bottom: 1cm !important;margin-left:0.1in !important;margin-right:0.1in !important;}}');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table td{border:1px solid gray;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:left;font-size:17px;padding-left:7px;}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        
        docprint.document.write('<div style="text-align:center;">' + comaddress);
        docprint.document.write('<h2 style="margin: 5px;"><u>Incomplete Sales Chalan</u></h2> </div>');
        docprint.document.write('<p style="margin-top:0;"> Date <?php echo date("Y-m-d"); ?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Invoice Id:<?php echo $randsellid; ?></p><h4>Customer:' + customer + '</h4><hr style="width:700px;">');

        docprint.document.write(oTable.innerHTML);
        docprint.document.write('</center></body></html>');
        docprint.document.write('</html>');
        docprint.document.close();
        docprint.print();
        
        
        docprint.close();
        $(".hidetoprint").show();
    }

    window.addEventListener("pageshow", function(event) {
        var historyTraversal = event.persisted ||
            (typeof window.performance != "undefined" &&
                window.performance.navigation.type === 2);
        if (historyTraversal) {
            // Handle page restore.
            var url = window.location.href;
            var pos = url.indexOf("sell");
            var res = url.substring(0, pos + 4);
            window.location.assign(res);
        }
    });

    function setquantity(input) {

        console.log(input);
        if ($("#typeqty").val() == '')
            var typeqty = 0.0;
        else
            var typeqty = parseFloat($("#typeqty").val());

        

        if ($("#d_m_v").val() == '')
            var d_m_v = 0.0;
        else
            var d_m_v = parseFloat(($("#d_m_v").val()));

        console.log(d_m_v);

        var d_m = parseFloat(($("#d_m").val()));
        var typeqty = typeqty + (d_m_v / d_m);

        var curentqty = parseFloat(($("#freeqty").val()));


        if (typeqty > curentqty || typeqty <= 0)
            input.setCustomValidity('No Available.!!');
        else
            input.setCustomValidity('');
        console.log(d_m_v, d_m, typeqty);

        $("#typeqty").val(parseFloat(typeqty).toFixed(2));
    }

    function productbyid() {
        var productuniqid = $("#productuniqid").val();
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'product_id=' + productuniqid + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("product/getpdetailsbyuniqid") ?>',
            data: datastring,
            success: function(response) {
                var jsonObject = jQuery.parseJSON(response);
               
                var rej = Number(jsonObject.sale_price);
                $("#price").attr('max',rej);
                let temp = rej.toLocaleString('en-IN');
                $("#price").val(temp);
                $("#pprice").val(jsonObject.sale_price);
                $("#d_m").val(jsonObject.decimale_multiplier);
                $("#freeqty").val(jsonObject.available_quantity + ' ' + jsonObject.unit);
                $('select[name=category_id]').val(jsonObject.category_id);
                $("#available_quantity").val(jsonObject.available_quantity);
                $('#unitname').val(jsonObject.unit);
                $("#productuniqid").val(jsonObject.product_id);



                if (jsonObject.decimale_multiplier != '10.00') $("#isd_m").show();
                else $("#isd_m").hide();
               
                getSubCatrgory(jsonObject.category_id, jsonObject.sub_category);
                getProduct(jsonObject.sub_category, jsonObject.id);
            }
        });

    }

    function setdate(fdate) {
        $("#finaldate").val(fdate);
    }

    function tableData(rownumber) {

        var quantity = parseFloat($("#qt_" + rownumber).val());
        var available_quantity = parseFloat($("#qt_" + rownumber).attr('max'));

        console.log(quantity, available_quantity);

        if (available_quantity >= quantity && quantity > 0) {

            var x;
            var totalprice = 0.0;
            var subtotal = 0.0;
            for (x = 1; x < document.getElementById("temptable").rows.length - 1; x++) {
                subtotal = $("#qt_" + x).val() * $("#up_" + x).val();
                $("#tp_" + x).val(Math.ceil(subtotal));
                totalprice = totalprice + Number(Math.ceil(subtotal));
            }

            $("#totalprice").val(totalprice.toLocaleString('en-IN'));
            finalcalculation();

            var tempid = $("#tempid_" + rownumber).val();
            var comment = $("#com_" + rownumber).val();
            var price = $("#up_" + rownumber).val();

            var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';


            var acgid = 'tempid=' + tempid + '&' + 'comment=' + comment + '&' + 'price=' + price + '&' + 'quantity=' + quantity + '&' + tokenname + '=' + tokenvalue;

            $.ajax({
                type: 'POST',
                url: '<?php echo site_url("sell/updatetempsell") ?>',
                data: acgid,
                success: function(response) {}
            });

        }

    }

    $('#temsellform').submit(function() {
        $("#addbutton", this)
            .html("Please Wait...")
            .attr('disabled', 'disabled');
        return true;
    });

    $('#submitform').submit(function() {
        var values = $('#productlistifempty').val();
        if (values == '0') {
            alert("Please Insert Product First !! দয়া করে আগে পণ্য উঠান !!");
            return false;
        } else {
            $("#submitbutton", this)
                .html("Please Wait...")
                .attr('disabled', 'disabled');
            return true; 
        }
    });
</script>