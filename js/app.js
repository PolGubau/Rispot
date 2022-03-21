$(document).ready(function() {
    // https://phpmyadmin.alwaysdata.com/phpmyadmin/index.php?route=/&route=%2F&lang=en 
    // host: mysql-database-sc.alwaysdata.net 

    console.log('Jquery working Good');
    $('.stadistics').hide();
    $('#task_result').hide();
    let edit = false;


    const tema_color = 5;
    fetchTasks();

    // BUSCAR A LA LUPA
    $('#search').keyup(function(e) {

        if ($('#search').val() == '') {
            $('#task_result').hide();
            $('#list_written').html('');

        };
        if ($('#search').val()) {
            const search = $('#search').val();

            $.ajax({
                url: 'API.php?searchInDB',
                type: 'POST',
                data: { search },
                success: function(response) {
                    let tasks = JSON.parse(response);
                    let template = `
                    <tr class="row">
                    <td><b>ID</b></td>
                    <td><b>NUMBER</b></td>
                    <td><b>PRICE</b></td>
                    <td><b>COUNTRY</b></td>
                    <td><b>CP</b></td>
                    <td><b>DATEHOUUR</b></td>
                    <td><b>DATE</b></td>
                    <td><b>HOUR</b></td>
                    <td><b>HOUAPROX</b></td>
                    <td><b>MONTH</b></td>
                    <td><b>WEEKDAY</b></td>
                    </tr>`;
                    tasks.forEach(task => {
                        template += `
                        <tr class="row" task_id="${task.ID}">
                        <td class="searched"> ${task.ID}</td>
                        <td><a href='#' class="nom_entrada"> ${task.NUMBER}</a> </td>
                        <td class="searched"> ${task.PRICE}€</td>
                        <td class="searched"> ${task.COUNTRY}</td>
                        <td class="searched"> ${task.CP}</td>
                        <td class="searched"> ${task.DATEHOUR}</td>
                        <td class="searched"> ${task.DATE}</td>
                        <td class="searched"> ${task.HOUR}</td>
                        <td class="searched"> ${task.HOURAPROX}</td>
                        <td class="searched"> ${task.MONTH}</td>
                        <td class="searched"> ${task.WEEKDAY}</td>
                        </tr>`

                    })
                    $('#list_written').html(template);
                    $('#task_result').show();

                }

            });
            $('.searched:contains(' + search + ')').each(function() {
                $(this).css("background-color", "yellow");
            });

            console.log(this);



        }

    })

    //CREAR UNA NOVA ENTRADA O EDITAR UNA EXISTENT
    $('#task_form').submit(function(e) {
        const postData = {
            id: $('#task_Id').val(),
            numero: $('#numero').val(),
            precio: $('#precio').val(),
            pais: $('#pais').val(),
            CP: $('#CP').val(),
            fecha: $('#fecha').val(),
            Hora: $('#Hora').val(),
            Mes: $('#mes').val(),
            Dia: $('#dia').val()
        };

        let url = edit === false ? 'API.php?addToDB' : 'API.php?upload'


        $.post(url, postData, function(response) {
            fetchTasks();
            $('#task_form').trigger('reset');
        });
        edit ? console.log('Updated') : console.log('Added');
        edit = false;
        $('#submit_create').text('Guardar Registro');

        e.preventDefault();


    });

    //POSAR UN LIMIT
    $('#limit').keyup(function(e) {
        const limit = $('#limit').val();
        e.preventDefault;
        fetchTasks(limit)
    });

    //IMPRIMIR LES ENTRADES
    function fetchTasks(limit) {
        $.post('API.php?viewDB', { limit }, function(response) {
            let tasks = JSON.parse(response);
            let template = '';
            tasks.forEach(task => {
                template += `
                    <tr class="row" task_id="${task.ID}">
                    <td>${task.ID}</td>
                    <td><a href='#' class="nom_entrada"> ${task.NUMBER}</a> </td>
                    <td>${task.PRICE}€</td>
                    <td>${task.COUNTRY}</td>
                    <td>${task.CP}</td>
                    <td>${task.DATEHOUR}</td>
                    <td>${task.DATE}</td>
                    <td>${task.HOUR}</td>
                    <td>${task.HOURAPROX}</td>
                    <td>${task.MONTH}</td>
                    <td>${task.WEEKDAY}</td>
        
                    <td> <button class="button_delete">Delete</button></td>
                    </tr>`
            });
            $('#tasks').html(template);
        });
    }

    //IMPRIMIR LES ESTADISTIQUES
    function fetchStadistics() {

        $('#task_result').hide();
        $('#list_written').html('');

        $.post('stats.php', {}, function(response) {
            let graphs = JSON.parse(response);

            graphs.forEach(data => {

                $('#D_weekdays').text(data.TOTAL);
                $('#D_BrutTotal').text(data.TOTAL);
                $('#D_VentesTotals').text(data.CANTIDAD_VENTAS);
                const weekdays = data.WEEKDAYS;
                const months = data.MONTHS;
                const hours = data.HOURSAPROX;
                var templatehours = '';

                $('.Barres_weekday').html(
                    '<div class="barra_WV" style="background-color: rgb(119,' + weekdays.Lunes + ' ,' + weekdays.Lunes * tema_color + ');width: ' + weekdays.Lunes + '%"><p>' + weekdays.Lunes + `</p></div>
                    <div class="barra_WV" style="background-color: rgb(119,` + weekdays.Martes + ', ' + weekdays.Martes * tema_color + ');width: ' + weekdays.Martes + '%"><p>' + weekdays.Martes + `</p></div>
                    <div class="barra_WV" style="background-color: rgb(119,` + weekdays.Miércoles + ', ' + weekdays.Miércoles * tema_color + ');width: ' + weekdays.Miércoles + '%"><p>' + weekdays.Miércoles + `</p></div>
                    <div class="barra_WV" style="background-color: rgb(119,` + weekdays.Jueves + ', ' + weekdays.Jueves * tema_color + ');width: ' + weekdays.Jueves + '%"><p>' + weekdays.Jueves + `</p></div>
                    <div class="barra_WV" style="background-color: rgb(119,` + weekdays.Viernes + ', ' + weekdays.Viernes * tema_color + ');width: ' + weekdays.Viernes + '%"><p>' + weekdays.Viernes + `</p></div>
                    <div class="barra_WV" style="background-color: rgb(119,` + weekdays.Sábado + ', ' + weekdays.Sábado * tema_color + ');width: ' + weekdays.Sábado + '%"><p>' + weekdays.Sábado + `</p></div>
                    <div class="barra_WV" style="background-color: rgb(119,` + weekdays.Domingo + ', ' + weekdays.Domingo * tema_color + ');width: ' + weekdays.Domingo + '%"><p>' + weekdays.Domingo + '</p></div>'
                );
                $('.Barres_month').html(
                    `<div class="barra_WV" style="background-color: rgb(119,` + months.Enero + ' ,' + months.Enero * tema_color + ');width: ' + months.Enero + '%"><p>' + months.Enero + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Febrero + ' ,' + months.Febrero * tema_color + ');width: ' + months.Febrero + '%"><p>' + months.Febrero + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Marzo + ' ,' + months.Marzo * tema_color + ');width: ' + months.Marzo + '%"><p>' + months.Marzo + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Abril + ' ,' + months.Abril * tema_color + ');width: ' + months.Abril + '%"><p>' + months.Abril + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Mayo + ' ,' + months.Mayo * tema_color + ');width: ' + months.Mayo + '%"><p>' + months.Mayo + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Junio + ' ,' + months.Junio * tema_color + ');width: ' + months.Junio + '%"><p>' + months.Junio + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Julio + ' ,' + months.Julio * tema_color + ');width: ' + months.Julio + '%"><p>' + months.Julio + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Agosto + ' ,' + months.Agosto * tema_color + ');width: ' + months.Agosto + '%"><p>' + months.Agosto + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Septiembre + ' ,' + months.Septiembre * tema_color + ');width: ' + months.Septiembre + '%"><p>' + months.Septiembre + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Octubre + ' ,' + months.Octubre * tema_color + ');width: ' + months.Octubre + '%"><p>' + months.Octubre + `</p></div>
                    
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Noviembre + ' ,' + months.Noviembre * tema_color + ');width: ' + months.Noviembre + '%"><p>' + months.Noviembre + `</p></div>
                    
                   
                    <div class="barra_WV" style="background-color: rgb(119,` + months.Diciembre + ' ,' + months.Diciembre * tema_color + ');width: ' + months.Diciembre + '%"><p>' + months.Diciembre + `</p></div>`
                );

                for (hora in hours) {
                    templatehours += '<div class="hourbox"><p>' + hora + '</p><p style="background-color: rgb(190, 138, ' + hours[hora] * 3.5 * tema_color + ')">' + hours[hora] + '</p></div>';
                }
                $('.Barres_hours').html('<div class="allhours">' + templatehours + '</div');


            });

            fetchTasks()


        });
    }

    // ESBORRAR ENTRADES
    $(document).on('click', '.button_delete', function() {
        if (confirm('Are you sure you want to delete it?')) {
            let element = $(this)[0].parentElement.parentElement;
            let id = $(element).attr('task_id');
            $.post('API.php?deleteFromDB', { id }, function(response) {
                fetchTasks();
            });
        }
    });

    //EDITAR ENTRADES
    $(document).on('click', '.nom_entrada', function() {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('task_id');

        $.post('API.php?valorize', { id }, function(response) {
            const row = JSON.parse(response);
            console.log(row);
            $('#numero').val(row[0].numero);
            $('#precio').val(row[0].precio);
            $('#pais').val(row[0].pais);
            $('#CP').val(row[0].CP);
            $('#data').val(row[0].data);
            $('#fecha').val(row[0].fecha);
            $('#Hora').val(row[0].Hora);
            $('#Horaaprox').val(row[0].Horaaprox);
            $('#mes').val(row[0].mes);
            $('#dia').val(row[0].dia);
            edit = true;
            $('#submit_create').text('Actualitzar');
        });
    });

    // CANVIAR MÈTODES
    $(document).on('click', '#verdatos', function() {
        $('#limit').show();
        $('.table_tasks').show();
        $('.stadistics').hide();


        fetchTasks();

    });
    $(document).on('click', '#verestadisticas', function() {
        $('#limit').hide();
        $('.table_tasks').hide();
        $('.stadistics').show();
        fetchTasks(0);
        fetchStadistics();

    });

});