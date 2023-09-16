<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->

        <div class="row">
            <div class="col-lg-8 col-sm-8">

                <section class="panel">
                    <header class="panel-heading">
                        Receipt Voucher
                    </header>

                    <div class="panel-body">
                        <?php if ($this->session->userdata('success')) : ?>
                            <div class="alert alert-block alert-success fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Congratulation!</strong> <?php
                                                                    echo $this->session->userdata('success');
                                                                    $this->session->unset_userdata('success');
                                                                    ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->userdata('failed')) : ?>
                            <div class="alert alert-block alert-danger fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Oops!</strong> <?php
                                                        echo $this->session->userdata('failed');
                                                        $this->session->unset_userdata('failed');
                                                        ?>
                            </div>
                        <?php endif; ?>

                        <form class="form-horizontal" role="form" action="<?php echo site_url('received/temp_payment'); ?>" method="post" enctype="multipart/form-data">
                            <div class="form">
                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="ledgerid" class="col-lg-3 col-sm-4 control-label"> Account Name</label>
                                        <div class="col-sm-7">
                                        <select class="form-control selectpicker" data-live-search="true" name="ledgerid" onchange="getuserdetails(this.value)" required="" tabindex="1">
                                            <option value="">-Select-</option>
                                            <?php
                                            if (sizeof($getledger) > 0) :
                                                foreach ($getledger as $ledger) :
                                            ?>
                                            <option value="<?php echo $ledger->id; ?>"><?php echo substr(($ledger->accountgroupid == 15 || $ledger->accountgroupid == 16 || $ledger->accountgroupid == 18) ? $ledger->groupname . "-" . $ledger->ledgername . "(" . $ledger->mobile . ")" . "(" . $ledger->address . "," . $ledger->district_name . ")" : $ledger->groupname . "-" . $ledger->ledgername, 0, 145); ?></option>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>

                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-3">
                                    <label for="amount">Payable Amount</label>
                                        <input style="height: 40px;font-size: 30px;" type="text" class="form-control"  tabindex="5" value="" name="payamount" id="payamount" readonly/>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="amount">Amount</label>
                                        <input style="height: 40px;font-size: 30px;" type="text" pattern="[0-9,]+" class="form-control" name="amount" id="amount" tabindex="2" onkeyup="setcomma(this)" required />
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="description">Comment</label>
                                        <textarea class="form-control" tabindex="3" name="description" id="description"></textarea>
                                    </div>

                                    <input type="hidden" class="form-control" name="invoiceid" id="invoiceid" value="<?php echo $randomkey; ?>" required="">

                                    <div class="col-sm-3">
                                        <label for="submit"></label>
                                        <button  tabindex="4" style="height: 40px;" type="submit" id="submit" class="btn btn-primary form-control">Add</button>
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    </div>
                                </div>   
                                </div>
                        </form>
                    </div>
                </section>

            </div>
            <div class="col-lg-4 col-sm-4">
                <section class="panel">
                    <header class="panel-heading">
                        Incomplete Record
                    </header>
                    <div class="panel-body">
                        <table class="display table table-bordered table-striped dataTable">
                            <thead>
                                <tr>
                                    <th>Invoice Id</th>
                                    <th>No of Account</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                                <?php foreach ($uncomlitelist as $item) : ?>
                                    <tr>
                                        <td><a href="<?php echo site_url('received/showtemp?randomkey=' . $item->invoiceid); ?>"><?php echo $item->invoiceid; ?></a></td>
                                        <td><?php echo $item->titem; ?></td>
                                        <td><?php echo $item->tprice; ?></td>
                                        <td><a href="<?php echo site_url('received/tempremove?randomkey=' . $item->invoiceid); ?>"><i class="fa fa-trash-o" title="Remove purchase"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>

                            </thead>
                        </table>
                    </div>
                </section>
            </div>
            <form role="form" action="<?php echo site_url('received/addpayments'); ?>" method="post" enctype="multipart/form-data">
                <div class="form col-lg-8">
                    <table class="display table table-bordered table-striped dataTable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Ledger Name</th>
                                <th>Amount</th>
                                <th>Comment</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $s = 1;
                            $total = 0;
                            if (sizeof($currentlist) > 0) :

                                foreach ($currentlist as $item) :
                                    $total += $item->amount;
                            ?>
                                    <tr>
                                        <td><?php echo ($s++); ?></td>

                                        <td><?php echo ($item->accountgroupid == 16 || $item->accountgroupid == 15) ? $item->ledgername . " (" . $item->address . ", " . $item->district_name . ")" : $item->ledgername; ?></td> <input type="hidden" name="ledgerid[]" value="<?php echo $item->ledgerid; ?>">

                                        <td style="text-align: right;"><?php echo moneyFormatIndia(ceil($item->amount)); ?></td><input type="hidden" name="amount[]" value="<?php echo $item->amount; ?>">


                                        <td><?php echo $item->description; ?>
                                        </td><textarea style="display: none;" name="comment[]"><?php echo $item->description; ?></textarea>
                                        <td><a href="<?php echo site_url('received/removedata?id=' . $item->id . '&randomkey=' . $randomkey); ?>"><i class="fa fa-trash-o" title="Remove purchase"></i></a></td>
                                    </tr>
                            <?php

                                endforeach;
                            endif;
                            ?>
                            <tr>
                                <td colspan="2">Total:</td>
                                <td style="font-weight: bold"><?php echo moneyFormatIndia(ceil($total)) . " Tk."; ?></td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="amount" class="col-lg-4 col-sm-4 control-label">Received Date</label>
                        <div class="col-lg-7">
                            <input type="text" autocomplete="off" class="form-control" name="date" value="<?php echo date('Y-m-d H:i:s') ?>" <?php echo $this->session->userdata('role') == 'admin' ? 'id="purdate"' : 'readonly'; ?> required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-4">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="randomkey" value="<?php echo $randomkey; ?>">
                            <button type="submit" class="btn btn-primary" id="finalpaymentbtn">Save</button>

                        </div>
                    </div>
                </div>

            </form>

            <table class="display table table-bordered table-striped dataTable" id="paymenthistory2">

                <thead>
                    <tr>
                        <th>S.N.</th>
                        <!-- <th>Invoice ID</th>                                         -->
                        <th>Date</th>
                        <th>Ledger Name</th>
                        <th>Amount</th>
                        <th>Comment</th>
                        <th>Inserted By</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $i = 1;
                    $totalamount = 0;
                    if (sizeof($payments) > 0) :
                        foreach ($payments as $payment) :
                    ?>
                            <tr>
                                <td><?php echo "Rec-" . sprintf("%06d", $payment->id); ?></td>
                                <!-- <td><?php echo ($payment->invoiceid); ?></td> -->
                                <td><?php echo ($payment->date); ?></td>
                                <td>
                                    <?php
                                    $party = $this->db->query("select a.ledgername,a.address,a.accountgroupid,d.name as district_name from accountledger as a left join districts as d on a.district=d.id where a.id='$payment->ledgerid'")->row();
                                    echo ($party->accountgroupid == 16 || $party->accountgroupid == 15) ? $party->ledgername . " (" . $party->address . ", " . $party->district_name . ")" : $party->ledgername; ?>
                                </td>
                                <td style="text-align: right;!importent;"><?php echo moneyFormatIndia(ceil($payment->amount)); ?></td>
                                <td><?php echo ($payment->description); ?></td>
                                <td><?php echo ($payment->fullname); ?></td>
                                <td><a style="" target="_blank" href="<?php echo site_url('reports/receivedshow/' . $payment->id); ?>"><i class="fa fa-print"></i></a>
                                </td>
                            </tr>
                    <?php
                            $totalamount = $totalamount + $payment->amount;
                        endforeach;
                    endif;
                    ?>
                </tbody>

            </table>

        </div>
    </section>
</section>

<?php include 'footer.php'; ?>

<script>
    $(document).ready(function () {
        $('#paymenthistory2').dataTable({
            aLengthMenu: [
                [500, 1000, 2500, 5000, -1],
                [500, 1000, 2500, 5000, "All"]
            ],
            iDisplayLength: 500,
            "aaSorting": [[1, "desc"]]
        });
    });
    function getuserdetails(ledgernameOrid) {
        console.log(ledgernameOrid);
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        var datastring = 'ledgerid=' + ledgernameOrid + '&' + tokenname + '=' + tokenvalue;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("sell/getcustomerdetails") ?>',
            data: datastring,
            success: function(response) {
                var dataob = JSON.parse(response);
                console.log(dataob.due);
                if(Number(dataob.due) >= 0)
                $("#payamount").val((Math.ceil(dataob.due)).toLocaleString('en-IN'));
                
                else{
                    $("#payamount").val((Math.ceil(dataob.due)).toLocaleString('en-IN'));}
                
            }
        });    
    }
    function setcomma(e) {
        if(e.value.slice(-1)!='.' && e.value!=''){
            let tmp = e.value.replaceAll(',','');
            let num = Number(tmp);
            var rej = num.toLocaleString('en-IN'); 
            e.value=rej;
        }
    }
</script>