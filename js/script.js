$(document).ready(function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'sr-Latn',
        height: 'auto',
        contentHeight: 'auto',
        aspectRatio: 1.5,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },
        dateClick: function (info) {
            $('#reservationModal').modal('show');
            $('#dateFrom').val(info.dateStr);
            $('#dateTo').val(info.dateStr);
        },
        events: function (fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: 'sacuvaj_rezervaciju.php',
                method: 'GET',
                success: function (response) {
                    try {
                        var responseData = JSON.parse(response);
                        successCallback(responseData);
                    } catch (e) {
                        alert('Greška prilikom učitavanja rezervacija.');
                    }
                },
                error: function () {
                    alert('Došlo je do greške prilikom učitavanja rezervacija.');
                },
            });
        },
        
        eventClick: function (info) {
            var event = info.event;

            $('#detaljiModal').modal('show');
            $('#detaljiIme').val(event.title);
            $('#detaljiOpis').val(event.extendedProps.description);
            $('#detaljiOd').val(event.start.toISOString().slice(0, 10));
            $('#detaljiDo').val(
                event.end
                    ? event.end.toISOString().slice(0, 10)
                    : event.start.toISOString().slice(0, 10)
            );

            $('#deleteReservationBtn').data('eventId', event.id);
        },
    });

    calendar.render();

    $('#saveReservation').on('click', function () {
        var name = $('#name').val();
        var description = $('#description').val();
        var dateFrom = $('#dateFrom').val();
        var dateTo = $('#dateTo').val();

        if (name && description && dateFrom && dateTo) {
            $.ajax({
                url: 'sacuvaj_rezervaciju.php',
                method: 'POST',
                data: {
                    name: name,
                    description: description,
                    dateFrom: dateFrom,
                    dateTo: dateTo,
                },
                success: function (response) {
                    try {
                        var responseData = JSON.parse(response);

                        if (responseData.success) {
                            calendar.addEvent({
                                id: responseData.id,
                                title: name,
                                description: description,
                                start: dateFrom,
                                end: dateTo,
                                color: '#ffb3b3',
                            });

                            $('#reservationModal').modal('hide');
                            $('#reservationForm')[0].reset();
                            alert('Rezervacija je uspešno dodata!');
                        } else {
                            alert(
                                'Došlo je do greške prilikom čuvanja: ' +
                                    responseData.message
                            );
                        }
                    } catch (e) {
                        alert('Greška prilikom obrade servera.');
                    }
                },
                error: function () {
                    alert('Došlo je do greške prilikom obrade.');
                },
            });
        } else {
            alert('Sva polja moraju biti popunjena!');
        }
    });

    $('#deleteReservationBtn').on('click', function () {
        var eventId = $(this).data('eventId');

        if (confirm('Da li ste sigurni da želite da obrišete ovu rezervaciju?')) {
            $.ajax({
                url: 'obrisi_rezervaciju.php',
                method: 'POST',
                data: { id: eventId },
                success: function (response) {
                    try {
                        var responseData = JSON.parse(response);
                        if (responseData.success) {
                            var event = calendar.getEventById(eventId);
                            if (event) {
                                event.remove();
                            }

                            $('#detaljiModal').modal('hide');
                            alert('Rezervacija je uspešno obrisana!');
                        } else {
                            alert(
                                'Došlo je do greške prilikom brisanja: ' +
                                    responseData.message
                            );
                        }
                    } catch (e) {
                        alert('Greška prilikom brisanja rezervacije.');
                    }
                },
                error: function () {
                    alert('Došlo je do greške prilikom brisanja.');
                },
            });
        }
    });
});
