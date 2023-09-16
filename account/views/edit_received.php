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
                    <div class="panel-heading">Received Update</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form"action="<?php echo site_url('received/update'); ?>" method="post" enctype="multipart/form-data">


                            <div class="form-group">
                                <label for="product_id" class="col-lg-3 col-sm-4 control-label"> Account Name</label> 
                                <div class="col-sm-4">
                                <select class="form-control selectpicker" data-live-search="true" name="ledgerid" required="">
                                    
                                    <?php
                                    if (sizeof($getledger) > 0):
                                        foreach ($getledger as $ledger):
                                            ?>
                                            <option <?php echo ($ledger->id==$ledgerid)? "selected" : "";?> value="<?php echo $ledger->id; ?>"><?php echo ($ledger->accountgroupid==16||$ledger->accountgroupid==15)? $ledger->ledgername." (".$ledger->address.", ".$ledger->district_name.")":$ledger->ledgername; ?></option>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </select>                                      
                       
                                </div>
                            </div>
                                <div class="form-group">
                                <label for="amount" class="col-lg-3 col-sm-4 control-label">Amount</label>
                                <div class="col-lg-2">
                                    <input style="height: 40px;font-size: 30px;" type="text" pattern="[0-9,]+" onkeyup="setcomma(this)"  class="form-control" name="amount" maxlength="50" id="amount" value="<?php echo moneyFormatIndia(ceil($amount));?>" required="">
                                </div>
                                </div>
                                <div class="form-group">
                                <label  for="description" class="col-lg-3 col-sm-4 control-label">Comment</label>
                                <div class="col-lg-3">
                                    <textarea class="form-control" name="description" id="description"><?php echo $comment;?></textarea>
                                </div>
                                </div>
                                <input  type="hidden" class="form-control" name="invoiceid" id="invoiceid" value="<?php echo $received_id; ?>" required="">

                                <div class="form-group">
                                    <label for="amount" class="col-lg-3 col-sm-4 control-label">Received Date</label>
                                    <div class="col-lg-2">
                                        <input type="text" autocomplete="off" class="form-control" name="date" maxlength="50" value="<?php echo $date;?>" id="purdate" required="">
                                    </div>
                                </div>

                            <div class="form-group">
                                <div class="col-lg-offset-3 col-lg-6">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <button type="submit" class="btn btn-primary">Submit</button> 
                                    <a href="<?php echo site_url('received'); ?>"><button type="button" class="btn btn-info">Back</button></a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- page end-->

    </section>
</section>

<?php include 'footer.php'; ?>
    
<script>
    function setcomma(e) {
        if(e.value.slice(-1)!='.' && e.value!=''){
            let tmp = e.value.replaceAll(',','');
            let num = Number(tmp);
            var rej = num.toLocaleString('en-IN'); 
            e.value=rej;
        }
    }
</script>