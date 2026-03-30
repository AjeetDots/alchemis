// file for common functions etc.

// --- Loader helpers ---
function showLoader() {
	// #region agent log: loader shown
	try{fetch('http://127.0.0.1:7520/ingest/4ceb35f8-bc50-4bc1-8562-a13e600978c3',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'651a1a'},body:JSON.stringify({sessionId:'651a1a',location:'app/view/js/script.js:showLoader',message:'showLoader() called',data:{},timestamp:Date.now(),runId:'pre_hypotheses',hypothesisId:'H_LOADER_ONLOAD_NOT_FIRED'})}).catch(()=>{});}catch(_e){}
	// #endregion
  const el = document.getElementById('alch-loader');
  if (el) el.style.display = 'block';
}

function hideLoader() {
	// #region agent log: loader hidden
	try{fetch('http://127.0.0.1:7520/ingest/4ceb35f8-bc50-4bc1-8562-a13e600978c3',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'651a1a'},body:JSON.stringify({sessionId:'651a1a',location:'app/view/js/script.js:hideLoader',message:'hideLoader() called',data:{},timestamp:Date.now(),runId:'pre_hypotheses',hypothesisId:'H_LOADER_ONLOAD_NOT_FIRED'})}).catch(()=>{});}catch(_e){}
	// #endregion
  const el = document.getElementById('alch-loader');
  if (el) el.style.display = 'none';
}

// Patch form.submit() so programmatic submits also show the loader
(function() {
  const _submit = HTMLFormElement.prototype.submit;
  HTMLFormElement.prototype.submit = function() {
    showLoader();
    _submit.call(this);
  };
})();

// Helpers to work safely with iframes (accept element, window, or element id string).
function iframeLocation(iframe, href) {
  let target = iframe;
  if (typeof iframe === 'string') {
    target = document.getElementById(iframe);
  }
  if (!target) {
    return;
  }

  showLoader();
  target.onload = function() {
		// #region agent log: iframe onload fired
		try{fetch('http://127.0.0.1:7520/ingest/4ceb35f8-bc50-4bc1-8562-a13e600978c3',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'651a1a'},body:JSON.stringify({sessionId:'651a1a',location:'app/view/js/script.js:iframeLocation:onload',message:'iframe onload fired',data:{href:href},timestamp:Date.now(),runId:'pre_hypotheses',hypothesisId:'H_LOADER_ONLOAD_NOT_FIRED'})}).catch(()=>{});}catch(_e){}
		// #endregion
		hideLoader();
	};

  if (target.contentWindow && target.contentWindow.location) {
    target.contentWindow.location.href = href;
    return;
  }
  if (target.location) {
    target.location.href = href;
    return;
  }
  jQuery(target).attr('src', href);
}

function iframeReload(iframe) {
  let target = iframe;
  if (typeof iframe === 'string') {
    target = document.getElementById(iframe);
  }
  if (!target) {
    return;
  }
  if (target.contentWindow && target.contentWindow.location) {
    target.contentWindow.location.reload();
  } else if (target.location) {
    target.location.reload();
  }
}

function iframeWindow(iframe) {
  if (!iframe) {
    return null;
  }
  if (iframe.constructor && iframe.constructor.name !== 'Window') {
    return iframe.contentWindow;
  }
  return iframe;
}