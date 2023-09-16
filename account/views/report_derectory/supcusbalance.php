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
                            <form class="tasi-form" method="post" action="<?php echo site_url('reports/supcusbalance'); ?>">
                                <div class="form-group">

                                    <div class="col-md-3" style="padding-left: 0">
                                        <div class="input-group input-sm" >
                                            
                                            <span class="input-group-addon">To</span>
                                            <div class="iconic-input right">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" id="edate" class="form-control" name="enddate" value="<?php echo $enddate; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        
                                        <select class="form-control selectpicker" data-live-search='true' name="typesc" required="">
                                            <option  value='0'>--Select--</option>
                                            <option <?php echo($typesc==7)?'selected':'';?> value='7'>Student</option>
                                            <option <?php echo($typesc==9)?'selected':'';?> value='9'>Donor</option>
                                            <option <?php echo($typesc==6)?'selected':'';?> value='6'>Customer</option>
                                            <option <?php echo($typesc==5)?'selected':'';?> value='5'>Supplier</option>
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
        <?php if($typesc!=''): $tbalance =0 ;?>
            <div class="row">
                <div class="col-lg-12">                
                    <section class="panel">
                        <header class="panel-heading">
                            <a href="<?php echo site_url('reports/exports_sup_cus/'.$enddate.'/'.$typesc); ?>"><button style="float: right; margin-left: 5px; padding: 1px 5px 1px 5px" class="btn btn-primary btn-md">Export All</button></a>
                            Balance For <?php echo $typesc=="16"?"All Customers":"All Suppliers";?><span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
                        </header>
                        <div class="panel-body">
                            <div id="invoicediv">                          
                                <table class="display table table-bordered" border="1">

                                    <tr>
                                        <th class="hidethistoprint"><input type="checkbox" id="checkAll"> Check All</th>
                                        <th>Name</th>
                                        <th>Father's Name</th>
                                        <th>Mobile No</th>
                                        <th>Address</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                    </tr>

                                <?php
                                    if (sizeof($ledgerdata) > 0):
                                        $i=1;$tdebit=$tcredit=0;
                                        foreach ($ledgerdata as $datarow):
                                        $debit = $datarow->pdebit + $datarow->debit;
                                        $credit = $datarow->pcredit + $datarow->credit;
                                        $tcredit+=$credit;$tdebit+=$debit;
                                        $balance = $debit - $credit;
                                        $tbalance += $balance;
                                        if($tbalance>0)
                                        $sbalance=$tbalance.' Dr';
                                        else 
                                        $sbalance = ($tbalance*-1). ' Cr';
                                    if ($balance==0)
                                        continue;

                                ?>
                                    <tr class="hidethistoprint" id='trc<?php echo $i;?>'>
                                        <td class="hidethistoprint"><input type="checkbox" id='c<?php echo $i++;?>'></td>
                                        <td style="text-align: left;!importent"><a target="_blank" href="<?php echo $baseurl.'/ledgerbalance/viewledgerbalance?sdate=01-01-2010&edate='.$enddate.'&ledgername='.$datarow->id;?>"><?php echo $datarow->ledgername; ?></a></td>
                                        <td style="text-align: left;"><?php echo $datarow->father_name; ?></td>
                                        <td style="text-align: right;"><?php echo $datarow->mobile; ?></td>
                                        <td style="text-align: left;"><?php echo $datarow->address.", ".$datarow->district_name; ?></td>
                                        <td style="text-align: right;"><?php  echo (number_format($debit, 2)); ?></td>
                                        <td style="text-align: right;"><?php echo (number_format($credit, 2)); ?></td>
                                        <td style="text-align: right;">
                                            <?php if($balance>0) echo (number_format($balance, 2)); else echo "(".(number_format($balance * -1, 2)).")";?>
                                        </td>
                                    </tr>
                                <?php
                                        endforeach;
                                    endif;
                                ?>
                                    

                                    <tr class="hidethistoprint">
                                        <td class="hidethistoprint"></td>  
                                        <td></td>  
                                        <td></td>  
                                        <td></td>  
                                        <td><b>Total : </b></td>  
                                        <td style="text-align: right;"><b><?=number_format($tdebit, 2);?></b></td> 
                                        <td style="text-align: right;"><b><?=number_format($tcredit, 2);?></b></td>  
                                        <td style="text-align: right;"><b>
                                        <?php
                                            echo $tbalance<0? "(".(number_format($tbalance*-1, 2)).")":(number_format($tbalance, 2)) ;
                                        ?>
                                        </b>
                                        </td>                                       
                                    </tr>

                                </table>                      

                            </div>
                        </div> 
                    </section>
                </div>          
            </div>
        <?php endif;?>


        <!-- page end-->
    </section>
</section>

<?php include __DIR__ .'/../footer.php'; ?>
<script type="text/javascript">
   function Clickheretoprint()
    {
        $(".hidethistoprint").hide();
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';     
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title> Customer/ Supplier Balance</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('a{text-decoration: none;color:black;} table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
       
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        

        docprint.document.write(comaddress);
       
        docprint.document.write("<p>Balance For <?php $date1=date_create($enddate); if($typesc==15) echo 'Supplier'; else echo 'Customer';echo '('.date_format($date1,"d-m-Y").')';?></p>");
        
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
        $(".hidethistoprint").show();
    }

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
        if($(this).prop("checked") == true){
            $('tr').removeClass("hidethistoprint");
        }
        else if($(this).prop("checked") == false){
            $('tr').addClass("hidethistoprint");
        }
    });
    $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
                var id = this.id;
                $("#tr"+this.id).removeClass("hidethistoprint");
            }
            else if($(this).prop("checked") == false){
                $("#tr"+this.id).addClass("hidethistoprint");
            }
    });

</script>