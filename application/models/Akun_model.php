<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun_model extends CI_Model{
    private $table = 'akun';

    public function getAkun(){
        return $this->db->get($this->table)->result();
    }

    public function getAkunByMonthYear($bulan,$tahun){
        return $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi_master.tgl_transaksi')
                        ->from($this->table)
                        ->where('month(transaksi_master.tgl_transaksi)',$bulan)
                        ->where('year(transaksi_master.tgl_transaksi)',$tahun)
                        ->join('transaksi_detail','transaksi_detail.no_reff = akun.no_reff')
                        ->join('transaksi_master','transaksi_detail.trans_id = transaksi_master.trans_id')
                        ->group_by('akun.nama_reff')
                        ->order_by('akun.no_reff')
                        ->get()
                        ->result();
    }

    public function countAkunByNama($str){
        return $this->db->where('nama_reff',$str)->get($this->table)->num_rows();
    }

    public function countAkunByNoReff($str){
        return $this->db->where('no_reff',$str)->get($this->table)->num_rows();
    }

    public function getAkunByNo($noReff){
        return $this->db->where('no_reff',"$noReff")->get($this->table)->row();
    }

    public function insertAkun($data){
        return $this->db->insert($this->table,$data);
    }

    public function updateAkun($noReff,$data){
        return $this->db->where('no_reff',$noReff)->update($this->table,$data);
    }

    public function deleteAkun($noReff){
        return $this->db->where('no_reff',$noReff)->delete($this->table);
    }

    public function getDefaultValues(){
        return [
            'no_reff'=>'',
            'nama_reff'=>'',
            'keterangan'=>'',
            'd_or_k'=>''
        ];
    }

    
}