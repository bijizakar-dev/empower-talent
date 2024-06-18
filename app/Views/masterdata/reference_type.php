<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title><?= $title ?> &mdash; Empower Talent</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <script src="<?= base_url()?>/assets/js/simple-datatables.min.js"></script>
    <script type="text/javascript">
        var dataTable;

        get_list_ref_type();
        reset_form();

        $(function() {
            // dataTables = new simpleDatatables.DataTable("#datatablesSimple");

            $('#reload').click(function(){
                reset_form();
                get_list_ref_type();
            });

            $('#add').click(function() {
                reset_form();
                $('#add_modal').modal('show');
            });
        });

        function reset_form() {
            $('.add_ref_type').val('');
        }

        function get_list_ref_type() {
            if (dataTable) {
                dataTable.destroy();
            }
            $('.table-ref-type tbody').empty();
            
            $.ajax({
                url: '<?= base_url('api/masterdata/listReferenceType') ?>',
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
                                        v.category+
                                    '</div>'+
                                '</td>'+
                                '<td>'+v.name+'</td>'+
                                '<td><span class="badge '+badgeStatus+'">'+status+'</span></td>'+
                                '<td>'+
                                    '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_ref_type('+v.id+')"><i data-feather="edit"></i></button>'+    
                                    '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="delete_ref_type('+v.id+')"><i data-feather="trash-2"></i></button>'+
                                '</td>'+
                            '</tr>';
                        $('.table-ref-type tbody').append(str);
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
        
        function save_ref_type() {
            let addForm = $('#add_form').serialize();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/masterdata/referenceType") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                success: function(data) {
                    $('#add_modal').modal('hide');
                    get_list_ref_type()

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

        function delete_ref_type(id) {
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
                        url: '<?= base_url("api/masterdata/referenceType") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        success: function(data) {
                            $('#add_modal').modal('hide');
                            get_list_ref_type()

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

        function edit_ref_type(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/masterdata/referenceType")?>?id='+id,
                cache: false,
                dataType : 'json',
                success: function(data) {
                    $('#id_ref_type').val(id);
                    $('#name_ref_type').val(data.data.name);
                    $('#category_ref_type').val(data.data.category);
                    $('#active_ref_type').val(data.data.active);

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
                    <table id="datatablesSimple" class="table-ref-type">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Nama Referensi Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Kategori</th>
                                <th>Nama Referensi Jenis</th>
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
                            <input type="hidden" class="form-control add_ref_type" id="id_ref_type" name="id">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="name_ref_type">Nama Referensi Jenis</label>
                                    <input class="form-control add_ref_type" id="name_ref_type" name="name" type="text" placeholder="Referensi Jenis"/>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="category_ref_type">Kategori</label>
                                    <textarea class="form-control add_ref_type" id="category_ref_type" name="category" type="text" placeholder="Kategori"></textarea>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="active_ref_type">Status</label>
                                    <select class="form-control add_ref_type" id="active_ref_type" name="active">
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
                    <button class="btn btn-light btn-sm" type="button" onclick="save_ref_type()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>