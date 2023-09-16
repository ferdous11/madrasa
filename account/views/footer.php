<!--main content end-->
<!--footer start-->
<footer class="site-footer" style="position: fixed;bottom: 0px;width: 100%">
    <div class="text-center">
        <?php echo '@2021 F&U Technology' ?>
        <a href="#" class="go-top">
            <i class="fa fa-angle-up"></i> 
        </a>
    </div>
</footer>
<!--footer end-->
</section>
<script src="<?php echo $baseurl; ?>assets/js/fu.js" type="text/javascript"></script>
<script src="<?php echo $baseurl; ?>assets/js/chosen.jquery.js" type="text/javascript"></script>
<script class="include" type="text/javascript" src="<?php echo $baseurl; ?>assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo $baseurl; ?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo $baseurl; ?>assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo $baseurl; ?>assets/js/jquery.sparkline.js" type="text/javascript"></script>
<!--<script src="<?php echo $baseurl; ?>assets/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>-->
<script src="<?php echo $baseurl; ?>assets/js/owl.carousel.js" ></script>
<script src="<?php echo $baseurl; ?>assets/js/jquery.customSelect.min.js" ></script>
<script src="<?php echo $baseurl; ?>assets/js/respond.min.js" ></script>
<script type="text/javascript" src="<?php echo $baseurl; ?>assets/js/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $baseurl; ?>assets/js/data-tables/DT_bootstrap.js"></script>

<!--script for this page-->

<script src="<?php echo $baseurl; ?>assets/js/count.js"></script>

<!--this page plugins-->

<script type="text/javascript" src="<?php echo $baseurl; ?>assets/js/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="<?php echo $baseurl; ?>assets/js/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="<?php echo $baseurl; ?>assets/js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<!-- <script src="<?php echo $baseurl; ?>assets/bootstrap5.0.0/js/bootstrap.bundle.min.js"></script> -->
<script type="text/javascript" src="<?php echo $baseurl; ?>assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo $baseurl; ?>assets/js/jquery.datetimepicker.js"></script>

<script src="<?php echo $baseurl; ?>assets/js/common-scripts.js"></script>
<!--gitter files -->
<script type="text/javascript" src="<?php echo $baseurl; ?>assets/assets/gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="<?php echo $baseurl; ?>assets/js/gritter.js" ></script>
<script src="<?php echo $baseurl; ?>assets/js/jspdf.debug.js"></script>
<script src="<?php echo $baseurl; ?>assets/js/init.js" type="text/javascript" charset="utf-8"></script>



<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#example').dataTable({
            "aaSorting": [[4, "desc"]]
        });
    });
    $(document).ready(function () {
        $('#examplef').dataTable({
            "aaSorting": [[1, "desc"]]
        });
    });

    $(document).ready(function () {
        $('#example1').dataTable({
            "aaSorting": [[4, "desc"]]
        });
    });
</script>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#example2').dataTable({
            "aaSorting": [[4, "desc"]]
        });
    });
</script>

<script type="text/javascript">
    var today = "<?php echo date("Y-m-d H:i:s"); ?>";
    $('#combodateFrom').datepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: today,
        minDate: '1970-01-01',
        maxDate: '2050-01-01',
        timepicker: false
    }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });

    $('#combodateTo').datepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        autoclose: true,
        startDate: today,
        minDate: '1970-01-01',
        maxDate: '2050-01-01',
        timepicker: false
    }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });
</script>
<script type="text/javascript">
    var today = "<?php echo date("Y-m-d H:i:s"); ?>";
    $('#sdate').datepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: today,
        minDate: '1970-01-01',
        maxDate: '2050-01-01',
        timepicker: false
    }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });

    $('#purdate').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'Y-m-d h:i:s',
        disabledDates: ['1986-01-08', '1986-01-09', '1986-01-10'],
        startDate: today,
        minDate: '2017-01-01',
        maxDate: '2050-01-01',
        timepicker: false
    });
    $('.edit-col-date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        format: 'Y-m-d h:i:s',
        disabledDates: ['1986-01-08', '1986-01-09', '1986-01-10'],
        startDate: today,
        minDate: '2017-01-01',
        maxDate: '2050-01-01',
        timepicker: false
    });

    $('#edate').datepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        autoclose: true,
        startDate: today,
        minDate: '1970-01-01',
        maxDate: '2050-01-01',
        timepicker: false
    }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });

</script>

</body>
</html>
