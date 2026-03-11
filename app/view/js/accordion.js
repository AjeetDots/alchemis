/**
 * Standalone accordion for .moofx-toggler / .moofx-slider panels.
 * No Prototype, jQuery, or eval. Works when CSP blocks other scripts.
 */
(function () {
  function run() {
    var togglers = document.getElementsByClassName('moofx-toggler');
    var sliders = document.getElementsByClassName('moofx-slider');
    if (!togglers.length || !sliders.length || togglers.length !== sliders.length) return;

    for (var i = 0; i < sliders.length; i++) {
      sliders[i].style.display = 'none';
    }

    for (var i = 0; i < togglers.length; i++) {
      (function (idx) {
        togglers[idx].addEventListener('click', function () {
          var targetSlider = sliders[idx];
          var isOpen = targetSlider.style.display === 'block';

          for (var j = 0; j < sliders.length; j++) {
            sliders[j].style.display = 'none';
            togglers[j].classList.remove('moofx-toggler-down');
          }
          if (!isOpen) {
            targetSlider.style.display = 'block';
            togglers[idx].classList.add('moofx-toggler-down');
          }
        });
      })(i);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', run);
  } else {
    run();
  }
})();
