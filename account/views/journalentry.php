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
                    <div class="panel-heading">
                        Add Journal Entry
                    </div>
                    <div class="panel-body">
                        <form class="cmxform form-horizontal tasi-form" method="post" action="<?php echo site_url('journalentry/addjournal') ?>">
                            
                            <div class="col-lg-12" style="padding-left: 0px;"> 
                                <div class="form-group">
                                    <div class="col-lg-2">
                                        <label  for="accountledger">Account Group</label>
                                    </div> 
                                                                                                     
                                    <div class="col-lg-4">
                                        <label  for="accountledger">Account Ledger</label>
                                    </div>                                    
                                    <div class="col-lg-2">
                                        <label  for="debit">Amount</label>
                                    </div>
                                                                      
                                </div>    
                            </div>

                            <div class="col-lg-12" style="padding-left: 0px;"> 
                                <div class="form-group" style="padding-top: 3px">

                                    <div class="col-lg-2">
                                        <select class=" form-control selectpicker" data-live-search="true" name="debitacgroup" onchange="setdracladger(this.value)"  required>
                                            <option value="">Select Debit A.Group</option>
                                            <?php
                                            foreach ($ledgergroup as $value) {
                                                $accNo = $value->id;
                                                echo "<option value='" . $value->id . "'> ".($accNo)." - ".$value->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>  
                                                                                                     
                                    <div class="col-lg-4">
                                        <select class=" form-control selectpicker" data-live-search="true" id="first_ledgerId" name="debitid"  required>
                                            <option value="">Select Debit Account</option>
                                            <?php
                                            foreach ($ledger as $value) {
                                                $accNo = $value->id;
                                                echo "<option value='" . $value->id . "'> ".($accNo)." - ".$value->ledgername."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>                                    
                                    <div class="col-lg-3">
                                        <input type="text" id="debit" name="debit" class="form-control debit"  placeholder="0.00" onkeyup="first_debit()">
                                    </div>
                                                                    
                                </div>   
                            </div>

                            <div class="col-lg-12"> 
                                <div class="form-group">  
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-4"></div>
                                    <div class="col-lg-8">
                                        <span id="valuecheck"> </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12" style="padding-left: 0px;"> 
                                <div class="form-group">
                                    <div class="col-lg-2">
                                        <select class=" form-control selectpicker" data-live-search="true" name="debitacgroup" onchange="setcracladger(this.value)"  required>
                                            <option value="">Select Credit A.Group</option>
                                            <?php
                                            foreach ($ledgergroup as $value) {
                                                $accNo = $value->id;
                                                echo "<option value='" . $value->id . "'> ".($accNo)." - ".$value->name."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <select class=" form-control selectpicker" data-live-search="true" id="second_ledgerId" name="creditid" required>
                                            <option value="">Select Credit Account</option>
                                        </select>
                                    </div>                                    
                                    

                                    <div class="col-lg-3">                                          
                                        <input id="second_credit" type="text" name="credit" class="form-control credit"  placeholder="0.00" onkeyup="f_credit()" >                                       
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12"> 
                                <div class="form-group">  
                                    <div class="col-lg-4"></div>
                                    <div class="col-lg-4"></div>
                                    <div class="col-lg-4">
                                        <span id="valuecheck"> </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12" style="padding-left: 0px">
                                <div class="panel-body">
                                    <div class="form-group ">
                                        <label for="opening_balance" class="control-label col-lg-6" style="text-align: right">Description</label>
                                        <div class="col-lg-4">
                                            <textarea class="form-control " id="description" name="description" cols="30" rows="3" required=""></textarea>
                                        </div>                               
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12" style="padding-left: 0px"> 
                                <div class="form-group ">
                                    <label for="opening_balance" class="control-label col-lg-6" style="text-align: right">Date</label>
                                    <div class="col-lg-4">
                                        <input class="form-control " <?php echo $this->session->userdata('role') == 'admin'?'id="purdate"':'readonly';?> name="date" value="<?php echo Date('Y-m-d H:i:s'); ?>"/>
                                    </div>                               
                                </div>
                            </div>

                            <div class="col-lg-12"> 
                                <div class="form-group">  
                                    <div class="col-lg-4"></div>
                                    <div class="col-lg-8">
                                        <span id="valuecheck"> </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12" style="padding-left: 0px"> 
                                <div class="form-group ">
                                    <label for="opening_balance" class="control-label col-lg-4"></label>
                                    <div class="col-lg-6">
                                        <input type="submit"  class="btn btn-primary"  value="Save">
                                    </div>                               
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
                        Journal Entry<span style="float: right"><button style="padding: 1px 5px 1px 5px" class="btn btn-primary" onclick="Clickheretoprint()"><i class="fa fa-print"></i> Print</button></span>
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
                            <table class="display table table-bordered table-striped dataTable" id="example11">

                                <thead>
                                    <tr>                                    
                                        <th>SN</th>                                       
                                        <th>Date</th>
                                        <th>Voucher#</th> 
                                        <th>Ledgers</th> 
                                        <th>Details</th>
                                        <th>Amount</th>
                                        <th class="hidetoprint">Action</th>
                                    </tr>
                                </thead>

                                <tbody id="invoicediv">
                                    <?php
                                    $i = 1;
                                    $totalamount = 0;
                                    if (sizeof($journalData) > 0):
                                        foreach ($journalData as $entry):
                                            ?>
                                            <tr>
                                                <td><?php echo ($i++); ?></td>                                                   
                                                                                          
                                                <td><?php
                                                    echo (date('Y-m-d', strtotime($entry->date)));
                                                    ?>
                                                </td>  
                                                <td><?php echo "Jou-". sprintf("%06d", $entry->journalmasterid) ; ?></td>     
                                                <td><?php echo $entry->ledger_name; ?></td>  
                                                <td><?php echo $entry->description; ?></td>  
                                                <td><?php echo (number_format($entry->debit + $entry->credit, 2)); ?></td>
                                                <td class="hidetoprint">
                                                <?php $datef= date('Y-m-d'); if ($role=='admin'  ||  ($entry->date>$datef." 00:00:00" && $entry->date<$datef." 23:59:59")):?> 
                                                    <a href="<?php echo site_url('journalentry/delete_entry/' . $entry->journalmasterid); ?>"  onclick="return confirm('Are you sure want to delete this Voucher !!')"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <a href="<?php echo site_url('journalentry/edit_entry/' . $entry->journalmasterid); ?>"><i class="fa fa-edit"></i></a>
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                            <?php
                                            $totalamount = $totalamount + ($entry->debit + $entry->credit);
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
                                        <th></th>
                                        <th><?php echo (number_format($totalamount, 2)); ?></th>
                                        <th class="hidetoprint"></th> 
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
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#example11').dataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: 25
        });
    });
</script>
<script>

    function first_debit() {

        $("#second_credit").val($("#debit").val());
        
    }

    function f_credit() {
        
        $("#debit").val($("#second_credit").val()); 
    }
    function setdracladger(id){
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        
        var acgid = 'id=' + id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("journalentry/getledger"); ?>',
            data: acgid,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#first_ledgerId").find('option').remove().end();
                
                $("#first_ledgerId").append($('<option>', {
                        value: '' ,
                        text: 'Select Debit Account'
                }));
                
                $.each( jsonObject, function( r,v) {
                    
                    $("#first_ledgerId").append($('<option>', {
                        value: v.id,
                        text: v.ledgername
                    }));
                });

                $('.selectpicker').selectpicker('refresh');            
            }
        });
    }
    function setcracladger(id){
        var tokenname = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var tokenvalue = '<?php echo $this->security->get_csrf_hash(); ?>';
        
        var acgid = 'id=' + id + '&' + tokenname + '=' + tokenvalue;

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("journalentry/getledger"); ?>',
            data: acgid,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                $("#second_ledgerId").find('option').remove().end();
                
                $("#second_ledgerId").append($('<option>', {
                        value: '' ,
                        text: 'Select Credit Account'
                }));
                
                $.each( jsonObject, function( r,v) {

                    if(id==15||id==16||id==18||id==5)
                    
                        $("#second_ledgerId").append($('<option>', {
                            value: v.id,
                            text: v.ledgername+'( '+v.address+','+v.district_name+' )'
                        }));
                    else 
                        $("#second_ledgerId").append($('<option>', {
                            value: v.id,
                            text: v.ledgername
                        }));
                });

                $('.selectpicker').selectpicker('refresh');            
            }
        });
    }

    
    function Clickheretoprint()
    {
        $(".hidetoprint").hide();
        var comname = "<?php echo $this->session->userdata('company_name'); ?>";
        var comaddress = '<?php echo $this->session->userdata('company_address'); ?>';
        var comemail = "<?php echo $this->session->userdata('email'); ?>";        
        var mobile = "<?php echo $this->session->userdata('mobile'); ?>";        
        var from_date = $("#sdate").val();        
        var to_date = $("#edate").val();        
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=780, left=100, top=25";
        var docprint = window.open("about:blank", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable = document.getElementById("invoicediv");
        docprint.document.open();
        docprint.document.write('<html><title>Journal Report</title>');
        docprint.document.write('<head><style>');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td { text-align:center;font-size:17px}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><center>');
   
        docprint.document.write(comaddress);
       
        docprint.document.write('<hr>');
        
        docprint.document.write('</p><p style="margin:-10px 0 10px 0px;text-align:center;">Journal History</p>');
        docprint.document.write('<table border="1">');
        docprint.document.write(oTable.parentNode.innerHTML);
        docprint.document.write('</table></center></body></html>');
        docprint.document.close();
        docprint.print();
        $(".hidetoprint").show();
        docprint.close();
    }
</script>