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
                    <div class="panel-body">
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('Purchase/viewPurchseDetails'); ?>">

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date From</label>
                                    <input class="form-control" type="text" name="sdate" value="<?php echo $sdate; ?>" id="sdate"/>                               
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date To</label>
                                    <input class="form-control" type="text" name="edate" value="<?php echo $edate; ?>" id="edate"/>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Supplier</label>
                                    
                                    <select class="form-control selectpicker" data-live-search="true" name="customer" id="customer">
                                        <option value="all">ALL</option>   
                                        <?php
                                        if (sizeof($customerlist) > 0):
                                            
                                            foreach ($customerlist as $buyer): 
                                                ?>
                                                <option <?php echo ($buyer->id == $cname) ? 'selected' : ''; ?> value="<?php echo $buyer->id; ?>"><?php echo substr($buyer->ledgername." (".$buyer->mobile.")"." (".$buyer->address.", ".$buyer->district_name.")",0,80); ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">                           
                                <div class="form-group" style="margin-right: 0px">     
                                    <label class="control-label"><br/></label>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input style="margin-top: 25px;margin-left: 20px" class="btn btn-primary" type="submit" value="Submit"/>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Purchase details 
                        <a href="<?php echo site_url('purchase/export_purchasedetails/'.$sdate.'/'.$edate.'/'.$cname); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a> 
                        
                        <span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
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

                        <table class="display table table-bordered table-striped dataTable" id="suppliertableid">
                            <thead>
                                <tr>                                    
                                    <th>Date</th>
                                    <th>Invoice ID</th>
                                    <th>Product Name</th>
                                    <th>Supplier Name</th>                             
                                    <th>Quantity</th> 
                                    <th>Unit Price</th>                             
                                    <th>Total Price</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody id="invoicediv">
                                <?php
                                $i = 1;
                                $totalsell = 0;
                                $totalreceived = 0;
                                $totalpay = 0;
                                $totaldue = 0;
                                $totalbill = 0;
                                $discount = 0;
                                $deliverycharge = 0;
                                $vat = 0;
                                $changed = 0;
                                $invoice = 0;
                                $netprice = 0;
                                $quantity = 0;
                                $totalqty = 0;

                                if (sizeof($selldata) > 0):
                                    foreach ($selldata as $sell):
                                        $netprice = $sell->quantity * $sell->buyprice;
                                        ?>
                                        <tr>
                                            <td><?php $date=date_create($sell->date);echo date_format($date,"d-m-Y H:i:s"); ?></td>
                                            <td><?php echo "Pur-". sprintf("%06d", $sell->sellid); ?></td> 
                                            <td><?php echo $sell->product_name;?>
                                            <td><?php
                                                echo $sell->ledgername." (".$sell->address.", ".$sell->district_name.")"; 
                                            ?></td>
                                            <td style="text-align: right;"><?php echo $sell->quantity . $sell->unit; ?></td>
                                            <td style="text-align: right;"><?php echo $sell->buyprice; ?></td>
                                            <td style="text-align: right;"><?php echo number_format($sell->buyprice*$sell->quantity, 2); ?></td>
                                            <!-- <td><a href="#" data-toggle="modal" data-target="#editsell<?php echo $sell->id ?>" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp; Edit</a>&nbsp; </td> -->
                                        </tr>
                                        <?php
                                        $invoice = $sell->invoiceid;
                                        $totalbill = $totalbill + $netprice;
                                        $totalqty += $sell->quantity;
                                        //$vat = $vat + ($netprice * $sell->vat) / 100;
                                        //$discount = $sell->discount;
                                        //$deliverycharge = $sell->delivery_charge;
                                    endforeach;
                                endif;
                                ?>

                            </tbody>
                            <tfoot id="hidetoprint">
                                <tr>                                     
                                    <th style="text-align: right;" colspan="4">Total:</th>
                                    <th style="text-align: right;">
                                        <?php echo number_format($totalqty, 2); ?>                                    
                                    </th>  
                                    <th style="text-align: right;">Total:</th> 
                                    <th style="text-align: right;"><?php echo number_format($totalbill, 2); ?>  </th>     
                                </tr>
                            </tfoot>
                        </table>                        
                    </div>
                </section>
            </div>          
        </div>

        <!-- page end-->
    </section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script>
    $(document).ready(function () {
        $('#suppliertableid').dataTable({
            aLengthMenu: [
                [ -1,500,200,50],
                ["All",500,200,50]
            ],
            iDisplayLength: -1
        });
        $("#foradvanced").hide();
    });
    function Clickheretoprint()
    {
        
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';
        
        var customername= $("#customer option:selected").text(); 
        var totalbill = parseFloat("<?php echo $totalbill; ?>");  
        totalbill = totalbill.toFixed(2);  
        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val(); 
        $("#hidetoprint").hide();       
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Puchase Details</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
   
        docprint.document.write(comaddress);
        
        docprint.document.write('<h2 style="margin:-10px 0 10px 0px;text-align:center;">Puchase Details</h2>');
        docprint.document.write('<p style="margin:-10px 0 10px 0px;text-align:center;">Supplier : '+customername+'</p>');
        docprint.document.write('<p style="margin:-10px 0 10px 0px;text-align:center;">Date:'
            +' (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write('<table border="1">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</table><p> Total: '+totalbill+'</p>');

        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
        $("#hidetoprint").show(); 
    }
</script>