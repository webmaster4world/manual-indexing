
/**
 * Product:     One-Page Checkout Booster
 * Package:     Aitoc_Aitcheckout_1.1.3_117677
 * Purchase ID: n/a
 * Generated:   2011-09-11 17:29:56
 * File path:   skin/frontend/base/default/js/aitoc/aitcheckout/checkout/step/giftmessage.js
 * Copyright:   (c) 2011 AITOC, Inc.
 */
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