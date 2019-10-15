define(
  [
    'Jadlog_Embarcador/js/view/checkout/shipping/model/resource-url-manager',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'mage/storage',
    'Magento_Checkout/js/model/shipping-service',
    'Jadlog_Embarcador/js/view/checkout/shipping/model/pudo-registry',
    'Magento_Checkout/js/model/error-processor'
  ],
  function(resourceUrlManager, quote, customer, storage, shippingService, pudoRegistry, errorProcessor) {
    'use strict';

    return {
      /**
       * Get nearest machine list for specified address
       * @param {Object} address
       */
      getPudoList: function(address, form) {
        shippingService.isLoading(true);
        var cacheKey = address.getCacheKey(),
          cache = pudoRegistry.get(cacheKey),
          serviceUrl = resourceUrlManager.getUrlForPudoList(quote);

        if (cache) {
          form.setPudoList(cache);
          shippingService.isLoading(false);
        } else {
          storage.get(
            serviceUrl, false
          ).done(
            function(result) {
              pudoRegistry.set(cacheKey, result);
              form.setPudoList(result);
            }
          ).fail(
            function(response) {
              errorProcessor.process(response);
            }
          ).always(
            function() {
              shippingService.isLoading(false);
            }
          );
        }
      }
    };
  }
);