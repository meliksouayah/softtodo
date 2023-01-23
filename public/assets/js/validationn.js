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
      } else {
        error.insertAfter(element);
      }
    }
  });

  $.validator.addMethod('strongPassword', function (value, element) {
    return this.optional(element)
        || value.length >= 8
        && /\d/.test(value)
        && /[a-z]/i.test(value)
        && /[@#$%!?*.]/i.test(value)
        && /[0-9]/i.test(value)
        && /[A-Z]/i.test(value);
  }, 'Your password must be at least 8 characters long, including upper and lower case letters, a number and a symbol.')


  $("#formulaire").validate({

  });


});

