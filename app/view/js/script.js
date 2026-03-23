// file for common functions etc.

// --- Loader helpers ---
function showLoader() {
  const el = document.getElementById('alch-loader');
  if (el) el.style.display = 'block';
}

function hideLoader() {
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
  target.onload = function() { hideLoader(); };

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