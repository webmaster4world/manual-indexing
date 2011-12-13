
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.1.3_117677
 * Purchase ID: n/a
 * Generated:   2011-09-11 17:29:56
 * File path:   skin/frontend/base/default/js/aitoc/aitcheckout/checkout/step/coupon.js
 * Copyright:   (c) 2011 AITOC, Inc.
 */
var AitCoupon = Class.create(Step,  
{
    initCoupon: function(applyId, cancelId)
    {
        if ($(applyId))
        {
            $(applyId).observe('click', this.onChangeStepData.bind(this));
        }
        if ($(cancelId))
        {
            $(cancelId).observe('click', this.onChangeStepData.bind(this));
        }
       
    },   
    
    update: function(event)
    {
        var params = Form.serialize(this.getCheckout().getForm()) + '&' + 
            Object.toQueryString({step : this.name, reload_steps : this.reloadSteps.join(',')});
        var validator = new Validation(this.container);
        
        if (validator && validator.validate())
        { 
            this.reloadSteps.each(
                function(stepName) {
                    this.getCheckout().getStep(stepName).loadWaiting();    
                }.bind(this)
            );    
            
            var request = new Ajax.Request(
                this.urls.couponUpdateUrl,
                {
                    method: 'post',
                    onComplete: this.onUpdateChild,
                    onSuccess: this.onUpdate,
                    parameters: params
                }
            );
        }
            
    },
    
    onUpdateResponseAfter: function(response)
    {
        var notice = $('coupon-notice');
                
        if (response.coupon.length != 0)
        {
            if (response.coupon.error == 0)
            {
                notice.addClassName('success-msg');  
            } else if (response.coupon.error == -1)
            {
                notice.addClassName('error-msg');
            } else if (response.coupon.error == 1)
            {
                notice.addClassName('notice-msg');    
            }
            notice.update(response.coupon.message); 
            $('coupon-notice').show(); 
        }   
    }
          
});