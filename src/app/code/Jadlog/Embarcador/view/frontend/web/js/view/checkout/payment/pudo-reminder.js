define([
  'jquery',
  'ko',
  'uiComponent',
  'Magento_Checkout/js/model/quote',
  'Jadlog_Embarcador/js/model/pudo-model',
  'Jadlog_Embarcador/js/view/checkout/shipping/pudo-form'
], function($, ko, Component, quote, pudoModel, pudoForm) {
  'use strict';

  return Component.extend({
    defaults: {
      template: 'Jadlog_Embarcador/checkout/payment/pudo-reminder'
    },

    initialize: function(config) {
      this.selectedMethod = ko.computed(function() {
        var method = quote.shippingMethod();
        var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;
        return selectedMethod;
      }, this);

      this.shouldShowJadlogPickupInfo = ko.computed(function() {
        return (this.selectedMethod() == 'jadlog_pickup_jadlog_pickup');
      }, this);

      this.getMessage = ko.computed(function() {
        var message = "Atenção, sua entrega seguirá para o seguinte endereço\n";
        message = message + ": " + pudoModel.getData() + ".";
        return message;
      }, this);

      this._super();
    },

  });
});