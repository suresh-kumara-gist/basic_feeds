// (function ($, Drupal, drupalSettings) {
//   'use strict';
//   Drupal.behaviors.basic_feeds = {
//     attach: function (context, settings) {
//     }
//   };
// })(window.jQuery, window.Drupal, window.drupalSettings);

function generateRandomkey() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i < 30; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}

function insertApikey() {
  jQuery('#edit-siteapikey').val(generateRandomkey());
}