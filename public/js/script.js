function price() {
    var dateIn = new Date(document.getElementById("inputDateIn").value)
    var dateOut = new Date(document.getElementById("inputDateOut").value)

    if (dateOut.getTime() < dateIn.getTime()) {
        document.getElementById("priceRender").textContent = "La date de fin de séjour est antérieure à la date de début";
    }
    else {
        var diff_time = dateOut.getTime() - dateIn.getTime();
        var nbr_day = (diff_time / (1000 * 3600 * 24)) + 1

        if (isNaN(nbr_day)) {
            document.getElementById("priceRender").textContent = "";
        }
        else {
            var price = (nbr_day * 60)
            document.getElementById("priceRender").textContent = "Le prix est de " + price + "€";
        }
    }
}

function dateMin() {
    var date = new Date()
    var year = date.getFullYear()
    var month = date.getMonth() + 1
    var day = date.getDate()

    if(month < 10){
        month = "0" + month
    }

    if(day < 10){
        day = "0" + day
    }

    document.getElementById("inputDateIn").setAttribute("min", year + "-" + month + "-" + day)
    document.getElementById("inputDateOut").setAttribute("min", year + "-" + month + "-" + day)
}