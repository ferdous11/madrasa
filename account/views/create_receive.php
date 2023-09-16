<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <style>
            #example_length{display: none}
            .dataTables_filter{width: 100%}
        </style>
        <?php
        if ($this->session->userdata('success')):
            echo '<div class="alert alert-dismissable alert-success"><a class="close" data-dismiss="alert">×</a><i class="icon icon-warning-sign"></i> ' . $this->session->userdata('success') . '</i></div>';
            $this->session->unset_userdata('success');
        endif;
        if ($this->session->userdata('failed')):
            echo '<div class="alert alert-dismissable alert-danger"><a class="close" data-dismiss="alert">×</a><i class="icon icon-warning-sign"></i> ' . $this->session->userdata('failed') . '</i></div>';
            $this->session->unset_userdata('failed');
        endif;
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">Receive Product</div>
                        <div class="panel-body">                        
                            <div class="panel">
                                <div class="panel-body">
                                    <form method="post" action="<?php echo site_url('receiveproduct/tempreceive'); ?>" class="form-horizontal" id="temsellform" style="border-bottom: 1px solid gray;margin-bottom: 10px">

                                        <div class="form-group col-lg-4">
                                            <label class="control-label col-lg-3" style="text-align: left">Product</label>
                                            <div class="col-lg-8" style="padding-left: 0px">
                                                <?php
                                                $comid = $this->session->userdata('company_id');
                                                $productlist = $this->db->query("select * from issuelist group by product_name")->result();
                                                ?>
                                                <select class="form-control selectpicker" data-live-search="true" name="productid" id="productid" onchange="saveproduct(this.value)" required="" tabindex="1">
                                                    <option value="">Select Product</option>
                                                    <?php
                                                    if (sizeof($productlist) > 0):
                                                        foreach ($productlist as $allpro):
                                                            ?>
                                                            <option <?php echo ($allpro->id == $pid) ? 'selected' : '' ?> value="<?php echo $allpro->id . ':' . $allpro->product_name;?>"><?php echo $allpro->product_name ;?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>                                                    
                                                </select>                                        
                                            </div>
                                        </div>


                                        <div class="form-group col-lg-4">
                                            <label class="control-label col-lg-3">Quantity</label>
                                            <div class="col-lg-4">
                                                <input type="text" class="form-control" name="freeqty" id="freeqty" required="" tabindex="3"/>
                                            </div>
                                            <div class="col-lg-4">
                                                <input type="text" class="form-control" id="unitdata" name="unit" readonly=""/>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-2" style="float: right">
                                            <label class="control-label col-lg-1"></label>
                                            <div class="col-lg-10">
                                                <input type="hidden" name="randsellid" value="<?php echo $randsellid; ?>"/>
                                                <input type="submit" class="form-control btn btn-primary" value="Add" onclick="return checkproduct()" tabindex="4"/>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-lg-2">
                                            <label class="control-label col-lg-3">Price</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="price" id="price" required="" tabindex="2"/>
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                    </form>

                                    <form method="post" action="<?php echo site_url('receiveproduct/savereceive'); ?>" class="form-horizontal" style="border-radius: 5px">


                                        <div class="form-group col-lg-4">
                                            <label class="control-label col-lg-3">Supplier</label>
                                            <div class="col-lg-8">                                                                                             
                                               
                                                <?php
                                                $comid = $this->session->userdata('company_id');
                                                $getcustomer = $this->db->query("select id,ledgername from accountledger where accountgroupid = '30' AND company_id = '$comid'")->result();
                                                ?>
                                                <select autofocus="" class="form-control selectpicker" data-live-search="true" name="cname" id="cname" required="" onchange="getuserdetails(this.value)"> 
                                                    <option value="">Select Supplier</option>
                                                    <?php
                                                    if (sizeof($getcustomer) > 0):
                                                        foreach ($getcustomer as $customer):
                                                            ?>
                                                            <option <?php echo ($customer->id == $cname) ? 'selected' : '' ?> value="<?php echo $customer->id; ?>"><?php echo $customer->ledgername; ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>                                                    

                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-3" style="float: right">
                                            <label class="control-label col-lg-4">Date</label>
                                            <div class="col-lg-8">                                                                                              
                                                <input class="form-control" readonly="" type="text" name="date" value="<?php echo date("Y-m-d"); ?>" id="sdate"/>
                                            </div>
                                        </div>

                                        <input type="hidden" name="randsellid" value="<?php echo $randsellid; ?>"/>
                                        <div class="clearfix"></div>

                                        <div class="panel-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Serial#</th>
                                                        <th>Product Name</th>                                                        
                                                        <th>Quantity</th>
                                                        <th>Unit</th>                                                       
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $demototal = 0;
                                                    $sn = 1;                                                   
                                                    $selldata = $this->db->get_where('tempsell', array('randsellid' => $randsellid))->result();
                                                    if (sizeof($selldata) > 0):
                                                        foreach ($selldata as $fsell):
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $sn++; ?></td>
                                                                <td><?php echo $fsell->pname; ?></td>                                                                
                                                                <td><?php echo $fsell->qty; ?></td>
                                                                <td><?php echo $fsell->unit; ?></td>                                                               
                                                                <td><a href="<?php echo site_url('receiveproduct/remove_receive/' . $fsell->id); ?>"><i class="fa fa-trash-o"></i></a></td>
                                                            </tr>
                                                        <input type="hidden" name="pricelist[]" value="<?php echo $fsell->sellprice; ?>"/>
                                                        <input type="hidden" name="qty[]" value="<?php echo $fsell->qty; ?>"/>
                                                        <input type="hidden" name="idlist[]" value="<?php echo $fsell->pid; ?>"/>
                                                        <input type="hidden" name="unit[]" value="<?php echo $fsell->unit; ?>"/>
                                                        <input type="hidden" name="pname[]" value="<?php echo $fsell->pname; ?>"/>
                                                        <?php
                                                        $demototal = $demototal + $fsell->sellprice * $fsell->qty;
                                                    endforeach;
                                                endif;
                                                ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" style="text-align: right"><b>Comment/Note</b></td>
                                                        <td colspan="2">
                                                            <textarea class="form-control" name="comment" placeholder="Comment"></textarea>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>  
                                        </div>                                  
                                       
                                        <div class="clearfix"></div>


                                        <div class="panel-heading col-lg-6" style="margin-top: 20px;float: right">                                          
                                            <div class="col-lg-12" style="float: right">         
                                                <input type="hidden" name="mobile" id="mobile"/>
                                                <input type="hidden" name="name" id="name"/>
                                                <input type="hidden" name="address" id="address"/>
                                                <button type="submit" class="btn btn-primary" name="stype" value="onlysave">Submit</button>&nbsp;&nbsp;                                                                                               
                                            </div>                                            
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="clearfix"></div>


                                    </form>
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

<?php include 'footer.php'; ?>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
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
        var demototal = $("#totalprice").val();
        var vat = $("#vat").val();
        var discount = $("#discount").val();
        var dcharge = $("#delivery_charge").val();

        var wvat = vat * demototal / 100;
        $("#totaldue").val(parseFloat(demototal) + parseFloat(wvat) - parseFloat(discount) + parseFloat(dcharge));
    }

    function saveproduct(product_id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'productid=' + product_id + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("receiveproduct/getpdetails") ?>',
            data: datastring,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#price").val(jsonObject.unit_price);
                $("#freeqty").val(jsonObject.quantity);
                $("#unitdata").val(jsonObject.unit);
            }
        });
    }

    function refresh() {
        window.location.reload();
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
                success: function (response) {
                    var dataob = JSON.parse(response);
                    $("#mobile").val(dataob.mobile);
                    $("#name").val(dataob.name);
                    $("#address").val(dataob.address);
                }
            });
        }
    }


</script>