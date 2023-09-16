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

                    <div class="panel-heading">Stock details<button onclick="Clickheretoprint()" style="float: right;" class="btn btn-primary btn-md"><i class="fa fa-print"> Print </i>  </button></div>
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

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Product</label>
                                    <?php
                                    $getbuyer = $this->db->query("select id,product_name from products where category_id='$category_id' and status=1")->result();
                                    ?>
                                    <select class="form-control selectpicker" data-live-search="true" name="product" id="selectproduct" required>
                                        <option value="">---Select---</option>   
                                        <?php
                                        if (sizeof($getbuyer) > 0):
                                            
                                            foreach ($getbuyer as $buyer):
                                                ?>
                                                <option <?php if(isset($product) && $buyer->id == $product) echo 'selected'; else echo ''; ?> value="<?php echo $buyer->id; ?>"><?php echo $buyer->product_name; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Product ID</label>
                                    <input class="form-control" type="text" name="productid">
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
                        
                        <div class="panel-body">
                        <table class="display table table-bordered table-striped" id="stockTable">
                            
                            <thead>
                                <tr>   
                                    <th>S.N</th>  
                                    <th>Date</th>
                                    <th>Voucher Id</th>
                                    <th>Voucher Type</th>
                                    <th>Supplier/Customer</th>
                                    <th class="t-r">Quantity(<?php echo $unit?>)</th> 
                                    <th class="t-r">U. Price</th> 
                                    <th class="t-r">T. Quantity(<?php echo $unit?>)</th>          
                                </tr>
                            </thead>

                            <tbody id="invoicediv">
                                <tr>
                                    <td style="text-align: right;"colspan="7"><b>Opening:</b></td>
                                    
                                    <td class="t-r">
                                        <b><?php if($category_id==6)    
                                            echo 'Full='.number_format($openingQuantity, 2).' ('.$unit.') Empty='. number_format($openingECQuantity, 2); else echo number_format($openingQuantity, 2);?>(<?php echo $unit;?>)
                                        </b>
                                    </td>
                                </tr>
                                <?php
                                $i = 1;
                                $totaquantity = $openingQuantity;
                                $etotaquantity = $openingECQuantity;

                                $sellQty=0;
                                $receivedQty =0;

                                if (sizeof($items) > 0):
                                    ?>
                                
                                <?php
                                    foreach ($items as $item):

                                        if($item->type=="Sale"||$item->type=="Purchase Return")
                                        { 
                                            if($item->full_package==2)
                                            {
                                                $etotaquantity -= $item->quantity;
                                            }
                                            elseif ($item->full_package==0) {
                                                $totaquantity -= $item->quantity;
                                                $etotaquantity += $item->quantity;
                                            }
                                            else
                                            $totaquantity -= $item->quantity;
                                        }
                                        
                                        else{

                                            if($item->full_package==2)
                                            {
                                                $etotaquantity += $item->quantity;
                                            }
                                            elseif ($item->full_package==0) {
                                                $totaquantity += $item->quantity;
                                                $etotaquantity -= $item->quantity;
                                            }
                                            else
                                            $totaquantity += $item->quantity;
                                        }
                                        
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $item->date; ?></td>
                                            <td><?php if($item->type=="Sale"): ?>
                                                <a target="_blank" href="<?php echo site_url('reports/printinvoiceagain/' . $item->invoice_id); ?>" title="View details of sell"><?php echo "Inv-". sprintf("%06d", $item->voucherid);?></a>

                                                <?php elseif($item->type=="Purchase"):?>
                                                <a target="_blank" href="<?php echo site_url('reports/detailspurchase/' . $item->invoice_id); ?>" title="Edit Purchase"><?php echo "Pur-". sprintf("%06d", $item->voucherid);?></a>
                                               

                                                <?php elseif($item->type=="Sale Return"):?>
                                                <a target="_blank" href="<?php echo site_url('reports/showsellreturn/' . $item->invoice_id); ?>" title="Edit Purchase"><?php echo "Sr-". sprintf("%06d", $item->voucherid);?></a>
                                                

                                                <?php elseif($item->type=="Purchase Return"):?>
                                                <a target="_blank" href="<?php echo site_url('reports/showpurchasereturn/' . $item->invoice_id); ?>" title="Edit Purchase"><?php echo "Pr-". sprintf("%06d", $item->voucherid);?></a>

                                                <?php endif;?>


                                                </td>
                                                <td><?php echo $item->type;echo (($item->full_package==1)? "(Package)" :(($item->full_package==2)?"(Empty)":(($item->full_package==0)?"(Refill)":"")));?> </td>

                                            <td><?php echo $this->db->query("select ledgername from accountledger where id='$item->buyername'")->row()->ledgername; ?></td>
                                            <td class="t-r"><?php echo number_format($item->quantity,2); ?></td>
                                            <td class="t-r"><?php echo number_format($item->price,2); ?></td>
                                            <td class="t-r">
                                                
                                                <?php if($category_id==6)    
                                                echo 'Full='.number_format($totaquantity, 2).' ('.$unit.') Empty='. number_format($etotaquantity, 2); else echo number_format($totaquantity, 2);?>(<?php echo $unit;?>)
                                            </td>
                                        </tr>
                                        <?php
                                        
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan=""></td>
                                    
                                    
                                    <td class="t-r" colspan="6"><b>Closing Stock:</b></td>
                                    <td class ="t-r"><b><?php if($category_id==6)    
                                                        echo 'Full='.number_format($totaquantity, 2).' ('.$unit.') Empty='. number_format($etotaquantity, 2); else echo number_format($totaquantity, 2);?>(<?php echo $unit;?>)</b></td>
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

<?php include __DIR__ .'/../footer.php'; ?>

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
               
            }
        });
    }

function Clickheretoprint()
    {
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';  
        var productname= $("#selectproduct option:selected").text();      
        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val();        
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Product Details</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write(comaddress);
        
        docprint.document.write('</p><p style="margin:-10px 0 10px 0px;text-align:center;">'+productname+' (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write('<table  border="1">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</table></center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }
    
</script>
