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
                    <div class="panel-body">
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('home/viewreport'); ?>">
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
                                    <label class="control-label">Report Type</label>
                                    <select class="form-control" name="reporttype" id="reporttype">
                                        <option <?php echo ($rtype == 'sell') ? 'selected' : ''; ?> value="sell">Sales Report</option>
                                        <option <?php echo ($rtype == 'buy') ? 'selected' : ''; ?> value="buy">Buy Report</option>                                        
                                        <option <?php echo ($rtype == 'utilities') ? 'selected' : ''; ?> value="utilities">Utilities Report</option>
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
        <div class="row">
            <div class="col-lg-12">                
                <?php if ($rtype == 'sell'): ?>
                    <section class="panel">
                        <header class="panel-heading">
                            Sales Reports
                        </header>
                        <div class="panel-body">

                            <div class="form">
                                <table class="display table table-bordered table-striped dataTable" id="example">

                                    <thead>
                                        <tr>                                    
                                            <th>Date</th>                                       
                                            <th>Product Name</th>
                                            <th>Buying Price</th>
                                            <th>Sales Price</th>
                                            <th>Quantity</th>  
                                            <th>Total price</th>
                                            <th>Profit</th>
                                            <th>Loss</th>                                                                                
                                            <th>Buyer</th>    
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $totalprofit = 0;
                                        $totallos = 0;
                                        $totalbuy = 0;
                                        $totalsell = 0;
                                        $totalpayment = 0;
                                     
                                        $i = 1;
                                        if (sizeof($selldata) > 0):
                                            foreach ($selldata as $sell):
                                                if (($sell->sellprice * $sell->quantity) > ($sell->buyprice * $sell->quantity)):
                                                    $totallove = ($sell->sellprice * $sell->quantity) - ($sell->buyprice * $sell->quantity);
                                                else:
                                                    $totallove = 0.00;
                                                endif;
                                                if (($sell->sellprice * $sell->quantity) < ($sell->buyprice * $sell->quantity)):
                                                    $totalloss = ($sell->buyprice * $sell->quantity) - ($sell->sellprice * $sell->quantity);
                                                else:
                                                    $totalloss = 0.00;
                                                endif;
                                                ?>
                                                <tr>
                                                    <td><?php echo $sell->date; ?></td>   
                                                    <td><?php echo $sell->product_name; ?></td>
                                                    <td><?php echo number_format($sell->buyprice, 2); ?></td>
                                                    <td><?php echo number_format($sell->sellprice, 2); ?></td>
                                                    <td><?php echo $sell->quantity; ?></td>
                                                    <td><?php echo number_format($sell->sellprice*$sell->quantity, 2); ?></td>
                                                    <td><?php echo number_format($totallove, 2); ?></td>
                                                    <td><?php echo number_format($totalloss, 2); ?></td>
                                                    <td><?php echo $sell->buyername; ?></td>                                               
                                                </tr>
                                                <?php
                                                $totalprofit = $totalprofit + $totallove;
                                                $totallos = $totallos + $totalloss;
                                                $totalbuy = $totalbuy + $sell->buyprice;
                                                $totalsell = $totalsell + $sell->sellprice;
                                                $totalpayment = $totalpayment + $sell->payment;                                                
                                            endforeach;
                                        endif;
                                        ?>

                                    </tbody>

                                    <tfoot>

                                        <tr>                                    
                                            <th></th>                                       
                                            <th></th>
                                            <th>Total: <?php echo number_format($totalbuy, 2) ?></th>
                                            <th>Total: <?php echo number_format($totalsell, 2) ?></th>
                                             <th></th>    
                                            <th>Total: <?php echo number_format($totalpayment, 2) ?></th>
                                            <th>Total: <?php echo number_format($totalprofit, 2) ?></th>
                                            <th>Total: <?php echo number_format($totallos, 2) ?></th>
                                            
                                            <th></th> 
                                           
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </section>
                    <?php
                endif;
                if ($rtype == 'buy'):
                    ?>
                    <section class="panel">
                        <header class="panel-heading">
                            Buy Reports
                        </header>
                        <div class="panel-body">

                            <div class="form">
                                <table class="display table table-bordered table-striped dataTable" id="example">
                                    <thead>
                                        <tr>                                    
                                            <th>Date</th>                                       
                                            <th>Product Name</th>
                                            <th>Product ID</th>
                                            <th>Buying Price</th>
                                            <th>Sales Price</th>                                       
                                            <th>Quantity</th>                                                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $totalbuy = 0;
                                        $totalsell = 0;
                                        if (sizeof($buydata) > 0):
                                            foreach ($buydata as $buy):
                                                ?>
                                                <tr>
                                                    <td><?php echo $buy->date; ?></td>   
                                                    <td><?php echo $buy->pname; ?></td>
                                                    <td><?php echo $buy->product_id; ?></td>
                                                    <td><?php echo number_format($buy->buyprice, 2); ?></td>
                                                    <td><?php echo number_format($buy->sellprice, 2); ?></td>                                               
                                                    <td><?php echo $buy->quantity; ?></td>                                                                                           
                                                </tr>
                                                <?php
                                                $totalbuy = $totalbuy + $buy->buyprice;
                                                $totalsell = $totalsell + $buy->sellprice;
                                            endforeach;
                                        endif;
                                        ?>

                                    </tbody>
                                    <tfoot>

                                        <tr>                                    
                                            <th></th>                                       
                                            <th></th>
                                            <th></th> 
                                            <th>Total: <?php echo number_format($totalbuy, 2) ?></th>
                                            <th>Total: <?php echo number_format($totalsell, 2) ?></th>
                                            <th></th>                                                                                                                                                                 
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </section>

                    <?php
                endif;
                if ($rtype == 'utilities'):
                    ?>
                    <section class="panel">
                        <header class="panel-heading">
                            Utilities Reports
                        </header>
                        <div class="panel-body">

                            <div class="form">
                                <table class="display table table-bordered table-striped dataTable" id="example">
                                    <thead>
                                        <tr>                                    
                                            <th>Date</th>   
                                            <th>Expense by</th>
                                            <th>Particular</th>
                                            <th>Amount</th>                                                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $totalcost = 0;
                                        if (sizeof($ucost) > 0):
                                            foreach ($ucost as $cost):
                                                ?>
                                                <tr>
                                                    <td><?php echo $cost->date; ?></td>   
                                                    <td><?php echo $cost->addby; ?></td>
                                                    <td><?php echo $cost->goodsname; ?></td>
                                                    <td><?php echo number_format($cost->amount, 2); ?></td>                                                                                       
                                                </tr>
                                                <?php
                                                $totalcost = $totalcost + $cost->amount;
                                            endforeach;
                                        endif;
                                        ?>

                                    </tbody>
                                    <tfoot>

                                        <tr>                                    
                                            <th></th>                                       
                                            <th></th>
                                            <th></th> 
                                            <th>Total: <?php echo number_format($totalcost, 2) ?></th>                                                                                                   
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>




            </div>             

        </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include 'footer.php'; ?>
