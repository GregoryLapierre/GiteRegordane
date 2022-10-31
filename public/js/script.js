var inputDateIn = document.getElementById("inputDateIn")
var inputDateOut = document.getElementById("inputDateOut")
var inputNumberAdult = document.getElementById("inputNumberAdult")

new Litepicker({
    element: inputDateIn,
    elementEnd: inputDateOut,
    singleMode: false,
    allowRepick: true,
    lang: "fr",
    lockDays: datas,
    disallowLockDaysInRange: true,
    format : "YYYY-MM-DD",
    resetButton: true,
    tooltipText: {"one":"jour","other":"jours"},
    autoRefresh: true,
    minDate: dateMin(),
    setup: (picker) => {
     picker.on('selected', (startDate, endStart) => {
         price_resa()
         })
     }
  });

if (inputDateIn != null && inputDateOut != null && inputNumberAdult != null) {
    inputNumberAdult.addEventListener('change', price_resa)
}

function price_resa() {
    var dateIn = new Date(inputDateIn.value)
    var dateOut = new Date(inputDateOut.value)
    var numberAdult = inputNumberAdult.value
    if (dateOut.getTime() <= dateIn.getTime()) {
        document.getElementById("priceRender").innerHTML = "<h5>Veuillez indiquer une date de fin de séjour supérieure à la date de début</h5>";
    }
    else {
        document.getElementById("priceRender").innerHTML = "";
        var diff_time = dateOut.getTime() - dateIn.getTime();
        var nbr_day = (diff_time / (1000 * 3600 * 24)) + 1

        if (isNaN(nbr_day) || !numberAdult) {
            return
        }
        else {
            var tourismTax = (nbr_day - 1) * numberAdult * 0.6
            var totalTax = (tourismTax + (tourismTax / 10)).toFixed(2)
            var price = ((nbr_day * 60) + parseFloat(totalTax)).toFixed(2)
            var deposit = (parseFloat(price) * 0.3).toFixed(2)
            document.getElementById("priceRender").innerHTML = "<h5>Le prix de la taxe de séjour est de " + totalTax + "€<br>" + "Le prix du séjour est de " + price + "€</h5><br><br>" + "<h4>Acompte de 30% à payer immédiatement :</h4><br><h3><strong>" + deposit + "€</strong></h3>";
        }
    }
}

function dateMin() {
    var date = new Date()
    date.setDate(date.getDate() + 1)
    var year = date.getFullYear()
    var month = date.getMonth() + 1
    var day = date.getDate()
    
    if (month < 10) {
        month = "0" + month
    }

    if (day < 10) {
        day = "0" + day
    }

    return year + "-" + month + "-" + day
}
