<!DOCTYPE html>

<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>






    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-5">Laravel Calendar CRUD Events Example</h2>
        <div id='fullCalendar'>

        </div>
        <div class="modal fade" id="updateEvent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    @csrf
                    <div class="modal-body">
                        <ul id="updateEvent"></ul>

                        <input type="hidden" id="id">

                        <div class="form-group">
                            <label for="Event name">Event Name</label>
                            <input type="text" class="event_name form-control" id="title" name="event_name">
                        </div>
                        <div class="form-group">
                            <label for="rows">Start Date</label>
                            <input type="datetime-local" class="form-control" id="start" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="columns">End Date</label>
                            <input type="datetime-local" class="form-control" id="end" name="end_date">
                        </div>
                        <!-- </div> -->
                        <!-- <div class="modal-footer"> -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary updateEvent" id="updateEvent">Update changes</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadCalander();
        });

        function loadCalander() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var calendar = $('#fullCalendar').fullCalendar({
                header: {
                    'left': 'prev,next today',
                    'center': 'title',
                    'right': 'list,month,basicWeek,basicDay',
                },
                editable: true,
                events: "{{route('event.show')}}",
                displayEventTime: true,
                resizable: true,
                eventRender: function(event, element, view) {
                    if (event.allDay === true) {
                        event.allDay = true;
                        alert(event.textColor);
                    } else {
                        event.allDay = false;
                    }

                },
                selectable: true,
                selectHelper: true,

                eventDrop: function(event, jsEvent, view) {
                    $('#updateEvent').modal('show');
                    var id = event.id;
                    console.log(id);
                    $.ajax({
                        type: 'GET',
                        url: '/editEvent/' + id,
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.status == 404) {
                                $('#success_message').html("");
                                $('#success_message').addClass('alert alert-danger');
                                $('#success_message').text(response.message);
                            } else {
                                $('#title').val(response.editEvent.title);
                                $('#start').val(response.editEvent.start);
                                $('#end').val(response.editEvent.end);
                                $('#id').val(id);
                            }
                        }
                    });
                },
                eventClick: function(event) {
                    var deleteMsg = confirm("Do you really want to delete this event?");
                    var id = event.id;

                    if (deleteMsg) {
                        $.ajax({
                            type: "delete",
                            url: "{{route('event.destroy','')}}" + '/' + id,
                            dataType: 'json',

                            success: function(response) {
                                calendar.fullCalendar('removeEvents', event.id);
                                displayMessage("Event successfully deleted!");
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                },
            });

        }
        $(document).on('click', '.updateEvent', function(e) {
            e.preventDefault(e);

            var event_id = $('#id').val();
            var data = {
                'title': $('#title').val(),
                'start': $('#start').val(),
                'end': $('#end').val(),
                'id': $('#id').val(),
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'patch',
                url: 'updateEvent/' + event_id,
                data: data,
                dataType: 'json',
                success: function(response) {
                    $('#updateEvent').modal('hide');
                    fetchUpdateCalander();
                }
            });
        });

        function fetchUpdateCalander() {
            var title = $("#title").val();
            console.log(title);
            var start = $("#start").val();
            console.log(start);
            var end = $("#end").val();
            console.log(end);
            var id = $('#id').val();
            $.ajax({
                type: 'GET',
                url: '/fullcalender',
                dataType: 'json',
                success: function(response) {
                    // loadCalander();
                }
            });
        }


        function displayMessage(message) {

            toastr.success(message, 'Event');

        }
    </script>
</body>

</html>