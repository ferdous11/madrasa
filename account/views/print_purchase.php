<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->   
        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Print purchase invoice
                    </header>
                    <div class="panel-body">                        
                        <?php
                        $i = 1;
                        $totalpay = 0;
                        $othercost = 0;
                        $totalbill = 0;
                        $tax = 0;
                        $shippingcost = 0;
                        $changed = 0;
                        ?>
                        <div class="form" id="invoicediv">

                            <table class="display table table-bordered">
                                <tr>
                                    <td colspan="5">
                                        <p style="text-align: center;font-size: 12px">
                                            <strong style="font-size: 22px"><?php echo $supplierdata->ledgername; ?></strong><br/>
                                            <span style="text-align: center;font-size: 16px"><?php echo $supplierdata->address; ?><br/>
                                                Invoice #: <?php echo $invoiceid; ?><br/>
                                                Date: <?php echo date("Y-m-d"); ?></span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total Price ( Taka )</th>
                                </tr>
                                <?php
                                if (sizeof($purchasedata) > 0):
                                    foreach ($purchasedata as $invoice):
                                        $shippingcost = $invoice->shippingcost;
                                        $othercost = $invoice->othercost;
                                        $tax = $invoice->tax;
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php $temp=$this->db->get_where('products', array('id' => $invoice->product_id))->row(); echo $temp->pname; ?></td>
                                            <td><?php echo $invoice->buyprice; ?></td>
                                            <td><?php echo $invoice->quantity . '( ' . $this->db->get_where('product_unit', array('id' => $temp->unit))->row()->name .')'; ?></td>
                                            <td><?php echo number_format(($invoice->quantity * $invoice->buyprice), 2); ?></td>
                                        </tr>
                                        <?php
                                        $totalbill = $totalbill + $invoice->quantity * $invoice->buyprice;
                                    endforeach;
                                endif;
                                ?>

                                <tr>
                                    <td colspan="4" style="text-align: right">
                                        <b>Total Price</b>
                                    </td>
                                    <td><?php echo number_format($totalbill, 2); ?></td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="text-align: right">
                                        <b>Paid Amount</b>
                                    </td>
                                    <td><?php echo number_format($paid_amount, 2); ?></td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="text-align: right">
                                        <b>Previous Amount</b>
                                    </td>
                                    <td><?php echo number_format($supplier_payable, 2); ?></td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="text-align: right">
                                        <b>Total Payable Amount</b>
                                    </td>
                                    <td><?php echo number_format($totalbill - $paid_amount + $supplier_payable, 2); ?></td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="text-align: right">
                                        <b>Shipping cost</b>
                                    </td>
                                    <td><?php echo number_format($shippingcost, 2); ?></td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="text-align: right">
                                        <b>Other cost</b>
                                    </td>
                                    <td><?php echo number_format($othercost, 2); ?></td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="text-align: right">
                                        <b>Total Tax</b>
                                    </td>
                                    <td><?php echo number_format(($totalbill * $tax / 100), 2); ?></td>
                                </tr>


                                <tr>
                                    <td colspan="5" style="text-align: center;">
                                    <?php
                                        $taxtotal = $totalbill * $tax / 100;
                                        $gtotal = ($totalbill + $taxtotal + $shippingcost + $othercost);
                                        echo '<h4>Gross total :    ' . number_format($gtotal, 2) . '</h4>';
                                        ?>
                                        
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: center;">
                                        
                                        <?php
                                        echo '<h4>Gross total(In Words):    ' . $controller->convert_number(abs($gtotal)) . '</h4>';
                                        ?>
                                    </td>
                                    
                                </tr> 

                                <tr  >
                                    <td colspan="3">
                                        <h5 style="text-align: center;height: 60px">Authorised Signature</h5>
                                    </td>
                                    <td colspan="2" >
                                        <h5 style="text-align: center;height: 60px">Supplier Signature</h5>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="5"><h4 style="text-align: center">Thank you for your business</h4></td>
                                </tr>
                            </table>

                        </div>
                        <br/>
                        <span style="float: right;margin-left: 20px"><button class="btn btn-primary" value="Print" onclick="Clickheretoprint()">Print</button></span>&nbsp;&nbsp;
                        &nbsp;<span style="float: right;margin-left: 15px"><a href="<?php echo site_url('reports/purchasehistory'); ?>"><button class="btn btn-primary">Back To Home</button></a></span>
                    </div>
                </section>
            </div>          
        </div>        
        <!-- page end-->
    </section>
</section>
<?php include  __DIR__ .'footer.php'; ?>
<script type="text/javascript">

    function Clickheretoprint()
    {
       
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';
         

        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val();           
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Purchase</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td { border: 1px solid gray; text-align:center;font-size:11px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write('<p><span style="margin-top:50px; margin-left:-400px"><img height="50" weidth="150" src="<?php echo $baseurl."assets/img/logo.png"; ?>"/></span></p>');
        docprint.document.write('<p style="margin-top:-15px; margin-left:80px">' + comaddress + '</p>');
        
        docprint.document.write('<hr>');
        docprint.document.write('<h2 style="margin-left:80px">Purchase history</h2>');
        // docprint.document.write('<p style="margin:-10px 0 10px 82px">Statement (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write(oTable.innerHTML);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }
</script>

