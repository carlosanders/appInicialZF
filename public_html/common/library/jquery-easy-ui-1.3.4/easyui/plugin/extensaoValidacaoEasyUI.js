$.extend($.fn.validatebox.defaults.rules, {
    verificaCPF: {
        validator: function(value, param) {
            //return value.length >= param[0];
            var numeros, digitos, soma, i, resultado, digitos_iguais;
            digitos_iguais = 1;
            if (value.length < 11 || value == "01234567890" || value == "12345678909")
                return false;

            for (i = 0; i < value.length - 1; i++)
                if (value.charAt(i) != value.charAt(i + 1))
                {
                    digitos_iguais = 0;
                    break;
                }
            if (!digitos_iguais)
            {
                numeros = value.substring(0, 9);
                digitos = value.substring(9);
                soma = 0;
                for (i = 10; i > 1; i--)
                    soma += numeros.charAt(10 - i) * i;
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(0))
                    return false;
                numeros = value.substring(0, 10);
                soma = 0;
                for (i = 11; i > 1; i--)
                    soma += numeros.charAt(11 - i) * i;
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(1))
                    return false;
                return true;
            }
        },
        message: 'Por favor, insira pelo menos 11 caracteres.'
    }
});

$.extend($.fn.validatebox.defaults.rules, {
    minLength: {
        validator: function(value, param) {
            return value.length >= param[0];
        },
        message: 'Por favor, insira pelo menos {0} caracteres.'
    }
});

$.extend($.fn.validatebox.defaults.rules, {
    equals: {
        validator: function(value, param) {
            return value == $(param[0]).val();
        },
        message: 'Os campos não coincidem!'
    }
});

$.extend($.fn.validatebox.defaults.rules, {
    vrfCPF: {
        validator: function(value, param) {
            //return value.length >= param[0];
            value = value.replace('.', '');
            value = value.replace('.', '');
            value = value.replace('-', '');
            if (value.length < 11 || value == "01234567890" || value == "12345678909")
                return false;

            while (value.length < 11)
                value = "0" + value;
            var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
            var a = [];
            var b = new Number;
            var c = 11;
            for (i = 0; i < 11; i++) {
                a[i] = value.charAt(i);
                if (i < 9)
                    b += (a[i] * --c);
            }
            if ((x = b % 11) < 2) {
                a[9] = 0
            } else {
                a[9] = 11 - x
            }
            b = 0;
            c = 11;
            for (y = 0; y < 10; y++)
                b += (a[y] * c--);
            if ((x = b % 11) < 2) {
                a[10] = 0;
            } else {
                a[10] = 11 - x;
            }
            if ((value.charAt(9) != a[9]) || (value.charAt(10) != a[10]) || value.match(expReg))
                return false;
            return true;
        },
        message: 'Informe um CPF válido.'
    }
});

