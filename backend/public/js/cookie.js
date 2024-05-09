(function () {
  'use strict'

  const cookieName = 'cookieConsentLevel' // The cookie name
  const cookieLifetime = 365 // Cookie expiry in days

  const bannerElement = document.getElementById('cookie-consent')

  const AcceptAllElement = document.getElementById('cookie-consent-accept-all')
  const DeclineAllElement = document.getElementById('cookie-consent-decline-all')
  const ClearAllCookiesElement = document.getElementById('cookie-consent-clear-all-cookies')
  
  const configuration = {
    gtag: {
      enabled: true,
      measurement_id: "" 
    },
    meta_pixel: {
      enabled: false,
      pixel_id: "{your-pixel-id-goes-here}"
    },
    hotjar: {
      enabled: false,
      hjid: ""
    }
  }

  const Permissions = {
    Necessary: 1,       //  000001
    Functional: 1 << 1,  //  000010
    Analytics: 1 << 2,  //  000100
    Advertisement: 1 << 3,  //  001000
  }

  /**
   * Set a cookie
   * @param cname - cookie name
   * @param cvalue - cookie value
   * @param exdays - expiry in days
   */
  const _setCookie = function (cname, cvalue, exdays) {
    const d = new Date()
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000))
    const expires = 'expires=' + d.toUTCString()
    document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/'
  }

  /**
   * Get a cookie
   * @param cname - cookie name
   * @returns string
   */
  const _getCookie = function (cname) {
    const name = cname + '='
    const ca = document.cookie.split(';')
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i]
      while (c.charAt(0) === ' ') {
        c = c.substring(1)
      }
      if (c.indexOf(name) === 0) {
        return c.substring(name.length, c.length)
      }
    }
    return ''
  }

  const clearCookies = (wildcardDomain = false, primaryDomain = true, path = null) => {
    const pathSegment = path ? '; path=' + path : ''
    const expSegment = "=;expires=Thu, 01 Jan 1970 00:00:00 GMT"
    document.cookie
      .split(';')
      .forEach(
        (c) => {
          primaryDomain && (document.cookie = c.replace(/^ +/, "").replace(/=.*/, expSegment + pathSegment))
          wildcardDomain && (document.cookie = c.replace(/^ +/, "").replace(/=.*/, expSegment + pathSegment + '; domain=' + document.domain))
        }
      )
  }

  /**
   * Should the cookie banner be shown?
   */
  const _shouldShowPopup = () => {
    let consentLevel = _getCookie(cookieName)
    if (consentLevel !== '') {
      consentLevel = parseInt(consentLevel)
      OnDecide(consentLevel)
      return false
    } else {
      return true
    }
  }

  const hasClass = (ele, cls) => {
    return !!ele.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
  }

  const addClass = (ele, cls) => {
    if (!hasClass(ele, cls)) ele.className += " " + cls;
  }

  const removeClass = (ele, cls) => {
    if (hasClass(ele, cls)) {
      const reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
      ele.className = ele.className.replace(reg, ' ');
    }
  }


  const OnDecide = (userPermission) => {
    // 0b01111 is the full level
    _setCookie(
      cookieName,
      userPermission,
      cookieLifetime)

    addClass(bannerElement, 'zone-hidden')

    !!ClearAllCookiesElement && ClearAllCookiesElement.classList.remove('d-none')

    const preventCallbacks = !!document.querySelector('script#cookie-consent-required-necessary-prevent-callbacks')
    if (!preventCallbacks) {
      if (userPermission & Permissions.Necessary) {
        console.info('Necessary permission level accepted.')
        // Callback comes here
      }
      if (userPermission & Permissions.Functional) {
        console.info('Functional permission level accepted.')
        // Callback comes here
      }
      if (userPermission & Permissions.Analytics) {
        console.info('Analytics permission level accepted.')
        // Callback comes here
        if (configuration.meta_pixel && configuration.meta_pixel.enabled) {
          console.log('Meta Pixel start')
          !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
              n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
          }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', configuration.meta_pixel.pixel_id);
          fbq('track', 'PageView');
          console.log('Meta Pixel end')
        }

        if (configuration.gtag && configuration.gtag.enabled) {
          console.log('GTAG start')
          window.dataLayer = window.dataLayer || [];

          function gtag() {
            window.dataLayer.push(arguments);
          }

          gtag('js', new Date());

          gtag('config', configuration.gtag.measurement_id, { 'anonymize_ip': true });

          const gJsSource = document.createElement('script')
          gJsSource.async = !0;
          gJsSource.src = "https://www.googletagmanager.com/gtag/js?id=" + configuration.gtag.measurement_id
          document.body.appendChild(gJsSource);
          console.log('GTAG end')
        }
        if (configuration.hotjar && configuration.hotjar.enabled) {
          (function (h, o, t, j, a, r) {
            h.hj = h.hj || function () {
              (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = { hjid: configuration.hotjar.hjid, hjsv: 6 };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
          })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
        }

      }
      if (userPermission & Permissions.Advertisement) {
        console.info('Advertisement permission level accepted.')
        // Callback comes here
      }
    }
  }


  // Show the cookie banner on load if not previously accepted
  const showCookieBanner = () => {
    removeClass(bannerElement, 'zone-hidden')

    AcceptAllElement.addEventListener("click", (event) => {
      event.preventDefault()
      OnDecide(Permissions.Advertisement +
        Permissions.Necessary +
        Permissions.Analytics +
        Permissions.Functional)
    })

    DeclineAllElement.addEventListener("click", (event) => {
      event.preventDefault()
      OnDecide(0)
    })

    document
      .querySelectorAll('.cookie-preference-group .group-heading')
      .forEach(groupHeading =>
        groupHeading.addEventListener("click", () => {
          groupHeading.nextElementSibling.classList.toggle("zone-hidden")
          !hasClass(groupHeading, 'open')
            ? addClass(groupHeading, 'open')
            : removeClass(groupHeading, 'open')
        })
      )

  }

  !!ClearAllCookiesElement && ClearAllCookiesElement.addEventListener("click", (event) => {
    event.preventDefault()
    clearCookies()
    showCookieBanner()
  })

  // Show the cookie banner on load if not previously accepted
  if (_shouldShowPopup()) showCookieBanner()

})()