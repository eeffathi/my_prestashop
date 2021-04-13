/**
 * NOTICE OF LICENSE.
 *
 * This source file is subject to a commercial license from BSofts.
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the BSofts is strictly forbidden.
 *
 *  @author    BSoft Inc
 *  @copyright 2020 BSoft Inc.
 *  @license   Commerical License
 */

var input = document.querySelector("input[name=phone]");
var telInput = null, countryDropdown = document.querySelector('select[name=id_country]');
var GoogleAddress = {
  geoCoder: null,

  googleMap: null,

  googleMarker: null,

  infoWindow: null,

  searchBox: null,

  inputField: null,
  
  inputCity : null,

  inputPostcode : null,

  inputAddress2 : null,

  inputCountry: null,

  inputState: null,

  mapTypeId: null,

  bounds: {},

  lastPosition: {},

  boundsSet: false,

  draggable: false,

  mapZoom: GOOGLE_MAP_ZOOM_LEVEL,

  styles: JSON.parse(GOOGLE_MAP_THEME),

  isMapEnabled: GOOGLE_ADDRESS_GOOGLE_MAP,

  longitude: defaultLong,

  latitude: defaultLat,

  mapElement: "google-address-map-canvas",

  /**
   * initialize app - this is the entry point of the app
   */
  init: function () {
    //GoogleAddress.initFormFields();
    //GoogleAddress.inputField = document.getElementById("google-address-field");
    GoogleAddress.initGoogleAddress(150, 50);
    $(document).on(
      "change",
      "input[name=address1]",
      GoogleAddress.locateGoogleAddress
    );
    
    if ($(GoogleAddress.inputField).length) {
      $(GoogleAddress.inputField).trigger('chage');
    }
  },

  /**
   * setting address form field attributes
   */
  initFormFields: function () {
    GoogleAddress.inputCity = $('input[name=city]').get(0);
    GoogleAddress.inputPostcode = $('input[name=postcode]').get(0);
    GoogleAddress.inputAddress1 = $('input[name=address1]').get(0);
    GoogleAddress.inputAddress2 = $('input[name=address2]').get(0);
    GoogleAddress.inputCountry = $('select[name=id_country]').get(0);
    GoogleAddress.inputState = $('select[name=id_state]').get(0);
    GoogleAddress.inputField = $('input[name=address1]').get(0);

    if (GoogleAddress.isMapEnabled) {
      $(GoogleAddress.inputField).after(
      $('<div/>', {'class': 'form-group row'}).append(
          $('<div/>', {'class': 'col-md-3'})
        ).append(
          $('<div/>', {'class': 'col-md-12'}).append(
            $('<div/>',
              {
                id: "google-address-map-canvas",
                style: "width:100%; height: 500px; border: 1px solid #000;"
              }
            )
          )
        )
      );
    }
  },

  /**
   * initialize google map instance
   * @param {integer} width
   * @param {integer} height
   */
  initGoogleAddress: function (width, height) {
    if (!width) {
      width = 50;
    }

    if (!height) {
      height = 50;
    }
    // init searchbox
    var addressRestriction = {
      types: ['address']
    };
    GoogleAddress.searchBox = new google.maps.places.Autocomplete(
      GoogleAddress.inputField,
      //addressRestriction,
    );

    // Set initial restrict to the greater list of countries.
    // GoogleAddress.searchBox.setComponentRestrictions({
    //   country: JSON.parse(countryIso)
    // });

    GoogleAddress.searchBox.addListener(
      "place_changed",
      GoogleAddress.locateGoogleAddress
    );

    if (GoogleAddress.isMapEnabled) {
      // init info window
      GoogleAddress.infoWindow = new google.maps.InfoWindow({
        size: new google.maps.Size(width, height)
      });
      // init geocoder
      GoogleAddress.geoCoder = new google.maps.Geocoder();

      // initialize map with current address
      if ($.trim($(GoogleAddress.inputField).val())) {
        GoogleAddress.initGeocodeRequest($.trim($(GoogleAddress.inputField).val()));
      }
      var latlng = new google.maps.LatLng(
        GoogleAddress.latitude,
        GoogleAddress.longitude
      );

      var mapOptions = {
        zoom: GoogleAddress.mapZoom,
        center: latlng,
        styles: GoogleAddress.styles,
        mapTypeId: google.maps.MapTypeId.ROADMAP //GoogleAddress.mapTypeId
      };

      // init google Map
      GoogleAddress.googleMap = new google.maps.Map(
        document.getElementById(GoogleAddress.mapElement),
        mapOptions
      );

      GoogleAddress.bounds = new google.maps.LatLngBounds();
      GoogleAddress.lastPosition = latlng;

      google.maps.event.addListener(GoogleAddress.googleMap, 'bounds_changed', function () {
        if (!GoogleAddress.boundsSet) {
          GoogleAddress.bounds = GoogleAddress.googleMap.getBounds();
          GoogleAddress.boundsSet = true;
        }
      });

      google.maps.event.addListener(GoogleAddress.googleMap, "click", function () {
        GoogleAddress.infoWindow.close();
      });
    }
  },

  /**
   * locate address on google map
   */
  locateGoogleAddress: function () {
    //var inputField = $(this);
    var place = GoogleAddress.searchBox.getPlace();
    if (typeof place !== 'undefined') {
      //auto filling address
      GoogleAddress.autoFillAddressFields(place);

      if (GoogleAddress.isMapEnabled) {
        GoogleAddress.initGeocodeRequest(place.formatted_address);
      }
    }
  },

  /**
   * initiate geocode address request
   * @param {string} googleAddress 
   */
  initGeocodeRequest: function (googleAddress) {
    if (googleAddress && googleAddress != "") {
      GoogleAddress.geoCoder.geocode(
        {
          address: googleAddress
        },
        function (results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            GoogleAddress.googleMap.setCenter(results[0].geometry.location);
            if (GoogleAddress.googleMarker) {
              GoogleAddress.googleMarker.setMap(null);
              if (GoogleAddress.infoWindow) {
                GoogleAddress.infoWindow.close();
              }
            }

            GoogleAddress.googleMarker = new google.maps.Marker({
              map: GoogleAddress.googleMap,
              draggable: GoogleAddress.draggable,
              position: results[0].geometry.location
            });

            google.maps.event.addListener(
              GoogleAddress.googleMarker,
              "dragend",
              function () {
                var position = GoogleAddress.googleMarker.getPosition();
                if (GoogleAddress.bounds.contains(position)) {
                  GoogleAddress.lastPosition = position;

                } else {
                  GoogleAddress.googleMarker.setPosition(GoogleAddress.lastPosition);
                }
                GoogleAddress.locateGeocodePosition(
                  GoogleAddress.lastPosition
                  //GoogleAddress.googleMarker.getPosition()
                );
              }
            );

            google.maps.event.addListener(
              GoogleAddress.googleMarker,
              "click",
              function () {
                if (GoogleAddress.googleMarker.formatted_address) {
                  GoogleAddress.infoWindow.setContent(
                    GoogleAddress.googleMarker.formatted_address
                  );
                } else {
                  GoogleAddress.infoWindow.setContent(googleAddress);
                }
                GoogleAddress.infoWindow.open(
                  GoogleAddress.googleMap,
                  GoogleAddress.googleMarker
                );
              }
            );

            google.maps.event.trigger(GoogleAddress.googleMarker, "click");
          } else {
            alert(
              "Geocode was not successful for the following reason: " + status
            );
          }
        }
      );
    }
  },

  /**
   * update marker position on drag
   * @param {object} position
   */
  locateGeocodePosition: function (position) {
    GoogleAddress.geoCoder.geocode(
      {
        latLng: position
      },
      function (responses) {
        if (responses && responses.length > 0) {
          GoogleAddress.googleMarker.formatted_address =
            responses[0].formatted_address;
        } else {
          GoogleAddress.googleMarker.formatted_address = undetermined_add_label;
        }
        GoogleAddress.infoWindow.setContent(
          GoogleAddress.googleMarker.formatted_address
        ); // + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
        GoogleAddress.infoWindow.open(
          GoogleAddress.googleMap,
          GoogleAddress.googleMarker
        );
        GoogleAddress.inputField.value =
          GoogleAddress.googleMarker.formatted_address;
      }
    );
    GoogleAddress.updateState(position);
  },

  /**
   * update state after marker position update on address form
   * @param {object} latlng
   */
  updateState: function (latlng) {
    GoogleAddress.geoCoder.geocode({ location: latlng }, function (
      results,
      status
    ) {
      if (status === "OK") {
        if (results[0]) {
          GoogleAddress.autoFillAddressFields(results[0]);
        } else {
          window.alert("No results found");
        }
      } else {
        window.alert("Geocoder failed due to: " + status);
      }
    });
  },

  /**
   * update state after marger dragged
   * @param {object} place
   */
  autoFillAddressFields: function (place) {
    var componentForm = {
      street_number: "short_name", //street address
      route: "long_name", //street number
      country: "short_name", //country
      locality: "long_name", //city
      postal_code: "short_name", //zipcode
      premise: 'short_name',
      sublocality: 'long_name',
      neighborhood: 'long_name',
      administrative_area_level_1: "short_name", //state
      administrative_area_level_2: 'short_name',
    };
    // Get each component of the address from the place details,
    // and then fill-in the corresponding field on the form.
    var isoState = false;
    var isoCountry = false;
    var addressChunks = [];

    // reset fields beofre start
    GoogleAddress.resetFormFields();
    if (typeof place !== "undefined" && typeof place.address_components !== "undefined") {
      for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
          var val = place.address_components[i][componentForm[addressType]];
          switch (addressType) {
            case 'street_number':
                addressChunks.push(`${val},`);
                break;
            case 'route':
                addressChunks.push(val);
                break;
            case 'premise':
                addressChunks.push(`${val}/`);
                break;
            case 'locality':
                if (GOOGLE_ADDRESS_AUTOFILL_CITY == 1) {
                    $(GoogleAddress.inputCity).val(val);
                }
                break;
            case 'postal_code':
                if (GOOGLE_ADDRESS_AUTOFILL_ZIPCODE == 1) {
                    $(GoogleAddress.inputPostcode).val(val).trigger('keypress').trigger('blur');
                }
                break;
            case 'country':
                isoCountry = val;
                break;
            case 'administrative_area_level_1':
                isoState = val;
                break;
            case 'administrative_area_level_2':
                if (GOOGLE_ADDRESS_AUTOFILL_ADDRESS2 == 1) {
                  $(GoogleAddress.inputAddress2).val(val);
                }
                break;
          }
        }
      }
      if (place.name !== undefined) {
        GoogleAddress.inputAddress1.value = place.name;
      } else {
        GoogleAddress.inputAddress1.value = addressChunks.join('');
      }
    }

    if (isoCountry) {
      GoogleAddress.setCountry(isoCountry, isoState);
    }
  },

  /**
  * set state field on form
  * @param {string} isoState
  * @param {string} isoCountry
  */
  setCountry: function (isoCountry, isoState) {
    if (GOOGLE_ADDRESS_AUTOFILL_COUNTRY == 1) {
      $.get(
        gAddressUrl,
        { ajax: true, iso_country: isoCountry, action: "getCountry" },
        "json"
      ).done(function (response) {
        if (
          typeof response !== "undefined" &&
          response.success &&
          response.id_country
        ) {
          if ($(GoogleAddress.inputCountry).length) {
            $(GoogleAddress.inputCountry).val(response.id_country).trigger('change');
            if (isoState) {
              setTimeout(function () {
                GoogleAddress.setState(isoState, isoCountry);
              }, 300);
            }

            if (GOOGLE_ADDRESS_INTL_PHONE == 1) {
              var itiTemp = window.intlTelInputGlobals.getInstance(input);
              itiTemp.destroy();
              intlNumber(response.id_country);
            }
          }

          if (response.hasState == false) {
            $(GoogleAddress.inputState).closest('.form-group').hide();
          } else {
            $(GoogleAddress.inputState).closest('.form-group').show();
          }
        }
      });
    }
  },

  /**
   * set state field on form
   * @param {string} isoState
   * @param {string} isoCountry
   */
  setState: function (isoState, isoCountry) {
    if (GOOGLE_ADDRESS_AUTOFILL_STATE == 1) {
      $.get(
        gAddressUrl,
        { iso_state: isoState, iso_country: isoCountry, action: "getState" },
        "json"
      ).done(function (response) {
        if (
          typeof response !== "undefined" &&
          response.success &&
          response.id_state
        ) {

          if ($(GoogleAddress.inputState).length) {
            $(GoogleAddress.inputState).each(function (e) {
              $(this).removeAttr('selected');
            })
            $(GoogleAddress.inputState).val(response.id_state).trigger('change');
          }
        }
      });
    }
  },

  /**
   * resset address form fields
   * @return void
   */
  resetFormFields: function() {
    GoogleAddress.inputCity.value = '';
    GoogleAddress.inputPostcode.value = '';
    GoogleAddress.inputAddress1.value = '';
    GoogleAddress.inputAddress2.value = '';
    //GoogleAddress.inputCountry.value = '';
    //GoogleAddress.inputState.value = '';
  }
};

addScript(
  "https://maps.googleapis.com/maps/api/js?libraries=places&key=" +
  GOOGLE_API_KEY + '&language=' + ISO_LANG
);

$(document).ready(function () {
  var countries = JSON.parse(gAcountries)
  input = document.querySelector("input[name=phone]");

  GoogleAddress.initFormFields();
  if ($(GoogleAddress.inputField).length) {
    GoogleAddress.init();
  }

  if ($(input).length && GOOGLE_ADDRESS_INTL_PHONE == 1) {
    initErrors(input);
    intlNumber($('select[name=id_country] option:selected').val());
  }

  if (isOneSeven && front_controller) {
    prestashop.on("updatedAddressForm", function (event) {
      if ($(GoogleAddress.inputField).length) {
        GoogleAddress.initFormFields();
        GoogleAddress.init();
      }

      input = document.querySelector("input[name=phone]");
      if ($(input).length && GOOGLE_ADDRESS_INTL_PHONE == 1) {
        initErrors(input);
        var idCountry = $('select[name=id_country] option:selected').val();
        telInput = window.intlTelInput(input, getIntlOptions(idCountry));
        setListners();
      }
    });
  }
});

function addScript(src) {
  document.write("<" + 'script src="' + src + '"><' + "/script>");
}

function intlNumber(idCountry) {
  if (typeof idCountry === 'undefined' || !idCountry || idCountry == '') {
    idCountry = $('select[name=id_country] option:selected').val() || defaultCountry;
  }
  // initialise plugin
  telInput = intlTelInput(input, getIntlOptions(idCountry));
  setListners();
}

function getIntlOptions(idCountry) {
  var countries = JSON.parse(gAcountries);
  return {
    utilsScript: utils,
    preferredCountries: [],
    initialCountry: countries[idCountry],
    onlyCountries: (type == 'bo')? [] : [countries[idCountry]]
  };
}

function setListners() {
  if (input) {
    // on keyup / change flag: reset
    input.addEventListener('blur', validateIntel);
    input.addEventListener('change', validateIntel);
    input.addEventListener('keyup', validateIntel);
    input.addEventListener('keydown', validateIntel);
  }
}

function validateIntel() {
  var telNumber = telInput.getNumber();
  resetTelInput();
  if ($.trim(telNumber)) {
    if (telInput.isValidNumber()) {
      $('#valid-msg').removeClass("hide");
    } else {
      $(input).addClass("error");
      var errorCode = telInput.getValidationError();
      $('#error-msg').html(intl_errors[errorCode]);
      $('#error-msg').removeClass("hide");
    }
  }
}

function resetTelInput() {
  input.classList.remove("error");
  $('#error-msg').addClass("hide");
  $('#valid-msg').addClass("hide");
};

function initErrors(input) {
  $(input).attr('maxlength', 16)
  .after('<span id="valid-msg" class="hide">✓ ' +
      msg_labels.success +
      '</span><span id="error-msg" class="hide">✗ ' +
      msg_labels.error +
      '</span>'
    );
}


// Restricts input for each element in the set of matched elements to the given inputFilter.
(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));


$('input[name=phone]').inputFilter(function(value) {
  return /^-?\d*$/.test(value);
});