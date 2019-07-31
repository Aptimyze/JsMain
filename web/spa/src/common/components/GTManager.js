import React from 'react';

(function(w, d, s, l, i) {
  w[l] = w[l] || [];
  w[l].push({'gtm.start': new Date().getTime(),event: 'gtm.js'});
  let f = d.getElementsByTagName(s)[0],
    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
  j.async = true;
  j.src =
    '//www.googletagmanager.com/gtm.js?id=' + i + dl;
  f.parentNode.insertBefore(j, f);
})(window, document, 'script', 'dataLayer', 'GTM-KC9PHJ');

export const GTM = (sourcegroup, sourceid, age, mtongue, city, gender) => {
  return ((sourcegroup, sourceid, age, mtongue, city, gender) => {
    dataLayer.push({'sourcegroup': sourcegroup});
    dataLayer.push({'sourceid': sourceid});
    dataLayer.push({'age': age});
    dataLayer.push({'mtongue': mtongue});
    dataLayer.push({'city': city});
    dataLayer.push({'gender': gender});
  })(sourcegroup, sourceid, age, mtongue, city, gender)
};

export const Criteo = (pageObject) => {
  return ((pageObject) => {
    dataLayer.push(pageObject); //pageObject needs to be an object
  })(pageObject)
};