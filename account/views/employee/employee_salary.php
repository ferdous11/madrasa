<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-12">               

                <section class="panel">

                    <header class="panel-heading">
                        Employee Salary<span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
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
                                <strong>Oops!</strong> <?php
                                echo $this->session->userdata('failed');
                                $this->session->unset_userdata('failed');
                                ?>
                            </div> 
                        <?php endif; ?>

                        <div class="form">
                            <span style="float: right"><a href="#" data-toggle="modal" data-target="#addsupplier"><button class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Add New</button></a></span>
                            <table class="display table table-bordered table-striped dataTable" id="suppliertableid">

                                <thead>
                                    <tr>                                    
                                        <th>SN</th>                                       
                                        <th>Name</th> 
                                        <th>Emlpoyee Id</th>                                      
                                        <th>Type</th>
                                        <th>Working Time</th>
                                        <th>Salary</th>
                                        <th>Assign Salary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="allledger">
                                    <?php
                                    $i = 1;
                                    if (sizeof($salarylist) > 0):
                                        foreach ($salarylist as $supplier):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>   
                                                <td><?php echo ($supplier->ledgername); ?></td>  
                                                <td><?php echo $supplier->description; ?></td>  
                                                <td><?php echo ($supplier->type); ?></td>
                                                <td><?php echo date('h:i A',strtotime($supplier->start))." - ".date('h:i A',strtotime($supplier->end)); ?></td>  
                                                <td><?php echo $supplier->salary; ?></td>         
                                                <td><?php echo $supplier->assign_salary_month; ?></td>         
                                                <td>
                                                    <a style="margin-right: 2px;" class="col-lg-4 label label-warning" href="<?php echo site_url('employee/showledger/'. $supplier->employee_id); ?>">Edit</a>
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </section>
            </div>          
        </div>

        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="addsupplier" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                        <h4 class="modal-title">Add New</h4>
                    </div>

                    <div class="modal-body">

                        <form class="form-horizontal" role="form"action="<?php echo site_url('employee/addledger'); ?>" method="post" enctype="multipart/form-data">

                            <div   class="form-group">
                                <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Employee Name</label>
                                <div class="col-lg-7">
                                    <select class="form-control"  name="employee"  required="">
                                        <?php
                                        if (sizeof($employee) > 0):
                                            foreach ($employee as $acg):

                                                if(in_array($acg->id,$check_array))
                                                    continue;
                                                ?>
                                                <option value="<?php echo $acg->id; ?>"><?php echo $acg->ledgername ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <span style="color: red" id="error_acgroup"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="address" class="col-lg-4 col-sm-4 control-label">Employee ID</label>
                                <div class="col-lg-7">
                                    <input type="text" name ="employee_id" require>
                                </div>
                            </div> 

                            <div   class="form-group">
                                <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Type</label>
                                <div class="col-lg-7">
                                    <select class="form-control"  name="type"  required="">
                                        <option value="Monthly">Monthly</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Daily">Dailypload Fi</option>
                                        <option value="Hourly">Hourly</option>
                                    </select>
                                    <span style="color: red" id="error_acgroup"></span>
                                </div>
                            </div> 

                            <div class="form-group">
                                <label for="starttime" class="col-lg-4 col-sm-4 control-label">Start Time</label>
                                <div class="col-lg-7">
                                    <input type="time" name ="start">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="endtime" class="col-lg-4 col-sm-4 control-label">End Time</label>
                                <div class="col-lg-7">
                                    <input type="time" name ="end">
                                </div>
                            </div>             
                            <div class="form-group">
                                <label for="address" class="col-lg-4 col-sm-4 control-label">Salary</label>
                                <div class="col-lg-7">
                                    <input type="number" step="0.001" name ="salary" require>
                                </div>
                            </div> 

                            <div class="form-group">
                                <div class="col-lg-offset-4 col-lg-8">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input type="submit" class="btn btn-primary" id="fsubmit" value="Submit" onclick="return checkacgroup()"/>&nbsp;&nbsp;
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#suppliertableid').dataTable({
            aLengthMenu: [
                [100, 200, 500,  -1],
                [100, 200, 500, "All"]
            ],
            iDisplayLength: 100
        });
        $("#foradvanced").hide();
    });
</script>
<?php include __DIR__ .'/../footer.php'; ?>
<script>

    function Clickheretoprint()
    {
        var comname = "<?php echo $this->session->userdata('nirmatacompany_name'); ?>";
        var comaddress = "<?php echo $this->session->userdata('nirmatacompany_address'); ?>";
        var comemail = "<?php echo $this->session->userdata('nirmataemail'); ?>";        
        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val();        
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("allledger");
        docprint.document.open();
        docprint.document.write('<html><title>Ledger</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td { border: 1px solid gray; text-align:center;font-size:18px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
        docprint.document.write('<h2 style="text-align:center;">' + comname + '</h2>');
        docprint.document.write('<h3 style="margin-top:-15px;text-align:center;">' + comaddress + '</h3>');
        docprint.document.write('<h3 style="margin-top:-15px;text-align:center;">E-mail: ' + comemail + '</h3><hr style="width:700px; margin: -12px 0 -12px 0">');

        docprint.document.write('</p><p style="margin:-10px 0 10px 0px;text-align:center;"> </p>');
        docprint.document.write('<table class="display table table-bordered table-striped dataTable">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</table></center></body></html>');
        docprint.document.close();
        docprint.print();
        docprint.close();
    }
</script>