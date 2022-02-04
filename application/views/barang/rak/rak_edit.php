<?php echo form_open('barang/edit-rak/'.$rak->id_rak_barang, array('id' => 'FormEditRak')); ?>
<div class='form-group'>
	<?php
	echo form_input(array(
		'name' => 'rak', 
		'class' => 'form-control',
		'value' => $rak->rak
	));
	?>
</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function EditRak()
{
	$.ajax({
		url: $('#FormEditRak').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormEditRak').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1){ 
				$('#ResponseInput').html(json.pesan);
				setTimeout(function(){ 
			   		$('#ResponseInput').html('');
			    }, 3000);
				$('#my-grid').DataTable().ajax.reload( null, false );
			}
			else {
				$('#ResponseInput').html(json.pesan);
			}
		}
	});
}

$(document).ready(function(){
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanEditRak'>Update Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormEditRak").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanEditRak').click(function(e){
		e.preventDefault();
		EditRak();
	});

	$('#FormEditRak').submit(function(e){
		e.preventDefault();
		EditRak();
	});
});
</script>