@extends('admin.layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Room Bookings</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Room Bookings</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h3 class="card-title">Bookings</h3>
                        <div class="card-tools">
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-hover data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Booked For</th>
                                    <th>Room Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer text-right">
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

<!-- modal start-->
<div class="modal fade" id="ajaxModelexa" aria-hidden="true">
    <div class="modal-dialog">
        <form name="modal-form" id="modal-form">
            <input type="hidden" name="id" id="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="status_group">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="pending_status" name="status" value="Pending">
                            <label for="pending_status" class="custom-control-label">Pending</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="approve_status" name="status" value="Approved">
                            <label for="approve_status" class="custom-control-label text-success">Approve</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="cancel_status" name="status" value="Canceled">
                            <label for="cancel_status" class="custom-control-label text-danger">Cancel</label>
                        </div>

                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success" id="savedata" value="Update">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- / . modal end -->
@stop

@section('footer-scripts')
<script src="{{ asset('js/ajaxUtils.js') }}"></script>
<script>
    $(document).ready(function() {
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('bookings.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'room.name',
                        name: 'room.name'
                    },
                    {
                        data: 'start_time',
                        name: 'start_time'
                    },
                    {
                        data: 'end_time',
                        name: 'end_time'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });


            // get single item to edit
            $('body').on('click', '.edit-item', function() {
                var id = $(this).data('id');
                $.get("{{ route('bookings.index') }}" + '/' + id + '/edit', function(data) {
                    $('#modelHeading').html("Change Booking Room Status");
                    $('#savedata').html("Update");
                    $('#ajaxModelexa').modal('show');
                    $('#id').val(data.id);
                    $('input[name="status"]').attr('checked', true)
                    $('#status_group').find(':radio[name=status][value=' + data.status +
                        ']').prop('checked', true);
                    console.log(data.status);
                })
            });

            // updating
            $('#savedata').click(function(e) {
                e.preventDefault();
                let saveButton = $(this); // Store the button element
                saveButton.html('Sending..'); // Set the button text to 'Sending..'

                let id = $('#id').val();

                handleAjaxRequest(
                    "{{ route('bookings.index') }}" + '/' + id,
                    'PUT',
                    $('#modal-form').serialize(),
                    'Booking status updated successfully',
                    function() {
                        $('#modal-form').trigger("reset");
                        $('#ajaxModelexa').modal('hide');
                        table.draw();
                        saveButton.html('Update'); // Reset the button text after success

                    },
                    function() {
                        saveButton.html('Update'); // Reset the button text on error
                    }
                );
            });

            // deleting item
            $('body').on('click', '.delete-item', function() {
                var id = $(this).data("id");
                if (!confirm("Are You sure want to delete this!")) {
                    return false;
                }
                handleAjaxRequest(
                    "{{ route('bookings.index') }}" + '/' + id,
                    'DELETE',
                    $('#modal-form').serialize(),
                    'Booking deleted successfully',
                    // success function
                    function() {
                        table.draw();
                    }
                );

            });

        });
    });
</script>
@stop