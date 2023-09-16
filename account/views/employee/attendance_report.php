<?php include __DIR__ . '/../topheader.php'; ?>
<?php include __DIR__ . '/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-body">
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('employee/attendance_report'); ?>">

                            <div class="col-lg-2">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date From</label>
                                    <input class="form-control" type="text" name="sdate" value="<?php echo $sdate; ?>" id="sdate" />
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Date To</label>
                                    <input class="form-control" type="text" name="edate" value="<?php echo $edate; ?>" id="edate" />
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label">Employee</label>
                                    <select class="form-control selectpicker" data-live-search="true" name="employee" id="employee">
                                        <option value="all">ALL</option>
                                        <?php
                                        if (sizeof($employeelist) > 0) :
                                            foreach ($employeelist as $buyer) :

                                        ?>
                                                <option <?php echo ($buyer->description == $employee_id) ? 'selected' : ''; ?> value="<?php echo $buyer->description; ?>"><?php echo $buyer->ledgername . " (" . $buyer->address . ", " . $buyer->district_name . ")" ?></option>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-2">
                                <div class="form-group" style="margin-right: 0px">
                                    <label class="control-label"><br /></label>
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input style="margin-top: 25px;margin-left: 20px" class="btn btn-primary" type="submit" value="Submit" />
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <section class="panel">
                    <header class="panel-heading">
                        Attendance Report
                        <a href="<?php echo site_url('employee/exports_data/' . $sdate . '/' . $edate . '/' . $employee_id); ?>"><button style="float: right; margin-left: 10px;" class="btn btn-primary "><i class="fa fa-file-o"> Export </i> </button></a>
                        <span style="float: right;margin-left: 50px;"><a href="#" data-toggle="modal" data-target="#addcsv"><button class="btn btn-primary"><i class="fa fa-upload"></i>&nbsp;Upload CSV</button></a></span>

                        <span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
                    </header>
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addcsv" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                    <h4 class="modal-title">Upload CSV File</h4>
                                </div>

                                <div class="modal-body">
                                    <form class="form-horizontal" role="form" action="<?php echo site_url('Employee/attendanceUplodCsv'); ?>" method="post" enctype="multipart/form-data">

                                        <!-- BEGIN FORM-->
                                        <div class="form-group">
                                            <label for="asdate" class="col-lg-3 col-sm-3 control-label">Start Date<span class="required">*</span></label>
                                            <div class="col-lg-7">
                                                <input type="text" name="upload_date" value="<?php echo date('Y-m-d'); ?>" class="input-file uniform_on form-control" id="asdate" required>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label for="upload_data_file" class="col-lg-3 col-sm-3 control-label">Upload File<span class="required">*</span></label>
                                            <div class="col-lg-7">
                                                <input type="file" name="upload_data_file" class="input-file uniform_on form-control" id="upload_data_file" required>
                                            </div>

                                        </div>



                                        <div class="form-group">
                                            <div class="col-lg-offset-4 col-lg-8">

                                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                                <input type="submit" class="btn btn-primary" name="csv" value="Submit" />&nbsp;&nbsp;
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php if ($this->session->userdata('success')) : ?>
                            <div class="alert alert-block alert-success fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Congratulation!</strong>
                                <?php
                                echo $this->session->userdata('success');
                                $this->session->unset_userdata('success');
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->session->userdata('failed')) : ?>
                            <div class="alert alert-block alert-danger fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Oops!</strong>
                                <?php
                                echo $this->session->userdata('failed');
                                $this->session->unset_userdata('failed');
                                ?>
                            </div>
                        <?php endif; ?>
                        <div class="form">
                            <table class="display table table-bordered table-striped dataTable" id="suppliertableid">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Employee Name</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th>Working Time(h:m:s)</th>
                                        <th>Salary(Tk.)</th>
                                    </tr>
                                </thead>
                                <tbody id="invoicediv">
                                    <?php
                                    $totaldate = 0;
                                    $attent = 0;
                                    $totaltk = 0;
                                    for ($i = 1; $i <= array_key_last($arraydate); $i++) :
                                        if ($employee_id != 'all') {
                                            $totaldate++;
                                            if ($array_in[$i] != 0)
                                                $attent++;
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $arraydate[$i]; ?></td>
                                            <td><?php echo $arrayname[$i]; ?></td>
                                            <td style="text-align: right;"><?php echo $array_in[$i]; ?></td>
                                            <td style="text-align: right;"><?php echo $array_out[$i]; ?></td>
                                            <td><?php echo (int) ($arraytime[$i] / 3600) . ":" . (int)(($arraytime[$i] % 3600) / 60) . ":" . ($arraytime[$i] % 60); ?></td>
                                            <td style="text-align: right;"><?php echo round($arraysalary[$i]);
                                                                            $totaltk += round($arraysalary[$i]); ?></td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                                <tfoot class="hidetoprint">
                                    <tr>
                                        <?php if ($employee_id != 'all') : ?>
                                            <td style="text-align: center;" colspan="6">
                                                <b>Present: <span style="color: green;"><?php echo $attent < 2 ? $attent . " day" : $attent . " days"; ?></span>
                                                </b></br>
                                                <b>Absence: <span style="color: Red;"><?php echo $totaldate - $attent < 2 ? ($totaldate - $attent) . " day" : ($totaldate - $attent) . " days"; ?></span>
                                                </b></br>
                                                <b>Out of: <span style="color: green;"><?php echo $totaldate < 2 ? $totaldate . " day" : $totaldate . " days"; ?></span>
                                                </b></br>
                                                <b>Total Bill: <span style="color: Red;"><?php echo $totaltk; ?> Tk.</span>
                                                </b>
                                            </td>
                                        <?php else : ?>
                                            <td style="text-align: center;" colspan="6">
                                                <b>Total Bill: <span style="color: Red;"><?php echo $totaltk; ?> Tk.</span>
                                                </b>
                                            </td>
                                        <?php endif; ?>
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
<?php include __DIR__ . '/../footer.php'; ?>
<script>
    var today = "<?php echo date("Y-m-d 00:00:00"); ?>";
    
    $('#asdate').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'Y-m-d',
        disabledDates: ['1986-01-08', '1986-01-09', '1986-01-10'],
        startDate: today,
        minDate: '2017-01-01',
        maxDate: '2050-01-01',
        timepicker: false
    });

    function Clickheretoprint() {
        var comname = "<?php echo $this->session->userdata('company_name'); ?>";
        var comaddress = "<?php echo $this->session->userdata('company_address'); ?>";

        var employee = $("#employee option:selected").text();

        var from_date = $("#sdate").val();
        var to_date = $("#edate").val();

        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Attendance Report</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td { border: 1px solid gray; text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write('<h2 style="text-align:center;">' + comname + '</h2>');
        docprint.document.write('<h3 style="margin-top:-15px;text-align:center;">' + comaddress + '</h3>');

        docprint.document.write('<h2 style="margin:-10px 0 10px 0px;text-align:center;">Attendance Report</h2>');
        docprint.document.write('<p style="margin:-10px 0 10px 0px;text-align:center;">Employee : ' + employee + '</p>');

        docprint.document.write('<table>');
        docprint.document.write(oTable.parentNode.innerHTML);

        docprint.document.write('</table>');

        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }
</script>