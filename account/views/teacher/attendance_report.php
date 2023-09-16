<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<style>
    .tscroll {
        width: 400px;
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
                    <div class="form tscroll">
                        <table class="display table table-bordered table-striped dataTable" id="suppliertableid">

                            <thead>
                                <tr>                                    
                                    <th>Roll</th>                                    
                                    <th>Name</th>
                                    <?php foreach ($dates as $day): echo"<th>".date('d',strtotime($day->insert_date))."</th>"; endforeach;?>
                                    <th>T.P</th>
                                    <th>T.A</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                              
                                    foreach ($students as $student): $ta=0;$tp=0;
                                        echo "<tr><td>".$student->roll."</td><td>".$student->ledgername."</td>"; 
                                        $att = $this->db->query("select insert_date,attendance from attendance where student_id='".$student->id."' and insert_date between '$sdate' and '$edate'")->result(); 
                                        $d = count($dates) - count($att);
                                        while($d){
                                            echo "<td></td>";$d--;
                                        }
                                        foreach ($att as $a){
                                            if($a->attendance==1){$tp++;
                                            echo "<td style='background-color:green;text-align:center'><b>P</b></td>";}
                                            else{$ta++;
                                            echo "<td style='background-color:red;text-align:center'><b>A</b></td>";}
                                        }
                                        echo "<td style='background-color:green;text-align:right'><b>".$tp."</b></td><td style='background-color:red;text-align:right'><b>".$ta."</b></td></tr>";endforeach;?>
                             
                            </tbody>
                        </table>
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
</script>