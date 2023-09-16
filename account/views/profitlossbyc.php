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
                                                <input type="text" id="datepickermonth" class="form-control" name="datepickermonth" value="<?php echo $edate;?>">
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
                        <?php $totalcategory=count($products);?>
                        <div id="invoicediv">                          
                            <table class="display table table-bordered" id="example1">

                                <tr><th rowspan="2" style="text-align:center;vertical-align:middle;border:3px solid" >Product Name(id)</th>
                                    <th style="text-align:center;border:3px solid" colspan="4">Dr.</th>
                                    <th style="text-align:center;border:3px solid" colspan="4">Cr.</th>
                                    <th rowspan="2" style="text-align:center;vertical-align:middle;border:3px solid" >Profit</th>
                                </tr>
                                <tr>
                                    
                                    <th style="text-align:center;border: 3px solid;" >Opening</th>
                                    <th style="text-align:center;border: 3px solid;" >Purchase</th>
                                    <th style="text-align:center;border: 3px solid;" >Sales R.</th>
                                    <th style="text-align:center;border: 3px solid;" >Total Dr.</th>

                                    <th style="text-align:center;border: 3px solid;" >Sales</th>
                                    <th style="text-align:center;border: 3px solid;" >Purchase R.</th>
                                    <th style="text-align:center;border: 3px solid;">Clossing</th>
                                    <th style="text-align:center;border: 3px solid;">Total Cr.</th>
                                </tr>
 
                                
                                

                                <?php  $totaldebit=$totalcredit=0;
                                foreach ($products as $key) {
                                   $purchaseByCategory=$purchasereturnByCategory=$salesreturnByCategory=$salesByCategory=$closingByCategory=0;
                                    if(array_key_exists($key->id,$openingarray))
                                        $openingByCategory = $openingarray[$key->id];
                                    if(array_key_exists($key->id,$purchasearray))
                                        $purchaseByCategory = $purchasearray[$key->id];
                                    if(array_key_exists($key->id,$salesarray))
                                        $salesByCategory = $salesarray[$key->id];
                                    if(array_key_exists($key->id,$salesreturnarray))
                                        $salesreturnByCategory = $salesreturnarray[$key->id];
                                    if(array_key_exists($key->id,$purchasereturnarray))
                                        $purchasereturnByCategory = $purchasereturnarray[$key->id];
                                    if(array_key_exists($key->id,$clossingarray))
                                        $closingByCategory = $clossingarray[$key->id];
                                    echo '
                                    <tr>
                                        <td style="text-align:left;border: 3px solid;">'.$key->product_name.'('.$key->product_id.')</td>
                                        <td style="text-align:right">'.number_format($openingByCategory,2).'</td>
                                        <td style="text-align:right">'.number_format($purchaseByCategory,2).'</td>
                                        <td style="text-align:right">'.number_format($salesreturnByCategory,2).'</td>
                                        <th style="text-align:right;border-right: 3px solid;">'.number_format($salesreturnByCategory+$openingByCategory+$purchaseByCategory,2).'</th>
                                        <td style="text-align:right;">'.number_format($salesByCategory,2).'</td>
                                        <td style="text-align:right">'.number_format($purchasereturnByCategory,2).'</td>
                                        <td style="text-align:right">'.number_format($closingByCategory,2).'</td>
                                        <th style="text-align:right;border-right: 3px solid;">'.number_format($salesByCategory+$purchasereturnByCategory+$closingByCategory,2).'</th>
                                        <th style="text-align:right;border: 3px solid;">'.number_format((($salesByCategory+$purchasereturnByCategory+$closingByCategory)-($salesreturnByCategory+$openingByCategory+$purchaseByCategory)),2).'</th>
                                    
                                    </tr>';

                                }
                                ?>

                                <tr>
                                    <th style="text-align:right;border: 3px solid;">Total</th>
                                    <th style="text-align:right;border: 3px solid;"><?php echo  number_format($openingstock,2);?></th>
                                    <th style="text-align:right;border: 3px solid;"><?php echo  number_format($purchase,2);?></th>
                                    <th style="text-align:right;border: 3px solid;"><?php echo  number_format($salesreturn,2);?></th>
                                    <th style="text-align:right;border: 3px solid;background-color:#fcbdb6"><?php echo  number_format($purchase+$salesreturn+$openingstock,2);?></th>
                                    <th style="text-align:right;border: 3px solid;"><?php echo  number_format($sales,2);?></th>
                                    <th style="text-align:right;border: 3px solid;"><?php echo  number_format($purchasereturn,2);?></th>
                                    <th style="text-align:right;border: 3px solid;"><?php echo  number_format($clossingstock,2);?></th>
                                    <th style="text-align:right;border: 3px solid;background-color:#fbfc88"><?php echo  number_format($clossingstock+$purchasereturn+$sales,2);?></th>
                                    <th style="text-align:right;border: 3px solid;background-color:#63fa05"><?php echo  number_format((($clossingstock+$purchasereturn+$sales)-($purchase+$salesreturn+$openingstock)),2);
                                    $totaldebit = $purchase+$salesreturn+$openingstock;
                                    $totalcredit = $clossingstock+$purchasereturn+$sales;
                                    ?></th>
                                   
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
        var comname = "<?php echo $this->session->userdata('company_name'); ?>";
        var comaddress = "<?php echo $this->session->userdata('company_address'); ?>";
        var comemail = "<?php echo $this->session->userdata('email'); ?>";        
        var mobile = "<?php echo $this->session->userdata('mobile'); ?>";        
               
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
        docprint.document.write('table thead, tr, th, table tbody, tr, td { border: 1px solid gray; text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write('<h1 style="text-align:center;">' + comname + '</h1>');
        
        docprint.document.write('<h2 style="margin-top:-15px;text-align:center;"><u>Profit Loss (' + to_date + ') </u></h2>');
        
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }

</script>