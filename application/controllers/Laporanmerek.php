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

class Laporanmerek extends MY_Controller 
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
		 $rak = $this->User_model->getRak();
         $merek = $this->User_model->getMerek();
          $warna = $this->User_model->getWarna();
           $data['warna'] = $warna;
     $data['rak'] = $rak;
      $data['merek'] = $merek;
		$this->load->view('laporan/form_laporan_merek', $data);
	}

	public function penjualan($from, $to, $merek)
	{
		$this->load->model('m_penjualan_master');
		$dt['penjualan'] 	= $this->m_penjualan_master->laporan_penjualan_merek($from, $to, $merek);
		$dt['from']			= date('d F Y', strtotime($from));
		$dt['to']			= date('d F Y', strtotime($to));
	    $dt['merek']			= $merek;
		$this->load->view('laporan/laporan_penjualan_merek', $dt);
	}

	public function excel($from, $to, $merek)
	{
		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan_merek($from, $to, $merek);
		if($penjualan->num_rows() > 0)
		{
			$filename = 'Laporan_Penjualan_By_Merek'.$from.'_'.$to;
			header("Content-type: application/x-msdownload");
			header("Content-Disposition: attachment; filename=".$filename.".xls");

			echo "
				<h4>Laporan Penjualan by Merek Tanggal ".date('d/m/Y', strtotime($from))." - ".date('d/m/Y', strtotime($to))."</h4>
				<table border='1' width='100%'>
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Nama Barang</th>
							<th>Merek</th>
							<th>Nota</th>
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
						<td>".$p->merek."</td>
						<td>".$p->nomor_nota."</td>
						<td>".$p->warna."</td>
					
						<td>Rp. ".str_replace(",", ".", number_format($p->total_penjualan))."</td>
					</tr>
				";

				$total_penjualan = $total_penjualan + $p->total_penjualan;
				$no++;
			}

			echo "
				<tr>
					<td colspan='6'><b>Total Seluruh Penjualan</b></td>
					<td><b>Rp. ".str_replace(",", ".", number_format($total_penjualan))."</b></td>
				</tr>
			</tbody>
			</table>
			";
		}
	}

	public function pdf($from, $to, $merek)
	{
		$this->load->library('cfpdf');
					
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',9);

		$pdf->SetFont('Arial','',9);
		$pdf->Cell(0, 8, "Laporan Penjualan by Merek Tanggal ".date('d/m/Y', strtotime($from))." - ".date('d/m/Y', strtotime($to)), 0, 1, 'L'); 

		$pdf->Cell(10, 7, 'No', 1, 0, 'L'); 
		$pdf->Cell(30, 7, 'Tanggal', 1, 0, 'L');
		$pdf->Cell(45, 7, 'Nama Barang', 1, 0, 'L'); 
		$pdf->Cell(20, 7, 'Merek', 1, 0, 'L'); 
		$pdf->Cell(35, 7, 'Nota', 1, 0, 'L'); 
		$pdf->Cell(30, 7, 'Total Penjualan', 1, 0, 'L'); 
		$pdf->Ln();

		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan_merek($from, $to, $merek);

		$no = 1;
		$total_penjualan = 0;
		foreach($penjualan->result() as $p)
		{
			$pdf->Cell(10, 7, $no, 1, 0, 'L'); 
			$pdf->Cell(30, 7, date('d F Y', strtotime($p->tanggal)), 1, 0, 'L');
			$pdf->Cell(45, 7, $p->nama_barang, 1, 0, 'L');
			$pdf->Cell(20, 7, $p->merk, 1, 0, 'L');
			$pdf->Cell(35, 7, $p->nomor_nota, 1, 0, 'L');
			$pdf->Cell(30, 7, "Rp. ".str_replace(",", ".", number_format($p->total_penjualan)), 1, 0, 'L');
			$pdf->Ln();

			$total_penjualan = $total_penjualan + $p->total_penjualan;
			$no++;
		}

		$pdf->Cell(105, 7, 'Total Seluruh Penjualan By Merek', 1, 0, 'L'); 
		$pdf->Cell(65, 7, "Rp. ".str_replace(",", ".", number_format($total_penjualan)), 1, 0, 'R');
		$pdf->Ln();

		$pdf->Output();
	}
}