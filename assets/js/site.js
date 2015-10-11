$(document).ready(function () {
    $('form.destroy-form').on('submit', function (submit) {
        // Otetaan kohdelomakkeesta data-confirm-attribuutin arvo
        var confirm_message = $(this).attr('data-confirm');
        // Pyydetään käyttäjältä vahvistusta
        if (!confirm(confirm_message)) {
            // Jos käyttäjä ei anna vahvistusta, ei lähetetä lomaketta
            submit.preventDefault();
        }
    });
    //alert('Hello World!');
});
