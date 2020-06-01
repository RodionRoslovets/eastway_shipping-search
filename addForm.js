window.addEventListener('DOMContentLoaded', function () {
    let wrapper = document.querySelector('.wrap');
    let siteName = document.location.origin;

    if (wrapper) {
        let form = document.createElement('form'),
            fileInp = document.createElement('input'),
            submitInp = document.createElement('input'),
            formHeader = document.createElement('p'),
            formError = document.createElement('p');

        formHeader.innerHTML = 'Выберите файл для импорта <br> *Файл должен имет расширение csv';

        fileInp.type = 'file';
        fileInp.name = 'csv-import';

        submitInp.type = 'submit';
        submitInp.value = 'Импортировать';

        form.appendChild(formHeader);
        form.appendChild(fileInp);
        form.appendChild(submitInp);
        form.appendChild(formError);

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (fileInp.files.length) {

                if (fileInp.files[0].name.slice(-3) == 'csv') {
                    
                    formError.innerText = '';

                    let body = new FormData(form);

                    // Здесь запрос на сервер

                    fetch(siteName + '/wp-content/plugins/Import-tracking/write-script.php',{method:'POST',headers:{ContentType:'form/multipart',enctype:"multipart/form-data"},body:body}).
                        then(function(){formError.innerText = 'Файл импортирован';}).
                        catch(function(){formError.innerText = 'Запрос не удался';});

                } else {

                    formError.innerText = 'Неверный тип файла';
                }
            } else {
                formError.innerText = 'Файл не выбран';
            }
        });

        wrapper.appendChild(form);
    }
});