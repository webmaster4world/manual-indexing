
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.1.3_117677
 * Purchase ID: n/a
 * Generated:   2011-09-11 17:29:56
 * File path:   skin/frontend/base/default/js/aitoc/aitcheckout/checkout/step/payment.js
 * Copyright:   (c) 2011 AITOC, Inc.
 */
var AitPayment = Class.create(Step,  
{
    initPayment: function(paymentContainerId)
    {
        this.initEvents(paymentContainerId);
        $(paymentContainerId).select('input[type="radio"]').each(function(input){
            input.addClassName('validate-one-required-by-name');
        });                
    }       
});