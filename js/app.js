document.write('<script src="./js/functions.js"><\/script>');

$(document).ready(function () {
    // https://phpmyadmin.alwaysdata.com/phpmyadmin/index.php?route=/&route=%2F&lang=en 
    // host: mysql-database-sc.alwaysdata.net 

    // console.log('Jquery working Good');

    // STARTING BASES
    $('.stadistics').hide();
    $('#task_result').hide();
    let edit = false;
    var table = 'pedidos';
    var table2 = '';
    var order = 'ID';
    var direction = 'DESC';
    var limit = '10';



    // functions
    function searchScreen(search) {
        $('td.searched:contains(' + search + ')').each(function () {
            $(this).addClass("finded");
        });
    };


    const tema_color = 3;
    fetchTasks(limit, order, direction, table, table2);

    // Change DBs
    $('.witch_db_general').click(function () {
        table = 'pedidos';
        table2 = '';

        fetchTasks(limit, order, direction, table, table2);
    });
    $('.witch_db_backup').click(function () {
        table = 'backup';
        table2 = '';

        fetchTasks(limit, order, direction, table, table2);
    });
    $('.witch_db_edited').click(function () {
        table = 'updated';
        table2 = '';

        fetchTasks(limit, order, direction, table, table2);
    });
    $('.witch_db_all').click(function () {
        table = 'pedidos';
        table2 = 'backup';
        fetchTasks(limit, order, direction, table, table2);
    });

    // Buscar lupa
    $('#search').keyup(function (e, table) {

        if ($('#search').val() == '') {
            $('#task_result').hide();
            $('#list_written').html('');

        };
        if ($('#search').val()) {
            const search = $('#search').val();
            if (search.includes("€")) { alert('Recorda buscar els preus sense el €') }
            if (search.includes("Pol")) { alert(':)') }
            $.ajax({
                url: 'API.php?searchInDB',
                type: 'POST',
                data: { search, table },
                success: function (response) {

                    let tasks = JSON.parse(response);

                    let template = `
                    <tr class="row">
                    <td class="header_row"><b >ID</b></td>
                    <td class="header_row"><b >NUMBER</b></td>
                    <td class="header_row"><b >PRICE</b></td>
                    <td class="header_row"><b >COUNTRY</b></td>
                    <td class="header_row"><b >CP</b></td>
                    <td class="header_row"><b >DATE</b></td>
                    <td class="header_row"><b >HOUR</b></td>
                    <td class="header_row"><b >APROX</b></td>
                    <td class="header_row"><b >MONTH</b></td>
                    <td class="header_row"><b >WEEKDAY</b></td>
                    <td class="header_row"><b >ASIN</b></td>
                    <td class="header_row"><b >Added</b></td>
                    </tr>`;
                    tasks.forEach(task => {
                        template += `
                        <tr class="row searched" task_id="${task.ID}">
                        <td class="searched"> ${task.ID}</td>
                        <td><a href='#' class="searched nom_entrada"> ${task.NUMBER}</a> </td>
                        <td class="searched"> ${task.PRICE}€</td>
                        <td class="searched"> ${task.COUNTRY}</td>
                        <td class="searched"> ${task.CP}</td>
                        <td class="searched"> ${task.DATE}</td>
                        <td class="searched"> ${task.HOUR}</td>
                        <td class="searched"> ${task.HOURAPROX}</td>
                        <td class="searched"> ${task.MONTH}</td>
                        <td class="searched"> ${task.WEEKDAY}</td>
                        <td class="searched"> ${task.ASIN}</td>
                        <td class="searched"> ${task.USER} (${task.ADDED})</td>
                        </tr>`

                    })
                    $('#list_written').html(template);
                    $('#task_result').show();
                    searchScreen(search);
                    // $('td.searched:contains(' + search + ')').each(function () {
                    //     $(this).addClass("finded");
                    // });

                }

            });
        }

    })

    //CREAR UNA NOVA ENTRADA O EDITAR UNA EXISTENT
    $('#task_form').submit(function (e) {

        const postData = {
            'ID': $('#task_ID').val(),
            'NUMBER': $('#numero').val(),
            'PRICE': $('#precio').val(),
            'COUNTRY': $('#pais').val(),
            'CP': $('#CP').val(),
            'DATE': $('#fecha').val(),
            'HOUR': $('#Hora').val(),
            'MONTH': $('#mes').val(),
            'WEEKDAY': $('#dia').val(),
            'ASIN': $('#asin').val()
        };


        let url = edit === false ? 'API.php?addToDB' : 'API.php?upload'


        $.post(url, postData, function (r) {
            alert(r)
            fetchTasks(limit, order, direction, table, table2);
            $('#task_form').trigger('reset');
        });
        edit ? console.log('Updated') : console.log('Added');
        edit = false;
        $('#submit_create').text('Guardar Registro');

        e.preventDefault();


    });

    //POSAR UN LIMIT
    $('#limit').keyup(function (e) {
        const limit = $('#limit').val();
        fetchTasks(limit, order, direction, table, table2);
    });

    // Posar order y direcció
    $(document).on('click', '.header_row', function (e) {
        $('.header_row').removeClass("theader_active");
        $(e.target.tagName).not(this).removeClass("theader_active");
        $(this).addClass("theader_active");

        if (direction == 'DESC') { direction = 'ASC' } else { direction = 'DESC' }
        order = this.getAttribute('campo');
        fetchTasks(limit, order, direction, table, table2);
        e.preventDefault;
    });


    //!IMPRIMIR LES ENTRADES
    function fetchTasks(limit, order, direction, table, table2) {
        // console.log('Refreshed');
        url = 'API.php?viewDB';
        // console.log('1: ' + table + '. 2: ' + table2)
        $.post(url, { table, limit, order, direction, table2 }, function (response) {
            if (response == '') {
                window.location = './login.php';
            }
            // console.log('Resposta: '+response);
            let tasks = JSON.parse(response);
            let template = '';
            if (table == 'backup') {
                template += `
                <div class="truncate_box"> <button class="truncate_button">Delete all</button></div>
                `;
            }
            tasks.forEach(task => {
                template += `
                    <tr class="row" task_id="${task.ID}">
                    <td>${task.ID}</td>
                    <td><a href='#' class="nom_entrada"> ${task.NUMBER}</a> </td>
                    <td>${task.PRICE}€</td>
                    <td>${task.COUNTRY}</td>
                    <td>${task.CP}</td>
                    <td>${task.DATE}</td>
                    <td>${task.HOUR}</td>
                    <td>${task.MONTH}</td>
                    <td>${task.WEEKDAY}</td>
                    <td>${task.ASIN}</td>
                    <td>${task.USER} (${task.ADDED})</td>
        
                    <td> <button class="button_delete">Delete</button></td>`;
                if (table == 'backup') {
                    template += `

                    <td> <button class="button_backup">Backup</button></td>
                    `;
                }
                template += '</tr>';
            });
            $('#tasks').html(template);
        });
    }


    // ESBORRAR ENTRADES
    $(document).on('click', '.button_delete', function () {
        let advice = table === 'backup' ? 'Are you sure you want to delete it?' : 'This action will delete this row forever (a lot of time)';
        if (confirm(advice)) {
            let element = $(this)[0].parentElement.parentElement;
            let id = $(element).attr('task_id');


            console.log('Deleted ' + id + '. From ' + table);
            $.post('API.php?deleteFromDB', { 'id': id, 'table': table }, function (response) {
                console.log(response)
                fetchTasks(limit, order, direction, table, table2);
            });
        }
    });

    // Fer backup de les entrades
    $(document).on('click', '.truncate_button', function () {
        let alert = confirm('Are you sure? This will delete Backup table.');
        if (alert) {
            $.post('API.php?truncateBackup', {}, function (response) {
                console.log('Truncated Backup File');
                table = 'backup';
                fetchTasks(limit, order, direction, table, table2);

            });
        };

    });
    $(document).on('click', '.button_backup', function () {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('task_id');
        $.post('API.php?BackupRow', { 'id': id }, function (response) {
            console.log('Did a Backup ' + response);
            table = 'pedidos';
            fetchTasks(limit, order, direction, table, table2);
        });

    });

    //EDITAR ENTRADES
    $(document).on('click', '.nom_entrada', function () {
        // Agafem el seu ID
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('task_id');

        // Passem a la api el seu ID i esperem la resposta.
        $.post('API.php?valorize', { id }, function (response) {
            const row = JSON.parse(response);
            // Agafem els camps de la BD i els actualitzem pels nous.
            $('#task_ID').val(row[0].ID);
            $('#numero').val(row[0].NUMBER);
            $('#precio').val(row[0].PRICE);
            $('#pais').val(row[0].COUNTRY);
            $('#CP').val(row[0].CP);
            $('#fecha').val(row[0].DATE);
            $('#Hora').val(row[0].HOUR);
            $('#Horaaprox').val(row[0].HOURAPROX);
            $('#mes').val(row[0].MONTH);
            $('#dia').val(row[0].WEEKDAY);
            $('#asin').val(row[0].ASIN);
            edit = true;
            $('#submit_create').text('Actualitzar');
        });
    });

    // Reload
    $('.reloadSvg').click(function () {
        console.clear();
        $('.header_row').removeClass("theader_active");
        var $elem = $('.reloadSvg');
        var angle = 180;
        // we use a pseudo object for the animation
        // (starts from `0` to `angle`), you can name it as you want
        $({ deg: 0 }).animate({ deg: angle }, {
            duration: 200,
            step: function (now) {
                // in the step-callback (that is fired each step of the animation),
                // you can use the `now` paramter which contains the current
                // animation-position (`0` up to `angle`)
                $elem.css({
                    transform: 'rotate(' + now + 'deg)'
                });
            }
        });
        fetchTasks(limit, order, direction, table, table2);
    });


    //IMPRIMIR LES ESTADISTIQUES
    function fetchStadistics() {

        $('#task_result').hide();
        $('#list_written').html('');

        $.post('stats.php', {}, function (response) {
            let graphs = JSON.parse(response);
            // console.log(graphs)

            graphs.forEach(data => {

                $('#D_weekdays').text(data.TOTAL);
                $('#D_BrutTotal').text(data.TOTAL);
                $('#D_VentesTotals').text(data.CANTIDAD_VENTAS);

                const weekdays = data.WEEKDAYS;
                const months = data.MONTHS;
                const asins = data.ASIN;



                // MONTHS
                var myChart = echarts.init(document.getElementById('grafWeek'));

                var option = {
                    title: {
                        text: ''
                    },
                    tooltip: {},
                    legend: {
                        data: ['Ventes']
                    },
                    xAxis: {
                        data: Object.keys(weekdays)
                    },
                    yAxis: {},
                    series: [
                        {
                            name: 'Ventes',
                            type: 'line',
                            smooth: 'false',
                            data: Object.values(weekdays)
                        }
                    ]
                };

                myChart.setOption(option);

                var myChart = echarts.init(document.getElementById('grafMonths'));

                var option = {
                    title: {
                        text: ''
                    },
                    tooltip: {},
                    legend: {
                        data: ['Ventes', 'Clients']
                    },
                    xAxis: {
                        data: Object.keys(months)
                    },
                    yAxis: {},
                    series: [
                        {
                            name: 'Ventes',
                            type: 'bar',
                            smooth: 'true',
                            data: Object.values(months)
                        }
                    ]
                };

                myChart.setOption(option);
                // 
                // HORES
                // 
                const hours = data.HOURSAPROX;


                var myChart = echarts.init(document.getElementById('grafHours'));

                // console.log(hours)
                var option = {
                    title: {
                        text: ''
                    },
                    tooltip: {},
                    legend: {
                        data: ['Ventes']
                    },
                    xAxis: {
                        data: Object.keys(hours)
                    },
                    yAxis: {},
                    series: [
                        {
                            name: 'Ventes',
                            type: 'bar',
                            smooth: 'true',
                            data: Object.values(hours)
                        }
                    ]
                };

                myChart.setOption(option);

                // IBANS

                // console.log(asins);
                var myChart = echarts.init(document.getElementById('grafASIN'));

                var option = {
                    title: {
                        text: ''
                    },
                    tooltip: {},
                    legend: {
                        data: ['Ventes Per IBAN']
                    },
                    xAxis: {
                        data: Object.keys(asins)
                    },
                    yAxis: {},
                    series: [
                        {
                            name: 'Ventes Per IBAN',
                            type: 'bar',
                            data: Object.values(asins)
                        }
                    ]
                };

                myChart.setOption(option);



            });

            fetchTasks()


        });
    }


    // CANVIAR MÈTODES
    $(document).on('click', '#verdatos', function () {
        $('.dbs_filters').show();
        $('.table_tasks').show();
        $('.stadistics').hide();


        fetchTasks(limit, order, direction, table, table2);

    });

    $(document).on('click', '#verestadisticas', function () {
        $('.table_tasks').hide();
        $('.stadistics').show();
        $('.dbs_filters').hide();

        fetchTasks(limit, order, direction, table, table2);
        fetchStadistics();

    });

});
