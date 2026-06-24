<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotizaciones - IMAGEN UNIK</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .filter-panel { margin-bottom: 1rem; padding: 1rem; display: flex; gap: 1rem; align-items: flex-end; }
        .filter-panel input { padding: 0.5rem; border: 1px solid var(--border); border-radius: var(--radius-sm); }
        .quotes-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .quotes-table th, .quotes-table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid var(--border); }
        .quotes-table tr:hover { background: rgba(0,0,0,0.02); }
        
        /* Modal for details */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); display: none;
            align-items: center; justify-content: center; z-index: 2000;
        }
        .modal-content {
            background: white; padding: 2rem; border-radius: var(--radius);
            width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto;
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        
        #printable-area { padding: 2rem; background: white; color: black; }
        .print-header { text-align: center; margin-bottom: 2rem; }
        .print-header h1 { color: #333; margin-bottom: 0.5rem; }
        .print-table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
        .print-table th, .print-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .print-table th { background-color: #f2f2f2; }
        .print-totals { width: 300px; margin-left: auto; }
        .print-totals-row { display: flex; justify-content: space-between; margin-bottom: 0.5rem; }
        .print-totals-row.bold { font-weight: bold; font-size: 1.1em; border-top: 2px solid #333; padding-top: 0.5rem; }
        
        @media print {
            body * { visibility: hidden; }
            #printable-area, #printable-area * { visibility: visible; }
            #printable-area { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</head>
<body>
    <header class="pos-header glass">
        <div class="logo-container" style="display: flex; align-items: center; gap: 1rem;">
            <img src="Logo.jpeg" alt="IMAGEN UNIK Logo" class="logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
            <div class="header-titles">
                <h1 style="margin: 0; font-size: 1.5rem; color: var(--text-main); font-weight: 700; letter-spacing: -0.5px;">PUNTO DE VENTA IMAGEN UNIK</h1>
                <p style="margin: 0; font-size: 0.9rem; color: var(--text-muted);">Reynosa, Tamaulipas</p>
            </div>
        </div>
        <div>
            <a href="index.php" class="btn btn-secondary">Volver a Ventas</a>
        </div>
    </header>

    <div class="pos-container" style="display: block;">
        <div class="main-panel glass" style="margin-bottom: 1rem;">
            <h2>Historial de Cotizaciones</h2>
            
            <div class="filter-panel">
                <div>
                    <label>Desde:</label><br>
                    <input type="date" id="filter-start">
                </div>
                <div>
                    <label>Hasta:</label><br>
                    <input type="date" id="filter-end">
                </div>
                <button class="btn btn-primary" onclick="loadQuotes()">Buscar</button>
            </div>

            <table class="quotes-table">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha y Hora</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="quotes-tbody">
                    <!-- JS fills this -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detalles -->
    <div class="modal-overlay" id="quote-modal">
        <div class="modal-content" style="padding: 0; display: flex; flex-direction: column;">
            <div class="modal-header" style="padding: 1.5rem 2rem; border-bottom: 1px solid #eee; margin: 0; background: #f8f9fa; border-radius: var(--radius) var(--radius) 0 0;">
                <h3 style="margin: 0; color: #333;">📄 Detalle de Cotización</h3>
                <button class="btn btn-danger" onclick="closeModal()" style="padding: 0.5rem 1rem;">Cerrar</button>
            </div>

            <div id="printable-area" style="padding: 2rem; overflow-y: auto; max-height: 60vh;">
                <div class="print-header">
                    <img src="Logo.jpeg" alt="IMAGEN UNIK Logo" style="max-height: 80px; margin-bottom: 0.5rem;" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
                    <h1 style="margin-top: 0;">IMAGEN UNIK</h1>
                    <p>Reynosa, Tamaulipas</p>
                    <h2 style="margin-top: 1rem;">Cotización #<span id="print-id"></span></h2>
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
                    <tbody id="print-items">
                    </tbody>
                </table>
                
                <div class="print-totals">
                    <div class="print-totals-row">
                        <span>Subtotal:</span>
                        <span id="print-subtotal"></span>
                    </div>
                    <div class="print-totals-row">
                        <span>Descuento:</span>
                        <span id="print-discount"></span>
                    </div>
                    <div class="print-totals-row">
                        <span>IVA (16%):</span>
                        <span id="print-iva"></span>
                    </div>
                    <div class="print-totals-row bold">
                        <span>Total:</span>
                        <span id="print-total"></span>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #eee; background: #f8f9fa; border-radius: 0 0 var(--radius) var(--radius); display: flex; gap: 1rem; justify-content: flex-end;">
                <button class="btn btn-primary" onclick="exportHtml2Pdf()">📥 Descargar PDF</button>
                <button class="btn btn-success" onclick="printQuote()">🖨️ Imprimir</button>
            </div>
        </div>
    </div>

    <!-- html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Set default dates to current month
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            document.getElementById('filter-start').value = firstDay.toISOString().split('T')[0];
            document.getElementById('filter-end').value = now.toISOString().split('T')[0];
            
            loadQuotes();
        });

        function loadQuotes() {
            const start = document.getElementById('filter-start').value;
            const end = document.getElementById('filter-end').value;
            
            fetch(`api/get_quotes.php?start=${start}&end=${end}`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('quotes-tbody');
                    tbody.innerHTML = '';
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(q => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td style="font-weight:700;color:#5f6368;">F-${String(q.folio).padStart(4,'0')}</td>
                                <td>${q.fecha_hora}</td>
                                <td>${q.cliente_nombre}</td>
                                <td>$${parseFloat(q.total).toFixed(2)}</td>
                                <td><button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;" onclick="openQuote(${q.id})">Ver e Imprimir</button></td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No se encontraron cotizaciones en este rango de fechas.</td></tr>';
                    }
                })
                .catch(err => console.error(err));
        }

        function openQuote(id) {
            fetch(`api/get_quote_details.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const m = data.master;
                        document.getElementById('print-id').textContent = 'F-' + String(m.folio || m.id).padStart(4,'0');
                        document.getElementById('print-date').textContent = m.fecha_hora;
                        document.getElementById('print-client').textContent = m.cliente_nombre;
                        
                        const tbody = document.getElementById('print-items');
                        tbody.innerHTML = '';
                        data.detalles.forEach(d => {
                            const medidas = (d.alto && d.ancho)
                                ? `<br><small style="color:#1a73e8;font-size:0.78rem;">📐 Medidas: ${parseFloat(d.alto).toFixed(2)} m × ${parseFloat(d.ancho).toFixed(2)} m = ${parseFloat(d.cantidad).toFixed(4)} m²</small>`
                                : '';
                            tbody.innerHTML += `
                                <tr>
                                    <td>${d.nombre_producto}${medidas}</td>
                                    <td>${parseFloat(d.cantidad).toFixed(2)}</td>
                                    <td>$${parseFloat(d.costo_unitario).toFixed(2)}</td>
                                    <td>$${parseFloat(d.descuento_mxn).toFixed(2)}</td>
                                    <td>$${parseFloat(d.total_linea).toFixed(2)}</td>
                                </tr>
                            `;
                        });
                        
                        document.getElementById('print-subtotal').textContent = `$${parseFloat(m.subtotal).toFixed(2)}`;
                        document.getElementById('print-discount').textContent = `$${parseFloat(m.descuento_total).toFixed(2)}`;
                        document.getElementById('print-iva').textContent = `$${parseFloat(m.iva).toFixed(2)}`;
                        document.getElementById('print-total').textContent = `$${parseFloat(m.total).toFixed(2)}`;
                        
                        document.getElementById('quote-modal').style.display = 'flex';
                    }
                });
        }

        function closeModal() {
            document.getElementById('quote-modal').style.display = 'none';
        }

        function printQuote() {
            window.print();
        }
        
        function exportHtml2Pdf() {
            const element = document.getElementById('printable-area');
            const opt = {
              margin:       10,
              filename:     'Cotizacion_' + document.getElementById('print-id').textContent + '.pdf',
              image:        { type: 'jpeg', quality: 0.98 },
              html2canvas:  { scale: 2 },
              jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }

        // Handle ESC key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>
</html>
