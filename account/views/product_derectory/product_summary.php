<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<style>
    tr  td {text-align:right;}
</style>

    

<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-body">
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('product/summary'); ?>">

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

                            <div class="col-lg-2">                           
                                <div class="form-group" style="margin-right: 0px">     
                                    <label class="control-label"><br/></label>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input style="margin-top: 25px;margin-left: 20px" name="submit" class="btn btn-primary" type="submit" value="Submit"/>
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
                        Products Summary <span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
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
                                        <th rowspan="2">Sl.No.</th>
                                        <th rowspan="2">Product ID</th>
                                        <th rowspan="2">Product Name</th>  
                                        <th rowspan="2">Unit</th>  
                                        <th colspan="3">Sales</th>
                                        <th colspan="3">Purchase</th>
                                        <th colspan="3">Sales Return</th>
                                        <th colspan="3">Purchase Return</th>
                                    </tr>
                                    <tr> 
                                        <th>Qty</th>
                                        <th>Avg</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Avg</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Avg</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Avg</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody id="invoicediv">
                                    <?php

                                    if (sizeof($product) > 0): $tpurchase=$tsales=$tpurchaser=$tsalesr=0; $i=1;
                                        foreach ($product as $p):
                                            if(array_key_exists($p->id, $purchaseQ)||array_key_exists($p->id, $saleQ)||array_key_exists($p->id, $purchaseReturnQ)||array_key_exists($p->id, $saleReturnQ)):?>
                                            <tr class="t-r">
                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $p->product_id;?></td>
                                                <td><?php echo $p->product_name; echo $p->category_id==6?"(Package)":"";?></td>
                                                <td><?php echo $p->name;?></td>
                                                <?php if(array_key_exists($p->id, $saleQ)):?>
                                                    <td><?php echo number_format($saleQ[$p->id],2);?></td>
                                                    <td><?php echo number_format($saleP[$p->id]/$saleQ[$p->id],2);?></td>
                                                    <td><?php echo number_format($saleP[$p->id]);$tsales+=$saleP[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;if(array_key_exists($p->id, $purchaseQ)):?>
                                                    <td><?php echo number_format($purchaseQ[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchaseP[$p->id]/$purchaseQ[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchaseP[$p->id]);$tpurchase+=$purchaseP[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;if(array_key_exists($p->id, $saleReturnQ)):?>
                                                    <td><?php echo number_format($saleReturnQ[$p->id],2);?></td>
                                                    <td><?php echo number_format($saleReturnP[$p->id]/$saleReturnQ[$p->id],2);?></td>
                                                    <td><?php echo number_format($saleReturnP[$p->id]);$tsalesr+=$saleReturnP[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;if(array_key_exists($p->id, $purchaseReturnQ)):?>
                                                    <td><?php echo number_format($purchaseReturnQ[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchaseReturnP[$p->id]/$purchaseReturnQ[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchaseReturnP[$p->id]);$tpurchaser+=$purchaseReturnP[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;?>
                                            </tr>

                                            <?php endif;if(array_key_exists($p->id, $purchaseQR)||array_key_exists($p->id, $saleQR)||array_key_exists($p->id, $purchaseReturnQR)||array_key_exists($p->id, $saleReturnQR)):?>
                                            <tr class="t-r">
                                                <td><?php echo $i++;?></td> 
                                                <td><?php echo $p->product_id;?></td>
                                                <td><?php echo $p->product_name; echo $p->category_id==6?"(Refil)":"";?></td>
                                                <td><?php echo $p->name;?></td>
                                                <?php if(array_key_exists($p->id, $saleQR)):?>
                                                    <td><?php echo number_format($saleQR[$p->id],2);?></td>
                                                    <td><?php echo number_format($salePR[$p->id]/$saleQR[$p->id],2);?></td>
                                                    <td><?php echo number_format($salePR[$p->id]);$tsales+=$salePR[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;if(array_key_exists($p->id, $purchaseQR)):?>
                                                    <td><?php echo number_format($purchaseQR[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchasePR[$p->id]/$purchaseQR[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchasePR[$p->id]);$tpurchase+=$purchasePR[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;if(array_key_exists($p->id, $saleReturnQR)):?>
                                                    <td><?php echo number_format($saleReturnQR[$p->id],2);?></td>
                                                    <td><?php echo number_format($saleReturnPR[$p->id]/$saleReturnQR[$p->id],2);?></td>
                                                    <td><?php echo number_format($saleReturnPR[$p->id]);$tsalesr+=$saleReturnPR[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;if(array_key_exists($p->id, $purchaseReturnQR)):?>
                                                    <td><?php echo number_format($purchaseReturnQR[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchaseReturnPR[$p->id]/$purchaseReturnQR[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchaseReturnPR[$p->id]);$tpurchaser+=$purchaseReturnPR[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;?>
                                            </tr>
                                        <?php endif;if(array_key_exists($p->id, $purchaseQE)||array_key_exists($p->id, $saleQE)||array_key_exists($p->id, $purchaseReturnQE)||array_key_exists($p->id, $saleReturnQE)):?>
                                            <tr class="t-r">
                                                <td><?php echo $i++;?></td>
                                                <td><?php echo $p->product_id;?></td>
                                                <td><?php echo $p->product_name; echo $p->category_id==6?"(Empty)":"";?></td>
                                                <td><?php echo $p->name;?></td>
                                                <?php if(array_key_exists($p->id, $saleQE)):?>
                                                    <td><?php echo number_format($saleQE[$p->id],2);?></td>
                                                    <td><?php echo number_format($salePE[$p->id]/$saleQE[$p->id],2);?></td>
                                                    <td><?php echo number_format($salePE[$p->id]);$tsales+=$salePE[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;if(array_key_exists($p->id, $purchaseQE)):?>
                                                    <td><?php echo number_format($purchaseQE[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchasePE[$p->id]/$purchaseQE[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchasePE[$p->id]);$tpurchase+=$purchasePE[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;if(array_key_exists($p->id, $saleReturnQE)):?>
                                                    <td><?php echo number_format($saleReturnQE[$p->id],2);?></td>
                                                    <td><?php echo number_format($saleReturnPE[$p->id]/$saleReturnQE[$p->id],2);?></td>
                                                    <td><?php echo number_format($saleReturnPE[$p->id]);$tsalesr+=$saleReturnPE[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;if(array_key_exists($p->id, $purchaseReturnQE)):?>
                                                    <td><?php echo number_format($purchaseReturnQE[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchaseReturnPE[$p->id]/$purchaseReturnQE[$p->id],2);?></td>
                                                    <td><?php echo number_format($purchaseReturnPE[$p->id]);$tpurchaser+=$purchaseReturnPE[$p->id];?></td>
                                                <?php else:?>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                <?php endif;?>
                                            </tr>
                                            
                                    <?php  endif;  endforeach;
                                    endif;
                                    ?>
                                </tbody>
                                <tfoot id="printtohide">
                                    <tr>
                                        <td colspan="4"></td>
                                        <td colspan="2">Total:</td>
                                        <td><?php echo number_format($tsales)?></td>
                                        <td colspan="2">Total:</td>
                                        <td><?php echo number_format($tpurchase)?></td>
                                        <td colspan="2">Total:</td>
                                        <td><?php echo number_format($tsalesr)?></td>
                                        <td colspan="2">Total:</td>
                                        <td><?php echo number_format($tpurchaser)?></td>
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
            iDisplayLength: -1
        });
        $("#foradvanced").hide();
    });

    function Clickheretoprint()
    {
   
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';

        var tsalesr ='<?php echo $tsalesr;?>';
        var tsales ='<?php echo $tsales;?>';
        var tpurchaser ='<?php echo $tpurchaser;?>';
        var tpurchase ='<?php echo $tpurchase;?>';

        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val(); 
        $("#printtohide").hide();
     
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Product Summary</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');

        docprint.document.write(comaddress);
        
        docprint.document.write('<h2 style="margin:-10px 0 10px 0px;text-align:center;">Product Summary</h2>');
    
        docprint.document.write('<p style="margin:-10px 0 10px 0px;text-align:center;">Date:'
            +' (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write('<table border="1">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('<tr><th colspan="4"></th><th colspan="2">Total:</th><th>'+tsales+'</th><th colspan="2">Total:</th><th>'+tpurchase+'</th><th colspan="2">Total:</th><th>'+tsalesr+'</th><th colspan="2">Total:</th><th>'+tpurchaser+'</th></tr>');
        docprint.document.write('<table></center></body></html>');
        docprint.document.close();
        docprint.print();
        $("#printtohide").show();
        docprint.close();
        
    }
</script>

