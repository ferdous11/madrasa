<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->   
        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Purchase Details
                    </header>
                    <div class="panel-body">    
                        <?php
                        $sotre = $this->db->get_where('company', array('status' => '1'))->row();
                        ?>
                        <div class="form" id="invoicediv">
                            
                            <?php echo $company->address;?>
                                   
                            <table style="font-family: adorsholipi;font-size: 18px;font-style: bold" border="0" class="display table">
                                <colgroup>
                                    <col span="1" style="width: 22%;">
                                    <col span="1" style="width: 1%;">
                                    <col span="1" style="width: 59%;">
                                    <col span="1" style="width: 18%;">
                                </colgroup>

                                <tr>
                                    <td>Ledger Name</td>
                                    <td>:</td>
                                    <td><?php echo '<b>'.$summary->ledgername.'</b>';?></td>
                                </tr>
                                
                                <tr>
                                    <td><small>Address</small></td>
                                    <td>:</td>
                                    <td><small> <?php echo $summary->address;echo $summary->district_name!=""?", ".$summary->district_name:""; ?></small></td>
                                    <td><b>Pur#<?php echo sprintf("%06d", $summary->id); ?></b></td>
                                </tr>
                                
                                <tr>  
                                    <td><small>Mobile No</small></td> 
                                    <td>:</td> 
                                    <td><small><?php echo $summary->mobile; ?></small></td>  
                                    <td><small><?php $date=strtotime($summary->date); echo (date('d-M-Y',$date)); ?></small></td>
                                </tr>                                 
                            </table>
                            <table border="1" class="display table table-bordered">
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Product Name</th>
                                    <th style="text-align: center;">Quantity</th>
                                    <th style="text-align: center;">Price</th>
                                    <th style="text-align: center;">Total(Tk.)</th>
                                </tr>
                                <?php
                                $i = 1;
                                $r=0;
                                if (sizeof($purchasedata) > 0):
                                    foreach ($purchasedata as $invoice): ++$r;
                                        ?>
                                        
                                        <tr>
                                            <td><?php echo ($i++); ?></td>
                                            <td><?php echo $invoice->product_name;echo $invoice->comment!="" ? "(".$invoice->comment.")":"";?></td>
                                            <td style="text-align: right;"><?php echo (float)$invoice->quantity.' '.$invoice->unit; ; ?></td>
                                            <td  style="text-align: right;"><?php echo (float)($invoice->buyprice); ?></td>
                                            
                                            <td  style="text-align: right;"><?php echo moneyFormatIndia(ceil($invoice->quantity * $invoice->buyprice)); ?></td>
                                        </tr>
                                        <?php
                                        
                                    endforeach;
                                endif;
                                ?>
                                
                                <tr>
                                    <td colspan="4" style="text-align: right;padding-right: 20px;">
                                        <b>Total</b>
                                    </td>
                                    <td  style="text-align: right;"><?php  echo moneyFormatIndia(ceil($summary->total_purchase)); ?></td>
                                </tr>

                                <tr <?php echo $summary->payment==0?"hidden":"";?>>
                                    <td colspan="4" style="text-align: right;padding-right: 20px;">
                                        <b>Payment</b>
                                    </td>
                                    <td style="text-align: right;"><?php echo moneyFormatIndia(ceil($summary->payment)); ?></td>
                                </tr>

                                <tr>
                                    <td colspan="5" style="text-align: center;">
                                        <p>
                                           <b> Gross Total : <?php echo moneyFormatIndia(ceil($grossTotal))." TK.";?></br>
                                            In Word : <?php  echo $inword."."; ?></b>
                                        <p>
                                    </td>
                                    
                                </tr>

                                <tr>
                                    <td colspan="6">
                                      
                                        <label style="float:left;margin-top:20px;">Authorized by: </label>
                                        <label style="float:right;margin-top:20px">Prepared by: <?php echo $summary->fullname;?></label>
                                    </td>

                                </tr>
                            </table>
                            <div class="row"><div style="text-align: center;" class="col-md-12">This is a computer generated invoice & do not require any signature.</div></div>
                        </div>
                        <br/>
                        <span style="float: right;margin-left: 20px"><button class="btn btn-primary" value="Print" tabindex="1" onclick="Clickheretoprint()">Print Memo</button></span>&nbsp;&nbsp;
                        <span style="float: right;margin-left: 20px"><button class="btn btn-primary" value="Print" tabindex="2" onclick="chalan()">Print Chalan</button></span>&nbsp;&nbsp;
                        &nbsp;<span style="float: right;margin-left: 15px"><a href="<?php if(isset($backtoreport)&&$backtoreport)echo site_url('purchase/sellhistory');else echo site_url('purchase') ?>"><button tabindex="3" class="btn btn-primary">Back</button></a></span>
                    </div>

                    <div style="display: none">
                    <div id="chalan">
                    
                            <?php echo $company->address;?>
                                  
                            <table style="font-family: adorsholipi;font-size: 18px;font-style: bold" border="0" class="display table">
                                <colgroup>
                                    <col span="1" style="width: 22%;">
                                    <col span="1" style="width: 1%;">
                                    <col span="1" style="width: 59%;">
                                    <col span="1" style="width: 18%;">
                                </colgroup>
                                <tr>
                                    <td>Ledger Name</td>
                                    <td>:</td>
                                    <td><?php echo '<b>'.$summary->ledgername.'</b>';?></td>
                                </tr>
                                
                                <tr>
                                    <td><small>Address</small></td>
                                    <td>:</td>
                                    <td><small> <?php echo $summary->address;echo $summary->district_name!=""?", ".$summary->district_name:""; ?></small></td>
                                    <td><b>Pur#<?php echo sprintf("%06d", $summary->id); ?></b></td>
                                </tr>
                                
                                <tr>  
                                    <td><small>Mobile No</small></td> 
                                    <td>:</td> 
                                    <td><small><?php echo $summary->mobile; ?></small></td>  
                                    <td><small><?php $date=strtotime($summary->date); echo (date('d-M-Y',$date)); ?></small></td>
                                </tr>                                 
                            </table>
                            </br>
                            <table border="1" class="display table table-bordered">
                                <tr>
                                    <th>Sl.No</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                             
                                </tr>
                                <?php
                                $i = 1;
                                $r=0;
                                if (sizeof($purchasedata) > 0):
                                    foreach ($purchasedata as $invoice): ++$r;
                                        ?>
                                        
                                        <tr>
                                            <td><?php echo ($i++); ?></td>
                                            <td><?php echo $invoice->product_name;echo (($invoice->full_package==1)? "(Package)" :(($invoice->full_package==2)?"(Empty)":(($invoice->full_package==0)?"(Refill)":""))); echo $invoice->comment!="" ? "(".$invoice->comment.")":""; ?></td>
                                            <td><?php echo number_format($invoice->quantity,2).' '.$invoice->unit; ?></td>
                                            
                                        </tr>
                                        <?php
                                        
                                    endforeach;
                                endif;
                                ?>
                                <tr>
                                    <td colspan="3">
                                       
                                        <label style="float:left;margin-top:20px;">Authorized by: </label>
                                        <label style="float:right;margin-top:20px">Prepared by: <?php echo $summary->fullname;?></label>
                                    </td>
                                    
                                </tr>

                            </table>  
                            <div class="row"><div style="text-align: center;" class="col-md-12">This is a computer generated invoice & do not require any signature.</div></div>                    
                    </div>
                    </div>
                </section>
            </div>          
        </div>        
        <!-- page end-->
    </section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>

<script type="text/javascript">

    function Clickheretoprint()
    {
        var bottom_note = "<?php echo $company->bottom_note; ?>";             
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=760, left=215, top=215";
        var docprint = window.open("Note:"+bottom_note, "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv"); 
        docprint.document.open();
        docprint.document.write('<html>');
        docprint.document.write('<head><style>');
        docprint.document.write('@media print {  @page {size: 8.3in 11.67in;margin-top:1cm !important; margin-bottom: 1cm !important;margin-left:0.5in !important;margin-right:0.5in !important;}}');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:left;font-size:25px;padding-left:7px;}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write(oTable.innerHTML);
        docprint.document.write('</center></body>');
        docprint.document.write('<footer>'+bottom_note+'</footer></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }

    function chalan()
    {
        var bottom_note = "<?php echo $company->bottom_note; ?>";                 
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=0, top=0";
        var docprint = window.open("Note:"+bottom_note, "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("chalan");
        docprint.document.open();
        docprint.document.write('<html>');
        docprint.document.write('<head><style>');
        docprint.document.write('@media print {  @page {size: 8.3in 11.67in;margin-top:1cm !important; margin-bottom: 1cm !important;margin-left:0.5in !important;margin-right:0.5in !important;}}');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:left;font-size:25px;padding-left:7px;}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write(oTable.innerHTML);
        docprint.document.write('</center></body>');
        docprint.document.write('<footer>'+bottom_note+'</footer></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
      
    }
   
</script>