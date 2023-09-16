<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->   
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">

                    <div class="panel-heading">Stock details</div>
                    <div class="panel-body">
                        <form class="cmxform form-horizontal tasi-form" method="post" action="">

                            <div class="col-lg-2">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date From</label>
                                    <input class="form-control" type="text" name="sdate" value="<?php echo $sdate; ?>" id="sdate" required />                               
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date To</label>
                                    <input class="form-control" type="text" name="edate" value="<?php echo $edate; ?>" id="edate" required />
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Product Group</label>
                                    <?php
                                    $getbuyer = $this->db->query("select * from productgroup")->result();
                                    ?>
                                    <select class="form-control" data-live-search="true" name="productGroup" id="productGroup" onchange="selecProduct(this.value)" required>
                                        <option value="">---Select---</option>   
                                        <?php
                                        if (sizeof($getbuyer) > 0):
                                            
                                            foreach ($getbuyer as $buyer):
                                                ?>
                                                <option <?php if(isset($productGroup) && $buyer->id == $productGroup) echo 'selected'; else echo ''; ?> value="<?php echo $buyer->id; ?>"><?php echo $buyer->name; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Product</label>
                                    <?php
                                    if(isset($productGroup))
                                         $getbuyer = $this->db->query("select id,pname from products where product_group_id='$productGroup'")->result();
                                    else
                                    $getbuyer = $this->db->query("select id,pname from products")->result();
                                    ?>
                                    <select class="form-control selectpicker" data-live-search="true" name="product" id="selectproduct" required>
                                        <option value="">---Select---</option>   
                                        <?php
                                        if (sizeof($getbuyer) > 0):
                                            
                                            foreach ($getbuyer as $buyer):
                                                ?>
                                                <option <?php if(isset($product) && $buyer->id == $product) echo 'selected'; else echo ''; ?> value="<?php echo $buyer->id; ?>"><?php echo $buyer->pname; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">                           
                                <div class="form-group" style="margin-right: 0px">     
                                    <label class="control-label"><br/></label>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input style="margin-top: 25px;margin-left: 5px" class="btn btn-primary" type="submit" name="search" value="Submit"/>
                                </div>
                            </div>

                        </form>

 <!-- adsfgsdgfaffd -->
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Daily Sales details
                    </header>
                        
                        <div class="panel-body">
                        <table class="display table table-bordered table-striped" id="stockTable">
                            
                            <thead>
                                <tr>   
                                    <th>S.N</th>  
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Supplier/P.F.</th>
                                    <th>Received Qty</th>
                                    <th>Customer/P.F.</th>
                                    <th>Sell/Issue Qty</th>
                                    <th>Quantity(<?php echo $unit?>)</th> 
                                    <th>Total Quantity(<?php echo $unit?>)</th>          
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td style="text-align: right;"colspan="7">Oprning Quantity</td>
                                    <td><?php echo number_format($openingQuantity, 2); ?></td>
                                    <td><b><?php echo number_format($openingQuantity, 2); ?></b></td>
                                </tr>
                                <?php
                                $i = 1;
                                $totaquantity = $openingQuantity;
                                $sellQty=0;
                                $receivedQty =0;

                                if (sizeof($items) > 0):
                                    ?>
                                
                                <?php
                                    foreach ($items as $item):

                                        // echo"<pre>";
                                        // print_r($item);
                                        // echo "</pre>";

                                        if($item->stype=="sell"||$item->stype=="issue")
                                            $totaquantity -= $item->quantity;
                                        
                                        else
                                            $totaquantity += $item->quantity;
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $item->date; ?></td>
                                            <td><?php if($item->stype=="")echo "purchase";else echo $item->stype; ?></td>
                                            <td><?php if($item->stype!="sell"&&$item->stype!="issue") echo $this->db->query("select ledgername from accountledger where id='$item->buyername'")->row()->ledgername; ?></td>
                                            <td>
                                                <?php if($item->stype!="sell"&&$item->stype!="issue") {echo $item->quantity; $receivedQty+=$item->quantity;}?>
                                            </td>
                                            <td><?php if($item->stype=="sell"||$item->stype=="issue"): 

                                                $ledgerid = $item->buyername;
                                                $ledgerid2=intval($ledgerid);
                                                if(strpos($ledgerid,'L')!==false || strpos($ledgerid,'S')===false)
                                                    $rname =  $this->db->get_where('accountledger', array('id' => $ledgerid2))->row()->ledgername;
                                                else
                                                    $rname =  $this->db->get_where('staff', array('id' => $ledgerid2))->row()->title;
                                                echo $rname;

                                                endif;

                                             ?></td> 
                                            <td>
                                                <?php if($item->stype=="sell"||$item->stype=="issue") {echo $item->quantity; $sellQty+=$item->quantity;}?>
                                            </td>  
                                            <td><?php echo $item->quantity; ?></td>
                                            <td><?php echo number_format($totaquantity, 2); ?></td>
                                        </tr>
                                        <?php
                                        
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan=""></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo '= ' . number_format($receivedQty, 2); ?></td>
                                    <td></td>
                                    <td><?php echo '= ' . number_format($sellQty, 2); ?></td>
                                    <td></td>
                                    <td><b><?php echo '= ' . number_format($totaquantity, 2); ?>(<?php echo $unit?>)</b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- page end-->

    </section>
</section>

<?php include 'footer.php'; ?>

<script type="text/javascript">
     function selecProduct(productGroupId){
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'productGroupId=' + productGroupId + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("reports/getProductList") ?>',
            data: datastring,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#selectproduct").find('option').remove().end();
                
                $("#selectproduct").append($('<option>', {
                        value: '' ,
                        text: '---Select---'
                }));

                $.each( jsonObject, function( r,v) {
                    
                    $("#selectproduct").append($('<option>', {
                        value: v.id,
                        text: v.pname
                    }));
                });
                 $('.selectpicker').selectpicker('refresh');
                // $("#price").val(jsonObject.sellprice);
                // $("#freeqty").val(jsonObject.available_quantity);
                // $("#unitdata").val(jsonObject.unit);
                // $("#typeqty").attr("Max",jsonObject.available_quantity);
            }
        });
    }
</script>
