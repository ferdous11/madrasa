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
                        বেতন আদায়
                    </header>
                    <div class="panel-body">
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

                        <div class="row">
                            <div class="col-md-12">
                            <a style="float: left;" class="btn btn-danger" href="<?=$baseurl;?>"><i class="fa fa-arrow-left"></i> পূর্ববর্তী পেজে ফিরে যান </a>
                                <span style="float: right"><a  class="btn btn-success" href="<?php echo $baseurl;?>teacher/add_student/"><i class="fa fa-plus"></i>&nbsp;নতুন তালেবে এলেম </a></span>
                               
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        যে সকল ফি এডমিন কে জমা দেয়া হয় নাই
                    </header>
                    <div class="panel-body">
                        <table class="display table table-bordered table-striped dataTable">
                            <tr>
                                <th>SL</th>
                                <th>নাম</th>
                                <th>ID</th>
                                <th>শ্রেণি</th>
                                <th>RL</th>
                                <th>তারিখ</th>
                                <th>বিবরন</th>
                                <th>টাকা</th>
                            </tr>
                            <?php $i=1;$ptotal=0; if(!empty($notpaid)): foreach ($notpaid as $key): ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                               
                                <td><?php echo $key->ledgername;?></td>
                                <td><?php echo $key->ledgerid;?></td>
                                <td><?php echo $key->class_name;?></td>
                                <td><?php echo $key->roll;?></td>
                                <td><?php echo $key->date;?></td>
                                <td><?php echo $key->description;?></td>
                                <td><?php $ptotal+=$key->credit; echo $key->credit;?></td>
                            </tr>
                            <?php endforeach;endif;?>

                            <tr>
                                <td colspan="7" style="text-align: right;">মোটঃ</td>
                                
                                <td style="text-align: right;">
                                    <label style="text-align: right;" ><b>=<?=$ptotal;?></b></label>
                                </td>
                            </tr>
                        </table> 
                    </div>  
                </section>                               
            </div>
            <div class="form-group">
                <div class="col-lg-offset-4 col-lg-8">
                    <span style="float: right;margin-bottom: 20px;"><a  class="btn btn-success" href="<?php echo $baseurl.'teacher/smstoadmin/'.$ptotal;?>"><i class="fa fa-plus"></i>&nbsp;সকল ফি এডমিনকে জমা দিন </a></span>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        যে সকল ফি এডমিন কে জমা দেয়া হয়েছে
                    </header>
                    <div class="panel-body">
                        <table class="display table table-bordered table-striped dataTable">
                            <tr>
                                <th>SL.</th>
                                <th>নাম</th>
                                <th>ID</th>
                                <th>শ্রেণি</th>
                                <th>RL</th>
                                <th>তারিখ</th>
                                <th>বিবরন</th>
                                <th>টাকা</th>
                            </tr>
                            <?php $i=1;$ptotal=0; if(!empty($paid)): foreach ($paid as $key): ?>
                            <tr>
                                <td><?php echo $i++;?></td>
                                
                                <td><?php echo $key->ledgername;?></td>
                                <td><?php echo $key->ledgerid;?></td>
                                <td><?php echo $key->class_name;?></td>
                                <td><?php echo $key->roll;?></td>
                                <td><?php echo $key->date;?></td>
                                <td><?php echo $key->description;?></td>
                                <td><?php $ptotal+=$key->credit; echo $key->credit;?></td>
                            </tr>
                            <?php endforeach;endif;?>

                            <tr>
                                <td colspan="7" style="text-align: right;">মোটঃ</td>
                                
                                <td style="text-align: right;">
                                    <label style="text-align: right;" ><b>=<?=$ptotal;?></b></label>
                                </td>
                            </tr>
                        </table> 
                    </div>  
                </section>                               
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
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