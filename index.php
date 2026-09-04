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

    <!-- ══ Modal: Confirmar Venta ═══════════════════════════════════════════ -->
    <div id="confirm-sale-overlay" style="
        display:none; position:fixed; inset:0;
        background:rgba(0,0,0,0.55); backdrop-filter:blur(3px);
        align-items:center; justify-content:center; z-index:5000;">

        <div style="
            background:white; border-radius:16px;
            width:95%; max-width:680px; max-height:92vh;
            box-shadow:0 24px 60px rgba(0,0,0,0.25);
            overflow:hidden; display:flex; flex-direction:column;
            animation: fadeInModal .25s ease;">

            <!-- Header -->
            <div style="background:linear-gradient(135deg,#34a853,#1e7e34);
                        color:white; padding:1.25rem 1.75rem;
                        display:flex; align-items:center; gap:.75rem; flex-shrink:0;">
                <span style="font-size:1.8rem;">🛒</span>
                <div>
                    <h3 style="margin:0;font-size:1.15rem;font-weight:700;">Confirmar Venta</h3>
                    <p style="margin:.2rem 0 0;font-size:.85rem;opacity:.88;">Revisa los detalles antes de procesar.</p>
                </div>
            </div>

            <!-- Scrollable body (printable) -->
            <div id="confirm-sale-printable" style="overflow-y:auto; flex:1; padding:1.75rem;">

                <!-- Print header (shown on print only) -->
                <div class="print-only-header" style="display:none; text-align:center; margin-bottom:1.5rem;">
                    <img src="Logo.jpeg" alt="IMAGEN UNIK" style="max-height:70px;margin-bottom:.5rem;"
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
                    <h2 style="margin:.25rem 0 0;">IMAGEN UNIK</h2>
                    <p style="color:#666;margin:.2rem 0;">Reynosa, Tamaulipas</p>
                </div>

                <!-- Sale info row -->
                <div style="display:flex; gap:1rem; margin-bottom:1.25rem; flex-wrap:wrap;">
                    <div style="flex:1; min-width:140px; background:#f8f9fa; border-radius:10px; padding:.85rem 1rem;">
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#5f6368;">Folio</div>
                        <div id="csm-folio" style="font-weight:700;font-size:1.05rem;color:#1a73e8;margin-top:.2rem;">—</div>
                    </div>
                    <div style="flex:1; min-width:140px; background:#f8f9fa; border-radius:10px; padding:.85rem 1rem;">
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#5f6368;">Cliente</div>
                        <div id="csm-cliente" style="font-weight:600;font-size:.95rem;margin-top:.2rem;">Público General</div>
                    </div>
                    <div style="flex:1; min-width:140px; background:#f8f9fa; border-radius:10px; padding:.85rem 1rem;">
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#5f6368;">Fecha</div>
                        <div id="csm-fecha" style="font-weight:600;font-size:.9rem;margin-top:.2rem;">—</div>
                    </div>
                </div>

                <!-- Items table -->
                <table style="width:100%;border-collapse:collapse;font-size:.88rem;margin-bottom:1.25rem;">
                    <thead>
                        <tr style="background:#f0f4ff;">
                            <th style="padding:.6rem .75rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#5f6368;border-bottom:2px solid #e0e6ff;">Producto</th>
                            <th style="padding:.6rem .75rem;text-align:center;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#5f6368;border-bottom:2px solid #e0e6ff;">Cant.</th>
                            <th style="padding:.6rem .75rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#5f6368;border-bottom:2px solid #e0e6ff;">P.Unit.</th>
                            <th style="padding:.6rem .75rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#5f6368;border-bottom:2px solid #e0e6ff;">Desc.</th>
                            <th style="padding:.6rem .75rem;text-align:right;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#5f6368;border-bottom:2px solid #e0e6ff;">Importe</th>
                        </tr>
                    </thead>
                    <tbody id="csm-items"></tbody>
                </table>

                <!-- Totals -->
                <div style="width:280px;margin-left:auto;">
                    <div style="display:flex;justify-content:space-between;padding:.35rem 0;font-size:.9rem;">
                        <span style="color:#5f6368;">Subtotal:</span><span id="csm-subtotal" style="font-weight:600;">$0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:.35rem 0;font-size:.9rem;">
                        <span style="color:#5f6368;">Descuento:</span><span id="csm-descuento" style="font-weight:600;color:#ea4335;">$0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:.35rem 0;font-size:.9rem;">
                        <span style="color:#5f6368;">IVA:</span><span id="csm-iva" style="font-weight:600;">$0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:.6rem 0 .35rem;margin-top:.4rem;border-top:2px solid #e0e6ff;font-size:1.15rem;font-weight:800;color:#1a73e8;">
                        <span>TOTAL:</span><span id="csm-total">$0.00</span>
                    </div>
                </div>

                <!-- Payment method -->
                <div id="csm-pago-section" style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid #f0f0f0;">
                    <p style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#5f6368;margin:0 0 .75rem;">Método de Pago</p>
                    <div style="display:flex;gap:.75rem;flex-wrap:wrap;" id="payment-options">
                        <label id="pay-efectivo" class="pay-opt" style="flex:1;min-width:120px;display:flex;align-items:center;gap:.5rem;padding:.75rem 1rem;border:2px solid #e0e0e0;border-radius:10px;cursor:pointer;transition:all .2s;font-weight:600;font-size:.9rem;">
                            <input type="radio" name="metodo_pago" value="efectivo" style="accent-color:#34a853;width:16px;height:16px;"> 💵 Efectivo
                        </label>
                        <label id="pay-transferencia" class="pay-opt" style="flex:1;min-width:120px;display:flex;align-items:center;gap:.5rem;padding:.75rem 1rem;border:2px solid #e0e0e0;border-radius:10px;cursor:pointer;transition:all .2s;font-weight:600;font-size:.9rem;">
                            <input type="radio" name="metodo_pago" value="transferencia" style="accent-color:#1a73e8;width:16px;height:16px;"> 🏦 Transferencia
                        </label>
                        <label id="pay-tarjeta" class="pay-opt" style="flex:1;min-width:120px;display:flex;align-items:center;gap:.5rem;padding:.75rem 1rem;border:2px solid #e0e0e0;border-radius:10px;cursor:pointer;transition:all .2s;font-weight:600;font-size:.9rem;">
                            <input type="radio" name="metodo_pago" value="tarjeta" style="accent-color:#fbbc04;width:16px;height:16px;"> 💳 Tarjeta
                        </label>
                    </div>
                    <p id="csm-pago-error" style="display:none;color:#ea4335;font-size:.82rem;margin:.4rem 0 0;">⚠ Selecciona un método de pago.</p>
                </div>

                <!-- Ticket footer (shown on print only) -->
                <div class="print-ticket-footer" style="display:none;">

                    ¡Gracias por su preferencia!<br>

                    IMAGEN UNIK — Imprenta y Publicidad<br>

                    Reynosa, Tamaulipas

                </div> 


            </div>

            <!-- Footer -->
            <div style="padding:1rem 1.75rem 1.5rem;
                        display:flex;gap:.75rem;justify-content:space-between;align-items:center;
                        border-top:1px solid #f0f0f0;background:#fafafa;flex-shrink:0;">
                <button class="btn btn-secondary" onclick="printSaleTicket()" style="min-width:110px;">🖨️ Imprimir</button>
                <div style="display:flex;gap:.75rem;">
                    <button class="btn btn-secondary" onclick="closeConfirmSaleModal()" style="min-width:100px;">Cancelar</button>
                    <button class="btn btn-success" id="csm-ok-btn" onclick="processSale()" style="min-width:150px;font-weight:700;">✅ Confirmar Venta</button>
                </div>
            </div>
        </div>
    </div>
<style>
    .pay-opt:has(input:checked) {
        border-color: #34a853 !important;
        background: #e6f4ea;
    }
    
    @media print {
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        body * {
            visibility: hidden !important;
        }
        
        /* Forzar fuente Arial, negrita extrema y negro absoluto */
        #confirm-sale-printable, 
        #confirm-sale-printable * {
            visibility: visible !important;
            color: #000000 !important;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif !important;
            font-weight: 900 !important;
            opacity: 1 !important;
            text-shadow: none !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #confirm-sale-printable {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 80mm !important;
            max-width: 80mm !important;
            padding: 4mm 5mm 0mm 5mm !important;
            box-sizing: border-box !important;
            background: #ffffff !important;
            font-size: 13px !important;
            line-height: 1.35 !important;
            overflow: visible !important;
        }

        .print-only-header {
            display: block !important;
            text-align: center !important;
            margin-bottom: 10px !important;
            padding-bottom: 6px !important;
            border-bottom: 2px dashed #000000 !important;
        }
        .print-only-header img {
            max-height: 50px !important;
            margin-bottom: 4px !important;
            filter: contrast(200%) grayscale(100%) !important;
        }
        .print-only-header h2 {
            font-size: 16px !important;
            font-weight: 900 !important;
            margin: 0 !important;
        }
        .print-only-header p {
            font-size: 12px !important;
            margin: 2px 0 0 !important;
        }

        /* Info grid layout convertida a ticket */
        #confirm-sale-printable > div:nth-of-type(2) {
            display: block !important;
            margin-bottom: 10px !important;
            padding-bottom: 6px !important;
            border-bottom: 2px dashed #000000 !important;
        }
        #confirm-sale-printable > div:nth-of-type(2) > div {
            background: transparent !important;
            padding: 2px 0 !important;
            display: flex !important;
            justify-content: space-between !important;
            font-size: 12px !important;
            border-radius: 0 !important;
            min-width: 0 !important;
        }

        /* Tabla de productos */
        #confirm-sale-printable table {
            width: 100% !important;
            font-size: 12px !important;
            border-collapse: collapse !important;
            margin-bottom: 10px !important;
        }
        #confirm-sale-printable table thead tr {
            background: transparent !important;
        }
        #confirm-sale-printable table th {
            font-size: 12px !important;
            font-weight: 900 !important;
            padding: 4px 2px !important;
            color: #000000 !important;
            border-bottom: 2px solid #000000 !important;
        }
        #confirm-sale-printable table td {
            padding: 4px 2px !important;
            font-size: 12px !important;
            color: #000000 !important;
            border-bottom: 1px dashed #000000 !important;
        }
        
        /* Descripción e información secundaria de producto */
        #confirm-sale-printable table small {
            color: #000000 !important;
            font-size: 11px !important;
            display: block !important;
            font-weight: 900 !important;
            margin-top: 1px !important;
            opacity: 1 !important;
        }

        /* Totales */
        #confirm-sale-printable > div:nth-of-type(4) {
            width: 100% !important;
            margin-left: 0 !important;
            padding-top: 6px !important;
            border-top: 2px dashed #000000 !important;
        }
        #confirm-sale-printable > div:nth-of-type(4) > div {
            font-size: 13px !important;
            padding: 3px 0 !important;
        }
        #confirm-sale-printable > div:nth-of-type(4) > div span {
            color: #000000 !important;
            font-weight: 900 !important;
        }
        #confirm-sale-printable > div:nth-of-type(4) > div:last-child {
            border-top: 2px solid #000000 !important;
            font-size: 15px !important;
            font-weight: 900 !important;
            padding-top: 6px !important;
            margin-top: 6px !important;
        }

        #csm-pago-section {
            display: none !important;
        }
        
        /* Fuerza la visibilidad del footer sobre el style="display:none;" en línea */
        div.print-ticket-footer,
        #confirm-sale-printable .print-ticket-footer {
            display: block !important;
            visibility: visible !important;
            text-align: center !important;
            margin-top: 12px !important;
            padding-top: 8px !important;
            border-top: 2px dashed #000000 !important;
            font-size: 12px !important;
            font-weight: 900 !important;
            color: #000000 !important;
            margin-bottom: 0 !important;
        }

        /* Espaciador de 55mm pegado DIRECTAMENTE DEBAJO DEL FOOTER para evitar el corte */
        div.print-ticket-footer::after,
        #confirm-sale-printable .print-ticket-footer::after {
            content: "" !important;
            display: block !important;
            height: 70mm !important; /* Avance de papel para librar la cuchilla */
            width: 100% !important;
            clear: both !important;
        }
    }

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
                    showAlert('Orden cancelada y registrada correctamente.', 'success', 'Orden Cancelada').then(() => {
                        window.location.reload();
                    });
                } else {
                    showAlert(res.message || 'Error al cancelar la orden.', 'error', 'Error');
                }
            })
            .catch(() => showAlert('No se pudo conectar con el servidor. Verifique la conexión.', 'error', 'Error de Conexión'));
        }

        // Close modal on Escape
        document.addEventListener('keydown', e => {
            const authOverlay = document.getElementById('auth-modal-overlay');
            if (authOverlay && authOverlay.style.display === 'flex') {
                if (e.key === 'Escape') {
                    closeAuthDeleteModal();
                } else if (e.key === 'Enter') {
                    confirmAuthDelete();
                }
                return;
            }
            const quoteObsOverlay = document.getElementById('quote-obs-modal-overlay');
            if (quoteObsOverlay && quoteObsOverlay.style.display === 'flex') {
                if (e.key === 'Escape') {
                    closeQuoteObsModal();
                }
                return;
            }
            if (e.key === 'Escape') closeCancelModal();
        });
        // Close on overlay click (null guards: some modals are defined later in the DOM)
        const _cancelOvl = document.getElementById('cancel-modal-overlay');
        if (_cancelOvl) _cancelOvl.addEventListener('click', function(e) {
            if (e.target === this) closeCancelModal();
        });
        const _authOvl = document.getElementById('auth-modal-overlay');
        if (_authOvl) _authOvl.addEventListener('click', function(e) {
            if (e.target === this) closeAuthDeleteModal();
        });
        const _quoteObsOvl = document.getElementById('quote-obs-modal-overlay');
        if (_quoteObsOvl) _quoteObsOvl.addEventListener('click', function(e) {
            if (e.target === this) closeQuoteObsModal();
        });
    </script>

    <!-- ══ MODAL DE AUTORIZACIÓN PARA ELIMINAR ══════════════════════════════ -->
    <div class="modal-overlay" id="auth-modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:16px; width:90%; max-width:400px; padding:1.5rem; box-shadow:0 10px 30px rgba(0,0,0,0.25); animation:fadeInModal 0.2s ease;">
            <div style="text-align:center; margin-bottom:1rem;">
                <div style="font-size:2.5rem; margin-bottom:0.3rem;">🔒</div>
                <h3 style="margin:0; font-size:1.15rem; color:var(--text-main); font-weight:700;">Autorización Requerida</h3>
                <p style="margin:0.3rem 0 0; font-size:0.85rem; color:var(--text-muted);">Ingresa la contraseña para quitar este registro de la venta.</p>
            </div>

            <div style="margin-bottom:1.25rem;">
                <input type="password" id="auth-delete-password" placeholder="Contraseña de autorización" style="width:100%; padding:0.7rem 1rem; border:2px solid var(--border); border-radius:10px; font-size:1rem; text-align:center; outline:none; transition:border-color 0.2s;">
                <p id="auth-delete-error" style="display:none; color:#ea4335; font-size:0.82rem; margin:0.4rem 0 0; text-align:center; font-weight:600;">⚠ Contraseña incorrecta.</p>
            </div>

            <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeAuthDeleteModal()" style="flex:1; padding:0.6rem;">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-confirm-delete-auth" onclick="confirmAuthDelete()" style="flex:1; padding:0.6rem; background:#ea4335; border-color:#ea4335; color:white; font-weight:700;">Eliminar</button>
            </div>
        </div>
    </div>

    <!-- ══ MODAL DE OBSERVACIONES PARA COTIZACIÓN ══════════════════════════════ -->
    <div class="modal-overlay" id="quote-obs-modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; border-radius:16px; width:90%; max-width:440px; padding:1.5rem; box-shadow:0 10px 30px rgba(0,0,0,0.25); animation:fadeInModal 0.2s ease;">
            <div style="text-align:center; margin-bottom:1rem;">
                <div style="font-size:2.5rem; margin-bottom:0.3rem;">📋</div>
                <h3 style="margin:0; font-size:1.2rem; color:var(--text-main); font-weight:700;">Cuadro de observaciones</h3>
                <p style="margin:0.3rem 0 0; font-size:0.88rem; color:var(--text-muted);">Agrega Observaciones o Notas a la cotizacion</p>
            </div>

            <div style="margin-bottom:1.25rem;">
                <textarea id="quote-obs-text" rows="4" placeholder="Sin observaciones adicionales" style="width:100%; padding:0.75rem 1rem; border:2px solid var(--border); border-radius:10px; font-size:0.95rem; font-family:inherit; outline:none; resize:vertical; transition:border-color 0.2s;"></textarea>
            </div>

            <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeQuoteObsModal()" style="flex:1; padding:0.6rem;">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-confirm-quote-obs" onclick="confirmSaveQuoteWithObs()" style="flex:1; padding:0.6rem; background:#34a853; border-color:#34a853; color:white; font-weight:700;">Confirmar</button>
            </div>
        </div>
    </div>

    <!-- Custom Popup & Toast Notifications -->
    <script src="js/notifications.js"></script>
    <script src="js/pos.js"></script>
</body>
</html>
