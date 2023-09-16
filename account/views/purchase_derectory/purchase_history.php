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
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('purchase/viewpurchasehistory'); ?>">
                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date From</label>
                                    <input autocomplete="off" class="form-control" type="text" name="sdate" value="<?php echo $sdate; ?>" id="combodateFrom"/>                               
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date To</label>
                                    <input autocomplete="off" class="form-control" type="text" name="edate" value="<?php echo $edate; ?>" id="combodateTo"/>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Supplier</label>
                                    <?php
                                    $comid = $this->session->userdata('company_id');
                                    $getsuplier = $this->db->query("select l.*,d.name as district_name from accountledger as l left join districts as d on l.district=d.id where l.accountgroupid = '5' AND l.company_id = '$comid' and l.status<>0 order by l.ledgername asc")->result();
                                    ?>
                                    <select class="form-control selectpicker" data-live-search="true" name="supplier_id" id="supplier_id">
                                        <option value="all">ALL</option>   
                                        <?php
                                        if (count($getsuplier) > 0):
                                            foreach ($getsuplier as $supplr):
                                                ?>
                                                <option <?php echo ($supplier_id == $supplr->id) ? 'selected' : ''; ?> value="<?php echo $supplr->id; ?>"><?php echo substr($supplr->ledgername." (".$supplr->mobile.")"." (".$supplr->address.", ".$supplr->district_name.")",0,80); ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
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
                        Purchase History 
                        <a href="<?php echo site_url('purchase/export_purchasehistory/'.$sdate.'/'.$edate.'/'.$supplier_id); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a> 
                        
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
                            <table class="display table table-bordered table-striped dataTable" id="purchasesummary">
                                <thead>
                                    <tr>                                    
                                        
                                        <th>Invoice ID</th>  
                                        <th>Date</th>   
                                        <th>Party</th>  
                                        <th>Father's Name</th>
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
                                    <col span="1" style="width: 9%;">
                                    <col span="1" style="width: 21%;">
                                    <col span="1" style="width: 9%;">
                                    <col span="1" style="width: 11%;">
                                    <col span="1" style="width: 7%;">
                                    <col span="1" style="width: 7%;">
                                    <col span="1" style="width: 7%;">
                                    <col span="1" style="width: 8%;"> 
                                    <col class="hidetoprint" span="1" style="width: 16%;"> 
                                </colgroup>

                                <tbody id="invoicediv">
                                    <?php $i = 1;
                                    $Grosstotalbutcost = 0;$totalpayment=0;
                                    
                                    if (count($purchasedata) > 0):
                                        foreach ($purchasedata as $buy):
                                            $totalpurchase = $buy->total_purchase;
                                    ?>
                                            <tr>
                                                
                                                <td><a target="_blank" href="<?php echo site_url('purchase/detailspurchase/' . $buy->invoiceid); ?>"><?php echo "Pur-". sprintf("%06d", $buy->id); ?></a></td>   
                                                <td><?php echo $buy->date; ?></td> 
                                                <td><?php
                                                    
                                                    $party =$this->db->query("select a.ledgername,a.address,a.father_name,a.mobile,d.name as district_name from accountledger as a left join districts as d on a.district=d.id where a.id='$buy->supplier_id'")->row();
                                                    echo $party->ledgername." (".$party->address.", ".$party->district_name.")"; 
                                                ?></td>   

                                                <td><?php echo $party->father_name;?></td>
                                                <td><?php echo $party->mobile;?></td>

                                                <td class="t-r"><?php echo number_format($totalpurchase, 2); ?></td>
                                                
                                                <td><?php echo number_format(($buy->payment), 2); ?></td>
                                                <td><?php echo number_format(($totalpurchase-$buy->payment), 2); ?></td>
                                           
                                                <td><?php echo $buy->fullname;?></td>

                                                <td class="hidetoprint">
                                                        <a style="width:40px" target="_blank"  class='col-lg-3  label label-success'  href="<?php echo site_url('purchase/detailspurchase/' . $buy->invoiceid); ?>">Show</a>
                                                    <?php $datef= date('Y-m-d'); if ($role=='admin'  ||  ($buy->date>$datef." 00:00:00" && $buy->date<$datef." 23:59:59") && $buy->user_id==$this->session->userdata('user_id')):?>
                                                        <a  class="col-lg-3 col-lg-offset-1 label label-danger" href="<?php echo site_url('purchase/edit_purchase_view/' . $buy->invoiceid); ?>" title="Edit Purchase">Edit</a>

                                                        <a onclick="return confirm('Are you sure to Permanently delete this Purchase Voucher <?php echo "Inv-". sprintf("%06d",  $buy->id) ; ?> !!')" class="col-lg-3 col-lg-offset-1 label label-danger" href="<?php echo site_url('purchase/deletepurchase/' . $buy->invoiceid); ?>" title="Delete Purchase">Delete</a>

                                                    <?php endif;?>
                                                   
                                                </td>
                                            </tr>
                                            <?php
                                            $Grosstotalbutcost = $Grosstotalbutcost + $buy->total_purchase;
                                            $totalpayment += $buy->payment;
                                        endforeach;
                                    endif;
                                    ?>

                                </tbody> 

                                <tfoot class="hidetoprint">
                                    <tr>                                       
                                        <th colspan="5" style="text-align: right;">Total:</th>
                                        <th style="text-align: right;"><?php echo number_format($Grosstotalbutcost, 2); ?></th>  
                                        <th style="text-align: right;"><?php echo number_format($totalpayment, 2); ?></th>  
                                        <th style="text-align: right;"><?php echo number_format($Grosstotalbutcost-$totalpayment, 2); ?></th>  
                                        <th colspan="3"></th>  
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
        $('#purchasesummary').dataTable({
            aLengthMenu: [
                [500, 1000, 2500, 5000, -1],
                [500, 1000, 2500, 5000, "All"]
            ],
            iDisplayLength: 500,
            "aaSorting": [[0, "desc"]]

        });
    });

    function Clickheretoprint()
    {
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';

        var customername= $("#supplier_id option:selected").text(); 
        var totalbill = parseFloat("<?php echo $Grosstotalbutcost; ?>");  
        var totalpayment = parseFloat("<?php echo $totalpayment; ?>");  
        totalbill = totalbill.toFixed(2);  
        totalpayment = totalpayment.toFixed(2);  
        var from_date = $("#combodateFrom").val();        
        var to_date = $("#combodateTo").val(); 
        $(".hidetoprint").hide();       
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Purchase Summary</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');

        docprint.document.write(comaddress);
       
        docprint.document.write('<h2 style="text-align:center;">Purchase Summary</h2>');
        docprint.document.write('<p style="margin:-10px 0 10px 0px;text-align:center;">Supplier : '+customername+'</p>');
        docprint.document.write('<p style="margin:-10px 0 10px 0px;text-align:center;">Date:'
            +' (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write('<table border="1">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('<tr><th colspan="5" style="text-align: right;">Total:</th><th>'+totalbill+'</th><th>'+totalpayment+'</th><th>'+(totalbill-totalpayment).toFixed(2)+'</th></tr></table>');

        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
        $(".hidetoprint").show(); 
    }

</script>