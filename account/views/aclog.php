<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">

                    <header class="panel-heading">
                        Activity Log
                    </header>

                    <div class="panel-body">                       

                        <div class="form">                           
                            <table class="display table table-bordered table-striped dataTable" id="suppliertableid">

                                <thead>
                                    <tr>                                    
                                        <th>SN</th>                                       
                                        <th>Action</th>
                                        <th>Details</th>   
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $i = 1;
                                    $aclog = $this->db->query("select * from activity_log order by id desc")->result();
                                    if (sizeof($aclog) > 0):
                                        foreach ($aclog as $log):
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>   
                                                <td><?php echo $log->action;?></td>
                                                <td><?php echo $log->details;?></td>
                                                <td><?php echo $log->date;?></td>
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


        <!-- page end-->
    </section>
</section>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#suppliertableid').dataTable({
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            iDisplayLength: 25
        });
    });
</script>
<?php include 'footer.php'; ?>
