const $ = require('jquery')

function googleLocationDetection()
{
    this.apiKey = ''

    this.init = function (apiKey) {
        this.apiKey = apiKey;
    }

    // Call the GoogleMap Api
    this.getAddress = function (latitude, longitude) {

        return new Promise((resolve, reject) => {
            var self = this;
            $.ajax('https://maps.googleapis.com/maps/api/geocode/json?latlng=' + latitude + ',' + longitude + '&key=' + this.apiKey)
                .then(
                    function success (response) {
                        //console.log(response)
                        if (response.error_message) {
                            return reject(response.error_message)
                        } else {
                            var data = self.extractData(response.results)
                            //console.log(data)
                            resolve(data)
                        }
                    },
                    function fail (status) {
                        return reject('Request failed.')
                    }
                )
        })
    }

    // Extract the data we need from the API response
    this.extractData = function (data) {
        var country = ""
        var countryCode = ""
        var areaLvl1 = ""
        var areaLvl2 = ""
        var city = ""

        for(var k in data[0].address_components) {
            var item = data[0].address_components[k];

            if (item.types.indexOf('country') !== -1) {
                countryCode = item.short_name
                country = item.long_name
            }

            if (item.types.indexOf('administrative_area_level_1') !== -1) {
                areaLvl1 = item.long_name
            }

            if (item.types.indexOf('administrative_area_level_2') !== -1) {
                areaLvl2 = item.long_name
            }

            if (item.types.indexOf('locality') !== -1) {
                city = item.long_name
            }
        }

        return {
            'country': country,
            'countryCode': countryCode,
            'areaLvl1': areaLvl1,
            'areaLvl2': areaLvl2,
            'city': city
        }
    }

}

module.exports = googleLocationDetection