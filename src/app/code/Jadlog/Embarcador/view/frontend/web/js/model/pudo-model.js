define([
  'ko',
  'underscore',
  'domReady!'
], function(ko, _) {
  'use strict';

  /**
   * Get totals data from the extension attributes.
   * @param {*} data
   * @returns {*}
   */
  var data = ko.observable(null);

  return {
    data: data,

    getData: function() {
      return data();
    },

    setData: function(x) {
      data(x);
    }
  };
});