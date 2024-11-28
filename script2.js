<script>
    // Функция для выделения всех чекбоксов
    document.getElementById('select-all').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.delete-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Проверка перед удалением
    document.getElementById('delete-product-btn').addEventListener('click', function (event) {
        const checkboxes = document.querySelectorAll('.delete-checkbox:checked');
        if (checkboxes.length === 0) {
            alert("Please select at least one product to delete.");
            event.preventDefault();
        } else if (!confirm("Are you sure you want to delete selected products?")) {
            event.preventDefault();
        }
    });
</script>
