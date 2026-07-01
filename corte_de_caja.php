<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corte de Caja — IMAGEN UNIK</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* ── Page reset ────────────────────────────────────────────────────── */
        body { min-height: 100vh; display: flex; flex-direction: column; overflow-x: hidden; }

        /* ── Main 2-column grid ─────────────────────────────────────────────── */
        .corte-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            grid-template-rows: auto;
            gap: 1rem;
            padding: 1rem 1.25rem 2rem;
            flex: 1;
            align-items: start;
        }

        /* ── Left column ────────────────────────────────────────────────────── */
        .col-left {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            min-width: 0;
        }

        /* ── Right column ───────────────────────────────────────────────────── */
        .col-right {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            position: sticky;
            top: 1rem;
        }

        /* ── Card ───────────────────────────────────────────────────────────── */
        .corte-card {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            border-radius: 14px;
            padding: 1.25rem 1.4rem;
        }
        .corte-card h2 {
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--unik-magenta);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        /* ── Period selector ────────────────────────────────────────────────── */
        .period-row {
            display: flex;
            gap: 0.75rem;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .period-row .field { display: flex; flex-direction: column; gap: 0.25rem; }
        .period-row label  { font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .period-row input[type="date"] {
            padding: 0.45rem 0.75rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.9rem;
            background: white;
            color: var(--text-main);
            transition: border-color 0.2s;
        }
        .period-row input[type="date"]:focus { outline: none; border-color: var(--primary); }

        /* ── KPI strip ──────────────────────────────────────────────────────── */
        .kpi-strip {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.65rem;
            margin-top: 1rem;
        }
        .kpi-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #eef1fb 100%);
            border: 1px solid rgba(26,115,232,0.1);
            border-radius: 10px;
            padding: 0.75rem 0.9rem;
            text-align: center;
        }
        .kpi-box .kpi-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.04em; }
        .kpi-box .kpi-value { font-size: 1.15rem; font-weight: 800; color: var(--primary); margin-top: 0.2rem; }
        .kpi-box.accent { background: linear-gradient(135deg, #fff0f8, #ffe0f0); border-color: rgba(228,0,127,0.15); }
        .kpi-box.accent .kpi-value { color: var(--unik-magenta); }

        /* ── Ventas mini-table ──────────────────────────────────────────────── */
        .ventas-table-wrap {
            max-height: 200px; overflow-y: auto;
            margin-top: 0.9rem; border-radius: 8px;
            border: 1px solid var(--border);
        }
        .ventas-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
        .ventas-table th {
            background: #f1f3f8; font-weight: 600;
            color: var(--text-muted); padding: 0.45rem 0.75rem;
            text-align: left; position: sticky; top: 0;
        }
        .ventas-table td { padding: 0.4rem 0.75rem; border-bottom: 1px solid var(--border); }
        .ventas-table tr:last-child td { border-bottom: none; }
        .ventas-table tr:hover td { background: rgba(26,115,232,0.04); }
        .ventas-table .right { text-align: right; }
        #ventas-empty { text-align: center; color: var(--text-muted); padding: 1.5rem; font-size: 0.88rem; }

        /* ── Denominaciones grid removido ── */

        /* ── Money input ────────────────────────────────────────────────────── */
        .money-input-wrap { position: relative; }
        .money-input-wrap .currency-prefix {
            position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%);
            font-weight: 700; color: var(--text-muted); font-size: 1rem;
        }
        .money-input-wrap input {
            width: 100%; padding: 0.65rem 0.9rem 0.65rem 2rem;
            font-size: 1.2rem; font-weight: 700; font-family: inherit;
            border: 2px solid var(--border); border-radius: 10px;
            color: var(--text-main); background: white;
            transition: border-color 0.2s;
        }
        .money-input-wrap input:focus { outline: none; border-color: var(--primary); }

        /* ── Gastos ─────────────────────────────────────────────────────────── */
        .gastos-list { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 0.75rem; }
        .gasto-row {
            display: grid;
            grid-template-columns: 1fr 110px 34px;
            gap: 0.5rem;
            align-items: center;
            animation: fadeIn 0.2s ease;
        }
        .gasto-row input {
            padding: 0.45rem 0.65rem;
            border: 1.5px solid var(--border);
            border-radius: 7px;
            font-family: inherit; font-size: 0.88rem; background: white;
            transition: border-color 0.2s;
        }
        .gasto-row input:focus { outline: none; border-color: var(--primary); }
        .gasto-row input.monto-input { text-align: right; font-weight: 600; }
        .btn-del-gasto {
            background: none; border: 1.5px solid var(--danger);
            color: var(--danger); border-radius: 7px; width: 34px; height: 34px;
            cursor: pointer; font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .btn-del-gasto:hover { background: var(--danger); color: white; }
        .gastos-total-row {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 0.5rem; border-top: 1px dashed var(--border);
        }
        .gastos-total-row span { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; }
        .gastos-total-row strong { font-size: 1.1rem; color: var(--danger); }

        /* ── Resumen table ──────────────────────────────────────────────────── */
        .resumen-table { width: 100%; border-collapse: collapse; }
        .resumen-table td { padding: 0.55rem 0.4rem; border-bottom: 1px dashed var(--border); font-size: 0.9rem; }
        .resumen-table tr:last-child td { border-bottom: none; }
        .resumen-table .label { color: var(--text-muted); }
        .resumen-table .value { text-align: right; font-weight: 600; }
        .resumen-table .separator td { border-bottom: 2px solid var(--text-main); padding: 0; }

        /* ── Diferencia box ─────────────────────────────────────────────────── */
        .diferencia-box {
            border-radius: 12px; padding: 1rem 1.2rem;
            text-align: center; border: 2px solid;
            margin-top: 0.8rem;
        }
        .diferencia-box.sobrante { background: linear-gradient(135deg,#f0fff4,#e0f7e9); border-color: var(--success); }
        .diferencia-box.faltante { background: linear-gradient(135deg,#fff5f5,#ffe0e0); border-color: var(--danger);  }
        .diferencia-box.neutro   { background: linear-gradient(135deg,#f8f9fa,#e9ecef); border-color: var(--border);  }
        .diferencia-box .dif-label  { font-size: 0.73rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 0.3rem; }
        .diferencia-box.sobrante .dif-label { color: var(--success); }
        .diferencia-box.faltante .dif-label { color: var(--danger);  }
        .diferencia-box .dif-value  { font-size: 2.2rem; font-weight: 800; line-height: 1; }
        .diferencia-box.sobrante .dif-value { color: var(--success); }
        .diferencia-box.faltante .dif-value { color: var(--danger);  }
        .diferencia-box.neutro   .dif-value { color: var(--text-muted); }
        .diferencia-box .dif-status { margin-top: 0.35rem; font-size: 0.82rem; font-weight: 600; }

        /* ── Notas ──────────────────────────────────────────────────────────── */
        .notas-textarea {
            width: 100%; min-height: 70px; resize: vertical;
            padding: 0.6rem 0.85rem; border: 1.5px solid var(--border); border-radius: 9px;
            font-family: inherit; font-size: 0.9rem; background: white;
            transition: border-color 0.2s; color: var(--text-main);
        }
        .notas-textarea:focus { outline: none; border-color: var(--primary); }

        /* ── Actions ────────────────────────────────────────────────────────── */
        .actions-row { display: flex; gap: 0.65rem; flex-wrap: wrap; }
        .actions-row .btn { flex: 1; justify-content: center; font-size: 0.88rem; padding: 0.7rem 0.5rem; }

        /* ── Print ──────────────────────────────────────────────────────────── */
        #print-area { display: none; }
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area {
                display: block !important;
                position: fixed; top: 0; left: 0; width: 100%;
                padding: 1.5rem 2rem; font-family: 'Courier New', monospace; background: white;
            }
            .no-print { display: none !important; }
        }
        .print-ticket { max-width: 400px; margin: 0 auto; font-size: 0.85rem; }
        .print-ticket h1 { font-size: 1.1rem; text-align: center; margin-bottom: 0.3rem; }
        .print-ticket .sub { text-align: center; color: #666; margin-bottom: 1rem; font-size: 0.8rem; }
        .print-ticket hr { border: none; border-top: 1px dashed #999; margin: 0.7rem 0; }
        .print-ticket .pr { display: flex; justify-content: space-between; padding: 0.2rem 0; }
        .print-ticket .pr.bold { font-weight: 700; }
        .print-ticket .pr.big  { font-size: 1.1rem; font-weight: 800; }
        .print-ticket .pr.green { color: #2d7a3a; }
        .print-ticket .pr.red   { color: #c0392b; }
        .print-ticket .footer   { text-align: center; margin-top: 1rem; font-size: 0.75rem; color: #888; }

        /* ── Responsive collapse ────────────────────────────────────────────── */
        @media (max-width: 900px) {
            .corte-grid { grid-template-columns: 1fr; }
            .col-right { position: static; }
        }
    </style>
</head>
<body>

    <!-- ══ HEADER ══════════════════════════════════════════════════════════ -->
    <header class="pos-header glass" style="margin:0.75rem 1.25rem 0; flex-shrink:0;">
        <div class="logo-container" style="display:flex; align-items:center; gap:0.9rem;">
            <img src="Logo.jpeg" alt="IMAGEN UNIK Logo" style="height:50px; object-fit:contain;"
                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
            <div>
                <h1 style="margin:0; font-size:1.2rem; font-weight:700; color:var(--text-main);">PUNTO DE VENTA IMAGEN UNIK</h1>
                <p style="margin:0; font-size:0.8rem; color:var(--text-muted);">Corte de Caja — Reynosa, Tamaulipas</p>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:0.9rem;">
            <div id="clock" style="font-size:0.88rem; color:var(--text-muted);"></div>
            <a href="index.php" class="btn btn-secondary no-print" style="font-size:0.82rem; padding:0.5rem 1rem;">← Volver</a>
        </div>
    </header>

    <!-- ══ MAIN 2-COLUMN GRID ══════════════════════════════════════════════ -->
    <div class="corte-grid">

        <!-- ════════════════════════════════════════════════════════════════ -->
        <!-- COLUMNA IZQUIERDA                                               -->
        <!-- ════════════════════════════════════════════════════════════════ -->
        <div class="col-left">

            <!-- 1 · PERÍODO + VENTAS ──────────────────────────────────── -->
            <div class="corte-card">
                <h2>📅 Período de ventas</h2>
                <div class="period-row">
                    <div class="field">
                        <label>Fecha inicio</label>
                        <input type="date" id="fecha-inicio">
                    </div>
                    <div class="field">
                        <label>Fecha fin</label>
                        <input type="date" id="fecha-fin">
                    </div>
                    <button class="btn btn-primary no-print" id="btn-cargar" style="align-self:flex-end; padding:0.5rem 1.2rem;">
                        Cargar ventas
                    </button>
                </div>

                <!-- KPI strip -->
                <div class="kpi-strip">
                    <div class="kpi-box">
                        <div class="kpi-label">Nº Ventas</div>
                        <div class="kpi-value" id="kpi-num-ventas">—</div>
                    </div>
                    <div class="kpi-box">
                        <div class="kpi-label">Subtotal</div>
                        <div class="kpi-value" id="kpi-subtotal">—</div>
                    </div>
                    <div class="kpi-box">
                        <div class="kpi-label">Descuentos</div>
                        <div class="kpi-value" id="kpi-descuentos">—</div>
                    </div>
                    <div class="kpi-box">
                        <div class="kpi-label">IVA 16%</div>
                        <div class="kpi-value" id="kpi-iva">—</div>
                    </div>
                    <div class="kpi-box accent">
                        <div class="kpi-label">Total Ventas</div>
                        <div class="kpi-value" id="kpi-total-ventas">—</div>
                    </div>
                </div>

                <!-- Ventas list -->
                <div class="ventas-table-wrap" id="ventas-wrap" style="display:none;">
                    <table class="ventas-table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Fecha / Hora</th>
                                <th>Cliente</th>
                                <th class="right">Total</th>
                            </tr>
                        </thead>
                        <tbody id="ventas-tbody"></tbody>
                    </table>
                </div>
                <div id="ventas-empty" style="display:none; text-align:center; color:var(--text-muted); padding:1rem; font-size:0.88rem;">
                    Sin ventas para el período seleccionado.
                </div>
            </div>



        </div><!-- /.col-left -->

        <!-- ════════════════════════════════════════════════════════════════ -->
        <!-- COLUMNA DERECHA                                                 -->
        <!-- ════════════════════════════════════════════════════════════════ -->
        <div class="col-right">

            <!-- 3 · FONDO INICIAL ─────────────────────────────────────── -->
            <div class="corte-card">
                <h2>💵 Fondo inicial de caja</h2>
                <p style="font-size:0.8rem; color:var(--text-muted); margin-bottom:0.75rem;">
                    Efectivo con que se abrió la caja al inicio del turno.
                </p>
                <div class="money-input-wrap">
                    <span class="currency-prefix">$</span>
                    <input type="number" id="fondo-inicial" value="0" min="0" step="0.01" placeholder="0.00">
                </div>
            </div>

            <!-- 3b · EFECTIVO CONTADO ──────────────────────────────────── -->
            <div class="corte-card">
                <h2>💰 Efectivo contado en caja</h2>
                <p style="font-size:0.8rem; color:var(--text-muted); margin-bottom:0.75rem;">
                    Total de efectivo físico real en caja al momento del corte.
                </p>
                <div class="money-input-wrap">
                    <span class="currency-prefix">$</span>
                    <input type="number" id="efectivo-contado" value="0" min="0" step="0.01" placeholder="0.00">
                </div>
            </div>

            <!-- 3c · MOVIMIENTOS EXTRA (INGRESOS/RETIROS) ──────────────── -->
            <div class="corte-card no-print">
                <h2>💸 Movimientos Extra</h2>
                <p style="font-size:0.8rem; color:var(--text-muted); margin-bottom:0.75rem;">
                    Ingresos adicionales o retiros de la caja.
                </p>
                <div id="movimientos-list" style="display:flex; flex-direction:column; gap:0.5rem; margin-bottom:0.75rem;">
                    <div style="text-align:center; color:var(--text-muted); font-size:0.85rem;">Ningún movimiento registrado.</div>
                </div>
                <button class="btn btn-secondary" onclick="abrirModalMovimiento()" style="width:100%; font-size:0.85rem; padding:0.4rem; border-style:dashed;">+ Añadir Movimiento</button>
            </div>

            <!-- 4 · RESUMEN DE CIERRE ─────────────────────────────────── -->
            <div class="corte-card">
                <h2>📊 Resumen de cierre</h2>
                <table class="resumen-table">
                    <tr>
                        <td class="label">Fondo inicial</td>
                        <td class="value" id="r-fondo">$0.00</td>
                    </tr>
                    <tr>
                        <td class="label">+ Total ventas</td>
                        <td class="value" id="r-ventas">$0.00</td>
                    </tr>
                    <tr id="tr-ingresos">
                        <td class="label" style="color:var(--success);">+ Ingresos extras</td>
                        <td class="value" id="r-ingresos" style="color:var(--success);">$0.00</td>
                    </tr>
                    <tr id="tr-retiros">
                        <td class="label" style="color:var(--danger);">- Retiros (Gastos)</td>
                        <td class="value" id="r-retiros" style="color:var(--danger);">$0.00</td>
                    </tr>
                    <tr class="separator"><td></td><td></td></tr>
                    <tr>
                        <td class="label"><strong>Efectivo esperado</strong></td>
                        <td class="value"><strong id="r-esperado">$0.00</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Efectivo contado</td>
                        <td class="value" id="r-contado">$0.00</td>
                    </tr>
                </table>

                <div class="diferencia-box neutro" id="dif-box">
                    <div class="dif-label" id="dif-label">Diferencia</div>
                    <div class="dif-value" id="dif-value">$0.00</div>
                    <div class="dif-status" id="dif-status">Ingresa los datos para calcular</div>
                </div>
            </div>

            <!-- 6 · NOTAS + ACCIONES ─────────────────────────────────── -->
            <div class="corte-card no-print">
                <h2>📝 Notas del turno</h2>
                <textarea class="notas-textarea" id="notas" placeholder="Observaciones, incidencias..."></textarea>
                <div class="actions-row" style="margin-top:0.9rem;">
                    <button class="btn btn-secondary" onclick="window.location.href='index.php'">Cancelar</button>
                    <button class="btn btn-primary"  id="btn-imprimir"     onclick="imprimirCorte()">🖨 Imprimir</button>
                    <button class="btn btn-success"  id="btn-cerrar-corte">✔ Cerrar</button>
                </div>
            </div>

        </div><!-- /.col-right -->

    </div><!-- /.corte-grid -->

    <!-- ══ MODAL MOVIMIENTO ════════════════════════════════════════════════ -->
    <div class="overlay" id="modal-movimiento" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
        <div style="background:white; padding:1.5rem; border-radius:12px; width:90%; max-width:400px; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
            <h3 style="margin-top:0; color:var(--text-main); font-size:1.1rem; border-bottom:1px solid var(--border); padding-bottom:0.5rem; margin-bottom:1rem;">Registrar Movimiento</h3>
            
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text-muted); margin-bottom:0.3rem;">Tipo de Movimiento</label>
                <select id="modal-mov-tipo" style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px; font-family:inherit; font-size:1rem;">
                    <option value="ingreso">Ingreso (Añadir efectivo)</option>
                    <option value="retiro">Retiro (Gasto / Salida)</option>
                </select>
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text-muted); margin-bottom:0.3rem;">Monto</label>
                <div class="money-input-wrap">
                    <span class="currency-prefix" style="left:0.6rem;">$</span>
                    <input type="number" id="modal-mov-monto" min="0" step="0.01" placeholder="0.00" style="padding-left:1.5rem; font-size:1.1rem;">
                </div>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text-muted); margin-bottom:0.3rem;">Motivo / Descripción</label>
                <textarea id="modal-mov-desc" rows="3" style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px; font-family:inherit; font-size:0.9rem; resize:vertical;"></textarea>
            </div>

            <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                <button class="btn btn-secondary" onclick="cerrarModalMovimiento()" style="padding:0.6rem 1rem;">Cancelar</button>
                <button class="btn btn-primary" onclick="guardarMovimiento()" id="btn-save-mov" style="padding:0.6rem 1rem;">Guardar</button>
            </div>
        </div>
    </div>

    <!-- ══ PRINT AREA ══════════════════════════════════════════════════════ -->
    <div id="print-area">
        <div class="print-ticket" id="ticket-content"></div>
    </div>

    <script>
    // ── Helpers ───────────────────────────────────────────────────────────
    const fmt = n => '$' + parseFloat(n || 0).toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2});

    let ventasData   = { totales:{num_ventas:0,subtotal_ventas:0,descuentos_ventas:0,iva_ventas:0,total_ventas:0}, ventas:[], movimientos:[] };
    
    // ── Movimientos de Caja Modal ─────────────────────────────────────────
    function abrirModalMovimiento() {
        document.getElementById('modal-mov-tipo').value = 'retiro';
        document.getElementById('modal-mov-monto').value = '';
        document.getElementById('modal-mov-desc').value = '';
        document.getElementById('modal-movimiento').style.display = 'flex';
        setTimeout(() => document.getElementById('modal-mov-monto').focus(), 100);
    }

    function cerrarModalMovimiento() {
        document.getElementById('modal-movimiento').style.display = 'none';
    }

    async function guardarMovimiento() {
        const tipo = document.getElementById('modal-mov-tipo').value;
        const monto = parseFloat(document.getElementById('modal-mov-monto').value) || 0;
        const desc = document.getElementById('modal-mov-desc').value.trim();

        if (monto <= 0 || !desc) {
            alert('Por favor, ingresa un monto mayor a 0 y una descripción.');
            return;
        }

        const btn = document.getElementById('btn-save-mov');
        btn.textContent = 'Guardando...'; btn.disabled = true;

        try {
            const res = await fetch('api/save_movimiento.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ tipo, monto, descripcion: desc })
            });
            const data = await res.json();
            if (data.success) {
                cerrarModalMovimiento();
                cargarVentas(); // Reload the data
            } else {
                alert('Error: ' + data.message);
            }
        } catch(e) { alert('Error de red'); }
        btn.textContent = 'Guardar'; btn.disabled = false;
    }

    async function eliminarMovimiento(id) {
        if (!confirm('¿Eliminar este movimiento?')) return;
        try {
            const res = await fetch('api/delete_movimiento.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const data = await res.json();
            if (data.success) {
                cargarVentas();
            } else {
                alert('Error: ' + data.message);
            }
        } catch(e) { alert('Error de red'); }
    }

    function renderMovimientos() {
        const list = document.getElementById('movimientos-list');
        list.innerHTML = '';
        if (!ventasData.movimientos || ventasData.movimientos.length === 0) {
            list.innerHTML = '<div style="text-align:center; color:var(--text-muted); font-size:0.85rem;">Ningún movimiento registrado.</div>';
            return;
        }

        ventasData.movimientos.forEach(m => {
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.justifyContent = 'space-between';
            div.style.alignItems = 'center';
            div.style.padding = '0.5rem';
            div.style.border = '1px solid var(--border)';
            div.style.borderRadius = '8px';
            div.style.background = 'rgba(255,255,255,0.6)';
            div.style.fontSize = '0.85rem';
            
            const isIngreso = m.tipo === 'ingreso';
            const color = isIngreso ? 'var(--success)' : 'var(--danger)';
            const sign = isIngreso ? '+' : '-';
            
            div.innerHTML = `
                <div style="flex:1; overflow:hidden;">
                    <strong style="color:${color}; text-transform:uppercase; font-size:0.75rem;">${isIngreso ? 'Ingreso' : 'Retiro'}</strong><br>
                    <span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:inline-block; width:100%; color:var(--text-main); margin-top:2px;">${m.descripcion}</span>
                </div>
                <div style="font-weight:700; color:${color}; padding:0 0.5rem;">${sign}${fmt(m.monto)}</div>
                <button onclick="eliminarMovimiento(${m.id})" style="background:none; border:none; color:var(--danger); cursor:pointer; font-size:1.1rem; padding:0 0.3rem;" title="Eliminar">×</button>
            `;
            list.appendChild(div);
        });
    }

    function getMovimientosTotales() {
        let ingresos = 0, retiros = 0;
        if (ventasData.movimientos) {
            ventasData.movimientos.forEach(m => {
                if (m.tipo === 'ingreso') ingresos += parseFloat(m.monto);
                else retiros += parseFloat(m.monto);
            });
        }
        return { ingresos, retiros };
    }

    // ── Resumen en tiempo real ────────────────────────────────────────────
    function recalcResumen() {
        const fondo     = parseFloat(document.getElementById('fondo-inicial').value) || 0;
        const totalVent = parseFloat(ventasData.totales.total_ventas) || 0;
        const movs      = getMovimientosTotales();
        const contado   = parseFloat(document.getElementById('efectivo-contado').value) || 0;
        
        const esperado  = fondo + totalVent + movs.ingresos - movs.retiros;
        const diferencia= contado - esperado;

        document.getElementById('r-fondo').textContent    = fmt(fondo);
        document.getElementById('r-ventas').textContent   = fmt(totalVent);
        document.getElementById('r-ingresos').textContent = fmt(movs.ingresos);
        document.getElementById('r-retiros').textContent  = fmt(movs.retiros);
        document.getElementById('r-esperado').textContent = fmt(esperado);
        document.getElementById('r-contado').textContent  = fmt(contado);

        const difBox   = document.getElementById('dif-box');
        const difValue = document.getElementById('dif-value');
        const difStatus= document.getElementById('dif-status');
        const difLabel = document.getElementById('dif-label');

        difValue.textContent = fmt(Math.abs(diferencia));
        difBox.className = 'diferencia-box ';
        if (diferencia > 0.005) {
            difBox.className += 'sobrante';
            difLabel.textContent  = '⬆ Sobrante';
            difStatus.textContent = 'Hay más efectivo del esperado';
        } else if (diferencia < -0.005) {
            difBox.className += 'faltante';
            difLabel.textContent  = '⬇ Faltante';
            difStatus.textContent = 'Hay menos efectivo del esperado';
        } else {
            difBox.className += 'neutro';
            difLabel.textContent  = '✓ Cuadrado';
            difStatus.textContent = 'La caja cuadra perfectamente';
        }
    }

    // ── Cargar ventas ─────────────────────────────────────────────────────
    function cargarVentas() {
        const inicio = document.getElementById('fecha-inicio').value;
        const fin    = document.getElementById('fecha-fin').value;
        if (!inicio || !fin) { alert('Selecciona las fechas.'); return; }

        const btn = document.getElementById('btn-cargar');
        btn.textContent = 'Cargando…';
        btn.disabled = true;

        fetch(`api/get_corte_data.php?fecha_inicio=${inicio}&fecha_fin=${fin}`)
            .then(r => r.json())
            .then(data => {
                btn.textContent = 'Cargar ventas';
                btn.disabled = false;
                if (!data.success) { alert('Error: ' + data.message); return; }

                ventasData = data;
                const t = data.totales;
                document.getElementById('kpi-num-ventas').textContent   = t.num_ventas;
                document.getElementById('kpi-subtotal').textContent     = fmt(t.subtotal_ventas);
                document.getElementById('kpi-descuentos').textContent   = fmt(t.descuentos_ventas);
                document.getElementById('kpi-iva').textContent          = fmt(t.iva_ventas);
                document.getElementById('kpi-total-ventas').textContent = fmt(t.total_ventas);

                const tbody = document.getElementById('ventas-tbody');
                tbody.innerHTML = '';
                const wrap  = document.getElementById('ventas-wrap');
                const empty = document.getElementById('ventas-empty');

                if (data.ventas.length === 0) {
                    wrap.style.display  = 'none';
                    empty.style.display = 'block';
                } else {
                    empty.style.display = 'none';
                    wrap.style.display  = 'block';
                    data.ventas.forEach(v => {
                        const tr = document.createElement('tr');
                        const dt = new Date(v.fecha_hora.replace(' ','T'));
                        const hora  = dt.toLocaleTimeString('es-MX', {hour:'2-digit',minute:'2-digit'});
                        const fecha = dt.toLocaleDateString('es-MX', {day:'2-digit',month:'2-digit',year:'numeric'});
                        const folioText = 'F-' + String(v.folio || v.id).padStart(4, '0');
                        tr.innerHTML = `
                            <td>${folioText}</td>
                            <td>${fecha} ${hora}</td>
                            <td>${v.cliente}</td>
                            <td class="right">${fmt(v.total)}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }
                renderMovimientos();
                recalcResumen();
            })
            .catch(err => {
                btn.textContent = 'Cargar ventas';
                btn.disabled = false;
                console.error(err);
                alert('Error de conexión.');
            });
    }

    // ── Cerrar corte ──────────────────────────────────────────────────────
    document.getElementById('btn-cerrar-corte').addEventListener('click', () => {
        if (!ventasData.ventas.length && (!ventasData.movimientos || !ventasData.movimientos.length)) {
            if (!confirm('No hay ventas ni movimientos. ¿Seguro que deseas hacer el corte de caja en cero?')) return;
        }

        const movs = getMovimientosTotales();
        const fondo   = parseFloat(document.getElementById('fondo-inicial').value) || 0;
        const contado = parseFloat(document.getElementById('efectivo-contado').value) || 0;
        const totalVent = parseFloat(ventasData.totales.total_ventas) || 0;
        const esperado  = fondo + totalVent + movs.ingresos - movs.retiros;
        const diferencia= contado - esperado;

        const payload = {
            fecha_inicio:      document.getElementById('fecha-inicio').value + ' 00:00:00',
            fecha_fin:         document.getElementById('fecha-fin').value + ' 23:59:59',
            fondo_inicial:     fondo,
            num_ventas:        ventasData.totales.num_ventas,
            subtotal_ventas:   ventasData.totales.subtotal_ventas,
            descuentos_ventas: ventasData.totales.descuentos_ventas,
            iva_ventas:        ventasData.totales.iva_ventas,
            total_ventas:      totalVent,
            total_ingresos:    movs.ingresos,
            total_gastos:      movs.retiros,
            efectivo_esperado: esperado,
            efectivo_contado:  contado,
            diferencia:        diferencia,
            notas:             document.getElementById('notas').value.trim(),
            gastos:            []
        };

        const btn = document.getElementById('btn-cerrar-corte');
        btn.textContent = 'Guardando…';
        btn.disabled = true;

        fetch('api/save_corte.php', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            btn.textContent = '✔ Cerrar';
            btn.disabled = false;
            if (data.success) {
                buildTicket(payload);
                alert(`✅ Corte #${data.corte_id} guardado.\n\nSe procederá a imprimir el ticket.`);
                imprimirCorte();
            } else {
                alert('Error al guardar: ' + data.message);
            }
        })
        .catch(err => {
            btn.textContent = '✔ Cerrar';
            btn.disabled = false;
            console.error(err);
            alert('Error de conexión.');
        });
    });

    // ── Build ticket ──────────────────────────────────────────────────────
    function buildTicket(p) {
        const dif      = p.diferencia;
        const difClass = dif > 0.005 ? 'green' : (dif < -0.005 ? 'red' : '');
        const difText  = dif > 0.005 ? '▲ SOBRANTE' : (dif < -0.005 ? '▼ FALTANTE' : '✓ CUADRADO');
        const inicio   = document.getElementById('fecha-inicio').value;
        const fin      = document.getElementById('fecha-fin').value;
        const ahora    = new Date().toLocaleString('es-MX', {dateStyle:'short',timeStyle:'medium'});

        document.getElementById('ticket-content').innerHTML = `
            <h1>IMAGEN UNIK</h1>
            <div class="sub">CORTE DE CAJA<br>Reynosa, Tamaulipas</div>
            <hr>
            <div class="pr"><span>Período:</span><span>${inicio} — ${fin}</span></div>
            <div class="pr"><span>Generado:</span><span>${ahora}</span></div>
            <hr>
            <div class="pr bold"><span>VENTAS</span></div>
            <div class="pr"><span>Nº ventas:</span><span>${p.num_ventas}</span></div>
            <div class="pr"><span>Subtotal:</span><span>${fmt(p.subtotal_ventas)}</span></div>
            <div class="pr"><span>Descuentos:</span><span>-${fmt(p.descuentos_ventas)}</span></div>
            <div class="pr"><span>IVA 16%:</span><span>${fmt(p.iva_ventas)}</span></div>
            <div class="pr bold"><span>Total ventas:</span><span>${fmt(p.total_ventas)}</span></div>
            <hr>
            <div class="pr"><span>Fondo inicial:</span><span>${fmt(p.fondo_inicial)}</span></div>
            <div class="pr"><span>Ingresos:</span><span>${fmt(p.total_ingresos)}</span></div>
            <div class="pr"><span>Retiros:</span><span>-${fmt(p.total_gastos)}</span></div>
            <div class="pr bold"><span>Efectivo esperado:</span><span>${fmt(p.efectivo_esperado)}</span></div>
            <div class="pr bold"><span>Efectivo contado:</span><span>${fmt(p.efectivo_contado)}</span></div>
            <hr>
            <div class="pr big ${difClass}"><span>${difText}:</span><span>${fmt(Math.abs(p.diferencia))}</span></div>
            ${p.notas ? `<hr><div class="pr"><span><em>Notas: ${p.notas}</em></span></div>` : ''}
            <div class="footer">— Firma del cajero —<br><br>_________________________</div>
        `;
    }

    function imprimirCorte() {
        window.print();
    }

    // ── Clock ─────────────────────────────────────────────────────────────
    function updateClock() {
        const el = document.getElementById('clock');
        if (el) el.textContent = new Date().toLocaleString('es-MX', {dateStyle:'short',timeStyle:'medium'});
    }

    // ── Init ──────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        updateClock();
        setInterval(updateClock, 1000);

        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha-inicio').value = today;
        document.getElementById('fecha-fin').value    = today;

        document.getElementById('btn-cargar').addEventListener('click', cargarVentas);
        document.getElementById('btn-cerrar-corte').addEventListener('click', cerrarCorte);
        document.getElementById('fondo-inicial').addEventListener('input', recalcResumen);
        document.getElementById('efectivo-contado').addEventListener('input', recalcResumen);

        cargarVentas();
    });
    </script>
</body>
</html>
