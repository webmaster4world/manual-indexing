
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.1.3_117677
 * Purchase ID: n/a
 * Generated:   2011-09-11 17:29:56
 * File path:   skin/frontend/base/default/js/aitoc/aitcheckout/checkout/step/review.js
 * Copyright:   (c) 2011 AITOC, Inc.
 */
var AitReview = Class.create(Review,  
{
    save: function()
    {
        if (aitCheckout.updatingNow)
        {
            return;
        }

        var validator = new Validation(aitCheckout.getForm());
        if (validator && validator.validate())
        {
            this.setLoadWaiting();

            var params = Form.serialize(aitCheckout.getForm());
            if (this.agreementsForm) {
                params += '&'+Form.serialize(this.agreementsForm);
            }
            params.save = true;

            var request = new Ajax.Request(
                this.saveUrl,
                {
                    method:'post',
                    parameters:params,
                    onComplete: this.onComplete,
                    onSuccess: this.onSave,
                    onFailure: aitCheckout.ajaxFailure.bind(aitCheckout)
                }
            );
        }
    },
    
    setLoadWaiting: function(step) 
    {
        var container = $('checkout-buttons-container');
        container.addClassName('disabled');
        container.setStyle({opacity:.5});
        this._disableEnableAll(container, true);
        Element.show('checkout-please-wait');
    },
    
    resetLoadWaiting: function(transport)
    {
        var container = $('checkout-buttons-container');
        container.removeClassName('disabled');
        container.setStyle({opacity:1});
        this._disableEnableAll(container, false);
        Element.hide('checkout-please-wait');    
    },
    
    _disableEnableAll: function(element, isDisabled) {
        var descendants = element.descendants();
        for (var k in descendants) {
            descendants[k].disabled = isDisabled;
        }
        element.disabled = isDisabled;
    },

    nextStep: function(transport){
        if (transport && transport.responseText) {
            try{
                response = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                response = {};
            }
            
           // check for errors
            var msg = response.error_messages;
            if (typeof(msg)=='object') {
                msg = msg.join("\n");
            }
            if (msg) {
                alert(msg);
                return;
            }
            
             if (response.redirect) {
                location.href = response.redirect;
                return;
            }
            if (response.success) {
                this.isSuccess = true;
                window.location=this.successUrl;
            }
            

            if (response.update_section) {
                $('checkout-'+response.update_section.name+'-load').update(response.update_section.html);
            }

            if (response.goto_section) {
                checkout.gotoSection(response.goto_section);
                checkout.reloadProgressBlock();
            }
        }
    }        
});