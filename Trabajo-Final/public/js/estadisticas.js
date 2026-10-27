(function () {
    const urls = window.ESTADISTICAS_URLS;

    const COLORES_VENTAS = {
        semana: '#198754', 
        mes:    '#0d6efd', 
        anio:   '#fd7e14', 
    };

    const ETIQUETAS_VENTAS = {
        semana: 'Ingresos semanales ($)',
        mes:    'Ingresos mensuales ($)',
        anio:   'Ingresos anuales ($)',
    };

    const peso = n => '$' + Number(n).toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    const get = async (url, params = {}) => {
        const r = await fetch(url + '?' + new URLSearchParams(params), {headers: {'Accept': 'application/json'}});
        if (!r.ok) throw new Error('Error ' + r.status);
        return r.json();
    };

    let chartVentas, chartCats;

    async function cargarResumen() {
        const d = await get(urls.resumen);
        ['total', 'aceptadas', 'pendientes', 'canceladas', 'rechazadas'].forEach(k =>
            document.getElementById('k-' + k).textContent = d[k]);
        document.getElementById('k-ingresos_mes').textContent = peso(d.ingresos_mes);
        document.getElementById('k-ingresos_anio').textContent = peso(d.ingresos_anio);
    }

    async function cargarVentas(periodo) {
        const d = await get(urls.ventas, {periodo});
        chartVentas?.destroy();
        chartVentas = new Chart(document.getElementById('graficoVentas'), {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [{
                    label: ETIQUETAS_VENTAS[periodo],
                    data: d.ingresos,
                    backgroundColor: COLORES_VENTAS[periodo],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {tooltip: {callbacks: {
                    label: c => peso(c.parsed.y) + ' · ' + d.pedidos[c.dataIndex] + ' pedidos'
                }}},
                scales: {y: {beginAtZero: true}}
            }
        });
    }

    async function cargarPeriodo(periodo) {
        const [top, cats] = await Promise.all([get(urls.top, {periodo}), get(urls.categorias, {periodo})]);

        const lista = document.getElementById('listaTop');
        lista.innerHTML = '';
        if (!top.length) lista.innerHTML = '<li class="list-group-item text-muted">Sin ventas en el período</li>';
        top.forEach((l, i) => {
            const li = document.createElement('li');
            li.className = 'list-group-item top-item';
            li.innerHTML =
                '<span class="top-pos"></span>' +
                '<span class="top-nombre"></span>' +
                '<span class="badge bg-primary rounded-pill top-badge"></span>';
            li.children[0].textContent = (i + 1) + '.';
            li.children[1].textContent = l.nombre;
            li.children[2].textContent = l.total + (Number(l.total) === 1 ? ' unidad' : ' unidades');
            lista.appendChild(li);
        });

        chartCats?.destroy();
        chartCats = new Chart(document.getElementById('graficoCategorias'), {
            type: 'pie',
            data: {
                labels: cats.map(c => c.nombre),
                datasets: [{data: cats.map(c => Number(c.total))}]
            },
             options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {legend: {position: 'bottom'}}
            }
        });
    }

    function grupo(id, fn) {
        const g = document.getElementById(id);
        g.addEventListener('click', e => {
            const b = e.target.closest('button');
            if (!b) return;
            g.querySelectorAll('button').forEach(x => x.classList.remove('active'));
            b.classList.add('active');
            fn(b.dataset.v);
        });
    }

    grupo('grp-ventas', cargarVentas);
    grupo('grp-periodo', cargarPeriodo);

    cargarResumen();
    cargarVentas('mes');
    cargarPeriodo('mes');
})();