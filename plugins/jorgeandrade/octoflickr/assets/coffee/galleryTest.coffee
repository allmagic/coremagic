describe 'a generate unique key for gallery', ->
	it 'should not write a name', ->
		gallery = new Gallery
		expect(gallery.name).toBeNull

	it 'should write a name', ->
		gallery = new Gallery 'Gaticos'
		expect(gallery.name).toEqual 'Gaticos'

	it 'should args.src and arg.dest cant be empty', ->
		gallery = new Gallery 'Gaticos', {src: '', dest: ''}
		expect(gallery.verif()).toEqual {src: "src can't be empty", dest: "dest can't be empty"}

	it 'should prepare inputs for write de unique key', ->
		gallery = new Gallery 'Gaticos', {src: '[data-id:"src"]', dest: '[data-id:"dest"]'}
		expect(gallery.prepare()).toEqual