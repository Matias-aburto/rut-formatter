document.addEventListener("DOMContentLoaded", function() {
  var rutInput = document.getElementById(RUTFormatter.billing_rut_field_name); // Usar el nombre del campo proporcionado desde PHP

  if (rutInput) { // Asegurarse de que el campo existe antes de agregar un event listener
      rutInput.addEventListener("input", function() {
          var rut = this.value;
          var formattedRut = formatRut(rut);
          this.value = formattedRut;
      });
  }

  function formatRut(rut) {
      // Elimina cualquier caracter no numérico y las letras Kk al final
      rut = rut.replace(/[^\dKk]/g, "");
    
      // Asegura que el RUT tenga una longitud máxima de 9 dígitos
      rut = rut.substr(0, 9);
    
      // Divide el RUT en parte antes del guión y el dígito verificador
      var rutBody = rut.substring(0, rut.length - 1);
      var rutVerifier = rut.charAt(rut.length - 1);

      // Verifica que haya al menos 7 caracteres antes de agregar el guión
      if (rutBody.length >= 7) {
          // Formatea la parte antes del guión con puntos y agrega el guión y el dígito verificador
          var formattedRut = rutBody.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + "-" + rutVerifier;
      } else {
          // Si no hay al menos 7 caracteres, no se agrega el guión
          var formattedRut = rutBody + rutVerifier;
      }
    
      return formattedRut;
  }
});
