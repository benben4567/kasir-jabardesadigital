<?php echo form_open('pembelian/tambah-supplier', array('id' => 'FormTambahSupplier')); ?>
<div class="row">
	<div class="col-md-4 contact_type_div">
<div class='form-group'>
	<label>Nama Supplier</label>
	<input type='text' name='nama' class='form-control'>
</div>
</div>
	<div class="col-md-4 contact_type_div">
<div class='form-group'>
	<label>Nama Bisnis</label>
	<input type='text' name='nmbisnis' class='form-control'>
</div>
</div>
<div class="col-md-4 contact_type_div">
<div class='form-group'>
	<label>No. Hp</label>
	<input type='text' name='telepon' class='form-control'>
</div>
</div>
<div class="col-md-4 contact_type_div">
<div class='form-group'>
	<label>No.Telp Bisnis</label>
	<input type='text' name='nobisnis' class='form-control'>
</div>
</div>
<div class="col-md-4 contact_type_div">
<div class='form-group'>
	<label>Saldo Awal</label>
	<input type='text' name='saldo' class='form-control'>
</div>
</div>
<div class="col-md-4 contact_type_div">
<div class='form-group'>
	<label>Kota</label>
	<input type='text' name='kota' class='form-control'>
</div>
</div>
<div class="col-md-6 contact_type_div">
<div class='form-group'>
	<label>Alamat </label>
	<input type='text' name='alamat' class='form-control'>
</div>
</div>
<div class="col-md-6 contact_type_div">
<div class='form-group'>
	<label>Nomor Pajak</label>
	<input type='text' name='nopajak' class='form-control'>
</div>
</div>
<div class="col-md-6 contact_type_div">
<div class='form-group'>
	<label>Alamat Pengiriman</label>
	<input type='text' name='alamatp' class='form-control'>
</div>
</div>
<div class="col-md-6 contact_type_div">
<div class='form-group'>
	<label>Pembayaran</label>
	<select class="form-control" name="pembayaran"><option value="cash">Cash</option><option value="hutang">Hutang</option></select>
</div>
</div>
<div class="col-md-12 contact_type_div">
<div class='form-group'>
	<label>Info Tambahan Lainnya</label>
	<textarea name='info' class='form-control' style='resize:vertical;'></textarea>
</div>
</div>
</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function TambahSupplier()
{
	$.ajax({
		url: $('#FormTambahSupplier').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormTambahSupplier').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1)
			{ 
				$('#FormTambahSupplier').each(function(){
					this.reset();
				});

				if(document.getElementById('SupplierArea') != null)
				{
					$('#ResponseInput').html('');

					$('.modal-dialog').removeClass('modal-lg');
					$('.modal-dialog').addClass('modal-sm');
					$('#ModalHeader').html('Berhasil');
					$('#ModalContent').html(json.pesan);
					$('#ModalFooter').html("<button type='button' class='btn btn-primary' data-dismiss='modal' autofocus>Okay</button>");
					$('#ModalGue').modal('show');

					$('#id_supplier').append("<option value='"+json.id_supplier+"' selected>"+json.nama+"</option>");
					$('#telp_supplier').html(json.telepon);
					$('#alamat_supplier').html(json.alamat);
					$('#info_tambahan_supplier').html(json.info);
				}
				else
				{
					$('#ResponseInput').html(json.pesan);
					setTimeout(function(){ 
				   		$('#ResponseInput').html('');
				    }, 3000);
					$('#my-grid').DataTable().ajax.reload( null, false );
				}
			}
			else 
			{
				$('#ResponseInput').html(json.pesan);
			}
		}
	});
}

$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahSupplier'>Simpan Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormTambahSupplier").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanTambahSupplier').click(function(e){
		e.preventDefault();
		TambahSupplier();
	});

	$('#FormTambahSupplier').submit(function(e){
		e.preventDefault();
		TambahSupplier();
	});
});
</script>