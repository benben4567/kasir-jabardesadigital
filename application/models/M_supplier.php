<?php
class M_supplier extends CI_Model
{
	function get_all()
	{
		return $this->db
			->select('id_supplier, nama, alamat, telp, info_tambahan')
			->where('dihapus', 'tidak')
			->order_by('nama','asc')
			->get('pj_supplier');
	}

	function get_baris($id_supplier)
	{
		return $this->db
			->select('id_supplier nama, alamat, telp, info_tambahan')
			->where('id_supplier', $id_supplier)
			->limit(1)
			->get('pj_supplier');
	}

	function fetch_data_supplier($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_supplier`, 
				a.`nama`, 
				a.`alamat`,
				a.`telp`,
				a.`info_tambahan`,
				DATE_FORMAT(a.`waktu_input`, '%d %b %Y - %H:%i:%s') AS waktu_input 
			FROM 
				`pj_supplier` AS a 
				, (SELECT @row := 0) r WHERE 1=1 
				AND a.`dihapus` = 'tidak' 
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				a.`nama` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`alamat` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`telp` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`info_tambahan` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR DATE_FORMAT(a.`waktu_input`, '%d %b %Y - %H:%i:%s') LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`nama`',
			2 => 'a.`alamat`',
			3 => 'a.`telp`',
			4 => 'a.`info_tambahan`',
			5 => 'a.`waktu_input`'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function tambah_supplier2($nama,$alamat,$telepon,$info,$nmbisnis,$nobisnis,$saldo,$kota,$nopajak,$alamatp,$pembayaran,$unique)
	{
		date_default_timezone_set("Asia/Jakarta");

		$dt = array(
			'nama' => $nama,
			'alamat' => $alamat,
			'telp' => $telepon,
			'info_tambahan' => $info,
			'nmbisnis' => $nmbisnis,
			'nobisnis' => $nobisnis,
			'saldo' => $saldo,
			'kota' => $kota,
			'nopajak' => $nopajak,
			'alamatp' => $alamatp,
			'pembayaran' => $pembayaran,
			'waktu_input' => date('Y-m-d H:i:s'),
			'dihapus' => 'tidak',
			'kode_unik' => $unique
		);

		return $this->db->insert('pj_supplier', $dt);
	}
	function tambah_supplier($nama, $alamat, $telepon, $info, $nmbisnis, $nobisnis, $saldo, $kota, $nopajak, $alamatp, $pembayaran, $unique)
	{
		date_default_timezone_set("Asia/Jakarta");

		$dt = array(
			'nama' => $nama,
			'alamat' => $alamat,
			'telp' => $telepon,
			'info_tambahan' => $info,
			'nmbisnis' => $nmbisnis,
			'nobisnis' => $nobisnis,
			'saldo' => $saldo,
			'kota' => $kota,
			'nopajak' => $nopajak,
			'alamatp' => $alamatp,
			'pembayaran' => $pembayaran,
			'waktu_input' => date('Y-m-d H:i:s'),
			'dihapus' => 'tidak',
			'kode_unik' => $unique
		);

		return $this->db->insert('pj_supplier', $dt);
	}
	function update_supplier($id_supplier, $nama, $alamat, $telepon, $info)
	{
		$dt = array(
			'nama' => $nama,
			'alamat' => $alamat,
			'telp' => $telepon,
			'info_tambahan' => $info
		);

		return $this->db
			->where('id_supplier', $id_supplier)
			->update('pj_supplier', $dt);
	}

	function hapus_supplier($id_supplier)
	{
		$dt = array(
			'dihapus' => 'ya'
		);

		return $this->db
			->where('id_supplier', $id_supplier)
			->update('pj_supplier', $dt);
	}

	function get_dari_kode($kode_unik)
	{
		return $this->db
			->select('id_supplier')
			->where('kode_unik', $kode_unik)
			->limit(1)
			->get('pj_supplier');
	}
}