<?php echo form_open('barang/tambah-display-rak', array('id' => 'FormTambahDisplayRak')); ?>
<div class="form-horizontal">
	<div class="form-group">
		<label class="col-lg-3 control-label">Nama Rak</label>
		<div class="col-lg-8">
		<select name='rak' class='form-control'>
				<option value=''></option>
				<?php
				foreach($rak->result() as $k)
				{
								echo "<option value='".$k->rak."'>".$k->rak."</option>";
				}
				?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3 control-label">Produk</label>
		<div class="col-lg-8">
		<select name='produk' class='form-control'>
				<option value=''></option>
				<?php
				foreach($produk->result() as $p)
				{
								echo "<option value='".$p->id_barang."'>".$p->nama_barang." (".$p->gender.")</option>";
				}
				?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-3 control-label">Keterangan</label>
		<div class="col-lg-8">
		<textarea name='keterangan' class='form-control'></textarea> 
		</div>
	</div>
</div>

<?php echo form_close(); ?>

<div id='ResponseInput'></div>

<script>
function TambahDisplayRak()
{
	$.ajax({
		url: $('#FormTambahDisplayRak').attr('action'),
		type: "POST",
		cache: false,
		data: $('#FormTambahDisplayRak').serialize(),
		dataType:'json',
		success: function(json){
			if(json.status == 1){ 
				$('#ResponseInput').html(json.pesan);
				setTimeout(function(){ 
			   		$('#ResponseInput').html('');
			    }, 3000);
				$('#my-grid').DataTable().ajax.reload( null, false );

				$('#FormTambahDisplayRak').each(function(){
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
	var Tombol = "<button type='button' class='btn btn-primary' id='SimpanTambahDisplayRak'>Simpan Data</button>";
	Tombol += "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>";
	$('#ModalFooter').html(Tombol);

	$("#FormTambahDisplayRak").find('input[type=text],textarea,select').filter(':visible:first').focus();

	$('#SimpanTambahDisplayRak').click(function(e){
		e.preventDefault();
		TambahDisplayRak();
	});

	$('#FormTambahDisplayRak').submit(function(e){
		e.preventDefault();
		TambahDisplayRak();
	});
});
</script>