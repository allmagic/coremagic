// Generated by CoffeeScript 1.9.1
(function() {
  window.Images = (function() {
    function Images() {
      this.url = 'https://api.flickr.com/services/feeds/photos_public.gne?jsoncallback=?';
      this.row = $('#row-images-container');
      this.rig = $('#row-images-gallery-container');
      this.search = $('#Form-field-Image-search');
      this.gallery = $('#Form-field-Image-gallerie');
    }

    Images.prototype.init = function() {
      var self;
      $(document).find('form').on('submit', function() {
        return false;
      });
      this.searchPhotos();
      this.getImages();
      self = this;
      this.gallery.on('change', function() {
        return self.getImages();
      });
      return this.search.on('change', function() {
        return self.searchPhotos(this.value);
      });
    };

    Images.prototype.searchPhotos = function(tags) {
      var data;
      if (tags == null) {
        tags = null;
      }
      this.row.empty();
      data = {
        format: 'json'
      };
      if (tags !== null) {
        data.tags = tags;
      }
      return $.getJSON(this.url, data, (function(_this) {
        return function(data) {
          _this.items = data.items;
          return $.each(_this.items, function(i) {
            var thumb;
            thumb = _this.createThumbnail(i);
            return _this.row.append(thumb);
          });
        };
      })(this));
    };

    Images.prototype.getImages = function() {
      var url;
      this.rig.empty();
      url = '/backend/jorgeandrade/octoflickr/images/create';
      return $.ajax({
        url: url,
        type: 'post',
        data: {
          gallery_id: this.gallery.val()
        },
        dataType: 'json',
        headers: {
          'X-OCTOBER-REQUEST-HANDLER': 'onGetImages',
          'X-OCTOBER-REQUEST-PARTIALS': ''
        },
        success: (function(_this) {
          return function(data) {
            $('#titlegallery').text(data.images.length + " images in gallery");
            return $.each(data.images, function(i) {
              return _this.rig.append($('<li/>').append($('<div/>').addClass('attachment-item').append($('<a/>').attr('href', 'javascript:;').addClass('active-image').append($('<img/>').addClass('attachment-image').attr('src', data.images[i].url))).append($('<div/>').addClass('uploader-toolbar').append($('<h3/>').append($('<abbr/>').attr('title', data.images[i].title).text(data.images[i].title))).append($('<a/>').attr('href', 'javascript:;').addClass('uploader-remove oc-icon-times').on('click', function() {
                return $.ajax({
                  url: url,
                  data: {
                    id: data.images[i].id
                  },
                  type: 'post',
                  dataType: 'json',
                  headers: {
                    'X-OCTOBER-REQUEST-HANDLER': 'onDeleteImages',
                    'X-OCTOBER-REQUEST-PARTIALS': ''
                  },
                  success: (function(_this) {
                    return function(data) {
                      return $(_this).parent().parent().parent().remove();
                    };
                  })(this)
                });
              })))));
            });
          };
        })(this)
      });
    };

    Images.prototype.createThumbnail = function(i) {
      var $container, item, self;
      item = this.items[i];
      self = this;
      return $container = $('<div/>').addClass('col-sm-6 col-md-3').append($('<div/>').addClass('thumbnail').append($('<a/>').addClass('active-image').append($('<img/>').addClass('attachment-image').attr('src', item.media.m))).append($('<div/>').addClass('caption').append($('<h3/>').text(item.title.substr(0, 20))).append($('<p/>').append("<strong>Author:</strong> " + item.author)).append($('<p/>').append($('<a/>').addClass('btn btn-primary').text('Add to a gallery').on('click', function() {
        var $t, image, url;
        url = '/backend/jorgeandrade/octoflickr/images/create';
        $t = $(this);
        $t.text('Adding...');
        image = {
          'Image[title]': item.title,
          'Image[description]': item.description,
          'Image[author]': item.author,
          'Image[url]': item.media.m,
          'Image[gallerie]': self.gallery.val(),
          _token: $('[name="_token"]').val(),
          _session_key: $('[name="_session_key"]').val()
        };
        return $.ajax({
          url: url,
          data: image,
          type: 'post',
          dataType: 'json',
          headers: {
            'X-OCTOBER-REQUEST-HANDLER': 'onSave',
            'X-OCTOBER-REQUEST-PARTIALS': ''
          },
          success: function(data) {
            if (data.X_OCTOBER_REDIRECT) {
              $t.text('Image added');
              return self.getImages();
            } else {
              return $t.text('an error has ocurred.');
            }
          }
        });
      })).append($('<a/>').addClass('btn btn-default').attr({
        href: item.link,
        target: '_blank'
      }).text('Show in Flickr')))));
    };

    return Images;

  })();

  $(document).on('ready', function() {
    return (new Images).init();
  });

}).call(this);
