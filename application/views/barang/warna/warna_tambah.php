<?php echo form_open('barang/tambah-warna', array('id' => 'FormTambahWarna')); ?>
<div class='form-group'>
	<input type='text' name='warna' class='form-control'>
</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function TambahWarna()
{
	$.ajax({
		url: $('#FormTambahWarna').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormTambahWarna').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1){ 
				$('#ResponseInput').html(json.pesan);
				setTimeout(function(){ 
			   		$('#ResponseInput').html('');
			    }, 3000);
				$('#my-grid').DataTable().ajax.reload( null, false );

				$('#FormTambahWarna').each(function(){
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
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahWarna'>Simpan Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormTambahWarna").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanTambahWarna').click(function(e){
		e.preventDefault();
		TambahWarna();
	});

	$('#FormTambahWarna').submit(function(e){
		e.preventDefault();
		TambahWarna();
	});
});
</script>