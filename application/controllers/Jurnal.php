<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal extends CI_Controller{
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

    
    public function jurnalUmum(){
        $titleTag = 'Jurnal Umum';
        $content = 'user/jurnal_umum_main';
    
        $param=[
            'tgl_filter'    =>$this->input->post('tgl_filter',true),
            'no_reff'       =>""
        ];
        if(!$param['tgl_filter']){
            $param['tgl_filter']='';
        }    
        $listJurnal=$this->jurnal->getJurnal($param);
        $this->load->view('template',compact('content','listJurnal','titleTag'));
    }

    public function jurnalUmumDetail(){
        $content = 'user/jurnal_umum';
        $titleTag = 'Jurnal Umum';

        $bulan = $this->input->post('bulan',true);
        $tahun = $this->input->post('tahun',true);
        $jurnals = null;

        if(empty($bulan) || empty($tahun)){
            redirect('jurnal_umum');
        }

        $jurnals = $this->jurnal->getJurnalJoinAkunDetail($bulan,$tahun);
        $totalDebit = $this->jurnal->getTotalSaldoDetail('debit',$bulan,$tahun);
        $totalKredit = $this->jurnal->getTotalSaldoDetail('kredit',$bulan,$tahun);

        if($jurnals==null){
            $this->session->set_flashdata('dataNull','Data Jurnal Dengan Bulan '.bulan($bulan).' Pada Tahun '.date('Y',strtotime($tahun)).' Tidak Di Temukan');
            redirect('jurnal_umum');
        }

        $this->load->view('template',compact('content','jurnals','totalDebit','totalKredit','titleTag'));
    }

    public function createJurnal(){
        $title = 'Simpan'; 
        $content = 'user/form_jurnal'; 
        $action = 'jurnal_umum/tambah'; 
        $tgl_input = date('Y-m-d H:i:s'); 
        $id_user = $this->session->userdata('id'); 
        $titleTag = 'Tambah Jurnal Umum';

        if(!$_POST){
            $data = (object) $this->getDefaultValues();
        }else{
            //echo json_encode($this->input->post());exit;
            $next_id=$this->jurnal->getNextlMasterID();
            $data = (object) [
                'trans_id'  =>$next_id+1,
                'id_user'=>$id_user,
                'no_bukti'=>$this->input->post('no_bukti',true),
                'tgl_transaksi'=>$this->input->post('tgl_transaksi',true),
                'akun_debet'=>$this->input->post('akun_debet',true),
                'akun_kredit'=>$this->input->post('akun_kredit',true),
                'saldo'=>$this->input->post('saldo',true),
                'no_bukti'=>$this->input->post('no_bukti',true),
                'keterangan'=>$this->input->post('keterangan',true),
                'arus_kas'=>$this->input->post('arus_kas',true)
            ];

          
        }
          
        if(!$this->validate()){
            $this->load->view('template',compact('content','title','action','data','titleTag'));
            return;
        }
        $params=$data;
        $this->insertJurnalMaster($data);
         $this->insertJurnalDetail($params);
        $this->session->set_flashdata('berhasil','Data Jurnal Berhasil Di Tambahkan');
        redirect('jurnal_umum');    
    }
    private function insertJurnalMaster($data){
        $param=[
            'trans_id' => $data->trans_id,
            'id_user' => $data->trans_id,
            'no_bukti' => $data->no_bukti,
            'tgl_transaksi' => $data->tgl_transaksi,
            'keterangan' => $data->keterangan,
            'arus_kas' => $data->arus_kas
        ];
                                                                                                                                                                        
        $this->jurnal->insertJurnalMaster($param);
    }
    private function insertJurnalDetail($data){
        
        $param=[
            'trans_id' => $data->trans_id,
            'no_reff' => $data->akun,
            'nominal' => $data->saldo,
        ];

        $param['d_or_k']='d';
        $param['no_reff']=$data->akun_debet;
        $this->jurnal->insertJurnalDetail($param);
        $param['d_or_k']='k';
        $param['no_reff']=$data->akun_kredit;
        $this->jurnal->insertJurnalDetail($param);
    }
    private function getNextlMasterID($params){
        $this->jurnal->getNextlMasterID($params);
    }
    

    public function getDefaultValues(){
        return [
            'tgl_transaksi'=>date('Y-m-d'),
            'no_reff'=>'',
            'jenis_saldo'=>'',
            'saldo'=>'',
            'no_bukti'=>'',
            'keterangan'=>'',
            'akun_debet'=>'',
            'akun_kredit'=>'',
            'arus_kas'  =>''
        ];
    }
    public function editForm(){
        if($_POST){
            $id = $this->input->post('id',true);
            $title = 'Edit'; $content = 'user/form_jurnal'; $action = 'jurnal_umum/edit'; $titleTag = 'Edit Jurnal Umum';

            $data = (object) $this->jurnal->getJurnalById($id);

            $this->load->view('template',compact('content','title','action','data','id','titleTag'));
        }else{
            redirect('jurnal_umum');
        }
    }

    public function editJurnal(){
        $title = 'Edit'; $content = 'user/form_jurnal'; $action = 'jurnal_umum/edit'; $tgl_input = date('Y-m-d H:i:s'); $id_user = $this->session->userdata('id'); $titleTag = 'Edit Jurnal Umum';

        if($_POST){
            $data = (object) [
                'id_user'=>$id_user,
                'no_reff'=>$this->input->post('no_reff',true),
                'tgl_input'=>$tgl_input,
                'tgl_transaksi'=>$this->input->post('tgl_transaksi',true),
                'jenis_saldo'=>$this->input->post('jenis_saldo',true),
                'saldo'=>$this->input->post('saldo',true)
            ];
            $id = $this->input->post('id',true);
        }

        if(!$this->jurnal->validate()){
            $this->load->view('template',compact('content','title','action','data','id','titleTag'));
            return;
        }
        
        $this->jurnal->updateJurnal($id,$data);
        $this->session->set_flashdata('berhasil','Data Jurnal Berhasil Di Ubah');
        redirect('jurnal_umum');    
    }

    public function deleteJurnal(){
        $id = $this->input->post('id',true);
        $this->jurnal->deleteJurnal($id);
        $this->session->set_flashdata('berhasilHapus','Data Jurnal berhasil di hapus');
        redirect('jurnal_umum');
    }

    
    public function getValidationRules(){
        return [
            [
                'field'=>'tgl_transaksi',
                'label'=>'Tanggal Transaksi',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'no_bukti',
                'label'=>'No Bukti',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'akun_debet',
                'label'=>'Akun Debet',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'akun_kredit',
                'label'=>'Akun Kredit',
                'rules'=>'trim|required'
            ],
            [
                'field'=>'saldo',
                'label'=>'Saldo',
                'rules'=>'trim|required|numeric'
            ],
            [
                'field'=>'keterangan',
                'label'=>'Keterangan',
                'rules'=>'required'
            ],
            [
                'field'=>'arus_kas',
                'label'=>'Arus Kas',
                'rules'=>'trim|required'
            ],
        ];
    }

    public function validate(){
        $rules = $this->getValidationRules();
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_error_delimiters('<span class="text-danger" style="font-size:14px">','</span>');
        return $this->form_validation->run();
    }
}
