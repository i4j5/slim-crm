$ -> 

	smartDate = ->
	  $('.js-moment').each (index, el) ->
	    $el = $(el)
	    time = $el.data('time')
	    if moment().format('l') == moment.unix(time).format('l')
	      $el.text moment.unix(time).fromNow()
	    else if moment().format('YYYY') == moment.unix(time).format('YYYY')
	      $el.text moment.unix(time).format('D MMMM в h:mm')
	    else
	      $el.text moment.unix(time).format('D MMMM YYYY')
	    return
	  return

	$('.button-collapse').sideNav
	  menuWidth: 180
	  edge: 'right'

	$('select').material_select()

	moment.lang 'ru'
	
	smartDate()

	setInterval smartDate, 30000