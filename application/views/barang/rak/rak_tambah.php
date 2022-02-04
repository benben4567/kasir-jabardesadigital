<?php echo form_open('barang/tambah-rak', array('id' => 'FormTambahRak')); ?>
<div class='form-group'>
	<input type='text' name='rak' class='form-control'>
</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function TambahRak()
{
	$.ajax({
		url: $('#FormTambahRak').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormTambahRak').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1){ 
				$('#ResponseInput').html(json.pesan);
				setTimeout(function(){ 
			   		$('#ResponseInput').html('');
			    }, 3000);
				$('#my-grid').DataTable().ajax.reload( null, false );

				$('#FormTambahRak').each(function(){
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
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahRak'>Simpan Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormTambahRak").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanTambahRak').click(function(e){
		e.preventDefault();
		TambahRak();
	});

	$('#FormTambahRak').submit(function(e){
		e.preventDefault();
		TambahRak();
	});
});
</script>