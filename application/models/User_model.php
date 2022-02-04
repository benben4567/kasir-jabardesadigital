<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

   // Get DataTable data
   function getUsers($postData=null){

     $response = array();

     ## Read value
     $draw = $postData['draw'];
     $start = $postData['start'];
     $rowperpage = $postData['length']; // Rows display per page
     $columnIndex = $postData['order'][0]['column']; // Column index
     $columnName = $postData['columns'][$columnIndex]['data']; // Column name
     $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
     $searchValue = $postData['search']['value']; // Search value

     // Custom search filter 
     $searchCity = $postData['searchCity'];
     $searchMerk = $postData['searchMerk'];
     $searchGender = $postData['searchGender'];
     $searchName = $postData['searchName'];
    $searchWarna = $postData['searchWarna'];
    $searchRak = $postData['searchRak'];
    $searchSize = $postData['searchSize'];

     ## Search 
     $search_arr = array();
     $searchQuery = "";
     if($searchValue != ''){
        $search_arr[] = " (nama_barang like '%".$searchValue."%' or 
         kategori like '%".$searchValue."%' or 
         warna like '%".$searchValue."%'or or 
         rak like '%".$searchValue."%' merk like '%".$searchValue."%' or
         gender like'%".$searchValue."%' ) ";
     }
     if($searchCity != ''){
        $search_arr[] = " kategori='".$searchCity."' ";
     }
       if($searchMerk != ''){
        $search_arr[] = " merk='".$searchMerk."' ";
     }
     if($searchGender != ''){
        $search_arr[] = " gender='".$searchGender."' ";
     }
     if($searchName != ''){
        $search_arr[] = " nama_barang like '%".$searchName."%' ";
     }
       if($searchWarna != ''){
        $search_arr[] = " warna like '%".$searchWarna."%' ";
     }
       if($searchRak != ''){
        $search_arr[] = " rak like '%".$searchRak."%' ";
     }
     if(count($search_arr) > 0){
        $searchQuery = implode(" and ",$search_arr);
     }

     ## Total number of records without filtering
     $this->db->select('count(*) as allcount');
        $this->db->from('pj_barang');
     $this->db->join('pj_kategori_barang', 'pj_kategori_barang.id_kategori_barang = pj_barang.id_kategori_barang');
      $this->db->join('pj_merk_barang', 'pj_merk_barang.id_merk_barang = pj_barang.id_merk_barang');
       $this->db->join('pj_warna_barang', 'pj_warna_barang.id_warna_barang = pj_barang.id_warna');
         $this->db->join('pj_rak_barang', 'pj_rak_barang.id_rak_barang = pj_barang.id_rak');
$records = $this->db->get()->result();
     $totalRecords = $records[0]->allcount;

     ## Total number of record with filtering
     $this->db->select('count(*) as allcount');
        $this->db->from('pj_barang');
     $this->db->join('pj_kategori_barang', 'pj_kategori_barang.id_kategori_barang = pj_barang.id_kategori_barang');
      $this->db->join('pj_merk_barang', 'pj_merk_barang.id_merk_barang = pj_barang.id_merk_barang');
        $this->db->join('pj_warna_barang', 'pj_warna_barang.id_warna_barang = pj_barang.id_warna');
           $this->db->join('pj_rak_barang', 'pj_rak_barang.id_rak_barang = pj_barang.id_rak');
     if($searchQuery != '')
     $this->db->where($searchQuery);
     $records = $this->db->get()->result();
     $totalRecordwithFilter = $records[0]->allcount;

     ## Fetch records
     $this->db->select('*');
      $this->db->from('pj_barang');
    $this->db->join('pj_kategori_barang', 'pj_kategori_barang.id_kategori_barang = pj_barang.id_kategori_barang');
     $this->db->join('pj_merk_barang', 'pj_merk_barang.id_merk_barang = pj_barang.id_merk_barang');
       $this->db->join('pj_warna_barang', 'pj_warna_barang.id_warna_barang = pj_barang.id_warna');
         $this->db->join('pj_rak_barang', 'pj_rak_barang.id_rak_barang = pj_barang.id_rak');
     if($searchQuery != '')
     $this->db->where($searchQuery);
     $this->db->order_by($columnName, $columnSortOrder);
     $this->db->limit($rowperpage, $start);
     $records = $this->db->get()->result();

     $data = array();

     foreach($records as $record ){
       $hasil_rupiah = "Rp. " . number_format($record->harga,0,',','.');
       $data[] = array( 
         "kode_barang"=>$record->kode_barang,
         "nama_barang"=>$record->nama_barang,
          "kategori"=>$record->kategori,
          "size"=>$record->size,
          "merk"=>$record->merk,
         "harga"=>$hasil_rupiah,
         "warna"=>$record->warna,
         "rak"=>$record->rak,
         "gender"=>$record->gender
       ); 
     }

     ## Response
     $response = array(
       "draw" => intval($draw),
       "iTotalRecords" => $totalRecords,
       "iTotalDisplayRecords" => $totalRecordwithFilter,
       "aaData" => $data
     );

     return $response; 
   }

   // Get cities array
   public function getCities(){

     ## Fetch records
     $this->db->distinct();
     $this->db->select('kategori');
     $this->db->order_by('kategori','asc');
     $records = $this->db->get('pj_kategori_barang')->result();

     $data = array();

     foreach($records as $record ){
        $data[] = $record->kategori;
     }

     return $data;
   }
    public function getRak(){

     ## Fetch records
     $this->db->distinct();
     $this->db->select('rak');
     $this->db->order_by('rak','asc');
     $records = $this->db->get('pj_rak_barang')->result();

     $data = array();

     foreach($records as $record ){
        $data[] = $record->rak;
     }

     return $data;
   }
 public function getWarna(){

     ## Fetch records
     $this->db->distinct();
     $this->db->select('warna');
     $this->db->order_by('warna','asc');
     $records = $this->db->get('pj_warna_barang')->result();

     $data = array();

     foreach($records as $record ){
        $data[] = $record->warna;
     }

     return $data;
   }
public function getMerek(){

     ## Fetch records
     $this->db->distinct();
     $this->db->select('merk');
     $this->db->order_by('merk','asc');
     $records = $this->db->get('pj_merk_barang')->result();

     $data = array();

     foreach($records as $record ){
        $data[] = $record->merk;
     }

     return $data;
   }
   public function getKategori(){

     ## Fetch records
     $this->db->distinct();
     $this->db->select('kategori');
     $this->db->order_by('kategori','asc');
     $records = $this->db->get('pj_kategori_barang')->result();

     $data = array();

     foreach($records as $record ){
        $data[] = $record->kategori;
     }

     return $data;
   }
}