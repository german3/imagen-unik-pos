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
});

function fetchNextSaleId() {
    const folioInput = document.getElementById('folio-venta');
    if (!folioInput) return;
    fetch('api/get_next_sale_id.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                folioInput.value = data.next_id;
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

        // If the user clears or types very little, reset to Público General
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
                            // mousedown fires before blur so we can safely update the inputs
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

    // Hide suggestions when focus leaves the input
    clientInput.addEventListener('blur', () => {
        setTimeout(() => { suggestionsEl.style.display = 'none'; }, 200);
    });

    // Clear id when user manually edits the name (forces re-selection)
    clientInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            suggestionsEl.style.display = 'none';
        } else if (e.key === 'Enter') {
            // Select the first suggestion if available
            const first = suggestionsEl.querySelector('.autocomplete-suggestion');
            if (first) first.dispatchEvent(new MouseEvent('mousedown'));
            suggestionsEl.style.display = 'none';
        } else {
            // Any other key -> user is editing, reset stored id
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
    tr.innerHTML = `
        <td class="line-number center">${lineCounter}</td>
        <td>
            <input type="text" class="td-input product-search" placeholder="Escribir producto...">
            <input type="hidden" class="product-id">
            <div class="autocomplete-suggestions"></div>
        </td>
        <td><input type="number" class="td-input center qty" value="1" min="1" step="1"></td>
        <td><input type="number" class="td-input right cost" value="0.00" step="0.01"></td>
        <td><input type="number" class="td-input center disc-perc" value="0" min="0" max="100" step="1"></td>
        <td><input type="number" class="td-input right disc-mxn" value="0.00" readonly></td>
        <td><input type="number" class="td-input right line-total" value="0.00" readonly></td>
        <td class="center"><button class="row-delete-btn" tabindex="-1">×</button></td>
    `;
    tbody.appendChild(tr);
    lineCounter++;

    attachRowEvents(tr);
    
    // Focus new product input
    tr.querySelector('.product-search').focus();
}

let searchTimeout;

function attachRowEvents(tr) {
    const productInput = tr.querySelector('.product-search');
    const suggestionsBox = tr.querySelector('.autocomplete-suggestions');
    const qtyInput = tr.querySelector('.qty');
    const costInput = tr.querySelector('.cost');
    const discPercInput = tr.querySelector('.disc-perc');
    const deleteBtn = tr.querySelector('.row-delete-btn');

    // Autocomplete Logic
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
                            div.innerHTML = `<span>${item.descripcion}</span> <small class="text-muted">$${item.precio}</small>`;
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

    // Hide suggestions on blur (with delay to allow click)
    productInput.addEventListener('blur', () => {
        setTimeout(() => { suggestionsBox.style.display = 'none'; }, 200);
    });

    // Keydown to handle Enter on product search (to allow custom product or move to next)
    productInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            suggestionsBox.style.display = 'none';
            // Move focus to quantity
            qtyInput.focus();
            qtyInput.select();
        }
    });

    // Add new row if editing the last row's quantity or other fields
    const inputs = [qtyInput, costInput, discPercInput];
    inputs.forEach(input => {
        input.addEventListener('input', () => calculateRow(tr));
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                // If this is the last row and it has data, add a new row
                if (tr === tbody.lastElementChild && productInput.value.trim() !== '') {
                    addNewRow();
                } else if(tr !== tbody.lastElementChild) {
                    // move focus to next row's product search
                    const nextRow = tr.nextElementSibling;
                    if(nextRow) nextRow.querySelector('.product-search').focus();
                }
            }
        });
    });

    deleteBtn.addEventListener('click', () => {
        if (tbody.children.length > 1) {
            tr.remove();
            calculateTotals();
        } else {
            // Just clear it
            tr.querySelectorAll('input').forEach(inp => {
                if(inp.type === 'number') inp.value = inp.classList.contains('qty') ? '1' : '0.00';
                else inp.value = '';
            });
            calculateTotals();
        }
    });
}

function selectProduct(tr, item) {
    tr.querySelector('.product-search').value = item.descripcion;
    tr.querySelector('.product-id').value = item.id;
    tr.querySelector('.cost').value = parseFloat(item.precio).toFixed(2);
    
    calculateRow(tr);
    tr.querySelector('.qty').focus();
    tr.querySelector('.qty').select();
}

function calculateRow(tr) {
    const qty = parseFloat(tr.querySelector('.qty').value) || 0;
    const cost = parseFloat(tr.querySelector('.cost').value) || 0;
    const discPerc = parseFloat(tr.querySelector('.disc-perc').value) || 0;

    const sub = qty * cost;
    const discMxn = sub * (discPerc / 100);
    const totalLine = sub - discMxn;

    tr.querySelector('.disc-mxn').value = discMxn.toFixed(2);
    tr.querySelector('.line-total').value = totalLine.toFixed(2);

    calculateTotals();
}

function calculateTotals() {
    let subtotalSinIva = 0;
    let descuentoTotal = 0;

    const rows = tbody.querySelectorAll('tr');
    rows.forEach(tr => {
        const qty = parseFloat(tr.querySelector('.qty').value) || 0;
        const cost = parseFloat(tr.querySelector('.cost').value) || 0;
        const discMxn = parseFloat(tr.querySelector('.disc-mxn').value) || 0;
        
        subtotalSinIva += (qty * cost);
        descuentoTotal += discMxn;
    });

    const subtotalConDescuento = subtotalSinIva - descuentoTotal;
    const iva = subtotalConDescuento * 0.16;
    const totalPagar = subtotalConDescuento + iva;

    document.getElementById('lbl-subtotal').textContent = `$${subtotalSinIva.toFixed(2)}`;
    document.getElementById('lbl-desc-total').textContent = `$${descuentoTotal.toFixed(2)}`;
    document.getElementById('lbl-subtotal-desc').textContent = `$${subtotalConDescuento.toFixed(2)}`;
    document.getElementById('lbl-iva').textContent = `$${iva.toFixed(2)}`;
    document.getElementById('lbl-total').textContent = `$${totalPagar.toFixed(2)}`;
}

function confirmTransaction(endpoint, successMessage) {
    const rows = tbody.querySelectorAll('tr');
    const detalles = [];
    let hasItems = false;

    rows.forEach(tr => {
        const prodName = tr.querySelector('.product-search').value.trim();
        if (prodName) {
            hasItems = true;
            detalles.push({
                producto_id: tr.querySelector('.product-id').value || null,
                producto: prodName,
                cantidad: parseFloat(tr.querySelector('.qty').value) || 0,
                costo_unitario: parseFloat(tr.querySelector('.cost').value) || 0,
                descuento_porcentaje: parseFloat(tr.querySelector('.disc-perc').value) || 0,
                descuento_mxn: parseFloat(tr.querySelector('.disc-mxn').value) || 0,
                total_linea: parseFloat(tr.querySelector('.line-total').value) || 0
            });
        }
    });

    if (!hasItems) {
        alert("Agregue al menos un producto a la tabla.");
        return;
    }

    // Get subtotal from lbl-subtotal-desc, but fallback to raw lbl-subtotal
    const descTotalEl = document.getElementById('lbl-desc-total');
    const lblDesc = descTotalEl ? descTotalEl.textContent.replace('$','') : '0';

    // Use the client selected via autocomplete; fall back to 1 (Público General)
    const clienteIdEl = document.getElementById('client-id');
    const clienteId   = (clienteIdEl && clienteIdEl.value && clienteIdEl.value !== '') 
                        ? parseInt(clienteIdEl.value, 10) 
                        : 1;

    const payload = {
        cliente_id: clienteId,
        subtotal: parseFloat(document.getElementById('lbl-subtotal').textContent.replace('$','')),
        descuento_total: parseFloat(lblDesc),
        iva: parseFloat(document.getElementById('lbl-iva').textContent.replace('$','')),
        total: parseFloat(document.getElementById('lbl-total').textContent.replace('$','')),
        detalles: detalles
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
