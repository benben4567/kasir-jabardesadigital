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

class Laporanrak extends MY_Controller 
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
     $data['rak'] = $rak;
      $data['merek'] = $merek;
		$this->load->view('laporan/form_laporan_rak', $data);
	}

	public function penjualan($from, $to, $rak, $gender)
	{
		$this->load->model('m_penjualan_master');
		$dt['penjualan'] 	= $this->m_penjualan_master->laporan_penjualan_rak($from, $to, $rak, $gender);
		$dt['from']			= date('d F Y', strtotime($from));
		$dt['to']			= date('d F Y', strtotime($to));
		$dt['rak']			= $rak;
		$dt['gender']			= $gender;
		$this->load->view('laporan/laporan_penjualan_rak', $dt);
	}

	public function excel($from, $to, $rak, $gender)
	{
		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan_rak($from, $to, $rak, $gender);
		if($penjualan->num_rows() > 0)
		{
			$filename = 'Laporan_Penjualan_'.$from.'_'.$to;
			header("Content-type: application/x-msdownload");
			header("Content-Disposition: attachment; filename=".$filename.".xls");

			echo "
				<h4>Laporan Penjualan by Rak Tanggal ".date('d/m/Y', strtotime($from))." - ".date('d/m/Y', strtotime($to))."</h4>
				<table border='1' width='100%'>
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>No.Rak</th>
							<th>Nama Barang</th>
							<th>Gender</th>
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
						<td>".$p->rak."</td>
						<td>".$p->nama_barang."</td>
						<td>".$p->gender."</td>
						<td>".$p->nomor_nota."</td>
						<td>Rp. ".str_replace(",", ".", number_format($p->total_penjualan))."</td>
					</tr>
				";

				$total_penjualan = $total_penjualan + $p->total_penjualan;
				$no++;
			}

			echo "
				<tr>
					<td colspan='2'><b>Total Seluruh Penjualan</b></td>
					<td><b>Rp. ".str_replace(",", ".", number_format($total_penjualan))."</b></td>
				</tr>
			</tbody>
			</table>
			";
		}
	}

	public function pdf($from, $to, $rak, $gender)
	{
		$this->load->library('cfpdf');
					
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);

		$pdf->SetFont('Arial','',10);
		$pdf->Cell(0, 8, "Laporan Penjualan by Rak Tanggal ".date('d/m/Y', strtotime($from))." - ".date('d/m/Y', strtotime($to)), 0, 1, 'L'); 

		$pdf->Cell(10, 7, 'No', 1, 0, 'L'); 
		$pdf->Cell(35, 7, 'Tanggal', 1, 0, 'L');
		$pdf->Cell(20, 7, 'No.Rak', 1, 0, 'L'); 
		$pdf->Cell(40, 7, 'Nama Barang', 1, 0, 'L'); 
		$pdf->Cell(20, 7, 'Gender', 1, 0, 'L'); 
		$pdf->Cell(35, 7, 'Nota', 1, 0, 'L'); 
		$pdf->Cell(30, 7, 'Total Penjualan', 1, 0, 'L'); 
		$pdf->Ln();

		$this->load->model('m_penjualan_master');
		$penjualan 	= $this->m_penjualan_master->laporan_penjualan_rak($from, $to, $rak, $gender);

		$no = 1;
		$total_penjualan = 0;
		foreach($penjualan->result() as $p)
		{
			$pdf->Cell(10, 7, $no, 1, 0, 'L'); 
			$pdf->Cell(35, 7, date('d F Y', strtotime($p->tanggal)), 1, 0, 'L');
			$pdf->Cell(20, 7, $p->rak, 1, 0, 'L');
			$pdf->Cell(40, 7, $p->nama_barang, 1, 0, 'L');
			$pdf->Cell(20, 7, $p->gender, 1, 0, 'L');
			$pdf->Cell(35, 7, $p->nomor_nota, 1, 0, 'L');
			$pdf->Cell(30, 7, "Rp. ".str_replace(",", ".", number_format($p->total_penjualan)), 1, 0, 'L');
			$pdf->Ln();

			$total_penjualan = $total_penjualan + $p->total_penjualan;
			$no++;
		}

		$pdf->Cell(105, 7, 'Total Seluruh Penjualan', 1, 0, 'L'); 
		$pdf->Cell(85, 7, "Rp. ".str_replace(",", ".", number_format($total_penjualan)), 1, 0, 'R');
		$pdf->Ln();

		$pdf->Output();
	}
}