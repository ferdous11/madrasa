<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-12">                

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('reports/viewcustomerreport'); ?>">

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
                                            <label class="control-label">Customer</label>
                                            <?php
                                            $acgroup = $this->db->get_where('accountgroup', array('name' => 'customer'))->row();
                                            if (sizeof($acgroup) > 0):
                                                $acgroupd = $acgroup->id;
                                            else:
                                                $acgroupd = '';
                                            endif;
                                            $getledgername = $this->db->query("select * from accountledger where accountgroupid = '$acgroupd' order by id desc")->result();
                                            ?>
                                            <select class="form-control" name="customer" id="customer">                                                
                                                <?php
                                                if (sizeof($getledgername) > 0):
                                                    $customerName = '0';
                                                    foreach ($getledgername as $ledger):
                                                        ?>
                                                        <option <?php echo ($customer == $ledger->ledgername) ? 'selected' : ''; ?> value="<?php echo $ledger->ledgername; ?>"><?php echo $ledger->ledgername; ?></option>
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

                <section class="panel">
                    <header class="panel-heading">
                        Sales History <span style="float: right;padding: 2px 12px;font-size: 12px" class="btn btn-primary"><a onclick="PrintElem('#invoicediv')" title="Print" style="color: #fff"><i class="fa fa-print"></i>&nbsp;Print</a></span>
                    </header>
                    <div class="panel-body">                       
                        <div class="form" id="invoicediv">
                            <table class="display table table-bordered table-striped dataTable">

                                <thead>
                                    <tr>
                                        <th colspan="3" style="background: rgba(128, 128, 128, 0.28)">Sales history</th>
                                    </tr>
                                    <tr>                                    
                                        <th>Invoice ID</th>                                           
                                        <th>Date</th>                                        
                                        <th>Amount</th>                       
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $totalbill = 0;
                                    if (sizeof($selldata) > 0):
                                        foreach ($selldata as $sell):
                                            ?>
                                            <tr>
                                                <td><a href="<?php echo site_url('reports/detailssell/' . $sell->invoice_id); ?>"><?php echo $sell->invoice_id; ?></a></td> 
                                                <td><?php echo $sell->date; ?></td>                                               
                                                <td><?php echo number_format($sell->sellprice, 2); ?></td> 
                                            </tr>
                                            <?php
                                            $totalbill = $totalbill + $sell->sellprice;
                                        endforeach;
                                    endif;
                                    ?>
                                    <tr>                                    
                                        <th></th>                                           
                                        <th>Total Sell</th>                                        
                                        <th><?php echo number_format($totalbill, 2); ?></th>                       
                                    </tr>
                                </tbody>

                                <thead>
                                    <tr>
                                        <th colspan="3" style="background: rgba(128, 128, 128, 0.28)">Received history</th>
                                    </tr>
                                    <tr>                                    
                                        <th>Invoice ID</th>                                           
                                        <th>Date</th>                                        
                                        <th>Amount</th>                       
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $totalreceived = 0;
                                    if (sizeof($receivedata) > 0):
                                        foreach ($receivedata as $receiv):
                                            ?>
                                            <tr>
                                                <td><?php echo $receiv->invoiceid; ?></td> 
                                                <td><?php echo $receiv->date; ?></td>                                               
                                                <td><?php echo number_format($receiv->amount, 2); ?></td> 
                                            </tr>
                                            <?php
                                            $totalreceived = $totalreceived + $receiv->amount;
                                        endforeach;
                                    endif;
                                    ?>
                                    <tr>                                    
                                        <th></th>                                           
                                        <th>Total Received</th>                                        
                                        <th><?php echo number_format($totalreceived, 2); ?></th>                       
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr style="background: rgba(128, 128, 128, 0.28)">                                    
                                        <th></th>                                           
                                        <th style="text-align: right">Total Due</th>                                        
                                        <th style="background: greenyellow"><?php
                                            if ($totalreceived > $totalbill):
                                                $sign = "+";
                                                $due = $totalreceived - $totalbill;
                                                echo $sign . number_format($due, 2);
                                            else:
                                                $sign = "-";
                                                echo $sign . number_format($totalbill - $totalreceived, 2);
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
<script type="text/javascript">
    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data)
    {
        var mywindow = window.open('', 'new div', 'height=400,width=600');
        mywindow.document.write('<html><head><title></title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo $baseurl; ?>assets/css/bootstrap.min.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.print();
        mywindow.close();
        return true;
    }
</script>