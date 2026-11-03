/* Comportamientos comunes del sitio y del panel de administracion */
(function () {
  'use strict';

  // Confirmacion antes de eliminar (formularios con clase .formEliminar)
  document.addEventListener('submit', function (evento) {
    var form = evento.target.closest('.formEliminar');
    if (!form || form.dataset.confirmado) {
      return;
    }
    evento.preventDefault();

    var que = form.dataset.que || 'el registro';
    var nombre = form.dataset.nombre || '';

    var confirmar = window.Swal
      ? Swal.fire({
        title: '¿Eliminar ' + que + '?',
        text: nombre ? nombre + ' — esta acción no se puede deshacer.' : 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        focusCancel: true
      }).then(function (r) { return r.isConfirmed; })
      : Promise.resolve(window.confirm('¿Eliminar ' + que + '? ' + nombre));

    confirmar.then(function (ok) {
      if (ok) {
        form.dataset.confirmado = '1';
        form.submit();
      }
    });
  }, true);

  // Evita el doble envio de formularios (doble clic en Guardar)
  document.addEventListener('submit', function (evento) {
    var form = evento.target;
    if (evento.defaultPrevented || form.classList.contains('formEliminar') || form.method.toLowerCase() === 'get') {
      return;
    }
    setTimeout(function () {
      form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function (boton) {
        boton.disabled = true;
      });
    }, 0);
  });

  // Aviso inmediato si el archivo elegido supera el maximo del servidor
  document.addEventListener('change', function (evento) {
    var input = evento.target;
    if (!input.matches('input[type="file"][data-max-mb]') || !input.files || !input.files[0]) {
      return;
    }
    var maximo = parseInt(input.dataset.maxMb, 10);
    var mb = input.files[0].size / 1048576;
    if (maximo && mb > maximo) {
      var texto = 'El archivo pesa ' + mb.toFixed(1) + ' MB y el máximo permitido es ' + maximo + ' MB.';
      if (window.Swal) {
        Swal.fire({ icon: 'warning', title: 'Archivo demasiado grande', text: texto });
      } else {
        window.alert(texto);
      }
      input.value = '';
    }
  });

  // Vista previa de imagenes antes de subirlas
  document.addEventListener('change', function (evento) {
    var input = evento.target;
    if (!input.matches('input[type="file"][data-preview]') || !input.files || !input.files[0]) {
      return;
    }
    var nombre = input.dataset.preview;
    var img = document.querySelector('[data-preview-de="' + nombre + '"]');
    var vacio = document.querySelector('[data-vacio-de="' + nombre + '"]');
    if (img) {
      img.src = URL.createObjectURL(input.files[0]);
      img.classList.remove('d-none');
    }
    if (vacio) {
      vacio.remove();
    }
  });

  // Filtro instantaneo de opciones en listas largas (select con data-filtro)
  document.querySelectorAll('input[data-filtrar]').forEach(function (campo) {
    var select = document.getElementById(campo.dataset.filtrar);
    if (!select) {
      return;
    }
    campo.addEventListener('input', function () {
      var texto = campo.value.toLowerCase();
      Array.prototype.forEach.call(select.options, function (opcion) {
        if (!opcion.value) {
          return;
        }
        opcion.hidden = texto !== '' && opcion.text.toLowerCase().indexOf(texto) === -1;
      });
    });
  });

  // Videos livianos: la miniatura se reemplaza por el reproductor recien al tocarla
  document.addEventListener('click', function (evento) {
    var boton = evento.target.closest('.video-liviano');
    if (!boton) {
      return;
    }
    var iframe = document.createElement('iframe');
    iframe.src = boton.dataset.video;
    iframe.title = boton.getAttribute('aria-label') || 'Video';
    iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
    iframe.allowFullscreen = true;
    iframe.className = 'video-liviano-iframe';
    boton.replaceWith(iframe);
  });
})();
