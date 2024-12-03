document.addEventListener('DOMContentLoaded', function() {
    const productTypeSelect = document.getElementById('productType');
    const dvdFields = document.getElementById('type-dvd');
    const bookFields = document.getElementById('type-book');
    const furnitureFields = document.getElementById('type-furniture');

    function toggleFields() {
        const selectedType = productTypeSelect.value;

        dvdFields.classList.add('hidden');
        bookFields.classList.add('hidden');
        furnitureFields.classList.add('hidden');

        if (selectedType === 'DVD') {
            dvdFields.classList.remove('hidden');
        } else if (selectedType === 'Book') {
            bookFields.classList.remove('hidden');
        } else if (selectedType === 'Furniture') {
            furnitureFields.classList.remove('hidden');
        }
    }

    productTypeSelect.addEventListener('change', toggleFields);
    toggleFields(); // Вызов функции при загрузке страницы, чтобы правильно отобразить поля
});
ысф