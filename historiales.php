<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Transacciones - IMAGEN UNIK</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* ── Layout ───────────────────────────────────────────────────────── */
        .page-wrapper { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem 3rem; }

        .hist-card {
            background: white; border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10); overflow: hidden;
        }

        /* ── Card Header ─────────────────────────────────────────────────── */
        .hist-header {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: white; padding: 1.75rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .hist-header h2 { margin: 0; font-size: 1.35rem; font-weight: 700;
                          display: flex; align-items: center; gap: .6rem; }
        .hist-header p  { margin: .25rem 0 0; font-size: .85rem; opacity: .85; }
        #total-badge {
            background: rgba(255,255,255,.2); padding: 4px 14px;
            border-radius: 20px; font-size: .85rem; font-weight: 600;
        }

        /* ── Filter bar ──────────────────────────────────────────────────── */
        .filter-bar {
            display: flex; flex-wrap: wrap; gap: 1rem;
            align-items: flex-end; padding: 1.25rem 2rem;
            background: #fafafa; border-bottom: 1px solid #f0f0f0;
        }
        .filter-bar label  { font-size: .75rem; font-weight: 700;
                             text-transform: uppercase; color: #5f6368;
                             display: block; margin-bottom: .3rem; }
        .filter-bar input  { padding: .55rem .9rem; border: 1.5px solid #e0e0e0;
                             border-radius: 8px; font-family: inherit; font-size: .9rem;
                             transition: border-color .2s; }
        .filter-bar input:focus { outline: none; border-color: #1a73e8;
                                  box-shadow: 0 0 0 3px rgba(26,115,232,.12); }

        /* ── Status tab pills ────────────────────────────────────────────── */
        .tab-pills { display: flex; gap: .5rem; }
        .tab-pill {
            padding: .45rem 1.1rem; border-radius: 20px;
            font-size: .82rem; font-weight: 700;
            border: 2px solid transparent; cursor: pointer;
            transition: all .2s;
        }
        .tab-pill.all       { background: #e8eaed; color: #5f6368; border-color: #e8eaed; }
        .tab-pill.confirmed { background: #e6f4ea; color: #34a853; border-color: #e6f4ea; }
        .tab-pill.cancelled { background: #fce8e6; color: #ea4335; border-color: #fce8e6; }
        .tab-pill.active    { border-color: currentColor; }

        /* ── Table ───────────────────────────────────────────────────────── */
        .hist-table { width: 100%; border-collapse: collapse; }
        .hist-table thead th {
            padding: .9rem 1.25rem; text-align: left;
            font-size: .72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px;
            color: #5f6368; background: #f8f9fa;
            border-bottom: 2px solid #e8eaed;
        }
        .hist-table tbody tr { border-bottom: 1px solid #f0f0f0; transition: background .15s; }
        .hist-table tbody tr:hover { background: #f8f9ff; }
        .hist-table tbody td { padding: .85rem 1.25rem; font-size: .9rem; color: #333; }

        /* ── Status badges ───────────────────────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: 3px 12px; border-radius: 20px;
            font-size: .75rem; font-weight: 700;
        }
        .badge.confirmada { background: #e6f4ea; color: #2d7a4f; }
        .badge.cancelada  { background: #fce8e6; color: #c0392b; }

        /* ── Action buttons ──────────────────────────────────────────────── */
        .btn-sm {
            padding: .3rem .8rem; border-radius: 6px; border: none;
            cursor: pointer; font-weight: 600; font-size: .78rem;
            font-family: inherit; display: inline-flex;
            align-items: center; gap: .3rem; transition: all .2s;
        }
        .btn-sm.view   { background: #e8f0fe; color: #1a73e8; }
        .btn-sm.view:hover { background: #1a73e8; color: white; }

        /* ── Empty state ─────────────────────────────────────────────────── */
        .empty-state { text-align: center; padding: 3rem; color: #aaa; }
        .empty-state .icon { font-size: 2.5rem; display: block; margin-bottom: .75rem; }

        /* ── Detail Modal ────────────────────────────────────────────────── */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.5);
            display: none; align-items: center; justify-content: center; z-index: 2000;
        }
        .modal-box {
            background: white; border-radius: 16px;
            width: 90%; max-width: 820px;
            max-height: 92vh; overflow: hidden;
            display: flex; flex-direction: column;
            box-shadow: 0 24px 60px rgba(0,0,0,.22);
        }
        .modal-head {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: white; padding: 1.25rem 1.75rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-head h3 { margin: 0; font-size: 1.05rem; }
        .modal-body   { padding: 1.75rem 2rem; overflow-y: auto; flex: 1; }
        .modal-foot   {
            padding: 1rem 1.75rem; border-top: 1px solid #f0f0f0;
            background: #fafafa; display: flex; gap: .75rem; justify-content: flex-end;
        }

        /* ── Printable receipt ───────────────────────────────────────────── */
        #printable-area { color: black; }
        .print-header   { text-align: center; margin-bottom: 1.5rem; }
        .print-header h1 { color: #333; font-size: 1.2rem; margin-bottom: .25rem; }
        .print-table    { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        .print-table th, .print-table td { border: 1px solid #ddd; padding: 7px 10px; text-align: left; font-size: .87rem; }
        .print-table th  { background: #f2f2f2; font-weight: 700; }
        .print-totals    { width: 280px; margin-left: auto; }
        .totals-row { display: flex; justify-content: space-between; padding: .35rem 0; font-size: .9rem; color: #555; }
        .totals-row.grand { font-weight: 700; font-size: 1.05rem; color: #202124; border-top: 2px solid #333; padding-top: .5rem; margin-top: .25rem; }

        /* ── Cancelled watermark in modal ────────────────────────────────── */
        .cancel-note {
            background: #fff3f3; border: 1.5px solid #f5c6c6;
            border-radius: 10px; padding: 1rem 1.25rem;
            margin-bottom: 1.25rem; display: flex; gap: .75rem; align-items: flex-start;
        }
        .cancel-note .cn-icon { font-size: 1.4rem; line-height: 1; }
        .cancel-note .cn-text { font-size: .88rem; color: #c0392b; }
        .cancel-note .cn-text strong { display: block; margin-bottom: .2rem; font-size: .92rem; }

        /* ── Toast ───────────────────────────────────────────────────────── */
        #toast { position: fixed; bottom: 2rem; right: 2rem; padding: 1rem 1.5rem;
                 border-radius: 10px; font-weight: 600; font-size: .95rem;
                 color: white; box-shadow: 0 8px 24px rgba(0,0,0,.2);
                 display: none; z-index: 9999; }
        #toast.success { background: #34a853; }
        #toast.error   { background: #ea4335; }

        @media print {
            body * { visibility: hidden; }
            #printable-area, #printable-area * { visibility: visible; }
            #printable-area { position: absolute; left: 0; top: 0; width: 100%; padding: 2rem; }
        }
    </style>
</head>
<body>
    <header class="pos-header glass">
        <div class="logo-container" style="display:flex;align-items:center;gap:1rem;">
            <img src="Logo.jpeg" alt="IMAGEN UNIK Logo" class="logo"
                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
            <div>
                <h1 style="margin:0;font-size:1.4rem;font-weight:700;">PUNTO DE VENTA IMAGEN UNIK</h1>
                <p style="margin:0;font-size:.85rem;color:#5f6368;">Reynosa, Tamaulipas</p>
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">← Volver a Ventas</a>
    </header>

    <div class="page-wrapper">
        <div class="hist-card">

            <!-- Header -->
            <div class="hist-header">
                <div>
                    <h2>📋 Historial de Transacciones</h2>
                    <p>Ventas confirmadas y canceladas registradas en el sistema.</p>
                </div>
                <span id="total-badge">Cargando...</span>
            </div>

            <!-- Filter bar -->
            <div class="filter-bar">
                <div>
                    <label>Desde:</label>
                    <input type="date" id="filter-start">
                </div>
                <div>
                    <label>Hasta:</label>
                    <input type="date" id="filter-end">
                </div>
                <!-- Status tabs -->
                <div>
                    <label>Estatus:</label>
                    <div class="tab-pills">
                        <button class="tab-pill all active"       onclick="setTab('all',this)">Todos</button>
                        <button class="tab-pill confirmed"        onclick="setTab('confirmada',this)">✅ Confirmadas</button>
                        <button class="tab-pill cancelled"        onclick="setTab('cancelada',this)">🚫 Canceladas</button>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="loadSales()" style="align-self:flex-end;">Buscar</button>
            </div>

            <!-- Table -->
            <table class="hist-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha y Hora</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estatus</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="sales-tbody"></tbody>
            </table>

            <div id="empty-state" class="empty-state" style="display:none;">
                <span class="icon">🔍</span>
                <p>No se encontraron transacciones en este rango.</p>
            </div>
        </div>
    </div>

    <!-- ══ Modal: Detalle de Venta ════════════════════════════════════════ -->
    <div class="modal-overlay" id="sale-modal">
        <div class="modal-box">
            <div class="modal-head">
                <h3 id="modal-title">📄 Detalle de Venta</h3>
                <button class="btn-sm view" onclick="closeModal()" style="background:rgba(255,255,255,.2);color:white;">✕ Cerrar</button>
            </div>

            <div class="modal-body">
                <!-- Cancellation note (shown only for cancelled) -->
                <div class="cancel-note" id="cancel-note" style="display:none;">
                    <span class="cn-icon">🚫</span>
                    <div class="cn-text">
                        <strong>Venta Cancelada</strong>
                        <span id="cancel-reason-text">—</span>
                    </div>
                </div>

                <div id="printable-area">
                    <div class="print-header">
                        <img src="Logo.jpeg" alt="IMAGEN UNIK" style="max-height:70px;margin-bottom:.5rem;"
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
                        <h1>IMAGEN UNIK</h1>
                        <p style="color:#666;margin:.2rem 0;">Reynosa, Tamaulipas</p>
                        <h2 style="margin-top:1rem;">
                            <span id="print-doc-title">Nota de Venta</span> &nbsp;<strong><span id="print-id"></span></strong>
                        </h2>
                        <p>Fecha: <span id="print-date"></span></p>
                        <p>Cliente: <span id="print-client"></span></p>
                    </div>

                    <table class="print-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>P. Unitario</th>
                                <th>Descuento</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody id="print-items"></tbody>
                    </table>

                    <div class="print-totals">
                        <div class="totals-row"><span>Subtotal:</span>   <span id="print-subtotal"></span></div>
                        <div class="totals-row"><span>Descuento:</span>  <span id="print-discount"></span></div>
                        <div class="totals-row"><span>IVA:</span>        <span id="print-iva"></span></div>
                        <div class="totals-row grand"><span>Total:</span><span id="print-total"></span></div>
                    </div>
                </div>
            </div>

            <div class="modal-foot" id="modal-foot">
                <button class="btn btn-primary"  onclick="exportHtml2Pdf()">📥 Descargar PDF</button>
                <button class="btn btn-success"  onclick="window.print()">🖨️ Imprimir</button>
            </div>
        </div>
    </div>

    <div id="toast"></div>

    <!-- html2pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // ── State ──────────────────────────────────────────────────────────
        let currentTab = 'all';

        // ── Init ───────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            const now      = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            document.getElementById('filter-start').value = firstDay.toISOString().split('T')[0];
            document.getElementById('filter-end').value   = now.toISOString().split('T')[0];
            loadSales();
        });

        // ── Tab control ────────────────────────────────────────────────────
        function setTab(tab, btn) {
            currentTab = tab;
            document.querySelectorAll('.tab-pill').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            loadSales();
        }

        // ── Load sales ────────────────────────────────────────────────────
        function loadSales() {
            const start   = document.getElementById('filter-start').value;
            const end     = document.getElementById('filter-end').value;
            const estatus = currentTab !== 'all' ? `&estatus=${currentTab}` : '';

            fetch(`api/get_sales.php?start=${start}&end=${end}${estatus}`)
                .then(r => r.json())
                .then(data => {
                    const tbody  = document.getElementById('sales-tbody');
                    const empty  = document.getElementById('empty-state');
                    const badge  = document.getElementById('total-badge');
                    tbody.innerHTML = '';

                    if (!data.success || data.data.length === 0) {
                        empty.style.display = 'block';
                        badge.textContent   = '0 registros';
                        return;
                    }

                    empty.style.display = 'none';
                    badge.textContent   = `${data.data.length} registro${data.data.length !== 1 ? 's' : ''}`;

                    data.data.forEach(v => {
                        const isCancel = v.estatus === 'cancelada';
                        const badge_html = isCancel
                            ? `<span class="badge cancelada">🚫 Cancelada</span>`
                            : `<span class="badge confirmada">✅ Confirmada</span>`;

                        const tr = document.createElement('tr');
                        if (isCancel) tr.style.opacity = '.75';

                        tr.innerHTML = `
                            <td style="font-weight:700;color:#5f6368;">F-${String(v.folio).padStart(4,'0')}</td>
                            <td>${v.fecha_hora}</td>
                            <td>${v.cliente_nombre}</td>
                            <td style="font-weight:600;color:${isCancel ? '#ea4335' : '#1a73e8'};">
                                $${parseFloat(v.total).toFixed(2)}
                            </td>
                            <td>${badge_html}</td>
                            <td>
                                <button class="btn-sm view" onclick="openSale(${v.id})">
                                    🔍 Ver
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => { console.error(err); showToast('❌ Error al cargar historial.', 'error'); });
        }

        // ── Open sale modal ────────────────────────────────────────────────
        function openSale(id) {
            fetch(`api/get_sale_details.php?id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) { showToast('❌ Error al cargar los detalles.', 'error'); return; }

                    const m         = data.master;
                    const isCancel  = m.estatus === 'cancelada';

                    document.getElementById('modal-title').textContent =
                        isCancel ? '🚫 Venta Cancelada' : '📤 Detalle de Venta';
                    document.getElementById('print-doc-title').textContent =
                        isCancel ? 'Venta Cancelada' : 'Nota de Venta';
                    document.getElementById('print-id').textContent =
                        'F-' + String(m.folio || m.id).padStart(4, '0');

                    // Cancellation note
                    const cancelNote = document.getElementById('cancel-note');
                    if (isCancel && m.motivo_cancelacion) {
                        document.getElementById('cancel-reason-text').textContent = m.motivo_cancelacion;
                        cancelNote.style.display = 'flex';
                    } else {
                        cancelNote.style.display = 'none';
                    }

                    // Header data
                    document.getElementById('print-date').textContent   = m.fecha_hora;
                    document.getElementById('print-client').textContent = m.cliente_nombre;

                    // Items
                    const tbody = document.getElementById('print-items');
                    tbody.innerHTML = '';
                    data.detalles.forEach(d => {
                        const medidas = (d.alto && d.ancho)
                            ? `<br><small style="color:#1a73e8;font-size:.78rem;">
                               📐 ${parseFloat(d.alto).toFixed(2)} m × ${parseFloat(d.ancho).toFixed(2)} m
                               = ${parseFloat(d.cantidad).toFixed(4)} m²</small>`
                            : '';
                        tbody.innerHTML += `
                            <tr>
                                <td>${d.nombre_producto}${medidas}</td>
                                <td>${parseFloat(d.cantidad).toFixed(2)}</td>
                                <td>$${parseFloat(d.costo_unitario).toFixed(2)}</td>
                                <td>$${parseFloat(d.descuento_mxn).toFixed(2)}</td>
                                <td>$${parseFloat(d.total_linea).toFixed(2)}</td>
                            </tr>`;
                    });

                    // Totals
                    document.getElementById('print-subtotal').textContent = `$${parseFloat(m.subtotal).toFixed(2)}`;
                    document.getElementById('print-discount').textContent = `$${parseFloat(m.descuento_total).toFixed(2)}`;
                    document.getElementById('print-iva').textContent      = `$${parseFloat(m.iva).toFixed(2)}`;
                    document.getElementById('print-total').textContent    = `$${parseFloat(m.total).toFixed(2)}`;

                    // Hide print/PDF buttons for cancelled records
                    document.getElementById('modal-foot').style.display = isCancel ? 'none' : 'flex';

                    document.getElementById('sale-modal').style.display = 'flex';
                });
        }

        function closeModal() { document.getElementById('sale-modal').style.display = 'none'; }

        function exportHtml2Pdf() {
            const element = document.getElementById('printable-area');
            html2pdf().set({
                margin: 10,
                filename: 'Venta_' + document.getElementById('print-id').textContent + '.pdf',
                image:     { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF:     { unit: 'mm', format: 'a4', orientation: 'portrait' }
            }).from(element).save();
        }

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg; t.className = type; t.style.display = 'block';
            setTimeout(() => { t.style.display = 'none'; }, 3500);
        }

        // Close on ESC or overlay click
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
        document.getElementById('sale-modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
</body>
</html>
