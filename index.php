<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta IMAGEN UNIK</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <!-- Header -->
    <header class="pos-header glass">
        <div class="logo-container">
            <img src="Logo.jpeg" alt="Imagen Unik Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
        </div>
        <div class="header-info">
            <h1>PUNTO DE VENTA IMAGEN UNIK</h1>
            <div id="clock">Cargando fecha...</div>
            <div>LUGAR: Reynosa, Tamaulipas</div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="pos-container">
        
        <!-- Left Panel: Client & Table -->
        <div class="main-panel">
            <div class="client-section glass">
                <input type="hidden" id="client-id" value="1">
                <div class="input-group" style="position: relative; flex: 1;">
                    <label>CLIENTE:</label>
                    <input type="text" id="client-search" class="input-control" value="Público General" placeholder="Escribir cliente...">
                    <div class="autocomplete-suggestions" id="client-suggestions"></div>
                </div>
                <div class="input-group" style="max-width: 100px;">
                    <label>ID VENTA</label>
                    <input type="text" id="folio-venta" class="input-control center" value="Cargando..." readonly style="background: #f1f3f4; color: #5f6368; font-weight: bold; cursor: not-allowed;">
                </div>
            </div>

            <div class="table-container glass">
                <table id="pos-table">
                    <thead>
                        <tr>
                            <th class="center">Líneas</th>
                            <th>Producto</th>
                            <th class="center">Cantidad</th>
                            <th class="center dim-col">Alto (m)</th>
                            <th class="center dim-col">Ancho (m)</th>
                            <th class="right">Costo Unitario</th>
                            <th class="center">Desc %</th>
                            <th class="right">Desc (MXN)</th>
                            <th class="right">Total Línea</th>
                            <th class="center">X</th>
                        </tr>
                    </thead>
                    <tbody id="pos-tbody">
                        <!-- JS Dynamically adds rows here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Panel: Actions & Totals -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <div class="actions-panel glass">
                <a href="productos.php" class="btn btn-primary">REGISTRAR PRODUCTO</a>
                <a href="clientes.php" class="btn btn-secondary">REGISTRAR CLIENTE</a>
                <a href="corte_de_caja.php" class="btn btn-secondary">CORTE DE CAJA</a>
                <a href="cotizaciones.php" class="btn btn-secondary">COTIZACIONES</a>
                <a href="historiales.php" class="btn btn-secondary">HISTORIALES</a>
            </div>

            <!-- Totals Panel -->
            <div class="totals-panel glass">
                <div class="total-row">
                    <span>SUBTOTAL (Sin IVA)</span>
                    <span id="lbl-subtotal">$0.00</span>
                </div>
                <div class="total-row">
                    <span>DESCUENTO TOTAL</span>
                    <span id="lbl-desc-total">$0.00</span>
                </div>
                <div class="total-row">
                    <span>SUBTOTAL DESP. DESCUENTO</span>
                    <span id="lbl-subtotal-desc">$0.00</span>
                </div>
                <div class="total-row">
                    <select id="iva-rate" class="select-iva" style="background: transparent; border: 1px solid #ccc; border-radius: 6px; padding: 0.25rem 0.5rem; font-family: inherit; font-size: 0.85rem; font-weight: 600; color: #5f6368; outline: none; cursor: pointer;">
                        <option value="0.16" selected>IVA (16%)</option>
                        <option value="0.08">IVA (08%)</option>
                        <option value="0.00">SIN IVA</option>
                    </select>
                    <span id="lbl-iva">$0.00</span>
                </div>
                <hr>
                <div class="total-row grand-total">
                    <span>TOTAL A PAGAR</span>
                    <span id="lbl-total">$0.00</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Actions Panel -->
    <div class="bottom-actions" style="display: flex; gap: 1rem; padding: 0 1rem 1rem; max-width: 600px;">
        <button class="btn btn-primary" style="flex:1;" id="btn-cotizacion">COTIZACION</button>
        <button class="btn btn-danger" style="flex:1;" id="btn-cancelar">Cancelar</button>
        <button class="btn btn-success" style="flex:1;" id="btn-confirm">Confirmar</button>
    </div>

    <!-- ══ Modal: Motivo de Cancelación ══════════════════════════════════ -->
    <div id="cancel-modal-overlay" style="
        display:none; position:fixed; inset:0;
        background:rgba(0,0,0,0.55); backdrop-filter:blur(3px);
        align-items:center; justify-content:center; z-index:5000;">
        <div style="
            background:white; border-radius:16px;
            width:90%; max-width:460px;
            box-shadow:0 24px 60px rgba(0,0,0,0.25);
            overflow:hidden; animation: fadeInModal .25s ease;">

            <!-- Header -->
            <div style="background:linear-gradient(135deg,#ea4335,#c0392b);
                        color:white; padding:1.5rem 1.75rem;
                        display:flex; align-items:center; gap:.75rem;">
                <span style="font-size:1.6rem;">🚫</span>
                <div>
                    <h3 style="margin:0;font-size:1.1rem;font-weight:700;">Cancelar Orden</h3>
                    <p style="margin:.2rem 0 0;font-size:.85rem;opacity:.88;">Registre el motivo antes de continuar.</p>
                </div>
            </div>

            <!-- Body -->
            <div style="padding:1.75rem;">
                <label style="display:block;font-size:.78rem;font-weight:700;
                              text-transform:uppercase;letter-spacing:.5px;
                              color:#5f6368;margin-bottom:.5rem;">Motivo de cancelación <span style="color:#ea4335;">*</span></label>
                <textarea id="cancel-motivo"
                    rows="4"
                    placeholder="Ej: Cliente cambió de opinión, error en el pedido..."
                    style="width:100%;padding:.8rem 1rem;border:1.5px solid #e0e0e0;
                           border-radius:8px;font-family:inherit;font-size:.95rem;
                           resize:vertical;box-sizing:border-box;
                           transition:border-color .2s;"
                    oninput="document.getElementById('cancel-error').style.display='none';"
                    onfocus="this.style.borderColor='#ea4335';this.style.boxShadow='0 0 0 3px rgba(234,67,53,.12)';"
                    onblur="this.style.borderColor='#e0e0e0';this.style.boxShadow='none';"
                ></textarea>
                <p id="cancel-error" style="display:none;color:#ea4335;
                   font-size:.82rem;margin:.4rem 0 0;">⚠ El motivo es obligatorio.</p>
            </div>

            <!-- Footer -->
            <div style="padding:1rem 1.75rem 1.5rem;
                        display:flex;gap:.75rem;justify-content:flex-end;
                        border-top:1px solid #f0f0f0;background:#fafafa;">
                <button class="btn btn-secondary" onclick="closeCancelModal()" style="min-width:100px;">Volver</button>
                <button class="btn btn-danger"   onclick="confirmCancel()"   style="min-width:130px;">🚫 Cancelar Orden</button>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInModal {
            from { opacity:0; transform:translateY(-12px) scale(.97); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }
    </style>

    <script>
        document.getElementById('btn-cancelar').addEventListener('click', openCancelModal);

        function openCancelModal() {
            // Only open if there is at least one product entered
            const firstProd = document.querySelector('#pos-tbody .product-search');
            const hasItems  = firstProd && firstProd.value.trim() !== '';

            if (!hasItems) {
                // Nothing to cancel: just reset the page
                window.location.reload();
                return;
            }
            document.getElementById('cancel-motivo').value = '';
            document.getElementById('cancel-error').style.display = 'none';
            const overlay = document.getElementById('cancel-modal-overlay');
            overlay.style.display = 'flex';
            setTimeout(() => document.getElementById('cancel-motivo').focus(), 50);
        }

        function closeCancelModal() {
            document.getElementById('cancel-modal-overlay').style.display = 'none';
        }

        function confirmCancel() {
            const motivo = document.getElementById('cancel-motivo').value.trim();
            if (!motivo) {
                document.getElementById('cancel-error').style.display = 'block';
                document.getElementById('cancel-motivo').focus();
                return;
            }

            // Build a lightweight "cancelled sale" record
            // We re-use the existing confirmTransaction flow but posting to a special endpoint
            cancelOrderWithMotivo(motivo);
        }

        function cancelOrderWithMotivo(motivo) {
            // Collect rows
            const rows = document.querySelectorAll('#pos-tbody tr');
            const detalles = [];
            rows.forEach(tr => {
                const prodName = tr.querySelector('.product-search')?.value.trim();
                if (prodName) {
                    const isMetros = tr.dataset.metros === '1';
                    detalles.push({
                        producto_id:          tr.querySelector('.product-id')?.value || null,
                        producto:             prodName,
                        cantidad:             parseFloat(tr.querySelector('.qty')?.value) || 0,
                        costo_unitario:       parseFloat(tr.querySelector('.cost')?.value) || 0,
                        descuento_porcentaje: parseFloat(tr.querySelector('.disc-perc')?.value) || 0,
                        descuento_mxn:        parseFloat(tr.querySelector('.disc-mxn')?.value) || 0,
                        total_linea:          parseFloat(tr.querySelector('.line-total')?.value) || 0,
                        alto:  isMetros ? (parseFloat(tr.querySelector('.alto')?.value)  || null) : null,
                        ancho: isMetros ? (parseFloat(tr.querySelector('.ancho')?.value) || null) : null
                    });
                }
            });

            const clienteIdEl = document.getElementById('client-id');
            const clienteId   = (clienteIdEl && clienteIdEl.value) ? parseInt(clienteIdEl.value, 10) : 1;

            const lbl = (id) => parseFloat(document.getElementById(id).textContent.replace('$','')) || 0;

            const payload = {
                cliente_id:      clienteId,
                subtotal:        lbl('lbl-subtotal'),
                descuento_total: lbl('lbl-desc-total'),
                iva:             lbl('lbl-iva'),
                total:           lbl('lbl-total'),
                estatus:         'cancelada',
                motivo_cancelacion: motivo,
                detalles:        detalles
            };

            fetch('api/save_sale.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    closeCancelModal();
                    alert('✅ Orden cancelada y registrada correctamente.');
                    window.location.reload();
                } else {
                    alert('❌ Error: ' + res.message);
                }
            })
            .catch(() => alert('❌ Error de conexión.'));
        }

        // Close modal on Escape
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeCancelModal();
        });
        // Close on overlay click
        document.getElementById('cancel-modal-overlay').addEventListener('click', function(e) {
            if (e.target === this) closeCancelModal();
        });
    </script>

    <script src="js/pos.js"></script>
</body>
</html>
