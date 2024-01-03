<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<style>
    .tscroll {
        width: 680px;
        overflow-x: scroll;
        margin-bottom: 10px;
        border: solid black 1px;
    }
    .tscroll table td:first-child {
        position: sticky;
        left: 0;
        background-color: #ddd;
    }
    .tscroll table td:nth-child(2) {
        position: sticky;
        left: 0;
        background-color: #ddd;
    }
    .tscroll td, .tscroll th {
        border-bottom: dashed #888 1px;
    }
</style>

<section id="main-content">
    <section class="wrapper">

            <?php if ($this->session->userdata('success')): ?>
                <div class="alert alert-block alert-success fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                    <strong>আলহামদুলিল্লাহ,&nbsp;</strong> <?php
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

            <section class="panel">
                <header class="panel-heading">
                    Attendance Report
                </header>
                <div class="panel-body">
                    <div id="invoicediv" class="form tscroll">
                        <form method="post" action="<?php echo site_url('exam/admit'); ?>" enctype="multipart/form-data">
                        <table class="display table table-bordered table-striped dataTable" id="suppliertableid">

                            <thead id="thead">
                                <tr >                                    
                                    <th>পূর্বের রোল</th>                                    
                                    <th>নাম</th>
                                    <?php foreach($subjects as $subject): ?>
                                    <th><?=$subject->name;?></th>
                                    <?php endforeach;?>
                                    <th>মোট</th>
                                    <th>বর্তমান রোল</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="body">
                                <?php
                                    foreach ($students as $student): $ta=0;
                                        echo "<tr><td>".$student->roll."</td><td>".$student->ledgername."</td>"; 
                                        foreach($marks[$student->id] as $m){
                                            $ta+=$m->mark;
                                            echo "<td style='text-align:center'>".$m->mark."</td>";
                                        } 
                                        echo "<td style='text-align:center'>".$ta."</td>";
                                        echo "<td style='text-align:center'>".$newRoll[$student->id]."</td><td>"; 
                                        echo '<input type="checkbox" name="'.$student->id.'"value="'.$student->id.'">';

                                        echo '<input type="hidden" name="student_id[]" value="'.$student->id.'">';
                                        echo '<input type="hidden" name="class_id[]" value="'.$student->class_id.'">';
                                        echo '<input type="hidden" name="roll[]" value="'.$student->roll.'">';
                                        echo '<input type="hidden" name="new_roll[]" value="'.$newRoll[$student->id].'"></td></tr>';
                                        
                                    endforeach;?> 
                            </tbody>
                        </table>
                        <span style="float: right;margin-left: 20px"><button class="btn btn-primary" value="Print" tabindex="2" onclick="Clickheretoprint()">প্রিন্ট করুন</button>
                        <button class="btn btn-success" type="submit">পরবর্তি ক্লাসে যুক্ত করুন</button>
                        </form>
                    </div>
                </div>
            </section>


        </section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script>
    $(document).ready(function () {
        $('#suppliertableid').dataTable({
            aLengthMenu: [
                [ -1,500,200,50],
                ["All",500,200,50]
            ],
            iDisplayLength: -1,
            "aaSorting": [[0, "asc"]]
        });
        $("#foradvanced").hide();
    });
    function Clickheretoprint()
    {
                    
        var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
        disp_setting += "scrollbars=yes,width=1140, height=760, left=215, top=215";
        var docprint = window.open("nn", "_blank", disp_setting);
        var oTable;
        //console.log(selected_val);
        oTable1 = document.getElementById("thead"); 
        oTable2 = document.getElementById("body"); 
        docprint.document.open();
        docprint.document.write('<html>');
        docprint.document.write('<head><style>');
        docprint.document.write('@media print {  @page {size: 8.3in 11.67in;margin-top:1cm !important; margin-bottom: 1cm !important;margin-left:0.5in !important;margin-right:0.5in !important;}}');
        docprint.document.write('table {width:100%;}');
        docprint.document.write('table {border-collapse:collapse;}');
        docprint.document.write('table thead, tr, th, table tbody, tr, td {text-align:left;font-size:20px;padding-left:7px;}');
        docprint.document.write('table thead, tr, th{ background-colo: #000;}');
        docprint.document.write('</style></head>');
        docprint.document.write('<body><h2 style="text-align:center">ফরহাদুজ্জামান নূরানী তাহফিজুল কোরআন মাদ্রাসা</h2><h2 style="text-align:center">ফলাফল বার্ষিক পরীক্ষা ২০২৩</h2><h2 style="text-align:center">নার্সারি জামাত</h2><center><table border="1"><thead>');
        docprint.document.write(oTable1.innerHTML);
        docprint.document.write('</thead><tbody>');
        docprint.document.write(oTable2.innerHTML);
        docprint.document.write('</tbody></table>');
        docprint.document.write('</center></body>');
        docprint.document.write('</html>');
      
        docprint.print();
        docprint.close();
    }
</script>