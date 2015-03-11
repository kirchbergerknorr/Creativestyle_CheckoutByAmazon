/**
 * This file is part of The Official Amazon Payments Magento Extension
 * (c) creativestyle GmbH <amazon@creativestyle.de>
 * All rights reserved
 *
 * Reuse or modification of this source code is not allowed
 * without written permission from creativestyle GmbH
 *
 * @category   Creativestyle
 * @package    Creativestyle_CheckoutByAmazon
 * @copyright  Copyright (c) 2011 - 2013 creativestyle GmbH (http://www.creativestyle.de)
 * @author     Marek Zabrowarny / creativestyle GmbH <amazon@creativestyle.de>
 */

var _gaq = _gaq || [];

var CheckoutByAmazon = Class.create();

CheckoutByAmazon.prototype = {

    initialize: function(accordion, purchaseContractId, urls) {
        this.accordion = accordion;
        this.purchaseContractId = purchaseContractId;

        this.urls = {
            saveShipping: urls.saveShipping,
            saveShippingMethod: urls.saveShippingMethod,
            savePayment: urls.savePayment,
            saveOrder: urls.saveOrder,
            success: urls.success,
            progress: urls.progress,
            failure: urls.failure
        };

        this.loadWaiting = false;
        this.accordion.disallowAccessToNextSections = true;

        _gaq.push(["_trackPageview", "/checkoutbyamazon/checkout/cba-shipping"]);

    },

    _disableEnableAll: function(element, isDisabled) {
        var descendants = element.descendants();
        for (var k in descendants) {
            descendants[k].disabled = isDisabled;
        }
        element.disabled = isDisabled;
    },

    ajaxFailure: function() {
        location.href = this.urls.failure;
    },

    reloadProgressBlock: function() {
        new Ajax.Updater('checkout-progress-wrapper', this.urls.progress, {method: 'get', evalScripts: true, onFailure: this.ajaxFailure.bind(this)});
    },

    gotoSection: function(section) {
        section = $('opc-'+section);
        section.addClassName('allow');
        this.accordion.openSection(section);
    },

    back: function(){
        if (this.loadWaiting) return;
        this.accordion.openPrevSection(true);
    },

    setLoadWaiting: function(step, keepDisabled) {
        if (step) {
            if (this.loadWaiting) {
                this.setLoadWaiting(false);
            }
            var container = $(step + '-buttons-container');
            container.addClassName('disabled');
            container.setStyle({opacity: .5});
            this._disableEnableAll(container, true);
            Element.show(step + '-please-wait');
        } else {
            if (this.loadWaiting) {
                var container = $(this.loadWaiting + '-buttons-container');
                var isDisabled = (keepDisabled ? true : false);
                if (!isDisabled) {
                    container.removeClassName('disabled');
                    container.setStyle({opacity: 1});
                }
                this._disableEnableAll(container, isDisabled);
                Element.hide(this.loadWaiting + '-please-wait');
            }
        }
        this.loadWaiting = step;
    },

    resetLoadWaiting: function(transport) {
        this.setLoadWaiting(false);
    },

    setStepResponse: function(response) {
        if (response.update_section) {
            $('checkout-' + response.update_section.name + '-load').update(response.update_section.html);
        }
        if (response.allow_sections) {
            response.allow_sections.each(function(e){
                $('opc-' + e).addClassName('allow');
            });
        }
        if (response.goto_section) {
            this.reloadProgressBlock();
            this.gotoSection(response.goto_section);
            return true;
        }
        if (response.redirect) {
            location.href = response.redirect;
            return true;
        }
        return false;
    },

    nextStep: function(transport) {
        if (transport && transport.responseText) {
            try {
                response = eval('(' + transport.responseText + ')');
            } catch (e) {
                response = {};
            }
        }
        if (response.error) {
            if ((typeof response.message) == 'string') {
                alert(response.message);
            } else {
                alert(response.message.join("\n"));
            }
            return false;
        }
        this.setStepResponse(response);
    },

    saveShipping: function() {
        if (this.loadWaiting != false) return;
        this.setLoadWaiting('shipping');
        new Ajax.Request(this.urls.saveShipping, {
                method: 'post',
                onComplete: this.resetLoadWaiting.bindAsEventListener(this),
                onSuccess: this.nextStep.bindAsEventListener(this),
                onFailure: this.ajaxFailure.bind(this),
                parameters: {purchaseContractId: this.purchaseContractId}
            }
        );
    },

    validateShippingMethod: function() {

        var methods = document.getElementsByName('shipping_method');
        if (methods.length == 0) {
            alert(Translator.translate('Your order can not be completed at this time as there is no shipping methods available for it. Please make neccessary changes in your shipping address.'));
            return false;
        }

        var validator = new Validation($('co-shipping-method-form'));

        if (!validator.validate()) {
            return false;
        }

        for (var i=0; i < methods.length; i++) {
            if (methods[i].checked) {
                return true;
            }
        }
        alert(Translator.translate('Please specify shipping method.'));
        return false;

    },

    saveShippingMethod: function() {
        if (this.loadWaiting != false) return;
        if (this.validateShippingMethod()) {
            this.setLoadWaiting('shipping-method');
            new Ajax.Request(this.urls.saveShippingMethod, {
                    method: 'post',
                    onComplete: this.resetLoadWaiting.bindAsEventListener(this),
                    onSuccess: this.nextStep.bindAsEventListener(this),
                    onFailure: this.ajaxFailure.bind(this),
                    parameters: Form.serialize($('co-shipping-method-form'))
                }
            );
        }
    },

    savePayment: function() {
        if (this.loadWaiting != false) return;
        this.setLoadWaiting('payment');
        new Ajax.Request(this.urls.savePayment, {
                method: 'post',
                onComplete: this.resetLoadWaiting.bindAsEventListener(this),
                onSuccess: this.nextStep.bindAsEventListener(this),
                onFailure: this.ajaxFailure.bind(this),
                parameters: {purchaseContractId: this.purchaseContractId}
            }
        );
    },

    completeOrder: function(transport) {
        if (transport && transport.responseText) {
            try {
                response = eval('(' + transport.responseText + ')');
            } catch (e) {
                response = {};
            }
            if (response.success) {
                this.isSuccess = true;
                window.location = this.urls.success;
            } else {
                var msg = response.error_messages;
                if (typeof(msg) == 'object') {
                    msg = msg.join("\n");
                }
                alert(msg);
            }
            if (response.update_section) {
                $('checkout-' + response.update_section.name + '-load').update(response.update_section.html);
                response.update_section.html.evalScripts();
            }

            if (response.goto_section) {
                this.gotoSection(response.goto_section);
                this.reloadProgressBlock();
            }
        }
    },

    saveOrder: function() {
        if (this.loadWaiting != false) return;
        this.setLoadWaiting('review');

        var params = 'purchaseContractId=' + this.purchaseContractId;

        if ($('checkout-agreements')) {
            params += '&' + Form.serialize($('checkout-agreements'));
        }

        params.save = true;

        new Ajax.Request(this.urls.saveOrder, {
                method: 'post',
                onComplete: this.resetLoadWaiting.bindAsEventListener(this),
                onSuccess: this.completeOrder.bindAsEventListener(this),
                onFailure: this.ajaxFailure.bind(this),
                parameters: params
            }
        );
    }

}

if (Ajax.Responders) {
    Ajax.Responders.register({
        onComplete: function(response) {
            if (!response.url.include('progress')) {
                if (response.url.include('saveOrder')) {
                    _gaq.push(["_trackPageview", "/checkoutbyamazon/checkout/cba-place_order"]);
                } else if (accordion && accordion.currentSection) {
                    _gaq.push(["_trackPageview", "/checkoutbyamazon/checkout/" + accordion.currentSection.gsub(/opc-/, 'cba-')]);
                }
            }
        }
    });
};
