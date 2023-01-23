$(function () {
  $.validator.setDefaults({
    errorClass: 'help-block',
    highlight: function (element) {
      $(element)
              .closest('.form-group')
              .addClass('has-error');
    },
    unhighlight: function (element) {
      $(element)
              .closest('.form-group')
              .removeClass('has-error')
              .addClass('has-success');
    },
    errorPlacement: function (error, element) {
        if (element.prop('type') === 'checkbox') {
            error.insertAfter(element.parent());
        }
        else if(element.prop('type') == 'select-one' || element.prop('type') == 'select-multiple'){
            error.insertAfter(element.parent().find('.select2-container'));
        }
        else if(element.hasClass('datepicker1') ||element.hasClass('datetimepicker1')) {
            error.insertAfter(element.parent());
        }
        else if (element.attr('name') == 'captcha') {
            error.insertAfter("#captcha-img");
        }
        //touchspin
        else if (element.hasClass('tarif') || element.hasClass('quantite')) {
            error.insertAfter(element.parent());
        }
        else {
            error.insertAfter(element);
        }
    }
  });

  $.validator.addMethod('strongPassword', function (value, element) {
    return this.optional(element)
            || value.length >= 6
            && /[0-9]/.test(value)
            && /[A-Z]/.test(value)
            && /[a-z]/i.test(value);
  }, 'Votre mot de passe doit contenir 6 caractères, un chiffre et une lettre majuscule au minimum.')

  $.validator.addMethod('ContainsAtLeastOneDigit',function (value) {
        return /[0-9]/.test(value);
      }, 'Votre mot de passe doit contenir au moins un chiffre.');

  $.validator.addMethod('ContainsAtLeastOneCapitalLetter', function (value) {
        return /[A-Z]/.test(value);
      }, 'Votre mot de passe doit contenir au moins une lettre majuscule.');

  $("#formulaire").validate({
  });

  $("#formulaire2").validate({
  });

  $("#formulaire_register").validate({
    rules: {
      "plainPassword[first]": {
          //strongPassword: true,
          ContainsAtLeastOneDigit: true,
          ContainsAtLeastOneCapitalLetter: true,
      },
      "plainPassword[second]": {
          //strongPassword: true,
          ContainsAtLeastOneDigit: true,
          ContainsAtLeastOneCapitalLetter: true,
          equalTo: "#plainPassword_first",
      }
    },
    messages: {
        "plainPassword[first]": { minlength : "Votre mot de passe doit contenir 6 caractères, un chiffre et une lettre majuscule au minimum." },
        "plainPassword[second]": { minlength : "Votre mot de passe doit contenir 6 caractères, un chiffre et une lettre majuscule au minimum." },
    }
  });

  $("#formulaire_login").validate({
    rules: {
      _password : { strongPassword: true }
    },
  });

  $("#formulaire_profile").validate({
        rules: {
            current_password : { strongPassword: true }
        },
        messages: {
            type : { required: "Veuillez sélectionner un type." },
            gouvernorat : { required: "Veuillez sélectionner un gouvernorat." },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'type') {
                error.insertAfter("#select_type");
            }
            else if (element.attr('name') == 'gouvernorat') {
                error.insertAfter("#select_gouv");
            }
            else if (element.attr('name') == 'htmleditor') {
                error.insertAfter("#textearea_ppt");
            }
            else {
                error.insertAfter(element);
            }
        }
    });


});

