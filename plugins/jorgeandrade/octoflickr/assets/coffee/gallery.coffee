class window.Gallery
	constructor: (@name = null, @arg = {}) ->
		
	init: ()->
		dest = $(@arg.dest)
		src = $(@arg.src)
		gallery = @
		src.on 'keyup', ->
			dest.val gallery.createKey @.value
	verif: () ->
		message = {}
		if @arg.src == ''
			message.src = "src can't be empty"
		if @arg.dest == ''
			message.dest = "dest can't be empty"
		message

	createKey: (str) ->
		str = str.replace(/^\s+|\s+$/g, '');
		str = str.toLowerCase();

		from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;"
		to   = "aaaaaeeeeeiiiiooooouuuunc------"
		for i in from
			str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));


		str = str.replace(/[^a-z0-9 -]/g, '')
		.replace(/\s+/g, '-')
		.replace(/-+/g, '-');

		return str;

$(document).on 'ready', ->
	gallery = new Gallery 'Gaticos', {src:'#Form-field-Gallerie-nombre', dest:'#Form-field-Gallerie-clave'}
	gallery.init()