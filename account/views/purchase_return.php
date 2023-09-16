<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Purchase return
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

                            <form class="form-horizontal" role="form" action="<?php echo site_url('purchase/findpurchase'); ?>" method="post">

                                <br/>
                                <div class="form-group">

                                    <label for="invoiceid" class="col-lg-2 col-sm-2 control-label">মেমো নং</label>
                                    <div class="col-lg-3 col-sm-3">
                                        <select class="form-control selectpicker" name="invoiceid" data-live-search="true">
                                            <?php
                                            $comid = $this->session->userdata('company_id');
                                            $invoiceidp = $this->db->query("select invoiceid from purchase where company_id = '$comid' group by invoiceid")->result();
                                            if (sizeof($invoiceidp) > 0):
                                                foreach ($invoiceidp as $invoid):
                                                    ?>
                                                    <option <?php echo ($invoid->invoiceid == $invoiceid) ? 'selected' : '' ?> value="<?php echo $invoid->invoiceid; ?>"><?php echo ($invoid->invoiceid); ?></option>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                        <span style="color: red" id="error_invoiceId"></span>
                                    </div>

                                    <div class="col-lg-2 col-sm-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div> 

                                </div>

                            </form>                           
                        </div>

                        <div class="form">
                            <table class="display table table-bordered table-striped dataTable">
                                <thead>
                                    <tr>                                    
                                        <th>তারিখ</th>  
                                        <th>পণ্যের নাম</th>                                        
                                        <th>দর</th>
                                        <th>পরিমান</th>
                                        <th>একক</th>
                                        <th>মোট</th>   
                                        <th>পরিবর্তন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $totalsell = 0;
                                    if (sizeof($purchasedata) > 0):
                                        foreach ($purchasedata as $purchase):
                                            ?>
                                            <tr>
                                                <td><?php echo ($purchase->date); ?></td>                                                 
                                                <td><?php echo $purchase->pname; ?></td>                                               
                                                <td><?php echo (number_format($purchase->buyprice, 2)); ?></td>
                                                <td><?php echo ($purchase->quantity); ?></td>
                                                <td><?php echo $purchase->name; ?></td>
                                                <td><?php echo (number_format($purchase->total_buyprice, 2)); ?></td>
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#editpurchase<?php echo $purchase->id ?>" class="btn btn-primary"><i class="fa fa-edit"></i>&nbsp; Edit</a>&nbsp; 
                                                </td>
                                            </tr>
                                            <?php
                                            $totalsell = $totalsell + $purchase->total_buyprice;
                                        endforeach;
                                    endif;
                                    ?>

                                </tbody>
                                <tfoot>
                                    <tr>    
                                        <th></th>
                                        <th></th>
                                        <th></th>                                         
                                        <th></th>
                                        <th></th>
                                        <th><?php echo (number_format($totalsell, 2)); ?></th>  
                                        <th></th>   
                                    </tr>
                                </tfoot>
                            </table>
                        </div>


                        <?php
                        if (sizeof($purchasedata) > 0):
                            foreach ($purchasedata as $selld):
                                ?>
                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editpurchase<?php echo $selld->id; ?>" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                <h4 class="modal-title">
                                                    ক্রয় ফেরত
                                                </h4>
                                            </div>

                                            <div class="modal-body">

                                                <form class="form-horizontal" role="form"action="<?php echo site_url('purchase/purchase_return_submit'); ?>" method="post" enctype="multipart/form-data">

                                                    <div class="form-group">
                                                        <label for="invoiceid" class="col-lg-4 col-sm-4 control-label">মেমো নং</label>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input type="text" class="form-control" name="invoiceid" id="invoiceid" value="<?php echo ($selld->invoiceid); ?>" readonly="" required="">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="buyprice" class="col-lg-4 col-sm-4 control-label">দর</label>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input type="text" class="form-control" name="buyprice" id="buyprice" value="<?php echo ($selld->buyprice); ?>" required="" readonly="">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="quantity" class="col-lg-4 col-sm-4 control-label">পরিমান</label>
                                                        <div class="col-lg-5 col-sm-5" >
                                                            <input type="text" class="form-control" name="quantity" id="quantity<?php echo $selld->id; ?>" value="<?php echo ($selld->quantity); ?>" required="" readonly="">
                                                        </div>
                                                        <div class="col-lg-3 col-sm-3">
                                                            <input type="text" class="form-control" value="<?php echo $selld->name; ?>" required="" readonly="">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="quantity" class="col-lg-4 col-sm-4 control-label">ফেরতের পরিমান</label>
                                                        <div class="col-lg-5 col-sm-5">
                                                            <input type="text" class="form-control" name="rquantity" id="rquantity<?php echo $selld->id; ?>" required="" autofocus="" onchange="checkqty('<?php echo $selld->id; ?>')">
                                                            <span style="color: red" id="rqty_error<?php echo $selld->id; ?>"></span>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-3">
                                                            <input type="text" class="form-control" value="<?php echo $selld->name; ?>" required="" readonly="">
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <div class="col-lg-offset-4 col-lg-8">
                                                            <input type="hidden" name="pid" value="<?php echo $selld->id; ?>"/>
                                                            <input type="hidden" name="invoiceid" value="<?php echo $selld->invoiceid; ?>"/>
                                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                            <input type="submit" class="btn btn-default" id="mainsubmit<?php echo $selld->id; ?>" value="Submit" onclick="return checkqty('<?php echo $selld->id; ?>')"/>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>

                                                </form>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        endif;
                        ?>

                    </div>
                </section>
            </div>          
        </div>


        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>


<script>
    function checkqty(id) {
        var raw = $("#quantity" + id).val();
        var rraw = $("#rquantity" + id).val();
        
        if (parseInt(rraw) < '1') {
            $("#mainsubmit" + id).prop('disabled', true);
            $("#rqty_error" + id).text("Invalid quantity selection");
            return false;
        }
        if (parseInt(rraw) > parseInt(raw)) {
            $("#mainsubmit" + id).prop('disabled', true);
            $("#rqty_error" + id).text("Invalid quantity selection");
            return false;
        } else {
            $("#mainsubmit" + id).prop('disabled', false);
            $("#rqty_error" + id).text("");
            return true;
        }
    }
</script>