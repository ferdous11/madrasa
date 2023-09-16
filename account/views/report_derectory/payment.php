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
                        <div class="clearfix">                
            				<form class="tasi-form" method="post" action="<?php echo site_url('reports/payment'); ?>">
                                <div class="form-group">

                                    <div class="col-md-5" style="padding-left: 0">
                                        <div class="input-group input-sm" >
                                            <span class="input-group-addon">From </span>
                                            <div class="iconic-input right">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" id="sdate" class="form-control" name="sdate" value="<?php echo $sdate; ?>">
                                            </div>
                                            <span class="input-group-addon">To</span>
                                            <div class="iconic-input right">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" id="edate" class="form-control" name="edate" value="<?php echo $edate; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1"><button class="btn btn-primary" type="submit" >Submit</button></div>                                        
                                </div>                                         
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                	<section class="panel">
                    <header class="panel-heading">
                            Payment Voucher
                            <a href="<?php echo site_url('reports/export_payment/'.$sdate.'/'.$edate); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a> 

                            <span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
                    </header>
                    <div >
                    <table class="display table table-bordered table-striped dataTable" id="paymenthistory">

                        <thead>
                            <tr>                                    
                                <th>S.N.</th>                                        
                                <th>Date</th>
                                <th>Ledger Name</th> 
                                <th>Father's Name</th> 
                                <th>Mobile No.</th> 
                                <th>Amount</th>                                        
                                <th>Comment</th> 
                                <th>Inserted By</th>                                        
                                <th class="hidetoprint">Action</th>
                            </tr>
                    	</thead>

                        <tbody id="invoicediv">
                            <?php
                            $i = 1;
                            $totalamount = 0;
                            if (sizeof($payments) > 0):
                                foreach ($payments as $payment):
                                    ?>
                                <tr>
                                    <td><?php echo "Pay-". sprintf("%06d", $payment->id) ; ?></td>                                                   
                                    <!-- <td><?php echo ($payment->invoiceid); ?></td> -->
                                    <td><?php echo ($payment->date); ?></td>
                                    <td>
                                        <?php 
                                            $party =$this->db->query("select a.ledgername,a.address,a.accountgroupid,a.father_name,a.mobile,d.name as district_name from accountledger as a left join districts as d on a.district=d.id where a.id='$payment->ledgerid'")->row();
                                            echo ($party->accountgroupid==16||$party->accountgroupid==15)? $party->ledgername." (".$party->address.", ".$party->district_name.")":$party->ledgername; ?>
                                    </td>
                                    <td><?php echo ($party->father_name); ?></td>  
                                    <td><?php echo ($party->mobile); ?></td>  
                                    <td style="text-align: right;!importent;"><?php echo number_format(($payment->amount),2); ?></td>                                                
                                    <td><?php echo ($payment->description); ?></td>                                                
                                    <td><?php echo ($payment->fullname); ?></td>                                                
                                    <td class="hidetoprint">
                                    <a style="" target="_blank" href="<?php echo site_url('reports/paymentshow/' . $payment->id); ?>" ><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;

                                    <?php 

                                    $ps = $this->db->query("select count(id) as rnumber from purchase_summary where invoiceid='$payment->invoiceid'")->row()->rnumber;
                                    $srs = $this->db->query("select count(id) as rnumber from sell_return_summary where invoiceid='$payment->invoiceid'")->row()->rnumber;

                                    $datef= date('Y-m-d'); if (($role=='admin'  ||  ($payment->date>$datef." 00:00:00" && $payment->date<$datef." 23:59:59"))&& !$ps&& !$srs):
                                    ?>

                                    <a style="margin-right: 50%" href="<?php echo site_url('payments/edit/' . $payment->id) ?>" title="Edit Payment"><i class="fa fa-edit"></i></a>  
                                    <?php endif;?>
                                      
                                    <!-- <a style="" href="<?php echo site_url('payment/deletereceiptvoucher/' . $payment->id); ?>" onclick="return confirm('Are you sure want to delete this Voucher !!')"><i class="fa fa-trash-o"></i></a>
                                     -->
                                            
                                    </td>
                                   
                                </tr>
                            <?php
                                $totalamount = $totalamount + $payment->amount;
                            endforeach;
                            ?>
                            <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align:right;"><b>Total:</b></td>
                            <td style="text-align:right;"><b><?php echo number_format( $totalamount,2);?></b></td>
                            <td></td>
                            <td></td>
                            <td class="hidetoprint"></td>
                            
                            </tr>
                            <?php 
                            endif;?>
                            
                        </tbody>

                    </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
    </section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script>
    $(document).ready(function () {
        $('#paymenthistory').dataTable({
            aLengthMenu: [
                [500, 1000, 2500, 5000, -1],
                [500, 1000, 2500, 5000, "All"]
            ],
            iDisplayLength: 500,
            "aaSorting": [[1, "desc"]]
        });
    });

    function Clickheretoprint()
    {
        
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';
              
        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val();        
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        $(".hidetoprint").hide();
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Payment Report</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        
        docprint.document.write(comaddress);

        docprint.document.write('</p><p style="margin:-10px 0 10px 0px;text-align:center;">Payment History (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write('<table border="1">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</table></center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
        $(".hidetoprint").show();
    }

</script>