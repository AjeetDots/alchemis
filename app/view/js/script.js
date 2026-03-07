// file for common functions etc.

// script to fix issues with iframes
function iframeLocation (iframe, href) {

  if(iframe.location){
    iframe.location.href = href;
  }else{
    jQuery(iframe).attr('src', href);
  }

}

function iframeReload (iframe) {
  if(iframe.contentWindow){
    iframe.contentWindow.location.reload();
  }else{
    iframe.location.reload();
  }
}

function iframeWindow (iframe) {
  if(iframe.constructor.name !== 'Window') return iframe.contentWindow;
  return iframe;
}