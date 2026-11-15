document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalAgregarAutor');
    const form = document.getElementById('formAgregarAutor');
    const nombre = document.getElementById('aut_nombre');
    const nombreFb = document.getElementById('aut_nombreFeedback');
    const apellido = document.getElementById('aut_apellido');
    const apellidoFb = document.getElementById('aut_apellidoFeedback');
    const fecha = document.getElementById('aut_fechaNac');
    const fechaAyuda = form.querySelector('#aut_fechaNac-help');
    const fechaFb = document.getElementById('aut_fechaNacFeedback');
    const pais = document.getElementById('aut_pais');
    const paisFb = document.getElementById('aut_paisFeedback');
    const boton = document.getElementById('btnAgregarAutor');
    if (!modalEl || !form || !nombre || !apellido || !fecha || !pais || !boton) return;


    const SOLO_LETRAS = /[^\p{L}\s'’.-]/gu;
    const formatear = iso => iso.split('-').reverse().join('/');


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
                fechaAyuda.className = 'form-text text-warning';
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
        let timer;

        el.addEventListener('input', () => {
            const limpio = el.value.replace(regex, '');
            if (limpio === el.value) return;

            let pos = null;
            try { pos = el.selectionStart; } catch (e) {}

            el.value = limpio;

            if (pos !== null) {
                const nueva = Math.max(0, pos - 1);
                try { el.setSelectionRange(nueva, nueva); } catch (e) {}
            }

            if (!ayuda) return;
            ayuda.textContent = mensaje;
            ayuda.hidden = false;
            clearTimeout(timer);
            timer = setTimeout(() => { ayuda.hidden = true; }, 3000);
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

    nombre.addEventListener('input', () => { nombre.classList.remove('is-invalid'); actualizarBoton(); });
    apellido.addEventListener('input', () => { apellido.classList.remove('is-invalid'); actualizarBoton(); });

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
            clearTimeout(timerFecha);
            restaurarAyudaFecha();
        }
        setTimeout(actualizarBoton, 0);
    });

    actualizarBoton();

    if (window.REABRIR_MODAL_AGREGAR_AUTOR) {
        new bootstrap.Modal(modalEl).show();
    }
});