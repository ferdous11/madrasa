<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">    
        <section class="panel">
            <header class="panel-heading">
                Contra Voucher
            </header>
            <div class="panel-body">
                <div class="adv-table">

                    <form class="cmxform form-horizontal tasi-form" id="edit<?php echo $rows->id ?>" method="post" action="<?php echo site_url('contravoucher/editcontravoucher2') ?>">
                        <div class="modal-content">

                            <div class="modal-body">  
                                <div class="row">  
                                    <div class="col-lg-12">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-lg-4"></div>
                                                <div class="col-lg-6" style="padding-left: 0px">                                                                
                                                    <div class="radio">
                                                        <label >
                                                            <input type="radio" class="radiobutton" name="optionsRadios" id="optionsRadios1" value="Deposit" <?php echo $rows->type == "Deposit"?'checked':'';?>>
                                                            Deposit
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" class="radiobutton" name="optionsRadios" id="optionsRadios2" value="Withdraw" <?php echo $rows->type == "Withdraw"?'checked':'';?>>
                                                            Withdraw
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style="padding-left: 0px;">
                                        <div class="panel-body">
                                            <div class="form-group" style="padding-top: 3px">
                                                <label for="opening_balance" class="control-label col-lg-4">Bank Account</label>
                                                <div class="col-lg-6">
                                                    <select class=" form-control selectpicker" data-live-search="true" id="editledgerId" name="editledgerId" required>
                                                        <option value="">Select</option>                                            
                                                        <?php
                                                        foreach ($ledger as $value) {
                                                            ?>
                                                            <option <?php echo ($rows->ledger_id == $value->id) ? 'selected' : '' ?> value="<?php echo $value->id; ?>"><?php echo $value->ledgername; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>                               
                                            </div>                           
                                        </div>
                                    </div>
                                      
                                    <div class="col-lg-12" style="padding-left: 0px;"> 
                                        <div class="panel-body">
                                            <div class="form-group ">                                   
                                                <label for="opening_balance"class="control-label col-lg-4">Total Amount</label>
                                                <div class="col-lg-6 ">
                                                    <input class="form-control " type="text" id="editamount<?php echo $rows->id ?>" placeholder="0.00" name="editamount" value="<?php echo ($rows->amount); ?>"required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12"> 
                                        <div class="form-group">  
                                            <div class="col-lg-4"></div>
                                            <div class="col-lg-6">
                                                <span id="valuecheckiddiv<?php echo $rows->id ?>"> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style="padding-left: 0px">
                                        <div class="panel-body">
                                            <div class="form-group ">
                                                <label for="opening_balance" class="control-label col-lg-4">Date</label>
                                                <div class="col-lg-6">
                                                    <input class="form-control " id="cdate" name="editdate" value="<?php echo $rows->date ?>" />
                                                </div>                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12" style="padding-left: 0px">
                                        <div class="panel-body">
                                            <div class="form-group ">
                                                <label for="opening_balance" class="control-label col-lg-4">Cheque No</label>
                                                <div class="col-lg-6">
                                                    <input class="form-control " name="editchequeNo" value="<?php echo ($rows->cheque_no); ?>"/>
                                                </div>                               
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12" style="padding-left: 0px">
                                        <div class="panel-body">
                                            <div class="form-group ">
                                                <label for="opening_balance" class="control-label col-lg-4">Cheque Date</label>
                                                <div class="col-lg-6">
                                                    <input class="form-control " id="ccdate"  name="editchequeDate"  value="<?php echo $rows->cheque_date; ?>"/>
                                                    <input type="hidden" name="editcontravoucherid"  value="<?php echo $rows->id; ?>">
                                                    <input type="hidden" name="previousledger"  value="<?php echo $rows->ledger_id; ?>">
                                                    
                                                </div>                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit"  class="btn btn-primary" onclick="return amountCheck(<?php echo $rows->id ?>)">Save</button>
                                    
                                    <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>              
            </div>
        </section>
    </section>
</section>

<?php include 'footer.php'; ?>

<script type="text/javascript">
    var today = "<?php echo date("Y-m-d H:i:s"); ?>";
    $('#cdate').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'Y-m-d H:i:s',
        disabledDates: ['1986-01-08', '1986-01-09', '1986-01-10'],
        startDate: today,
        minDate: '2017-01-01',
        maxDate: '2050-01-01',
        timepicker: false
    });
    $('#ccdate').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'Y-m-d',
        disabledDates: ['1986-01-08', '1986-01-09', '1986-01-10'],
        startDate: today,
        minDate: '2017-01-01',
        maxDate: '2050-01-01',
        timepicker: false
    });
</script>
