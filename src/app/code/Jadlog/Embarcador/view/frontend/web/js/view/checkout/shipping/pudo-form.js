define([
  'jquery',
  'ko',
  'uiComponent',
  'Magento_Checkout/js/model/quote',
  'Jadlog_Embarcador/js/model/pudo-model',
  'Magento_Checkout/js/model/shipping-service',
  'Jadlog_Embarcador/js/view/checkout/shipping/pudo-service',
  'mage/translate',
], function($, ko, Component, quote, pudoModel, shippingService, pudoService, t) {
  'use strict';

  return Component.extend({
    defaults: {
      template: 'Jadlog_Embarcador/checkout/shipping/pudo-form'
    },

    initialize: function(config) {
      this.pudos = ko.observableArray();
      this.selectedPudo = ko.observable();

      this.selectedMethod = ko.computed(function() {
        var method = quote.shippingMethod();
        var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;
        return selectedMethod;
      }, this);

      this.shouldShowPopupJadlogPickup = ko.computed(function() {
        return (this.selectedMethod() == 'jadlog_pickup_jadlog_pickup');
      }, this);

      this.showPopupJadlogPickup = function() {
        if (this.shouldShowPopupJadlogPickup()) {
          this.reloadPudos();
        }
      }

      this._super();
    },

    initObservable: function() {
      this._super();

      this.showPudoSelection = ko.computed(function() {
        return this.pudos().length != 0
      }, this);

      quote.shippingMethod.subscribe(function(method) {
        var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;
        //if (selectedMethod == 'jadlog_pickup_jadlog_pickup') {
        //  this.reloadPudos();
        //}
        this.showPopupJadlogPickup();
      }, this);

      this.selectedPudo.subscribe(function(pudo) {
        if (quote.shippingAddress().extensionAttributes == undefined) {
          quote.shippingAddress().extensionAttributes = {};
        }
        quote.shippingAddress().extensionAttributes.jadlog_pudo = pudo;
        pudoModel.setData(pudo);
      });

      return this;
    },

    setPudoList: function(list) {
      this.pudos(list);
    },

    reloadPudos: function() {
      pudoService.getPudoList(quote.shippingAddress(), this);
      var defaultPudo = this.pudos()[0];
      if (defaultPudo) {
        this.selectedPudo(defaultPudo);
      }
      $("#popup-jadlog-pickup").modal("openModal");
    },

    getPudo: function() {
      var pudo;
      if (this.selectedPudo()) {
        for (var i in this.pudos()) {
          var m = this.pudos()[i];
          if (m.name == this.selectedPudo()) {
            pudo = m;
          }
        }
      } else {
        pudo = this.pudos()[0];
      }
      return pudo;
    },

    initSelector: function() {
      var startPudo = this.getPudo();
    },

    getPudoValue: function() {
      return this.selectedPudo();
    }
  });
});