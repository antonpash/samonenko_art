(function() {
  (function($) {

    'use strict';

	if(!$('#main-wrap').find('.main-footer').length){
		$('#main-wrap').append($('.main-footer'));
	}

    var maskED, TeslaThemes, template, teslaRouter, themeBg, themeContactForm, themeCovers, themeForms, themeIcon, themeInstagram, themeIsotope, themeLogo, themeMap, themeMenu, themeRouter, themeStickySidebar, themeTabs, themeZoom;
    TeslaThemes = (function() {
      function TeslaThemes() {
        var teslaBg, teslaLogo, teslaZoom, videoIframe;
        if (typeof ($('body').data('animated-bg')) !== 'undefined') {
          teslaBg = new themeBg($('body').data('animated-bg'));
        }
        if ($('.identity img').length) {
          teslaLogo = new themeLogo($('.identity img'), $('.identity').data('logo-color'));
        }
        this.teslaMenu = new themeMenu($('#menu-toggle'));
        if ($('[data-zoom]').length) {
          teslaZoom = new themeZoom($('[data-zoom]'));
        }
        this.teslaIcon = new themeIcon({
          iconSrc: themeOptions.dirUri + '/images/icons.svg'
        });
        this.ajaxEvents(false);
        videoIframe = jQuery('iframe[src^="//player.vimeo.com"], iframe[src*="//www.youtube.com/embed"], iframe[src*="//w.soundcloud.com/player"]');
        if (videoIframe.length) {
          $('body').fitVids({
            customSelector: 'iframe[src^="//player.vimeo.com"], iframe[src*="//www.youtube.com/embed"], iframe[src*="//w.soundcloud.com/player"]'
          });
        }
        if ($('#contact-form').length) {
          new themeContactForm($('#contact-form'));
        }
      }

      TeslaThemes.prototype.ajaxEvents = function(onfly) {
        var lat, lng, pin, teslaForms, teslaTabs, teslaZoom, videoIframe, zoom;
        if (onfly === true) {
          this.teslaMenu._hideAjax();
          this.teslaIcon.reload();
        }
        if (document.getElementById('map-canvas') && $('.box-map').length) {
          lat = $('.box-map').data('latitude' || 44.2661906);
          lng = $('.box-map').data('longitude' || -68.5691898);
          zoom = $('.box-map').data('zoommap' || 16);
          pin = $('.box-map').data('pin');
          $(document).ready(function() {
            return google.maps.event.addDomListener(window, 'load', function() {
              return new themeMap({
                selector: document.getElementById('map-canvas'),
                title: 'test',
                zoom: zoom,
                icon: pin,
                coord: {
                  lat: lat,
                  lng: lng
                }
              });
            });
          });
        }
        teslaForms = new themeForms();
        if ($('[data-instagram]').length) {
          $('[data-instagram]').each(function(index) {
            var iContainer, teslaInstagram;
            iContainer = $('[data-instagram]').eq(index);
            return teslaInstagram = new themeInstagram(iContainer, {
              access_token: '',
              client_id: '632fb01c8c0d43d7b63da809d0b6a662',
              count: iContainer.data('instagram-count') || 6
            });
          });
        }
        if ($('[data-cover-box]').length) {
          $('[data-cover-box]').imagesLoaded(function() {
            var teslaCovers;
            return teslaCovers = new themeCovers($('[data-cover-box]'));
          });
        }
        if ($('[data-sticky-sidebar]').length) {
          $('.main-content').imagesLoaded(function() {
            var teslaStickySidebar;
            return teslaStickySidebar = new themeStickySidebar($('[data-sticky-sidebar]'));
          });
        }
        if ($('.vc_tta-tabs-container').length) {
          $('.vc_tta-tabs-container').css({
            'height': 100
          });

          new themeStickySidebar($('.vc_tta-tabs-container'));

          var height = 0;

          $('.vc_tta-tabs-container .sidebar-inner').children().each(function () {
            height += $(this).height();
            console.log($(this).height());
          });

          $('.vc_tta-tabs-container').css({
            'height': height
          });
        }
        if ($('.tabs-box').length) {
          teslaTabs = new themeTabs();
        }
        if ($('[data-grid]').length) {
          $('[data-grid]').imagesLoaded(function() {
            var teslaIsotope;
            return teslaIsotope = new themeIsotope({
              selector: $('[data-grid]'),
              item: $('[data-grid]').data('grid')
            });
          });
        }
        if ($('[data-zoom]').length && !$('.zoom-container').length) {
          teslaZoom = new themeZoom($('[data-zoom]'));
          this.teslaIcon.reload();
        }
        videoIframe = jQuery('iframe[src^="//player.vimeo.com"], iframe[src*="//www.youtube.com/embed"], iframe[src*="//w.soundcloud.com/player"]');
        if (videoIframe.length) {
          $('body').fitVids({
            customSelector: 'iframe[src^="//player.vimeo.com"], iframe[src*="//www.youtube.com/embed"], iframe[src*="//w.soundcloud.com/player"]'
          });
        }
        if ($('.js-flickity').length) {
          return $(document).ready(function() {
            return $('.js-flickity').imagesLoaded(function() {
              $('.js-flickity').flickity({
                pageDots: false,
                wrapAround: true,
                resize: true
              });
              return $('.gallery-nav').flickity({
                contain: true,
                asNavFor: ".js-flickity",
                pageDots: false,
                prevNextButtons: false,
                resize: true
              });
            });
          });
        }
      };

      return TeslaThemes;

    })();
    themeBg = (function() {
      function themeBg(colorRange) {
        var canvas, clickCircle, gradient;
        if (!$('#click-circle').length) {
          this.paper = Snap(window.innerWidth, window.innerHeight);
          gradient = this.paper.gradient("r(0.5, 0.5, 0.5)" + colorRange);
          gradient.attr({
            id: 'background-gradient'
          });
          canvas = this.paper.rect(0, 0, '100%', '100%');
          canvas.attr({
            fill: gradient,
            id: 'canvas-gredient'
          });
          clickCircle = this.paper.circle(0, 0, 0);
          clickCircle.attr({
            id: 'click-circle',
            fill: 'rgba(255,255,255, 0.4)',
            opacity: 1
          });
          this._bindEvents();
        }
      }

      themeBg.prototype._bindEvents = function() {
        window.addEventListener('resize', (function(_this) {
          return function() {
            return _this.paper.attr({
              width: window.innerWidth,
              height: window.innerHeight
            });
          };
        })(this));
        window.addEventListener('mousemove', (function(_this) {
          return function(event) {
            var clientX, clientY;
            clientX = (Math.round(event.clientX / window.innerWidth * 100)) / 100;
            clientY = (Math.round(event.clientY / window.innerHeight * 100)) / 100;
            return _this.paper.select('#background-gradient').attr({
              cx: Math.max(0.4, Math.min(clientX, 0.6)),
              cy: Math.max(0.4, Math.min(clientY, 0.6))
            });
          };
        })(this));
        return $('body').on('click', 'a, button, .contact-form input, .contact-form textarea', (function(_this) {
          return function(event) {
            var clickCircle, clientX, clientY, tl;
            clientX = event.clientX;
            clientY = event.clientY;
            clickCircle = _this.paper.select('#click-circle');
            clickCircle.attr({
              cx: clientX,
              cy: clientY
            });
            tl = new TimelineLite();
            tl.to(clickCircle.node, 0.5, {
              attr: {
                r: window.innerHeight + window.innerWidth
              },
              opacity: 0,
              ease: Linear.easeOut
            });
            return tl.to(clickCircle.node, 0, {
              attr: {
                r: 0
              },
              opacity: 1
            });
          };
        })(this));
      };

      return themeBg;

    })();
    themeLogo = (function() {
      function themeLogo(logo, color) {
        var isSVG, logoPath;
        this.paper = '';
        logoPath = logo.attr('src');
        isSVG = logoPath.slice(-3);
        if (isSVG === 'svg') {
          Snap.load(logoPath, (function(_this) {
            return function(data) {
              logo.remove();
              _this.paper = Snap('.identity');
              _this.paper.append(data);
              _this.els = _this.paper.selectAll('g:last-child polygon, g:last-child  path, g:last-child g');
              return _this._bindEvents(color);
            };
          })(this));
        }
      }

      themeLogo.prototype._bindEvents = function(color) {
        var animateLogo;
        animateLogo = function(el, index, color) {
          var tl;
          tl = new TimelineLite();
          tl.to(el.node, 0.3, {
            fill: color,
            scale: 0.8,
            transformOrigin: '50% 50%',
            ease: Circ.easeOut
          });
          return tl.to(el.node, 0.3, {
            scale: 1
          });
        };
        $('.identity').on('mouseenter', (function(_this) {
          return function() {
            return _this.els.forEach(function(el, index) {
              if (index % 2) {
                return animateLogo(el, index, color);
              }
            });
          };
        })(this));
        return $('.identity').on('mouseleave', (function(_this) {
          return function() {
            return _this.els.forEach(function(el, index) {
              if (index % 2) {
                return el.animate({
                  fill: '#000000'
                }, 100);
              } else {
                return animateLogo(el, index, '#000000');
              }
            });
          };
        })(this));
      };

      return themeLogo;

    })();
    themeMenu = (function() {
      function themeMenu(logo1) {
        this.logo = logo1;
        this.logoIcon = this.logo.find('svg');
        this.logoPath = this.logoIcon.find('path');
        this.menu = $('.main-nav');
        this._treeMenu();
        this._bindEvents();
      }

      themeMenu.prototype._showMenu = function(logoIcon) {
        var tl;
        $('body').addClass('menu-active');
        tl = new TimelineLite();
        tl.to(logoIcon, 0.2, {
          scale: 0.6,
          opacity: 0,
          transformOrigin: '50% 50%',
          ease: Linear.ease
        });
        tl.to(logoIcon, 0.1, {
          attr: {
            d: 'M298 311l-119 -119l119 -119l-30 -30l-119 119l-119 -119l-30 30l119 119l-119 119l30 30l119 -119l119 119z'
          }
        });
        tl.to('.header-bar, .main-content', 0.3, {
          x: '100px',
          opacity: 0.5,
          ease: Linear.easeOut
        }, '-=0.3');
        tl.to('.menu-box', 0.2, {
          x: '0%',
          opacity: 1,
          ease: Linear.easeOut
        }, '-=0.2');
        tl.staggerFrom('.main-nav > ul > li', 0.2, {
          opacity: 0,
          x: -50
        }, 0.1, '-=0.2');
        tl.to(logoIcon, 0.3, {
          attr: {
            fill: '#ffffff'
          },
          scale: 0.1
        }, '-=0.4');
        tl.to(logoIcon, 0.3, {
          scale: 1,
          rotation: -180,
          opacity: 1,
          ease: Elastic.ease
        });
        return tl.from('.main-nav + h5, .menu-info, .social-links', 0.5, {
          opacity: 0
        }, '-=0.1');
      };

      themeMenu.prototype._hideMenu = function(logoIcon) {
        var tl;
        $('body').removeClass('menu-active');
        tl = new TimelineLite();
        tl.to(logoIcon, 0.2, {
          scale: 0.6,
          opacity: 0,
          transformOrigin: '50% 50%',
          ease: Linear.ease
        });
        tl.to(logoIcon, 0.1, {
          attr: {
            d: 'M0 175v32h320v-32h-320zM0 271v32h320v-32h-320zM0 79v32h320v-32h-320z'
          }
        });
        tl.to('.menu-box', 0.2, {
          x: '-100%',
          opacity: 0,
          ease: Linear.easeOut
        }, '-=0.2');
        tl.to('.header-bar, .main-content', 0.3, {
          x: 0,
          opacity: 1,
          ease: Linear.easeOut,
          clearProps: "all"
        }, '-=0.2');
        tl.to(logoIcon, 0.3, {
          attr: {
            fill: '#000000'
          },
          scale: 0.1,
          rotation: 0
        });
        return tl.to(logoIcon, 0.3, {
          scale: 1,
          opacity: 1,
          ease: Elastic.ease
        });
      };

      themeMenu.prototype._hideAjax = function() {
        return this._hideMenu(this.logoPath);
      };

      themeMenu.prototype._treeMenu = function() {
        var subMenu, subMenuLink;
        subMenu = this.menu.find('ul > li ul');
        subMenuLink = subMenu.prev();
        subMenuLink.addClass('parent-link');
        subMenuLink.append(' <i data-icon="ios-arrow-down" data-icon-size="18" data-icon-color="#5b5b5b"></i>');
        return subMenu.prepend('<li class="menu-back"><i data-icon="arrow-left-c" data-icon-size="26" data-icon-color="#5b5b5b"></i></li>');
      };

      themeMenu.prototype._bindEvents = function() {
        var back, i, tl;
        tl = {};
        back = $('.menu-back');
        i = 0;
        $('.main-nav > ul > li').on('click', function() {
          $('.main-nav > ul > li').removeClass('current_page_item');
          return $(this).addClass('current_page_item');
        });
        this.logo.on('click', (function(_this) {
          return function(event) {
            event.preventDefault();
            if ($('body').hasClass('menu-active')) {
              return _this._hideMenu(_this.logoPath);
            } else {
              return _this._showMenu(_this.logoPath);
            }
          };
        })(this));
        $(window).on('click', (function(_this) {
          return function(event) {
            if ($(event.target).is(':not(.menu-box):not(.menu-box *):not(#menu-toggle *)') && $('body').hasClass('menu-active')) {
              return _this._hideMenu(_this.logoPath);
            }
          };
        })(this));
        $('.parent-link').on('click', (function(_this) {
          return function(event) {
            var ul;
            event.preventDefault();
            tl[i] = new TimelineLite();
            ul = $(event.currentTarget).parent();
            tl[i].staggerTo(ul.parent().children('li'), 0.1, {
              opacity: 0,
              x: '-100%'
            }, 0.01, '-=0.2');
            tl[i].set(ul.parent().children('li'), {
              display: 'none'
            });
            tl[i].set(ul.children('a'), {
              display: 'none'
            });
            tl[i].set(ul, {
              display: 'block',
              x: '0%',
              opacity: 1
            });
            tl[i].set(ul.children(' ul'), {
              display: 'block'
            });
            tl[i].staggerTo(ul.children('ul').children('li'), 0.2, {
              x: '0%',
              opacity: 1
            }, 0.1);
            return i++;
          };
        })(this));
        return back.on('click', function(e) {
          var index;
          e.preventDefault();
          index = $(this).parent().find('.menu-back').index(this);
          tl[index].timeScale(3);
          tl[index].reverse();
          delete tl[index];
          return i--;
        });
      };

      return themeMenu;

    })();
    themeMap = (function() {
      function themeMap(options) {
        var map, marker, myOptions;
        if (typeof google !== "undefined" && google !== null) {
          myOptions = {
            zoom: options.zoom,
            center: options.coord,
            disableDefaultUI: true,
            panControl: true,
            zoomControl: true,
            scrollwheel: false,
            zoomControlOptions: {
              style: google.maps.ZoomControlStyle.DEFAULT
            },
            mapTypeControl: true,
            mapTypeControlOptions: {
              style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
            },
            streetViewControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
              {
                "featureType": "landscape",
                "stylers": [
                  {
                    "saturation": -100
                  }, {
                    "lightness": 65
                  }, {
                    "visibility": "on"
                  }
                ]
              }, {
                "featureType": "poi",
                "stylers": [
                  {
                    "saturation": -100
                  }, {
                    "lightness": 51
                  }, {
                    "visibility": "simplified"
                  }
                ]
              }, {
                "featureType": "road.highway",
                "stylers": [
                  {
                    "saturation": -100
                  }, {
                    "visibility": "simplified"
                  }
                ]
              }, {
                "featureType": "road.arterial",
                "stylers": [
                  {
                    "saturation": -100
                  }, {
                    "lightness": 30
                  }, {
                    "visibility": "on"
                  }
                ]
              }, {
                "featureType": "road.local",
                "stylers": [
                  {
                    "saturation": -100
                  }, {
                    "lightness": 40
                  }, {
                    "visibility": "on"
                  }
                ]
              }, {
                "featureType": "transit",
                "stylers": [
                  {
                    "saturation": -100
                  }, {
                    "visibility": "simplified"
                  }
                ]
              }, {
                "featureType": "administrative.province",
                "stylers": [
                  {
                    "visibility": "off"
                  }
                ]
              }, {
                "featureType": "water",
                "elementType": "labels",
                "stylers": [
                  {
                    "visibility": "on"
                  }, {
                    "lightness": -25
                  }, {
                    "saturation": -100
                  }
                ]
              }, {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                  {
                    "hue": "#ffff00"
                  }, {
                    "lightness": -25
                  }, {
                    "saturation": -97
                  }
                ]
              }
            ]
          };
          map = new google.maps.Map(options.selector, myOptions);
          map.panBy(200, 0);
          marker = new google.maps.Marker({
            position: options.coord,
            map: map,
            title: options.title,
            icon: options.icon,
            animation: google.maps.Animation.DROP
          });
        }
      }

      return themeMap;

    })();
    themeIcon = (function() {
      function themeIcon(icon) {
        var _this, icons;
        icons = $('[data-icon]');
        _this = this;
        this.dataIcons = '';
        if (icons.length) {
          if (this.dataIcons.length) {
            this.addIcons(this.dataIcons);
          } else {
            Snap.load(icon.iconSrc, function(data) {
              _this.dataIcons = data;
              return _this.addIcons(data);
            });
          }
        }
      }

      themeIcon.prototype.addIcons = function(data) {
        var iconObj, icons;
        iconObj = {};
        icons = $('[data-icon]');
        return icons.each(function(i, el) {
          var iconClass, iconColor, iconSize, s;
          iconClass = icons.eq(i).data('icon');
          if (iconClass) {
            if (!iconObj[iconClass]) {
              iconObj[iconClass] = data.selectAll('.' + iconClass + ' path');
            }
            if (!icons.eq(i).find('svg').length) {
              iconSize = icons.eq(i).data('icon-size') || 36;
              iconColor = icons.eq(i).data('icon-color') || '#000000';
              s = Snap(iconSize, iconSize);
              icons.eq(i).append(s.node);
              s.attr({
                viewBox: '0 0 1024 1024',
                preserveAspectRatio: 'xMidYMin meet'
              });
              return s.group(iconObj[iconClass].clone()).attr({
                fill: iconColor
              });
            }
          }
        });
      };

      themeIcon.prototype.reload = function() {
        return this.addIcons(this.dataIcons);
      };

      return themeIcon;

    })();
    themeForms = (function() {
      function themeForms() {
        if ($('form').length) {
          this.formLabel();
        }
      }

      themeForms.prototype.formLabel = function() {
        var _hasContent, formInputs;
        formInputs = $('form').find('input[type=text], input[type=email],input[type=search],input[type=password], textarea');
        _hasContent = function(input, value) {
          if (value) {
            return input.addClass('has-content');
			console.log('sdfsdf');
          } else {
            return input.removeClass('has-content');
          }
        };
        formInputs.each(function() {
          var input, inputValue;
          input = $(this);
          inputValue = input.val();
		  if((input.attr("id") && (input.attr("id").indexOf('billing') + 1)) || (input.parent()[0].className.indexOf('comment-form') + 1))
			return;

			return _hasContent(input, inputValue);
        });
        return formInputs.blur(function() {
          var input, inputValue;
          input = $(this);
          inputValue = input.val();
		  if((input.attr("id") && (input.attr("id").indexOf('billing') + 1)) || (input.parent()[0].className.indexOf('comment-form') + 1))
			return;

			return _hasContent(input, inputValue);
        });
      };

      return themeForms;

    })();
    themeInstagram = (function() {
      function themeInstagram(container, data) {
        var pattern, renderTemplate, storageObj, storageTime, url;
        url = 'https://api.instagram.com/v1';
        pattern = function(obj) {
          var item, k, len, template;
          if (obj.length) {
            template = '';
            for (k = 0, len = obj.length; k < len; k++) {
              item = obj[k];
              template += "<li class='col-sm-4 col-xs-6 text-center'><a href='" + item.link + "' title='" + item.title + "' target='_blank'><img src='" + item.image + "' alt='" + item.title + "' width='150' height='150'></a></li>";
            }
            return container.append(template);
          }
        };
        if (container.data('instagram-username')) {
          url += "/users/search?q=" + (container.data('instagram-username')) + "&client_id=" + data.clientID + "&callback=?";
          renderTemplate = this._template;
          storageTime = new Date().getTime();
          if (localStorage.getItem('instagramFeed')) {
            storageObj = JSON.parse(localStorage.getItem('instagramFeed'));
            storageTime = new Date().getTime() - storageObj.timestamp;
            if (storageTime < 99999) {
              pattern(storageObj.data);
            }
          }
          if (storageTime > 99999) {
            localStorage.removeItem('instagramFeed');
            $.ajax({
              dataType: "jsonp",
              url: url,
              data: data,
              success: function(response) {
                var urlUser;
                if (response.data.length) {
                  urlUser = "https://api.instagram.com/v1/users/" + response.data[0].id + "/media/recent/?client_id=" + data.clientID + "&count=" + data.count + "&callback=?";
                  return $.ajax({
                    dataType: "jsonp",
                    url: urlUser,
                    data: data,
                    success: function(response) {
                      var instagramFeed;
                      if (response.data.length) {
                        instagramFeed = {};
                        instagramFeed.data = renderTemplate(response);
                        instagramFeed.timestamp = new Date().getTime();
                        localStorage.setItem('instagramFeed', JSON.stringify(instagramFeed));
                        return pattern(instagramFeed.data);
                      }
                    }
                  });
                }
              }
            });
          }
        }
      }

      themeInstagram.prototype._template = function(obj) {
        var item, k, len, ref, results;
        if (obj.data) {
          ref = obj.data;
          results = [];
          for (k = 0, len = ref.length; k < len; k++) {
            item = ref[k];
            results.push({
              title: item.user.username,
              link: item.link,
              image: item.images.thumbnail.url
            });
          }
          return results;
        }
      };

      return themeInstagram;

    })();
    themeCovers = (function() {
      function themeCovers(selector) {
        var boxBg, coverHeigth, coverWidth, tl;
        boxBg = selector.data('cover-box');
        coverWidth = window.innerWidth;
        if ($('article.sticky').length && !$('article.sticky .post-thumbnail').length) {
          coverHeigth = $('article.sticky').height() + parseInt(selector.css('padding-top'));
        } else {
          coverHeigth = $('.post-thumbnail').height() + parseInt(selector.css('padding-top'));
        }
        this.s = Snap(coverWidth, coverHeigth);
        selector.append(this.s.node);
        this.s.attr({
          viewBox: "0 0 " + coverWidth + " " + coverHeigth,
          preserveAspectRatio: 'xMidYMin meet'
        });
        this.pattern = this.s.image(boxBg, 0, 0, coverWidth, coverHeigth).attr({
          preserveAspectRatio: 'xMinYMin slice'
        }).pattern(0, 0, coverWidth, coverHeigth);
        this.coverBox = this.s.rect(0, 0, coverWidth, coverHeigth).attr({
          fill: this.pattern,
          opacity: 0
        });
        tl = new TimelineLite();
        tl.to(this.coverBox.node, 0.5, {
          opacity: 1
        });
        this._bindEvents(this.coverBox, selector);
      }

      themeCovers.prototype.createImg = function(width, height) {
        this.s.attr({
          viewBox: "0 0 " + width + " " + height,
          width: width,
          height: height
        });
        this.coverBox.attr({
          width: width,
          height: height
        });
        this.pattern.attr({
          width: width,
          height: height,
          viewBox: "0 0 " + width + " " + height
        });
        return this.pattern.select('image').attr({
          width: width,
          height: height
        });
      };

      themeCovers.prototype._bindEvents = function(el, selector) {
        var _that, tl;
        tl = new TimelineLite();
        _that = this;
        tl.to(el.node, 1, {
          opacity: 0,
          y: -100,
          scale: 0.9,
          transformOrigin: '50%',
          ease: Linear.ease
        });
        tl.pause();
        window.addEventListener('scroll', function() {
          var position, progress;
          position = el.node.getBoundingClientRect();
          progress = (-position.top / position.height) >= 0 ? -position.top / position.height : 0;
          if (progress <= 1) {
            return tl.progress(progress).pause();
          }
        });
        return window.addEventListener('resize', function() {
          var coverHeigth;
          if ($('article.sticky').length && !$('article.sticky .post-thumbnail').length) {
            coverHeigth = $('article.sticky').height() + parseInt(selector.css('padding-top'));
          } else {
            coverHeigth = $('.post-thumbnail').height() + parseInt(selector.css('padding-top'));
          }
          return _that.createImg(window.innerWidth, coverHeigth);
        });
      };

      return themeCovers;

    })();
    themeStickySidebar = (function() {
      function themeStickySidebar(sidebar) {
        var i, j, lastScroll, scrollDiff, sidebarHeight, sidebarWrap, tl, totalHeight;
        sidebar = $(sidebar.selector);
        if (sidebar.parent().parent().height() > sidebar.height()) {
          totalHeight = 0;
          sidebarHeight = '';
          scrollDiff = '';
          i = 0;
          j = 0;
          lastScroll = 0;
          tl = new TimelineLite();
          sidebar.children().wrapAll('<div class="sidebar-inner" />');
          sidebarWrap = $('.sidebar-inner');
          tl.set(sidebarWrap, {
            width: sidebar.width()
          });
          $(window).on('scroll load resize', function() {
            var howScroll, initScroll, item, scrolled;
            if ($(window).width() > 1200) {
              tl.set(sidebarWrap, {
                width: sidebar.width()
              });
              item = sidebar[0].getBoundingClientRect();
              if (sidebarWrap.height() > $(window).height()) {
                totalHeight = sidebar.parent().parent().height();
                sidebarHeight = sidebarWrap.height();
                scrollDiff = totalHeight - sidebarHeight;
                initScroll = $(this).scrollTop();
                howScroll = ((-item.top - sidebarHeight) + $(window).height()) / scrollDiff;
                howScroll = howScroll <= 0 ? 0 : howScroll;
                howScroll = howScroll >= 1 ? 1 : howScroll;
                scrolled = howScroll * scrollDiff;
                if (initScroll < lastScroll) {
                  if ((i - scrolled) > (-item.top - scrolled)) {
                    tl.set(sidebarWrap, {
                      position: 'absolute',
                      top: -item.top > 0 ? -item.top : 0
                    });
                    j = -item.top;
                  } else {
                    tl.set(sidebarWrap, {
                      position: 'absolute',
                      top: i,
                      clearProps: 'bottom'
                    });
                  }
                } else {
                  if (howScroll > 0 && howScroll <= 1) {
                    if ((j - scrolled) < 0) {
                      i = scrolled;
                      if (scrolled < (sidebar.parent().parent().height() - sidebarWrap.height())) {
                        tl.set(sidebarWrap, {
                          position: 'fixed',
                          bottom: 0,
                          clearProps: 'top'
                        });
                      } else {
                        tl.set(sidebarWrap, {
                          position: 'absolute',
                          top: i,
                          clearProps: 'bottom'
                        });
                      }
                    }
                  }
                }
                return lastScroll = initScroll;
              } else {
                if (item.top <= 0) {
                  if (item.top - sidebarWrap.height() + sidebar.parent().parent().height() < 0) {
                    return tl.set(sidebarWrap, {
                      position: 'relative',
                      top: sidebar.parent().parent().height() - sidebarWrap.height()
                    });
                  } else {
                    return tl.set(sidebarWrap, {
                      position: 'fixed',
                      top: 0
                    });
                  }
                } else {
                  return tl.set(sidebarWrap, {
                    position: 'static'
                  });
                }
              }
            } else {
              return tl.set(sidebarWrap, {
                width: '100%',
                position: 'static'
              });
            }
          });
        }
      }

      return themeStickySidebar;

    })();
    themeZoom = (function() {
      function themeZoom(selector) {
        var closeZoom, currentItem, hideZoom, imageSize, mainContent, mooveNextPrev, notNextPrev, s, size, tl, zoomContainer, zoomContent, zoomLeft, zoomRight, zoomSize;
        if (!$('.zoom-container').length) {
          $('body').append('<div class="zoom-container"><div class="zoom-content"></div></div>');
        }
        mainContent = $('.main-content');
        zoomContainer = $('.zoom-container');
        zoomContent = $('.zoom-content');
        tl = '';
        currentItem = 0;
        if (!$('.close-zoom').length) {
          s = Snap(36, 36);
          s.attr({
            viewBox: '0 0 512 512',
            preserveAspectRatio: 'xMidYMin meet',
            "class": 'close-zoom'
          });
          closeZoom = s.path('M298 311l-119 -119l119 -119l-30 -30l-119 119l-119 -119l-30 30l119 119l-119 119l30 30l119 -119l119 119z');
          closeZoom.attr({
            opacity: 0,
            transform: 'scale(0, 0, 10)'
          });
          zoomContainer.append(s.node);
        }
        zoomSize = function(width, height, ratio) {
          var size;
          if (ratio == null) {
            ratio = 0.5625;
          }
          size = {};
          size.wW = width * 0.8;
          size.wH = height * 0.8;
          size.iW = size.wH / ratio > size.wW ? size.wW : size.wH / ratio;
          size.iH = size.wH / ratio > size.wW ? size.wW * ratio : size.wH;
          return size;
        };
        hideZoom = function(tl) {
          tl.timeScale(4);
          tl.reverse();
          setTimeout(function() {
            return $('.main-content').attr('style', '');
          }, 1000);
          $('body').removeClass('no-scroll');
          return zoomContent.find('iframe').remove();
        };
        imageSize = function(imageSrc) {
          var zoomImg;
          zoomImg = new Image();
          zoomImg.src = imageSrc;
          return {
            width: zoomImg.width < size.wW && zoomImg.width !== 0 ? zoomImg.width : size.wW,
            height: zoomImg.height < size.wH && zoomImg.height !== 0 ? zoomImg.height : size.wH
          };
        };
        notNextPrev = function(direction, navTl) {
          navTl.to(zoomContent, 0.2, {
            x: (direction === 'right' ? -20 : 20) + 'px'
          });
          navTl.to(zoomContent, 0.2, {
            x: (direction === 'right' ? 20 : -20) + 'px'
          });
          return navTl.to(zoomContent, 0.2, {
            x: 0
          });
        };
        mooveNextPrev = function(direction, navTl, id) {
          var iSize, imgSrc, mooveItem, provider;
          if (direction === 'right') {
            currentItem++;
          } else {
            currentItem--;
          }
          imgSrc = selector.eq(id).data('zoom');
          mooveItem = window.innerWidth;
          iSize = new imageSize(imgSrc);
          navTl.to(zoomContent, 0.3, {
            x: (direction === 'right' ? -mooveItem : mooveItem) + 'px',
            opacity: 0,
            scale: 0,
            transformOrigin: '50% 50%'
          });
          zoomContent.find('iframe').remove();
          if (/^[a-zA-Z0-9_-]{11}$/g.test(imgSrc) || /^[0-9]{8}$/g.test(imgSrc)) {
            if (/^[a-zA-Z0-9_-]{11}$/g.test(imgSrc)) {
              provider = 'https://www.youtube.com/embed/';
            } else if (/^[0-9]{8}$/g.test(imgSrc)) {
              provider = 'https://player.vimeo.com/video/';
            }
            zoomContent.append($('<iframe>', {
              src: provider + imgSrc + '/?autoplay=1'
            }));
            navTl.set(zoomContent, {
              width: size.iW,
              height: size.iH,
              scale: 0,
              transformOrigin: '50% 100%',
              x: '0%',
              clearProps: "backgroundImage"
            });
          } else {
            navTl.set(zoomContent, {
              scale: 0,
              transformOrigin: '50% 100%',
              x: '0%',
              backgroundImage: "url(" + imgSrc + ")",
              width: iSize.width + 'px',
              height: iSize.height + 'px'
            });
          }
          return navTl.to(zoomContent, 0.3, {
            scale: 1,
            opacity: 1,
            ease: Back.easeOut
          });
        };
        size = zoomSize(window.innerWidth, window.innerHeight);
        $('body').on('click', selector.selector, function(e) {
          var iSize, provider, x, y, zoomData;
          selector = $(selector.selector);
          e.preventDefault();
          tl = new TimelineLite();
          zoomData = $(this).data('zoom');
          x = (e.clientX / window.innerWidth) * 100;
          y = (e.clientY / window.innerHeight) * 100;
          $('body').addClass('no-scroll');
          currentItem = selector.index(this);
          tl.set(zoomContainer, {
            display: 'block'
          });
          if (/^[a-zA-Z0-9_-]{11}$/g.test(zoomData) || /^[0-9]{8}$/g.test(zoomData)) {
            if (/^[a-zA-Z0-9_-]{11}$/g.test(zoomData)) {
              provider = 'https://www.youtube.com/embed/';
            } else if (/^[0-9]{8}$/g.test(zoomData)) {
              provider = 'https://player.vimeo.com/video/';
            }
            zoomContent.append($('<iframe>', {
              src: provider + zoomData + '/?autoplay=1'
            }));
            tl.set(zoomContent, {
              width: size.iW,
              height: size.iH
            });
          } else {
            iSize = new imageSize(zoomData);
            tl.set(zoomContent, {
              backgroundImage: "url(" + zoomData + ")",
              width: iSize.width + 'px',
              height: iSize.height + 'px'
            });
          }
          tl.to(zoomContainer, 0.3, {
            opacity: 1
          });
          tl.to(mainContent, 0.5, {
            opacity: 0.3,
            scale: 0.8,
            transformOrigin: '50 50'
          }, '-=0.3');
          tl.to(zoomContent, 0.5, {
            transformOrigin: x + "% " + y + "%",
            scale: 1,
            ease: Back.easeOut
          }, '-=0.3');
          return tl.to(closeZoom.node, 0.3, {
            scale: 1,
            rotation: -180,
            opacity: 1,
            transformOrigin: '50% 50%',
            ease: Elastic.ease
          }, '+=0.2');
        });
        if (selector.length > 1) {
          if (!$('.left-zoom, .right-zoom').length) {
            zoomContainer.append('<i class="left-zoom" data-icon="ios-arrow-left" data-icon-size="96" data-icon-color="#000000"></i><i class="right-zoom" data-icon="ios-arrow-right" data-icon-size="96" data-icon-color="#000000"></i>');
          }
          zoomLeft = $('.left-zoom');
          zoomRight = $('.right-zoom');
          zoomRight.on('click', function(e) {
            var navTl;
            e.preventDefault();
            navTl = new TimelineLite();
            if (currentItem + 1 < selector.length) {
              return mooveNextPrev('right', navTl, currentItem + 1);
            } else {
              notNextPrev('right', navTl);
              return currentItem = selector.length - 1;
            }
          });
          zoomLeft.on('click', function(e) {
            var navTl;
            e.preventDefault();
            navTl = new TimelineLite();
            if (currentItem > 0) {
              return mooveNextPrev('left', navTl, currentItem - 1);
            } else {
              notNextPrev('left', navTl);
              return currentItem = 0;
            }
          });
        }
        zoomContainer.on('click', function(e) {
          if ($(e.target).is('.zoom-container, .close-zoom path, .close-zoom')) {
            e.preventDefault();
            return hideZoom(tl);
          }
        });
        $(document).on('keyup', function(e) {
          if (e.keyCode === 27) {
            return hideZoom(tl);
          }
        });
        $(window).on('resize', function() {
          var currentHeight, currentWidth, ratio;
          currentWidth = zoomContent.width();
          currentHeight = zoomContent.height();
          ratio = currentHeight / currentWidth;
          size = zoomSize(window.innerWidth, window.innerHeight, ratio);
          return zoomContent.css({
            width: size.iW,
            height: size.iH
          });
        });
      }

      return themeZoom;

    })();
    themeTabs = (function() {
      function themeTabs() {
        var initIndex, tabContent, tabNav, tl;
        tabNav = $('.tab-nav > li');
        tabContent = $('.tab-content > li');
        initIndex = tabNav.index($('.current-tab'));
        tabContent.eq(initIndex).show();
        tl = new TimelineLite();
        tabNav.on('click', function(e) {
          var item, itemIndex, tabHeight;
          e.preventDefault();
          item = $(this);
          itemIndex = tabNav.index(this);
          if (!item.hasClass('current-tab')) {
            tabNav.removeClass('current-tab');
            item.addClass('current-tab');
            tabContent.hide();
            tabHeight = tabContent.eq(itemIndex).height() + 'px';
            tl.set(tabContent.eq(itemIndex), {
              display: 'block',
              opacity: 0,
              y: 24
            });
            return tl.to(tabContent.eq(itemIndex), 0.4, {
              opacity: 1,
              y: 0,
              ease: Circ.easeOut
            });
          }
        });
      }

      return themeTabs;

    })();
    themeIsotope = (function() {
      function themeIsotope(data) {
        var options;
        options = {
          selector: data.item || 'li',
          layoutMode: 'masonry'
        };
        data.selector.isotope(options);
        if ($('.portfolio-filters').length) {
          $('.portfolio-filters a').on('click', function(e) {
            var filterTag, item;
            e.preventDefault();
            item = $(this);
            $('.portfolio-filters li').removeClass('filter-active');
            item.parent().addClass('filter-active');
            filterTag = item.attr("cat").toLowerCase() != 0 ? '[data-grid-filter*=' + item.attr("cat").toLowerCase() + ']' : '*';
            return data.selector.isotope({
              filter: filterTag
            });
          });
        }
      }

      return themeIsotope;

    })();
    themeRouter = (function() {
      function themeRouter() {
        var currentLink, loadPage;
        // if (!$('.preloader').length) {
        //   $('body').append('<div class="preloader"><span class="top-span"></span><span class="bottom-span"></span></div><b></b>');
        // }
        currentLink = '';
        $('html').on('click', 'a', function(e) {
          var $this, homeUrl, linkHref, linkTarget;
          $this = $(this);
          linkTarget = $this.attr('target') || false;
          linkHref = $this.attr('href') || '#';
          homeUrl = themeOptions.routerHome;
          if (linkTarget === false && linkHref.indexOf('#') < 0 && linkHref.search(homeUrl) === 0 && linkHref.search('wp-admin') < 0 && !$this.hasClass('parent-link')) {
            e.preventDefault();

            return loadPage(linkHref, true, false);
          }
        });
        window.onpopstate = function(event) {
          if (event.state != null) {
            if (event.state.url !== currentLink) {
              loadPage(event.state.url, false, false);
              return currentLink = event.state.url;
            }
          }
        };
        loadPage = function(linkHref, addHistory, noAjax) {
          var diagonal, tla;
          diagonal = Math.sqrt(Math.pow(window.innerHeight, 2) + Math.pow(window.innerWidth, 2));
          tla = new TimelineLite();
          template.teslaMenu._hideAjax();
          if (!localStorage.getItem('ajax')) {
            // tla.set('#canvas-gredient', {
            //   opacity: 1
            // });
            // tla.to('.main-content section', 0.3, {
            //   opacity: 0,
            //   ease: Linear.ease
            // });
            // tla.to('.preloader', 0.2, {
            //   width: diagonal,
            //   ease: Linear.ease
            // }, '-=0.3');
            // tla.to('.preloader', 0.3, {
            //   rotation: -Math.asin(window.innerHeight / diagonal) * (180 / Math.PI),
            //   x: '-50%',
            //   y: '-50%',
            //   transformOrigin: '50% 50%',
            //   ease: Linear.ease
            // }, '-=0.1');
          }
          tla.to('.preloader', 0.5, {
            height: window.innerHeight + window.innerWidth,
            onComplete: function() {
              tla.set('#canvas-gredient', {
                opacity: 1
              });

              if (noAjax === false) {
                localStorage.setItem('ajax', true);
                return window.location.href = linkHref;
              } else {
                if (localStorage.getItem('ajax')) {
                  tla.set('.preloader', {
                    width: diagonal,
                    height: window.innerHeight + window.innerWidth,
                    ease: Linear.ease
                  }, '-=0.3');
                }
                tla.to('.preloader', 0.3, {
                  rotation: Math.asin(window.innerHeight / diagonal) * (180 / Math.PI),
                  x: '-50%',
                  y: '-50%',
                  transformOrigin: '50% 50%',
                  ease: Linear.ease
                });
                tla.to('.preloader + b', 0.2, {
                  scale: 0,
                  x: '-50%',
                  y: '-50%'
                });
                tla.to('.main-content section, #main-wrap', 0.3, {
                  opacity: 1,
                  ease: Linear.ease
                });
                tla.to('.top-span', 0.5, {
                  bottom: '100%'
                }, '-=0.1');
                return tla.to('.bottom-span', 0.5, {
                  top: '100%',
                  onComplete: function() {
                    $('.preloader, .preloader span').attr('style', '');
                    tla.to('.preloader', 0.1, {
                      rotation: 0,
                      x: '-50%',
                      y: '-50%',
                      transformOrigin: '50% 50%',
                      ease: Linear.ease
                    });
                    return localStorage.removeItem('ajax');
                  }
                }, '-=0.5');
              }
            }

          });
          return tla.to('.preloader + b', 0.3, {
            scale: 1,
            x: '-50%',
            y: '-50%'
          }, '-=0.3');
        };
        if (!$('#main-wrap').hasClass('tt-no-preload')) {
          loadPage('', true, true);
        }
      }

      return themeRouter;

    })();
    themeContactForm = (function() {
      function themeContactForm(formSelector) {
        if (formSelector.length) {
          formSelector.each(function(index) {
            var currentForm, result, resultVal, validateEmail;
            currentForm = formSelector.eq(index);
            result = currentForm.find('button[type="submit"]');
            resultVal = result.text();
            validateEmail = function(email) {
              var re;
              re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
              return re.test(email);
            };
            return currentForm.submit(function(e) {
              var formValues, formValuesItems;
              e.preventDefault();
              formValues = {};
              formValuesItems = currentForm.find('input[name],textarea[name]');
              formValuesItems.each(function() {
                return formValues[this.name] = jQuery(this).val();
              });
              if (formValues['name'] === '' || formValues['email'] === '' || formValues['message'] === '') {
                result.text('Заполните все поля');
              } else if (!validateEmail(formValues['email'])) {
                result.text('Не верный e-mail');
              } else {
                jQuery.ajax({
                  url: ajaxurl,
                  type: 'POST',
                  data: 'action=tt_contact_form&' + currentForm.serialize(),
                  success: function(resultAjax) {
                    return result.text(resultAjax);
                  }
                });
              }
              return setTimeout(function() {
                return result.text(resultVal);
              }, 3000);
            });
          });
        }
      }

      return themeContactForm;

    })();
    template = new TeslaThemes();
    return teslaRouter = new themeRouter();
  })(jQuery);

}).call(this);