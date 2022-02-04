<?php echo form_open('barang/edit-warna/'.$warna->id_warna_barang, array('id' => 'FormEditWarna')); ?>
<div class='form-group'>
	<?php
	echo form_input(array(
		'name' => 'warna', 
		'class' => 'form-control',
		'value' => $warna->warna
	));
	?>
</div>
<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function EditWarna()
{
	$.ajax({
		url: $('#FormEditWarna').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormEditWarna').serialize(),
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
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanEditWarna'>Update Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormEditWarna").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanEditWarna').click(function(e){
		e.preventDefault();
		EditWarna();
	});

	$('#FormEditWarna').submit(function(e){
		e.preventDefault();
		EditWarna();
	});
});
</script>