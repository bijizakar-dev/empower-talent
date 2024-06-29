<?= $this->extend('layout/default') ?>

<?= $this->section('title') ?>
    <title><?= $title ?> &mdash; Empower Talent</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <style>
        .label-container {
            display: flex;
            justify-content: space-between;
        }
        .label-text {
            text-align: left;
            flex-grow: 1;
        }
        .label-colon {
            text-align: right;
            padding-left: 5px;
        }
        .show_line_colon {
            display: inline-block;
            border-bottom: 1px solid #000; /* Add underline */
            padding-bottom: 2px;
            min-width: 100px;
            font-style: italic;
        }
    </style>
    
    <script src="<?= base_url()?>/assets/js/simple-datatables.min.js"></script>
    <script type="text/javascript">
        var dataTable1;
        var dataTable2;

       

        $(function() {
            
            get_list_pengajuan_permit();
            reset_form();

            $('#start_date_permit').datetimepicker({
                dateFormat: 'yy-mm-dd',
                timeFormat: 'HH:mm:ss',
                minDate: new Date(),
                beforeShowDay: function(date) {
                    var disabledDates = [new Date()];
                    for (var i = 0; i < disabledDates.length; i++) {
                        if (date.getTime() == disabledDates[i].getTime()) {
                            return [false, "", "Disabled"];
                        }
                    }
                    return [true, ""];
                }
            });

            $('#end_date_permit').datetimepicker({
                dateFormat: 'yy-mm-dd',
                timeFormat: 'HH:mm:ss',
                minDate: new Date(),
                beforeShowDay: function(date) {
                    var disabledDates = [new Date()];
                    for (var i = 0; i < disabledDates.length; i++) {
                        if (date.getTime() == disabledDates[i].getTime()) {
                            return [false, "", "Disabled"];
                        }
                    }
                    return [true, ""];
                }
            });


            $('#reload').click(function(){
                reset_form();
                get_list_pengajuan_permit();
            });

            $('#add').click(function() {
                reset_form();
                $('#add_modal').modal('show');
                $('.modal-title').html('Tambah Pengajuan Izin')
            });

            $('#id_employee_permit').change(function() {
                $('#id_employee_permit_hidden').val($(this).val());
                show_data_employee($(this).val());
            });
        })

        function reset_form() {
            $('.add_permit').val('');
            $('#id_employee_permit').attr('disabled', false);

            $('.edit_status_permit_form').val('')

            $('#img_emp').removeAttr('src')
            $('#img_emp').attr('src', '<?= base_url()?>template/assets/img/demo/user-placeholder.svg'); 
        }

        function get_list_pengajuan_permit() {

            if (dataTable1) {
                dataTable1.destroy();
            }
            // dataTables1 = new simpleDatatables.DataTable("#datatablesSimple1");    

            $('.table-pengajuan tbody').empty();
            
            $.ajax({
                url: '<?= base_url('api/service/listPermit') ?>',
                type: 'GET',
                data: 'created_start=<?= date('Y-m-1')?>&created_end=<?= date('Y-m-d')?>&status=Submitted,Pending',
                dataType: 'json',
                beforeSend: function() {
                    showLoading();
                    reset_form();
                },
                success: function(response) {

                    let str = ''; let status = ''; let badgeStatus = '';
                    if(response.data.jumlah != 0) {
                        $.each(response.data, function(i, v) {
                            note = (v.note != null && v.note != '') ? '<br><span style="font-size: 13px" class="badge bg-yellow-soft text-yellow"><small> Note : . '+v.note+'</small></span>' : ''
                            status = v.status
                            if(v.status == 'Submitted') {
                                badgeStatus = 'bg-blue-soft text-blue';
                            } else if(v.status == 'Pending') {
                                badgeStatus = 'bg-purple-soft text-purple';
                            } else if(v.status == 'Approved') {
                                badgeStatus = 'bg-green-soft text-green'
                            } else if(v.status == 'Rejected') {
                                badgeStatus = 'bg-red-soft text-red';
                                note = '<br><span style="font-size: 13px;" class="badge bg-red-soft text-red"><small> Alasan Penolakan : '+v.reason_rejected+'</small></span>'
                            } else {
                                badgeStatus = 'bg-yellow-soft text-yellow';
                            }

                            str = '<tr>'+
                                    '<td>'+
                                        '<div class="d-flex align-items-center">'+
                                            '<div class="avatar me-2"><i data-feather="user"></i></div>'+
                                            '<div><span style="font-size: 13px"><small> P : '+v.created_at+'</small></span> <hr >'+v.name+' <br> <span style="font-size: 13px"><small> NIP. '+v.nip+'</small></span></div>'+
                                        '</div>'+
                                    '</td>'+
                                    '<td>'+
                                        '<span style="font-size: 13px"><small>S : '+v.start_date+'</small></span><br>'+
                                        '<span style="font-size: 13px"><small>E :  '+v.end_date+'</small></span><br>'+
                                        '<span style="font-size: 13px"><small>Dur : '+v.duration+'</small></span><br>'+
                                    '</td>'+
                                    '<td>'+
                                        '<div>'+v.reason+'<br>'+note+'</div>'+
                                    '</td>'+
                                    '<td><span class="badge '+badgeStatus+'">'+status+'</span></td>'+
                                    '<td>'+
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_status_permit('+v.id+')" title="Ubah Status Pengajuan"><i data-feather="check-circle"></i></button> '+     
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark me-2" onclick="edit_permit('+v.id+')" title="Ubah Data"><i data-feather="edit"></i></button> '+     
                                        '<button type="button" class="btn btn-datatable btn-icon btn-transparent-dark" onclick="delete_permit('+v.id+')" title="Hapus Data"><i data-feather="trash-2"></i></button>'+
                                    '</td>'+
                                '</tr>';
                            $('.table-pengajuan tbody').append(str);
                        });
                    } else {
                        console.log('KOSONG')

                        str = '<tr><td class="datatable-empty" colspan="5">No entries found</td></tr>';
                        $('.table-pengajuan tbody').append(str);
                    }

                    feather.replace();

                    dataTable1 = new simpleDatatables.DataTable("#datatablesSimple1");
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }
        
        function save_permit() {
            let addForm = $('#add_form').serialize();

            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/service/permit") ?>',
                data: addForm,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    dataTables1 = new simpleDatatables.DataTable("#datatablesSimple1");      

                    $('#add_modal').modal('hide');
                    get_list_pengajuan_permit();

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
                },
                complete: function() {
                    hideLoading();
                    reset_form();
                }
            });
        }

        function delete_permit(id) {
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
                        url: '<?= base_url("api/service/permit") ?>?id='+id,
                        cache: false,
                        dataType : 'json',
                        beforeSend: function() {
                            showLoading();
                            reset_form();
                        },
                        success: function(data) {
                            $('#add_modal').modal('hide');
                            get_list_pengajuan_permit()

                            Swal.fire("Berhasil", "Data Berhasil Hapus", "success");
                        },
                        error: function(e){
                            Swal.fire({
                                title: "Access Failed",
                                text: "Internal Server Error",
                                icon: "error"
                            });
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }
            });

            
        }

        function edit_permit(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/service/permit")?>?id='+id,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                    reset_form();
                },
                success: function(data) {
                    $('#id_permit').val(id);
                    $('#id_employee_permit').val(data.data.id_employee);
                    $('#id_type_permit').val(data.data.id_type);
                    $('#end_date_permit').val(data.data.end_date);
                    $('#start_date_permit').val(data.data.start_date);
                    $('#reason_permit').val(data.data.reason);

                    if(data.data.duration.includes('Hari')) {
                        $('#duration_type_permit').val('Hari');
                    } else {
                        $('#duration_type_permit').val('Jam');
                    }

                    // Set the hidden input value and disable the select
                    let idEmpVal = $('#id_employee_permit').val();
                    $('#id_employee_permit_hidden').val(idEmpVal);
                    $('#id_employee_permit').attr('disabled', true);

                    $('.modal-title').html('Edit Data Pengajuan Izin')
                    $('#add_modal').modal('show');
                },
                error: function(e){
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        function edit_status_permit(id) {
            if(id == '' || id == null) {
                return false;
            }

            $.ajax({
                type : 'GET',
                url: '<?= base_url("api/service/permit")?>?id='+id,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                    reset_form();
                },
                success: function(data) {
                    $('#id_permit_status').val(id);
                    $('#status_permit').val(data.data.status);
                    $('#reason_rejected_permit').val(data.data.reason_rejected);
                    $('#note_permit').val(data.data.note);

                    status = data.data.status
                    if(status == 'Submitted') {
                        badgeStatus = 'bg-blue-soft text-blue';
                    } else if(status == 'Pending') {
                        badgeStatus = 'bg-purple-soft text-purple';
                    } else if(status == 'Approved') {
                        badgeStatus = 'bg-green-soft text-green'
                    } else if(status == 'Rejected') {
                        badgeStatus = 'bg-red-soft text-red';
                    } else {
                        badgeStatus = 'bg-yellow-soft text-yellow';
                    }
                    
                    $('#nip_emp').html(data.data.nip);
                    $('#name_emp').html(data.data.name);
                    $('#start_date_emp').html(data.data.start_date);
                    $('#end_date_emp').html(data.data.end_date);
                    $('#duration_emp').html(data.data.duration);
                    $('#reason_emp').html(data.data.reason);
                    $('#type_emp').html(data.data.type_name);
                    $('#status_emp').html('<span class="badge '+badgeStatus+'">'+status+'</span>');

                    $('.modal-title').html('Edit Status Pengajuan Izin')
                    $('#edit_status_modal').modal('show');
                },
                error: function(e){
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        function save_update_status_permit() {
            let statusForm = $('#status_form').serialize();
            console.log($('#id_permit_status').val())
            $.ajax({
                type : 'POST',
                url: '<?= base_url("api/service/updateStatusPermit") ?>',
                data: statusForm,
                cache: false,
                dataType : 'json',
                beforeSend: function() {
                    showLoading();
                },
                success: function(data) {
                    if(!data.success) {
                        return false;
                    } else {
                        $('#edit_status_modal').modal('hide');
                        dataTables1 = new simpleDatatables.DataTable("#datatablesSimple1");   

                        get_list_pengajuan_permit()

                        Swal.fire({
                            title: "Berhasil",
                            text: "Data Berhasil Simpan",
                            icon: "success"
                        });
                    }
                   

                },
                error: function(e){
                    Swal.fire({
                        title: "Access Failed",
                        text: "Internal Server Error",
                        icon: "error"
                    });
                },
                complete: function() {
                    hideLoading();
                    reset_form();
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
                                List Pengajuan Izin
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">
                            <button type="button" class="btn btn-sm btn-light text-primary" id="reload">
                                <i class="me-1" data-feather="search"></i>
                                Pencarian
                            </button>
                            <button type="button" class="btn btn-sm btn-light text-primary" id="reload">
                                <i class="me-1" data-feather="refresh-ccw"></i>
                                Reload
                            </button>
                            <button type="button" class="btn btn-sm btn-light text-primary" id="add">
                                <i class="me-1" data-feather="user-plus"></i>
                                Tambah Pengajuan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-fluid px-4 mb-5">
            <div class="card">
                <div class="card-body">
                    <table id="datatablesSimple1" class="table-pengajuan">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Waktu Izin</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nama</th>
                                <th>Waktu Izin</th>
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
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 0px 20px 0px 20px">
                        <form id="add_form">
                            <input type="hidden" class="form-control add_permit" id="id_permit" name="id">
                            <input type="hidden" class="form-control add_permit" id="id_employee_permit_hidden" name="id_employee">

                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-12" for="id_employee_permit">Pegawai</label>
                                    <select class="form-select add_permit" id="id_employee_permit" name="id_employee" aria-label="Default select example">
                                        <option value="" selected disabled>Pilih Pegawai...</option>
                                        <?php foreach ($employee as $key => $value): ?>
                                            <option value="<?= esc($key) ?>"><?= esc($value) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="id_type_permit">Jenis Izin</label>
                                    <select class="form-select add_permit" id="id_type_permit" name="id_type" aria-label="Default select example">
                                        <option value="" selected disabled>Pilih Jenis Izin...</option>
                                        <?php foreach ($reference_type as $key => $value): ?>
                                            <option value="<?= esc($key) ?>"><?= esc($value) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-4">
                                    <label class="small mb-1" for="duration_type_permit">Jenis Durasi</label>
                                    <select class="form-select add_permit" id="duration_type_permit" name="duration_type" aria-label="Default select example">
                                        <option value="" selected disabled>Pilih Durasi...</option>
                                        <option value="Hari">Hari</option>
                                        <option value="Jam">Jam</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="small mb-1" for="start_date_permit">Tanggal Awal</label>
                                    <input class="form-control add_permit" id="start_date_permit" type="text" name="start_date" placeholder="Tanggal Awal" />
                                </div>
                                <div class="col-md-4">
                                    <label class="small mb-1" for="end_date_permit">Tanggal Awal</label>
                                    <input class="form-control add_permit" id="end_date_permit" type="text" name="end_date" placeholder="Tanggal Akhir" />
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-12" for="reason_permit">Alasan Izin</label>
                                    <textarea class="form-control add_permit" id="reason_permit" name="reason" placeholder="Keterangan Alasan"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></i> &nbsp; Keluar</button>
                    <button class="btn btn-light btn-sm" type="button" onclick="save_permit()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_status_modal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Status Pengajuan Izin</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" style="padding: 0px 20px 0px 20px">
                        <div class="col-xl-6">
                            <div class="card mb-4 mb-xl-0">
                                <div class="card-header">Detail Pengajuan</div>
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">NIP</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-9">
                                                <span class="show_line_colon edit_status_permit" style="min-width: 100%;" id="nip_emp"></span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">Nama</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-9">
                                                <span class="show_line_colon edit_status_permit" style="min-width: 100%;" id="name_emp"></span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">Awal</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-9">
                                                <span class="show_line_colon edit_status_permit" style="min-width: 100%;" id="start_date_emp"></span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">Akhir</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-9">
                                                <span class="show_line_colon edit_status_permit" style="min-width: 100%;" id="end_date_emp"></span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">Durasi</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <span class="show_line_colon edit_status_permit" style="min-width: 100%;" id="duration_emp"></span>
                                            </div>
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">Jenis Izin</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-3">
                                                <span class="show_line_colon edit_status_permit" style="min-width: 100%;" id="type_emp"></span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">Alasan</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-9">
                                                <span class="show_line_colon edit_status_permit" style="min-width: 100%;" id="reason_emp"></span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-lg-3 label-container">
                                                <span class="label-text">Status</span>
                                                <span class="label-colon">:</span>
                                            </div>
                                            <div class="col-lg-9">
                                                <span class="edit_status_permit" style="min-width: 100%;" id="status_emp"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card mb-4 mb-xl-0">
                                <div class="card-header">Status Pengajuan</div>
                                <div class="card-body">
                                    <div class="container">
                                        <form id="status_form">
                                            <input type="hidden" class="form-control edit_status_permit_form" id="id_permit_status" name="id">

                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-12">
                                                    <label class="small mb-12" for="status_permit">Status</label>
                                                    <select class="form-select edit_status_permit_form" id="status_permit" name="status" aria-label="Default select example">
                                                        <option value="" selected disabled>Pilih Status...</option>
                                                        <option value="Submitted">Submitted</option>
                                                        <option value="Pending">Pending</option>
                                                        <option value="Approved">Approved</option>
                                                        <option value="Rejected">Rejected</option>
                                                        <option value="Cancelled">Cancelled</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-12">
                                                    <label class="small mb-12" for="reason_rejected_permit">Alasan Penolakan <small style="font-size: 10px;"><i>*Jika Rejected</i></small></label>
                                                    <textarea class="form-control edit_status_permit_form" id="reason_rejected_permit" name="reason_rejected" placeholder="Alasan Penolakan"></textarea>
                                                </div>
                                            </div>
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-12">
                                                    <label class="small mb-12" for="note_permit">Catatan</label>
                                                    <textarea class="form-control edit_status_permit_form" id="note_permit" name="note" placeholder="Catatan"></textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-sm" type="button" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></i> &nbsp; Keluar</button>
                    <button class="btn btn-light btn-sm" type="button" onclick="save_update_status_permit()"><i class="fa-regular fa-floppy-disk"></i> &nbsp; Simpan</button>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>