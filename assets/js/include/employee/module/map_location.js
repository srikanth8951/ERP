/*
 Template Name: Mentric Wap Bootstrap 
 Author: Mentrictech
 File: Map Location js
 */

$(function () {

    
    window.getPlaceSearchDetail = function (args) {
        let defaultOptions = {
            'type': 'inline'
        };
        var options = Object.assign({}, defaultOptions, args);

        switch(options.type) {
            case 'modal':
                if ($('#placeSearchModal').length <= 0) {
                    let modalContent = `<div id="placeSearchModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="placeSearchModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="placeSearchModalLabel">Place Search</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input class="form-control" id="input-place-search"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
    
                    $('body').append(modalContent);
                }
    
                $('#placeSearchModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
                break;
            default:
                let inlineContent = `<div class="form-group">
                    <input class="form-control" placeholder="Search Location" id="input-place-search"  />
                </div>`;
                $(options.element).html(inlineContent);
        }
     
        return new Promise(resolve => {
            const options = {
                fields: ["formatted_address", "geometry", "name"],
                strictBounds: false,
            };
                
            const autocomplete = new google.maps.places.Autocomplete(document.getElementById("input-place-search"), options);

            resolve(autocomplete);
            // autocomplete.addListener("place_changed", function () {
            //     let place = autocomplete.getPlace();
            //     $('#input-place-search').val('');
            //     $('#placeSearchModal').modal('hide');
            //     resolve(place);
            // });
        });
    }

});