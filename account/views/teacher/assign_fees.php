<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
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

        <div class="align-items-center">
            <form id="form1"  method="post" action="<?php echo site_url('teacher/students_list'); ?>"  id="tform" enctype="multipart/form-data">
              
                
            </form>
        </div>


	</section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script type="text/javascript">
    var roll=1;
    var lastroll = $("#lastroll").val();


   function updateinfo(sid,ledgerid){
        var name = $("#name-"+sid).val();
        var call_name = $("#call_name-"+sid).val();
        var roll = $("#roll-"+sid).val();
        var guardian_name = $("#guardian_name-"+sid).val();
        var mobile = $("#mobile-"+sid).val();
        var status = $("#status-"+sid).val();
        var postdata = 'id=' + sid +'&&name='+name+'&&call_name='+call_name+'&&roll='+roll+'&&guardian_name='+guardian_name+'&&guardian_mobile='+mobile+'&&status='+status+'&&ledger_id='+ledgerid;
        console.log(postdata);

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("teacher/updatestudent"); ?>',
            data: postdata,
            success: function (response) {
              $("#lbl-"+sid).hide();   
            }
        });
            
   }

</script>

