(function($) {
	"use strict";
	$.fn.get_oembed = function(callback, onlycode) {
		var $this = $(this),
			$getoembed = $this.next('.oembed_code');

		if ($getoembed[0]) {
			var $getcode = $getoembed.html();
			$getoembed.remove();
			var data = {
					action: 'get_oembed',
					onlycode: onlycode,
					urlOembed: escape($getcode),
					dataType: "json",
			};
			$.post(SiteParameters.admin_ajax, data, function(data){
				if (data != '') {
					try {
						var parsedData = JSON.parse(data);
						if (parsedData.code == 'null') {
							$this.html('<b>oEmbed not supported.</b>');
							$this.closest('.uploader-uncode-media').find('.spinner').removeClass('visible');
						} else $this.html(parsedData.code);
						if (callback && onlycode) {
							var mime,
								imgWidth,
								imgHeight;
							mime = parsedData.mime;
							if (parsedData.mime == 'image/url') {
								$(parsedData.code).on('load', function(event) {
									imgWidth = $(event.target)[0].width;
									imgHeight = $(event.target)[0].height;
									callback(mime, imgWidth, imgHeight);
								});
							} else {
								imgWidth = parsedData.width;
								imgHeight = parsedData.height;
								callback(mime, imgWidth, imgHeight);
							}
						}
					} catch (e) {
						$this.closest('.uploader-uncode-media').find('.spinner').removeClass('visible');
					}
				}
			});
		}
	}
	$(document).on('ready', function() {
		$(document).on('DOMNodeInserted', function(e) {
			try {
				if ($(e.target).closest('.media-modal').length || $(e.target).closest('.wpb_el_type_media_element').length || $(e.target).closest('.attachments').length) {
					if ($(e.target).is('.attachment')) {
						if ($(e.target).find(".oembed").length > 0) $(e.target).find('.oembed:not(.rendered)').get_oembed(null, true);
					}
					if ($(e.target).find('.attachment-media-view').length > 0) {
						if ($(e.target).find(".oembed").length > 0) $(e.target).find('.oembed:not(.rendered)').get_oembed(null, true);
					}
					if ($(e.target).is('.format-settings')) {
						$.each($(".oembed:not(.rendered)", e.target), function(index, val) {
							$(this).get_oembed(null, true);
						});
					}
				}
			} catch (e) {}
		});
	});
	$('.vc_welcome-header').html('Welcome to Uncode<br>Visual Composer Version');
	$('a.deactivate-jscomposer').on('click', function(e) {
		e.preventDefault();
		$.ajax({
			type: 'post',
			dataType: "json",
			data: {
				action: 'deactivate_js_composer',
			},
			url: ajaxurl,
			success: function(data) {
				if (data == 1) {
					$('a.deactivate-jscomposer').addClass('button-disabled');
					window.location = 'admin.php?page=uncode-plugins';
				}
			}
		});
	});
})(jQuery);

/*
 * EASYDROPDOWN - A Drop-down Builder for Styleable Inputs and Menus
 * Version: 2.1.4
 * License: Creative Commons Attribution 3.0 Unported - CC BY 3.0
 * http://creativecommons.org/licenses/by/3.0/
 * This software may be used freely on commercial and non-commercial projects with attribution to the author/copyright holder.
 * Author: Patrick Kunka
 * Copyright 2013 Patrick Kunka, All Rights Reserved
 */
(function($) {
	function EasyDropDown() {
		this.isField = true,
			this.down = false,
			this.inFocus = false,
			this.disabled = false,
			this.cutOff = false,
			this.hasLabel = false,
			this.keyboardMode = false,
			this.nativeTouch = true,
			this.wrapperClass = 'colors-dropdown',
			this.onChange = null;
	};
	EasyDropDown.prototype = {
		constructor: EasyDropDown,
		instances: {},
		init: function(domNode, settings) {
			var self = this;
			$.extend(self, settings);
			self.$select = $(domNode);
			self.id = domNode.id;
			self.options = [];
			self.$options = self.$select.find('option');
			self.isTouch = ('ontouchstart' in window || navigator.maxTouchPoints);
			self.$select.removeClass(self.wrapperClass + ' dropdown');
			if (self.$select.is(':disabled')) {
				self.disabled = true;
			};
			if (self.$options.length) {
				self.$options.each(function(i) {
					var $option = $(this);
					if ($option.is(':selected')) {
						self.selected = {
							index: i,
							title: $option.text(),
							color: $option.attr('class')
						}
						self.focusIndex = i;
					};
					if ($option.hasClass('label') && i == 0) {
						self.hasLabel = true;
						self.label = $option.text();
						$option.attr('value', '');
					} else {
						self.options.push({
							domNode: $option[0],
							title: $option.text(),
							value: $option.val(),
							color: $option.attr('class'),
							disabled: $option.is(':disabled'),
							selected: $option.is(':selected')
						});
					};
				});
				if (!self.selected) {
					self.selected = {
						index: 0,
						title: self.$options.eq(0).text(),
					}
					self.focusIndex = 0;
				};
				self.render();
			};
		},
		render: function() {
			var self = this,
				touchClass = self.isTouch && self.nativeTouch ? ' touch' : '',
				disabledClass = self.disabled ? ' disabled' : '';

			self.$container = self.$select.wrap('<div class="' + self.wrapperClass + touchClass + disabledClass + '"><span class="old"/></div>').parent().parent();
			self.$active = $('<span class="selected">' + self.selected.title + (self.selected.color != '' ? ' <small>: '+ self.selected.color +' </small>' : '') + '<span class="color style-' + self.selected.color + '-bg"></span></span>').appendTo(self.$container);
			self.$carat = $('<span class="carat"/>').appendTo(self.$container);
			self.$scrollWrapper = $('<div class="dropdown-colors-list"><ul/></div>').appendTo(self.$container);
			self.$dropDown = self.$scrollWrapper.find('ul');
			self.$form = self.$container.closest('form');
			$.each(self.options, function() {
				var option = this,
					disabled = option.disabled ? ' disabled' : '',
					active = option.selected ? ' active' : '',
					liclass = '';
				if (disabled != '' || active != '') liclass = ' class="' + disabled + active + '"';
				self.$dropDown.append('<li' + liclass + '>' + option.title + (option.color != '' ? ' <small>: '+ option.color +' </small>' : '') + '<span class="color style-' + option.color + '-bg"></span></li>');
			});
			self.$items = self.$dropDown.find('li');
			if (self.cutOff && self.$items.length > self.cutOff) self.$container.addClass('scrollable');
			self.getMaxHeight();
			if (self.isTouch && self.nativeTouch) {
				self.bindTouchHandlers();
			} else {
				self.bindHandlers();
			};
		},
		getMaxHeight: function() {
			var self = this;
			self.maxHeight = 0;
			for (i = 0; i < self.$items.length; i++) {
				var $item = self.$items.eq(i);
				self.maxHeight += $item.outerHeight();
				if (self.cutOff == i + 1) {
					break;
				};
			};
		},
		bindTouchHandlers: function() {
			var self = this;
			self.$container.on('click.easyDropDown', function() {
				self.$select.focus();
			});
			self.$select.on({
				change: function() {
					var $selected = $(this).find('option:selected'),
						title = $selected.text(),
						value = $selected.val();
					self.$active.text(title);
					if (typeof self.onChange === 'function') {
						self.onChange.call(self.$select[0], {
							title: title,
							value: value
						});
					};
				},
				focus: function() {
					self.$container.addClass('focus');
				},
				blur: function() {
					self.$container.removeClass('focus');
				}
			});
		},
		bindHandlers: function() {
			var self = this;
			self.query = '';
			self.$container.on({
				'click.easyDropDown': function() {
					if (!self.down && !self.disabled) {
						self.open();
					} else {
						self.close();
					};
				},
				'mousemove.easyDropDown': function() {
					if (self.keyboardMode) {
						self.keyboardMode = false;
					};
				}
			});
			$('body').on('click.easyDropDown.' + self.id, function(e) {
				var $target = $(e.target),
					classNames = self.wrapperClass.split(' ').join('.');
				if (!$target.closest('.' + classNames).length && self.down) {
					self.close();
				};
			});
			self.$items.on({
				'click.easyDropDown': function() {
					var index = $(this).index();
					self.select(index);
					self.$select.focus();
				},
				'mouseover.easyDropDown': function() {
					if (!self.keyboardMode) {
						var $t = $(this);
						$t.addClass('focus').siblings().removeClass('focus');
						self.focusIndex = $t.index();
					};
				},
				'mouseout.easyDropDown': function() {
					if (!self.keyboardMode) {
						$(this).removeClass('focus');
					};
				}
			});
			self.$select.on({
				'focus.easyDropDown': function() {
					self.$container.addClass('focus');
					self.inFocus = true;
				},
				'blur.easyDropDown': function() {
					self.$container.removeClass('focus');
					self.inFocus = false;
				},
				'keydown.easyDropDown': function(e) {
					if (self.inFocus) {
						self.keyboardMode = true;
						var key = e.keyCode;
						if (key == 38 || key == 40 || key == 32) {
							e.preventDefault();
							if (key == 38) {
								self.focusIndex--
									if ($(self.$items[self.focusIndex]).hasClass('disabled')) self.focusIndex--;
								self.focusIndex = self.focusIndex < 0 ? 0 : self.focusIndex;
							} else if (key == 40) {
								self.focusIndex++
									if ($(self.$items[self.focusIndex]).hasClass('disabled')) self.focusIndex++;
								self.focusIndex = self.focusIndex > self.$items.length - 1 ? self.$items.length - 1 : self.focusIndex;
							};
							if (!self.down) {
								self.open();
							};
							self.$items.removeClass('focus').eq(self.focusIndex).addClass('focus');
							if (self.cutOff) {
								self.scrollToView();
							};
							self.query = '';
						};
						if (self.down) {
							if (key == 9 || key == 27) {
								self.close();
							} else if (key == 13) {
								e.preventDefault();
								self.select(self.focusIndex);
								self.close();
								return false;
							} else if (key == 8) {
								e.preventDefault();
								self.query = self.query.slice(0, -1);
								self.search();
								clearTimeout(self.resetQuery);
								return false;
							} else if (key != 38 && key != 40) {
								var letter = String.fromCharCode(key);
								self.query += letter;
								self.search();
								clearTimeout(self.resetQuery);
							};
						};
					};
				},
				'keyup.easyDropDown': function() {
					self.resetQuery = setTimeout(function() {
						self.query = '';
					}, 1200);
				}
			});
			self.$dropDown.on('scroll.easyDropDown', function(e) {
				if (self.$dropDown[0].scrollTop >= self.$dropDown[0].scrollHeight - self.maxHeight) {
					self.$container.addClass('bottom');
				} else {
					self.$container.removeClass('bottom');
				};
			});
			if (self.$form.length) {
				self.$form.on('reset.easyDropDown', function() {
					var active = self.hasLabel ? self.label : self.options[0].title;
					self.$active.text(active);
				});
			};
		},
		unbindHandlers: function() {
			var self = this;
			self.$container.add(self.$select).add(self.$items).add(self.$form).add(self.$dropDown).off('.easyDropDown');
			$('body').off('.' + self.id);
		},
		open: function() {
			var self = this,
				scrollTop = window.scrollY || document.documentElement.scrollTop,
				scrollLeft = window.scrollX || document.documentElement.scrollLeft,
				scrollOffset = self.notInViewport(scrollTop);
			self.closeAll();
			self.getMaxHeight();
			self.$select.focus();
			//window.scrollTo(scrollLeft, scrollTop+scrollOffset);
			self.$container.addClass('open');
			self.$scrollWrapper.css('height', self.maxHeight + 'px');
			self.down = true;
		},
		close: function() {
			var self = this;
			self.$container.removeClass('open');
			self.$scrollWrapper.css('height', '0px');
			self.focusIndex = self.selected.index;
			self.query = '';
			self.down = false;
		},
		closeAll: function() {
			var self = this,
				instances = Object.getPrototypeOf(self).instances;
			for (var key in instances) {
				var instance = instances[key];
				instance.close();
			};
		},
		select: function(index) {
			var self = this;
			if (typeof index === 'string') {
				index = self.$select.find('option[value=' + index + ']').index() - 1;
			};
			var option = self.options[index],
				selectIndex = self.hasLabel ? index + 1 : index;
			self.$items.removeClass('active').eq(index).addClass('active');
			self.$active.html(option.title + (option.color != '' ? ' <small>: '+ option.color +' </small>' : '') + '<span class="color style-' + option.color + '-bg"></span>');
			self.$select.find('option').removeAttr('selected').eq(selectIndex).prop('selected', true).parent().trigger('change');
			self.selected = {
				index: index,
				title: option.title
			};
			self.focusIndex = i;
			if (typeof self.onChange === 'function') {
				self.onChange.call(self.$select[0], {
					title: option.title,
					value: option.value
				});
			};
		},
		search: function() {
			var self = this,
				lock = function(i) {
					self.focusIndex = i;
					self.$items.removeClass('focus').eq(self.focusIndex).addClass('focus');
					self.scrollToView();
				},
				getTitle = function(i) {
					return self.options[i].title.toUpperCase();
				};
			for (i = 0; i < self.options.length; i++) {
				var title = getTitle(i);
				if (title.indexOf(self.query) == 0) {
					lock(i);
					return;
				};
			};
			for (i = 0; i < self.options.length; i++) {
				var title = getTitle(i);
				if (title.indexOf(self.query) > -1) {
					lock(i);
					break;
				};
			};
		},
		scrollToView: function() {
			var self = this;
			if (self.focusIndex >= self.cutOff) {
				var $focusItem = self.$items.eq(self.focusIndex),
					scroll = ($focusItem.outerHeight() * (self.focusIndex + 1)) - self.maxHeight;
				self.$dropDown.scrollTop(scroll);
			};
		},
		notInViewport: function(scrollTop) {
			var self = this,
				range = {
					min: scrollTop,
					max: scrollTop + (window.innerHeight || document.documentElement.clientHeight)
				},
				menuBottom = self.$dropDown.offset().top + self.maxHeight;
			if (menuBottom >= range.min && menuBottom <= range.max) {
				return 0;
			} else {
				return (menuBottom - range.max) + 5;
			};
		},
		destroy: function() {
			var self = this;
			self.unbindHandlers();
			self.$select.unwrap().siblings().remove();
			self.$select.unwrap();
			delete Object.getPrototypeOf(self).instances[self.$select[0].id];
		},
		disable: function() {
			var self = this;
			self.disabled = true;
			self.$container.addClass('disabled');
			self.$select.attr('disabled', true);
			if (!self.down) self.close();
		},
		enable: function() {
			var self = this;
			self.disabled = false;
			self.$container.removeClass('disabled');
			self.$select.attr('disabled', false);
		}
	};
	var instantiate = function(domNode, settings) {
			domNode.id = !domNode.id ? 'EasyDropDown' + rand() : domNode.id;
			var instance = new EasyDropDown();
			if (!instance.instances[domNode.id]) {
				instance.instances[domNode.id] = instance;
				instance.init(domNode, settings);
			};
		},
		rand = function() {
			return ('00000' + (Math.random() * 16777216 << 0).toString(16)).substr(-6).toUpperCase();
		};
	$.fn.easyDropDown = function() {
		var args = arguments,
			dataReturn = [],
			eachReturn;
		eachReturn = this.each(function() {
			if (args && typeof args[0] === 'string') {
				var data = EasyDropDown.prototype.instances[this.id][args[0]](args[1], args[2]);
				if (data) dataReturn.push(data);
			} else {
				instantiate(this, args[0]);
			};
		});
		if (dataReturn.length) {
			return dataReturn.length > 1 ? dataReturn : dataReturn[0];
		} else {
			return eachReturn;
		};
	};
	$(function() {
		if (typeof Object.getPrototypeOf !== 'function') {
			if (typeof 'test'.__proto__ === 'object') {
				Object.getPrototypeOf = function(object) {
					return object.__proto__;
				};
			} else {
				Object.getPrototypeOf = function(object) {
					return object.constructor.prototype;
				};
			};
		};
		$('select.dropdown').each(function() {
			var json = $(this).attr('data-settings');
			settings = json ? $.parseJSON(json) : {};
			instantiate(this, settings);
		});
	});
	$.fn.uncode_init_upload = function() {
		// Open up the media manager to handle editing image metadata.
		$(document).on('click', '.ot_upload_media', function(e) {
			e.preventDefault();
			var uncode_frames = {}, // Store our workflows in an object
				frame_id = 'uncode-editor', // Unique ID for each workflow
				default_view = wp.media.view.AttachmentsBrowser, // Store the default view to restore it later
				media = wp.media,
				field_id = $(this).parent('.option-tree-ui-upload-parent').find('input').attr('id'),
				post_id = $(this).attr('rel'),
				save_attachment_id = $('#' + field_id).val(),
				btnContent = '';
			// If the media frame already exists, reopen it.
			if (uncode_frames[frame_id]) {
				uncode_frames[frame_id].open();
				return;
			}
			media.view.uploadMediaView = media.View.extend({
				tagName: 'div',
				className: 'uploader-uncode-media',
				template: media.template('uploader-uncode-media'),
				events: {
					'click .close': 'hide',
					'paste #mle-code': 'entercode',
					'input #mle-code': 'entercode'
				},
				oembed_callback: function($mime, $width, $height) {
					var $this = (window['workflow'] != undefined) ? workflow : wp.media.frame,
						$el = $this.$el;
					$button = $el.find('.media-button'),
						$spinner = $el.find('.spinner');
					if ($mime != '') {
						$el.find('#mle-mime').val($mime);
						$el.find('#mle-width').val($width);
						$el.find('#mle-height').val($height);
						$button.removeAttr('disabled');
						$spinner.removeClass('visible');
					}
				},
				entercode: function(event) {
					var _this = this;
					var $el = $(this.$el),
						$codeInput = $el.find('#mle-code'),
						$codeDiv = $el.find('.oembed_code'),
						$oEmbedRender = $el.find('.oembed'),
						$spinner = $el.find('.spinner'),
						$code;
					setTimeout(function() {
						$code = $codeInput.val();
						if ($codeDiv.length == 0) $oEmbedRender.after('<div class="oembed_code">' + $code + '</div>');
						else $codeDiv.html($code);
						$oEmbedRender.get_oembed(_this.oembed_callback, true);
						$spinner.addClass('visible');
					}, 100);
				},
				recordmedia: function() {
					var $this = this,
						$el = $(this.$el),
						$button = uncode_frames[frame_id].$el.find('.media-button-select');
					if (uncode_frames[frame_id].content.get().el.className == 'uploader-uncode-media') {
						$button.attr('disabled', 'disabled');
						$.ajax({
							type: 'POST',
							dataType: "json",
							url: ajaxurl + '?action=recordMedia',
							data: $el.find('input[name],select[name],textarea[name]').serialize(),
							success: function(data) {
								if (!isNaN(data.id) && data != undefined) {
									btnContent += '<div class="option-tree-ui-image-wrap"><div class="oembed"><span class="spinner" style="display: block; float: left;"></span></div><div class="oembed_code" style="display: none;">' + data.url + '</div></div>';
									$('#' + field_id).val(data.id);
									$('#' + field_id + '_media').remove();
									$('#' + field_id).parent().parent('div').append('<div class="option-tree-ui-media-wrap" id="' + field_id + '_media" />');
									$('#' + field_id + '_media').append(btnContent).slideDown();
									$('#' + field_id + '_media .oembed').get_oembed(null, true);
									$('#' + field_id + '_media .spinner').removeClass('visible');
									$button.removeAttr('disabled');
									uncode_frames[frame_id].off('select');
									uncode_frames[frame_id].close();
								}
							}
						});
					} else {
						$this.select();
					}
				},
				ready: function() {
					var $this = this,
						$button = uncode_frames[frame_id].$el.find('.media-button-select');
					$button.off('click').on('click', function() {
						$this.recordmedia();
					});
				},
				select:function () {
					var selection = media.frame.toolbar.get('selection').selection.single();
					media.frame.close();
				}
			});
			// Create the media frame.
			uncode_frames[frame_id] = media({
				title: $(this).attr('title'),
				button: {
					text: option_tree.upload_text
				},
				multiple: false
			});
			uncode_frames[frame_id].on('router:render:browse', function(routerView) {
				routerView.set({
					upload: {
						text: 'Upload Files',
						priority: 20
					},
					browse: {
						text: 'Media Library',
						priority: 40
					},
					uncode: {
						text: 'Upload oEmbed',
						priority: 30
					}
				});
			});
			uncode_frames[frame_id].on('content:render:uncode', function() {
				uncode_frames[frame_id].content.set(new media.view.uploadMediaView({
					controller: this
				}));
			});
			uncode_frames[frame_id].on('close', function() {
				wp.media.view.AttachmentsBrowser = default_view;
			});
			uncode_frames[frame_id].on('select', function() {
				var attachment = uncode_frames[frame_id].state().get('selection').first(),
					href = attachment.attributes.url,
					attachment_id = attachment.attributes.id,
					mime = attachment.attributes.mime,
					regex = /^image\/(?:jpe?g|png|url|gif|x-icon)$/i;
				if (mime == 'oembed/svg') {
					btnContent += '<div class="option-tree-ui-image-wrap">' + attachment.attributes.description + '</div>';
				} else if (mime.match(regex)) {
					btnContent += '<div class="option-tree-ui-image-wrap"><img src="' + href + '" alt="" /></div>';
				} else {
					btnContent += '<div class="option-tree-ui-image-wrap"><div class="oembed"><span class="spinner" style="display: block; float: left;"></span></div><div class="oembed_code" style="display: none;">' + href + '</div></div>';
				}
				btnContent += '<a href="#" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="' + option_tree.remove_media_text + '"><span class="icon fa fa-minus2"></span>' + option_tree.remove_media_text + '</a>';
				$('#' + field_id).val(attachment_id);
				$('#' + field_id + '_media').remove();
				$('#' + field_id).parent().parent('div').append('<div class="option-tree-ui-media-wrap" id="' + field_id + '_media" />');
				$('#' + field_id + '_media').append(btnContent).slideDown();
				$('#' + field_id + '_media .oembed').get_oembed();
				uncode_frames[frame_id].off('select');
				uncode_frames[frame_id].close();
			});
			uncode_frames[frame_id].on('open',function() {
				var selection = uncode_frames[frame_id].state().get('selection'),
			  attachment = media.attachment(save_attachment_id);
			  attachment.fetch();
			  selection.set( attachment );
			});
			// Finally, open the modal.
			uncode_frames[frame_id].open();
		});
		$(document).on('click', '.option-tree-ui-remove-media', function(e) {
			e.preventDefault();
			var cont = $(e.currentTarget).closest('td'),
				$input = cont.find('.option-tree-ui-upload-parent input'),
				$placeholder = cont.find('.option-tree-ui-media-wrap');
			$input.attr('value','');
			$placeholder.remove();
		});
	};


	var media = wp.media;

	/**
	 * Extended Filters dropdown with taxonomy term selection values
	 */
	if ( media && mediaTaxonomies != null ) {

		$.each(mediaTaxonomies,function(key,label){

			media.view.AttachmentFilters[key] = media.view.AttachmentFilters.extend({
				className: 'attachment-filters',

				createFilters: function() {
					var filters = {};

					_.each( mediaTerms[key] || {}, function( term ) {

						var query = {};

						query[key] = {
							taxonomy: key,
							term_id: parseInt( term.id, 10 ),
							term_slug: term.slug
						};

						filters[ term.slug ] = {
							text: term.label,
							props: query
						};
					});

					this.filters = filters;
				}


			});

			/**
			 * Replace the media-toolbar with our own
			 */
			var myDrop = media.view.AttachmentsBrowser;

			media.view.AttachmentsBrowser = media.view.AttachmentsBrowser.extend({
				createToolbar: function() {

					media.model.Query.defaultArgs.filterSource = 'filter-media-taxonomies';

					myDrop.prototype.createToolbar.apply(this,arguments);

					this.toolbar.set( key, new media.view.AttachmentFilters[key]({
						controller: this.controller,
						model:      this.collection.props,
						priority:   -80
						}).render()
					);
				}
			});

		});
	}

	/* Save taxonomy */
	$('html').delegate( '.media-terms input', 'change', function(){

		var obj = $(this),
			container = obj.parents('.media-terms'),
			row = container.parent(),
			data = {
				action: 'save-media-terms',
				term_ids: [],
				attachment_id: container.data('id'),
				taxonomy: container.data('taxonomy')
			};

		container.find('input:checked').each(function(){
			data.term_ids.push( $(this).val() );
		});

		row.addClass('media-save-terms');
		container.find('input').prop('disabled', 'disabled');

		$.post( ajaxurl, data, function( response ){
			row.removeClass('media-save-terms');
			container.find('input').removeProp('disabled');
		});

	});

	// Add new taxonomy
	$('html').delegate('.toggle-add-media-term', 'click', function(e){
		e.preventDefault();
		$(this).parent().find('.add-new-term').toggle();
	});

	// Save new taxnomy
	$('html').delegate('.save-media-category', 'click', function(e){

		var obj = $(this),
			termField = obj.parent().find('input'),
			termParent = obj.parent().find('select'),
			data = {
				action: 'add-media-term',
				attachment_id: obj.data('id'),
				taxonomy: obj.data('taxonomy'),
				parent: termParent.val(),
				term: termField.val()
			};

		// No val
		if ( '' == data.term ) {
			termField.focus();
			return;
		}

		$.post(ajaxurl, data, function(response){

			obj.parents('.field').find('.media-terms ul:first').html( response.checkboxes );
			obj.parents('.field').find('select').replaceWith( response.selectbox );

			termField.val('');

		}, 'json' );

	});
})(jQuery);


(function($){

"use strict";

var UNCODE = window.UNCODE || {};

UNCODE.dismissAdminNotices = function(){

	$('#uncode_vc_admin_notice').on( 'click', '.notice-dismiss', function() {

		$.ajax({
			url: ajaxurl,
			data: {
				action: 'uncode_vc_admin_notice_dismiss'
			}
		});

	});

};

UNCODE.managePageOptions = function(){
	$( ".ot-numeric-slider-wrap" ).has('#_uncode_product_media_size').each(function(){
		var $input = $('.ot-numeric-slider-helper-input', this),
			$slide = $( ".ot-numeric-slider", this);

		if ( $input.val() == 0 )
			$input.val('Inherit');

		$slide.on( "slide slidechange", function( event, ui ) {
			if (ui.value == 0)
				$input.val('Inherit');
		} );
	});
};

$(function(){
	UNCODE.dismissAdminNotices();
	UNCODE.managePageOptions();
});

})(jQuery);