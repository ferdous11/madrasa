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
                        Bank Account List
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

                        <span style="float: right"><a href="#" data-toggle="modal" data-target="#addnewbank"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New Bank</button></a></span>

                        <table class="display table table-bordered table-striped" id="example">

                            <thead>
                                <tr>   
                                    <th>S.N</th>
                                    <th>Bank Name</th>
                                    <th>Account Name</th>
                                    <th>Account ID</th>
                                    <th>Account Type</th>
                                    <th>Account Group</th>
                                    <th>Current Balance</th>
                                    <th>Type</th> 
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                $i = 1;
                                if (sizeof($bankdata) > 0):
                                    foreach ($bankdata as $bankk):
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $bankk->bankname; ?></td>
                                            <td><a href="<?php echo site_url('bankmanagement/transection/' . $bankk->id); ?>"><?php echo $bankk->bankaccountname; ?></a></td>
                                            <td><?php echo $bankk->bankaccount; ?></td>
                                            <td style="color: <?php echo ($bankk->accounttype == 'primary') ? 'green' : 'black' ?>;font-weight: bold"><?php echo $bankk->accounttype; ?></td>
                                            <td>
                                                <?php
                                                $groupval = $this->db->get_where('bankgroup', array('id' => $bankk->bankgroup))->row();
                                                if (sizeof($groupval) > 0):
                                                    echo $groupval->groupname;
                                                else:
                                                    echo 'N/A';
                                                endif;
                                                ?>
                                            </td>
                                            <td style="color: <?php echo ($bankk->balance > 0) ? 'green' : 'red' ?>;font-weight: bold">
                                                <?php
                                                $bankid = $bankk->id;
                                                $balanceType = $bankk->balancetype;
                                                $currentOpbalance = $bankk->balance;
                                                $sumdebitbaalnce = $this->db->query("select sum(amount) as totaldebit from bankdeposit where bankid = '$bankid' AND payment_type = 'debit'")->row();
                                                $sumcreditbalnce = $this->db->query("select sum(amount) as totalcredit from bankdeposit where bankid = '$bankid' AND payment_type = 'credit'")->row();
                                                if (sizeof($sumdebitbaalnce) > 0):
                                                    $totaldebit = $sumdebitbaalnce->totaldebit;
                                                else:
                                                    $totaldebit = 0;
                                                endif;
                                                if (sizeof($sumcreditbalnce) > 0):
                                                    $totalcredit = $sumcreditbalnce->totalcredit;
                                                else:
                                                    $totalcredit = 0;
                                                endif;
                                                $netbalance = $totalcredit - $totaldebit;
                                                echo $netbalance;
                                                ?>
                                            </td>                                           
                                            <td><?php echo $bankk->balancetype; ?></td>
                                            <td style="color: <?php echo ($bankk->status == 'active') ? 'green' : 'red' ?>;font-weight: bold"><?php echo $bankk->status; ?></td>
                                            <td>
                                                <a href="#" data-toggle="modal" data-target="#editbank<?php echo $bankk->id; ?>"><i class="fa fa-edit" title="Edit Bank"></i></a>&nbsp;&nbsp;
                                                <a href="<?php echo site_url('bankmanagement/deletebank/' . $bankk->id); ?>" title="Delete Bank" onclick="return confirm('Are you sure want to delete this bank details!!')"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;
                                                <a href="<?php echo site_url('bankmanagement/transection/' . $bankk->id); ?>"><i class="fa fa-eye" title="Transection history"></i></a>
                                            </td>                                                                                                              
                                        </tr>
                                        <?php
                                        $total = $total + $bankk->balance;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>

                        </table>
                    </div>
                </section>
            </div>   

            <!-- 
            Add new bank
            -->
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addnewbank" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 class="modal-title">Add New Bank</h4>
                        </div>

                        <div class="modal-body">

                            <form class="form-horizontal" role="form"action="<?php echo site_url('bankmanagement/addbank'); ?>" method="post" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label for="name" class="col-lg-4 col-sm-4 control-label">Account Type</label>
                                    <div class="col-lg-7">
                                        <select class="form-control" name="accounttype" id="accounttype" required="" onchange="getoption(this.value)">
                                            <option value="">Select Account Type</option>
                                            <option value="primary">Primary</option>
                                            <option value="secondary">Secondary</option>
                                        </select>
                                    </div>
                                </div>  

                                <div class="form-group" id="forprimary">
                                    <label for="name" class="col-lg-4 col-sm-4 control-label">Bank Account Group</label>
                                    <div class="col-lg-7">
                                        <select class="form-control" name="accountgroup" id="accountgroup">
                                            <option value="">Select Bank Group</option>
                                            <?php
                                            $groupname = $this->db->get('bankgroup')->result();
                                            if (sizeof($groupname) > 0):
                                                foreach ($groupname as $bgroup):
                                                    ?>
                                                    <option value="<?php echo $bgroup->id; ?>"><?php echo $bgroup->groupname; ?></option>        
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>

                                        </select>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Bank Name</label>                                   
                                    <div class="col-lg-7">                                    
                                        <select class="form-control" name="bank_name">                                      
                                            <option value="AB Bank Limited">AB Bank Limited</option>
                                            <option value="Al-Arafah Islami Bank">Al-Arafah Islami Bank</option>
                                            <option value="Bangladesh Commerce Bank Limited">Bangladesh Commerce Bank Limited</option>
                                            <option value="Bank Asia Limited">Bank Asia Limited</option>
                                            <option value="BRAC Bank Limited">BRAC Bank Limited</option>
                                            <option value="City Bank Limited">City Bank Limited</option>
                                            <option value="Dhaka Bank Limited">Dhaka Bank Limited</option>
                                            <option value="Dutch-Bangla Bank Limited">Dutch-Bangla Bank Limited</option>
                                            <option value="Eastern Bank Limited">Eastern Bank Limited</option>
                                            <option value="IFIC Bank Limited">IFIC Bank Limited</option>
                                            <option value="Jamuna Bank Limited">Jamuna Bank Limited</option>
                                            <option value="Meghna Bank Limited">Meghna Bank Limited</option>
                                            <option value="Mercantile Bank Limited">Mercantile Bank Limited</option>
                                            <option value="rerereMidland Bank Limitedre">Midland Bank Limited</option>
                                            <option value="Modhumoti Bank Limited">Modhumoti Bank Limited</option>
                                            <option value="Mutual Trust Bank Limited">Mutual Trust Bank Limited</option>
                                            <option value="National Bank Limited">National Bank Limited</option>
                                            <option value="National Credit & Commerce Bank Limited">National Credit & Commerce Bank Limited</option>
                                            <option value="NRB Bank Limited">NRB Bank Limited</option>
                                            <option value="NRB Commercial Bank Limited">NRB Commercial Bank Limited</option>
                                            <option value="NRB Global Bank Limited">NRB Global Bank Limited</option>
                                            <option value="One Bank Limited">One Bank Limited</option>
                                            <option value="Premier Bank Limited">Premier Bank Limited</option>
                                            <option value="Prime Bank Limited">Prime Bank Limited</option>
                                            <option value="Pubali Bank Limited">Pubali Bank Limited</option>
                                            <option value="South Bangla Agriculture & Commerce Bank Limited">South Bangla Agriculture & Commerce Bank Limited</option>
                                            <option value="Southeast Bank Limited">Southeast Bank Limited</option>
                                            <option value="Standard Bank Limited">Standard Bank Limited</option>
                                            <option value="The Farmers Bank Limited">The Farmers Bank Limited</option>
                                            <option value="Trust Bank Limited">Trust Bank Limited</option>
                                            <option value="United Commercial Bank Limited">United Commercial Bank Limited</option>
                                            <option value="Uttara Bank Limited">Uttara Bank Limited</option>
                                            <option value="ASM Group">ASM Group</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Account Name</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" name="bankaccname" id="bankaccname" required="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Account Number</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" name="bankacc" id="bankacc" required="">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Opening Balance</label>
                                    <div class="col-lg-4">
                                        <input type="number" class="form-control" name="opbalance" id="opbalance" required="">
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control" name="balanceType" id="balanceType">
                                            <option value="debit">Debit</option>
                                            <option value="credit">Credit</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Account Status</label>
                                    <div class="col-lg-7">
                                        <select class="form-control" name="acstatus" id="acstatus">
                                            <option value="active">Active</option>
                                            <option value="closed">Closed</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-4 col-lg-7">                                       
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

            <?php
            $i = 1;

            if (sizeof($bankdata) > 0):
                foreach ($bankdata as $cost):
                    ?>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="editbank<?php echo $cost->id; ?>" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 class="modal-title">Update Bank</h4>
                                </div>

                                <div class="modal-body">

                                    <form class="form-horizontal" role="form"action="<?php echo site_url('bankmanagement/updatebank'); ?>" method="post" enctype="multipart/form-data">

                                        <div class="form-group">
                                            <label for="name" class="col-lg-4 col-sm-4 control-label">Account Type</label>
                                            <div class="col-lg-7">
                                                <select class="form-control" name="accounttype" id="accounttype">
                                                    <option <?php echo ($cost->accounttype == 'primary') ? 'selected' : ''; ?> value="primary">Primary</option>
                                                    <option <?php echo ($cost->accounttype == 'secondary') ? 'selected' : ''; ?> value="secondary">Secondary</option>
                                                </select>
                                            </div>
                                        </div>

                                        <?php
                                        if ($cost->accounttype == 'secondary'):
                                            ?>
                                            <div class="form-group">
                                                <label for="name" class="col-lg-4 col-sm-4 control-label">Bank Account Group</label>
                                                <div class="col-lg-7">
                                                    <select class="form-control" name="accountgroup" id="accountgroup">
                                                        <option value="">Select Bank Group</option>
                                                        <?php
                                                        $groupname = $this->db->get('bankgroup')->result();
                                                        if (sizeof($groupname) > 0):
                                                            foreach ($groupname as $bgroup):
                                                                ?>
                                                                <option <?php echo ($cost->bankgroup == $bgroup->id) ? 'selected' : ''; ?> value="<?php echo $bgroup->id; ?>"><?php echo $bgroup->groupname; ?></option>        
                                                                <?php
                                                            endforeach;
                                                        endif;
                                                        ?>

                                                    </select>
                                                </div>
                                            </div> 
                                            <?php
                                        endif;
                                        ?>


                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Bank Name</label>                                   
                                            <div class="col-lg-7">                                    
                                                <select class="form-control selecpicker" name="bank_name" data-live-search="true">                                      
                                                    <option value="<?php echo $cost->bankname; ?>"><?php echo $cost->bankname; ?></option>                                                    
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Account Name</label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" name="bankaccname" id="bankaccname" required="" value="<?php echo $cost->bankaccountname; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Account Number</label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" name="bankacc" id="bankacc" required="" value="<?php echo $cost->bankaccount; ?>">
                                            </div>
                                        </div> 
                                        
                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Opening Balance</label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" name="opbalance" id="opbalance" required="" value="<?php echo $cost->balance; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="bankacc" class="col-lg-4 col-sm-4 control-label">Account Status</label>
                                            <div class="col-lg-7">
                                                <select class="form-control" name="acstatus" id="acstatus">
                                                    <option <option <?php echo ($cost->status == 'active') ? 'selected' : ''; ?> value="active">Active</option>
                                                    <option <option <?php echo ($cost->status == 'closed') ? 'selected' : ''; ?> value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <div class="col-lg-offset-4 col-lg-7">   
                                                <input type="hidden" name="id" value="<?php echo $cost->id; ?>"/>
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

                    <?php
                endforeach;
            endif;
            ?>

        </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
<script>
    $(document).ready(function () {
        $("#forprimary").hide();
    });
    function getoption(typpe) {
        if (typpe == 'primary') {
            $("#forprimary").hide();
        }
        if (typpe == 'secondary') {
            $("#forprimary").show();
        }
    }
</script>