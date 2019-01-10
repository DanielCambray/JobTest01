const $ = require('jquery')
var LocationDetection = require('./lib/locationDetection')

locationDetection = new LocationDetection()
locationDetection.init(params.googleMapKey)
locationDetection.getAddress()
    .then(data => {
        console.log(data)
        process(data)
    }, error => {
        console.log(error)
        process({})
    })

// Show the location if any and the form after the detection have been made
function process(data)
{
    $("#form-subscription").removeClass("hidden")
    $("#dialog-welcome").addClass("hidden")

    var location = "";
    if (data.country) {
        location = data.country
    }

    if (data.areaLvl1) {
        location += ", " + data.areaLvl1
    }

    if (data.areaLvl2) {
        location += ", " + data.areaLvl2
    }

    if (data.city) {
        location += ", " + data.city
    }

    // If we have been able to detect the country
    if (data.country !== undefined) {
        $("#dialog-success").removeClass("hidden").html("We have detected that you're from " + location)
    } else {
        $("#dialog-error").removeClass("hidden").html("We couldn't find where you're from.")
    }

    $("#account_country option[value=" + data.countryCode + "]").attr('selected', 'selected')
}
