<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/navbar'); ?>

<?php
$level = $this->session->userdata('ap_level');
?>

<div class="container">
	<div class="panel panel-default">
		<div class="panel-body">
			<h5><i class='fa fa-cube fa-fw'></i> Barang <i class='fa fa-angle-right fa-fw'></i> Semua Barang</h5>
			<?php
$tambahan = '';
if($level == 'admin' OR $level == 'inventory')
{
	$tambahan .= nbs(2)."<a href='".site_url('barang/tambah')."' class='btn btn-default' id='TambahBarang'><i class='fa fa-plus fa-fw'></i> Tambah Barang</a>";
	$tambahan .= nbs(2)."<span id='Notifikasi' style='display: none;'></span>";
}
echo $tambahan;
?>
			<hr />
		
      <!-- City -->
      <div class="row">
      	   <div class="col-sm-2">
      <select id='sel_gender' class="form-control">
        <option value=''>-- Gender --</option>
        <option value='Laki-laki'>Laki-laki</option>
        <option value='Perempuan'>Perempuan</option>
      </select>
 </div>
      <div class="col-sm-2">
      <select id='sel_city' class="form-control">
        <option value=''>-- Kategori --</option>
        <?php 
        foreach($cities as $city){
          echo "<option value='".$city."'>".$city."</option>";
        }
        ?>
      </select>
</div>
 <div class="col-sm-2">
      <select id='sel_merk' class="form-control">
        <option value=''>-- Merek --</option>
        <?php 
        foreach($merek as $merk){
          echo "<option value='".$merk."'>".$merk."</option>";
        }
        ?>
      </select>
</div>

   <div class="col-sm-2">
      <select id='sel_warna' class="form-control">
        <option value=''>-- Warna --</option>
        <?php 
        foreach($warna as $warn){
          echo "<option value='".$warn."'>".$warn."</option>";
        }
        ?>
      </select>
</div>
 <div class="col-sm-2">
      <select id='sel_rak' class="form-control">
        <option value=''>-- Rak --</option>
        <?php 
        foreach($rak as $rk){
          echo "<option value='".$rk."'>".$rk."</option>";
        }
        ?>
      </select>
</div>
    </div>

			<div class='table-responsive'>


    <!-- Table -->
    <table id='userTable' class='display dataTable'>

      <thead>
        <tr>
          <th>Kode</th>
          <th>Nama Barang</th>
           <th>Kategori</th>
           <th>Size</th>
            <th>Merek</th>
          <th>Harga</th>
          <th>Warna</th>
          <th>Gender</th>
          <th>Rak</th>
        </tr>
      </thead>

    </table>

    <!-- Script -->
    <script type="text/javascript">
    $(document).ready(function(){

       var userDataTable = $('#userTable').DataTable({
         'processing': true,
         'serverSide': true,
         'serverMethod': 'post',
         //'searching': false, // Remove default Search Control
         'ajax': {
            'url':'<?=base_url()?>index.php/Barang/userList',
            'data': function(data){
               data.searchCity = $('#sel_city').val();
                data.searchMerk = $('#sel_merk').val();
               data.searchGender = $('#sel_gender').val();
               data.searchWarna = $('#sel_warna').val();
               data.searchRak = $('#sel_rak').val();
               data.searchName = $('#searchName').val();
            }
         },
         'columns': [
            { data: 'kode_barang' },
            { data: 'nama_barang' },
            { data: 'kategori' },
            { data: 'size' },
             { data: 'merk' },
            { data: 'harga' },
             { data: 'warna' },
            { data: 'gender' },
            { data: 'rak' },
          
         ]
       });

       $('#sel_city,#sel_gender,#sel_warna,#sel_rak,#sel_merk').change(function(){
          userDataTable.draw();
       });
       $('#searchName').keyup(function(){
          userDataTable.draw();
       });
    });
    </script>
			</div>
		</div>
	</div>
</div>
<p class='footer'><?php echo config_item('web_footer'); ?></p>

<script type="text/javascript" language="javascript" >
	
	
	$(document).on('click', '#HapusBarang', function(e){
		e.preventDefault();
		var Link = $(this).attr('href');

		$('.modal-dialog').removeClass('modal-lg');
		$('.modal-dialog').addClass('modal-sm');
		$('#ModalHeader').html('Konfirmasi');
		$('#ModalContent').html('Apakah anda yakin ingin menghapus <br /><b>'+$(this).parent().parent().find('td:nth-child(3)').html()+'</b> ?');
		$('#ModalFooter').html("<button type='button' class='btn btn-primary' id='YesDelete' data-url='"+Link+"'>Ya, saya yakin</button><button type='button' class='btn btn-default' data-dismiss='modal'>Batal</button>");
		$('#ModalGue').modal('show');
		
	});

	$(document).on('click', '#YesDelete', function(e){
		e.preventDefault();
		$('#ModalGue').modal('hide');

		$.ajax({
			url: $(this).data('url'),
			type: "POST",
			cache: false,
			dataType:'json',
			success: function(data){
				$('#Notifikasi').html(data.pesan);
				$("#Notifikasi").fadeIn('fast').show().delay(3000).fadeOut('fast');
				$('#userTable').DataTable().ajax.reload( null, false );
			}
		});
	});

	$(document).on('click', '#TambahBarang, #EditBarang', function(e){
		e.preventDefault();
		if($(this).attr('id') == 'TambahBarang')
		{
			$('.modal-dialog').removeClass('modal-sm');
			$('.modal-dialog').addClass('modal-lg');
			$('#ModalHeader').html('Tambah Barang');
		}
		if($(this).attr('id') == 'EditBarang')
		{
			$('.modal-dialog').removeClass('modal-sm');
			$('.modal-dialog').removeClass('modal-lg');
			$('#ModalHeader').html('Edit Barang');
		}
		$('#ModalContent').load($(this).attr('href'));
		$('#ModalGue').modal('show');
	});

	$(document).on('keyup', '.kode_barang', function(){
		$(this).parent().find('span').html("");

		var Kode = $(this).val();
		var Indexnya = $(this).parent().parent().index();
		var Pass = 0;
		$('#TabelTambahBarang tbody tr').each(function(){
			if(Indexnya !== $(this).index())
			{
				var KodeLoop = $(this).find('td:nth-child(2) input').val();
				if(KodeLoop !== '')
				{
					if(KodeLoop == Kode){
						Pass++;
					}
				}
			}
		});

		if(Pass > 0)
		{
			$(this).parent().find('span').html("<font color='red'>Kode sudah ada</font>");
			$('#SimpanTambahBarang').addClass('disabled');
		}
		else
		{
			$(this).parent().find('span').html('');
			$('#SimpanTambahBarang').removeClass('disabled');

			$.ajax({
				url: "<?php echo site_url('barang/ajax-cek-kode'); ?>",
				type: "POST",
				cache: false,
				data: "kodenya="+Kode,
				dataType:'json',
				success: function(json){
					if(json.status == 0){ 
						$('#TabelTambahBarang tbody tr:eq('+Indexnya+') td:nth-child(2)').find('span').html(json.pesan);
						$('#SimpanTambahBarang').addClass('disabled');
					}
					if(json.status == 1){ 
						$('#SimpanTambahBarang').removeClass('disabled');
					}
				}
			});
		}
	});
</script>
<script type="text/javascript" language="javascript" src="<?php echo config_item('plugin'); ?>datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo config_item('plugin'); ?>datatables/js/dataTables.bootstrap.js"></script>

<?php $this->load->view('include/footer'); ?>