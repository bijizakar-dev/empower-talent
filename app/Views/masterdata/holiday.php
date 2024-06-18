<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title><?= $title ?> &mdash; Empower Talent</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <script src="<?= base_url()?>/assets/js/simple-datatables.min.js"></script>
    <script type="text/javascript">
        var dataTables;

        get_list_holiday();
        reset_form();   

        $(document).ready(function() {

            // dataTables = new simpleDatatables.DataTable("#datatablesSimple");

            $('#reload').click(function(){
                reset_form();
                get_list_holiday();
            });

            $('#add').click(function() {
                reset_form();
                $('#add_modal').modal('show');
            });

        });

        function reset_form() {
            $('.add_holiday').val('');
        }

        function get_list_holiday() {
            showLoading();
            if (dataTables) {
                dataTables.destroy();
            }
            $('.table-holiday tbody').empty();

            $.ajax({
                url: '<?= base_url('api/masterdata/listHoliday') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    hideLoading();

                    if(response.data.length === 0) {
                        return false;
                    }

                    let str = ''; 
                    let status = ''; let badgeStatus = '';
                    let cutiBersama = ''; let badgeCutiBersama = '';

                    $.each(response.data, function(i, v) {
                        badgeStatus = v.active == 1 ? 'bg-green-soft text-green' : 'bg-red-soft text-red';
                        status = v.active == 1 ? 'Aktif' : 'Non-Aktif'

                        if(v.active != 0) {
                            badgeCutiBersama = v.national_holiday == 0 ? 'bg-blue-soft text-blue' : 'bg-yellow-soft text-yellow';
                            cutiBersama = v.national_holiday == 0 ? 'Libur' : 'Cuti Bersama'
                        }
                        
                        str = '<tr>'+
                                '<td>'+
                                    '<div class="d-flex align-items-center">'+
                                        '<div class="avatar me-2"><i data-feather="smile"></i></div>'+
                                        v.date+
                                    '</div>'+
                                '</td>'+
                                '<td>'+v.name+'</td>'+
                                '<td>'+
                                    '<span class="badge '+badgeStatus+'">'+status+'</span> '+
                                    '<span class="badge '+badgeCutiBersama+'">'+cutiBersama+'</span>'+
                                '</td>'+
                                '<td>'+
                                    '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_holiday('+v.id+')"><i data-feather="edit"></i></button>'+    
                                    '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="delete_holiday('+v.id+')"><i data-feather="trash-2"></i></button>'+
                                '</td>'+
                            '</tr>';
                        $('.table-holiday tbody').append(str);
                       
                    });
                    feather.replace();
                    
                    dataTables = new simpleDatatables.DataTable("#datatablesSimple");

                },
                error: function(xhr, status, error) {
                    hideLoading();
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                }
            });
        }

        function save_holiday() {
            showLoading();
            let addForm = $('#add_form').serialize();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/masterdata/holiday") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                success: function(data) {
                    hideLoading();
                    $('#add_modal').modal('hide');
                    $('.table-holiday tbody').empty();

                    get_list_holiday()
                    
                    Swal.fire({
                        title: "Berhasil",
                        text: "Data Berhasil Simpan",
                        icon: "success"
                    });

                },
                error: function(e){
                    hideLoading();
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                }
            });
        }

        function delete_holiday(id) {
            if(id == '' || id == null) {
                return false;
            }

            Swal.fire({
                icon: "question",
                title: "Konfirmasi Hapus",
                text: "Anda yakin untuk menghapus data ini ?",
                showCancelButton: true,
                confirmButtonText: "Ya",
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    $.ajax({
                        type : 'DELETE',
                        url: '<?= base_url("api/masterdata/holiday") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        success: function(data) {
                            hideLoading();
                            $('#add_modal').modal('hide');
                            get_list_holiday()

                            Swal.fire("Berhasil", "Data Berhasil Hapus", "success");
                        },
                        error: function(e){
                            hideLoading();
                            Swal.fire({
                                title: "Access Failed",
                                text: "Internal Server Error",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }

        function edit_holiday(id) {
            if(id == '' || id == null) {
                return false;
            }
            showLoading();
            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/masterdata/holiday")?>?id='+id,
                cache: false,
                dataType : 'json',
                success: function(data) {
                    hideLoading();
                    $('#id_holiday').val(id);
                    $('#date_holiday').val(data.data.date);
                    $('#name_holiday').val(data.data.name);
                    $('#national_holiday').val(data.data.national_holiday);
                    $('#active_holiday').val(data.data.active);

                    $('.modal-title').html('Edit Data')
                    $('#add_modal').modal('show');
                },
                error: function(e){
                    hideLoading();
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                }
            });
        }

        function generate_holiday() {
            Swal.fire({
                icon: "question",
                title: "Konfirmasi Generate Libur Nasional",
                text: "Jika melakukan aksi ini data hari libur anda akan terhapus. Anda yakin akan melakukan aksi ini ?",
                showCancelButton: true,
                confirmButtonText: "Ya",
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    $.ajax({
                        type : 'GET',
                        url: '<?= base_url("api/masterdata/nationalHolidayAPI") ?>',
                        cache: false,
                        dataType : 'json',
                        success: function(data) {
                            hideLoading();

                            get_list_holiday()
                            Swal.fire("Berhasil", "Data Berhasil Disesuaikan", "success");
                        },
                        error: function(e){
                            hideLoading();
                            Swal.fire({
                                title: "Access Failed",
                                text: "Internal Server Error",
                                icon: "error"
                            });
                        }
                    });
                }
            });

        }


    </script>
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="list"></i></div>
                                <?= $title ?>
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">
                            <button type="button" class="btn btn-sm btn-light text-primary" id="reload">
                                <i class="me-1" data-feather="refresh-ccw"></i>
                                Reload
                            </button>
                            <button type="button" class="btn btn-sm btn-light text-primary" id="add">
                                <i class="me-1" data-feather="user-plus"></i>
                                Tambah Data
                            </button>
                            <button type="button" class="btn btn-sm btn-light text-primary" id="add" onclick="generate_holiday()">
                                <i class="me-1" data-feather="download-cloud"></i>
                                Generate Libur Nasional
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-fluid px-4">
            <div class="card">
                <div class="card-body">
                    <table id="datatablesSimple" class="table-holiday">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="add_modal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 0px 20px 0px 20px">
                        <form id="add_form">
                            <input type="hidden" class="form-control add_holiday" id="id_holiday" name="id">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="date_holiday">Tanggal Libur</label>
                                    <input class="form-control add_holiday" id="date_holiday" name="date" type="date" placeholder="Tanggal"/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="name_holiday">Keterangan</label>
                                    <input class="form-control add_holiday" id="name_holiday" name="name" type="text" placeholder="Keterangan"/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="national_holiday">Cuti Bersama</label>
                                    <select class="form-control add_holiday" id="national_holiday" name="national_holiday">
                                        <option value="" disabled selected>Pilih Status...</option>
                                        <option value="1" >Ya</option>
                                        <option value="0" >Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="active_holiday">Status</label>
                                    <select class="form-control add_holiday" id="active_holiday" name="active">
                                        <option value="" disabled selected>Pilih Status...</option>
                                        <option value="1" >Aktif</option>
                                        <option value="0" >Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></i> &nbsp; Keluar</button>
                    <button class="btn btn-light btn-sm" type="button" onclick="save_holiday()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
