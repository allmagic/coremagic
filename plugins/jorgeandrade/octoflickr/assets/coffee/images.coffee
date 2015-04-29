class window.Images
	constructor: () ->
		@url = 'https://api.flickr.com/services/feeds/photos_public.gne?jsoncallback=?'
		@row = $('#row-images-container')
		@rig = $('#row-images-gallery-container')
		@search = $('#Form-field-Image-search')
		@gallery = $('#Form-field-Image-gallerie')
	init: ()->
		$(document).find('form').on 'submit', ->
			false
		@searchPhotos()
		@getImages()
		self = @
		@gallery.on 'change', ->
			self.getImages()
		@search.on 'change', ->
			self.searchPhotos @value
	searchPhotos: (tags = null)->
		@row.empty()
		data = format: 'json'
		if tags != null
			data.tags = tags
		$.getJSON @url, data,
			(data) =>
				@items = data.items
				$.each @items, (i) =>
					thumb = @createThumbnail i
					@row.append thumb
	getImages: ()->
		@rig.empty()
		url = '/backend/jorgeandrade/octoflickr/images/create'
		$.ajax
			url: url
			type: 'post'
			data: gallery_id: @gallery.val()
			dataType: 'json'
			headers: 
				'X-OCTOBER-REQUEST-HANDLER': 'onGetImages'
				'X-OCTOBER-REQUEST-PARTIALS': ''
			success: (data)=>
				$('#titlegallery').text "#{data.images.length} images in gallery"
				$.each data.images, (i) =>
					@rig.append(
						$('<li/>').append(
							$('<div/>').addClass('attachment-item').append(
								$('<a/>').attr('href', 'javascript:;').addClass('active-image').append(
									$('<img/>').addClass('attachment-image').attr('src', data.images[i].url)
								)
							).append(
								$('<div/>').addClass('uploader-toolbar').append(
									$('<h3/>').append(
										$('<abbr/>').attr('title', data.images[i].title).text(data.images[i].title)
									)
								).append(
									$('<a/>').attr('href', 'javascript:;').addClass('uploader-remove oc-icon-times').on 'click', ->
										$.ajax
					 						url: url
					 						data: id: data.images[i].id
					 						type: 'post'
					 						dataType: 'json'
					 						headers: 
					 							'X-OCTOBER-REQUEST-HANDLER': 'onDeleteImages'
					 							'X-OCTOBER-REQUEST-PARTIALS': ''
					 						success: (data)=>
					 							$(@).parent().parent().parent().remove()
								)
							)
						)
					)
	createThumbnail: (i)->
		item = @items[i]
		self = @
		$container = $('<div/>').addClass('col-sm-6 col-md-3')
					 .append(
					 	$('<div/>').addClass('thumbnail').append(
					 			$('<a/>').addClass('active-image').append(
					 				$('<img/>').addClass('attachment-image').attr('src', item.media.m)
					 			)
					 			
					 		).append(
					 			$('<div/>').addClass('caption')
					 			.append(
						 			$('<h3/>').text(item.title.substr(0,20))
						 		).append(
						 			$('<p/>').append("<strong>Author:</strong> #{item.author}")
						 		).append(
						 			$('<p/>').append(
						 				$('<a/>').addClass('btn btn-primary').text('Add to a gallery')
						 				.on 'click', ->
						 					url = '/backend/jorgeandrade/octoflickr/images/create'
						 					$t = $(@)
						 					$t.text('Adding...')
						 					image = 
						 					'Image[title]': item.title
						 					'Image[description]': item.description
						 					'Image[author]': item.author
						 					'Image[url]': item.media.m
						 					'Image[gallerie]': self.gallery.val()
						 					_token:	$('[name="_token"]').val()
						 					_session_key: $('[name="_session_key"]').val()
						 					$.ajax
						 						url: url
						 						data: image
						 						type: 'post'
						 						dataType: 'json'
						 						headers: 
						 							'X-OCTOBER-REQUEST-HANDLER': 'onSave'
						 							'X-OCTOBER-REQUEST-PARTIALS': ''
						 						success: (data)->
						 							if data.X_OCTOBER_REDIRECT
						 								$t.text('Image added')
						 								self.getImages()
						 							else
						 								$t.text('an error has ocurred.')
						 			).append(
						 				$('<a/>').addClass('btn btn-default')
						 				.attr(
						 					href: item.link
						 					target: '_blank'
						 				).text('Show in Flickr')
						 			)
						 		)
					 		)
					 )
		
	

$(document).on 'ready', ->
	(new Images).init()