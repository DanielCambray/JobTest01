const $ = require('jquery')

function ipLocationDetection() {
    // Call the ip-api API
    this.getAddress = function () {
        return new Promise((resolve, reject) => {
            var self = this
            $.ajax('http://ip-api.com/json')
                .then(
                    function success(response) {
                        //console.log(response)
                        var data = self.extractData(response)
                        resolve(data);
                    },
                    function fail(data, status) {
                        return reject('Request failed.')
                    }
                )
        }
    )}

    // Extract the data we need from the API response
    this.extractData = function (data) {
        var countryCode = (data.countryCode) ? data.countryCode : ''
        var country = (data.country) ? data.country : ''
        var areaLvl1 = (data.regionName) ? data.regionName : ''
        var city = (data.city) ? data.city : ''

        return {
            'country': country,
            'countryCode': countryCode,
            'areaLvl1': areaLvl1,
            'city': city
        }
    }
}

module.exports = ipLocationDetection