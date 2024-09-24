const lockWindow = () => {
  $.blockUI({
    message: "<h1>Espere por favor...</h1>",
    css: {
      border: 'none',
      padding: '15px',
      backgroundColor: '#000',
      opacity: .5,
      color: '#fff',
      display: 'flex',
      top: 0,
      bottom: 0,
      left: 0,
      right: 0,
      'z-index': 1051,
      width: '100%',
      'justify-content': 'center',
      'align-items': 'center',
    }
  });
};

const unlockWindow = () => $.unblockUI();

function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this, args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};

function notice(title, text, type){
  Swal.fire(
  title,
  text,
  type,
  )
}

$('.date-datepicker').datepicker({
  format: 'dd/mm/yyyy',
  language: 'es-ES',
});

function cleanAllChildren(element){
  while (element.firstChild) {
    element.removeChild(element.firstChild); 
  }
}