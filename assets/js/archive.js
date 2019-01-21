$(document).ready(function () {
  $('input[name="viewOptions"]').on('change', function(el) {
    var view = $(el.target).data('target')
    $('.collapse').collapse('hide')
    $('#' + view).collapse('toggle')
  })
})
