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
                            <form class="tasi-form" method="post" action="<?php echo site_url('trialbalance/viewtrialbalance'); ?>">
                                <div class="form-group">
                                    <div class="col-md-5" style="padding-left: 0">
                                        <div class="input-group input-sm" >
                                            <span class="input-group-addon">From </span>
                                            <div class="iconic-input right">
                                                <i class="fa fa-calendar"></i>
                                                <input autocomplete="off" type="text" id="sdate" class="form-control" name="sdate" value="<?php echo $sdate; ?>">
                                            </div>
                                            <span class="input-group-addon">To</span>
                                            <div class="iconic-input right">
                                                <i class="fa fa-calendar"></i>
                                                <input autocomplete="off" type="text" id="edate" class="form-control" name="edate" value="<?php echo $edate; ?>">
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
                        Trial Balance <span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
                    </header>
                    <div class="panel-body">
                        <div id="invoicediv">
                            <table class="display table table-bordered">
                                <tr>
                                    <th>Sl No</th>
                                    <th>Account Ledger</th>                                        
                                    <th>Debit</th>
                                    <th>Credit</th>
                                </tr>
                                <?php
                                $lageralldebit = 0;
                                $lagerallcredit = 0;

                                if (sizeof($ledgerdata) > 0):
                                    $sl = 0;
                                    foreach ($ledgerdata as $datarow):
                                        $sl++;
                                        if ($datarow->ledgerid > 0):
                                            ?>
                                            <tr>
                                                <td><?php echo ($sl); ?></td>
                                                <td><?php
                                                    $ledgerID = $datarow->ledgerid;
                                                    echo $this->db->get_where('accountledger', array('id' => $ledgerID))->row()->ledgername;
                                                    ?></td>                                                
                                                <?php if ($datarow->totaldebit > $datarow->totalcredit): ?>
                                                    <td><b><?php echo (number_format(($datarow->totaldebit - $datarow->totalcredit), 2)); ?></b></td>
                                                    <td> 0.00 </td>
                                                    <?php
                                                    $debitval = $datarow->totaldebit - $datarow->totalcredit;
                                                    $creditval = 0;
                                                else:
                                                    ?>
                                                    <td> 0.00 </td>
                                                    <td><b><?php echo (number_format(($datarow->totalcredit - $datarow->totaldebit), 2)); ?></b></td>
                                                    <?php
                                                    $debitval = 0;
                                                    $creditval = $datarow->totalcredit - $datarow->totaldebit;
                                                endif;
                                                $lageralldebit += $debitval;
                                                $lagerallcredit += $creditval;
                                                ?>
                                            </tr>
                                            <?php
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                                <tr>
                                    <td></td>                                       
                                    <td></td>
                                    <td style="font-size: 15px;"><b><?php echo (number_format($lageralldebit, 2)); ?></b></td>
                                    <td style="font-size: 15px;"><b><?php echo (number_format($lagerallcredit, 2)); ?></b></td>
                                </tr>
                                <tr>    
                                    <td></td> 
                                    <td></td>                                     
                                    <td colspan="2"><b>Total : <?php
                                        if ($lageralldebit > $lagerallcredit):
                                            echo (number_format(($lageralldebit - $lagerallcredit), 2)) . " Dr";
                                        elseif ($lagerallcredit > $lageralldebit):
                                            echo (number_format(($lagerallcredit - $lageralldebit), 2)) . " Cr";
                                        else:
                                            echo (number_format(($lagerallcredit - $lageralldebit), 2));
                                        endif;
                                        ?></b>
                                    </td>                                       
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
        docprint.document.write('<html><title>Trial Balance</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td { border: 1px solid gray; text-align:center;font-size:11px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write('<p><span style="margin-top:50px; margin-left:-400px"><img height="50" weidth="150" src="<?php echo $baseurl."assets/img/logo.png"; ?>"/></span></p><p style="margin-top:-60px; margin-left:80px; color: red"><b><u>' + comname + '</u></b></p>');
        docprint.document.write('<p style="margin-top:-15px; margin-left:80px">' + comaddress + '</p>');
        docprint.document.write('<p style="margin-top:-15px; margin-left:80px">E-mail: ' + comemail + '<hr style="width:700px; margin: -12px 0 -12px 0">');
        docprint.document.write('<p style="margin-left:80px">List of trial balance</p>');
        docprint.document.write('<p style="margin:-10px 0 10px 82px">Statement (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }
</script>