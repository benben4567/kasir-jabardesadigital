<?php
class M_penjualan_master extends CI_Model
{
	function insert_master($nomor_nota, $tanggal, $id_kasir, $id_pelanggan, $bayar, $grand_total, $catatan)
	{
		$dt = array(
			'nomor_nota' => $nomor_nota,
			'tanggal' => $tanggal,
			'grand_total' => $grand_total,
			'bayar' => $bayar,
			'keterangan_lain' => $catatan,
			'id_pelanggan' => (empty($id_pelanggan)) ? NULL : $id_pelanggan,
			'id_user' => $id_kasir
		);

		return $this->db->insert('pj_penjualan_master', $dt);
	}

	function get_id($nomor_nota)
	{
		return $this->db
			->select('id_penjualan_m')
			->where('nomor_nota', $nomor_nota)
			->limit(1)
			->get('pj_penjualan_master');
	}

	function fetch_data_penjualan($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
	{
		$sql = "
			SELECT 
				(@row:=@row+1) AS nomor, 
				a.`id_penjualan_m`, 
				a.`nomor_nota` AS nomor_nota, 
				DATE_FORMAT(a.`tanggal`, '%d %b %Y - %H:%i:%s') AS tanggal,
				CONCAT('Rp. ', REPLACE(FORMAT(a.`grand_total`, 0),',','.') ) AS grand_total,
				IF(b.`nama` IS NULL, 'Umum', b.`nama`) AS nama_pelanggan,
				c.`nama` AS kasir,
				a.`keterangan_lain` AS keterangan   
			FROM 
				`pj_penjualan_master` AS a 
				LEFT JOIN `pj_pelanggan` AS b ON a.`id_pelanggan` = b.`id_pelanggan` 
				LEFT JOIN `pj_user` AS c ON a.`id_user` = c.`id_user` 
				, (SELECT @row := 0) r WHERE 1=1 
		";
		
		$data['totalData'] = $this->db->query($sql)->num_rows();
		
		if( ! empty($like_value))
		{
			$sql .= " AND ( ";    
			$sql .= "
				a.`nomor_nota` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR DATE_FORMAT(a.`tanggal`, '%d %b %Y - %H:%i:%s') LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR CONCAT('Rp. ', REPLACE(FORMAT(a.`grand_total`, 0),',','.') ) LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR IF(b.`nama` IS NULL, 'Umum', b.`nama`) LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR c.`nama` LIKE '%".$this->db->escape_like_str($like_value)."%' 
				OR a.`keterangan_lain` LIKE '%".$this->db->escape_like_str($like_value)."%' 
			";
			$sql .= " ) ";
		}
		
		$data['totalFiltered']	= $this->db->query($sql)->num_rows();
		
		$columns_order_by = array( 
			0 => 'nomor',
			1 => 'a.`tanggal`',
			2 => 'nomor_nota',
			3 => 'a.`grand_total`',
			4 => 'nama_pelanggan',
			5 => 'keterangan',
			6 => 'kasir'
		);

		$sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
		$sql .= " LIMIT ".$limit_start." ,".$limit_length." ";
		
		$data['query'] = $this->db->query($sql);
		return $data;
	}

	function get_baris($id_penjualan)
	{
		$sql = "
			SELECT 
				a.`nomor_nota`, 
				a.`grand_total`,
				a.`tanggal`,
				a.`bayar`,
				a.`id_user` AS id_kasir,
				a.`id_pelanggan`,
				a.`keterangan_lain` AS catatan,
				b.`nama` AS nama_pelanggan,
				b.`alamat` AS alamat_pelanggan,
				b.`telp` AS telp_pelanggan,
				b.`info_tambahan` AS info_pelanggan 
			FROM 
				`pj_penjualan_master` AS a 
				LEFT JOIN `pj_pelanggan` AS b ON a.`id_pelanggan` = b.`id_pelanggan` 
			WHERE 
				a.`id_penjualan_m` = '".$id_penjualan."' 
			LIMIT 1
		";
		return $this->db->query($sql);
	}

	function hapus_transaksi($id_penjualan, $reverse_stok)
	{
		if($reverse_stok == 'yes'){
			$loop = $this->db
				->select('id_barang, jumlah_beli')
				->where('id_penjualan_m', $id_penjualan)
				->get('pj_penjualan_detail');

			foreach($loop->result() as $b)
			{
				$sql = "
					UPDATE `pj_barang` SET `total_stok` = `total_stok` + ".$b->jumlah_beli." 
					WHERE `id_barang` = '".$b->id_barang."' 
				";

				$this->db->query($sql);
			}
		}

		$this->db->where('id_penjualan_m', $id_penjualan)->delete('pj_penjualan_detail');
		return $this->db
			->where('id_penjualan_m', $id_penjualan)
			->delete('pj_penjualan_master');
	}

	function laporan_penjualan($from, $to)
	{
		
		$sql = "
			SELECT 
				DISTINCT(SUBSTR(a.`tanggal`, 1, 10)) AS tanggal,d.`nama_barang`,c.`jumlah_beli`,f.`kategori`,e.`merk`,g.`warna`,
				(
					SELECT 
						SUM(b.`grand_total`) 
					FROM 
						`pj_penjualan_master` AS b 
					WHERE 
						SUBSTR(b.`tanggal`, 1, 10) = SUBSTR(a.`tanggal`, 1, 10) 
					LIMIT 1
				) AS total_penjualan 
			FROM 
				`pj_penjualan_master` AS a 
				LEFT JOIN `pj_penjualan_detail` AS c ON c.`id_penjualan_m` = a.`id_penjualan_m` 
		    	LEFT JOIN `pj_barang` AS d ON d.`id_barang` = c.`id_barang`
				LEFT JOIN `pj_merk_barang` AS e ON e.`id_merk_barang` = d.`id_merk_barang`
				LEFT JOIN `pj_kategori_barang` AS f ON f.`id_kategori_barang` = d.`id_barang`
			    LEFT JOIN `pj_warna_barang` AS g ON g.`id_warna_barang` = d.`id_warna`
				
			WHERE 
				SUBSTR(a.`tanggal`, 1, 10) >= '2020-01-28' 
				AND SUBSTR(a.`tanggal`, 1, 10) <= '2021-01-28' 
			  
			ORDER BY 
				a.`tanggal` ASC
		";

		return $this->db->query($sql);
	}
function laporan_penjualan_rak($from, $to, $rak, $group)
	{
	//	$group = 'Perempuan';
		$query = '';
        if($group!='semua'){
            $query = 'AND d.gender = "'.$group.'"';
        }
		       
		$sql = "
			SELECT 
				DISTINCT(SUBSTR(a.`tanggal`, 1, 10)) AS tanggal,a.`nomor_nota`,c.`produk`,c.`rak`,d.`nama_barang`,d.`gender`,
				(
					SELECT 
						SUM(b.`grand_total`)
					FROM 
						`pj_penjualan_master` AS b 
					WHERE 
						SUBSTR(b.`tanggal`, 1, 10) = SUBSTR(a.`tanggal`, 1, 10) 
					LIMIT 1
				) AS total_penjualan 
			FROM 
				`pj_penjualan_master` AS a 
				LEFT JOIN `pj_penjualan_detail` AS b ON a.`id_penjualan_m` = b.`id_penjualan_m` 
				right JOIN `pj_display_rak_barang` AS c ON c.`produk` = b.`id_barang`
				LEFT JOIN `pj_barang` AS d ON d.`id_barang` = c.`produk`
			WHERE 
				SUBSTR(a.`tanggal`, 1, 10) >= '".$from."' 
				AND SUBSTR(a.`tanggal`, 1, 10) <= '".$to."' 
				AND c.`rak` = '".$rak."' ".$query."
			ORDER BY 
				a.`tanggal` ASC
		";

		return $this->db->query($sql);
	}
	function laporan_penjualan_gender($from, $to, $rak, $group, $warna)
	{
	//	$group = 'Perempuan';
		$query = '';
		$query2 = '';
        if($group!='semua'){
            $query = 'AND d.gender = "'.$group.'"';
        }
		  if($warna!='semua'){
            $query2 = 'AND e.warna = "'.$warna.'"';
        }      
		$sql = "
			SELECT 
				DISTINCT(SUBSTR(a.`tanggal`, 1, 10)) AS tanggal,a.`nomor_nota`,c.`produk`,c.`rak`,d.`nama_barang`,d.`gender`,e.`warna`,
				(
					SELECT 
						SUM(b.`grand_total`)
					FROM 
						`pj_penjualan_master` AS b 
					WHERE 
						SUBSTR(b.`tanggal`, 1, 10) = SUBSTR(a.`tanggal`, 1, 10) 
					LIMIT 1
				) AS total_penjualan 
			FROM 
				`pj_penjualan_master` AS a 
				LEFT JOIN `pj_penjualan_detail` AS b ON a.`id_penjualan_m` = b.`id_penjualan_m` 
				right JOIN `pj_display_rak_barang` AS c ON c.`produk` = b.`id_barang`
				LEFT JOIN `pj_barang` AS d ON d.`id_barang` = c.`produk`
				LEFT JOIN `pj_warna_barang` AS e ON e.`id_warna_barang` = d.`id_warna`
			WHERE 
				SUBSTR(a.`tanggal`, 1, 10) >= '".$from."' 
				AND SUBSTR(a.`tanggal`, 1, 10) <= '".$to."' 
				AND c.`rak` = '".$rak."' ".$query." ".$query2."
			ORDER BY 
				a.`tanggal` ASC
		";

		return $this->db->query($sql);
	}
	function laporan_penjualan_merek($from, $to, $merek)
	{
	//	$group = 'Perempuan';
		$query = '';
		if($merek!='semua'){
            $query = 'AND d.merk = "'.$merek.'"';
        }
		   
		$sql = "
			SELECT 
				DISTINCT(SUBSTR(a.`tanggal`, 1, 10)) AS tanggal,a.`nomor_nota`,c.`id_barang`,c.`nama_barang`,d.`merk`,
				(
					SELECT 
						SUM(b.`grand_total`)
					FROM 
						`pj_penjualan_master` AS b 
					WHERE 
						SUBSTR(b.`tanggal`, 1, 10) = SUBSTR(a.`tanggal`, 1, 10) 
					LIMIT 1
				) AS total_penjualan 
			FROM 
				`pj_penjualan_master` AS a 
				LEFT JOIN `pj_penjualan_detail` AS b ON a.`id_penjualan_m` = b.`id_penjualan_m` 
				LEFT JOIN `pj_barang` AS c ON c.`id_barang` = b.`id_barang`
				LEFT JOIN `pj_merk_barang` AS d ON d.`id_merk_barang` = c.`id_barang`
			WHERE 
				SUBSTR(a.`tanggal`, 1, 10) >= '".$from."' 
				AND SUBSTR(a.`tanggal`, 1, 10) <= '".$to."' 
				".$query." 
			ORDER BY 
				a.`tanggal` ASC
		";

		return $this->db->query($sql);
	}
	function cek_nota_validasi($nota)
	{
		return $this->db->select('nomor_nota')->where('nomor_nota', $nota)->limit(1)->get('pj_penjualan_master');
	}
}