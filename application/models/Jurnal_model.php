<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_model extends CI_Model{
    private $table = 'transaksi_master';
    private $table_kas = 'reff_arus_kas';
    private $table_akun = 'akun';
    private $table_detail = 'transaksi_detail';

    public function getJurnal($params){
      $tgl_filter=$params['tgl_filter'];
    $no_reff  =$params['no_reff'];
      $this->db->select("tgl_transaksi,nama_reff,$this->table_detail.d_or_k,no_bukti,$this->table_detail.no_reff,nominal,$this->table.keterangan,$this->table.arus_kas,deskripsi as deskripsi_arus_kas,$this->table_akun.d_or_k as d_or_k_bertambah,")
            ->from($this->table_detail)
            ->join($this->table, "$this->table.trans_id=$this->table_detail.trans_id")
            ->join($this->table_akun, "$this->table_detail.no_reff=$this->table_akun.no_reff")
            ->join($this->table_kas, "$this->table_kas.kode=$this->table.arus_kas");
            if($no_reff!=""){
                $this->db->where("$this->table_detail.no_reff",$no_reff);
            }
            if($tgl_filter!=""){
                $this->db->where("$this->table.tgl_transaksi",$tgl_filter);
            }
           return $this->db->get()->result();
    }
    public function getReffArusKas(){
        return $this->db->get($this->table_kas)->result();
    }
    public function getNextlMasterID(){
        return $this->db->select('COALESCE (max(trans_id),0) next')
        ->from($this->table)
        ->get()
        ->result()[0]->next;

    }

    public function getJurnalById($id){
        return $this->db->where('id_transaksi',$id)->get($this->table)->row();
    }

    public function countJurnalNoReff($noReff){
        return $this->db->where('no_reff',$noReff)->get($this->table_detail)->num_rows();
    }

    public function getJurnalByYear(){
        return $this->db->select('tgl_transaksi')
                        ->from($this->table)
                        ->group_by('year(tgl_transaksi)')
                        ->get()
                        ->result();
    }

    public function getJurnalByYearAndMonth(){
        return $this->db->select('tgl_transaksi')
                        ->from($this->table)
                        ->group_by('month(tgl_transaksi)')
                        ->group_by('year(tgl_transaksi)')
                        ->get()
                        ->result();
    }

    public function getAkunInJurnal(){
        return $this->db->select("$this->table_detail.no_reff,akun.no_reff,akun.nama_reff")
                    ->from($this->table_detail)            
                    ->join('akun',"$this->table_detail.no_reff = akun.no_reff")
                    ->order_by('akun.no_reff','ASC')
                    ->group_by('akun.nama_reff')
                    ->get()
                    ->result();
    }

    public function countAkunInJurnal(){
        return $this->db->select('transaksi.no_reff,akun.no_reff,akun.nama_reff')
                    ->from($this->table)            
                    ->join('akun','transaksi.no_reff = akun.no_reff')
                    ->order_by('akun.no_reff','ASC')
                    ->group_by('akun.nama_reff')
                    ->get()
                    ->num_rows();
    }

    public function getJurnalByNoReff($noReff){
        return $this->db->select('*')
                    ->from($this->table)            
                    ->where($this->table_detail.'.trans_id',$noReff)
                    ->join("$this->table_detail","$this->table_detail.trans_id = $this->table.trans_id")
                    ->join("$this->table_akun","$this->table_detail.no_reff = $this->table_akun.no_reff")
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffMonthYear($noReff,$bulan,$tahun){
        return $this->db->select('transaksi_master.trans_id,transaksi_master.tgl_transaksi,akun.nama_reff,transaksi_detail.no_reff,transaksi_detail.d_or_k,transaksi_detail.nominal')
        ->from($this->table)            
        ->where($this->table_detail.'.no_reff',$noReff)
        ->join("$this->table_detail","$this->table_detail.trans_id = $this->table.trans_id")
        ->join("$this->table_akun","$this->table_detail.no_reff = $this->table_akun.no_reff")
        ->where("month($this->table.tgl_transaksi)",$bulan)
        ->where("year($this->table.tgl_transaksi)",$tahun)   
        ->order_by('tgl_transaksi','ASC')
        ->get()
         ->result();
    }
    public function getJurnalByNoReffSaldo($noReff){
        return $this->db->select('*')
                    ->from($this->table_detail)            
                    ->where($this->table_detail.'.no_reff',$noReff)
                    ->join($this->table,$this->table_detail.".trans_id = $this->table.trans_id")
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalByNoReffSaldoMonthYear($noReff,$bulan,$tahun){
        return $this->db->select('*')
                    ->from($this->table_detail)            
                    ->where($this->table_detail.'.no_reff',$noReff)
                    ->where("month($this->table.tgl_transaksi)",$bulan)
                    ->where("year($this->table.tgl_transaksi)",$tahun)
                    ->join($this->table,$this->table_detail.".trans_id = $this->table.trans_id")
                    ->order_by('tgl_transaksi','ASC')
                    ->get()
                    ->result();
    }

    public function getJurnalJoinAkun(){
        return $this->db->select('transaksi.id_transaksi,transaksi.tgl_transaksi,akun.nama_reff,transaksi.no_reff,transaksi.jenis_saldo,transaksi.saldo,transaksi.tgl_input')
                        ->from($this->table)
                        ->join('akun','transaksi.no_reff = akun.no_reff')
                        ->order_by('tgl_transaksi','ASC')
                        ->order_by('tgl_input','ASC')
                        ->order_by('jenis_saldo','ASC')
                        ->get()
                        ->result();
    }

    public function getJurnalJoinAkunDetail($bulan,$tahun){
        return $this->db->select('transaksi_master.trans_id,transaksi_master.tgl_transaksi,akun.nama_reff,transaksi_detail.no_reff,transaksi_detail.d_or_k,transaksi_detail.nominal')
                        ->from($this->table)
                        ->where('month(transaksi_master.tgl_transaksi)',$bulan)
                        ->where('year(transaksi_master.tgl_transaksi)',$tahun)
                        ->join('transaksi_detail','transaksi_detail.trans_id = transaksi_master.trans_id')
                        ->join('akun','transaksi_detail.no_reff = akun.no_reff')
                        ->order_by('tgl_transaksi','ASC')
                        ->order_by('d_or_k','ASC')
                        ->get()
                        ->result();
    }

    public function getTotalSaldoDetail($jenis_saldo,$bulan,$tahun){
        return $this->db->select_sum('nominal')
                        ->from($this->table)
                        ->join('transaksi_detail','transaksi_detail.trans_id = transaksi_master.trans_id')
                        ->where('month(transaksi_master.tgl_transaksi)',$bulan)
                        ->where('year(transaksi_master.tgl_transaksi)',$tahun)
                        ->where('d_or_k',$jenis_saldo)
                        ->get()
                        ->row();
    }

    public function getTotalSaldo($jenis_saldo){
        return $this->db->select_sum('saldo')
                        ->from($this->table)
                        ->where('jenis_saldo',$jenis_saldo)
                        ->get()
                        ->row();
    }

    public function insertJurnalMaster($data){
        return $this->db->insert($this->table,$data);
    }
    public function insertJurnalDetail($data){
        return $this->db->insert($this->table_detail,$data);
    }

    public function updateJurnal($id,$data){
        return $this->db->where('id_transaksi',$id)->update($this->table,$data);
    }

    public function deleteJurnal($id){
        return $this->db->where('id_transaksi',$id)->delete($this->table);
    }

    
}