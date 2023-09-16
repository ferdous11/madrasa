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
                        Transection history for <strong><?php echo 'Bank Name: <span style="color:green">' . $bankname->bankname . '</span>-' . 'Account Name: <span style="color:green">' . $bankname->bankaccountname . '</span>-' . 'Account Number: <span style="color:green">' . $bankname->bankaccount . '</span>' . '-Account Type: <span style="color:green">' . $bankname->accounttype . '</span>'; ?></strong>
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
                                <strong>Congratulation!</strong> <?php
                                echo $this->session->userdata('failed');
                                $this->session->unset_userdata('failed');
                                ?>
                            </div> 
                        <?php endif; ?>

                        <span style="float: right"><a href="#" data-toggle="modal" data-target="#addpayment"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add Payment</button></a></span>

                        <table class="display table table-bordered table-striped" id="example4">

                            <thead>
                                <tr>   
                                    <th>S.N</th>  
                                    <th>Date</th>  
                                    <th>Particular</th>
                                    <th>Debit</th> 
                                    <th>Credit</th>                                     
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                $i = 1;
                                $paytotal = 0;
                                $recetotal = 0;
                                if (sizeof($bankpaymentdata) > 0):
                                    foreach ($bankpaymentdata as $bankk):
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $bankk->date; ?></td>    
                                            <td><?php echo $bankk->details; ?></td>     
                                            <td><?php echo ($bankk->payment_type == 'debit') ? $bankk->amount : '0'; ?></td>  
                                            <td><?php echo ($bankk->payment_type == 'credit') ? $bankk->amount : '0'; ?></td>  

                                        </tr>
                                        <?php
                                        if ($bankk->payment_type == 'debit'):
                                            $paytotal = $paytotal + $bankk->amount;
                                        endif;
                                        
                                        if ($bankk->payment_type == 'credit'):
                                            $recetotal = $recetotal + $bankk->amount;
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>   
                                    <th></th>
                                    <th></th>
                                    <th></th>  
                                    <th><?php echo number_format($paytotal, 2); ?></th>
                                    <th><?php echo number_format($recetotal, 2); ?></th>
                                </tr>
                                <tr>
                                    <th colspan="5"><?php echo 'Balance: ' . number_format(($recetotal - $paytotal), 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>
            </div>   

            <!-- 
            Add new bank
            -->
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addpayment" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Add Payment</h4>
                        </div>

                        <div class="modal-body">

                            <form class="form-horizontal" role="form"action="<?php echo site_url('bankmanagement/addpayment'); ?>" method="post" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Payment Type</label>                                   
                                    <div class="col-lg-7">                                    
                                        <select class="form-control" name="paymenttype">                                      
                                            <option value="debit">Payment</option>
                                            <option value="credit">Received</option>
                                            <!--<option value="return">Return</option>-->
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Source Bank & Account</label>
                                    <div class="col-lg-7">
                                        <select class="form-control" name="sourcebank">   
                                            <?php
                                            $primaryBank = $this->db->get_where('bankaccountlist', array('accounttype' => 'primary'))->result();
                                            if (sizeof($primaryBank) > 0):
                                                foreach ($primaryBank as $pbank):
                                                    ?>
                                                    <option value="<?php echo $pbank->id; ?>"><?php echo $pbank->bankaccountname . '-' . $pbank->bankaccount; ?></option>       
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>

                                        </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Destination Bank & Account</label>
                                    <div class="col-lg-7">
                                        <select class="form-control" name="destinationbank">                                    

                                            <?php
                                            $secBank = $this->db->get('bankaccountlist')->result();
                                            if (sizeof($secBank) > 0):
                                                foreach ($secBank as $dbank):
                                                    ?>
                                                    <option value="<?php echo $dbank->id; ?>"><?php echo $dbank->bankaccountname . '-' . $dbank->bankaccount; ?></option>       
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="col-lg-4 col-sm-4 control-label">Amount</label>
                                    <div class="col-lg-7">
                                        <input type="number" class="form-control" name="amount" id="amount" required="" value="0.00"/>
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <label for="name" class="col-lg-4 col-sm-4 control-label">Remarks/Note</label>
                                    <div class="col-lg-7">
                                        <textarea class="form-control" name="note" id="note"></textarea>
                                    </div>
                                </div>  

                                <div class="form-group">
                                    <div class="col-lg-offset-4 col-lg-7">   
                                        <input type="hidden" name="bankid" value="<?php echo $bankid; ?>"/>
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <button type="submit" class="btn btn-default">Submit</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
            <!-- 
            End add new bank
            -->


        </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
