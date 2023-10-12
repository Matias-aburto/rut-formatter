document.addEventListener("DOMContentLoaded", function() {
    var rutInput = document.getElementById(RUTFormatter.billing_rut_field_name);

    if (rutInput) {
        rutInput.addEventListener("input", function() {
            var rut = this.value;
            var formattedRut = formatRut(rut);
            this.value = formattedRut;
        });

        rutInput.addEventListener("blur", function() {
            if (RUTFormatter.enable_dv_validation === "on") {
                var rut = this.value.replace(/\./g, '').replace('-', '');
                if (!isValidRut(rut)) {
                    displayError("El RUT parece ser inválido. Verifícalo.");
                    clearSuccess();
                    displayFailure();
                } else {
                    clearError();
                    clearFailure();
                    displaySuccess();
                }
            }
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
            return rutBody.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + "-" + rutVerifier;
        } else {
            // Si no hay al menos 7 caracteres, no se agrega el guión
            return rutBody + rutVerifier;
        }
    }

    function isValidRut(rut) {
        var body = rut.slice(0, -1);
        var dv = rut.slice(-1).toLowerCase();
        return calculateDV(body) === dv;
    }

    function calculateDV(rutBody) {
        var total = 0;
        var factor = 2;
        
        for (var i = rutBody.length - 1; i >= 0; i--) {
            total += rutBody[i] * factor;
            factor = factor === 7 ? 2 : factor + 1;
        }
        
        var remainder = total % 11;
        
        if (remainder === 1) return 'k';
        if (remainder === 0) return '0';
        return (11 - remainder).toString();
    }

    function displayError(message) {
        var errorLabel = document.getElementById("rut-error");
        if (!errorLabel) {
            errorLabel = document.createElement("label");
            errorLabel.id = "rut-error";
            errorLabel.style.color = "red";
            errorLabel.style.display = "block";
            errorLabel.style.marginTop = "5px";
            rutInput.parentNode.appendChild(errorLabel);
        }
        errorLabel.textContent = message;
    }

    function clearError() {
        var errorLabel = document.getElementById("rut-error");
        if (errorLabel) {
            errorLabel.textContent = "";
        }
    }

    function displaySuccess() {
        rutInput.style.backgroundImage = 
            "url('data:image/svg+xml;utf8,<svg width=\"20\" height=\"20\" xmlns=\"http://www.w3.org/2000/svg\"><rect width=\"20\" height=\"20\" fill=\"%234CAF50\" rx=\"2\" ry=\"2\"/><path d=\"M5 10 L8 13 L15 6\" fill=\"none\" stroke=\"%23FFFFFF\" stroke-width=\"2\"/></svg>')";
        rutInput.style.backgroundRepeat = "no-repeat";
        rutInput.style.backgroundPosition = "right 5px center";
        rutInput.style.backgroundSize = "20px 20px";
    }
    
    function clearSuccess() {
        rutInput.style.backgroundImage = "";
    }

    function displayFailure() {
        rutInput.style.backgroundImage = 
            "url('data:image/svg+xml;utf8,<svg width=\"20\" height=\"20\" xmlns=\"http://www.w3.org/2000/svg\"><rect width=\"20\" height=\"20\" fill=\"%23FF0000\" rx=\"2\" ry=\"2\"/><path d=\"M6 6 L14 14 M14 6 L6 14\" fill=\"none\" stroke=\"%23FFFFFF\" stroke-width=\"2\"/></svg>')";
        rutInput.style.backgroundRepeat = "no-repeat";
        rutInput.style.backgroundPosition = "right 5px center";
        rutInput.style.backgroundSize = "20px 20px";
    }

    function clearFailure() {
        rutInput.style.backgroundImage = "";
    }
});
