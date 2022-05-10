$(document).ready(function() {

    $('.stat_description').slideUp();


    var i = 0;
    $('.stat_title').click(function(e) {
        if (i === 1) {
            $( this).next().slideUp()

            i = 0;
            return;
        }
        if (i === 0) {
            $( this).next().slideDown()

            i = 1;
            return;
        }
    });
    

});
