fx.Slide = Class.create();
fx.Slide.prototype = {
	setOptions: function(options) {
		this.options = {
			delay: 50,
			opacity: false,
			openFirst: false
		}
		Object.extend(this.options, options || {});
	},

	initialize: function(togglers, sliders, options) {
		this.sliders  = sliders;
		this.togglers = togglers;
		this.setOptions(options);
		sliders.each(function(el, i){
			el.style.display = 'none';
			options.onComplete = function(){
				if (el.offsetHeight == 0) el.style.display = 'none';
				if (el.offsetHeight > 0) el.style.height = '1%';
			}
			el.fx = new fx.Combo(el, options);
			el.fx.hide();
		});

		togglers.each(function(toggler, i)
		{
			toggler.onclick = function()
			{
				this.toggle(sliders[i], toggler);
			}
			.bind(this);
		}
		.bind(this));

		// Open first panel (e.g. Client Details) by default when option is set
		if (this.options.openFirst && togglers.length > 0 && sliders.length > 0) {
			var self = this;
			setTimeout(function() { self.toggle(self.sliders[0], self.togglers[0]); }, 50);
		}
	},

	toggle: function(slider, toggler){
		
		this.sliders.each(function(el, i){
			if (el.offsetHeight > 0) this.clear(el);
		}.bind(this));
		
		this.togglers.each(function(el, i){
			 Element.removeClassName(el, 'moofx-toggler-down');
		}.bind(this));
		
		setTimeout(function(){this.clear(slider);}.bind(this), this.options.delay);
		
		slider.style.display = 'block';
		if (slider.offsetHeight <= 0) {
			Element.addClassName(toggler, 'moofx-toggler-down');
		}
	},

	clear: function(slider){
		slider.fx.clearTimer();
		slider.fx.toggle();
	}
}


//fx.Slide = Class.create();
//fx.Slide.prototype = {
//	setOptions: function(options) {
//		this.options = {
//			delay: 50,
//			opacity: false
//		}
//		Object.extend(this.options, options || {});
//	},
//
//	initialize: function(togglers, sliders, options) {
//		this.sliders  = sliders;
//		this.togglers = togglers;
//		this.setOptions(options);
//		sliders.each(function(el, i)
//		{
//			el.style.display = 'none';
//			options.onComplete = function(){
//				if (el.offsetHeight == 0) el.style.display = 'none';
//				if (el.offsetHeight > 0) el.style.height = '1%';
//			}
//			el.fx = new fx.Combo(el, options);
//			el.fx.hide();
//		});
//
//		togglers.each(function(toggler, i)
//		{
//			if(i == 0) this.toggle(sliders[i], toggler);
//			toggler.onclick = function()
//			{
//				this.toggle(sliders[i], toggler);
//			}.bind(this);
//		}.bind(this));
//	},
//
//	toggle: function(slider, toggler)
//	{
//		
////		this.sliders.each(function(el, i)
//// 		{
////			if (el.offsetHeight > 0) this.clear(el);
////		}.bind(this));
//		
////		alert(slider.toString());
//		var x = 0;
//		this.togglers.each(function(el, i)
//		{
//			
//////			if (this.sliders[x].offsetHeight > 0) 
//////			if (this.sliders[x].style.display = '')
//			if (Element.hasClassName(el, 'moofx-toggler-down'))
//			{
//				Element.removeClassName(el, 'moofx-toggler-down');
//			}
//			else
//			{
//				Element.addClassName(el, 'moofx-toggler-down');
//			}
//			x++;
//		}.bind(this));
//		
//		setTimeout(function(){this.clear(slider);}.bind(this), this.options.delay);
//		
//		slider.style.display = 'block';
//		
////		if (slider.offsetHeight <= 0 && Element.hasClassName(toggler, 'moofx-toggler-down')) {
//		if (slider.style.display = 'block' && !Element.hasClassName(toggler, 'moofx-toggler-down')) 
//		{
//			Element.addClassName(toggler, 'moofx-toggler-down');
////			alert("toggler.hasClassName = " + Element.hasClassName(toggler, 'moofx-toggler-down'));
//		}
//	},
//
//	clear: function(slider){
//		slider.fx.clearTimer();
//		slider.fx.toggle();
//	}
//}

/* -------------------------------------------- */
/* -- page loader ----------------------------- */
/* -------------------------------------------- */

function init_moofx() 
{
  try {
    var sliderEls = document.getElementsByClassName('moofx-slider');
    var togglerEls = document.getElementsByClassName('moofx-toggler');
    if (!sliderEls.length || !togglerEls.length) {
      return;
    }
    if (typeof $A !== 'undefined' && typeof fx !== 'undefined' && typeof fx.Slide !== 'undefined') {
      var sliders  = $A(sliderEls);
      var togglers = $A(togglerEls);
      if (sliders.length === togglers.length) {
        var slide = new fx.Slide(togglers, sliders, { opacity: true, duration: 200, openFirst: false });
        return;
      }
    }
    init_moofx_fallback(sliderEls, togglerEls);
  } catch (e) {
    var sliderEls = document.getElementsByClassName('moofx-slider');
    var togglerEls = document.getElementsByClassName('moofx-toggler');
    if (sliderEls.length && togglerEls.length) {
      init_moofx_fallback(sliderEls, togglerEls);
    }
    if (typeof console !== 'undefined' && console.error) {
      console.error('init_moofx:', e);
    }
  }
}

function init_moofx_fallback(sliderEls, togglerEls) {
  var sliders = Array.prototype.slice.call(sliderEls);
  var togglers = Array.prototype.slice.call(togglerEls);
  if (sliders.length !== togglers.length) return;
  for (var i = 0; i < sliders.length; i++) {
    sliders[i].style.display = 'none';
  }
  togglers.forEach(function(toggler, i) {
    toggler.addEventListener('click', function() {
      var slider = sliders[i];
      var isOpen = slider.style.display === 'block';
      sliders.forEach(function(s) { s.style.display = 'none'; });
      togglers.forEach(function(t) { t.classList.remove('moofx-toggler-down'); });
      if (!isOpen) {
        slider.style.display = 'block';
        toggler.classList.add('moofx-toggler-down');
      }
    });
  });
}