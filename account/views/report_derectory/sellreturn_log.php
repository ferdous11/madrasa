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
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('reports/sellreturnhistory'); ?>">

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
                        Sales Return History 
                        <a href="<?php echo site_url('reports/export_salesreturn/'.$sdate.'/'.$edate); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a> 
                        <span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
                    </header>
                    <div class="panel-body">

                        <div class="form">
                            <table class="display table table-bordered table-striped dataTable" id="suppliertableid">
                                <thead>
                                    <tr> 
                                        <th>Invoice Id</th>
                                        <th>Date</th>
                                        <th>Customer Name</th>  
                                        <th>Father's Name</th> 
                                        <th>Mobile No.</th> 
                                        <th>Total Price</th> 
                                        <th>Payment</th> 
                                        <th>Due</th> 
                                        <th>Inserted By</th> 
                                        <th class="hidetoprint" >Action</th> 
                                    </tr>
                                </thead>
                                <colgroup>
                                    <col span="1" style="width: 5%;">
                                    <col span="1" style="width: 10%;">
                                    <col span="1" style="width: 20%;">
                                    <col span="1" style="width: 10%;">
                                    <col span="1" style="width: 8%;">

                                    <col span="1" style="width: 8%;">
                                    <col span="1" style="width: 8%;">
                                    <col span="1" style="width: 8%;">
                                    <col span="1" style="width: 10%;"> 
                                    <col span="1" style="width: 13%;"> 
                                </colgroup>
                                <tbody id="invoicediv">
                                    <?php
                                    $i = 1;
                                    $totalbill = 0;
                                    //

                                    if (sizeof($selldata) > 0):
                                        foreach ($selldata as $sell):
                                            ?>
                                            <tr>
                                                <td><?php echo "Sr-". sprintf("%06d", $sell->id); ?></td>
                                                <td><?php echo $sell->date; ?></td>   
                                                <td><?php echo $sell->ledgername."(".$sell->address.",".$sell->district_name.")"; ?></td>
                                                <td><?php echo $sell->father_name;?></td> 
                                                <td><?php echo $sell->mobile;?></td> 
                                                <td><?php echo $sell->total_purchase;?></td>
                                                <td><?php echo $sell->payment;?></td>   
                                                <td><?php echo number_format($sell->total_purchase-$sell->payment,2) ;?></td>   
                                                <td><?php echo $sell->fullname;?></td>   

                                                <td class="hidetoprint">
                                                    <a style="width:40px" target="_blank"  class='col-lg-3  label label-success'  href="<?php echo site_url('reports/showsellreturn/' . $sell->invoiceid) ?>">Show</a>
                                                   <?php $datef= date('Y-m-d'); if ($role=='admin'  ||  ($sell->date>$datef." 00:00:00" && $sell->date<$datef." 23:59:59")):?>
                                                        <a target="_blank" class="col-lg-3 col-lg-offset-1 label label-danger" href="<?php echo site_url('reports/editsellreturn/'.$sell->invoiceid); ?>" title="View details of Sales Return">Edit</a>

                                                        <a onclick="return confirm('Are you sure to Permanently delete this Sales Return Voucher <?php echo "Sr-". sprintf("%06d", $sell->id) ; ?> !!')" class="col-lg-3 col-lg-offset-1 label label-danger" href="<?php echo site_url('reports/deletesellreturn/' . $sell->invoiceid); ?>" title="Delete Sales Return">Delete</a>
                                                    <?php endif;?>
                                                </td>     

                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                                
                            </table>
                        </div>
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
            iDisplayLength: -1,
            
        });
        $("#foradvanced").hide();
    });
    function Clickheretoprint()
    {
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';   
        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val(); 
        $(".hidetoprint").hide();       
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Sale Return</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write(comaddress);
        docprint.document.write('<h2 style="margin:-10px 0 10px 0px;text-align:center;">Sale Return</h2>');
        docprint.document.write('<table  border="1">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</table>');
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
        $(".hidetoprint").show(); 
    }
</script>
