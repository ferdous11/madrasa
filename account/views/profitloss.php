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
                            <form class="tasi-form" method="post" action="<?php echo site_url('profitloss'); ?>">
                                <div class="form-group">

                                    <div class="col-md-3" style="padding-left: 0">
                                        <div class="input-group input-sm" >
                                            
                                            <span class="input-group-addon">To</span>
                                            <div class="iconic-input right">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" class="form-control" name="sdate" id="sdate" value="<?php echo $sdate;?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="padding-left: 0">
                                        <div class="input-group input-sm" >
                                            
                                            <span class="input-group-addon">End</span>
                                            <div class="iconic-input right">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" class="form-control" name="edate" id="edate" value="<?php echo $edate;?>">
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
                        Profit Loss<span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
                    </header>
                    <div class="panel-body">
                        <?php $totalcategory=count($categorys);?>
                        <div id="invoicediv">                          
                            <table class="display table table-bordered" id="example1" border="1">

                                <tr><th style="text-align:center;vertical-align:middle;border:3px solid" >Category</th>
                                    <th style="text-align:center;border:3px solid" colspan="4">From Sales.</th>
                                    <th style="text-align:center;border:3px solid" colspan="4">Sales Return</th>
                                    <th style="text-align:center;vertical-align:middle;border:3px solid" >Profit</th>
                                </tr>

                                <?php  $totaldebit=$totalcredit=$income=$expense=0;
                                foreach ($categorys as $key) {
                                    
                                    if(array_key_exists($key->id,$sales))
                                        $SalesByCategory = $sales[$key->id];
                                    else
                                        $SalesByCategory = 0;

                                    if(array_key_exists($key->id,$salesReturn))
                                        $salesReturnByCategory = $salesReturn[$key->id];
                                    else
                                        $salesReturnByCategory = 0;

                                    $totalcredit+=$SalesByCategory;
                                    $totaldebit+=$salesReturnByCategory;

                                    echo '
                                    <tr>
                                        <td style="text-align:left;border: 3px solid;">'.$key->name.'</td>
                                        <td style="text-align:right;border: 3px solid;" colspan="4" >'.number_format($SalesByCategory,2).'</td>
                                        <td style="text-align:right;border: 3px solid;" colspan="4">'.number_format($salesReturnByCategory,2).'</td>
                                        <td style="text-align:right;border: 3px solid;">'.number_format($SalesByCategory-$salesReturnByCategory,2).'</td>
                                    </tr>';
                                }
                                ?>

                                <tr>
                                    <th style="text-align:right;border: 3px solid;">Total</th>
                                    <th style="text-align:right;border: 3px solid;" colspan="4" ><?php echo  number_format($totalcredit,2);?></th>
                                    <th style="text-align:right;border: 3px solid;" colspan="4" ><?php echo  number_format($totaldebit,2);?></th>
                                    <th style="text-align:right;border: 3px solid;"><?php echo  number_format($totalcredit-$totaldebit,2);?></th>
                                </tr>
                                <tr >
                                    <td style="height:3px;border:3px solid;" colspan="10"></td>
                                </tr>

                                <tr >
                                    <th style="border:3px solid;text-align:center;" colspan="5">Expense</th>
                                    <th style="border:3px solid;text-align:center;" colspan="5">Income</th>
                                </tr>
                                <tr >
                                    <th style="border-left:3px solid;border-right:3px solid;text-align:center;white-space:nowrap" colspan="5">
                                    <div class="row" style="text-align:left;">
                                        
                                        <div class="col-xs-6">Labour Cost= <?php echo number_format($labourcostquery->debit,2);$totaldebit+=$labourcostquery->debit;$expense+=$labourcostquery->debit;?></div>
                                        <div class="col-xs-6">Transport Cost = <?php echo number_format($transportcostquery->debit,2);$totaldebit+=$transportcostquery->debit;$expense+=$transportcostquery->debit;?></div>
                                    </div>
                                    <div class="row" style="text-align:left;">
                                        <div class="col-xs-6">Shipping Cost = <?php echo number_format($shippingcostquery->debit,2);$totaldebit+=$shippingcostquery->debit;$expense+=$shippingcostquery->debit;?></div>
                                        <div class="col-xs-6">Other Cost = <?php echo number_format($othercostquery->debit,2);$totaldebit+=$othercostquery->debit;$expense+=$othercostquery->debit;?></div>
                                       
                                    </div>

                                    <div class="row" style="text-align:left;">
                                        
                                        <div class="col-xs-6">Sales Discount = <?php echo number_format($discountquery->debit,2);$totaldebit+=$discountquery->debit;$expense+=$discountquery->debit;?></div>
                                        <div class="col-xs-6">Employee Salary = <?php echo number_format($employeequery->debit,2);$totaldebit+=$employeequery->debit;$expense+=$employeequery->debit;?></div>
                                    </div>

                                    <?php if(count($expenseledgerquery)>0) for ($i=0; $i <count($expenseledgerquery) ; $i+=2) { ?> 
                                        <div class="row" style="text-align:left;">
                                        
                                            <div class="col-xs-6"><?php echo $expenseledgerquery[$i]->ledgername.' = '.  number_format($expenseledgerquery[$i]->debit+$legderopeninarray[$expenseledgerquery[$i]->id],2);$totaldebit+=$expenseledgerquery[$i]->debit+$legderopeninarray[$expenseledgerquery[$i]->id];$expense+=$expenseledgerquery[$i]->debit+$legderopeninarray[$expenseledgerquery[$i]->id];?></div>
                                            <?php if(array_key_exists($i+1, $expenseledgerquery)):?>
                                                <div class="col-xs-6"><?php echo $expenseledgerquery[$i+1]->ledgername.' = '.  number_format($expenseledgerquery[$i+1]->debit+$legderopeninarray[$expenseledgerquery[$i+1]->id],2);$totaldebit+=$expenseledgerquery[$i+1]->debit+$legderopeninarray[$expenseledgerquery[$i+1]->id];$expense+=$expenseledgerquery[$i+1]->debit+$legderopeninarray[$expenseledgerquery[$i+1]->id];?></div>
                                            <?php endif;?>
                                        </div>
                                   
                                    <?php }?>
                                    
                                    </th>
                                    <th style="border-left:3px solid;border-right:3px solid;text-align:center;white-space:nowrap" colspan="5">
                                        <div class="row" style="text-align:left;">
                                            
                                            <div class="col-xs-6">Purchase Discount = <?php echo number_format($discountquery->credit,2);$totalcredit+=$discountquery->credit;$income+=$discountquery->credit;?></div>
                                            <div class="col-xs-6"></div>
                                        </div>

                                        <?php if(count($otherincomquery)>0) for ($i=0; $i <count($otherincomquery) ; $i+=2) { ?> 
                                        <div class="row" style="text-align:left;">
                                        
                                            <div class="col-xs-6"><?php echo $otherincomquery[$i]->ledgername.' = '.  number_format($otherincomquery[$i]->credit+($legderopeninarray[$otherincomquery[$i]->id]*-1),2);$totalcredit+=$otherincomquery[$i]->credit+($legderopeninarray[$otherincomquery[$i]->id]*-1);$income+=$otherincomquery[$i]->credit+($legderopeninarray[$otherincomquery[$i]->id]*-1);?></div>
                                            <?php if(array_key_exists($i+1, $otherincomquery)):?>
                                                <div class="col-xs-6"><?php echo $otherincomquery[$i+1]->ledgername.' = '.  number_format($otherincomquery[$i+1]->credit+($legderopeninarray[$otherincomquery[$i+1]->id]*-1),2);$totalcredit+=$otherincomquery[$i+1]->credit+($legderopeninarray[$otherincomquery[$i+1]->id]*-1);$income+=$otherincomquery[$i+1]->credit+($legderopeninarray[$otherincomquery[$i+1]->id]*-1);?></div>
                                            <?php endif;?>
                                        </div>
                                   
                                    <?php }?>

                                    </th>
                                </tr>

                                
                                <tr>
                                    <th colspan="5" style="text-align:center;border:3px solid;">Total = <?php echo number_format($expense,2);?></th>
                                    
                                    <th colspan="5" style="text-align:center;border:3px solid;">Total = <?php echo number_format($income,2);?></th>
                                   
                                </tr>
                                <tr>
                                    <th colspan="5" style="text-align:center;border:3px solid;">Total Dr. Amount = <?php echo number_format($totaldebit,2);?></th>
                                    
                                    <th colspan="5" style="text-align:center;border:3px solid;">Total Cr. Amount = <?php echo number_format($totalcredit,2);?></th>
                                   
                                </tr>
                                <tr>
                                    <th colspan="10" style="text-align:center;border:3px solid;">Net Profit = <?php echo number_format($totalcredit - $totaldebit,2);?></th>
                                   
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

    $("#datepickermonth").datepicker( {
    format: "yyyy-mm",
    startView: "months", 
    minViewMode: "months"
    });

   function Clickheretoprint()
    {
        
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';
               
        var to_date = "<?php $date=date_create($edate.'-01'); echo date_format($date,'F-Y');?>";        
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("example1");
        docprint.document.open();
        docprint.document.write('<html><title>Profit Loss</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write(comaddress);
        
        docprint.document.write('<h2 style="margin-top:-15px;text-align:center;"><u>Profit Loss (' + to_date + ') </u></h2>');
        
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }

</script>