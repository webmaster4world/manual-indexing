
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.1.3_117677
 * Purchase ID: n/a
 * Generated:   2011-09-11 17:29:56
 * File path:   skin/frontend/base/default/js/aitoc/aitcheckout/checkout/step/address/billing.js
 * Copyright:   (c) 2011 AITOC, Inc.
 */
var AitBilling = Class.create(AitAddress,  
{   
    initRegister: function(checkboxId, passwordContainerId)
    {
        if ($(checkboxId)) 
        {
            $(checkboxId).observe('click', this.onRegisterChange.bind(this, checkboxId));                  
        }
        this.initEvents(passwordContainerId);
    },
    
    onRegisterChange: function(checkboxId, event)
    {
        
        if ($(checkboxId).checked) {
            Element.show('register-customer-password');
            var method = 'register';
        } else {
            Element.hide('register-customer-password');
            var method = 'guest'; 
        }
        var request = new Ajax.Request(
            this.urls.saveMethodUrl, {method: 'post', parameters: {method : method}}
        );
    }
    
});