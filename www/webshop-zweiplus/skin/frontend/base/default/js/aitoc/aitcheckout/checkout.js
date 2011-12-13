
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.1.3_117677
 * Purchase ID: n/a
 * Generated:   2011-09-11 17:29:56
 * File path:   skin/frontend/base/default/js/aitoc/aitcheckout/checkout.js
 * Copyright:   (c) 2011 AITOC, Inc.
 */
var AitCheckout = Class.create();
AitCheckout.prototype = {
    initialize: function(form, urls) 
    {
        this.form = form;
        this.ajaxUpdateUrl = urls.ajaxUpdateUrl;
        this.failureUrl = urls.failureUrl;
        this.steps = [];
        
        this.updatingNow = undefined;
        
        this.cache = [];
        
        $$('.checkout-types').each(function(container) {
            $(container).select('button').each(function(btn) {
                btn.onclick = '';
                btn.observe('click', function(event) {
                    Effect.ScrollTo(this.form); 
                }.bind(this))
            }.bind(this));
        }.bind(this));  
    },
    
    ajaxFailure: function(){
        window.location = this.failureUrl;
    },
    
    getForm: function()
    {
        return this.form;
    },
    
    getValidator: function()
    {
        return new Validation(this.form);
    },
    
    setStep: function(name, step)
    {
        this.steps[name] = step;
        return this;
    },
    
    getStep: function(name)
    {
        if (this.steps[name]) 
        {
            return this.steps[name]; 
        }
    },
    
    setUpdatingNow: function(flag)
    {
        this.updatingNow = flag;
    }   
}

/*
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
        this.reloadSteps = [];
        this.onChangeStepData = this.update.bindAsEventListener(this);
        this.onUpdateChild = this.onUpdateReloadStep.bindAsEventListener(this);
        this.onUpdate = this.onUpdateResponse.bindAsEventListener(this);
        
        //checkout fields manager
        this.cfmTopContainer = this.name + '-aitcheckoutfields-top';
        this.cfmBottomContainer = this.name + '-aitcheckoutfields-bottom';
        
        [this.cfmTopContainer, this.cfmBottomContainer].each(function(item) {
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
        if (event)
        {
            var elem = Event.element(event);
            if ($(elem.id))
            {
                if (!Validation.validate(elem.id))
                {
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
                    notice.addClassName('notice-msg');    
                }
                notice.update(stepResponse.message); 
                notice.show(); 
                checkoutBtn.disabled = true;
                checkoutBtn.addClassName('no-checkout');                
            } else {
                notice.hide();
                checkoutBtn.disabled = false;
                checkoutBtn.removeClassName('no-checkout');  
            }   
        } 
        
        
  
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
    }       
}


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
    
    onChangeSavedAddress: function(event)
    {   
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
                    this.getAddress(Event.element(event).value);
                    this.update();
                }
                  
            }.bind(this));                       
        }      
        //observe address fields change events
        this.initEvents(newAddressContainerId);        
    },
    
    getAddress: function(addressId)
    {
        var addressUrl = this.urls.addressUrl;
        if (addressId) {
            request = new Ajax.Request(
                addressUrl+addressId, {
                    method:'post', 
                    onSuccess: this.fillForm.bind(this)
                }
            );
        }
        else {
            this.fillForm(false);
        }
    },
    
    fillForm: function(transport)
    {
        var elementValues = {};
        if (transport && transport.responseText){
            try{
                elementValues = eval('(' + transport.responseText + ')');
            }
            catch (e) {
                elementValues = {};
            }
        }

        arrElements = Form.getElements(this.getCheckout().getForm());
        for (var elemIndex in arrElements) {
            if (arrElements[elemIndex].id) 
            {
                var fieldName = arrElements[elemIndex].id.replace(eval('/^' + this.name + ':/'), '');
                if (fieldName != arrElements[elemIndex].id)
                {
                    if ('use_for_shipping' != fieldName && fieldName.indexOf('aitoc_checkout_')==-1)
                    {
                        arrElements[elemIndex].value = elementValues[fieldName] ? elementValues[fieldName] : '';         
                    }  
                    
                }
            }
        }
        eval(this.name + 'RegionUpdater.update();'); 
    }
                           
});


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


var AitShipping = Class.create(AitAddress,  
{
    initShipping: function(checkboxId)
    {   
        $(checkboxId).observe('click', this.onChangeUseForShipping.bind(this, checkboxId)); 
        Event.observe(window, 'load', this.onChangeUseForShipping.bind(this, checkboxId));                
    },
    
    onChangeUseForShipping: function(checkboxId, event)
    {  
        if ($(checkboxId).checked) {
            if ($(this.cfmTopContainer) || $(this.cfmBottomContainer))
            {
                Element.show(this.container);
                Element.hide(this.container + '-child');
            } else {
                Element.hide(this.container);
            }
            this.getCheckout().getStep('billing').setReloadSteps(this.getCheckout().getStep('shipping').reloadSteps);
            this.getCheckout().getStep('billing').update(event);      
        } else {
            Element.show(this.container);
            Element.show(this.container + '-child');
            this.getCheckout().getStep('billing').setReloadSteps([]);    
            this.getCheckout().getStep('billing').update(event); 
            this.getCheckout().getStep('shipping').update(event);
        }
    }        
    
});

var AitShippingMethod = Class.create(Step,  
{
    initShippingMethod: function(shippingMethodContainerId)
    {
        this.initEvents(shippingMethodContainerId);                     
    }       
});

var AitPayment = Class.create(Step,  
{
    initPayment: function(paymentContainerId)
    {
        this.initEvents(paymentContainerId);                
    }       
});


var AitReview = Class.create(Review,  
{
    save: function()
    {
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
    }        
});

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

var AitCart = Class.create(Step,  
{
    initCart: function(productOptionsCssQuery, qtyCssQuery, increaseCssQuery, decreaseCssQuery, removeCssQuery)
    {
        $$(productOptionsCssQuery).each(function(element)
        {
            element.select('input', 'select', 'textarea').each(
                function(input)
                {
                    if (input.type.toLowerCase() == 'radio' || input.type.toLowerCase() == 'checkbox') {
                        input.observe('click', this.updatePost.bind(this));
                    } else {
                        input.observe('change', this.updatePost.bind(this));    
                    }
                }.bind(this)
            );                 
        }.bind(this)); 
        
        $$(increaseCssQuery, decreaseCssQuery).each(function(element){
            element.observe('click', this.updateOptions.bind(this));    
        }.bind(this)); 
        
        $$(qtyCssQuery).each(function(element){
            element.observe('change', this.updatePost.bind(this));        
        }.bind(this)); 
         
        $$(removeCssQuery).each(function(element){
            element.observe('click', this.deletePost.bind(this));    
        }.bind(this)); 
    }, 
    
    
    updateOptions: function(event)
    {
        var params = Form.serialize(this.getCheckout().getForm()) + '&' + 
            Object.toQueryString({step : this.name, reload_steps : this.reloadSteps.join(',')});  
        var itemRel = Event.element(event).rel;
        
        //in future need to add param @options@ to step initialize params 
        var sign = Event.element(event).hasClassName('btn-increase') ? '1' : (
                        Event.element(event).hasClassName('btn-decrease') ? '-1' : '0' );     
        this.loadWaiting();    
        this.reloadSteps.each(
            function(stepName) {
                this.getCheckout().getStep(stepName).loadWaiting();    
            }.bind(this)
        ); 
        var request = new Ajax.Request(
            this.urls.updateOptionsUrl + 'id/' + itemRel + '/sign/'+ sign,
            {
                method:'post',
                parameters: params,
                onComplete: this.onUpdateChild,
                onSuccess: this.onUpdate,
                onFailure: this.getCheckout().ajaxFailure.bind(this.getCheckout())
            }
        );   
    },

    updatePost: function(event)
    {
        var params = Form.serialize(this.getCheckout().getForm()) + '&' + 
            Object.toQueryString({step : this.name, reload_steps : this.reloadSteps.join(',')});  
        var itemRel = Event.element(event).rel;  
        this.loadWaiting();     
        this.reloadSteps.each(
            function(stepName) {
                this.getCheckout().getStep(stepName).loadWaiting();    
            }.bind(this)
        );  
        var request = new Ajax.Request(
            this.urls.updatePostUrl,
            {
                method:'post',
                parameters: params,
                onComplete: this.onUpdateChild,
                onSuccess: this.onUpdate,
                onFailure: this.getCheckout().ajaxFailure.bind(this.getCheckout())
            }
        );   
    },
    
    deletePost: function(event)
    {
        var params = Object.toQueryString({step : this.name, reload_steps : this.reloadSteps.join(',')});  
        var itemRel = Event.element(event).rel;  
        this.loadWaiting(); 
        this.reloadSteps.each(
            function(stepName) {
                this.getCheckout().getStep(stepName).loadWaiting();    
            }.bind(this)
        );  
        var request = new Ajax.Request(
            this.urls.deleteUrl + 'id/' + itemRel,
            {
                method:'post',
                parameters: params,
                onComplete: this.onUpdateChild,
                onSuccess: this.onUpdate,
                onFailure: this.getCheckout().ajaxFailure.bind(this.getCheckout())
            }
        );   
    }

});


var AitGiftMessage = Class.create(Step,  
{
    initGiftMessage: function(allowCheckboxId, allowForOrderCheckboxId, allowForItemsCheckboxId)
    {
        $(allowCheckboxId, allowForOrderCheckboxId, allowForItemsCheckboxId).each(function(input) {
            var source = input.id;
            var objects = [input.id + '-container'];
            this.toogleVisibilityOnObjects(source, objects);
            input.observe('click', function(event) {
                this.toogleVisibilityOnObjects(source, objects);        
            }.bind(this));
        }.bind(this));
        
        $(this.container).select('input', 'textarea').each(
            function(input)
            {
                Event.observe(input, 'change', this.onChangeStepData.bind(this));
            }.bind(this)
        ); 
    },
    
    toogleVisibilityOnObjects: function(source, objects) {
        if($(source) && $(source).checked) {
            objects.each(function(item){
                $(item).show();
                $$('#' + item + ' .input-text').each(function(item) {
                    item.removeClassName('validation-passed');
                });
            });


        } else {
            objects.each(function(item){  
                $(item).hide();
                $$('#' + item + ' .input-text').each(function(sitem) {
                    sitem.addClassName('validation-passed');
                });

                $$('#' + item + ' .giftmessage-area').each(function(sitem) {
                    sitem.value = '';
                });
                $$('#' + item + ' .checkbox').each(function(sitem) {
                    sitem.checked = false; 
                    this.toogleVisibilityOnObjects(sitem.id, [sitem.id + '-container']);
                }.bind(this));
                $$('#' + item + ' .select').each(function(sitem) {
                    sitem.value = '';
                });
                $$('#' + item + ' .price-box').each(function(sitem) {
                    sitem.addClassName('no-display');
                });
            }.bind(this));
        }
    }
}); 

var AitDeliverydate = Class.create(Step,  
{
    initDeliverydate: function()
    {
        this.initEvents(this.container); 
    }
    
});

var AitGiftwrap = Class.create(Step,  
{
    initGiftwrap: function()
    {
        this.initEvents(this.container);
    }
    
}); 
*/