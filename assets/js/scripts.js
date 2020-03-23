

$(document).ready(function() {
    
    // $.extend($.fn.pickadate.defaults, {
    //     monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    //     weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
    //     today: 'aujourd\'hui',
    //     clear: 'effacer',
    //     formatSubmit: 'yyyy-mm-dd'
    //   });

    jQuery.extend( jQuery.fn.pickadate.defaults, {
        monthsFull: [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
        monthsShort: [ 'Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec' ],
        weekdaysFull: [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ],
        weekdaysShort: [ 'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam' ],
        today: 'Aujourd\'hui',
        clear: 'Effacer',
        close: 'Fermer',
        firstDay: 1,
        format: 'dd mmmm yyyy',
        formatSubmit: 'yyyy-mm-dd',
        labelMonthNext:"Mois suivant",
        labelMonthPrev:"Mois précédent",
        labelMonthSelect:"Sélectionner un mois",
        labelYearSelect:"Sélectionner une année"
    });

    jQuery.extend( jQuery.fn.pickatime.defaults, {
        clear: 'Effacer',
        format: 'H:i'
    });

    $(document).ajaxStart(function () {
        $("#loading").show();
    }).ajaxStop(function () {
        $("#loading").hide();
    });

//    $.extend($.fn.pickadate.defaults, {
//        monthsFull: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
//        weekdaysShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
//        today: 'aujourd\'hui',
//        clear: 'effacer',
//        formatSubmit: 'yyyy/mm/dd'
//    });

    $('.fresh-table .fixed-table-body').addClass('scrollbar-deep-purple');
    loadMenu();
    $('.menu-item > a').click(function(e) {
        e.preventDefault();
    });
    
    setInterval(laodHour, 1000);
});

function loadMenu () {
    
    var windowHeight = $(window).height();
    // $('.main-container').css('height', windowHeight);
    $('.views .container-table').css('height', windowHeight - 350);
    $('.container-menu').css('height', windowHeight - ($('.header').height() + 115));
    
    $('ul.menu li.menu-item .submenu').hide();

    if ($('ul.menu li.menu-item').hasClass('active')) {
        $('ul.menu > li.active ul.submenu').slideDown('normal');
    }
    $('ul.menu li.menu-item').click(function () {
        if ($(this).children('ul').length > 0) {
            
            if ($(this).hasClass('active')) {
                $(this).children('.submenu').slideUp ('normal', function () {
                    $(this).parent().removeClass('active');
                });
                // console.log('je suis la classe active');
            }else {
                // console.log('je veux etre activer');
                $('li.menu-item .submenu').slideUp('normal', function () {
                    $('li.menu-item').removeClass('active');
                });

                $(this).children('ul.submenu').slideDown('normal', function () {
                    $(this).parent().addClass('active');
                });
            }
        }
    });
}

function laodHour () {
    var dateTime = new Date();
    var heure = dateTime.getHours();
    var minutes = dateTime.getMinutes();
    var secondes = dateTime.getSeconds();
    
    if (heure <= 9) {
        heure = '0' + heure;
    }
    
    if (minutes <= 9) {
        minutes = '0' + minutes;
    }
    
    if (secondes <= 9) {
        secondes = '0' + secondes;
    }
    
    $('p.time span').text(heure + ':' + minutes + ':' + secondes);
}

function fieldForm (domElement, message, type) {
    var parent = domElement.parent('.form-group').removeClass('has-success');
    if (!parent.hasClass('has-feedback')) {
        parent.addClass('has-feedback');
    }
    if (type == 1) {
        parent.removeClass('has-success');
        parent.removeClass('has-warning');
        parent.addClass('has-error');
        parent.children('span.glyphicon-warning-sign').remove();
        if (parent.children('span.glyphicon-remove').length == 0) {
            parent.append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
        }

        parent.children('span.help-block').text(message);
    }else if (type == 2){
        parent.removeClass('has-success');
        parent.removeClass('has-error');
        parent.addClass('has-warning');
        parent.children('span.glyphicon-ok').remove();
        parent.children('span.glyphicon-remove').remove();
        if (parent.children('span.glyphicon-remove').length == 0) {
            parent.append('<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>');
        }
        parent.children('span.help-block').text(message);
    }else {
        parent.removeClass('has-error');
        parent.addClass('has-success');
        parent.children('span.glyphicon-remove').remove();
        parent.append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
        parent.children('span.help-block').text('');
    }
}

