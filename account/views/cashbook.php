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
                    <div class="panel-body">
                        <div class="clearfix">
                            <form class="tasi-form" method="post" action="<?php echo site_url('cashbook/cashbookdetails'); ?>">
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
                                    <div class="col-md-1"><button type="submit" class="btn btn-primary">Submit</button></div>                                        
                                </div>                                         
                            </form>                       
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Cash Book
                        <a href="<?php echo site_url('cashbook/exports_data/'.$sdate.'/'.$edate); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a>   
                        
                        <button onclick="Clickheretoprint()" style="float: right;" class="btn btn-primary btn-md"><i class="fa fa-print"> Print </i>  </button>
                    </header>
                    <div class="panel-body">
                        <?php
                        $lageralldebit = $opdebit;
                        $lagerallcredit = $opcredit;
                        ?>
                        <div id="invoicediv">
                            <table class="display table table-bordered">
                                <tr>
                                    <th>Date</th>
                                    <th>Voucher No</th>
                                    <th>Voucher Type</th>
                                    <th>Ledger Name</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo "Opening Balance"; ?></td>
                                    <td><?php echo ($opdebit);?></td>
                                    <td><?php echo ($opcredit);?></td>
                                </tr>

                                <?php
                                if (sizeof($ledgerdata) > 0):
                                    $sl = 0;

                                    foreach ($ledgerdata as $datarow):
                                        $sl++;
                                        $lageralldebit += $datarow->debit;
                                        $lagerallcredit += $datarow->credit;
                                        ?>
                                        <tr>                                               
                                            <td><?php
                                                echo (date('Y-m-d', strtotime($datarow->date)));
                                                ?></td>
                                            <td><?php echo ($datarow->voucherid); ?></td>
                                            <td><?php echo $datarow->vouchertype; ?></td>
                                            <td><?php //echo $this->db->get_where('accountledger', array('id' => $datarow->ledgerid))->row()->ledgername; 

                                            echo $this->db->query("select ledgername from accountledger where id=(select ledgerid from ledgerposting where voucherid='".$datarow->voucherid."' and vouchertype='".$datarow->vouchertype."' and id<>'".$datarow->id."' limit 1)")->row()->ledgername;
                                            ?></td>
                                            <td><?php echo $datarow->description; ?></td>
                                            <td><?php echo ($datarow->debit); ?></td>
                                            <td><?php echo ($datarow->credit); ?></td>    
                                        </tr>
                                        <?php
                                    endforeach;
                                endif;
                                ?> 
                                <tr>                                    
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style=" text-align: right;"><b>Total</b></td>
                                    <td><b><?php echo '<b>' . (number_format($lageralldebit, 2)) . '</b>'; ?></b></td>
                                    <td><b><?php echo '<b>' . (number_format($lagerallcredit, 2)) . '</b>'; ?></b></td>
                                </tr> 
                                
                                <tr>                                    
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style=" text-align: right;"><b>Closing Balance</b></td>
                                    <td colspan="3" style=" text-align: center;"><b><?php if($lageralldebit+$opdebit-$lagerallcredit-$opcredit>=0) echo '<b>' . (number_format($lageralldebit+-$lagerallcredit, 2)) . ' Dr</b>';else echo '<b>' . (number_format($lagerallcredit-$lageralldebit, 2)) . ' Cr</b>'; ?></b></td>
                                    
                                </tr>
                            </table>                           

                        </div>
                    </div> 
                </section>
            </div>          
        </div>


        <!-- page end-->
    </section>
</section>

<?php include 'footer.php'; ?>
<script type="text/javascript">
    function Clickheretoprint()
    {
        var comname = "<?php echo $this->session->userdata('company_name'); ?>";
        var comaddress = "<?php echo $this->session->userdata('company_address'); ?>";
        var comemail = "<?php echo $this->session->userdata('email'); ?>";       
        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val();        
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Cash Book</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:98%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td { border: 1px solid gray; text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write('<h1 style=";margin-left:80px;"><b>' + comname + '</b></h1>');
        docprint.document.write('<h3 style="margin-left:80px">' + comaddress + '</h3>');
        docprint.document.write('<h3 style="margin-left:80px">E-mail: ' + comemail + '</h3><hr style="width:700px; margin: -12px 0 -12px 0">');
        docprint.document.write('<h2 style="margin-left:10px"><u>Cash book history</u></h2>');
        docprint.document.write('<h3 style="margin:-10px 0 10px 10px">Statement (' + from_date + ' to ' + to_date + ')</h3>');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }
   
</script>