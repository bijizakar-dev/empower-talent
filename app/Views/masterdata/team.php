<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title><?= $title ?> &mdash; Empower Talent</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <script src="<?= base_url()?>/assets/js/simple-datatables.min.js"></script>
    <script type="text/javascript">
        var dataTable;

        get_list_team();
        reset_form();

        $(function() {
            // dataTables = new simpleDatatables.DataTable("#datatablesSimple");

            $('#reload').click(function(){
                reset_form();
                get_list_team();
            });

            $('#add').click(function() {
                reset_form();
                $('#add_modal').modal('show');
                $('.modal-title').html('Tambah Data')
            });
        });

        function reset_form() {
            $('.add_team').val('');
        }

        function get_list_team() {
            if (dataTable) {
                dataTable.destroy();
            }
            $('.table-team tbody').empty();
            
            $.ajax({
                url: '<?= base_url('api/masterdata/listTeam') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if(response.data.length === 0) {
                        return false;
                    }

                    let str = ''; let status = ''; let badgeStatus = '';
                    $.each(response.data, function(i, v) {
                        badgeStatus = v.active == 1 ? 'bg-green-soft text-green' : 'bg-red-soft text-red';
                        status = v.active == 1 ? 'Aktif' : 'Non-Aktif'

                        str = '<tr>'+
                                '<td>'+
                                    '<div class="d-flex align-items-center">'+
                                        '<div class="avatar me-2"><i data-feather="smile"></i></div>'+
                                        v.name+
                                    '</div>'+
                                '</td>'+
                                '<td>'+v.description+'</td>'+
                                '<td><span class="badge '+badgeStatus+'">'+status+'</span></td>'+
                                '<td>'+
                                    '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_team('+v.id+')"><i data-feather="edit"></i></button>'+    
                                    '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="delete_team('+v.id+')"><i data-feather="trash-2"></i></button>'+
                                '</td>'+
                            '</tr>';
                        $('.table-team tbody').append(str);
                    });

                    feather.replace();

                    dataTable = new simpleDatatables.DataTable("#datatablesSimple");
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                }
            });
        }
        
        function save_team() {
            let addForm = $('#add_form').serialize();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/masterdata/team") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                success: function(data) {
                    $('#add_modal').modal('hide');
                    get_list_team()

                    Swal.fire({
                        title: "Berhasil",
                        text: "Data Berhasil Simpan",
                        icon: "success"
                    });

                },
                error: function(e){
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                }
            });
        }

        function delete_team(id) {
            if(id == '' || id == null) {
                return false;
            }

            Swal.fire({
                icon: "question",
                title: "Anda yakin untuk hapus data ?",
                showCancelButton: true,
                confirmButtonText: "Ya",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type : 'DELETE',
                        url: '<?= base_url("api/masterdata/team") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        success: function(data) {
                            $('#add_modal').modal('hide');
                            get_list_team()

                            Swal.fire("Berhasil", "Data Berhasil Hapus", "success");
                        },
                        error: function(e){
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

        function edit_team(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/masterdata/team")?>?id='+id,
                cache: false,
                dataType : 'json',
                success: function(data) {
                    $('#id_team').val(id);
                    $('#name_team').val(data.data.name);
                    $('#description_team').val(data.data.description);
                    $('#active_team').val(data.data.active);

                    $('.modal-title').html('Edit Data')
                    $('#add_modal').modal('show');
                },
                error: function(e){
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
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
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-fluid px-4">
            <div class="card">
                <div class="card-body">
                    <table id="datatablesSimple" class="table-team">
                        <thead>
                            <tr>
                                <th>Nama Team</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nama Team</th>
                                <th>Deskripsi</th>
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
                            <input type="hidden" class="form-control add_team" id="id_team" name="id">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="name_team">Nama Team</label>
                                    <input class="form-control add_team" id="name_team" name="name" type="text" placeholder="Team"/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="description_team">Deskripsi</label>
                                    <textarea class="form-control add_team" id="description_team" name="description" type="text" placeholder="Deskripsi Team"></textarea>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="active_team">Status</label>
                                    <select class="form-control add_team" id="active_team" name="active">
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
                    <button class="btn btn-light btn-sm" type="button" onclick="save_team()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>