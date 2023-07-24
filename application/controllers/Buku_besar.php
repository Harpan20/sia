<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buku_besar extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(['url','form','sia','tgl_indo']);
        $this->load->library(['session']);
        $this->load->model('Akun_model','akun',true);
        $this->load->model('Jurnal_model','jurnal',true);
        $login = $this->session->userdata('login');
        if(!$login){
            redirect('login');
        }
    }

    public function bukuBesar(){
        
        $titleTag = 'Buku Besar';
        $content = 'user/buku_besar_main';
        $param=[];
        $listJurnal="";
        if($this->input->post()){
            $param=[
                'tgl_filter'    =>"",
                'no_reff'       =>$this->input->post('no_reff',true),
            ];
            if(!$param['no_reff']){
                $param['no_reff']='';
            }    
            //echo json_encode($this->input->post());exit;
            $listJurnal=$this->jurnal->getJurnal($param);
            $this->load->view('template',compact('content','listJurnal','titleTag','param'));
        }else{
            $this->load->view('template',compact('content','titleTag'));
        }
        
    }
      
       

    public function bukuBesarDetail(){
        $content = 'user/buku_besar';
        $titleTag = 'Buku Besar';
        
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);

        if(empty($bulan) ||empty($tahun)){
            redirect('buku_besar');
        }
        
        $dataAkun = $this->akun->getAkunByMonthYear($bulan,$tahun);
        $data = null;
        $saldo = null;

        foreach($dataAkun as $row){
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff,$bulan,$tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff,$bulan,$tahun);
        }

        if($data == null || $saldo == null){
            $this->session->set_flashdata('dataNull','Data Buku Besar Dengan Bulan '.bulan($bulan).' Pada Tahun '.date('Y',strtotime($tahun)).' Tidak Di Temukan');
            redirect('buku_besar');
        }

        $jumlah = count($data);

        $this->load->view('template',compact('content','titleTag','dataAkun','data','jumlah','saldo'));
    }

}
