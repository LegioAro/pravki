$(document).ready(function(){

    // Click Form1 
    $('.form1').on('click', function () {
        $('.form-box1').toggleClass('js-toggle', 300, "easeOutSine");
        $('.form1').toggleClass("active");
    });

    // Click BGD 
    $('.form1').on('click', function () {
        $('.bgd').toggleClass('js-toggle', 300, "easeOutSine");
    });

    // Close Form1 
    $('.close-form1').on('click', function () {
        $('.form-box1').removeClass('js-toggle', 300, "easeOutSine");
    });

    // Click Form2 
    $('.form2').on('click', function () {
        $('.form-box2').toggleClass('js-toggle', 300, "easeOutSine");
        $('.form2').toggleClass("active");
    });

    // Click BGD 
    $('.form2').on('click', function () {
        $('.bgd').toggleClass('js-toggle', 300, "easeOutSine");
    });

    // Close Form2 
    $('.close-form2').on('click', function () {
        $('.form-box2').removeClass('js-toggle', 300, "easeOutSine");
    });

});
$('#form2').submit(function (event) {
    event.preventDefault();
    var form = $(this),
        name = form.find('input[name="name"]').val(),
        email = form.find('input[name="email"]').val(),
        ip = form.find('input[name="ip"]').val(),
        utm_campaign = form.find('input[name="utm_campaign"]').val(),
        utm_medium = form.find('input[name="utm_medium"]').val(),
        utm_source = form.find('input[name="utm_source"]').val(),
        utm_content = form.find('input[name="utm_content"]').val(),
        utm_group = form.find('input[name="utm_group"]').val(),
        gcpc = form.find('input[name="gcpc"]').val(),
        gcao = form.find('input[name="gcao"]').val(),
        referer = form.find('input[name="referer"]').val();
    var thx = form.find('input[name="thx"]').val();

    $.ajax({
        url: 'referal.php',
        type: 'POST',
        data: {
            name: name,
            email: email,
            ip: ip,
            utm_campaign: utm_campaign,
            utm_medium: utm_medium,
            utm_source: utm_source,
            utm_content: utm_content,
            utm_group: utm_group,
            gcpc: gcpc,
            gcao: gcao,
            referer: referer
        },
        beforeSend: function () {
            $('input[type="submit"]').attr('disabled', 'disabled');
            $('html').addClass('loading');
        },
        success: function () {
            window.location.href = thx+"?email="+email+"&name="+name;
        },
        error: function (response) {
            form.html('<p style="text-align:center;">Ошибка отправки сообщения</p>');
            $('button[type="submit"]').removeAttr('disable');
            $('html').removeClass('loading');
            console.log('Ошибка - ' + response);
        }
    });
});