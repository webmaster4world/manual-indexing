
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.1.3_117677
 * Purchase ID: n/a
 * Generated:   2011-09-11 17:29:56
 * File path:   skin/frontend/base/default/js/aitoc/aitcheckout/checkout/step/address.js
 * Copyright:   (c) 2011 AITOC, Inc.
 */
var AitAddress = Class.create(Step,  
{
    newAddress: function(isNew, containerId)
    {
        if (isNew) 
        {
            Element.show(containerId);
        } else {
            Element.hide(containerId);
        }
    },
    
    initAddress: function(savedAddressId, newAddressContainerId)
    {        
        //observe address selection change events 
        if ($(savedAddressId)) 
        {
            $(savedAddressId).observe('change', function(event) 
            {
                this.newAddress(!Event.element(event).value, newAddressContainerId);
                if (Event.element(event).value) {  
//                    this.getAddress(Event.element(event).value);
                    this.update();
                }
                  
            }.bind(this));                       
        }       
        //observe address fields change events
        this.initEvents(newAddressContainerId);        
    },
    initAdditional: function(containerID)
    {
        this.initEvents(containerID);   
    }
    
});