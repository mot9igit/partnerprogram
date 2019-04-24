(function (window, document, $, ppConfig) {
    var partnerProgram = partnerProgram || {};

    partnerProgram.ajaxProgress = false;
    partnerProgram.setup = function () {
        // selectors & $objects
        this.actionName = 'pp_action';
        this.action = ':submit[name=' + this.actionName + ']';
        this.form = '.pp_form';
        this.mapform = '.map-form';
        this.mapformup = '.map-formUp';
        this.changefields = '.changer';
        this.stepper = '.stepper';
        this.remover = '.remove';
        this.updater = '.update-object';
        this.objectsContainer = '.objects';
        this.sendmoney = '.sendmoney';
        this.$doc = $(document);

        this.sendData = {
            $form: null,
            action: null,
            formData: null
        };

        this.timeout = 300;
    };
    partnerProgram.initialize = function () {
        partnerProgram.setup();
        // Indicator of active ajax request

        //noinspection JSUnresolvedFunction
        partnerProgram.$doc
            .ajaxStart(function () {
                partnerProgram.ajaxProgress = true;
            })
            .ajaxStop(function () {
                partnerProgram.ajaxProgress = false;
            })
            .on('click', partnerProgram.remover, function (e) {
                e.preventDefault();
                var id = $(this).data("object");
                partnerProgram.Object.remove(id);
            })
            .on('click', partnerProgram.updater, function (e) {
                e.preventDefault();
                var id = $(this).data("object");
                partnerProgram.Object.get(id);
            })
            .on('click', partnerProgram.sendmoney, function (e) {
                e.preventDefault();
                partnerProgram.Balance.sendmoney();
            })
            .on('click', partnerProgram.stepper, function (e) {
                e.preventDefault();
                var target = $(this).data("to");
                $(".steps-form .step").removeClass('active');
                $(".steps-form .step."+target).addClass('active');
            })
            .on('submit', partnerProgram.form, function (e) {
                e.preventDefault();
                var $form = $(this);
                var action = $form.find(partnerProgram.action).val();

                if (action) {
                    var formData = $form.serializeArray();
                    formData.push({
                        name: partnerProgram.actionName,
                        value: action
                    });
                    partnerProgram.sendData = {
                        $form: $form,
                        action: action,
                        formData: formData
                    };
                    partnerProgram.controller();
                }
            })
            .on('change', partnerProgram.mapform+' '+partnerProgram.changefields, function (e) {
                partnerProgram.rebuildMap();
            })
            .on('change', partnerProgram.mapformup+' '+partnerProgram.changefields, function (e) {
                partnerProgram.rebuildMapUp();
            });
    };
    partnerProgram.controller = function () {
        var self = this;
        switch (self.sendData.action) {
            case 'object/check':
                partnerProgram.Object.check();
                break;
            case 'object/add':
                partnerProgram.Object.add();
                break;
            case 'object/update':
                partnerProgram.Object.add();
                break;
            case 'balance/update':
                partnerProgram.Balance.update();
                break;
            case 'balance/sendmoney':
                partnerProgram.Balance.sendmoney();
                break;
            default:
                return;
        }
    };
    partnerProgram.send = function (data) {
        if ($.isArray(data)) {
            data.push({
                name: 'ctx',
                value: ppConfig.ctx
            });
        }
        else if ($.isPlainObject(data)) {
            data.ctx = ppConfig.ctx;
        }
        else if (typeof data == 'string') {
            data += '&ctx=' + ppConfig.ctx;
        }

        // set action url
        var formActionUrl = (partnerProgram.sendData.$form)
            ? partnerProgram.sendData.$form.attr('action')
            : false;
        var url = (formActionUrl)
            ? formActionUrl
            : (ppConfig.actionUrl)
                ? ppConfig.actionUrl
                : document.location.href;
        // set request method
        var formMethod = (partnerProgram.sendData.$form)
            ? partnerProgram.sendData.$form.attr('method')
            : false;
        var method = (formMethod)
            ? formMethod
            : 'post';

        // send
        $.ajax({
            type: method,
            url: url,
            data: data,
            dataType: "json",
            success: function (data_r) {
                if(data_r) {
                    if (data_r.data.action == "object/check") {
                        if (data_r.success) {
                            $(".steps-form .step").removeClass('active');
                            $(".steps-form .step.step-2").addClass('active');
                            $(".steps-form .step.step-2 input[name=name]").val(data_r.data.address);
                            $(".steps-form .step.step-2 input[name=coordinates]").val(data_r.data.coordinates);
                            $(".steps-form .step.step-2 input[name=province]").val(data_r.data.province);
                            $(".steps-form .step.step-2 input[name=locality]").val(data_r.data.locality);
                            $(".steps-form .step.step-2 input[name=street]").val(data_r.data.street);
                            $(".steps-form .step.step-2 input[name=house]").val(data_r.data.house);
                            var data = $(".map-form").serialize() + '&action=mapdata/get';
                            partnerProgram.send(data);
                        } else {
                            $(".steps-form .message").html("<div class='alert alert-warning'>" + data_r.message + "</div>")
                        }
                    }
                    if (data_r.data.action == "mapdata/get") {
                        buildMap(data_r.data);
                        buildMapUp(data_r.data);
                    }
                    if (data_r.data.action == "object/add") {
                        partnerProgram.Message.success("Объект успешно добавлен!");
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);

                    }
                    if (data_r.data.action == "object/get") {
                        for (var index in data_r.data.fields) {
                            $("#update-object").find("input[name="+index+"]").val(data_r.data.fields[index]);
                        };
                        $("#update-object").modal("show");
                        partnerProgram.buildMapUp();
                    }
                    if (data_r.data.action == "object/update") {
                        if (data_r.success) {
                            partnerProgram.Message.success(data_r.message);
                        }else{
                            partnerProgram.Message.error(data_r.message);
                        }
                    }
                    if (data_r.data.action == "balance/update") {
                        if (data_r.success) {
                            partnerProgram.Message.success(data_r.message);
                        }else{
                            partnerProgram.Message.error(data_r.message);
                        }
                    }
                    if (data_r.data.action == "balance/sendmoney") {
                        if (data_r.success) {
                            partnerProgram.Message.success(data_r.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }else{
                            partnerProgram.Message.error(data_r.message);
                        }
                    }
                    if (data_r.data.action == "object/remove") {
                        if (data_r.success) {
                            partnerProgram.Message.success(data_r.message);
                            var selector = partnerProgram.objectsContainer+" #object_"+data_r.data.id;
                            $(selector).remove();
                        }else{
                            partnerProgram.Message.error(data_r.message);
                        }
                    }
                }
            }
        });
    };

    partnerProgram.Object = {
        setup: function () {
            partnerProgram.Object.row = '#pp_objects';;
        },
        initialize: function () {
            partnerProgram.Object.setup();
        },
        check: function () {
            partnerProgram.send(partnerProgram.sendData.formData);
        },
        add: function () {
            partnerProgram.send(partnerProgram.sendData.formData);
        },
        get: function (id) {
            data = {
                id: id,
                pp_action: "object/get"
            };
            partnerProgram.send(data);
        },
        update: function () {
            partnerProgram.send(partnerProgram.sendData.formData);
        },
        remove: function (id) {
            data = {
                id: id,
                pp_action: "object/remove"
            };
            partnerProgram.send(data);
        },
    };

    partnerProgram.Balance = {
        setup: function () {
            partnerProgram.Balance.row = '#pp_form';;
        },
        initialize: function () {
            partnerProgram.Balance.setup();
        },
        update: function () {
            partnerProgram.send(partnerProgram.sendData.formData);
        },
        sendmoney: function () {
            data = {
                pp_action: "balance/sendmoney"
            };
            partnerProgram.send(data);
        }
    };

    partnerProgram.Message = {
        initialize: function () {
            partnerProgram.Message.close = function () {
            };
            partnerProgram.Message.show = function (message) {
                if (message != '') {
                    alert(message);
                }
            };

            if (typeof($.fn.jGrowl) != 'function') {
                $.getScript(ppConfig.jsUrl + 'lib/jquery.jgrowl.min.js', function () {
                    partnerProgram.Message.initialize();
                });
            }else {
                $.jGrowl.defaults.closerTemplate = '<div>[ Закрыть все ]</div>';
                partnerProgram.Message.close = function () {
                    $.jGrowl('close');
                };
                partnerProgram.Message.show = function (message, options) {
                    if (message != '') {
                        $.jGrowl(message, options);
                    }
                }
            }
        },
        success: function (message) {
            partnerProgram.Message.show(message, {
                theme: 'pp-message-success',
                sticky: false
            });
        },
        error: function (message) {
            partnerProgram.Message.show(message, {
                theme: 'pp-message-error',
                sticky: false
            });
        },
        info: function (message) {
            partnerProgram.Message.show(message, {
                theme: 'pp-message-info',
                sticky: false
            });
        }
    };

    partnerProgram.Utils = {
        empty: function (val) {
            return (typeof(val) == 'undefined' || val == 0 || val === null || val === false || (typeof(val) == 'string' && val.replace(/\s+/g, '') == '') || (typeof(val) == 'object' && val.length == 0));
        },
        getValueFromSerializedArray: function (name, arr) {
            if (!$.isArray(arr)) {
                arr = partnerProgram.sendData.formData;
            }
            for (var i = 0, length = arr.length; i < length; i++) {
                if (arr[i].name == name) {
                    return arr[i].value;
                }
            }
            return null;
        }
    };

    partnerProgram.buildMap = function () {
        var data = $(".map-form").serialize()+'&action=mapdata/get';
        partnerProgram.send(data);
    };

    partnerProgram.rebuildMap = function () {
        var data = $(".map-form").serialize()+'&action=object/check';
        partnerProgram.send(data);
    };

    partnerProgram.buildMapUp = function () {
        var data = $(".map-formUp").serialize()+'&action=mapdata/get';
        partnerProgram.send(data);
    };

    partnerProgram.rebuildMapUp = function () {
        var data = $(".map-formUp").serialize()+'&action=object/check';
        partnerProgram.send(data);
    };

    $(document).ready(function ($) {
        partnerProgram.initialize();
        partnerProgram.Message.initialize();
        var money = $(".possible_balance").text();
        if(parseInt(money) > parseInt(ppConfig.minimal_paid)){
            $(partnerProgram.sendmoney).removeClass("hidden");
        }
        var html = $('html');
        html.removeClass('no-js');
        if (!html.hasClass('js')) {
            html.addClass('js');
        }
    });

    window.partnerProgram = partnerProgram;
})(window, document, jQuery, ppConfig);

// create map

var myMap;
var myPlacemark;

var parameters = JSON.parse(ppConfig.default_map_data);

ymaps.ready(init);

function init() {
    if($("#yandex-map").length){
        buildMap(parameters, false);
    }
}

function getCoordinates(coordinates) {
    var string = String(coordinates);
    string = string.replace(',', '|');

    return string;
}

function buildMap(parameters) {
    if (! myMap) {
        myMap = new ymaps.Map("yandex-map", {
            center: [parameters.position[1], parameters.position[0]],
            zoom: parameters.zoom
        });
    } else {
        var zoom = myMap.getZoom();

        if (parameters.zoom > zoom) {
            zoom = parameters.zoom;
        }

        myMap.setCenter(
            [parameters.position[1], parameters.position[0]],
            zoom
        );
    }

    if (! myPlacemark && parameters.mark !== false) {
        myPlacemark = new ymaps.Placemark([parameters.mark[1], parameters.mark[0]], {
            balloonContent: parameters.balloon,
            hintContent: ""
        }, {
            draggable: true
        });

        myPlacemark.events.add('dragend', function (e) {
            var coords = myPlacemark.geometry.getCoordinates()

            $('#coordinates').val(getCoordinates(coords));
        });

        myMap.geoObjects.add(myPlacemark);
    }

    if (! myPlacemark && parameters.mark === false) {
        myMap.events.once('click', function (e) {
            if (typeof myMap.geoObjects.get(0) == 'undefined') {
                var coords = e.get('coords');

                myPlacemark = new ymaps.Placemark([coords[0], coords[1]], {
                    balloonContent: "",
                    hintContent: ""
                }, {
                    draggable: true,
                    openEmptyBalloon: true
                });

                myPlacemark.events.add('dragend', function (e) {
                    var coords = myPlacemark.geometry.getCoordinates()

                    $('#coordinates').val(getCoordinates(coords));

                    ymaps.geocode(coords, {
                        results: 1,
                        json: true
                    }).then(function (res) {
                        findMarkAddress(res);
                    });
                });

                ymaps.geocode(myPlacemark.geometry.getCoordinates(), {
                    results: 1,
                    json: true
                }).then(function (res) {
                    findMarkAddress(res);
                });


                myMap.geoObjects.add(myPlacemark);

                $('#coordinates').val(getCoordinates(myPlacemark.geometry.getCoordinates()));
            }
        });
    }
}

// update map

var myMapUp;
var myPlacemarkUp;

var parameters = JSON.parse(ppConfig.default_map_data);

ymaps.ready(init);

function init() {
    if($("#yandex-map-2").length){
        buildMapUp(parameters, false);
    }
}

function getCoordinatesUp(coordinates) {
    var string = String(coordinates);
    string = string.replace(',', '|');

    return string;
}

function buildMapUp(parameters) {
    if (! myMapUp) {
        myMapUp = new ymaps.Map("yandex-map-2", {
            center: [parameters.position[1], parameters.position[0]],
            zoom: parameters.zoom
        });
    } else {
        var zoom = myMapUp.getZoom();

        if (parameters.zoom > zoom) {
            zoom = parameters.zoom;
        }

        myMapUp.setCenter(
            [parameters.position[1], parameters.position[0]],
            zoom
        );
    }

    if (! myPlacemarkUp && parameters.mark !== false) {
        myPlacemarkUp = new ymaps.Placemark([parameters.mark[1], parameters.mark[0]], {
            balloonContent: parameters.balloon,
            hintContent: ""
        }, {
            draggable: true
        });

        myPlacemarkUp.events.add('dragend', function (e) {
            var coords = myPlacemark.geometry.getCoordinates()

            $('#coordinatesUp').val(getCoordinates(coords));
        });

        myMapUp.geoObjects.add(myPlacemarkUp);
    }

    if (! myPlacemarkUp && parameters.mark === false) {
        myMapUp.events.once('click', function (e) {
            if (typeof myMap.geoObjects.get(0) == 'undefined') {
                var coords = e.get('coords');

                myPlacemarkUp = new ymaps.Placemark([coords[0], coords[1]], {
                    balloonContent: "",
                    hintContent: ""
                }, {
                    draggable: true,
                    openEmptyBalloon: true
                });

                myPlacemarkUp.events.add('dragend', function (e) {
                    var coords = myPlacemarkUp.geometry.getCoordinates()

                    $('#coordinatesUp').val(getCoordinates(coords));

                    ymaps.geocode(coords, {
                        results: 1,
                        json: true
                    }).then(function (res) {
                        findMarkAddress(res);
                    });
                });

                ymaps.geocode(myPlacemarkUp.geometry.getCoordinates(), {
                    results: 1,
                    json: true
                }).then(function (res) {
                    findMarkAddress(res);
                });


                myMap.geoObjects.add(myPlacemarkUp);

                $('#coordinatesUp').val(getCoordinates(myPlacemarkUp.geometry.getCoordinates()));
            }
        });
    }
}