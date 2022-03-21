$(document).ready(function() {
    $('.grid_stats_months').slideUp();
    $('.grid_stats_week').slideUp();
    $('.grid_stats_hours').slideUp();

    var apartado_week = 0;
    $('.stat_title_week').click(function(e) {
        if (apartado_week === 1) {
            $('.grid_stats_week').slideUp();
            apartado_week = 0;
            return;
        }
        if (apartado_week === 0) {
            $('.grid_stats_week').slideDown();
            apartado_week = 1;
            return;
        }
    });
    var apartado_meses = 0;
    $('.stat_title_months').click(function(e) {
        if (apartado_meses === 1) {
            $('.grid_stats_months').slideUp();
            apartado_meses = 0;
            return;
        }
        if (apartado_meses === 0) {
            $('.grid_stats_months').slideDown();
            apartado_meses = 1;
            return;
        }
    });
    var apartado_hour = 0
    $('.stat_title_hours').click(function(e) {
        if (apartado_hour === 1) {
            $('.grid_stats_hours').slideUp();
            apartado_hour = 0;
            return;
        }
        if (apartado_hour === 0) {
            $('.grid_stats_hours').slideDown();
            apartado_hour = 1;
            return;
        }
    });

});