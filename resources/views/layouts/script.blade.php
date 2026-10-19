<!-- Plugins JS File -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/optional/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.appear.min.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('assets/js/main.min.js') }}"></script>
<script src="{{ asset('js/cart.js') }}" defer></script>
<script>
    (function() {
         function c() {
             var b = a.contentDocument || (a.contentWindow && a.contentWindow.document);
             if (b) {
                 var d = b.createElement('script');
                 d.innerHTML =
                     "window.__CF$cv$params={r:'a23bca91aafdf369',t:'MTc4NTQ5MjM5NQ=='};var a=document.createElement('script');a.src='../../cdn-cgi/challenge-platform/h/g/scripts/jsd/f70cb37711aa/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);";
                 b.getElementsByTagName('head')[0].appendChild(d)
             }
         }
         if (document.body) {
             var a = document.createElement('iframe');
             a.height = 1;
             a.width = 1;
             a.style.position = 'absolute';
             a.style.top = 0;
             a.style.left = 0;
             a.style.border = 'none';
             a.style.visibility = 'hidden';
             document.body.appendChild(a);
             if ('loading' !== document.readyState) c();
             else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c);
             else {
                 var e = document.onreadystatechange || function() {};
                 document.onreadystatechange = function(b) {
                     e(b);
                     'loading' !== document.readyState && (document.onreadystatechange = e, c())
                 }
             }
         }
     })();
</script>

<script>
    (() => {
            const popup = document.querySelector('[data-cart-mini-popup]');
            if (!popup) return;

            const link = popup.querySelector('[data-cart-mini-popup-link]');
            const image = popup.querySelector('[data-cart-mini-popup-image]');
            const title = popup.querySelector('[data-cart-mini-popup-title]');
            let timeout;

            const close = () => {
                popup.classList.remove('is-visible');
                window.setTimeout(() => { popup.hidden = true; }, 200);
            };

            window.showCartMiniPopup = ({ name, imageUrl, productUrl }) => {
                window.clearTimeout(timeout);
                title.textContent = name;
                image.alt = name;
                image.src = imageUrl;
                link.href = productUrl;
                title.href = productUrl;
                popup.hidden = false;
                requestAnimationFrame(() => popup.classList.add('is-visible'));
                timeout = window.setTimeout(close, 4000);
            };

            popup.querySelector('[data-cart-mini-popup-close]').addEventListener('click', close);
        })();
</script>
