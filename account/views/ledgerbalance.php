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
                            <form class="tasi-form" method="get" action="<?php echo site_url('ledgerbalance/viewledgerbalance'); ?>">
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

                                    <div class="col-md-3">
                                        <select class="form-control selectpicker" data-live-search="true" name="ledgername" required="">
                                            <option value="">Select Ledger</option>
                                            <?php
                                            if (sizeof($ledgername) > 0):
                                                foreach ($ledgername as $ledger):
                                                    $led= ($ledger->accountgroupid==15||$ledger->accountgroupid==16||$ledger->accountgroupid==18)?$ledger->groupname."-".$ledger->ledgername."(".$ledger->mobile.")"."(".$ledger->address.",".$ledger->district_name.")":$ledger->groupname."-".$ledger->ledgername;
                                                    ?>
                                                    <option <?php echo ($ledger->id == $ledgerid) ? 'selected' : ''; ?> value="<?php echo $ledger->id; ?>"><?php echo substr($led,0,75); ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
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
                        Ledger Balance
                        <a href="<?php echo site_url('Ledgerbalance/exports_data/'.$ledgerid.'/'.$sdate.'/'.$edate); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a> 

                        <button onclick="Clickheretoprint()" style="float: right;" class="btn btn-primary btn-md"><i class="fa fa-print"> Print </i>  </button>
                    </header>
                    <div class="panel-body">
                        <?php
                        $openingbal = $debit - $credit;
                        $openingdebit = 0;
                        $openingcredit = 0;
                        ?>
                        <div id="invoicediv">                          
                            <table class="display table table-bordered" border="1" id="example1">

                                <tr>
                                    <th>Date</th>
                                    <th>Voucher No</th>
                                    <th>Voucher Type</th>
                                    <th>Details</th>
                                    <th>Comment</th>
                                    <th style="text-align: center;">Debit</th>
                                    <th style="text-align: center;">Credit</th>
                                    <th style="text-align: center;">Balance</th>
                                </tr>

                                <?php
                                if ($ledgerid != ""):if(sizeof($closingLedgerData) == 0):
                                    ?>
                                    <tr>
                                        
                                        <td colspan='7'><?php echo "Opening Balance"; ?></td>
                                        <?php
                                        if (substr($openingbal, 0, 1) == "-"):
                                            echo "<td  style='text-align: right; font-weight:bold'>(" . substr(($openingbal), 1). ")</td>";
                                            $openingcredit = substr($openingbal, 1);
                                        elseif ($openingbal == 0):
                                            echo '<td  style="text-align: right"> 0 </td>';
                                        else:
                                            echo '<td  style="text-align: right; font-weight:bold">' .number_format($openingbal) . "</b></td>";
                                            $openingdebit = $openingbal;
                                        endif;
                                        ?>    
                                    </tr>

                                <?php else: 
                               
                                            foreach ($closingLedgerData as $preLadger ) {
                                                $openingdebit+= $preLadger->debit;
                                                $openingcredit+=$preLadger->credit;
                                            }
                                            $openingcredit+=$credit;
                                            $openingdebit+=$debit;

                                            $openingbal = $openingdebit - $openingcredit;
                                            $openingcredit = 0;
                                            $openingdebit = 0;

                                ?>

                                    <tr>
                                        <td></td>    
                                        <td></td>    
                                        <td></td>    
                                        <td></td>    
                                        <td></td>    
                                        <td></td>    
                                        <td style='text-align: right;'></td>
                                        <?php
                                        if ($openingbal<0):
                                            echo "<td style='text-align: right;'>(" .number_format($openingbal*-1). ")</td>";
                                            $openingcredit = ($openingbal*-1);
                                        elseif ($openingbal == 0):
                                            echo '<td style="text-align: right"> 0.00 </td>';
                                        else:
                                            echo "<td style='text-align: right;'>" . number_format($openingbal) . "</b></td>";
                                            $openingdebit = $openingbal;
                                        endif;
                                        ?>    
                                    </tr>

                                    <?php
                                    endif;
                                endif;

                                $aDebit[] = 0;
                                $aCredit[] = 0;
 
                                $currbalance = $openingdebit - $openingcredit;

                                if (sizeof($ledgerdata) > 0):
                                    foreach ($ledgerdata as $datarow):
                                    
                                        $aDebit[] = $datarow->debit;
                                        $aCredit[] = $datarow->credit;
                                        $currbalance+= ($datarow->debit - $datarow->credit);

                                        ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo (date('Y-m-d', strtotime($datarow->date)));
                                                    ?>
                                                </td>
                                                <td><?php echo ($datarow->voucherid); ?></td>
                                                <td><?php echo $datarow->vouchertype; ?></td>
                                                <td><?php 
                                                    if(strpos($datarow->description, "Inv-") !== false):
                                                      ?>  
                                                    <a target="_blank" href="<?php echo site_url('reports/printinvoiceagain/' . $datarow->voucherid); ?>" title="View details of sell"><?php echo $datarow->description ; ?></a>
                                                    <?php
                                                    elseif(strpos($datarow->description, "Pur-") !== false):
                                                    ?> 
                                                        <a target="_blank" href="<?php echo site_url('reports/detailspurchase/' . $datarow->voucherid); ?>"><?php echo $datarow->description; ?></a>
                                                        <?php
                                                    elseif(strpos($datarow->description, "Inv-") === false && $datarow->vouchertype=="Received voucher"):
                                                    ?> 
                                                        <a target="_blank" href="<?php echo site_url('reports/receivedshow/' . $datarow->voucherid); ?>"><?php echo "Rec-". sprintf("%06d", $datarow->voucherid); ?></a>
                                                        <?php
                                                    elseif(strpos($datarow->description, "Pur-") === false && $datarow->vouchertype=="Payment voucher"):
                                                        ?> 
                                                        <a target="_blank" href="<?php echo site_url('reports/paymentshow/' . $datarow->voucherid); ?>"><?php echo "Pay-". sprintf("%06d", $datarow->voucherid); ?></a>
                                                    <?php
                                                    else:
                                                        echo $datarow->description; 
                                                    endif;
                                                    ?>
                                                </td>

                                                <td><?php 
                                                    if($datarow->vouchertype=="sales"):
                                                        echo $this->db->query("select comment from daily_sell_summary where voucherid ='$datarow->voucherid'")->row()->comment;
                                                
                                                    elseif($datarow->vouchertype=="purchase"):
                                                        echo $this->db->query("select Description from purchase_summary where invoiceid ='$datarow->voucherid'")->row()->Description;

                                                    elseif($datarow->vouchertype=="purchase return"):
                                                        echo $this->db->query("select Description from purchase_return_summary where invoiceid ='$datarow->voucherid'")->row()->Description;

                                                    elseif($datarow->vouchertype=="sales return"):
                                                        echo $this->db->query("select Description from sell_return_summary where invoiceid ='$datarow->voucherid'")->row()->Description;

                                                    elseif($datarow->vouchertype=="Payment voucher" && strpos($datarow->description, "Pur-") === false):
                                                        echo $this->db->query("select description from payments where id ='$datarow->voucherid'")->row()->description;

                                                    elseif($datarow->vouchertype=="Received voucher" && strpos($datarow->description, "Inv-")):
                                                        echo $this->db->query("select description from received where id ='$datarow->voucherid'")->row()->description;
                                                    
                                                    ?> 
                                                    <?php
                                                    else:
                                                        echo $datarow->description; 
                                                    endif;
                                                    ?>
                                                </td>

                                                <td style="text-align: right"><?php echo (number_format($datarow->debit)); ?></td>
                                                <td style="text-align: right"><?php echo (number_format($datarow->credit)); ?></td>
                                                <td style="text-align: right">
                                                    <?php
                                                    if ($currbalance < 0):
                                                        echo '('. (number_format(abs($currbalance))) . ")";
                                                    elseif ($currbalance == 0):
                                                        echo "0.00";
                                                    else:
                                                        echo (number_format($currbalance));
                                                    endif;
                                                    ?>
                                                </td> 
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
                                    <td></td>
                                    <td style="text-align: right"><b><?php echo (number_format(array_sum($aDebit))); ?></b></td>
                                    <td style="text-align: right"><b><?php echo (number_format(array_sum($aCredit))); ?></b></td>
                                    <td style="text-align: right"><b><?php echo (number_format($currbalance))?></b></td>
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
    $(document).ready(function () {
        $('#example1').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'print'
            ]
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
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Ledger Balance</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:20px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('p{ font-size:20px;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write(comaddress);
       
        docprint.document.write('<hr style="width:700px;">');
        docprint.document.write('<p style="text-align:left;">Ledger Name&nbsp&nbsp&nbsp&nbsp&nbsp: <b><?php echo $selectledger->ledgername; ?></b>');
        
        <?php if($selectledger->address!=""):?>
            docprint.document.write('</br>Address&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <?php echo $selectledger->address;echo $selectledger->district_name!=""?",".$selectledger->district_name:"";?>');
        <?php endif;?>
        <?php if($selectledger->mobile!=""):?>
            docprint.document.write('</br>Mobile No.&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <?php echo $selectledger->mobile; ?>');
        <?php endif;?>
        docprint.document.write('</p><p style="margin:-10px 0 10px 0px;text-align:center;">Statement (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</center><p style="text-align:right;margin-top:50px;text-decoration: overline">Authorized Signature</p></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }

</script>