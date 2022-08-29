<div class="modal fade text-left" id="modalEnterParking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Enter New Parking</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="enterParking" method="POST" action="{{ route( Auth::user()->roles->pluck('name')[0] . '.parking.enter') }}">
                    <div class="form-group">
                        <label for="nopol">Nomor Polisi Kendaraan
                            <span style="color: red">*</span>
                        </label>
                        <input type="text" class="form-control" name="nopol" id="nopol" placeholder="Nomor Polisi Kendaraan"/>
                    </div>
                    <button type="reset" class="btn btn-danger" style="float: left" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-success" style="float: right">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modalOutParking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Out Parking</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger mt-1 alert-validation-msg" role="alert">
                    <div class="alert-body">
                        <i data-feather="info" class="mr-50 align-middle"></i>
                        <strong>Atenttion!</strong> Insert Nomor Polisi Kendaran (Nopol) or Code Parking!.
                    </div>
                </div>
                <form id="outParking" method="POST" action="{{ route( Auth::user()->roles->pluck('name')[0] . '.parking.out') }}">
                    <div class="form-group">
                        <label for="nopol_edit">Nomor Polisi Kendaraan
                        </label>
                        <input type="text" class="form-control" name="nopol" id="nopol_edit" placeholder="Nomor Polisi Kendaraan" />
                    </div>
                    <div class="form-group">
                        <label for="code_park_edit">Code Parking
                        </label>
                        <input type="text" class="form-control" name="code_park" id="code_park_edit" placeholder="Code Parking" />
                    </div>
                    <button type="reset" class="btn btn-danger" style="float: left" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-success" style="float: right">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="modalOutParkingPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Out Parking</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="outParkingPayment" method="POST" action="{{ route( Auth::user()->roles->pluck('name')[0] . '.parking.payment') }}">
                    <div class="form-group">
                        <label for="nopol_payment">Nomor Polisi Kendaraan
                        </label>
                        <input type="text" class="form-control" name="nopol" id="nopol_payment" placeholder="Nomor Polisi Kendaraan" readonly />
                    </div>
                    <div class="form-group">
                        <label for="code_park_payment">Code Parking
                        </label>
                        <input type="text" class="form-control" name="code_park" id="code_park_payment" placeholder="Code Parking" readonly/>
                    </div>
                    <div class="form-group">
                        <label for="time_in_payment">Time In
                        </label>
                        <input type="text" class="form-control" name="time_in" id="time_in_payment" placeholder="Time In" readonly/>
                    </div>
                    <div class="form-group">
                        <label for="time_out_payment">Time Out
                        </label>
                        <input type="text" class="form-control" name="time_out" id="time_out_payment" placeholder="Time Out" readonly/>
                    </div>
                    <div class="form-group">
                        <label for="total_bayar_payment">Total Payment
                        </label>
                        <input type="text" class="form-control" name="total_bayar" id="total_bayar_payment" placeholder="Total Bayar Parkir" readonly />
                    </div>
                    <div class="form-group">
                        <label for="total_bayar_nonformat_payment">Total Payment
                        </label>
                        <input type="text" class="form-control" name="total_bayar_nonformat" id="total_bayar_nonformat_payment" placeholder="Total Bayar Parkir" hidden />
                    </div>
                    <div class="form-group">
                        <label for="paymentmethod_id_payment">Payment Method
                        </label>
                        <select class="form-control" name="paymentmethod_id" id="paymentmethod_id_payment">
                        </select>
                    </div>
                    <button type="reset" class="btn btn-danger" style="float: left" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-success" style="float: right">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
