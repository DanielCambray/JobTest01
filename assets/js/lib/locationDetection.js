var googleLocationDetection = require('./googleLocationDetection.js')
var ipLocationDetection = require('./ipLocationDetection.js')

function locationDetection()
{
    this.googleLocationDetection = ''
    this.ipLocationDetection = ''

    this.init = function (googleApiKey) {
        this.googleLocationDetection = new googleLocationDetection()
        this.googleLocationDetection.init(googleApiKey)
        this.ipLocationDetection = new ipLocationDetection()
    }

    this.getAddressFallback1 = function(resolve, reject) {
        this.ipLocationDetection.getAddress()
            .then(data => {
                resolve(data)
            }, error => {
                return reject(error)
            })
    }

    this.getAddress = function() {

        var self = this
        return new Promise((resolve, reject) => {

            if ("geolocation" in navigator) {
                // check if geolocation is supported/enabled on current browser
                navigator.geolocation.getCurrentPosition(
                    function success(position) {
                        // for when getting location is a success
                        console.log('latitude', position.coords.latitude, 'longitude', position.coords.longitude)
                        self.googleLocationDetection.getAddress(position.coords.latitude, position.coords.longitude)
                            .then(data => {
                                //console.log(data)
                                resolve(data)
                            }, error => {
                                console.log(error)
                                // GoogleMapApi failed, we fall back to IpLocation
                                self.getAddressFallback1(resolve, reject);
                            })
                    },
                    function error(error) {
                        // GoogleMapApi failed, we fall back to IpLocation
                        console.log(error)
                        self.getAddressFallback1(resolve, reject);
                    });
            } else {
                // Geolocation is not supported
                console.log('Geolocation is not enabled on this browser')

                // We fall back to IpLocation
                self.getAddressFallback1(resolve, reject);
            }
        })
    }
}

module.exports = locationDetection