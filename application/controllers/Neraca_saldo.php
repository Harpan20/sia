<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neraca_Saldo extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(['url','form','sia','tgl_indo']);
        $this->load->library(['session','form_validation']);
        $this->load->model('Akun_model','akun',true);
        $this->load->model('Jurnal_model','jurnal',true);
        $this->load->model('User_model','user',true);
        $login = $this->session->userdata('login');
        if(!$login){
            redirect('login');
        }
    }

   

    public function neracaSaldo(){
        $titleTag = 'Neraca Saldo';
        $content = 'user/neraca_saldo_main';
        $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
        $tahun = $this->jurnal->getJurnalByYear();
        $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
    }

    public function neracaSaldoDetail(){
        $content = 'user/neraca_saldo';
        $titleTag = 'Neraca Saldo';

        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);
        if(empty($bulan) || empty($tahun)){
            redirect('neraca_saldo');
        }

        $dataAkun = $this->akun->getAkunByMonthYear($bulan,$tahun);
      
        $data = null;
        $saldo = null;
        
        foreach($dataAkun as $row){
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff,$bulan,$tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff,$bulan,$tahun);
        }
      //  echo json_encode($saldo);exit;
        if($data == null || $saldo == null){
            $this->session->set_flashdata('dataNull','Neraca Saldo Dengan Bulan '.bulan($bulan).' Pada Tahun '.date('Y',strtotime($tahun)).' Tidak Di Temukan');
            redirect('neraca_saldo');
        }

        $jumlah = count($data);
        //$jumlah = 0;

        $this->load->view('template',compact('content','titleTag','dataAkun','data','jumlah','saldo'));
    }

    public function laporan(){
        $titleTag = 'Laporan';
        $content = 'user/laporan_main';
        $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
        $tahun = $this->jurnal->getJurnalByYear();
        $this->load->view('template',compact('content','listJurnal','titleTag','tahun'));
    }

    public function laporanCetak(){
        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);
        $titleTag = 'Laporan '.bulan($bulan).' '.$tahun;

        $dataAkun = $this->akun->getAkunByMonthYear($bulan,$tahun);
        $jurnals = $this->jurnal->getJurnalJoinAkunDetail($bulan,$tahun);
        $totalDebit = $this->jurnal->getTotalSaldoDetail('D',$bulan,$tahun);
      
        $totalKredit = $this->jurnal->getTotalSaldoDetail('K',$bulan,$tahun);

        $data = null;
        $saldo = null;
        foreach($dataAkun as $row){
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff,$bulan,$tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff,$bulan,$tahun);
        }

        if($data == null || $saldo == null){
            $this->session->set_flashdata('dataNull','Laporan Dengan Bulan '.bulan($bulan).' Pada Tahun '.date('Y',strtotime($tahun)).' Tidak Di Temukan');
            redirect('laporan');
        }

        $jumlah = count($data);

        $data = $this->load->view('user/laporan',compact('titleTag','dataAkun','bulan','tahun','jurnals','totalDebit','totalKredit','data','saldo','jumlah'),true);
        // echo $data;
        // die();
        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->filename = "laporan_".bulan($bulan).'_'.$tahun;
        $this->pdf->load_view('user/laporan', $data);
    }

    public function logout(){
        $this->user->logout();
        redirect('');
    }
}
