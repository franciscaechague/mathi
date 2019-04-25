let invalidClassName = 'invalid';
let inputs = document.querySelectorAll('input, select, textarea');

inputs.forEach(function (input) {
  input.addEventListener('invalid', function () {
    input.classList.add(invalidClassName)
  });

  input.addEventListener('input', function () {
    if (input.validity.valid) {
      input.classList.remove(invalidClassName)
    }
  })
});

function onSubmit() {
  alert('Muchas gracias por tu consulta, nos comunicaremos con vos dentro de poco');
}
