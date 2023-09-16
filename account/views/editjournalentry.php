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
                        Edit Journal Entry
                    </header>
                    <div class="panel-body"> 
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('journalentry/updatedjounal') ?>">

                            <?php
                            foreach ($sortalldata as $rows):
                                $cmpid = $this->session->userdata('company_id');
                                $id = $rows->id;
                                $query = $this->db->query("SELECT sum(debit) as debit ,sum(credit) as credit FROM `ledgerposting` where company_id = '$cmpid' AND (vouchertype='Journal entry' AND voucherid ='$id')");
                                if ($query->num_rows() > 0) {
                                    $value = $query->row();
                                    $debit = $value->debit;
                                    $credit = $value->credit;
                                }
                                ?>

                                <div class="col-lg-12"> 

                                    <div class="form-group">                                          
                                        <div class="col-lg-5">
                                            <div class="col-lg-3"> </div>
                                            <div class="col-lg-9">
                                                <label  for="accountledger">Account Ledger</label>
                                            </div>
                                        </div>                                    
                                        <div class="col-lg-3">
                                            <label  for="debit"> &nbsp; Debit</label>
                                        </div>
                                        <div class="col-lg-3">
                                            <label  for="credit"> &nbsp;&nbsp; Credit</label>
                                        </div>                                  
                                    </div>                           

                                </div>
                                <div class="col-lg-12" style="padding-left: 0px;">

                                    <div class="form-group" style="padding-top: 3px">   

                                        <?php
                                        foreach ($getidValues as $idrows):
                                            ?>

                                            <div class="col-lg-5">
                                                <div class="col-lg-3"> </div>
                                                <div class="col-lg-5">
                                                    <select class="form-control selectpicker" data-live-search="true" id="edit_ledgerId" name="edit_ledgerId[]" required>
                                                        <option value="">Select</option>
                                                        <?php
                                                        foreach ($ledger as $value):
                                                            ?>                                                                                
                                                            <option <?php echo ($idrows->ledgerid == $value->id) ? ' selected' : '' ?> value="<?php echo $value->id; ?>"><?php echo $value->id . ' - ' . $value->ledgername; ?></option>;
                                                            <?php
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3"> </div>
                                            </div>                                    
                                            <div class="col-lg-3">
                                                <div class="col-lg-7">
                                                    <input type="text" id="editdebit"  name="editdebit[]" value="<?php echo ($idrows->debit); ?>" class="form-control editdebit<?php echo $jmasterId; ?>"  placeholder="0.00" onchange="adddebitedit(<?php echo $jmasterId; ?>)">
                                                </div>
                                                <div class="col-lg-4"> </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="col-lg-7">
                                                    <input type="text" id="editcredit"  name="editcredit[]" value="<?php echo ($idrows->credit); ?>"class="form-control editcredit<?php echo $jmasterId; ?>"  placeholder="0.00" onchange="addcreditedit(<?php echo $jmasterId; ?>)">
                                                </div>
                                                <div class="col-lg-4"> </div>
                                            </div> 
                                            <div class="clearfix"></div>
                                            <input type="hidden" name="JournalMasterID" id="JournalMasterID" value="<?php echo $jmasterId ?>">
                                            <input type="hidden" name="journalDetailsId[]" id="journalDetailsId" value="<?php echo $idrows->id; ?>">                                                                    
                                            <?php
                                        endforeach;
                                        foreach ($getLedgerDataValues as $gledger):
                                            ?>
                                            <input type="hidden" name="ledgerPostingId[]" id="ledgerPostingId" value="<?php echo $gledger->id; ?>">
                                            <?php
                                        endforeach;
                                        ?>
                                    </div>                           
                                    <div class="clearfix"></div>
                                </div> 

                                <!-- <div class="col-lg-12" style="padding-left: 0px;font-weight:700;"> 

                                    <div class="form-group ">
                                        <label for="opening_balance" style="padding-top: 8px;font-weight:700;"class="control-label col-lg-5">Total</label>
                                        <div class="col-lg-3">
                                            <div class="col-lg-8">
                                                <input style="border:1px solid #0A0101" class="form-control " type="text" id="edittotal_debit<?php echo $rows->id ?>" placeholder="0.00" value="<?php echo $debit ?>" name="edittotal_debit" readonly required/> 
                                            </div>
                                            <div class="col-lg-4"> </div>
                                        </div>                                                                
                                        <div class="col-lg-3 ">
                                            <div class="col-lg-8">
                                                <input style="border:1px solid #0A0101" class="form-control " type="text" id="edittotal_credit<?php echo $rows->id ?>" placeholder="0.00" value="<?php echo $credit ?>" name="edittotal_credit" readonly required/>
                                            </div>
                                            <div class="col-lg-4"> </div>
                                        </div>
                                    </div>

                                </div> -->
                                <div class="col-lg-12"> 
                                    <div class="form-group">  
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-8">
                                            <span id="valuecheckiddiv<?php echo $rows->id ?>">  </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12" style="padding-left: 0px">
                                    <div class="panel-body">
                                        <div class="form-group ">
                                            <label for="opening_balance" class="control-label col-lg-5">Description</label>
                                            <div class="col-lg-6">
                                                <div class="col-lg-10">
                                                    <textarea class="form-control " id="editdescription<?php echo $rows->id ?>" name="editdescription" cols="30" rows="3"><?php echo $rows->description; ?></textarea>
                                                </div>
                                                <div class="col-lg-2"> </div>
                                            </div>                               
                                        </div>
                                    </div>
                                </div>
                                &nbsp;
                                <div class="col-lg-12" style="padding-left: 0px"> 
                                    <div class="form-group ">
                                        <label for="opening_balance" class="control-label col-lg-5">Date</label>
                                        <div class="col-lg-6">
                                            <div class="col-lg-10">
                                                <input class="form-control " id="sdate" name="editdate" value="<?php echo $rows->date; ?>"/>
                                            </div>
                                            <div class="col-lg-2"> </div>
                                        </div>
                                        <p> &nbsp; </p>
                                    </div>
                                </div>
                                <div class="col-lg-12" style="padding-left: 0px"> 
                                    <div class="form-group ">
                                        <label for="opening_balance" class="control-label col-lg-5"></label>
                                        <div class="col-lg-5" style="margin-top: 10px">
                                            <button type="submit"  class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </form>
                    </div>
                </section>
            </div>          
        </div>


        <!-- page end-->
    </section>
</section>

<?php include 'footer.php'; ?>
<script>
    function amountCheck(id) {
        var totalcredit = parseFloat($('#edittotal_credit' + id).val()) || 0;
        var totaldebit = parseFloat($('#edittotal_debit' + id).val()) || 0;
        if (totaldebit === 0) {
            $('#valuecheckiddiv' + id).html("Please Enter Debit Amount");
            $('#valuecheckiddiv' + id).css('color', 'red');
            return false;
        }
        if (totalcredit === 0)
        {
            $('#valuecheckiddiv' + id).html("Please Enter Credit Amount");
            $('#valuecheckiddiv' + id).css('color', 'red');
            return false;
        }
        if (totaldebit === totalcredit) {
            return true;
        }
        else {
            $('#valuecheckiddiv' + id).html("Debit & Credit are not equal.");
            $('#valuecheckiddiv' + id).css('color', 'red');
            return false;
        }

    }
    function addamountCheck() {
        var totalcredit = parseFloat($('#total_credit').val()) || 0;
        var totaldebit = parseFloat($('#total_debit').val()) || 0;
        if (totaldebit === 0) {
            $('#valuecheck').html("Please Enter Debit Amount");
            $('#valuecheck').css('color', 'red');
            return false;
        }
        if (totalcredit === 0)
        {
            $('#valuecheck').html("Please Enter Credit Amount");
            $('#valuecheck').css('color', 'red');
            return false;
        }
        if (totaldebit === totalcredit) {
            return true;
        }
        else {
            $('#valuecheck').html("Debit & Credit are not equal.");
            $('#valuecheck').css('color', 'red');
            return false;
        }

    }

    function adddebit() {
        var inputs = document.getElementsByClassName('debit'),
                names = [].map.call(inputs, function (input) {
            return input.value;
        });
        var sll = names.length;
        var i, total = 0;
        for (i = 0; i < sll; i++) {
            total = total + (parseFloat(names[i]) || 0);
        }
        $('#total_debit').val(total);
    }
    function addcredit() {
        var inputs = document.getElementsByClassName('credit'),
                names = [].map.call(inputs, function (input) {
            return input.value;
        });
        var sll = names.length;
        var i, total = 0;
        for (i = 0; i < sll; i++) {
            total = total + (parseFloat(names[i]) || 0);
        }
        $('#total_credit').val(total);
    }
    function adddebitedit(id) {
        var inputs = document.getElementsByClassName('editdebit' + id),
                names = [].map.call(inputs, function (input) {
            return input.value;
        });
        var sll = names.length;
        var i, total = 0;
        for (i = 0; i < sll; i++) {
            total = total + (parseFloat(names[i]) || 0);
        }
        $('#edittotal_debit' + id).val(total);
    }
    function addcreditedit(id) {
        var inputs = document.getElementsByClassName('editcredit' + id),
                names = [].map.call(inputs, function (input) {
            return input.value;
        });
        var sll = names.length;
        var i, total = 0;
        for (i = 0; i < sll; i++) {
            total = total + (parseFloat(names[i]) || 0);
        }
        $('#edittotal_credit' + id).val(total);
    }
</script>