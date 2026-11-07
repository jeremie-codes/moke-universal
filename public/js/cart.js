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

    function updateProductSelections(form) {
        const details = form.closest('.product-single-details');
        const selectedVariant = form.querySelector('[name="variant_id"]:checked');
        const variantOptionGroup = form.querySelector('[data-variant-option-group]');
        const variantId = selectedVariant?.value;

        form.querySelectorAll('[name="variant_option_id"]').forEach((radio) => {
            const belongsToVariant = radio.dataset.variantId === variantId;
            const isAvailable = Number.parseInt(radio.dataset.stock, 10) > 0;
            const label = radio.closest('.product-choice');

            if (radio.checked && !belongsToVariant) radio.checked = false;
            radio.disabled = !belongsToVariant || !isAvailable;
            radio.required = belongsToVariant;
            label.hidden = !belongsToVariant;
            label.classList.toggle('is-disabled', belongsToVariant && !isAvailable);
        });

        if (variantOptionGroup) variantOptionGroup.hidden = !variantId;

        form.querySelectorAll('.product-choice input[type="radio"]').forEach((radio) => {
            radio.closest('.product-choice').classList.toggle('is-selected', radio.checked);
            radio.closest('.product-choice').classList.toggle('is-disabled', radio.disabled);
        });

        const baseStock = Number.parseInt(details.querySelector('.js-add-to-cart').dataset.productBaseStock, 10);
        const chosenOptions = [
            form.querySelector('[name="product_option_id"]:checked'),
            form.querySelector('[name="variant_option_id"]:checked'),
        ]
            .filter(Boolean)
            .map((radio) => Number.parseInt(radio.dataset.stock, 10));
        const stock = chosenOptions.length ? Math.min(...chosenOptions) : baseStock;
        const quantity = details.querySelector('.js-product-quantity');
        quantity.max = stock;
        if (Number.parseInt(quantity.value, 10) > stock) quantity.value = Math.max(1, stock);
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
            const details = addButton.closest('.product-single-details');
            const optionsForm = details?.querySelector('[data-product-options]');
            if (optionsForm && !optionsForm.reportValidity()) return;

            addButton.disabled = true;
            try {
                const payload = {
                    product_id: Number(addButton.dataset.productId),
                    quantity: Number.parseInt(
                        details?.querySelector('.js-product-quantity')?.value,
                        10,
                    ) || 1,
                };
                ['variant_id', 'product_option_id', 'variant_option_id', 'delivery_type_id'].forEach((field) => {
                    const value = optionsForm?.querySelector(`[name="${field}"]:checked, select[name="${field}"]`)?.value;
                    if (value) payload[field] = Number(value);
                });

                const data = await sendRequest(addButton.dataset.url, 'POST', payload);
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
        if (input.matches('[data-product-options] select, [data-product-options] input[type="radio"]')) {
            updateProductSelections(input.form);
            return;
        }

        if (input.matches('.cart-delivery-type')) {
            const previousValue = input.dataset.savedDeliveryType;
            try {
                await sendRequest(input.dataset.url, 'PATCH', {
                    delivery_type_id: input.value ? Number(input.value) : null,
                });
                input.dataset.savedDeliveryType = input.value;
            } catch (error) {
                input.value = previousValue;
                window.alert(error.message);
            }
            return;
        }

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

    document.querySelectorAll('[data-product-options]').forEach(updateProductSelections);
})();
