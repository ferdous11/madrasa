<?php include __DIR__ . '/../topheader.php'; ?>
<?php include __DIR__ . '/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">

                <section class="panel">
                    <header class="panel-heading">
                        Received Voucher
                    </header>
                    <div class="panel-body">

                        <div class="form" id="invoicediv">
                            <center>

                                <table style="font-family: adorsholipi;font-size: 18px;font-style: bold" border="0" class="display table">
                                    <colgroup>
                                        <col span="1" style="width: 22%;">
                                        <col span="1" style="width: 1%;">
                                        <col span="1" style="width: 77%;">
                                    </colgroup>
                                    <tr>
                                        <td style="text-align: center;" colspan="3">
                                            <?php echo $this->session->userdata('company_address'); ?>
                                            <h2 style="margin: 5px;"><u>Received Voucher</u></h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ledger Name</td>
                                        <td>:</td>
                                        <td><?php echo '<b>' . $received->ledgername . '</b>'; ?></td>
                                    </tr>
                                </table>
                                <table style="font-family: adorsholipi;font-size: 18px;font-style: bold" border="0" class="display table">
                                    <colgroup>
                                        <col span="1" style="width: 22%;">
                                        <col span="1" style="width: 1%;">
                                        <col span="1" style="width: 59%;">
                                        <col span="1" style="width: 18%;">
                                    </colgroup>

                                    <tr>
                                        <td><small>Address</small></td>
                                        <td>:</td>
                                        <td><small> <?php echo $received->address;
                                                    echo $received->district_name != "" ? ", " . $received->district_name : ""; ?></small></td>
                                        <td><b>Rec#<?php echo sprintf("%06d", $received->id); ?></b></td>
                                    </tr>

                                    <tr>
                                        <td><small>Mobile No</small></td>
                                        <td>:</td>
                                        <td><small><?php echo $received->mobile; ?></small></td>
                                        <td><small><?php $date = strtotime($received->date);
                                                    echo (date('d-M-Y', $date)); ?></small></td>
                                    </tr>
                                </table>

                                <table border="1" class="display table table-bordered">
                                    <colgroup>
                                        <col span="1" style="width: 30%;">
                                        <col span="1" style="width: 70%;">
                                    </colgroup>
                                    <tr>
                                        <td>Received Amount</td>
                                        <td style="text-align: left;vertical-align: middle;font-size:25px;height:1in;">
                                            <b><?php echo  moneyFormatIndia(ceil($received->amount)) . " Tk."; ?></b>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>In Word</td>
                                        <td style="text-align: left;vertical-align: middle;font-size:20px;height:1in;">
                                            <p> <?php echo $inword . " Tk Only."; ?></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Current Due</td>
                                        <td style="text-align: left;vertical-align: middle;font-size:25px;height:1in;">
                                            <p><b><?php echo  moneyFormatIndia(ceil($currentdue)) . " Tk."; ?></b></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Comment</td>
                                        <td style="text-align: left; vertical-align: middle;height:1in;">
                                            <p> <?php echo $received->description; ?></p>
                                        </td>
                                    </tr>

                                    <tr>
                                    <td colspan="2">
                                        <label style="float:left;height:1in;"><small><?php echo "";?></small></label></br>
                                        <label style="float:left;margin-top:45px;">Authorized by: </label>
                                        <label style="float:right;margin-top:45px">Prepared by: <?php echo $received->fullname;?></label>
                                    </td>
                                    </tr>
                                </table>
                                <div class="row">
                                    <div style="text-align: center;" class="col-md-12">This is a computer generated invoice & do not require any signature.</div>
                                </div>
                            </center>
                        </div>
                        <br />
                        <span style="float: right;margin-left: 20px"><button class="btn btn-primary" value="Print" onclick="Clickheretoprint()">Print</button></span>&nbsp;&nbsp;
                        <!-- <span style="float: right;margin-left: 20px"><button class="btn btn-primary" value="pdf" onclick="Clickheretopdf()">PDF</button></span>&nbsp;&nbsp;
                        &nbsp; --><span style="float: right;margin-left: 15px"><a href="<?php echo site_url('reports/received'); ?>"><button class="btn btn-primary">Back To Home</button></a></span>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include __DIR__ . '/../footer.php'; ?>

<script type="text/javascript">
    function Clickheretoprint() {
        var bottom_note = "";
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=760, left=215, top=215";
        var docprint = window.open("Note:" + bottom_note, "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html>');
        docprint.document.write('<head><style>');
        docprint.document.write('@media print {  @page {size: 8.3in 11.67in;margin-top:1cm !important; margin-bottom: 1cm !important;margin-left:0.5in !important;margin-right:0.5in !important;}}');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:left;font-size:25px;vertical-align: middle;padding-left:7px;}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write(oTable.innerHTML);
        docprint.document.write('</center></body>');
        docprint.document.write('<footer>' + bottom_note + '</footer></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }
</script>