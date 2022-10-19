var inputDateIn = document.getElementById("inputDateIn")
var inputDateOut = document.getElementById("inputDateOut")
var inputNumberAdult = document.getElementById("inputNumberAdult")

if (inputDateIn != null && inputDateOut != null && inputNumberAdult != null) {
    inputDateIn.addEventListener('change', price_resa)
    inputDateIn.addEventListener('focus', dateMin)
    inputDateOut.addEventListener('change', price_resa)
    inputDateOut.addEventListener('focus', dateMin)
    inputNumberAdult.addEventListener('change', price_resa)
}

function price_resa() {
    var dateIn = new Date(document.getElementById("inputDateIn").value)
    var dateOut = new Date(document.getElementById("inputDateOut").value)
    var numberAdult = document.getElementById("inputNumberAdult").value
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
    var year = date.getFullYear()
    var month = date.getMonth() + 1
    var day = date.getDate()

    if (month < 10) {
        month = "0" + month
    }

    if (day < 10) {
        day = "0" + day
    }

    document.getElementById("inputDateIn").setAttribute("min", year + "-" + month + "-" + day)
    document.getElementById("inputDateOut").setAttribute("min", year + "-" + month + "-" + day)
}