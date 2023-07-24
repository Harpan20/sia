  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="./index.html">Buku Besar</a>
        <!-- Form -->
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
          <div class="form-group mb-0">
            
          </div>
        </form>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="<?= base_url('assets/img/theme/team-4-800x800.jpg') ?>">
                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"><?= ucwords($this->session->userdata('username')) ?></span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <a href="<?= base_url('logout') ?>" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Header -->
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">    
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
               
        </div>
      </div>
      <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Buku Besar</h3>
                </div>
              </div>
              <div class="row">
                <div class="col my-3">
                  <form action="<?= base_url('buku_besar') ?>" method="post" class="d-flex flex-row justify-content-end">
                      <div class="form-group mx-3">
                        <?php if(!isset($param))
                        {$param['no_reff']='';}?>
                      <?=form_dropdown('no_reff',getDropdownList('akun',['no_reff','nama_reff']),$param['no_reff'],['class'=>'form-control','id'=>'no_reff']);?>
                      </div>
                      <div class="form-group">
                        <button class="btn btn-success" type="submit">Lihat Buku Besar</button>
                      </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <caption>
                <?php if(isset($listJurnal)):?>
                  <?php if(sizeof($listJurnal)>0):?>
                <label  class="ml-3" style="font-size: 14px;"><b>Kode Akun :</b> <?=$listJurnal[0]->no_reff ?></label><br>
                <label  class="ml-3" style="font-size: 14px;"><b>Nama Akun : </b><?=$listJurnal[0]->nama_reff ?></label>
                <?php endif;?>
                  <?php endif;?>
              </caption>
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Transaksi</th>
                    <th scope="col">Debit</th>
                    <th scope="col">Kredit</th>
                    <th scope="col">Saldo</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $i=0;
                    $a=0;
                    $debit = 0;
                    $kredit = 0;
                    if(isset($listJurnal)):
                    foreach($listJurnal as $row):
                      if($row->d_or_k==$row->d_or_k_bertambah){
                        $a+=$row->nominal;
                      }else{
                        $a-=$row->nominal;
                      }
                      if($row->d_or_k=='D'){
                        $debit+=$row->nominal;
                      }else{
                        $kredit+=$row->nominal;
                      }
                    $i++;
                  ?>
                  <tr>
                    <td scope="col"><?=$i?></td>
                    <td scope="col"><?=formatDate($row->tgl_transaksi)?></td>
                    <td scope="col"><?=$row->keterangan ?></td>
                    <td class="text-end"><?=$row->d_or_k=='D' ? currency($row->nominal,2) : ''?></td>
                    <td scope="col" class="text-right"><?=$row->d_or_k=='K' ? currency($row->nominal,2) : ''?></td>
                    <td scope="col" class="text-right"><?= currency($a,2)?></td>
                  
                  </tr>
                  <?php
                    endforeach;
                  else:
                  ?>
                    <tr>
                      <td colspan="6" class="text-center text-muted">Tidak ada data yang ditampilkan</td>
                  </tr>
                  <?php endif;
                  ?>
                </tbody>
                <tfoot>
                  <?php if(isset($listJurnal)): ?>
                  <td colspan="3" style="font-weight: bold;">Total</td>
                  <td style="font-weight: bold;" class="text-right"><?= currency($debit,2)?></td>
                  <td style="font-weight: bold;" class="text-right"><?= currency($kredit,2)?></td>
                  <td style="font-weight: bold;" class="text-right"><?= currency($a,2)?></td>
                </tfoot>
                <?php endif?>
              </table>
            </div>
          </div>
        </div>
      </div>