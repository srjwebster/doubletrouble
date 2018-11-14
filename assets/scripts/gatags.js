jQuery(document).ready(function ($) {

  window.dataLayer = window.dataLayer || [];

  function gtag() {
    dataLayer.push(arguments);
  }

  gtag('js', new Date());
  gtag('config', 'UA-70693713-1');
  if ($('.product_cat-custom').length !== 0 || $('.product_cat-vouchers').length !== 0)  {
  }
  else if($('.single-product').length === 1){
    gtag('js', new Date());
    gtag('config', 'AW-860699185');
    gtag('event', 'page_view', {
      'send_to': 'AW-860699185',
      'dynx_itemid': itemVariables.ID,
      'dynx_pagetype': itemVariables.Category,
      "user_id": "'" + itemVariables.User + "'"
    });
  }
});
