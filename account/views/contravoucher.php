<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">    
        <?php if ($this->session->userdata('success')): ?>
            <div class="alert alert-block alert-success fade in">
                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                <strong>Congratulation!</strong> <?php
                echo $this->session->userdata('success');
                $this->session->unset_userdata('success');
                ?>
            </div> 
        <?php endif; ?>
        <section class="panel">
            <header class="panel-heading">
                Contra Voucher
            </header>
            <div class="panel-body">
                <div class="adv-table">
                    <div class="clearfix">
                        <div class="btn-group pull-right">                           
                            <button  class="btn btn-info" data-toggle="modal" data-target="#myModal">
                                Add New <i class="fa fa-plus"></i>
                            </button>                          
                        </div>                        
                    </div>                      

                    <table class="table table-striped table-hover table-bordered tab-pane active editable-sample1" id="example">
                        <thead>
                            <tr>
                                <th>SN</th>                                                               
                                <th>Voucher No</th>
                                <th>Date</th>
                                <th>Bank Account</th>
                                <th>Type</th>
                                <th>Amount</th>                                
                                <th>Inserted By</th>                                
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>                           
                            <?php
                            
                            if (sizeof($contradata) > 0):
                                $i = 1;
                                foreach ($contradata as $datarow):
                                    ?>
                                    <tr class="">                                               
                                        <td><?php echo ($i++); ?></td>
                                        <td><?php echo ($datarow->id); ?></td>
                                        <td><?php echo ($datarow->date); ?></td>
                                        <td><?php echo $datarow->ledgername;?></td>
                                        <td><?php echo $datarow->type;?></td> 
                                        <td><?php echo ($datarow->amount); ?></td>
                                        <td><?php echo $datarow->user_name; ?></td>
                                          
                                        <td><a href="<?php echo site_url('contravoucher/deletecontravoucher/' . $datarow->id); ?>"  onclick="return confirm('Are you sure want to delete this Voucher !!')" ><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="<?php echo site_url('contravoucher/editcontravoucher/' . $datarow->id); ?>"><i class="fa fa-edit"></i></a>
                                        </td>   
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>    
                        </tbody>
                    </table>  
                </div>

                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Add Contra Voucher</h4>
                            </div>
                            <div class="modal-body">  
                                <form class="cmxform form-horizontal tasi-form" id="" method="post" action="<?php echo site_url('contravoucher/addcontravoucher') ?>">
                                    <div class="row">  
                                        <div class="col-lg-12">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="col-lg-4"></div>
                                                    <div class="col-lg-6" style="padding-left: 0px">
                                                        <div class="radio">
                                                            <label >
                                                                <input type="radio" class="radiobutton" name="optionsRadios" id="optionsRadios1" value="Deposit" checked>
                                                                Deposit
                                                            </label>
                                                        </div>
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" class="radiobutton" name="optionsRadios" id="optionsRadios2" value="Withdraw">
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
                                                        <select class=" form-control selectpicker" data-live-search="true" id="ledgerId" name="ledgerId" required>
                                                            <option value="">Select</option>                                            
                                                            <?php
                                                            foreach ($ledger as $value) {
                                                                ?>
                                                                <option value="<?php echo $value->id; ?>"><?php echo $value->ledgername; ?></option>
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
                                                        <input class="form-control " type="text" id="amount" placeholder="0.00"
                                                               name="amount" required/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12"> 
                                            <div class="form-group">  
                                                <div class="col-lg-4"></div>
                                                <div class="col-lg-6">
                                                    <span id="valuecheck"> </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12" style="padding-left: 0px">
                                            <div class="panel-body">
                                                <div class="form-group ">
                                                    <label for="opening_balance" class="control-label col-lg-4">Date</label>
                                                    <div class="col-lg-6">
                                                        <input class="form-control " name="date" id="cdate" value="<?php echo Date('Y-m-d H:i:s'); ?>"/>
                                                    </div>                               
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12" style="padding-left: 0px">
                                            <div class="panel-body">
                                                <div class="form-group ">
                                                    <label for="opening_balance" class="control-label col-lg-4">Cheque No</label>
                                                    <div class="col-lg-6">
                                                        <input class="form-control" id="chequeNo" name="chequeNo"/>
                                                    </div>                               
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12" style="padding-left: 0px">
                                            <div class="panel-body">
                                                <div class="form-group ">
                                                    <label for="opening_balance" class="control-label col-lg-4">Cheque Date</label>
                                                    <div class="col-lg-6"> 
                                                        <input class="form-control" id="ccdate" name="chequeDate" value="<?php echo Date('Y-m-d'); ?>"/>
                                                    </div>                               
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" onclick="return addamountCheck()">Save</button>                                        
                                        <button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
