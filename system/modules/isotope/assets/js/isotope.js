(function(_win, _doc, jQuery, MooTools) {
    "use strict";

    if (!jQuery && !MooTools) {
        var polyfill = _doc.createElement('script');
        polyfill.src = 'system/modules/isotope/assets/js/polyfills.min.js';

        var script = _doc.getElementsByTagName('script')[0];
        script.parentNode.insertBefore(polyfill, script);
    }

    function addEventListener(el, name, callback) {
        if (jQuery) {
            jQuery(el).on(name, callback);
        } else if (MooTools) {
            el.addEvent(name, callback);
        } else {
            el.addEventListener(name, callback, false);
        }
    }

    function serializeForm(form) {
        if (jQuery) {
            return jQuery(form).serialize();
        } else if (MooTools) {
            return form.toQueryString();
        } else {
            return formToQueryString(form);
        }
    }

    _win.Isotope = _win.Isotope || {

        toggleAddressFields: function (el, id) {
            if (el.value == '0' && el.checked) {
                _doc.getElementById(id).style.display = 'block';
            } else {
                _doc.getElementById(id).style.display = 'none';
            }
        },

        displayBox: function (message, btnClose) {
            var box = _doc.getElementById('iso_ajaxBox');
            var overlay = _doc.getElementById('iso_ajaxOverlay');

            if (!overlay) {
                overlay = _doc.createElement('div');
                overlay.setAttribute('id', 'iso_ajaxOverlay');
                _doc.body.appendChild(overlay);
            }

            if (!box) {
                box = _doc.createElement('div');
                box.setAttribute('id', 'iso_ajaxBox');
                _doc.body.appendChild(box);
            }

            if (btnClose) {
                addEventListener(overlay, 'click', Isotope.hideBox);
                addEventListener(box, 'click', Isotope.hideBox);
                if (!box.className.search(/btnClose/) != -1) {
                    box.className = box.className + ' btnClose';
                }
            }

            overlay.style.display = 'block';

            box.innerHTML = message;
            box.style.display = 'block';
        },

        hideBox: function () {
            var box = _doc.getElementById('iso_ajaxBox');
            var overlay = _doc.getElementById('iso_ajaxOverlay');

            if (overlay) {
                overlay.style.display = 'none';
                overlay.removeEventListener('click', Isotope.hideBox, false);
            }

            if (box) {
                box.style.display = 'none';
                box.removeEventListener('click', Isotope.hideBox, false);
                box.className = box.className.replace(/ ?btnClose/, '');
            }
        },

        inlineGallery: function (el, elementId) {
            var i;
            var parent = el.parentNode;
            var siblings = parent.parentNode.children;

            for (i = 0; i < siblings.length; i++) {
                if (siblings[i].getAttribute('data-type') == 'gallery'
                    && siblings[i].getAttribute('data-uid') == elementId
                    && siblings[i].getAttribute('class').search(/(^| )active($| )/) != -1
                ) {
                    siblings[i].setAttribute('class', siblings[i].getAttribute('class').replace(/ ?active/, ''));
                }
            }

            parent.setAttribute('class', parent.getAttribute('class') + ' active');
            _doc.getElementById(elementId).src = el.href;

            return false;
        },

        elevateZoom: function (el, elementId) {
            Isotope.inlineGallery(el, elementId);

            jQuery('#' + elementId).data('elevateZoom').swaptheimage(el.getAttribute('href'), el.getAttribute('data-zoom-image'));

            return false;
        },

        checkoutButton: function (form) {
            addEventListener(form, 'submit', function () {
                try {
                    document.getElementsByName('nextStep')[0].disabled = true;
                } catch (e) {}
                try {
                    document.getElementsByName('previousStep')[0].disabled = true;
                } catch (e) {}

                setTimeout(function () {
                    window.location.reload()
                }, 30000);
            });
        },

        initAwesomplete: function (id, searchField) {
            var requested = false;
            addEventListener(searchField, 'focus', function() {
                if (requested) return false;

                requested = true;

                var url = window.location.href + (document.location.search ? '&' : '?') + '&iso_autocomplete=' + id,
                    xhr = new XMLHttpRequest();

                xhr.open('GET', encodeURI(url));
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        new Awesomplete(searchField, {
                            list: JSON.parse(xhr.responseText)
                        });
                        searchField.focus();
                    }
                };

                xhr.send();
            });
        }
    };

    _win.IsotopeProducts = (function() {
        var loadMessage = 'Loading product data …';

        function initProduct(config) {
            var formParent = _doc.getElementById(config.formId).parentNode;

            if (formParent) {
                registerEvents(formParent, config);
            }
        }

        function registerEvents(formParent, config) {
            var i, el,
                xhr = new XMLHttpRequest(),
                form = formParent.getElementsByTagName('form')[0];

            if (!form) return;

            xhr.open(form.getAttribute('method').toUpperCase(), encodeURI(form.getAttribute('action')));
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    ajaxSuccess(xhr.responseText);
                } else if (xhr.status !== 200) {
                    Isotope.hideBox();
                }
            };


            function ajaxSuccess(txt) {
                var div = _doc.createElement('div'),
                    scripts = '',
                    script, i;

                txt = txt.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, function(all, code){
                    scripts += code + '\n';
                    return '';
                });

                div.innerHTML = txt;

                // Remove all error messages
                var errors = div.getElementsByTagName('p');
                for(i=0; i<errors.length; i++) {
                    if (errors[i].className.search(/(^| )error( |$)/) != -1) {
                        errors[i].parentNode.removeChild(errors[i]);
                    }
                }

                formParent.innerHTML = '';
                for(i = 0; i<div.childNodes.length; i++) {
                    formParent.appendChild(div.childNodes[i]);
                }

                registerEvents(formParent, config);

                Isotope.hideBox();

                if (scripts) {
                    script = _doc.createElement('script');
                    script.text = scripts;
                    _doc.head.appendChild(script);
                    _doc.head.removeChild(script);
                }
            }

            if (config.attributes) {
                for (i=0; i<config.attributes.length; i++) {
                    el = _doc.getElementById(('ctrl_'+config.attributes[i]+'_'+config.formId));
                    if (el) {
                        addEventListener(el, 'change', function() {
                            if (xhr.readyState > 1) {
                                xhr.abort();
                            }

                            Isotope.displayBox(loadMessage);
                            xhr.send(serializeForm(form));
                        });
                    }
                }
            }
        }

        return {
            'attach': function(products) {
                var i;

                // Check if products is an array
                if (Object.prototype.toString.call(products) === '[object Array]' && products.length > 0) {
                    for (i=0; i<products.length; i++) {
                        initProduct(products[i]);
                    }
                }
            },

            'setLoadMessage': function(message) {
                loadMessage = message || 'Loading product data …';
            }
        };
    })();
})(window, document, window.jQuery, window.MooTools);
