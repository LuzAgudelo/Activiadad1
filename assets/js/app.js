// Función para mostrar alertas en el DOM
function showAlert(message, type) {
    const alertContainer = document.getElementById('alert-container');
    const alertDiv = document.createElement('div');
    alertDiv.classList.add('alert');
    alertDiv.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
    alertDiv.textContent = message;
    
    alertContainer.appendChild(alertDiv);

    // Eliminar alerta después de 5 segundos
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Función para validar el formulario de agregar producto
function validateProductForm() {
    const name = document.getElementById('product-name').value;
    const price = document.getElementById('product-price').value;
    const quantity = document.getElementById('product-quantity').value;
    
    if (!name || !price || !quantity) {
        showAlert("Todos los campos son obligatorios", "error");
        return false;
    }
    
    if (isNaN(price) || parseFloat(price) <= 0) {
        showAlert("El precio debe ser un número positivo", "error");
        return false;
    }

    if (isNaN(quantity) || parseInt(quantity) <= 0) {
        showAlert("La cantidad debe ser un número entero positivo", "error");
        return false;
    }

    return true;
}

// Función para hacer una petición AJAX para agregar un producto
function addProduct(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de manera tradicional

    if (!validateProductForm()) {
        return;
    }

    const formData = new FormData(document.getElementById('add-product-form'));

    fetch('backend/controllers/productController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert("Producto agregado con éxito", "success");
            document.getElementById('add-product-form').reset();
        } else {
            showAlert(data.message, "error");
        }
    })
    .catch(error => {
        showAlert("Error al agregar el producto: " + error, "error");
    });
}

// Función para eliminar un producto
function deleteProduct(productId) {
    if (confirm("¿Estás seguro de que quieres eliminar este producto?")) {
        fetch(`backend/controllers/productController.php?action=delete&id=${productId}`, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert("Producto eliminado con éxito", "success");
                document.getElementById(`product-${productId}`).remove(); // Eliminar el producto de la interfaz
            } else {
                showAlert(data.message, "error");
            }
        })
        .catch(error => {
            showAlert("Error al eliminar el producto: " + error, "error");
        });
    }
}

// Función para actualizar la cantidad de un producto
function updateProductQuantity(productId) {
    const newQuantity = prompt("Introduce la nueva cantidad para el producto:");

    if (newQuantity !== null && !isNaN(newQuantity) && parseInt(newQuantity) >= 0) {
        fetch(`backend/controllers/productController.php?action=update&id=${productId}&quantity=${newQuantity}`, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert("Cantidad actualizada con éxito", "success");
                document.getElementById(`quantity-${productId}`).textContent = newQuantity;
            } else {
                showAlert(data.message, "error");
            }
        })
        .catch(error => {
            showAlert("Error al actualizar la cantidad: " + error, "error");
        });
    } else {
        showAlert("La cantidad debe ser un número entero no negativo", "error");
    }
}

// Evento para el envío del formulario de agregar producto
const addProductForm = document.getElementById('add-product-form');
if (addProductForm) {
    addProductForm.addEventListener('submit', addProduct);
}

// Evento para eliminar productos
document.querySelectorAll('.delete-product-btn').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        deleteProduct(productId);
    });
});

// Evento para actualizar cantidad de productos
document.querySelectorAll('.update-quantity-btn').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        updateProductQuantity(productId);
    });
});
