(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const money = new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    function formatMoney(value) {
        return `${money.format(value)} $`;
    }

    function updateBadges(count) {
        document.querySelectorAll('[data-cart-count]').forEach((badge) => {
            badge.textContent = count;
        });
    }

    function recalculateCart() {
        let subtotal = 0;
        const rows = document.querySelectorAll('.cart-line');

        rows.forEach((row) => {
            const quantityInput = row.querySelector('.cart-quantity');
            const quantity = Number.parseInt(quantityInput.value, 10);
            if (!Number.isInteger(quantity) || quantity < 1) return;

            const lineSubtotal = Number(row.dataset.unitPrice) * quantity;
            subtotal += lineSubtotal;
            row.querySelector('.subtotal-price').textContent = formatMoney(lineSubtotal);
        });

        document.querySelectorAll('[data-cart-subtotal], [data-cart-total]').forEach((total) => {
            total.textContent = formatMoney(subtotal);
        });
    }

    async function sendRequest(url, method, payload) {
        const response = await fetch(url, {
            method,
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: payload ? JSON.stringify(payload) : undefined,
        });
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'La mise à jour du panier a échoué.');
        }

        return data;
    }

    document.addEventListener('click', async (event) => {
        const addButton = event.target.closest('.js-add-to-cart');
        if (addButton) {
            addButton.disabled = true;
            try {
                const data = await sendRequest(addButton.dataset.url, 'POST', {
                    product_id: Number(addButton.dataset.productId),
                    quantity: Number.parseInt(
                        addButton.closest('.product-single-details')?.querySelector('.js-product-quantity')?.value,
                        10,
                    ) || 1,
                });
                updateBadges(data.cart_count);
                window.showCartMiniPopup?.({
                    name: addButton.dataset.productName,
                    imageUrl: addButton.dataset.productImage,
                    productUrl: addButton.dataset.productUrl,
                });
                const label = addButton.querySelector('span');
                if (label) {
                    const previousLabel = label.textContent;
                    label.textContent = 'AJOUTÉ';
                    window.setTimeout(() => { label.textContent = previousLabel; }, 1200);
                }
            } catch (error) {
                window.alert(error.message);
            } finally {
                addButton.disabled = false;
            }
            return;
        }

        const removeButton = event.target.closest('.js-remove-cart-item');
        if (!removeButton) return;

        removeButton.disabled = true;
        try {
            const data = await sendRequest(removeButton.dataset.url, 'DELETE');
            removeButton.closest('.cart-line').remove();
            updateBadges(data.cart_count);
            if (document.querySelectorAll('.cart-line').length === 0) {
                const container = document.querySelector('.cart-table-container');
                if (container) container.innerHTML = '<p class="py-5 text-center" data-empty-cart>Votre panier est vide.</p>';
            }
            recalculateCart();
        } catch (error) {
            window.alert(error.message);
            removeButton.disabled = false;
        }
    });

    document.addEventListener('input', (event) => {
        if (event.target.matches('.cart-quantity')) {
            const input = event.target;
            const stock = Number.parseInt(input.dataset.stock, 10);
            if (input.value !== '' && Number.isInteger(stock)) {
                input.value = Math.max(1, Math.min(Number.parseInt(input.value, 10) || 1, stock));
            }
            recalculateCart();
        }
    });

    document.addEventListener('change', async (event) => {
        const input = event.target;
        if (!input.matches('.cart-quantity')) return;

        const quantity = Math.max(1, Math.min(
            Number.parseInt(input.value, 10) || 1,
            Number.parseInt(input.dataset.stock, 10),
        ));
        input.value = quantity;

        try {
            const data = await sendRequest(input.dataset.url, 'PATCH', { quantity });
            input.dataset.savedQuantity = quantity;
            updateBadges(data.cart_count);
            input.closest('.cart-line').querySelector('.subtotal-price').textContent = formatMoney(data.line_subtotal);
            document.querySelectorAll('[data-cart-subtotal], [data-cart-total]').forEach((total) => {
                total.textContent = formatMoney(data.subtotal);
            });
        } catch (error) {
            input.value = input.dataset.savedQuantity;
            recalculateCart();
            window.alert(error.message);
        }
    });
})();
