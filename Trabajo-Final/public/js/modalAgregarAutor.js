document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalAgregarAutor');
    const form = document.getElementById('formAgregarAutor');
    const nombre = document.getElementById('aut_nombre');
    const nombreFb = document.getElementById('aut_nombreFeedback');
    const apellido = document.getElementById('aut_apellido');
    const apellidoFb = document.getElementById('aut_apellidoFeedback');
    const fecha = document.getElementById('aut_fechaNac');
    const fechaAyuda = form.querySelector('#aut_fechaNac-help');
    const nombreAyuda = form.querySelector('#aut_nombre-help');
    const apellidoAyuda = form.querySelector('#aut_apellido-help');
    const fechaFb = document.getElementById('aut_fechaNacFeedback');
    const pais = document.getElementById('aut_pais');
    const paisFb = document.getElementById('aut_paisFeedback');
    const boton = document.getElementById('btnAgregarAutor');
    if (!modalEl || !form || !nombre || !apellido || !fecha || !pais || !boton) return;


    const SOLO_LETRAS = /[^\p{L}\s'’.-]/gu;
    const formatear = iso => iso.split('-').reverse().join('/');

    const ayudas = [
        { el: nombre, ayuda: nombreAyuda },
        { el: apellido, ayuda: apellidoAyuda }
    ];

    const timers = new Map();

    ayudas.forEach(({ el, ayuda }) => {
        if (!ayuda) return;

        ayuda.dataset.textoOriginal = ayuda.textContent.trim();
        ayuda.dataset.claseOriginal = ayuda.className;
    });

    function restaurarGuia(ayuda) {
        if (!ayuda) return;

        clearTimeout(timers.get(ayuda));

        ayuda.textContent = ayuda.dataset.textoOriginal;
        ayuda.className = ayuda.dataset.claseOriginal;
        ayuda.hidden = false;
    }

    function avisar(ayuda, mensaje) {
        if (!ayuda) return;

        ayuda.textContent = mensaje;
        ayuda.className = 'form-text text-danger';
        ayuda.hidden = false;

        clearTimeout(timers.get(ayuda));

        const timer = setTimeout(() => {
            restaurarGuia(ayuda);
        }, 3000);

        timers.set(ayuda, timer);
    }



    const fechaValida = () => fecha.value !== '' && fecha.value <= fecha.max && fecha.value >= fecha.min;

    let timerFecha;

    const textoRango = () => `Debe estar entre el ${formatear(fecha.min)} y el ${formatear(fecha.max)} (mínimo 18 años).`;

    const restaurarAyudaFecha = () => {
        if (!fechaAyuda) return;
        fechaAyuda.textContent = textoRango();
        fechaAyuda.className = 'form-text text-muted';
    };

    restaurarAyudaFecha();


    const ajustarFecha = () => {
        if (fecha.value === '') return;
        const anio = parseInt(fecha.value.slice(0, 4), 10);
        if (anio < 1000) return;                 
        if (fecha.max && fecha.value > fecha.max) {
            fecha.value = fecha.max;
        }
    };

    fecha.addEventListener('blur', () => {
        const caeFuera = fecha.value !== '' && (fecha.value > fecha.max || fecha.value < fecha.min);
        ajustarFecha();

        if (fechaAyuda) {
            clearTimeout(timerFecha);

            if (caeFuera && fechaValida()) {
                fechaAyuda.textContent = `La fecha se ajustó al máximo permitido (${formatear(fecha.max)}).`;
                fechaAyuda.className = 'form-text text-danger';
                timerFecha = setTimeout(restaurarAyudaFecha, 3000);
            } else if (caeFuera) {
                fechaAyuda.className = 'form-text text-danger';
            } else {
                restaurarAyudaFecha();
            }
        }
        actualizarBoton();
    });
    


   
    function filtrarConAviso(el, regex, mensaje) {
        const ayuda = form.querySelector(`#${el.id}-help`);

        el.addEventListener('input', () => {
            let limpio = el.value.replace(regex, '');
            let aviso = mensaje;

            const sinSimbolosInicio = limpio.replace(/^[\s'’.-]+/, '');
            const inicioInvalido = sinSimbolosInicio !== limpio;

            if (inicioInvalido) {
                limpio = sinSimbolosInicio;
                aviso = 'El texto debe empezar con una letra.';
            }

            if (limpio === el.value) return;

            let pos = null;

            try {
                pos = el.selectionStart;
            } catch (e) {}

            const quitados = el.value.length - limpio.length;
            el.value = limpio;

            if (pos !== null) {
                const nuevaPosicion = Math.max(0, pos - quitados);

                try {
                    el.setSelectionRange(nuevaPosicion, nuevaPosicion);
                } catch (e) {}
            }

            avisar(ayuda, aviso);
        });
    }


    filtrarConAviso(nombre, SOLO_LETRAS, 'El nombre solo admite letras, espacios, apóstrofes, puntos y guiones.');
    filtrarConAviso(apellido, SOLO_LETRAS, 'El apellido solo admite letras, espacios, apóstrofes, puntos y guiones.');


    const completo = () =>
        nombre.value.trim() !== '' &&
        apellido.value.trim() !== '' &&
        fechaValida() &&
        pais.value !== '';

    const actualizarBoton = () => { 
        
        const bloqueado = !completo();
        boton.classList.toggle('btn-bloqueado', bloqueado);
        boton.setAttribute('aria-disabled', bloqueado ? 'true' : 'false');
    
    };

    nombre.addEventListener('input', () => {
        nombreFb.textContent = '';
        nombre.classList.remove('is-invalid');
        if (nombreAyuda.hidden) {
            restaurarGuia(nombreAyuda);
        }
        actualizarBoton();
    });

    apellido.addEventListener('input', () => {
        apellidoFb.textContent = '';
        apellido.classList.remove('is-invalid');
        if (apellidoAyuda.hidden) {
            restaurarGuia(apellidoAyuda);
        }
        actualizarBoton();
    });

    fecha.addEventListener('input', () => {
        clearTimeout(timerFecha);
        fecha.classList.remove('is-invalid');
        restaurarAyudaFecha();
        actualizarBoton();
    });

    $(pais).on('change', () => { paisFb.textContent = ''; actualizarBoton(); });

    form.addEventListener('submit', function (e) {
        nombre.value = nombre.value.trim();
        apellido.value = apellido.value.trim();
        let ok = true;

        if (nombre.value === '') {
            nombreFb.textContent = 'El nombre es obligatorio.';
            nombre.classList.add('is-invalid');
            ok = false;
        }
        if (apellido.value === '') {
            apellidoFb.textContent = 'El apellido es obligatorio.';
            apellido.classList.add('is-invalid');
            ok = false;
        }
        if (fecha.value === '') {
            fechaFb.textContent = 'La fecha de nacimiento es obligatoria.';
            fecha.classList.add('is-invalid');
            ok = false;
        } else if (!fechaValida()) {
            fechaFb.textContent = 'El autor debe tener al menos 18 años.';
            fecha.classList.add('is-invalid');
            ok = false;
        }
        if (pais.value === '') {
            paisFb.textContent = 'Debe seleccionar un país.';
            ok = false;
        }

        if (!ok) {
            e.preventDefault();
            const primero = form.querySelector('.is-invalid');
            if (primero) {
                primero.focus();
            } else if (pais.value === '') {
                 $(pais).select2('open'); 
            }
                
        }
    });

    modalEl.addEventListener('show.bs.modal', function (e) {
        if (e.relatedTarget) {
            nombre.value = '';
            apellido.value = '';
            fecha.value = '';
            $(pais).val('').trigger('change');
            [nombre, apellido, fecha].forEach(el => el.classList.remove('is-invalid'));
            paisFb.textContent = '';
            ayudas.forEach(({ ayuda }) => {
                if (!ayuda) return;
                restaurarGuia(ayuda);
            });
            clearTimeout(timerFecha);
            restaurarAyudaFecha();
        }
        setTimeout(actualizarBoton, 0);
    });

    if (nombreFb.textContent.trim() !== '' && nombre.classList.contains('is-invalid')) {
        nombreAyuda.hidden = true;
    }

    if (apellidoFb.textContent.trim() !== '' && apellido.classList.contains('is-invalid')) {
        apellidoAyuda.hidden = true;
    }

    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_AUTOR) {
        new bootstrap.Modal(modalEl).show();
    }
});