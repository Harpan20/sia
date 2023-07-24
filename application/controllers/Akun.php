<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(['url','form','sia','tgl_indo']);
        $this->load->library(['session','form_validation']);
        $this->load->model('Akun_model','akun',true);
        $this->load->model('Jurnal_model','jurnal',true);
        $login = $this->session->userdata('login');
        if(!$login){
            redirect('login');
        }
    }


    public function dataAkun(){
        $content = 'akun/data_akun';
        $titleTag = 'Data Akun';
        $dataAkun = $this->akun->getAkun();
        $this->load->view('template',compact('content','dataAkun','titleTag'));
    }

    public function isNamaAkunThere($str){
        $namaAkun = $this->akun->countAkunByNama($str);
        if($namaAkun >= 1){
            $this->form_validation->set_message('isNamaAkunThere', 'Nama Akun Sudah Ada');
            return false;
        }
        return true;
    }

    public function isNoAkunThere($str){
        $noAkun = $this->akun->countAkunByNoReff($str);
        if($noAkun >= 1){
            $this->form_validation->set_message('isNoAkunThere', 'No.Reff Sudah Ada');
            return false;
        }
        return true;
    }

    public function createAkun(){
        $title = 'Tambah';
        $titleTag = 'Tambah Data Akun';
        $action = 'data_akun/tambah';
        $content = 'akun/form_akun';

        if(!$_POST){
            $data = (object) $this->akun->getDefaultValues();
        }else{
            $data = (object) $this->input->post(null,true);
            $data->id_user = $this->session->userdata('id');
        }
        if(!$this->validate(false)){
            $this->load->view('template',compact('content','title','action','data','titleTag'));
            return;
        }
        unset($data->no_reff_old);
        $this->akun->insertAkun($data);
        $this->session->set_flashdata('berhasil','Data Akun Berhasil Di Tambahkan');
        redirect('data_akun');
    }

    public function editAkun($no_reff = null){
        $title = 'Edit';
        $titleTag = 'Edit Data Akun';
        $action = 'data_akun/edit/'.$no_reff;
        $content = 'akun/form_akun';
        $validate="";

        if(!$_POST){
            $data = (object) $this->akun->getAkunByNo($no_reff);
        }else{
            $data = (object) $this->input->post(null,true);
            $data->id_user = $this->session->userdata('id');
            if($data->no_reff_old==$data->no_reff){
                $validate=$this->validate(true);
            } else{
                $validate=$this->validate(false);
            }
        }
        
        if(!$validate){
            $this->load->view('template',compact('content','title','action','data','titleTag'));
            return;
        }
        unset($data->no_reff_old);
        $this->akun->updateAkun($no_reff,$data);
        $this->session->set_flashdata('berhasil','Data Akun Berhasil Di Ubah');
        redirect('data_akun');
    }

    public function deleteAkun(){
        $id = $this->input->post('id',true);
        $noReffTransaksi = $this->jurnal->countJurnalNoReff($id);
        if($noReffTransaksi > 0 ){
            $this->session->set_flashdata('dataNull','No.Reff '.$id.' Tidak Bisa Di Hapus Karena Data Akun Ada Di Jurnal Umum');
            redirect('data_akun');
        }
        $this->akun->deleteAkun($id);
        $this->session->set_flashdata('berhasilHapus','Data akun dengan No.Reff '.$id.' berhasil di hapus');
        redirect('data_akun');
    }

    public function getValidationRules($dataOld){
        $roles=[];
        if($dataOld==1){
            
            $roles= [
                [
                    'field'=>'no_reff',
                    'label'=>'No.Reff',
                    'rules'=>'trim'
                ],
                [
                    'field'=>'nama_reff',
                    'label'=>'Nama Reff',
                    'rules'=>'trim|required'
                ],
                [
                    'field'=>'d_or_k',
                    'label'=>'Saldo Normal',
                    'rules'=>'trim|required'
                ],
                [
                    'field'=>'keterangan',
                    'label'=>'Keterangan',
                    'rules'=>'trim'
                ],
            ];
        }
       else  if($dataOld==0){
                $roles= [
                    [
                        'field'=>'no_reff',
                        'label'=>'No.Reff',
                        'rules'=>'trim|required|callback_isNoAkunThere'
                    ],
                    [
                        'field'=>'nama_reff',
                        'label'=>'Nama Reff',
                        'rules'=>'trim|required'
                    ],
                    [
                        'field'=>'d_or_k',
                        'label'=>'Saldo Normal',
                        'rules'=>'trim|required'
                    ],
                    [
                        'field'=>'keterangan',
                        'label'=>'Keterangan',
                        'rules'=>'trim'
                    ],
                ];
            }

        return $roles;
        
    }

    public function validate($dataOld=true){
      
        $rules = $this->getValidationRules($dataOld);
        
        $this->form_validation->set_rules($rules);
        
        $this->form_validation->set_error_delimiters('<span class="text-danger" style="font-size:14px">','</span>');
        
        return $this->form_validation->run();
    }

    
}
