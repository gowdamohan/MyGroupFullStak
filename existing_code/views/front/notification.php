
<?php if($this->session->flashdata('flashSuccess')) { ?>
<script type="text/javascript">
var msg = '<?php echo $this->session->flashdata("flashSuccess"); ?>';
$(function(){
    new PNotify({
        title: 'Success',
        text: msg,
        type: 'success',
    });
});

</script>
<?php } ?>
 
<?php if($this->session->flashdata('flashError')) { ?>
<script type="text/javascript">
var msg = '<?php echo $this->session->flashdata("flashError");?>';
	$(function(){
        new PNotify({
            title: 'Error',
            text: msg,
            type: 'error',
        });
    });
</script>
<?php } ?>
 
<?php if($this->session->flashdata('flashInfo')) { ?>
<script type="text/javascript">
var msg = '<?php echo $this->session->flashdata("flashInfo");?>';
	$(function(){
        new PNotify({
            title: 'Info',
            text: msg,
            type: 'info',
        });
    });
</script>
<?php } ?>
 
<?php if($this->session->flashdata('flashWarning')) { ?>

<script type="text/javascript">
var msg = '<?php echo $this->session->flashdata("flashWarning");?>';
    $(function(){
        new PNotify({
            title: 'Warning',
            text: msg,
            type: 'warning',
        });
    });
</script>
<?php }
?>
                             