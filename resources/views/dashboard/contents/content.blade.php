@extends('dashboard.contents.main')
@section('custom_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/app-assets/css/plugins/charts/chart-apex.css') }}">

    <link href="{{ asset('dashboard_user/assets/css/users/account-setting.css') }}" rel="stylesheet" type="text/css" />

    <!-- Styles -->
    <style>
        #trascationWeek {
            width: 100%;
            height: 500px;
        }
    </style>
@endsection
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- users list start -->
                <section class="app-user-list">

                    @role('staff')
                    <!-- users filter start -->
                    <div class="card">
                        <h5 class="card-header">Record Parking</h5>
                        <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                            <div class="col-md-6">
                                <button class="btn add-new btn-primary mt-50 w-100" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-toggle="modal" data-target="#modalEnterParking">
                                    <span>Enter New Parking</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn add-new btn-danger mt-50 w-100" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-toggle="modal" data-target="#modalOutParking">
                                    <span>Out Parking</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- users filter end -->
                    @elserole('admin')
                    <!-- users filter start -->
                    <div class="card">
                        <h5 class="card-header">Filter Data</h5>
                        <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                            <div class="col-md-3">
                                <label for="filter_from_date">From Date</label>
                                <input type="date" name="from_date" id="filter_from_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="filter_to_date">To Date</label>
                                <input type="date" name="to_date" id="filter_to_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="status_filter">Status</label>
                                <select name="status" id="status_filter" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="0">Not Completed</option>
                                    <option value="1">Completed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                {{-- <label for="status_filter">Status</label> --}}
                                <button class="btn btn-primary mt-2" id="download-excel">
                                    Download Excel
                                </button>
                                {{-- <button class="dt-button create-new btn btn-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-toggle="modal" data-target="#modals-slide-in">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus mr-50 font-small-4">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Add New Record
                                    </span>
                                </button> --}}
                            </div>
                            <div class="col-md-4 user_status"></div>
                        </div>
                    </div>
                    <!-- users filter end -->
                    @endrole

                    <!-- list section start -->
                    <div class="card">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="table" id="table-recordparking" style="margin: 2px">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Nomor Polisi</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Total Payment</th>
                                        <th>Status</th>
                                        <th>Payment Method</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- list section end -->
                </section>
                <!-- users list ends -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    @role('staff')
        @include('dashboard.contents.modal')
    @endrole
@endsection
@section('custom_js')

    <script>
        var table;
        $(function() {
            table = $('#table-recordparking').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route( Auth::user()->roles->pluck('name')[0] . '.parking.index') }}",
                    @role('admin')
                    data: function(query) {
                        query.from_date = $('#filter_from_date').val();
                        query.to_date = $('#filter_to_date').val();
                        query.status = $('#status_filter').val();
                    }
                    @endrole
                },
                columns: [{
                        data: 'number',
                        name: 'number',
                        className: "text-center"
                    },
                    {
                        data: 'nopol',
                        name: 'nama'
                    },
                    {
                        data: 'jam_masuk',
                        name: 'jam_masuk'
                    },
                    {
                        data: 'jam_keluar',
                        name: 'jam_keluar'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: "text-center"
                    },
                    {
                        data: 'paymentmethod',
                        name: 'paymentmethod'
                    },
                ],
            });
            table.on('draw.dt order.dt search.dt', function() {
                table.column(0, {
                        order: 'applied',
                        search: 'applied'
                    })
                    .nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();

            @role('admin')
                $('#filter_from_date').on('change', function() {
                    table.draw(false);
                });
                $('#filter_to_date').on('change', function() {
                    if ($("#filter_from_date").val() == "") {
                        Swal.fire({
                            title: 'Warning!',
                            text: "Filter from date not selected! Choose the date!",
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        })
                    }
                    else {
                        table.draw(false);
                    }
                });

                $('#status_filter').on('change', function() {
                    console.log($("#status_filter").val());
                    table.draw(false);
                });
            @endrole

        });
    </script>

    <script>
        $('#enterParking').on('submit', function(event) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            event.preventDefault();

            Swal.fire({
                text : "Mohon menunggu..."
            });

            Swal.showLoading();

            $.ajax({
                url: $(this).attr("action"),
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: (data) => {

                    if (data.status == "ok" && data.response == "successfully-created") {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: data.message,
                        });
                        setTimeout(function(){
                            $('#modalEnterParking').modal('hide');
                            table.draw(false);
                        }, 1500);
                    }
                },
                error: (data) => {
                    if (data.responseJSON.status == "failed" && data.responseJSON.response == "failed-validation") {
                        Swal.fire({
                            title: 'Warning!',
                            text: data.responseJSON.errors,
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        })
                    }

                    if (data.responseJSON.status == "failed" && data.responseJSON.response == "failed-code") {
                        Swal.fire({
                            title: 'Perhatian!',
                            text: data.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        });
                    }
                }
            });
        });

        $('#outParking').on('submit', function(event) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            event.preventDefault();

            Swal.fire({
                text : "Mohon menunggu..."
            });

            Swal.showLoading();

            $.ajax({
                url: $(this).attr("action"),
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: (data) => {

                    if (data.status == "ok" && data.response == "successfully-searched") {
                        Swal.close();

                        let dataReturn = data.data;
                        $("#outParking").trigger('reset');

                        $("#nopol_payment").val(dataReturn.nopol);
                        $("#code_park_payment").val(dataReturn.code_park);
                        $("#time_in_payment").val(dataReturn.timeIn);
                        $("#time_out_payment").val(dataReturn.timeOut);
                        $("#total_bayar_payment").val(dataReturn.totalForPayFormat);
                        $("#total_bayar_nonformat_payment").val(dataReturn.totalForPay);

                        $('#paymentmethod_id_payment').empty();
                        $.each(dataReturn.paymentMethods, function (key, value) {
                            $('#paymentmethod_id_payment').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });

                        $('#modalOutParking').modal('hide');
                        $('#modalOutParkingPayment').modal('show');


                    }
                },
                error: (data) => {
                    if (data.responseJSON.status == "failed" && data.responseJSON.response == "failed-validation") {
                        Swal.fire({
                            title: 'Warning!',
                            text: data.responseJSON.errors,
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        })
                    }

                    if (data.responseJSON.status == "failed" && data.responseJSON.response == "failed-code") {
                        Swal.fire({
                            title: 'Perhatian!',
                            text: data.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        });
                    }
                }
            });
        });

        $('#outParkingPayment').on('submit', function(event) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            event.preventDefault();

            Swal.fire({
                text : "Mohon menunggu..."
            });

            Swal.showLoading();

            $.ajax({
                url: $(this).attr("action"),
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: (data) => {

                    if (data.status == "ok" && data.response == "successfully-updated") {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: data.message,
                        });
                        setTimeout(function(){
                            $('#modalOutParking').modal('hide');
                            $('#modalOutParkingPayment').modal('hide');
                            table.draw(false);
                        }, 1500);
                    }
                },
                error: (data) => {
                    if (data.responseJSON.status == "failed" && data.responseJSON.response == "failed-validation") {
                        Swal.fire({
                            title: 'Warning!',
                            text: data.responseJSON.errors,
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        })
                    }

                    if (data.responseJSON.status == "failed" && data.responseJSON.response == "failed-code") {
                        Swal.fire({
                            title: 'Perhatian!',
                            text: data.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        });
                    }
                }
            });
        });

        $(document).on('click', '#download-excel', function(event) {
            console.log("On Click Donwload Excel ");
            event.preventDefault();
            console.log("On Click Donwload Excel Part 2");

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            Swal.fire({
                text : "Mohon menunggu..."
            });

            Swal.showLoading();

            let fromDate = $('#filter_from_date').val();
            let toDate = $('#filter_to_date').val();
            let status = $('#status_filter').val();

            $.ajax({
                url: "{{ route('admin.parking.export.excel') }}",
                method:"GET",
                data: {
                    fromDate,
                    toDate,
                    status
                },
                xhrFields:{
                    responseType: 'blob'
                },
                success: (data) => {

                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(data);
                    link.download = `Data Record Parking.xlsx`;
                    link.click();

                    Swal.close();

                },
                error: (data) => {
                    if (data.responseJSON.status == "failed" && data.responseJSON.response == "failed-validation") {
                        Swal.fire({
                            title: 'Warning!',
                            text: data.responseJSON.errors,
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        })
                    }

                    if (data.responseJSON.status == "failed" && data.responseJSON.response == "failed-code") {
                        Swal.fire({
                            title: 'Perhatian!',
                            text: data.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        });
                    }
                }
            });
        });

    </script>
@endsection
