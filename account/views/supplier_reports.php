<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('reports/searchpurchasehistory'); ?>">

                                        <div class="col-lg-3">
                                            <div class="form-group" style="margin-right: 0px">
                                                <label class="control-label">Date From</label>
                                                <input class="form-control" type="text" name="sdate" value="<?php echo $sdate; ?>" id="sdate"/>                               
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group" style="margin-right: 0px">
                                                <label class="control-label">Date To</label>
                                                <input class="form-control" type="text" name="edate" value="<?php echo $edate; ?>" id="edate"/>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group" style="margin-right: 0px">
                                                <label class="control-label">Supplier</label>
                                                <?php
                                                $acgroup = $this->db->get_where('accountgroup', array('name' => 'supplier'))->row();
                                                if (sizeof($acgroup) > 0):
                                                    $acgroupd = $acgroup->id;
                                                else:
                                                    $acgroupd = '';
                                                endif;
                                                ?>
                                                <select class="form-control" name="supplier" id="supplier">
                                                    <option <?php echo ($supplier == 'all') ? 'selected' : ''; ?> value="all">ALL</option>   
                                                    <?php
                                                    $suppName = $this->db->query("select * from accountledger where accountgroupid = '$acgroupd'")->result();
                                                    if (sizeof($suppName) > 0):
                                                        foreach ($suppName as $siuppler):
                                                            ?>
                                                            <option value="<?php echo $siuppler->id; ?>"><?php echo $siuppler->ledgername; ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">                           
                                            <div class="form-group" style="margin-right: 0px">     
                                                <label class="control-label"><br/></label>
                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                <input style="margin-top: 25px;margin-left: 20px" class="btn btn-primary" type="submit" value="Submit"/>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <header class="panel-heading">
                        Account Details
                    </header>
                    <div class="panel-body">                       
                        <div class="form">
                            <table class="display table table-bordered table-striped dataTable">
                                <thead>
                                    <tr>
                                        <th colspan="3" style="background: rgba(128, 128, 128, 0.28)">Purchase history</th>
                                    </tr>
                                    <tr>                                    
                                        <th>Invoice ID</th>  
                                        <th>Date</th>
                                        <th>Amount</th>                                                                              
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $totalpurchaseamunt = 0;
                                    if (sizeof($purchasedata) > 0):
                                        foreach ($purchasedata as $buy):
                                            ?>
                                            <tr>
                                                <td><a href="<?php echo site_url('reports/detailspurchase/' . $buy->invoiceid); ?>"><?php echo $buy->invoiceid; ?></a></td>   
                                                <td><?php echo $buy->date; ?></td>
                                                <td><?php echo number_format($buy->totalpurchase, 2); ?></td>                                                                                             
                                            </tr>
                                            <?php
                                            $totalpurchaseamunt = $totalpurchaseamunt + $buy->totalpurchase;
                                        endforeach;
                                    endif;
                                    ?>
                                    <tr>                                    
                                        <th></th> 
                                        <th>Total Purchase</th>                                        
                                        <th><?php echo number_format($totalpurchaseamunt, 2); ?></th>                                                                               
                                    </tr>
                                </tbody>


                                <thead>
                                    <tr>
                                        <th colspan="3" style="background: rgba(128, 128, 128, 0.28)">Payment history</th>
                                    </tr>
                                    <tr>                                    
                                        <th>Invoice ID</th>  
                                        <th>Date</th>
                                        <th>Amount</th>                                                                              
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $totalpayamount = 0;
                                    if (sizeof($paymentdata) > 0):
                                        foreach ($paymentdata as $payment):
                                            ?>
                                            <tr>
                                                <td><?php echo $payment->invoiceid; ?></td>   
                                                <td><?php echo $payment->date; ?></td>
                                                <td><?php echo number_format($payment->totalpayment, 2); ?></td>                                                                                             
                                            </tr>
                                            <?php
                                            $totalpayamount = $totalpayamount + $payment->totalpayment;
                                        endforeach;
                                    endif;
                                    ?>
                                    <tr>                                    
                                        <th></th> 
                                        <th>Total Payments</th>                                        
                                        <th><?php echo number_format($totalpayamount, 2); ?></th>                                                                               
                                    </tr>
                                </tbody>
                                <tfoot>

                                    <tr style="background: rgba(128, 128, 128, 0.28)">                                    
                                        <th></th>                                           
                                        <th style="text-align: right">Total Due</th>                                        
                                        <th style="background: greenyellow"><?php
                                    if ($totalpayamount > $totalpurchaseamunt):
                                        $sign = "+";
                                        $due = $totalpayamount - $totalpurchaseamunt;
                                        echo $sign . number_format($due, 2);
                                    else:
                                        $sign = "-";
                                        echo $sign . number_format($totalpurchaseamunt - $totalpayamount, 2);
                                    endif;
                                    ?>
                                        </th>                       
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
<?php include 'footer.php'; ?>
