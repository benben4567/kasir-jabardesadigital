<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------
 * CLASS NAME : Laporan
 * ------------------------------------------------------------------------
 *
 * @author     Muhammad Akbar <muslim.politekniktelkom@gmail.com>
 * @copyright  2016
 * @license    http://aplikasiphp.net
 *
 */

class Laporan extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$level 		= $this->session->userdata('ap_level');
		$allowed	= array('admin', 'keuangan');

		if( ! in_array($level, $allowed))
		{
			redirect();
		}
	}

	public function index()
	{
		 $kategoris = $this->User_model->getKategori();
         $merek = $this->User_model->getMerek();
          $warna = $this->User_model->getWarna();
     $data['kategoris'] = $kategoris;
      $data['merek'] = $merek;
            $data['warna'] = $warna;
		$this->load->view('laporan/form_laporan',$data);
	}

	public function penjualan($from, $to, $merk, $kategori, $warna)
	{
		$this->load->model('m_penjualan_master');
		$dt['penjualan'] 	= $this->m_penjualan_master->laporan_penjualan($from, $to, $merk, $kategori, $warna);
		$dt['from']			= date('d F Y', strtotime($from));
		$dt['to']			= date('d F Y', strtotime($to));
		$dt['merek']			= $merk;
	    $dt['kategori']			= $kategori;
		$dt['warna']			= $warna;
		$this->load->view('laporan/laporan_penjualan', $dt);
	}

	public function excel($from, $to)
	{
		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan($from, $to);
		if($penjualan->num_rows() > 0)
		{
			$filename = 'Laporan_Penjualan_'.$from.'_'.$to;
			header("Content-type: application/x-msdownload");
			header("Content-Disposition: attachment; filename=".$filename.".xls");

			echo "
				<h4>Laporan Penjualan Tanggal ".date('d/m/Y', strtotime($from))." - ".date('d/m/Y', strtotime($to))."</h4>
				<table border='1' width='100%'>
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Nama Barang</th>
							<th>Kategori</th>
							<th>Merek</th>
							<th>Warna</th>
							<th>Qty</th>
							<th>Total Penjualan</th>
						</tr>
					</thead>
					<tbody>
			";

			$no = 1;
			$total_penjualan = 0;
			foreach($penjualan->result() as $p)
			{
				echo "
					<tr>
						<td>".$no."</td>
						<td>".date('d F Y', strtotime($p->tanggal))."</td>
						<td>".$p->nama_barang."</td>
						<td>".$p->kategori."</td>
						<td>".$p->merk."</td>
						<td>".$p->warna."</td>
						<td>".$p->jumlah_beli."</td>
						<td>Rp. ".str_replace(",", ".", number_format($p->total_penjualan))."</td>
					</tr>
				";

				$total_penjualan = $total_penjualan + $p->total_penjualan;
				$no++;
			}

			echo "
				<tr>
					<td colspan='7'><b>Total Seluruh Penjualan</b></td>
					<td><b>Rp. ".str_replace(",", ".", number_format($total_penjualan))."</b></td>
				</tr>
			</tbody>
			</table>
			";
		}
	}

	public function pdf($from, $to)
	{
		$this->load->library('cfpdf');
					
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);

		$pdf->SetFont('Arial','',9);
		$pdf->Cell(0, 8, "Laporan Penjualan Tanggal ".date('d/m/Y', strtotime($from))." - ".date('d/m/Y', strtotime($to)), 0, 1, 'L'); 

		$pdf->Cell(15, 7, 'No', 1, 0, 'L'); 
		$pdf->Cell(25, 7, 'Tanggal', 1, 0, 'L');
		$pdf->Cell(35, 7, 'Nama Barang', 1, 0, 'L');
		$pdf->Cell(20, 7, 'Kategori', 1, 0, 'L');
		$pdf->Cell(15, 7, 'Merek', 1, 0, 'L');
		$pdf->Cell(15, 7, 'Warna', 1, 0, 'L');
		$pdf->Cell(15, 7, 'Qty', 1, 0, 'L');
		$pdf->Cell(25, 7, 'Total Penjualan', 1, 0, 'L'); 
		$pdf->Ln();

		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan($from, $to);

		$no = 1;
		$total_penjualan = 0;
		foreach($penjualan->result() as $p)
		{
			$pdf->Cell(15, 7, $no, 1, 0, 'L'); 
			$pdf->Cell(25, 7, date('d F Y', strtotime($p->tanggal)), 1, 0, 'L');
			$pdf->Cell(35, 7, $p->nama_barang, 1, 0, 'L'); 
			$pdf->Cell(20, 7, $p->kategori, 1, 0, 'L'); 
			$pdf->Cell(15, 7, $p->merk, 1, 0, 'L'); 
			$pdf->Cell(15, 7, $p->warna, 1, 0, 'L'); 
			$pdf->Cell(15, 7,  $p->jumlah_beli, 1, 0, 'L'); 
			$pdf->Cell(25, 7, "Rp. ".str_replace(",", ".", number_format($p->total_penjualan)), 1, 0, 'L');
			$pdf->Ln();

			$total_penjualan = $total_penjualan + $p->total_penjualan;
			$no++;
		}

		$pdf->Cell(95, 7, 'Total Seluruh Penjualan', 1, 0, 'L'); 
		$pdf->Cell(70, 7, "Rp. ".str_replace(",", ".", number_format($total_penjualan)), 1, 0, 'R');
		$pdf->Ln();

		$pdf->Output();
	}
}