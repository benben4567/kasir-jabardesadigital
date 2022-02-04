<?php
class M_warna_barang extends CI_Model 
{
	function get_all()
	{
		return $this->db
			->select('id_warna_barang, warna')
			->where('dihapus', 'tidak')
			->order_by('warna', 'asc')
			->get('pj_warna_barang');
	}

	function fetch_data_warna($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				id_warna_barang, 
				warna 
			FROM 
				`pj_warna_barang`, (SELECT @row := 0) r WHERE 1=1 
				AND dihapus = 'tidak' 
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				warna LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'warna'
		);
		
		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function tambah_warna($warna)
	{
		$dt = array(
			'warna' => $warna,
			'dihapus' => 'tidak'
		);

		return $this->db->insert('pj_warna_barang', $dt);
	}

	function hapus_warna($id_warna_barang)
	{
		$dt = array(
			'dihapus' => 'ya'
		);

		return $this->db
			->where('id_warna_barang', $id_warna_barang)
			->update('pj_warna_barang', $dt);
	}

	function get_baris($id_warna_barang)
	{
		return $this->db
			->select('id_warna_barang, warna')
			->where('id_warna_barang', $id_warna_barang)
			->limit(1)
			->get('pj_warna_barang');
	}

	function update_warna($id_warna_barang, $warna)
	{
		$dt = array(
			'warna' => $warna
		);

		return $this->db
			->where('id_warna_barang', $id_warna_barang)
			->update('pj_warna_barang', $dt);
	}
}