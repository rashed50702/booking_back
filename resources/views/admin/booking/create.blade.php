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
                <form id="form">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h3 class="card-title">Bookings</h3>
                            <div class="card-tools">
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="start_time">Start Time:</label>
                                        <div class="input-group date" id="start_time" data-target-input="nearest">
                                            <input type="text" name="start_time" class="form-control datetimepicker-input" data-target="#start_time" />
                                            <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="end_time">End Time:</label>
                                        <div class="input-group date" id="end_time" data-target-input="nearest">
                                            <input type="text" name="end_time" class="form-control datetimepicker-input" data-target="#end_time" />
                                            <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="availability-check">&nbsp;</label>
                                        <button class="btn btn-sm btn-primary form-control" id="check" type="submit"><i class="fa fa-search"></i> Check Availability</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="book_for">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="user">Book For</label>
                                        <select class="form-control select2" id="user" name="user" style="width: 100%;">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table id="availabilityTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Room</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Availability data will be inserted here -->
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                        </div>
                        <!-- /.card-footer -->
                    </div>
                </form>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!--/. container-fluid -->
</section>
<!-- /.content -->

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


            // Initialize select2
            $('#meeting_room').select2();
            $('#user').select2();
            $('#start_time, #end_time').datetimepicker({
                autoclose: true,
                todayHighlight: true,
                icons: {
                    time: 'far fa-clock',
                },
            });


        });


        // check availability
        let usersLoaded = false;
        $('#check').click(function(e) {
            e.preventDefault();
            let data = {
                start_time: $('input[name="start_time"]').val(),
                end_time: $('input[name="end_time"]').val(),
            };
            if (!validateDateTime(data.start_time, data.end_time)) {
                return false;
            }

            let checkBtn = $(this); // Store the button element
            checkBtn.html('<i class="fas fa-spinner fa-spin"></i> Checking...'); // Set the button text to 'Checking..'

            handleAjaxRequest(
                "{{ route('bookings-check') }}",
                'POST',
                data,
                '',
                function(response) {
                    // console.log(response);
                    checkBtn.html('<i class="fa fa-search"></i> Search');
                    $('#availabilityTable tbody').empty();
                    $('#availabilityTable').show();

                    response.forEach(function(row) {
                        $('#availabilityTable tbody').append(
                            '<tr>' +
                            '<td>' + row.room_id + '</td>' +
                            '<td>' + row.room_name + '</td>' +
                            '<td>' + moment(row.start_time).format('MM/DD/YY h:mm A') + '</td>' +
                            '<td>' + moment(row.end_time).format('MM/DD/YY h:mm A') + '</td>' +
                            '<td>' + (row.is_booked === false ? '<button class="btn btn-sm btn-secondary book-button" type="button">Book Now</button>' : '<button class="btn btn-sm btn-secondary book-button" type="button" disabled>Booked</button>') + '</td>' +
                            '</tr>'
                        );
                    });
                    $('#book_for').show();
                    if (!usersLoaded) {
                        loadUsers();
                        usersLoaded = true; // Set the flag to true after loading users
                    }

                },
                function() {
                    checkBtn.html('<i class="fa fa-search"></i> Search'); // Reset the button text on error
                }
            );

        });


        // Validation for start time and end time
        function validateDateTime(start_time, end_time) {
            if (start_time === '' || !isValidDate(start_time)) {
                alert("Start time should be a valid date");
                return false;
            }

            if (end_time === '' || !isValidDate(end_time)) {
                alert("End time should be a valid date!");
                return false;
            }

            if (new Date(start_time) < new Date() || new Date(end_time) < new Date()) {
                alert("Selected date must not be older than today.");
                return false;
            }

            if (new Date(start_time) > new Date(end_time)) {
                alert("Your date is older than start date.");
                return false;
            }

            let timediff = Math.floor(((new Date(end_time) - new Date(start_time)) / (1000 * 60)));

            if (timediff <= 30) {
                alert("Time difference should be at least 30 minutes!");
                return false;
            }
            return true;
        }

        function isValidDate(dateString) {
            let dateObject = new Date(dateString);
            return dateObject instanceof Date && !isNaN(dateObject);
        }
        // End of validation for start time and end time

        // Load Users
        function loadUsers() {
            $.ajax({
                url: "{{route('users')}}",
                method: 'GET',
                success: function(data) {
                    // Assuming the data is an array of options in the format { id, text }
                    // Loop through the data and add options to the select2 input
                    data.forEach(function(option) {
                        // Create a new Option element
                        var newOption = new Option(option.name, option.id, false, false);

                        // Append the new option and trigger change event
                        $('#user').append(newOption).trigger('change');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // book now
        $(document).on('click', '.book-button', function(e) {
            e.preventDefault();

            if ($("#user").val() == '') {
                alert("Please select Booking for!!");
                return false;
            }

            if (!confirm("Are You sure want to book this!")) {
                return false;
            }

            let button = $(this);
            let room_id = button.closest('tr').find('td:nth-child(1)').text(); // Assuming room_id is in the first column
            let room = button.closest('tr').find('td:nth-child(2)').text(); // Assuming room_id is in the first column
            let start_time = button.closest('tr').find('td:nth-child(3)').text(); // Assuming start_time is in the second column
            let end_time = button.closest('tr').find('td:nth-child(4)').text(); // Assuming end_time is in the third column

            let user_id = $("#user").val();

            let data = {
                user_id: user_id,
                room_id: room_id,
                start_time: start_time,
                end_time: end_time,
            };

            handleAjaxRequest(
                "{{ route('bookings.store') }}",
                'POST',
                data,
                'Room booked successfully',
                function(response) {
                    // Disable the button
                    button.prop('disabled', true);
                    // Change button text to "Booked"
                    button.text('Booked');
                },
                function() {}
            );
        });
    });
</script>
@stop