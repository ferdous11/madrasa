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
                        Transection History 
                    </header>
                    <div class="panel-body">   
                        <div class="clearfix">
                            <form class="tasi-form" method="post" action="<?php echo site_url('bankmanagement/viewtotaltransection'); ?>" style="padding: 10px;margin-bottom: 15px">

                                <div class="col-md-3">
                                    Bank Group
                                    <select class="form-control" name="bankgroup" id="accgroup" onchange="getacnamelist(this.value)">
                                        <?php
                                        if (sizeof($bankgroup) > 0):
                                            foreach ($bankgroup as $bgroup):
                                                ?>
                                                <option <?php echo ($bgroup->id == $groupid) ? 'selected' : ''; ?> value="<?php echo $bgroup->id; ?>"><?php echo $bgroup->groupname; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div> 


                                <div class="col-md-3">
                                    Account Name
                                    <select class="form-control" name="accountid" id="acname">
                                        <option value="all">All</option>
                                        <?php
                                        if (sizeof($accountlist) > 0):
                                            foreach ($accountlist as $aclist):
                                                ?>
                                                <option <?php echo ($aclist->id == $accountid) ? 'selected' : ''; ?> value="<?php echo $aclist->id; ?>"><?php echo $aclist->bankaccountname . '-' . $aclist->bankaccount; ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div> 


                                <div class="col-md-1"><button type="submit" class="btn btn-primary">Submit</button></div>         

                            </form>

                        </div>

                        <table class="display table table-bordered table-striped" id="example4" style="margin-top: 20px">

                            <thead>
                                <tr>   
                                    <th>S.N</th>  
                                    <th>Date</th>
                                    <th>Account ID</th> 
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
                                #$bankpaymentdata = array();
                                if (sizeof($bankpaymentdata) > 0):
                                    foreach ($bankpaymentdata as $bankk):
                                        ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $bankk->date; ?></td>
                                            <td>
                                                <?php
                                                $id = $bankk->bankid;
                                                echo $this->db->get_where('bankaccountlist', array('id' => $id))->row()->bankaccountname;
                                                ?>
                                            </td>
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
                                    <th></th> 
                                    <th><?php echo number_format($paytotal, 2); ?></th>
                                    <th><?php echo number_format($recetotal, 2); ?></th>

                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <?php
                                        if (sizeof($bankpaymentdata) > 0):
                                            $climit = 0;
                                            $limit = $this->db->get_where('bankgroup', array('id' => $groupid))->row();
                                            if (sizeof($limit) > 0):
                                                $climit = $limit->creditlimit;
                                                echo 'Total Credit Limit: ' . number_format($climit, 2) . '<br/>';
                                            else:
                                                echo 'Total Credit Limit: 0.00<br/>';
                                            endif;
                                            ?>
                                            <?php
                                            #$balnce = $recetotal - $paytotal;
                                            $lastlimit = $climit - $recetotal;
                                            echo 'Available Limit: ' . number_format($lastlimit, 2);
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>
            </div>   


        </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
<script>
    function getacnamelist(id) {
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var dataString = "id=" + id + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('bankmanagement/showaccountname'); ?>",
            data: dataString,
            success: function (data)
            {
                //$("#pindivHide").hide();
                $("#acname").html(data);
            }
        });
    }
</script>