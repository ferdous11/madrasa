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
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('reports/viewsellhistory'); ?>">

                            <div class="col-lg-2">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date From</label>
                                    <input class="form-control" type="text" name="sdate" value="<?php echo $sdate; ?>" id="sdate"/>                               
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date To</label>
                                    <input class="form-control" type="text" name="edate" value="<?php echo $edate; ?>" id="edate"/>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Customer</label>
                                    <select class="form-control selectpicker" data-live-search="true" name="customer" id="customer">
                                        <option value="all">ALL</option>   
                                        <?php
                                        if (sizeof($customerlist) > 0):
                                            foreach ($customerlist as $buyer):
                                                
                                                ?>
                                                <option <?php echo ($buyer->id == $cname) ? 'selected' : ''; ?> value="<?php echo $buyer->id; ?>"><?php echo substr($buyer->ledgername." (".$buyer->mobile.")"." (".$buyer->address.", ".$buyer->district_name.")",0,80); ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Sales Man</label>
                                    <?php
                                    $comid = $this->session->userdata('company_id');
                                    $getbuyer = $this->db->query("select id,fullname from alluser where company_id = '$comid' order by fullname asc")->result();
                                    ?>
                                    <select class="form-control selectpicker" data-live-search="true" name="salesman" id="salesman">
                                        <option value="all">ALL</option>   
                                        <?php
                                        if (sizeof($getbuyer) > 0):
                                            foreach ($getbuyer as $buyer):
                                                ?>
                                                <option <?php echo ($buyer->fullname == $salesman) ? 'selected' : ''; ?> value="<?php echo $buyer->fullname; ?>"><?php echo $buyer->fullname; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div> -->

                            <div class="col-lg-2">                           
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
                        Daily Sales summary 
                        <a href="<?php echo site_url('reports/export_sellhistory/'.$sdate.'/'.$edate.'/'.$cname); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a> 
                        <span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
                    </header>
                    <div class="panel-body">
                        <?php if ($this->session->userdata('success')): ?>
                            <div class="alert alert-block alert-success fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Congratulation!</strong> <?php
                                echo $this->session->userdata('success');
                                $this->session->unset_userdata('success');
                                ?>
                            </div> 
                        <?php endif; ?>
                        <?php if ($this->session->userdata('failed')): ?>
                            <div class="alert alert-block alert-danger fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Oops!</strong> <?php
                                echo $this->session->userdata('failed');
                                $this->session->unset_userdata('failed');
                                ?>
                            </div> 
                        <?php endif; ?>
                        <div class="form">
                            <table class="display table table-bordered table-striped dataTable" id="suppliertableid">
                                <thead>
                                    <tr> 
                                        <th>Voucher Id</th>
                                        <th>Date</th>  
                                        <th>Party</th>
                                        <th>Comment</th>
                                        <th>Mobile No.</th>
                                        <th>Total Bill</th>
                                        <th>Payment</th>
                                        <th>Due</th>
                                        <th>Inserted By</th>
                                        <th class="hidetoprint">Action</th>
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
                                    <col class="hidetoprint" span="1" style="width: 13%;"> 
                                </colgroup>
                                <tbody id="invoicediv">
                                    <?php
                                    $i = 1;
                                    $totalbill = $totalpayment=0;
                                    $carray = array(); 

                                    if (sizeof($selldata) > 0):
                                        foreach ($selldata as $sell):
                                            if ($sell->total_price != ''):
                                                ?>
                                                <tr>
                                                    <td><a target="_blank" href="<?php echo site_url('reports/printinvoiceagain/' . $sell->voucherid); ?>" title="View details of sell"><?php echo "Inv-". sprintf("%06d", $sell->id) ; ?></a></td>
                                                    <td><?php echo $sell->date; ?></td>
                                                    <td><?php
                                                    echo $sell->customer_name." (".$sell->address.", ".$sell->district_name.")"; 
                                                        ?>
                                                    </td> 
                                                    <td><?php echo $sell->comment;?></td>
                                                    <td><?php echo $sell->mobile;?></td>
                                                    <td style="text-align: right;"><?php echo number_format($sell->total_price - $sell->discount, 2); ?></td>
                                                    <td style="text-align: right;"><?php echo number_format($sell->paid_amount, 2); ?></td>
                                                    <td style="text-align: right;"><?php echo number_format($sell->total_price - $sell->discount-$sell->paid_amount, 2); ?></td>
                                                    <td><?php echo $sell->fullname; ?></td>
                                                                                                   
                                                    <td class="hidetoprint">
                                                        <a target="_blank" style="margin-right:1px;" class='col-lg-4  label label-success'  href="<?php echo site_url('reports/printinvoiceagain/' . $sell->voucherid) ?>">Show</a>
                                                        <!--&& !in_array($sell->customer_name, $carray) -->
                                                    <?php $datef= date('Y-m-d'); if ($role=='admin'  ||  ($sell->date>$datef." 00:00:00" && $sell->date<$datef." 23:59:59")&& $sell->user_id==$this->session->userdata('user_id')):?> 
                                                        <a target="_blank" style="margin-right:1px;" class="col-lg-3 label label-danger" href="<?php echo site_url('reports/edit/' . $sell->voucherid); ?>" title="View details of sell">Edit</a>

                                                        <a onclick="return confirm('Are you sure to Permanently delete this Sales Voucher <?php echo "Inv-". sprintf("%06d", $sell->id) ; ?> !!')" class="col-lg-4 label label-danger" href="<?php echo site_url('reports/deletesell/' . $sell->voucherid); ?>" title="View details of sell">Delete</a>

                                                    <?php $carray[]=$sell->customer_name; endif;?>
                                                   
                                                    </td>
                                                </tr>
                                                <?php
                                                $totalbill = $totalbill + $sell->total_price - $sell->discount;
                                                $totalpayment += $sell->paid_amount;
                                              
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                                <tfoot class="hidetoprint">
                                    <tr>                                       
                                        <th colspan="5" style="text-align: right;">Total:</th>
                                        <th style="text-align: right;"><?php echo number_format($totalbill, 2); ?></th>  
                                        <th style="text-align: right;"><?php echo number_format($totalpayment, 2); ?></th>  
                                        <th style="text-align: right;"><?php echo number_format($totalbill-$totalpayment, 2); ?></th>  
                                        <th colspan="2"></th>  
                                    </tr>
                                </tfoot>
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
            "aaSorting": [[0, "desc"]]
        });
        $("#foradvanced").hide();
    });

    function Clickheretoprint()
    {
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';
        var customername= $("#customer option:selected").text(); 
        var pageURL = window.location.href;
        var lastURLSegment = pageURL.substr(pageURL.lastIndexOf('/') + 1);
        console.log(lastURLSegment);
        var totalbill = parseFloat("<?php echo $totalbill; ?>");  
        totalbill = totalbill.toFixed(2);  

        var totalpayment = parseFloat("<?php echo $totalpayment; ?>");
        totalpayment = totalpayment.toFixed(2);

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
        docprint.document.write('<html><title>Sale Summary</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write(comaddress);
        
        docprint.document.write('<h2 style="margin:-10px 0 10px 0px;text-align:center;">Sale Summary</h2>');
        docprint.document.write('<p style="margin:-10px 0 10px 0px;text-align:center;">Customer : '+customername+'</p>');
        if(lastURLSegment=='sellhistory')
        docprint.document.write('<p style="margin:-10px 0 10px 0px;text-align:center;">Date:'
            + from_date + ' Last 10 Sales ');
        else
            docprint.document.write('<p style="margin:-10px 0 10px 0px;text-align:center;">Date:'
            +' (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write('<table  border="1">');
        docprint.document.write(oTable.parentNode.innerHTML);

        docprint.document.write('<tr><th colspan="5" style="text-align: right;">Total:</th><th>'+totalbill+'</th><th>'+totalpayment+'</th><th>'+(totalbill-totalpayment).toFixed(2)+'</th><th> </th></tr></table>');

        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
        $(".hidetoprint").show(); 
    }
</script>

