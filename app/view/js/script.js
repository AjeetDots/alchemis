// file for common functions etc.

// Helpers to work safely with iframes (accept element, window, or element id string).
function iframeLocation(iframe, href) {
  var target = iframe;
  if (typeof iframe === 'string') {
    target = document.getElementById(iframe);
  }
  if (!target) {
    return;
  }
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
  var target = iframe;
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