<?php
class M_rak_barang extends CI_Model 
{
	function get_all()
	{
		return $this->db
			->select('id_rak_barang, rak')
			->where('dihapus', 'tidak')
			->order_by('rak', 'asc')
			->get('pj_rak_barang');
	}
function get_all_barang()
	{
		return $this->db
			->select('*')
			->where('dihapus', 'tidak')
			->order_by('id_barang', 'asc')
			->get('pj_barang');
	}
	function fetch_data_rak($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				id_rak_barang, 
				rak 
			FROM 
				`pj_rak_barang`, (SELECT @row := 0) r WHERE 1=1 
				AND dihapus = 'tidak' 
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				rak LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'rak'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}
function fetch_data_display_rak($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_display_rak_barang`, 
				a.`rak`,
				a.`produk`,
				a.`tgl`,
				a.`keterangan`,
				b.`nama_barang`,
				b.`gender`

			FROM 
				`pj_display_rak_barang` as a
				LEFT JOIN `pj_barang` AS b ON b.`id_barang` = a.`produk` , (SELECT @row := 0) r WHERE 1=1 
				AND a.`dihapus` = 'tidak' 
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				rak LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'rak'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}
		function tambah_display_rak($rak,$produk,$keterangan)
	{
		$dt = array(
			'rak' => $rak,
			'produk' => $produk,
			'tgl' => date('d-m-Y'),
			'keterangan' => $keterangan,
			'dihapus' => 'tidak'
		);

		return $this->db->insert('pj_display_rak_barang', $dt);
	}

	function tambah_rak($rak)
	{
		$dt = array(
			'rak' => $rak,
			'dihapus' => 'tidak'
		);

		return $this->db->insert('pj_rak_barang', $dt);
	}

	function hapus_rak($id_rak_barang)
	{
		$dt = array(
			'dihapus' => 'ya'
		);

		return $this->db
			->where('id_rak_barang', $id_rak_barang)
			->update('pj_rak_barang', $dt);
	}
function hapus_display_rak($id_rak_barang)
	{
		$dt = array(
			'dihapus' => 'ya'
		);

		return $this->db
			->where('id_dislpay_rak_barang', $id_rak_barang)
			->update('pj_display_rak_barang', $dt);
	}

	function get_baris($id_rak_barang)
	{
		return $this->db
			->select('id_rak_barang, rak')
			->where('id_rak_barang', $id_rak_barang)
			->limit(1)
			->get('pj_rak_barang');
	}
	function get_baris_display($id_display_rak_barang)
	{
		return $this->db
			->select('id_display_rak_barang, rak')
			->where('id_display_rak_barang', $id_display_rak_barang)
			->limit(1)
			->get('pj_display_rak_barang');
	}
		function get_all_rak()
	{
		return $this->db
			->select('id_display_rak_barang, rak')
			->where('dihapus', 'tidak')
			->order_by('rak', 'asc')
			->get('pj_display_rak_barang');
	}


	function update_rak($id_rak_barang, $rak)
	{
		$dt = array(
			'rak' => $rak
		);

		return $this->db
			->where('id_rak_barang', $id_rak_barang)
			->update('pj_rak_barang', $dt);
	}
}