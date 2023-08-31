@extends('admin.layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Rooms</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Rooms</li>
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
                        <h3 class="card-title">Rooms</h3>
                        <div class="card-tools">
                            <button class="btn btn-xs btn-primary" id="create-modal"><i class="fas fa-plus-square"></i> Create New</button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-hover data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Room Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
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
                    <div class="form-group">
                        <label class="col-form-label" for="name">Room Name</label>
                        <input type="text" id="name" name="name" autocomplete="off" class="form-control" placeholder="Enter ...">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success" id="savedata">Submit</button>
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
                ajax: "{{ route('rooms.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#create-modal').click(function() {
                $('#savedata').val("Save");
                $('#id').val('');
                $('#modal-form').trigger("reset");
                $('#modelHeading').html("Create New Room");
                $('#ajaxModelexa').modal('show');
            });

            // get single item to edit
            $('body').on('click', '.edit-item', function() {
                var id = $(this).data('id');
                $.get("{{ route('rooms.index') }}" + '/' + id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Room");
                    $('#savedata').val("Update");
                    $('#savedata').html("Update");
                    $('#ajaxModelexa').modal('show');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                })
            });

            // saving and updating
            $('#savedata').click(function(e) {
                e.preventDefault();
                let saveButton = $(this); // Store the button element
                saveButton.html('Sending..'); // Set the button text to 'Sending..'

                if ($(this).val() == "Save") {
                    // Insert new record
                    handleAjaxRequest(
                        "{{ route('rooms.store') }}",
                        'POST',
                        $('#modal-form').serialize(),
                        'Room created successfully',
                        function() {
                            $('#modal-form').trigger("reset");
                            $('#ajaxModelexa').modal('hide');
                            table.draw();
                            saveButton.html('Submit'); // Reset the button text after success
                        },
                        function() {
                            saveButton.html('Submit'); // Reset the button text on error
                        }
                    );

                } else if ($(this).val() == "Update") {
                    // Update existing record
                    let id = $('#id').val();
                    handleAjaxRequest(
                        "{{ route('rooms.index') }}" + '/' + id,
                        'PUT',
                        $('#modal-form').serialize(),
                        'Room updated successfully',
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
                }
            });

            // deleting item
            $('body').on('click', '.delete-item', function() {
                var id = $(this).data("id");
                if (!confirm("Are You sure want to delete this! You will lost related data.")) {
                    return false;
                }
                handleAjaxRequest(
                    "{{ route('rooms.index') }}" + '/' + id,
                    'DELETE',
                    $('#modal-form').serialize(),
                    'Room deleted successfully',
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