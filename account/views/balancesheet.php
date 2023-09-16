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

                        <form class="tasi-form" method="post" action="<?php echo site_url('balancesheet/viewbalancesheet'); ?>">
                            <div class="form-group">
                                <div class="col-md-5" style="padding-left: 0">
                                    <div class="input-group input-sm" >
                                        <span class="input-group-addon">From </span>
                                        <div class="iconic-input right">
                                            <i class="fa fa-calendar"></i>
                                            <input type="text" class="form-control" id="sdate" name="date_from" value="<?php echo $date_from; ?>">
                                        </div>
                                        <span class="input-group-addon">To</span>
                                        <div class="iconic-input right">
                                            <i class="fa fa-calendar"></i>
                                            <input type="text" class="form-control" id="edate" name="date_to" value="<?php echo $date_to; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">   
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                </div>                              

                            </div>     
                            <p> &nbsp; </p>
                        </form>


                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">
                    <header class="panel-heading">
                        Balance Sheet <span style="float: right;margin-left: 20px"><button class="btn btn-primary" value="Print" onclick="Clickheretoprint()" style="padding: 1px 20px"><i class="fa fa-print"></i> Print</button></span>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div role="tabpanel" id="invoicediv"  class="tab-pane active">

                                <table class="table table-striped table-hover table-bordered editable-sample1" id="editable-sample">
                                    <thead>
                                        <tr>
                                            <th>Liability</th>                                            
                                            <th>Asset</th>                                           
                                        </tr>
                                    </thead>
                                    <tbody>                               
                                    </tbody>
                                </table>


                                <div class="row">
                                    
                                    <div class="col-lg-6">
                                        <table class="table table-striped table-hover table-bordered editable-sample1" id="editable-sample">
                                            <tbody>
                                                <tr>
                                                    <td style="text-align: left">Current Liability</td>
                                                    <td style="text-align: right"><?php echo $currentliability; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left">Loans &amp; liability</td>
                                                    <td style="text-align: right"><?php echo $liability; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left">Supplier</td>
                                                    <td style="text-align: right"><?php echo $supplier; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <table class="table table-striped table-hover table-bordered editable-sample1" id="editable-sample">
                                            <tbody>

                                                <tr>
                                                    <td style="text-align: left">Bank Account</td>
                                                    <td style="text-align: right"><?php echo $bankaccount; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left">Cash In Hand</td>
                                                    <td style="text-align: right"><?php echo $cashinhand; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left">Chits or Funds</td>
                                                    <td style="text-align: right"><?php echo $chirtfund; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left">Customer</td>
                                                    <td style="text-align: right"><?php echo $customertotal; ?></td>
                                                </tr>

                                                <tr>
                                                    <td style="text-align: left">Fixed Asset</td>
                                                    <td style="text-align: right"><?php echo $fixedasset; ?></td>
                                                </tr>

                                                <tr>
                                                    <td style="text-align: left">Closing Stock</td>
                                                    <td style="text-align: right"><?php echo $closingstock; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>



                            </div>

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
   function Clickheretoprint()
    {
        var comname = "<?php echo $this->session->userdata('company_name'); ?>";
        var comaddress = "<?php echo $this->session->userdata('company_address'); ?>";
        var comemail = "<?php echo $this->session->userdata('email'); ?>";    
        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val();        
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Balance Sheet</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td { border: 1px solid gray; text-align:center;font-size:11px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write('<p><span style="margin-top:50px; margin-left:-400px"><img height="50" weidth="150" src="<?php echo $baseurl."assets/img/logo.png"; ?>"/></span></p><p style="margin-top:-60px; margin-left:80px; color: red"><b><u>' + comname + '</u></b></p>');
        docprint.document.write('<p style="margin-top:-15px; margin-left:80px">' + comaddress + '</p>');
        docprint.document.write('<p style="margin-top:-15px; margin-left:80px">E-mail: ' + comemail + '<hr style="width:700px; margin: -12px 0 -12px 0">');
        docprint.document.write('<p style="margin-left:80px">Balance Sheet</p>');
        docprint.document.write('<p style="margin:-10px 0 10px 82px">Statement (' + from_date + ' to ' + to_date + ')</p>');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }
</script>
