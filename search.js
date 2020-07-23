window.addEventListener('DOMContentLoaded', function () {
    let div = document.querySelector('.tracking-result'),
        form = document.querySelector('.traking-form'),
        inp = document.querySelector('.traking-form input[name="search-value"]'),
        button = document.querySelector('.traking-form input[type="submit"]');

    async function getData() {
        return await fetch(`${document.location.origin}/wp-content/plugins/Import-tracking/shipping-search.php?id=${inp.value}`).then(res => res.json())
    }

    function createElem(elem, className, inner) {
        let element = document.createElement(elem)
        if (className) {
            element.classList.add(className)
        }

        if (inner) {
            element.innerHTML = inner
        }

        return element
    }

    class Cargo {
        constructor(name) {
            this.elem = createElem('div', 'cargo', `<h2>Партия ${name}</h2>`)
        }
    }

    class List {
        constructor() {
            this.elem = createElem('ul', 'cargo-data')
        }
    }

    class ListItem {
        constructor(listItem) {
            this.elem = createElem('li', 'cargo-data__item', listItem)
        }
    }

    class PathElem {
        constructor(icon, name, date, isActive) {
            this.elem = createElem('div', 'cargo-path__item')
            let pathName = createElem('div', 'cargo-path__name', name)
            let pathDate = createElem('div', 'cargo-path__date', date)
            let pathIcon = createElem('div', 'cargo-path__icon', icon)
            this.elem.appendChild(pathIcon)
            this.elem.appendChild(pathName)
            this.elem.appendChild(pathDate)

            if (isActive) {
                this.elem.classList.add('cargo-path__item_active')
            }
        }
    }

    if (div && form) {

        inp.addEventListener('input', ()=>{
            if(!inp.value.match(new RegExp(/^\d+$/))){
                inp.value = inp.value.slice(0, inp.value.length - 1)
            }

            if(inp.value.length > 4){
                inp.value = inp.value.slice(0, 4)
            }
        })

        form.addEventListener('submit', async (e) => {
            e.preventDefault()

            div.innerText = 'Загрузка...'

            button.disabled = true

            let data;

            try {
                data = await getData();
            }
            catch (err) {
                div.innerText = 'Ошибка запроса'
                button.disabled = false
            }

            if (!data.length) {
                div.innerText = 'Поиск не дал результатов'
                button.disabled = false
                return
            }
            data = await getData();
            console.log(data)
            div.innerText = ''
            data.forEach(dataItem => {
                let cargo = new Cargo(dataItem.cargo_id)

                let cargoData = new List()

                let cargoWeight = new ListItem(`<strong>Общий вес:</strong> ${dataItem.cargo_weight} кг`)
                let cargoSize = new ListItem(`<strong>Общий объем:</strong> ${dataItem.cargo_size} м<sup>3<sup>`)
                let cargoCount = new ListItem(`<strong>Данные обновлены </strong> ${dataItem.cargo_count}`)

                cargoSize.elem.style = "position:relative;top:-3px;"


                cargoData.elem.appendChild(cargoCount.elem)
                cargoData.elem.appendChild(cargoSize.elem)
                cargoData.elem.appendChild(cargoWeight.elem)
                cargo.elem.appendChild(cargoData.elem)

                let cargoPath = createElem('div', 'cargoPath')

                let cargoIcons = [
                    '<i class="fas fa-warehouse" style="position:relative;bottom: 2px;"></i>',
                    '<i class="fas fa-truck" style="position:relative;left: 3px;"></i>',
                    '<i class="fas fa-file-alt" style="position:relative;left: 1px;"></i>',
                    '<i class="fas fa-truck-loading" style="position:relative;right: 2px;"></i>',
                    '<i class="fas fa-check" style="position:relative;left: 2px;"></i>'
                ]


                cargoIcons.forEach((icon, index) => {
                    let date = dataItem.cargo_dates[index].status_date !== '0' ? dataItem.cargo_dates[index].status_date : ''
                    let pathItem = new PathElem(icon, dataItem.cargo_dates[index].status_name, date, index < dataItem.cargo_current_status ? true : false)

                    if(index != 0 && index != cargoIcons.length){
                        let arrow = createElem('div', 'cargo-path__arrow', '<i class="fas fa-arrow-right"></i>')

                        if(index <= dataItem.cargo_current_status){
                            arrow.classList.add('cargo-path__arrow_active') 
                        }

                        cargoPath.appendChild(arrow)
                    }
                    cargoPath.appendChild(pathItem.elem)
                })

                div.appendChild(cargo.elem)

                div.appendChild(cargoPath)

                button.disabled = false
            });
        });
    }
})
