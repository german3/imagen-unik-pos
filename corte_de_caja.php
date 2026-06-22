<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corte de Caja — IMAGEN UNIK</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* ── Layout ────────────────────────────────────────────────────── */
        .corte-wrapper {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1rem 1.5rem 3rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* ── Section card ──────────────────────────────────────────────── */
        .corte-card {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.45);
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            border-radius: 14px;
            padding: 1.6rem 2rem;
        }
        .corte-card h2 {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--unik-magenta);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .corte-card h2 .icon { font-size: 1.2rem; }

        /* ── Period selector ───────────────────────────────────────────── */
        .period-row {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .period-row .field { display: flex; flex-direction: column; gap: 0.3rem; }
        .period-row label  { font-size: 0.8rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .period-row input[type="date"] {
            padding: 0.55rem 0.9rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            background: white;
            color: var(--text-main);
            transition: border-color 0.2s;
        }
        .period-row input[type="date"]:focus { outline: none; border-color: var(--primary); }

        /* ── KPI cards ─────────────────────────────────────────────────── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        .kpi-box {
            background: linear-gradient(135deg, #f8f9ff 0%, #eef1fb 100%);
            border: 1px solid rgba(26,115,232,0.12);
            border-radius: 10px;
            padding: 1rem 1.2rem;
            text-align: center;
        }
        .kpi-box .kpi-label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; }
        .kpi-box .kpi-value { font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-top: 0.3rem; }
        .kpi-box.accent { background: linear-gradient(135deg, #fff0f8 0%, #ffe0f0 100%); border-color: rgba(228,0,127,0.15); }
        .kpi-box.accent .kpi-value { color: var(--unik-magenta); }

        /* ── Ventas mini-table ─────────────────────────────────────────── */
        .ventas-table-wrap { max-height: 220px; overflow-y: auto; margin-top: 1rem; border-radius: 8px; border: 1px solid var(--border); }
        .ventas-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        .ventas-table th { background: #f1f3f8; font-weight: 600; color: var(--text-muted); padding: 0.55rem 0.9rem; text-align: left; position: sticky; top: 0; }
        .ventas-table td { padding: 0.5rem 0.9rem; border-bottom: 1px solid var(--border); }
        .ventas-table tr:last-child td { border-bottom: none; }
        .ventas-table tr:hover td { background: rgba(26,115,232,0.04); }
        .ventas-table .right { text-align: right; }
        #ventas-empty { text-align: center; color: var(--text-muted); padding: 2rem; font-size: 0.9rem; }

        /* ── Fondo input ───────────────────────────────────────────────── */
        .money-input-wrap { position: relative; max-width: 260px; }
        .money-input-wrap .currency-prefix {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
            font-weight: 700; color: var(--text-muted); font-size: 1.1rem;
        }
        .money-input-wrap input {
            width: 100%; padding: 0.75rem 1rem 0.75rem 2.2rem;
            font-size: 1.3rem; font-weight: 700; font-family: inherit;
            border: 2px solid var(--border); border-radius: 10px;
            color: var(--text-main); background: white;
            transition: border-color 0.2s;
        }
        .money-input-wrap input:focus { outline: none; border-color: var(--primary); }

        /* ── Gastos table ──────────────────────────────────────────────── */
        .gastos-list { display: flex; flex-direction: column; gap: 0.6rem; margin-bottom: 1rem; }
        .gasto-row {
            display: grid;
            grid-template-columns: 1fr 180px 40px;
            gap: 0.6rem;
            align-items: center;
            animation: fadeIn 0.25s ease;
        }
        .gasto-row input {
            padding: 0.55rem 0.8rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            background: white;
            transition: border-color 0.2s;
        }
        .gasto-row input:focus { outline: none; border-color: var(--primary); }
        .gasto-row input.monto-input { text-align: right; font-weight: 600; }
        .btn-del-gasto {
            background: none; border: 1.5px solid var(--danger);
            color: var(--danger); border-radius: 8px; width: 36px; height: 36px;
            cursor: pointer; font-size: 1.1rem; display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .btn-del-gasto:hover { background: var(--danger); color: white; }
        .gastos-total-row {
            display: flex; justify-content: flex-end; align-items: center;
            gap: 1rem; padding-top: 0.6rem;
            border-top: 1px dashed var(--border);
        }
        .gastos-total-row span { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; }
        .gastos-total-row strong { font-size: 1.2rem; color: var(--danger); }

        /* ── Denominaciones ────────────────────────────────────────────── */
        .denom-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 0.8rem;
        }
        .denom-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 0.6rem 0.9rem;
            transition: border-color 0.2s;
        }
        .denom-item:focus-within { border-color: var(--primary); }
        .denom-badge {
            background: linear-gradient(135deg, var(--unik-cyan), var(--primary));
            color: white; border-radius: 6px; padding: 0.25rem 0.5rem;
            font-size: 0.78rem; font-weight: 700; min-width: 52px; text-align: center;
            flex-shrink: 0;
        }
        .denom-badge.moneda { background: linear-gradient(135deg, #f7b731, #f0932b); }
        .denom-qty {
            width: 60px; border: none; outline: none;
            font-family: inherit; font-size: 1rem; font-weight: 600;
            text-align: center; background: transparent; color: var(--text-main);
        }
        .denom-subtotal {
            margin-left: auto; font-size: 0.85rem;
            font-weight: 600; color: var(--text-muted);
            min-width: 80px; text-align: right;
        }
        .denom-sep { grid-column: 1/-1; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-top: 0.4rem; }
        .contado-total-box {
            display: flex; justify-content: flex-end; align-items: center;
            gap: 1rem; margin-top: 1rem; padding-top: 0.8rem;
            border-top: 2px solid var(--border);
        }
        .contado-total-box span { font-size: 0.9rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; }
        .contado-total-box strong { font-size: 1.6rem; font-weight: 800; color: var(--primary); }

        /* ── Resumen final ─────────────────────────────────────────────── */
        .resumen-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }
        @media (max-width: 680px) { .resumen-grid { grid-template-columns: 1fr; } }

        .resumen-table { width: 100%; border-collapse: collapse; }
        .resumen-table td { padding: 0.7rem 0.5rem; border-bottom: 1px dashed var(--border); font-size: 0.95rem; }
        .resumen-table tr:last-child td { border-bottom: none; }
        .resumen-table .label { color: var(--text-muted); }
        .resumen-table .value { text-align: right; font-weight: 600; }
        .resumen-table .separator td { border-bottom: 2px solid var(--text-main); padding: 0; }

        .diferencia-box {
            border-radius: 14px; padding: 1.5rem 1.8rem; text-align: center;
            border: 2px solid;
        }
        .diferencia-box.sobrante { background: linear-gradient(135deg, #f0fff4, #e0f7e9); border-color: var(--success); }
        .diferencia-box.faltante { background: linear-gradient(135deg, #fff5f5, #ffe0e0); border-color: var(--danger); }
        .diferencia-box.neutro   { background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-color: var(--border); }
        .diferencia-box .dif-label { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 0.6rem; }
        .diferencia-box.sobrante .dif-label { color: var(--success); }
        .diferencia-box.faltante .dif-label { color: var(--danger); }
        .diferencia-box .dif-value { font-size: 3rem; font-weight: 800; line-height: 1; }
        .diferencia-box.sobrante .dif-value { color: var(--success); }
        .diferencia-box.faltante .dif-value { color: var(--danger); }
        .diferencia-box.neutro   .dif-value { color: var(--text-muted); }
        .diferencia-box .dif-status { margin-top: 0.6rem; font-size: 0.95rem; font-weight: 600; }

        /* ── Notas ─────────────────────────────────────────────────────── */
        .notas-textarea {
            width: 100%; min-height: 90px; resize: vertical;
            padding: 0.75rem 1rem; border: 1.5px solid var(--border); border-radius: 10px;
            font-family: inherit; font-size: 0.95rem; background: white;
            transition: border-color 0.2s; color: var(--text-main);
        }
        .notas-textarea:focus { outline: none; border-color: var(--primary); }

        /* ── Actions row ───────────────────────────────────────────────── */
        .actions-row { display: flex; gap: 1rem; flex-wrap: wrap; justify-content: flex-end; }

        /* ── Print styles ──────────────────────────────────────────────── */
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
        .print-ticket hr { border: none; border-top: 1px dashed #999; margin: 0.8rem 0; }
        .print-ticket .pr { display: flex; justify-content: space-between; padding: 0.25rem 0; }
        .print-ticket .pr.bold { font-weight: 700; }
        .print-ticket .pr.big  { font-size: 1.1rem; font-weight: 800; }
        .print-ticket .pr.green { color: #2d7a3a; }
        .print-ticket .pr.red   { color: #c0392b; }
        .print-ticket .footer { text-align: center; margin-top: 1rem; font-size: 0.75rem; color: #888; }

        /* ── Responsive tweaks ─────────────────────────────────────────── */
        @media (max-width: 600px) {
            .corte-card { padding: 1.2rem 1rem; }
            .gasto-row { grid-template-columns: 1fr 120px 36px; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="pos-header glass">
        <div class="logo-container" style="display:flex; align-items:center; gap:1rem;">
            <img src="Logo.jpeg" alt="IMAGEN UNIK Logo" style="height:55px; object-fit:contain;"
                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
            <div>
                <h1 style="margin:0; font-size:1.35rem; font-weight:700; color:var(--text-main);">PUNTO DE VENTA IMAGEN UNIK</h1>
                <p style="margin:0; font-size:0.85rem; color:var(--text-muted);">Corte de Caja — Reynosa, Tamaulipas</p>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:1rem;">
            <div id="clock" style="font-size:0.9rem; color:var(--text-muted);"></div>
            <a href="index.php" class="btn btn-secondary no-print" style="font-size:0.85rem;">← Volver al POS</a>
        </div>
    </header>

    <div class="corte-wrapper">

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- 1. PERÍODO + RESUMEN DE VENTAS                                -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="corte-card">
            <h2><span class="icon">📅</span> Período de ventas</h2>
            <div class="period-row">
                <div class="field">
                    <label>Fecha inicio</label>
                    <input type="date" id="fecha-inicio">
                </div>
                <div class="field">
                    <label>Fecha fin</label>
                    <input type="date" id="fecha-fin">
                </div>
                <button class="btn btn-primary no-print" id="btn-cargar" style="align-self:flex-end;">
                    Cargar ventas
                </button>
            </div>

            <!-- KPI cards -->
            <div class="kpi-grid" style="margin-top:1.5rem;" id="kpi-grid">
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
                    <div class="kpi-label">IVA (16%)</div>
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
                            <th>#</th>
                            <th>Fecha/Hora</th>
                            <th>Cliente</th>
                            <th class="right">Total</th>
                        </tr>
                    </thead>
                    <tbody id="ventas-tbody"></tbody>
                </table>
            </div>
            <div id="ventas-empty" style="display:none;">Sin ventas para el período seleccionado.</div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- 2. FONDO INICIAL                                               -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="corte-card">
            <h2><span class="icon">💵</span> Fondo inicial de caja</h2>
            <p style="font-size:0.88rem; color:var(--text-muted); margin-bottom:1rem;">
                Efectivo con que se abrió la caja al inicio del turno.
            </p>
            <div class="money-input-wrap">
                <span class="currency-prefix">$</span>
                <input type="number" id="fondo-inicial" value="0" min="0" step="0.01"
                       placeholder="0.00">
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- 3. GASTOS Y RETIROS                                            -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="corte-card">
            <h2><span class="icon">🧾</span> Gastos y retiros del turno</h2>
            <p style="font-size:0.88rem; color:var(--text-muted); margin-bottom:1rem;">
                Registra cualquier salida de efectivo: pagos a proveedores, retiros, etc.
            </p>
            <div class="gastos-list" id="gastos-list"></div>
            <button class="btn btn-secondary no-print" id="btn-add-gasto" style="font-size:0.88rem;">
                + Agregar gasto / retiro
            </button>
            <div class="gastos-total-row" style="margin-top:1rem;">
                <span>Total gastos</span>
                <strong id="gastos-total-label">$0.00</strong>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- 4. CONTEO FÍSICO DE EFECTIVO                                   -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="corte-card">
            <h2><span class="icon">💰</span> Conteo físico de efectivo</h2>
            <p style="font-size:0.88rem; color:var(--text-muted); margin-bottom:1.2rem;">
                Ingresa la cantidad de cada denominación que hay físicamente en caja.
            </p>

            <div class="denom-grid" id="denom-grid">
                <!-- Billetes -->
                <div class="denom-sep">📄 Billetes</div>
                <!-- Rendered by JS -->
                <!-- Monedas -->
            </div>

            <div class="contado-total-box">
                <span>Total contado en caja</span>
                <strong id="contado-total-label">$0.00</strong>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- 5. RESUMEN DE CIERRE                                           -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="corte-card">
            <h2><span class="icon">📊</span> Resumen de cierre</h2>
            <div class="resumen-grid">
                <table class="resumen-table">
                    <tr>
                        <td class="label">Fondo inicial</td>
                        <td class="value" id="r-fondo">$0.00</td>
                    </tr>
                    <tr>
                        <td class="label">+ Total ventas</td>
                        <td class="value" id="r-ventas">$0.00</td>
                    </tr>
                    <tr>
                        <td class="label">− Total gastos</td>
                        <td class="value" id="r-gastos">$0.00</td>
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

                <!-- Diferencia box -->
                <div class="diferencia-box neutro" id="dif-box">
                    <div class="dif-label" id="dif-label">Diferencia</div>
                    <div class="dif-value" id="dif-value">$0.00</div>
                    <div class="dif-status" id="dif-status">Ingresa los datos para calcular</div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- 6. NOTAS Y CIERRE                                              -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="corte-card no-print">
            <h2><span class="icon">📝</span> Notas del turno</h2>
            <textarea class="notas-textarea" id="notas" placeholder="Observaciones del cajero, incidencias, etc."></textarea>
        </div>

        <div class="actions-row no-print">
            <button class="btn btn-secondary" onclick="window.location.href='index.php'">Cancelar</button>
            <button class="btn btn-primary" id="btn-imprimir" onclick="imprimirCorte()">🖨 Imprimir</button>
            <button class="btn btn-success" id="btn-cerrar-corte">✔ Cerrar Corte</button>
        </div>

    </div><!-- /.corte-wrapper -->

    <!-- ══ PRINT AREA ══════════════════════════════════════════════════════ -->
    <div id="print-area">
        <div class="print-ticket" id="ticket-content">
            <!-- Populated by JS before printing -->
        </div>
    </div>

    <script>
    // ── Globals ───────────────────────────────────────────────────────────
    const fmt = n => '$' + parseFloat(n || 0).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    let ventasData   = { totales: { num_ventas:0, subtotal_ventas:0, descuentos_ventas:0, iva_ventas:0, total_ventas:0 }, ventas: [] };
    let gastoCounter = 0;

    // ── Denominaciones ─────────────────────────────────────────────────────
    const DENOMINACIONES = [
        { val: 1000, tipo: 'billete' },
        { val:  500, tipo: 'billete' },
        { val:  200, tipo: 'billete' },
        { val:  100, tipo: 'billete' },
        { val:   50, tipo: 'billete' },
        { val:   20, tipo: 'billete' },
        { val:   10, tipo: 'moneda'  },
        { val:    5, tipo: 'moneda'  },
        { val:    2, tipo: 'moneda'  },
        { val:    1, tipo: 'moneda'  },
    ];

    // ── Build denomination grid ────────────────────────────────────────────
    function buildDenomGrid() {
        const grid = document.getElementById('denom-grid');
        let moedaSepDone = false;
        DENOMINACIONES.forEach(d => {
            if (d.tipo === 'moneda' && !moedaSepDone) {
                const sep = document.createElement('div');
                sep.className = 'denom-sep';
                sep.textContent = '🪙 Monedas';
                grid.appendChild(sep);
                moedaSepDone = true;
            }
            const item = document.createElement('div');
            item.className = 'denom-item';
            item.innerHTML = `
                <span class="denom-badge ${d.tipo === 'moneda' ? 'moneda' : ''}">$${d.val}</span>
                <input type="number" class="denom-qty" data-val="${d.val}"
                       value="0" min="0" step="1" title="${d.val === 1 ? 'moneda de $1' : '$' + d.val}">
                <span class="denom-subtotal" id="dsub-${d.val}">$0.00</span>
            `;
            grid.appendChild(item);
            item.querySelector('.denom-qty').addEventListener('input', () => {
                recalcDenoms();
                recalcResumen();
            });
        });
    }

    function recalcDenoms() {
        let total = 0;
        document.querySelectorAll('.denom-qty').forEach(inp => {
            const val   = parseInt(inp.dataset.val, 10);
            const qty   = parseInt(inp.value, 10) || 0;
            const sub   = val * qty;
            total += sub;
            document.getElementById(`dsub-${val}`).textContent = fmt(sub);
        });
        document.getElementById('contado-total-label').textContent = fmt(total);
        return total;
    }

    // ── Gastos ─────────────────────────────────────────────────────────────
    function addGastoRow(desc = '', monto = '') {
        gastoCounter++;
        const id   = gastoCounter;
        const list = document.getElementById('gastos-list');
        const row  = document.createElement('div');
        row.className = 'gasto-row';
        row.id = `gasto-${id}`;
        row.innerHTML = `
            <input type="text"   class="gasto-desc"  placeholder="Descripción del gasto" value="${desc}">
            <input type="number" class="gasto-monto monto-input" placeholder="0.00" value="${monto}" min="0" step="0.01">
            <button class="btn-del-gasto" onclick="removeGasto(${id})">×</button>
        `;
        list.appendChild(row);
        row.querySelector('.gasto-monto').addEventListener('input', () => {
            recalcGastos();
            recalcResumen();
        });
        row.querySelector('.gasto-desc').addEventListener('input', recalcResumen);
    }

    function removeGasto(id) {
        const el = document.getElementById(`gasto-${id}`);
        if (el) { el.remove(); recalcGastos(); recalcResumen(); }
    }

    function recalcGastos() {
        let total = 0;
        document.querySelectorAll('.gasto-monto').forEach(inp => {
            total += parseFloat(inp.value) || 0;
        });
        document.getElementById('gastos-total-label').textContent = fmt(total);
        return total;
    }

    // ── Resumen en tiempo real ─────────────────────────────────────────────
    function recalcResumen() {
        const fondo      = parseFloat(document.getElementById('fondo-inicial').value) || 0;
        const totalVent  = parseFloat(ventasData.totales.total_ventas) || 0;
        const totalGasto = recalcGastos();
        const contado    = recalcDenoms();

        const esperado   = fondo + totalVent - totalGasto;
        const diferencia = contado - esperado;

        document.getElementById('r-fondo').textContent    = fmt(fondo);
        document.getElementById('r-ventas').textContent   = fmt(totalVent);
        document.getElementById('r-gastos').textContent   = fmt(totalGasto);
        document.getElementById('r-esperado').textContent = fmt(esperado);
        document.getElementById('r-contado').textContent  = fmt(contado);

        const difBox    = document.getElementById('dif-box');
        const difValue  = document.getElementById('dif-value');
        const difStatus = document.getElementById('dif-status');
        const difLabel  = document.getElementById('dif-label');

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

    // ── Load sales ─────────────────────────────────────────────────────────
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

                // Update KPIs
                document.getElementById('kpi-num-ventas').textContent   = t.num_ventas;
                document.getElementById('kpi-subtotal').textContent     = fmt(t.subtotal_ventas);
                document.getElementById('kpi-descuentos').textContent   = fmt(t.descuentos_ventas);
                document.getElementById('kpi-iva').textContent          = fmt(t.iva_ventas);
                document.getElementById('kpi-total-ventas').textContent = fmt(t.total_ventas);

                // Populate ventas table
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
                    data.ventas.forEach((v, i) => {
                        const tr = document.createElement('tr');
                        const dt = new Date(v.fecha_hora.replace(' ', 'T'));
                        const hora = dt.toLocaleTimeString('es-MX', {hour:'2-digit', minute:'2-digit'});
                        const fecha = dt.toLocaleDateString('es-MX', {day:'2-digit', month:'2-digit', year:'numeric'});
                        tr.innerHTML = `
                            <td>#${v.id}</td>
                            <td>${fecha} ${hora}</td>
                            <td>${v.cliente}</td>
                            <td class="right">${fmt(v.total)}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }

                recalcResumen();
            })
            .catch(err => {
                btn.textContent = 'Cargar ventas';
                btn.disabled = false;
                console.error(err);
                alert('Error de conexión.');
            });
    }

    // ── Save corte ─────────────────────────────────────────────────────────
    function cerrarCorte() {
        const inicio = document.getElementById('fecha-inicio').value;
        const fin    = document.getElementById('fecha-fin').value;
        if (!inicio || !fin) { alert('Selecciona las fechas y carga las ventas primero.'); return; }

        const t            = ventasData.totales;
        const fondo        = parseFloat(document.getElementById('fondo-inicial').value) || 0;
        const totalGasto   = parseFloat(recalcGastos());
        const contado      = parseFloat(recalcDenoms());
        const esperado     = fondo + parseFloat(t.total_ventas || 0) - totalGasto;
        const diferencia   = contado - esperado;

        // Collect gastos
        const gastos = [];
        document.querySelectorAll('.gasto-row').forEach(row => {
            const desc  = row.querySelector('.gasto-desc').value.trim();
            const monto = parseFloat(row.querySelector('.gasto-monto').value) || 0;
            if (desc && monto > 0) gastos.push({ descripcion: desc, monto });
        });

        const payload = {
            fecha_inicio:      `${inicio} 00:00:00`,
            fecha_fin:         `${fin} 23:59:59`,
            fondo_inicial:     fondo,
            num_ventas:        parseInt(t.num_ventas)        || 0,
            subtotal_ventas:   parseFloat(t.subtotal_ventas) || 0,
            descuentos_ventas: parseFloat(t.descuentos_ventas) || 0,
            iva_ventas:        parseFloat(t.iva_ventas)      || 0,
            total_ventas:      parseFloat(t.total_ventas)    || 0,
            total_gastos:      totalGasto,
            efectivo_esperado: esperado,
            efectivo_contado:  contado,
            diferencia:        diferencia,
            notas:             document.getElementById('notas').value.trim(),
            gastos:            gastos,
        };

        const btn = document.getElementById('btn-cerrar-corte');
        btn.textContent = 'Guardando…';
        btn.disabled = true;

        fetch('api/save_corte.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            btn.textContent = '✔ Cerrar Corte';
            btn.disabled = false;
            if (data.success) {
                buildTicket(payload);
                alert(`✅ Corte #${data.corte_id} guardado correctamente.\n\nSe procederá a imprimir el ticket.`);
                imprimirCorte();
            } else {
                alert('Error al guardar: ' + data.message);
            }
        })
        .catch(err => {
            btn.textContent = '✔ Cerrar Corte';
            btn.disabled = false;
            console.error(err);
            alert('Error de conexión.');
        });
    }

    // ── Build print ticket ─────────────────────────────────────────────────
    function buildTicket(p) {
        const dif = p.diferencia;
        const difClass = dif > 0.005 ? 'green' : (dif < -0.005 ? 'red' : '');
        const difText  = dif > 0.005 ? '▲ SOBRANTE' : (dif < -0.005 ? '▼ FALTANTE' : '✓ CUADRADO');

        const inicio = document.getElementById('fecha-inicio').value;
        const fin    = document.getElementById('fecha-fin').value;
        const ahora  = new Date().toLocaleString('es-MX', {dateStyle:'short', timeStyle:'medium'});

        let gastosHtml = '';
        (p.gastos || []).forEach(g => {
            gastosHtml += `<div class="pr"><span>${g.descripcion}</span><span>${fmt(g.monto)}</span></div>`;
        });

        document.getElementById('ticket-content').innerHTML = `
            <h1>IMAGEN UNIK</h1>
            <div class="sub">CORTE DE CAJA<br>Reynosa, Tamaulipas</div>
            <hr>
            <div class="pr"><span>Período:</span><span>${inicio} — ${fin}</span></div>
            <div class="pr"><span>Generado:</span><span>${ahora}</span></div>
            <hr>
            <div class="pr bold"><span>VENTAS</span><span></span></div>
            <div class="pr"><span>Nº ventas:</span><span>${p.num_ventas}</span></div>
            <div class="pr"><span>Subtotal:</span><span>${fmt(p.subtotal_ventas)}</span></div>
            <div class="pr"><span>Descuentos:</span><span>-${fmt(p.descuentos_ventas)}</span></div>
            <div class="pr"><span>IVA 16%:</span><span>${fmt(p.iva_ventas)}</span></div>
            <div class="pr bold"><span>Total ventas:</span><span>${fmt(p.total_ventas)}</span></div>
            <hr>
            <div class="pr bold"><span>GASTOS / RETIROS</span><span></span></div>
            ${gastosHtml || '<div class="pr"><span>(Sin gastos)</span><span>$0.00</span></div>'}
            <div class="pr bold"><span>Total gastos:</span><span>-${fmt(p.total_gastos)}</span></div>
            <hr>
            <div class="pr"><span>Fondo inicial:</span><span>${fmt(p.fondo_inicial)}</span></div>
            <div class="pr bold"><span>Efectivo esperado:</span><span>${fmt(p.efectivo_esperado)}</span></div>
            <div class="pr bold"><span>Efectivo contado:</span><span>${fmt(p.efectivo_contado)}</span></div>
            <hr>
            <div class="pr big ${difClass}"><span>${difText}:</span><span>${fmt(Math.abs(p.diferencia))}</span></div>
            ${p.notas ? `<hr><div class="pr"><span><em>Notas: ${p.notas}</em></span></div>` : ''}
            <div class="footer">— Firma del cajero —<br><br>_________________________</div>
        `;
    }

    function imprimirCorte() {
        // Build ticket with current values even if corte wasn't saved yet
        const t   = ventasData.totales;
        const fondo = parseFloat(document.getElementById('fondo-inicial').value) || 0;
        const totalGasto = parseFloat(document.getElementById('gastos-total-label').textContent.replace('$','').replace(',','')) || 0;
        const contado    = parseFloat(recalcDenoms());
        const esperado   = fondo + parseFloat(t.total_ventas || 0) - totalGasto;
        const gastos = [];
        document.querySelectorAll('.gasto-row').forEach(row => {
            const desc  = row.querySelector('.gasto-desc').value.trim();
            const monto = parseFloat(row.querySelector('.gasto-monto').value) || 0;
            if (desc && monto > 0) gastos.push({ descripcion: desc, monto });
        });
        buildTicket({
            fecha_inicio: `${document.getElementById('fecha-inicio').value} 00:00:00`,
            fecha_fin:    `${document.getElementById('fecha-fin').value} 23:59:59`,
            fondo_inicial: fondo,
            num_ventas:   t.num_ventas    || 0,
            subtotal_ventas:   t.subtotal_ventas   || 0,
            descuentos_ventas: t.descuentos_ventas || 0,
            iva_ventas:   t.iva_ventas    || 0,
            total_ventas: t.total_ventas  || 0,
            total_gastos: totalGasto,
            efectivo_esperado: esperado,
            efectivo_contado:  contado,
            diferencia:        contado - esperado,
            notas: document.getElementById('notas').value.trim(),
            gastos,
        });
        window.print();
    }

    // ── Clock ──────────────────────────────────────────────────────────────
    function updateClock() {
        const el = document.getElementById('clock');
        if (el) el.textContent = new Date().toLocaleString('es-MX', {dateStyle:'short', timeStyle:'medium'});
    }

    // ── Init ───────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        updateClock();
        setInterval(updateClock, 1000);

        // Default dates = today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha-inicio').value = today;
        document.getElementById('fecha-fin').value    = today;

        buildDenomGrid();

        document.getElementById('btn-cargar').addEventListener('click', cargarVentas);
        document.getElementById('btn-add-gasto').addEventListener('click', () => addGastoRow());
        document.getElementById('btn-cerrar-corte').addEventListener('click', cerrarCorte);
        document.getElementById('fondo-inicial').addEventListener('input', () => recalcResumen());

        // Auto-load today's data
        cargarVentas();
    });
    </script>
</body>
</html>
