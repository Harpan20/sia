  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="./index.html">Data Akun</a>
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
                  <h3 class="mb-3"><?= $titleTag ?></h3>
                </div>
                <div class="col-12 my-3">
                <form action="<?= base_url($action) ?>" method="post">
                  <?php 
                    if(!empty($id)):
                  ?>
                   <input type="hidden" name="id" value="<?= $id ?>">
                  <?php endif; ?>
                  <div class="row">
                    <div class="col">
                    <div class="form-group ">
                        <label for="datepicker">Tanggal</label>
                        <input class="form-control" id="datepicker" name="tgl_transaksi" type="text" value="<?= $data->tgl_transaksi ?>">
                        <p><?= form_error('tgl_transaksi') ?></p>
                      </div>
                    </div>
                        <div class="col">
                        <div class="form-group ">
                          <label for="no_bukti" class="">No Bukti</label>
                            <input class="form-control" id="no_bukti" name="no_bukti" type="text" value="<?= $data->no_bukti ?>">
                            <p><?= form_error('no_bukti') ?></p>
                      </div>
                        </div>
                        </div>
                    <div class="row">
                      <div class="col">
                      <div class="form-group ">
                        <label for="akun_debet">Akun Debet</label>
                        <?=form_dropdown('akun_debet',getDropdownList('akun',['no_reff','nama_reff']),$data->akun_debet,['class'=>'form-control','id'=>'akun_debet']);?>
                        <?= form_error('akun_debet') ?>
                       </div>
                      </div>
                      <div class="col">
                       <div class="form-group">
                        <label for="akun_kredit">Akun Kredit</label>
                        <?=form_dropdown('akun_kredit',getDropdownList('akun',['no_reff','nama_reff']),$data->akun_kredit,['class'=>'form-control','id'=>'akun_kredit']);?>
                        <?= form_error('akun_kredit') ?>
                       </div>
                       </div>
                       </div>

                       <div class="row">
                      <div class="col">
                       <div class="form-group">
                      <label for="saldo">Jumlah</label>
                            <input class="form-control" id="saldo" name="saldo" type="text" value="<?= $data->saldo ?>">
                            <p><?= form_error('saldo') ?></p>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-group">
                      <label for="keterangan">Keterangan</label>
                            <input class="form-control" id="keterangan" name="keterangan" type="text" value="<?= $data->keterangan ?>">
                            <p><?= form_error('keterangan') ?></p>
                      </div>
                      </div>
                      </div>
                      <div class="form-group">
                        <label for="arus_kas">Komponen Arus Kas </label>
                        <?=form_dropdown('arus_kas',getDropdownList('reff_arus_kas',['kode','deskripsi']),$data->arus_kas,['class'=>'form-control','id'=>'arus_kas']);?>
                        <?= form_error('arus_kas') ?>
                       </div>
                      <div class="col-12 mt-4">
                        <button type="submit" class="btn-primary btn" id="button_akun"><?= $title ?></button>
                      </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>