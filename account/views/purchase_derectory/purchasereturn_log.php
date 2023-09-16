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
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('purchase/purchasereturnhistory'); ?>">

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
                        Purchase Return History
                    </header>
                    <div class="panel-body">

                        <div class="form">
                            <table class="display table table-bordered table-striped dataTable" id="example">
                                <thead>
                                    <tr> 
                                        <th>Invoice Id</th>
                                        <th>Date</th>
                                        <th>Customer Name</th>  
                                        <th>Total Price</th> 
                                        <th>Inserted By</th> 
                                        <th>Action</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $totalbill = 0;
                                    // && $sell->date>date("Y-m-d")
                                    if (sizeof($selldata) > 0):
                                        foreach ($selldata as $sell):
                                            ?>
                                            <tr>
                                                <td><?php echo "Pr-". sprintf("%06d", $sell->id); ?></td>
                                                <td><?php echo $sell->date; ?></td>   
                                                <td><?php echo $sell->ledgername."(".$sell->address.",".$sell->district_name.")"; ?></td>
                                                <td><?php echo $sell->total_purchase;?></td>                                              
                                                <td><?php echo $sell->fullname;?></td>                                              
                                                <td>
                                                    <a style="width:40px" target="_blank"  class='col-lg-3  label label-success'  href="<?php echo site_url('purchase/showpurchasereturn/' . $sell->invoiceid) ?>">Show</a>
                                                    <?php $datef= date('Y-m-d'); if ($role=='admin'  ||  ($sell->date>$datef." 00:00:00" && $sell->date<$datef." 23:59:59")):?> 
                                                        <a target="_blank" class="col-lg-3 col-lg-offset-1 label label-danger" href="<?php echo site_url('purchase/editpurchasereturn/'.$sell->invoiceid); ?>" title="View details of Sales Return">Edit</a>

                                                        <a onclick="return confirm('Are you sure to Permanently delete this Purchase Return Voucher <?php echo "Pr-". sprintf("%06d", $sell->id) ; ?> !!')" class="col-lg-3 col-lg-offset-1 label label-danger" href="<?php echo site_url('purchase/deletepurchasereturn/' . $sell->invoiceid); ?>" title="Delete Sales Return">Delete</a>
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
