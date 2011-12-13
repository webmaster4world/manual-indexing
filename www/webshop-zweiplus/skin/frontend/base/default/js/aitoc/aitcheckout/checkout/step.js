
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.1.3_117677
 * Purchase ID: n/a
 * Generated:   2011-09-11 17:29:56
 * File path:   skin/frontend/base/default/js/aitoc/aitcheckout/checkout/step.js
 * Copyright:   (c) 2011 AITOC, Inc.
 */
var Step = Class.create();
Step.prototype = {
    initialize: function(name, container, checkout, urls, options)
    {
        this.name = name;
        this.container = container;
        this.checkout = checkout;
        this.urls = urls;
        this.isLoadWaiting = options.isLoadWaiting;
        this.isUpdateOnReload = options.isUpdateOnReload;
        this.doCheckErrors = options.doCheckErrors;
        this.cacheFields = (typeof options.cache != 'undefined') ? options.cache : [];
        this.reloadSteps = [];
        this.onChangeStepData = this.update.bindAsEventListener(this);
        this.onUpdateChild = this.onUpdateReloadStep.bindAsEventListener(this);
        this.onUpdate = this.onUpdateResponse.bindAsEventListener(this);
        
        //checkout fields manager
        this.cfmTopContainer = this.name + '-aitcheckoutfields-top';
        this.cfmBottomContainer = this.name + '-aitcheckoutfields-bottom';
        this.cfmRegContainer = this.name + '-aitcheckoutfields-reg'; 
        
        [this.cfmTopContainer, this.cfmBottomContainer, this.cfmRegContainer].each(function(item) {
            if ($(item))
            {
                $(item).select('input', 'select', 'textarea').each(
                    function(input)
                    {
                        if (input.type.toLowerCase() == 'radio' || input.type.toLowerCase() == 'checkbox') {
                            Event.observe(input, 'click', this.onChangeStepData.bind(this));
                        } else {
                            Event.observe(input, 'change', this.onChangeStepData.bind(this));
                        }
                    }.bind(this)
                );    
            }    
        }.bind(this));
        
        //init cache
        if (typeof this.getCheckout().cache[this.name] == 'undefined')
        {
            this.getCheckout().cache[this.name] = [];       
        }
        this.fillCachedFields();
    },
    
    
    getCheckout: function()
    {
        return this.checkout;
    },
    
    setReloadSteps: function(steps)
    {
        this.reloadSteps = steps;
    },
    
    addReloadSteps: function(steps)
    {
        steps.each(function(step)
        {
            if (this.reloadSteps.indexOf(step) < 0)
            {
                this.reloadSteps.push(step);
            }    
        }.bind(this));
    },
    
    update: function(event)
    {
        this.getCheckout().setUpdatingNow(true);
        if (event)
        {
            var elem = Event.element(event);
            if ($(elem.id))
            {
                if (this.cacheFields.indexOf(elem.id) >= 0)
                {
                    this.getCheckout().cache[this.name][elem.id] = elem.value;
                }
                if (!Validation.validate(elem.id))
                {
                    this.getCheckout().setUpdatingNow(false);
                    return;
                } 
            }      
        }
        var validator = new AitValidation(this.container);
        if ( validator && validator.validate())
        {
            this.reloadSteps.each(
                function(stepName) {
                    this.getCheckout().getStep(stepName).loadWaiting();    
                }.bind(this)
            );
            var params = Form.serialize(this.getCheckout().getForm()) + '&' + 
                Object.toQueryString({step : this.name, reload_steps : this.reloadSteps.join(',')}); 
            var request = new Ajax.Request(
                this.checkout.ajaxUpdateUrl,
                {
                    method: 'post',
                    onComplete: this.onUpdateChild,
                    onSuccess: this.onUpdate,
                    parameters: params,
                    onFailure: this.getCheckout().ajaxFailure.bind(this.getCheckout())
                }
            );
        }
        else {
            this.getCheckout().setUpdatingNow(false);
        }    
    },
    
    loadWaiting: function()
    {
        if (this.isLoadWaiting && $(this.name+'-waiting')) 
        {
            $(this.name+'-waiting').show();
        } 
    },
        
    getContainer: function()
    {
        return $(this.container);            
    },
    
    onUpdateResponse: function(transport)
    {
        if (transport && transport.responseText){
            try{
                response = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                response = {};
            }
        }

        for (var idx in response.update_section) 
        {
            var stepName = response.update_section[idx].name;
            var stepHtml = response.update_section[idx].html;
            var step = this.checkout.getStep(stepName);
            var stepContainer = step.getContainer();
            stepContainer.update(stepHtml); 
        } 
        this.onUpdateResponseAfter(response);
        this.getCheckout().setUpdatingNow(false);
    },
    
    onUpdateReloadStep: function(transport)
    {
        if (transport && transport.responseText){
            try{
                response = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                response = {};
            }
        }
        for (var idx in response.update_section) 
        {   
            var stepName = response.update_section[idx].name;
            var step = this.checkout.getStep(stepName);
            if (step.isUpdateOnReload)   
            {
                step.update();    
            }         
        }
    }, 
    
    onUpdateResponseAfter: function(response)
    {
        var stepResponse = eval('response.' + this.name);
        
        if (this.doCheckErrors)
        { 
            var notice = $(this.name + '-notice');
            var checkoutBtn = $('checkout-buttons-container').down('button');
                    
            if (stepResponse.length != 0)
            {
                if (stepResponse.error == 0)
                {
                    notice.addClassName('success-msg');  
                } else if (stepResponse.error == -1)
                {
                    notice.addClassName('error-msg');
                } else if (stepResponse.error == 1)
                {
                    notice.addClassName('error-msg');    
                }
                notice.update(stepResponse.message); 
                notice.show(); 
                checkoutBtn.disabled = true;
                checkoutBtn.addClassName('no-checkout');
                this.stepErrorHandler(stepResponse);
            } else {
                notice.hide();
                checkoutBtn.disabled = false;
                checkoutBtn.removeClassName('no-checkout');  
            }   
        } 
        
        
  
    },  
    
    stepErrorHandler: function(stepResponse)
    {
        
    },
    initEvents: function(containerId)
    {
        if ($(containerId))
        {
            $(containerId).select('input', 'select', 'textarea').each(
                function(input)
                {
                    if (input.type.toLowerCase() == 'radio' || input.type.toLowerCase() == 'checkbox') 
                    {
                        Event.observe(input, 'click', this.onChangeStepData.bind(this));
                    } 
                    else {
                        Event.observe(input, 'change', this.onChangeStepData.bind(this));      
                    }
                }.bind(this)
            );
        }    
    },
    
    fillCachedFields: function()
    {
        if (typeof this.getCheckout().cache[this.name] != 'undefined')
        {
            for (var idx in this.cacheFields) 
            {
                var fieldId = this.cacheFields[idx];
                if (typeof this.getCheckout().cache[this.name][fieldId] != 'undefined')
                {
                    $(fieldId).value = this.getCheckout().cache[this.name][fieldId];
                }
            }
        }     
    }       
}