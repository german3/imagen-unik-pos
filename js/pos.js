// js/pos.js
document.addEventListener('DOMContentLoaded', () => {
    updateClock();
    setInterval(updateClock, 1000);
    
    // Initialize first row
    addNewRow();

    // Fetch next sale ID
    fetchNextSaleId();

    // ── Client autocomplete ──────────────────────────────────────────────
    initClientAutocomplete();

    // Event listener for Confirm Sale button
    const confirmBtn = document.getElementById('btn-confirm');
    if(confirmBtn) {
        confirmBtn.addEventListener('click', () => confirmTransaction('api/save_sale.php', 'Venta guardada exitosamente.'));
    }

    const cotizacionBtn = document.getElementById('btn-cotizacion');
    if(cotizacionBtn) {
        cotizacionBtn.addEventListener('click', () => confirmTransaction('api/save_quote.php', 'Cotización guardada en el historial.'));
    }

    const ivaRateEl = document.getElementById('iva-rate');
    if(ivaRateEl) {
        ivaRateEl.addEventListener('change', calculateTotals);
    }
});

function fetchNextSaleId() {
    const folioInput = document.getElementById('folio-venta');
    if (!folioInput) return;
    fetch('api/get_next_sale_id.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                folioInput.value = 'F-' + String(data.next_id).padStart(4, '0');
            }
        })
        .catch(err => console.error('Error fetching next sale ID:', err));
}

// ── Client Autocomplete ──────────────────────────────────────────────────────
function initClientAutocomplete() {
    const clientInput   = document.getElementById('client-search');
    const clientIdInput = document.getElementById('client-id');
    const suggestionsEl = document.getElementById('client-suggestions');
    if (!clientInput || !suggestionsEl) return;

    let clientTimeout;

    clientInput.addEventListener('input', () => {
        clearTimeout(clientTimeout);
        const val = clientInput.value.trim();

        if (val.length < 2) {
            suggestionsEl.style.display = 'none';
            return;
        }

        clientTimeout = setTimeout(() => {
            fetch(`api/search_clients.php?q=${encodeURIComponent(val)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsEl.innerHTML = '';
                    if (!Array.isArray(data) || data.length === 0) {
                        suggestionsEl.style.display = 'none';
                        return;
                    }
                    data.forEach(client => {
                        const div = document.createElement('div');
                        div.className = 'autocomplete-suggestion';
                        const fullName = `${client.nombre} ${client.apellidos || ''}`.trim();
                        const detail   = client.rfc ? ` · RFC: ${client.rfc}` : (client.telefono ? ` · Tel: ${client.telefono}` : '');
                        div.innerHTML  = `<span>${fullName}</span><small class="text-muted">${detail}</small>`;
                        div.addEventListener('mousedown', (e) => {
                            e.preventDefault();
                            clientInput.value   = fullName;
                            clientIdInput.value = client.id;
                            suggestionsEl.style.display = 'none';
                        });
                        suggestionsEl.appendChild(div);
                    });
                    suggestionsEl.style.display = 'block';
                })
                .catch(err => console.error('Client search error:', err));
        }, 300);
    });

    clientInput.addEventListener('blur', () => {
        setTimeout(() => { suggestionsEl.style.display = 'none'; }, 200);
    });

    clientInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            suggestionsEl.style.display = 'none';
        } else if (e.key === 'Enter') {
            const first = suggestionsEl.querySelector('.autocomplete-suggestion');
            if (first) first.dispatchEvent(new MouseEvent('mousedown'));
            suggestionsEl.style.display = 'none';
        } else {
            clientIdInput.value = '';
        }
    });
}

function updateClock() {
    const clockEl = document.getElementById('clock');
    if (!clockEl) return;
    const now = new Date();
    clockEl.textContent = now.toLocaleString('es-MX', { 
        dateStyle: 'short', 
        timeStyle: 'medium' 
    });
}

const tbody = document.getElementById('pos-tbody');
let lineCounter = 1;

function addNewRow() {
    const tr = document.createElement('tr');
    tr.className = 'animate-fade-in';
    // data-metros stores whether this row's product is sold by m²
    tr.dataset.metros = '0';
    tr.innerHTML = `
        <td class="line-number center">${lineCounter}</td>
        <td>
            <input type="text" class="td-input product-search" placeholder="Escribir producto...">
            <input type="hidden" class="product-id">
            <div class="autocomplete-suggestions"></div>
        </td>
        <td><input type="number" class="td-input center qty" value="1" min="0" step="0.01"></td>
        <td>
            <input type="number" class="td-input center alto" value="" placeholder="—" step="0.01" min="0"
                   disabled style="background:transparent;color:#bbb;cursor:not-allowed;">
        </td>
        <td>
            <input type="number" class="td-input center ancho" value="" placeholder="—" step="0.01" min="0"
                   disabled style="background:transparent;color:#bbb;cursor:not-allowed;">
        </td>
        <td><input type="number" class="td-input right cost" value="0.00" step="0.01"></td>
        <td><input type="number" class="td-input center disc-perc" value="0" min="0" max="100" step="1"></td>
        <td><input type="number" class="td-input right disc-mxn" value="0.00" readonly></td>
        <td><input type="number" class="td-input right line-total" value="0.00" readonly></td>
        <td class="center"><button class="row-delete-btn" tabindex="-1">×</button></td>
    `;
    tbody.appendChild(tr);
    lineCounter++;

    attachRowEvents(tr);
    
    tr.querySelector('.product-search').focus();
}

let searchTimeout;

// ── Helper: enable/disable the Alto & Ancho inputs for a row ────────────────
function setMetrosMode(tr, isMetros) {
    const qtyInput   = tr.querySelector('.qty');
    const altoInput  = tr.querySelector('.alto');
    const anchoInput = tr.querySelector('.ancho');

    tr.dataset.metros = isMetros ? '1' : '0';

    if (isMetros) {
        // Disable and grey out Cantidad
        qtyInput.readOnly = true;
        qtyInput.style.background  = '#f1f3f4';
        qtyInput.style.color       = '#5f6368';
        qtyInput.style.cursor      = 'not-allowed';

        // Enable Alto & Ancho
        altoInput.disabled  = false;
        anchoInput.disabled = false;
        altoInput.style.background  = '';
        anchoInput.style.background = '';
        altoInput.style.color       = '';
        anchoInput.style.color      = '';
        altoInput.style.cursor      = '';
        anchoInput.style.cursor     = '';

        // Default values
        if (!altoInput.value || parseFloat(altoInput.value) === 0) altoInput.value = '1.00';
        if (!anchoInput.value || parseFloat(anchoInput.value) === 0) anchoInput.value = '1.00';

        // Recalculate qty from dimensions
        const a = parseFloat(altoInput.value) || 0;
        const b = parseFloat(anchoInput.value) || 0;
        qtyInput.value = (a * b).toFixed(4);
    } else {
        // Enable Cantidad
        qtyInput.readOnly = false;
        qtyInput.style.background = '';
        qtyInput.style.color      = '';
        qtyInput.style.cursor     = '';

        // Disable Alto & Ancho
        altoInput.disabled  = true;
        anchoInput.disabled = true;
        altoInput.value     = '';
        anchoInput.value    = '';
        altoInput.style.background  = 'transparent';
        anchoInput.style.background = 'transparent';
        altoInput.style.color       = '#bbb';
        anchoInput.style.color      = '#bbb';
        altoInput.style.cursor      = 'not-allowed';
        anchoInput.style.cursor     = 'not-allowed';
    }
}

function attachRowEvents(tr) {
    const productInput  = tr.querySelector('.product-search');
    const suggestionsBox = tr.querySelector('.autocomplete-suggestions');
    const qtyInput      = tr.querySelector('.qty');
    const altoInput     = tr.querySelector('.alto');
    const anchoInput    = tr.querySelector('.ancho');
    const costInput     = tr.querySelector('.cost');
    const discPercInput = tr.querySelector('.disc-perc');
    const deleteBtn     = tr.querySelector('.row-delete-btn');

    // ── Product autocomplete ──────────────────────────────────────────────
    productInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const val = e.target.value.trim();
        
        if (val.length < 2) {
            suggestionsBox.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`api/search_products.php?q=${encodeURIComponent(val)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    if (data.error) {
                        console.error("SQL Error from server:", data.error);
                        suggestionsBox.style.display = 'none';
                        return;
                    }
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'autocomplete-suggestion';
                            const m2badge = parseInt(item.venta_por_metros) === 1
                                ? ' <span style="background:#e8f0fe;color:#1a73e8;font-size:0.7rem;padding:1px 5px;border-radius:4px;font-weight:700;">M²</span>'
                                : '';
                            div.innerHTML = `<span>${item.descripcion}${m2badge}</span> <small class="text-muted">$${item.precio}</small>`;
                            div.addEventListener('click', () => {
                                selectProduct(tr, item);
                                suggestionsBox.style.display = 'none';
                            });
                            suggestionsBox.appendChild(div);
                        });
                        suggestionsBox.style.display = 'block';
                    } else {
                        suggestionsBox.style.display = 'none';
                    }
                })
                .catch(err => console.error(err));
        }, 300);
    });

    productInput.addEventListener('blur', () => {
        setTimeout(() => { suggestionsBox.style.display = 'none'; }, 200);
    });

    productInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            suggestionsBox.style.display = 'none';
            // Focus Alto if metros mode, otherwise Cantidad
            if (tr.dataset.metros === '1') {
                altoInput.focus();
                altoInput.select();
            } else {
                qtyInput.focus();
                qtyInput.select();
            }
        }
    });

    // ── Alto & Ancho listeners: recalculate qty = alto × ancho ───────────
    altoInput.addEventListener('input', () => {
        if (tr.dataset.metros === '1') {
            const a = parseFloat(altoInput.value) || 0;
            const b = parseFloat(anchoInput.value) || 0;
            qtyInput.value = (a * b).toFixed(4);
            calculateRow(tr);
        }
    });

    anchoInput.addEventListener('input', () => {
        if (tr.dataset.metros === '1') {
            const a = parseFloat(altoInput.value) || 0;
            const b = parseFloat(anchoInput.value) || 0;
            qtyInput.value = (a * b).toFixed(4);
            calculateRow(tr);
        }
    });

    // Move focus from Alto → Ancho on Enter, Ancho → next field on Enter
    altoInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            anchoInput.focus();
            anchoInput.select();
        }
    });
    anchoInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            costInput.focus();
            costInput.select();
        }
    });

    // ── Standard row inputs ───────────────────────────────────────────────
    const inputs = [qtyInput, costInput, discPercInput];
    inputs.forEach(input => {
        input.addEventListener('input', () => calculateRow(tr));
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (tr === tbody.lastElementChild && productInput.value.trim() !== '') {
                    addNewRow();
                } else if (tr !== tbody.lastElementChild) {
                    const nextRow = tr.nextElementSibling;
                    if (nextRow) nextRow.querySelector('.product-search').focus();
                }
            }
        });
    });

    deleteBtn.addEventListener('click', () => {
        if (tbody.children.length > 1) {
            tr.remove();
            calculateTotals();
        } else {
            // Clear the single remaining row
            tr.querySelectorAll('input').forEach(inp => {
                if (inp.type === 'number') inp.value = inp.classList.contains('qty') ? '1' : '0.00';
                else inp.value = '';
            });
            setMetrosMode(tr, false);
            calculateTotals();
        }
    });
}

// ── Select a product from the autocomplete dropdown ──────────────────────────
function selectProduct(tr, item) {
    const isMetros = parseInt(item.venta_por_metros) === 1;

    tr.querySelector('.product-search').value = item.descripcion;
    tr.querySelector('.product-id').value     = item.id;

    // Use precio_m2 for m² products, regular precio otherwise
    const price = isMetros
        ? parseFloat(item.precio_m2 || item.precio)
        : parseFloat(item.precio);
    tr.querySelector('.cost').value = price.toFixed(2);

    // Toggle metros mode (enables/disables Alto & Ancho, locks/unlocks Cantidad)
    setMetrosMode(tr, isMetros);

    calculateRow(tr);

    if (isMetros) {
        tr.querySelector('.alto').focus();
        tr.querySelector('.alto').select();
    } else {
        tr.querySelector('.qty').focus();
        tr.querySelector('.qty').select();
    }
}

// ── Calculate a single row's discount and total ───────────────────────────────
function calculateRow(tr) {
    const qty      = parseFloat(tr.querySelector('.qty').value) || 0;
    const cost     = parseFloat(tr.querySelector('.cost').value) || 0;
    const discPerc = parseFloat(tr.querySelector('.disc-perc').value) || 0;

    const sub      = qty * cost;
    const discMxn  = sub * (discPerc / 100);
    const totalLine = sub - discMxn;

    tr.querySelector('.disc-mxn').value   = discMxn.toFixed(2);
    tr.querySelector('.line-total').value = totalLine.toFixed(2);

    calculateTotals();
}

// ── Recalculate all grand totals ─────────────────────────────────────────────
function calculateTotals() {
    let subtotalSinIva = 0;
    let descuentoTotal = 0;

    const rows = tbody.querySelectorAll('tr');
    rows.forEach(tr => {
        const qty    = parseFloat(tr.querySelector('.qty').value) || 0;
        const cost   = parseFloat(tr.querySelector('.cost').value) || 0;
        const discMxn = parseFloat(tr.querySelector('.disc-mxn').value) || 0;
        
        subtotalSinIva += (qty * cost);
        descuentoTotal += discMxn;
    });

    const subtotalConDescuento = subtotalSinIva - descuentoTotal;
    const ivaRateEl = document.getElementById('iva-rate');
    const ivaRate   = ivaRateEl ? parseFloat(ivaRateEl.value) : 0.16;
    const iva       = subtotalConDescuento * ivaRate;
    const totalPagar = subtotalConDescuento + iva;

    document.getElementById('lbl-subtotal').textContent     = `$${subtotalSinIva.toFixed(2)}`;
    document.getElementById('lbl-desc-total').textContent   = `$${descuentoTotal.toFixed(2)}`;
    document.getElementById('lbl-subtotal-desc').textContent = `$${subtotalConDescuento.toFixed(2)}`;
    document.getElementById('lbl-iva').textContent          = `$${iva.toFixed(2)}`;
    document.getElementById('lbl-total').textContent        = `$${totalPagar.toFixed(2)}`;
}

// ── Build payload and send to the server ─────────────────────────────────────
function confirmTransaction(endpoint, successMessage) {
    const rows    = tbody.querySelectorAll('tr');
    const detalles = [];
    let hasItems  = false;

    rows.forEach(tr => {
        const prodName = tr.querySelector('.product-search').value.trim();
        if (prodName) {
            hasItems = true;
            const isMetros = tr.dataset.metros === '1';
            const altoVal  = isMetros ? (parseFloat(tr.querySelector('.alto').value)  || null) : null;
            const anchoVal = isMetros ? (parseFloat(tr.querySelector('.ancho').value) || null) : null;

            detalles.push({
                producto_id:         tr.querySelector('.product-id').value || null,
                producto:            prodName,
                cantidad:            parseFloat(tr.querySelector('.qty').value) || 0,
                costo_unitario:      parseFloat(tr.querySelector('.cost').value) || 0,
                descuento_porcentaje: parseFloat(tr.querySelector('.disc-perc').value) || 0,
                descuento_mxn:       parseFloat(tr.querySelector('.disc-mxn').value) || 0,
                total_linea:         parseFloat(tr.querySelector('.line-total').value) || 0,
                alto:                altoVal,
                ancho:               anchoVal
            });
        }
    });

    if (!hasItems) {
        alert("Agregue al menos un producto a la tabla.");
        return;
    }

    const descTotalEl = document.getElementById('lbl-desc-total');
    const lblDesc     = descTotalEl ? descTotalEl.textContent.replace('$', '') : '0';

    const clienteIdEl = document.getElementById('client-id');
    const clienteId   = (clienteIdEl && clienteIdEl.value && clienteIdEl.value !== '') 
                        ? parseInt(clienteIdEl.value, 10) 
                        : 1;

    const payload = {
        cliente_id:      clienteId,
        subtotal:        parseFloat(document.getElementById('lbl-subtotal').textContent.replace('$', '')),
        descuento_total: parseFloat(lblDesc),
        iva:             parseFloat(document.getElementById('lbl-iva').textContent.replace('$', '')),
        total:           parseFloat(document.getElementById('lbl-total').textContent.replace('$', '')),
        detalles:        detalles
    };

    fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(successMessage);
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error de conexión.');
    });
}
