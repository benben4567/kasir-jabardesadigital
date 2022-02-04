<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>

<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			<h5><i class='fa fa-file-text-o fa-fw'></i> Laporan by Merek</h5>
			<hr />

			<?php echo form_open('laporan', array('id' => 'FormLaporan')); ?>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-6 control-label">Dari Tanggal</label>
							<div class="col-sm-5">
								<input type='text' name='from' class='form-control' id='tanggal_dari' value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-6 control-label">Sampai Tanggal</label>
							<div class="col-sm-5">
								<input type='text' name='to' class='form-control' id='tanggal_sampai' value="<?php echo date('Y-m-d'); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-horizontal">
						<div class="form-group">
								<label class="col-sm-2 control-label">Merek</label>
								<div class="col-sm-8">
							 <select id='merek' name='merek' class="form-control">
        <option value='semua'>-- Merek --</option>
        <?php 
        foreach($merek as $merk){
          echo "<option value='".$merk."'>".$merk."</option>";
        }
        ?>
      </select>
						</div></div>
					</div>
				</div>
							
			</div>	

			<div class='row'>
				<div class="col-sm-14">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-sm-4"></div>
							<div class="col-sm-8">
								<button type="submit" class="btn btn-primary" style='margin-left: 522px;'>Tampilkan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>

			<br />
			<div id='result'></div>
		</div>
	</div>
</div>
<p class='footer'><?php echo config_item('web_footer'); ?></p>

<link rel="stylesheet" type="text/css" href="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.css"/>
<script src="<?php echo config_item('plugin'); ?>datetimepicker/jquery.datetimepicker.js"></script>
<script>
$('#tanggal_dari').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
});
$('#tanggal_sampai').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	closeOnDateSelect:true
});

$(document).ready(function(){
	$('#FormLaporan').submit(function(e){
		e.preventDefault();

		var TanggalDari = $('#tanggal_dari').val();
		var TanggalSampai = $('#tanggal_sampai').val();
		var Merek = $('#merek').val();
	

		if(TanggalDari == '' || TanggalSampai == '')
		{
			$('.modal-dialog').removeClass('modal-lg');
			$('.modal-dialog').addClass('modal-sm');
			$('#ModalHeader').html('Oops !');
			$('#ModalContent').html("Tanggal harus diisi !");
			$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Ok, Saya Mengerti</button>");
			$('#ModalGue').modal('show');
		}
		else
		{
			var URL = "<?php echo site_url('laporanmerek/penjualan'); ?>/" + TanggalDari + "/" + TanggalSampai + "/" + Merek;
			$('#result').load(URL);
		}
	});
});
</script>

<?php $this->load->view('include/footer'); ?>