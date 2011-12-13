
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.1.3_117677
 * Purchase ID: n/a
 * Generated:   2011-09-11 17:29:56
 * File path:   skin/frontend/base/default/js/aitoc/aitcheckout/checkout/step/address/shipping.js
 * Copyright:   (c) 2011 AITOC, Inc.
 */
var AitShipping = Class.create(AitAddress,  
{
    initShipping: function(checkboxId)
    {   
        if ($(checkboxId)) 
        {
            $(checkboxId).observe('click', this.onChangeUseForShipping.bind(this, checkboxId));
        } 
        Event.observe(window, 'load', this.onChangeUseForShipping.bind(this, checkboxId));                
    },
    
    onChangeUseForShipping: function(checkboxId, event)
    {  
        if ($(checkboxId)) 
        {
            if ($(checkboxId).checked) 
            {
                if ($(this.cfmTopContainer) || $(this.cfmBottomContainer))
                {
                    Element.show(this.container);
                    Element.hide(this.container + '-child');
                } else {
                    Element.hide(this.container);
                }
                this.getCheckout().getStep('billing').setReloadSteps(this.getCheckout().getStep('shipping').reloadSteps);
                this.getCheckout().getStep('billing').update(event);  
                return;    
            }            
        } 
        Element.show(this.container);
        Element.show(this.container + '-child');
        this.getCheckout().getStep('billing').setReloadSteps([]);    
        this.getCheckout().getStep('billing').update(event); 
        this.getCheckout().getStep('shipping').update(event);
    }        
});