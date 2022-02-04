<?php echo form_open('barang/tambah-kategori', array('id' => 'FormTambahKategori')); ?>
<div class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-6 control-label">Nama Kategori</label>
		<div class="col-sm-12">
			<input type='text' name='kategori' class='form-control'>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-6 control-label">Sub Kategori 1</label>
		<div class="col-sm-12">
		<input type='text' name='subkategori' class='form-control'>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-6 control-label">Sub Kategori 2</label>
		<div class="col-sm-12">
		<input type='text' name='subkategori2' class='form-control'>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-6 control-label">Sub Kategori 3</label>
		<div class="col-sm-12">
		<input type='text' name='subkategori3' class='form-control'>
		</div>
	</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function TambahKategori()
{
	$.ajax({
		url: $('#FormTambahKategori').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormTambahKategori').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1){ 
				$('#ResponseInput').html(json.pesan);
				setTimeout(function(){ 
			   		$('#ResponseInput').html('');
			    }, 3000);
				$('#my-grid').DataTable().ajax.reload( null, false );

				$('#FormTambahKategori').each(function(){
					this.reset();
				});
			}
			else {
				$('#ResponseInput').html(json.pesan);
			}
		}
	});
}

$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahKategori'>Simpan Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormTambahKategori").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanTambahKategori').click(function(e){
		e.preventDefault();
		TambahKategori();
	});

	$('#FormTambahKategori').submit(function(e){
		e.preventDefault();
		TambahKategori();
	});
});
</script>