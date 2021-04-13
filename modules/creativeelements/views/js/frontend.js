/*!
 * Creative Elements - Elementor based PageBuilder
 * Copyright 2019-2021 WebshopWorks.com & Elementor.com
 */

(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var ElementsHandler;

ElementsHandler = function( $ ) {
	var self = this;

	// element-type.skin-type
	var handlers = {
		// Elements
		'section': require( 'elementor-frontend/handlers/section' ),

		// Widgets
		'accordion.default': require( 'elementor-frontend/handlers/accordion' ),
		'alert.default': require( 'elementor-frontend/handlers/alert' ),
		'contact-form.default': require( 'elementor-frontend/handlers/ajax-form' ),
		'counter.default': require( 'elementor-frontend/handlers/counter' ),
		'countdown.default': require( 'elementor-frontend/handlers/countdown' ),
		'email-subscription.default': require( 'elementor-frontend/handlers/ajax-form' ),
		'image-carousel.default': require( 'elementor-frontend/handlers/image-carousel' ),
		'product-carousel.default': require( 'elementor-frontend/handlers/image-carousel' ),
		'progress.default': require( 'elementor-frontend/handlers/progress' ),
		'tabs.default': require( 'elementor-frontend/handlers/tabs' ),
		'testimonial-carousel.default': require( 'elementor-frontend/handlers/image-carousel' ),
		'text-editor.default': require( 'elementor-frontend/handlers/text-editor' ),
		'trustedshops-reviews.default': require( 'elementor-frontend/handlers/image-carousel' ),
		'toggle.default': require( 'elementor-frontend/handlers/toggle' ),
		'video.default': require( 'elementor-frontend/handlers/video' )
	};

	var addGlobalHandlers = function() {
		ceFrontend.hooks.addAction( 'frontend/element_ready/global', require( 'elementor-frontend/handlers/global' ) );
		ceFrontend.hooks.addAction( 'frontend/element_ready/widget', require( 'elementor-frontend/handlers/widget' ) );
	};

	var addElementsHandlers = function() {
		$.each( handlers, function( elementName, funcCallback ) {
			ceFrontend.hooks.addAction( 'frontend/element_ready/' + elementName, funcCallback );
		} );
	};

	var runElementsHandlers = function() {
		var $elements;

		if ( ceFrontend.isEditMode() ) {
			// Elements outside from the Preview
			$elements = ceFrontend.getScopeWindow().jQuery( '.elementor-element', '.elementor:not(.elementor-edit-mode)' );
		} else {
			$elements = $( '.elementor-element' );
		}

		$elements.each( function() {
			self.runReadyTrigger( $( this ) );
		} );
	};

	var init = function() {
		if ( ! ceFrontend.isEditMode() ) {
			self.initHandlers();
		}
	};

	this.initHandlers = function() {
		addGlobalHandlers();

		addElementsHandlers();

		runElementsHandlers();
	};

	this.getHandlers = function( handlerName ) {
		if ( handlerName ) {
			return handlers[ handlerName ];
		}

		return handlers;
	};

	// TODO: Temp fallback method from 1.2.0
	this.addExternalListener = function( $scope, event, callback, externalElement ) {
		ceFrontend.addListenerOnce( $scope.data( 'model-cid' ), event, callback, externalElement );
	};

	this.runReadyTrigger = function( $scope ) {
		var elementType = $scope.attr( 'data-element_type' );

		if ( ! elementType ) {
			return;
		}

		ceFrontend.hooks.doAction( 'frontend/element_ready/global', $scope, $ );

		var isWidgetType = ( -1 === [ 'section', 'column' ].indexOf( elementType ) );

		if ( isWidgetType ) {
			ceFrontend.hooks.doAction( 'frontend/element_ready/widget', $scope, $ );
		}

		ceFrontend.hooks.doAction( 'frontend/element_ready/' + elementType, $scope, $ );
	};

	init();
};

module.exports = ElementsHandler;

},{
	"elementor-frontend/handlers/accordion":4,
	"elementor-frontend/handlers/ajax-form":21,
	"elementor-frontend/handlers/alert":5,
	"elementor-frontend/handlers/counter":6,
	"elementor-frontend/handlers/countdown":22,
	"elementor-frontend/handlers/global":7,
	"elementor-frontend/handlers/image-carousel":8,
	"elementor-frontend/handlers/progress":9,
	"elementor-frontend/handlers/section":10,
	"elementor-frontend/handlers/tabs":11,
	"elementor-frontend/handlers/text-editor":12,
	"elementor-frontend/handlers/toggle":13,
	"elementor-frontend/handlers/video":14,
	"elementor-frontend/handlers/widget":15
}],2:[function(require,module,exports){
/* global ceFrontendConfig */
( function( $ ) {
	var elements = {},
		EventManager = require( '../utils/hooks' ),
		Module = require( './handler-module' ),
		ElementsHandler = require( 'elementor-frontend/elements-handler' ),
		YouTubeModule = require( 'elementor-frontend/utils/youtube' ),
		AnchorsModule = require( 'elementor-frontend/utils/anchors' );

	var ceFrontend = function() {
		var self = this,
			dialogsManager,
			scopeWindow = window;

		this.config = ceFrontendConfig;

		this.hooks = new EventManager();

		this.Module = Module;

		var initElements = function() {
			elements.$document = $( self.getScopeWindow().document );

			elements.$elementor = elements.$document.find( '.elementor' );
		};

		var initOnReadyComponents = function() {
			self.utils = {
				youtube: new YouTubeModule(),
				anchors: new AnchorsModule()
			};

			self.elementsHandler = new ElementsHandler( $ );
		};

		this.init = function() {
			initElements();

			$( window ).trigger( 'elementor/frontend/init' );

			self.hooks.doAction( 'init' );
			initOnReadyComponents();
		};

		this.getScopeWindow = function() {
			return scopeWindow;
		};

		this.setScopeWindow = function( window ) {
			scopeWindow = window;
		};

		this.getElements = function( element ) {
			if ( element ) {
				return elements[ element ];
			}

			return elements;
		};

		this.getDialogsManager = function() {
			if ( ! dialogsManager ) {
				dialogsManager = new DialogsManager.Instance();
			}

			return dialogsManager;
		};

		this.isEditMode = function() {
			return self.config.isEditMode;
		};

		// Based on underscore function
		this.throttle = function( func, wait ) {
			var timeout,
				context,
				args,
				result,
				previous = 0;

			var later = function() {
				previous = Date.now();
				timeout = null;
				result = func.apply( context, args );

				if ( ! timeout ) {
					context = args = null;
				}
			};

			return function() {
				var now = Date.now(),
					remaining = wait - ( now - previous );

				context = this;
				args = arguments;

				if ( remaining <= 0 || remaining > wait ) {
					if ( timeout ) {
						clearTimeout( timeout );
						timeout = null;
					}

					previous = now;
					result = func.apply( context, args );

					if ( ! timeout ) {
						context = args = null;
					}
				} else if ( ! timeout ) {
					timeout = setTimeout( later, remaining );
				}

				return result;
			};
		};

		this.addListenerOnce = function( listenerID, event, callback, to ) {
			if ( ! to ) {
				to = $( self.getScopeWindow() );
			}

			if ( ! self.isEditMode() ) {
				to.on( event, callback );

				return;
			}

			if ( to instanceof jQuery ) {
				var eventNS = event + '.' + listenerID;

				to.off( eventNS ).on( eventNS, callback );
			} else {
				to.off( event, null, listenerID ).on( event, callback, listenerID );
			}
		};

		this.getCurrentDeviceMode = function() {
			return getComputedStyle( elements.$elementor[ 0 ], ':after' ).content.replace( /"/g, '' );
		};

		this.waypoint = function( $element, callback, options ) {
			var correctCallback = function() {
				var element = this.element || this;

				return callback.apply( element, arguments );
			};

			$element.elementorWaypoint( correctCallback, options );
		};
	};

	window.ceFrontend = new ceFrontend();
} )( jQuery );

if ( ! ceFrontend.isEditMode() ) {
	jQuery( ceFrontend.init );
}

},{
	"../utils/hooks":18,
	"./handler-module":3,
	"elementor-frontend/elements-handler":1,
	"elementor-frontend/utils/anchors":16,
	"elementor-frontend/utils/youtube":17
}],3:[function(require,module,exports){
var ViewModule = require( '../utils/view-module' ),
	HandlerModule;

HandlerModule = ViewModule.extend( {
	$element: null,

	onElementChange: null,

	__construct: function( settings ) {
		this.$element  = settings.$element;

		if ( ceFrontend.isEditMode() ) {
			this.addEditorListener();
		}
	},

	addEditorListener: function() {
		var self = this;

		if ( self.onElementChange ) {
			var uniqueGroup = self.getModelCID() + self.$element.attr( 'data-element_type' ),
				elementName = self.getElementName(),
				eventName = 'change';

			if ( 'global' !== elementName ) {
				eventName += ':' + elementName;
			}

			ceFrontend.addListenerOnce( uniqueGroup, eventName, function( controlView, elementView ) {
				var currentUniqueGroup = elementView.model.cid + elementView.$el.attr( 'data-element_type' );

				if ( currentUniqueGroup !== uniqueGroup ) {
					return;
				}

				self.onElementChange( controlView.model.get( 'name' ),  controlView, elementView );
			}, elementor.channels.editor );
		}
	},

	getElementName: function() {
		return this.$element.data( 'element_type' ).split( '.' )[0];
	},

	getID: function() {
		return this.$element.data( 'id' );
	},

	getModelCID: function() {
		return this.$element.data( 'model-cid' );
	},

	getElementSettings: function( setting ) {
		var elementSettings,
			modelCID = this.getModelCID();

		if ( ceFrontend.isEditMode() && modelCID ) {
			var settings = ceFrontend.config.elements.data[ modelCID ],
				activeControls = settings.getActiveControls(),
				activeValues = _.pick( settings.attributes, Object.keys( activeControls ) ),
				settingsKeys = ceFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];

			elementSettings = _.pick( activeValues, settingsKeys );
		} else {
			elementSettings = this.$element.data( 'settings' ) || {};
		}

		return this.getItems( elementSettings, setting );
	}
} );

module.exports = HandlerModule;

},{"../utils/view-module":20}],4:[function(require,module,exports){
var activateSection = function( sectionIndex, $accordionTitles ) {
	var $activeTitle = $accordionTitles.filter( '.active' ),
		$requestedTitle = $accordionTitles.filter( '[data-section="' + sectionIndex + '"]' ),
		isRequestedActive = $requestedTitle.hasClass( 'active' );

	$activeTitle
		.removeClass( 'active' )
		.next()
		.slideUp();

	if ( ! isRequestedActive ) {
		$requestedTitle
			.addClass( 'active' )
			.next()
			.slideDown();
	}
};

module.exports = function( $scope, $ ) {
	var defaultActiveSection = $scope.find( '.elementor-accordion' ).data( 'active-section' ),
		$accordionTitles = $scope.find( '.elementor-accordion-title' );

	if ( ! defaultActiveSection ) {
		defaultActiveSection = 1;
	}

	activateSection( defaultActiveSection, $accordionTitles );

	$accordionTitles.on( 'click', function() {
		activateSection( this.dataset.section, $accordionTitles );
	} );
};

},{}],5:[function(require,module,exports){
module.exports = function( $scope, $ ) {
	$scope.find( '.elementor-alert-dismiss' ).on( 'click', function() {
		$( this ).parent().fadeOut();
	} );
};

},{}],6:[function(require,module,exports){
module.exports = function( $scope, $ ) {
	ceFrontend.waypoint( $scope.find( '.elementor-counter-number' ), function() {
		var $number = $( this ),
			data = $number.data();

		var decimalDigits = data.toValue.toString().match( /\.(.*)/ );

		if ( decimalDigits ) {
			data.rounding = decimalDigits[1].length;
		}

		$number.numerator( data );
	}, { offset: '90%' } );
};

},{}],7:[function(require,module,exports){
var HandlerModule = require( 'elementor-frontend/handler-module' ),
	GlobalHandler;

GlobalHandler = HandlerModule.extend( {
	onInit: function() {
		HandlerModule.prototype.onInit.apply( this, arguments );

		var $element = this.$element;

		var animation = $element.data( 'animation' );

		if ( ! animation ) {
			return;
		}

		$element.addClass( 'elementor-invisible' ).removeClass( animation );

		ceFrontend.waypoint( $element, function() {
			$element.removeClass( 'elementor-invisible' ).addClass( 'animated ' + animation );
		}, { offset: '90%' } );
	}
} );

module.exports = function( $scope ) {
	if ( ceFrontend.isEditMode() ) {
		return;
	}

	new GlobalHandler( { $element: $scope } );
};

},{"elementor-frontend/handler-module":3}],8:[function(require,module,exports){
module.exports = function( $scope, $ ) {
	var $carousel = $scope.find( '.elementor-image-carousel' );
	if ( ! $carousel.length ) {
		return;
	}

	var savedOptions = $carousel.data( 'slider_options' ),
		tabletSlides = 1 === savedOptions.slidesToShow ? 1 : 2,
		defaultOptions = {
			responsive: [
				{
					breakpoint: 769,
					settings: {
						slidesToShow: savedOptions.slidesToShowTablet,
						slidesToScroll: tabletSlides
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: savedOptions.slidesToShowMobile,
						slidesToScroll: 1
					}
				}
			]
		},

		slickOptions = $.extend( {}, defaultOptions, $carousel.data( 'slider_options' ) );

	$carousel.slick( slickOptions );
};

},{}],9:[function(require,module,exports){
module.exports = function( $scope, $ ) {
	ceFrontend.waypoint( $scope.find( '.elementor-progress-bar' ), function() {
		var $progressbar = $( this );

		$progressbar.css( 'width', $progressbar.data( 'max' ) + '%' );
	}, { offset: '90%' } );
};

},{}],10:[function(require,module,exports){
var HandlerModule = require( 'elementor-frontend/handler-module' );

var BackgroundVideo = function( $backgroundVideoContainer, $ ) {
	var player,
		elements = {},
		isYTVideo = false;

	var calcVideosSize = function() {
		var containerWidth = $backgroundVideoContainer.outerWidth(),
			containerHeight = $backgroundVideoContainer.outerHeight(),
			aspectRatioSetting = '16:9', //TEMP
			aspectRatioArray = aspectRatioSetting.split( ':' ),
			aspectRatio = aspectRatioArray[ 0 ] / aspectRatioArray[ 1 ],
			ratioWidth = containerWidth / aspectRatio,
			ratioHeight = containerHeight * aspectRatio,
			isWidthFixed = containerWidth / containerHeight > aspectRatio;

		return {
			width: isWidthFixed ? containerWidth : ratioHeight,
			height: isWidthFixed ? ratioWidth : containerHeight
		};
	};

	var changeVideoSize = function() {
		var $video = isYTVideo ? $( player.getIframe() ) : elements.$backgroundVideo,
			size = calcVideosSize();

		$video.width( size.width ).height( size.height );
	};

	var prepareYTVideo = function( YT, videoID ) {
		player = new YT.Player( elements.$backgroundVideo[ 0 ], {
			videoId: videoID,
			events: {
				onReady: function() {
					player.mute();

					changeVideoSize();

					player.playVideo();
				},
				onStateChange: function( event ) {
					if ( event.data === YT.PlayerState.ENDED ) {
						player.seekTo( 0 );
					}
				}
			},
			playerVars: {
				controls: 0,
				showinfo: 0
			}
		} );

		$( ceFrontend.getScopeWindow() ).on( 'resize', changeVideoSize );
	};

	var initElements = function() {
		elements.$backgroundVideo = $backgroundVideoContainer.children( '.elementor-background-video' );
	};

	var run = function() {
		var videoID = elements.$backgroundVideo.data( 'video-id' );

		if ( videoID ) {
			isYTVideo = true;

			ceFrontend.utils.youtube.onYoutubeApiReady( function( YT ) {
				setTimeout( function() {
					prepareYTVideo( YT, videoID );
				}, 1 );
			} );
		} else {
			elements.$backgroundVideo.one( 'canplay', changeVideoSize );
		}
	};

	var init = function() {
		initElements();
		run();
	};

	init();
};

var StretchedSection = function( $section, $ ) {
	var elements = {},
		settings = {};

	var stretchSection = function() {
		// Clear any previously existing css associated with this script
		var direction = settings.is_rtl ? 'right' : 'left',
			resetCss = {},
			isStretched = $section.hasClass( 'elementor-section-stretched' );

		if ( ceFrontend.isEditMode() || isStretched ) {
			resetCss.width = 'auto';

			resetCss[ direction ] = 0;

			$section.css( resetCss );
		}

		if ( ! isStretched ) {
			return;
		}

		var containerWidth = elements.$scopeWindow.outerWidth(),
			sectionWidth = $section.outerWidth(),
			sectionOffset = $section.offset().left,
			correctOffset = sectionOffset;

		if ( elements.$sectionContainer.length ) {
			var containerOffset = elements.$sectionContainer.offset().left;

			containerWidth = elements.$sectionContainer.outerWidth();

			if ( sectionOffset > containerOffset ) {
				correctOffset = sectionOffset - containerOffset;
			} else {
				correctOffset = 0;
			}
		}

		if ( settings.is_rtl ) {
			correctOffset = containerWidth - ( sectionWidth + correctOffset );
		}

		resetCss.width = containerWidth + 'px';

		resetCss[ direction ] = -correctOffset + 'px';

		$section.css( resetCss );
	};

	var initSettings = function() {
		settings.sectionContainerSelector = ceFrontend.config.stretchedSectionContainer;
		settings.is_rtl = ceFrontend.config.is_rtl;
	};

	var initElements = function() {
		elements.scopeWindow = ceFrontend.getScopeWindow();
		elements.$scopeWindow = $( elements.scopeWindow );
		elements.$sectionContainer = $( elements.scopeWindow.document ).find( settings.sectionContainerSelector );
	};

	var bindEvents = function() {
		ceFrontend.addListenerOnce( $section.data( 'model-cid' ), 'resize', stretchSection );
	};

	var init = function() {
		initSettings();
		initElements();
		bindEvents();
		stretchSection();
	};

	init();
};

var Shapes = HandlerModule.extend( {

	getDefaultSettings: function() {
		return {
			selectors: {
				container: '> .elementor-shape-%s'
			},
			svgURL: ceFrontend.config.urls.assets + 'img/shapes/'
		};
	},

	getDefaultElements: function() {
		var elements = {},
			selectors = this.getSettings( 'selectors' );

		elements.$topContainer = this.$element.find( selectors.container.replace( '%s', 'top' ) );

		elements.$bottomContainer = this.$element.find( selectors.container.replace( '%s', 'bottom' ) );

		return elements;
	},

	buildSVG: function( side ) {
		var self = this,
			baseSettingKey = 'shape_divider_' + side,
			shapeType = self.getElementSettings( baseSettingKey ),
			$svgContainer = this.elements[ '$' + side + 'Container' ];

		$svgContainer.empty().attr( 'data-shape', shapeType );

		if ( ! shapeType ) {
			return;
		}

		var fileName = shapeType;

		if ( self.getElementSettings( baseSettingKey + '_negative' ) ) {
			fileName += '-negative';
		}

		var svgURL = self.getSettings( 'svgURL' ) + fileName + '.svg';

		jQuery.get( svgURL, function( data ) {
			$svgContainer.append( data.childNodes[0] );
		} );

		this.setNegative( side );
	},

	setNegative: function( side ) {
		this.elements[ '$' + side + 'Container' ].attr( 'data-negative', !! this.getElementSettings( 'shape_divider_' + side + '_negative' ) );
	},

	onInit: function() {
		var self = this;

		HandlerModule.prototype.onInit.apply( self, arguments );

		[ 'top', 'bottom' ].forEach( function( side ) {
			if ( self.getElementSettings( 'shape_divider_' + side ) ) {
				self.buildSVG( side );
			}
		} );
	},

	onElementChange: function( propertyName ) {
		var shapeChange = propertyName.match( /^shape_divider_(top|bottom)$/ );

		if ( shapeChange ) {
			this.buildSVG( shapeChange[1] );

			return;
		}

		var negativeChange = propertyName.match( /^shape_divider_(top|bottom)_negative$/ );

		if ( negativeChange ) {
			this.buildSVG( negativeChange[1] );

			this.setNegative( negativeChange[1] );
		}
	}
} );

module.exports = function( $scope, $ ) {
	new StretchedSection( $scope, $ );

	if ( ceFrontend.isEditMode() ) {
		new Shapes( { $element:  $scope } );
	}

	var $backgroundVideoContainer = $scope.find( '.elementor-background-video-container' );

	if ( $backgroundVideoContainer ) {
		new BackgroundVideo( $backgroundVideoContainer, $ );
	}
};

},{"elementor-frontend/handler-module":3}],11:[function(require,module,exports){
module.exports = function( $scope, $ ) {
	var defaultActiveTab = $scope.find( '.elementor-tabs' ).data( 'active-tab' ),
		$tabsTitles = $scope.find( '.elementor-tab-title' ),
		$tabs = $scope.find( '.elementor-tab-content' ),
		$active,
		$content;

	if ( ! defaultActiveTab ) {
		defaultActiveTab = 1;
	}

	var activateTab = function( tabIndex ) {
		if ( $active ) {
			$active.removeClass( 'active' );

			$content.hide();
		}

		$active = $tabsTitles.filter( '[data-tab="' + tabIndex + '"]' );

		$active.addClass( 'active' );

		$content = $tabs.filter( '[data-tab="' + tabIndex + '"]' );

		$content.show();
	};

	activateTab( defaultActiveTab );

	$tabsTitles.on( 'click', function() {
		activateTab( this.dataset.tab );
	} );
};

},{}],12:[function(require,module,exports){
var HandlerModule = require( 'elementor-frontend/handler-module' ),
	TextEditor;

TextEditor = HandlerModule.extend( {
	dropCapLetter: '',

	getDefaultSettings: function() {
		return {
			selectors: {
				paragraph: 'p:first'
			},
			classes: {
				dropCap: 'elementor-drop-cap',
				dropCapLetter: 'elementor-drop-cap-letter'
			}
		};
	},

	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' ),
			classes = this.getSettings( 'classes' ),
			$dropCap = jQuery( '<span>', { 'class': classes.dropCap } ),
			$dropCapLetter = jQuery( '<span>', { 'class': classes.dropCapLetter } );

		$dropCap.append( $dropCapLetter );

		return {
			$paragraph: this.$element.find( selectors.paragraph ),
			$dropCap: $dropCap,
			$dropCapLetter: $dropCapLetter
		};
	},

	getElementName: function() {
		return 'text-editor';
	},

	wrapDropCap: function() {
		var isDropCapEnabled = this.getElementSettings( 'drop_cap' );

		if ( ! isDropCapEnabled ) {
			// If there is an old drop cap inside the paragraph
			if ( this.dropCapLetter ) {
				this.elements.$dropCap.remove();

				this.elements.$paragraph.prepend( this.dropCapLetter );

				this.dropCapLetter = '';
			}

			return;
		}

		var $paragraph = this.elements.$paragraph;

		if ( ! $paragraph.length ) {
			return;
		}

		var	paragraphContent = $paragraph.html().replace( /&nbsp;/g, ' ' ),
			firstLetterMatch = paragraphContent.match( /^ *([^ ] ?)/ );

		if ( ! firstLetterMatch ) {
			return;
		}

		var firstLetter = firstLetterMatch[1],
			trimmedFirstLetter = firstLetter.trim();

		// Don't apply drop cap when the content starting with an HTML tag
		if ( '<' === trimmedFirstLetter ) {
			return;
		}

		this.dropCapLetter = firstLetter;

		this.elements.$dropCapLetter.text( trimmedFirstLetter );

		var restoredParagraphContent = paragraphContent.slice( firstLetter.length ).replace( /^ */, function( match ) {
			return new Array( match.length + 1 ).join( '&nbsp;' );
		});

		$paragraph.html( restoredParagraphContent ).prepend( this.elements.$dropCap );
	},

	onInit: function() {
		HandlerModule.prototype.onInit.apply( this, arguments );

		this.wrapDropCap();
	},

	onElementChange: function( propertyName ) {
		if ( 'drop_cap' === propertyName ) {
			this.wrapDropCap();
		}
	}
} );

module.exports = function( $scope ) {
	new TextEditor( { $element: $scope } );
};

},{"elementor-frontend/handler-module":3}],13:[function(require,module,exports){
module.exports = function( $scope, $ ) {
	var $toggleTitles = $scope.find( '.elementor-toggle-title' );

	$toggleTitles.on( 'click', function() {
		var $active = $( this ),
			$content = $active.next();

		if ( $active.hasClass( 'active' ) ) {
			$active.removeClass( 'active' );
			$content.slideUp();
		} else {
			$active.addClass( 'active' );
			$content.slideDown();
		}
	} );
};

},{}],14:[function(require,module,exports){
var HandlerModule = require( 'elementor-frontend/handler-module' ),
	VideoModule;

VideoModule = HandlerModule.extend( {
	oldAnimation: null,

	oldAspectRatio: null,

	getDefaultSettings: function() {
		return {
			selectors: {
				imageOverlay: '.elementor-custom-embed-image-overlay',
				videoWrapper: '.elementor-wrapper',
				videoFrame: 'iframe'
			},
			classes: {
				aspectRatio: 'elementor-aspect-ratio-%s'
			}
		};
	},

	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' );

		var elements = {
			$lightBoxContainer: jQuery( ceFrontend.getScopeWindow().document.body ),
			$imageOverlay: this.$element.find( selectors.imageOverlay ),
			$videoWrapper: this.$element.find( selectors.videoWrapper )
		};

		elements.$videoFrame = elements.$videoWrapper.find( selectors.videoFrame );

		return elements;
	},

	getLightBoxModal: function() {
		if ( ! VideoModule.lightBoxModal ) {
			this.initLightBoxModal();
		}

		return VideoModule.lightBoxModal;
	},

	initLightBoxModal: function() {
		var self = this;

		var lightBoxModal = VideoModule.lightBoxModal = ceFrontend.getDialogsManager().createWidget( 'lightbox', {
			className: 'elementor-widget-video-modal',
			container: self.elements.$lightBoxContainer,
			closeButton: true,
			position: {
				within: ceFrontend.getScopeWindow()
			}
		} );

		lightBoxModal.refreshPosition = function() {
			var position = self.getElementSettings( 'lightbox_content_position' );

			lightBoxModal.setSettings( 'position', {
				my: position,
				at: position
			} );

			DialogsManager.getWidgetType( 'lightbox' ).prototype.refreshPosition.apply( lightBoxModal, arguments );
		};

		lightBoxModal.getElements( 'message' ).addClass( 'elementor-video-wrapper' );
	},

	handleVideo: function() {
		var self = this,
			$videoFrame = this.elements.$videoFrame,
			isLightBoxEnabled = self.getElementSettings( 'lightbox' );

		if ( isLightBoxEnabled ) {
			// Autoload DialogsManager when needed
			if ( ! window.DialogsManager ) {
				return jQuery.ajax({
					url: (window.baseDir || prestashop.urls.base_url) + 'modules/creativeelements/views/lib/dialog/dialog.min.js?v=3.1.2',
					dataType: 'script',
					cache: true,
					success: self.handleVideo.bind(self),
				});
			}

			self.playVideo();

			var lightBoxModal = self.getLightBoxModal(),
				$widgetContent = lightBoxModal.getElements( 'widgetContent' );

			lightBoxModal.onHide = function() {
				DialogsManager.getWidgetType( 'lightbox' ).prototype.onHide.apply( lightBoxModal, arguments );

				$videoFrame.remove();

				$widgetContent.removeClass( 'animated' );
			};

			lightBoxModal.onShow = function() {
				DialogsManager.getWidgetType( 'lightbox' ).prototype.onShow.apply( lightBoxModal, arguments );

				lightBoxModal.setMessage( $videoFrame );

				self.animateVideo();
			};

			self.handleAspectRatio();

			$videoFrame.remove();

			lightBoxModal
				.setID( 'elementor-video-modal-' + self.getID() )
				.show();
		} else {
			self.playVideo();

			this.elements.$imageOverlay.remove();
		}
	},

	playVideo: function() {
		var $videoFrame = this.elements.$videoFrame,
			newSourceUrl = $videoFrame[0].src.replace( '&autoplay=0', '' );

		$videoFrame[0].src = newSourceUrl + '&autoplay=1';
	},

	animateVideo: function() {
		var animation = this.getElementSettings( 'lightbox_content_animation' ),
			$widgetContent = this.getLightBoxModal().getElements( 'widgetContent' );

		if ( this.oldAnimation ) {
			$widgetContent.removeClass( this.oldAnimation );
		}

		this.oldAnimation = animation;

		if ( animation ) {
			$widgetContent.addClass( 'animated ' + animation );
		}
	},

	handleAspectRatio: function() {
		var $widgetContent = this.getLightBoxModal().getElements( 'widgetContent' ),
			oldAspectRatio = this.oldAspectRatio,
			aspectRatio = this.getElementSettings( 'aspect_ratio' ),
			aspectRatioClass = this.getSettings( 'classes.aspectRatio' );

		this.oldAspectRatio = aspectRatio;

		if ( oldAspectRatio ) {
			$widgetContent.removeClass( aspectRatioClass.replace( '%s', oldAspectRatio ) );
		}

		$widgetContent.addClass( aspectRatioClass.replace( '%s', aspectRatio ) );
	},

	bindEvents: function() {
		this.elements.$imageOverlay.on( 'click', this.handleVideo );
	},

	onElementChange: function( propertyName ) {
		if ( 'lightbox_content_animation' === propertyName ) {
			this.animateVideo();

			return;
		}

		var lightBoxModal = this.getLightBoxModal();

		if ( -1 !== [ 'lightbox_content_width', 'lightbox_content_position' ].indexOf( propertyName ) ) {
			lightBoxModal.refreshPosition();

			return;
		}

		var isLightBoxEnabled = this.getElementSettings( 'lightbox' );

		if ( 'lightbox' === propertyName && ! isLightBoxEnabled ) {
			lightBoxModal.hide();

			return;
		}

		if ( 'aspect_ratio' === propertyName && isLightBoxEnabled ) {
			this.handleAspectRatio();

			lightBoxModal.refreshPosition();
		}
	}
} );

VideoModule.lightBoxModal = null;

module.exports = function( $scope ) {
	new VideoModule( { $element: $scope } );
};

},{"elementor-frontend/handler-module":3}],15:[function(require,module,exports){
module.exports = function( $scope, $ ) {
	if ( ! ceFrontend.isEditMode() ) {
		return;
	}

	if ( $scope.hasClass( 'elementor-widget-edit-disabled' ) ) {
		return;
	}

	$scope.find( '.elementor-element' ).each( function() {
		ceFrontend.elementsHandler.runReadyTrigger( $( this ) );
	} );
};

},{}],16:[function(require,module,exports){
var ViewModule = require( '../../utils/view-module' );

module.exports = ViewModule.extend( {
	getDefaultSettings: function() {

		return {
			scrollDuration: 1000,
			selectors: {
				links: 'a[href*="#"]',
				targets: '.elementor-element, .elementor-menu-anchor',
				scrollable: 'html, body',
				wpAdminBar: '#wpadminbar'
			}
		};
	},

	getDefaultElements: function() {
		var $ = jQuery,
			selectors = this.getSettings( 'selectors' );

		return {
			window: ceFrontend.getScopeWindow(),
			$scrollable: $( selectors.scrollable ),
			$wpAdminBar: $( selectors.wpAdminBar )
		};
	},

	bindEvents: function() {
		ceFrontend.getElements( '$document' ).on( 'click', this.getSettings( 'selectors.links' ), this.handleAnchorLinks );
	},

	handleAnchorLinks: function( event ) {
		var clickedLink = event.currentTarget,
			location = this.elements.window.location,
			isSamePathname = ( location.pathname === clickedLink.pathname ),
			isSameHostname = ( location.hostname === clickedLink.hostname );

		if ( ! isSameHostname || ! isSamePathname || clickedLink.hash.length < 2 ) {
			return;
		}

		var $anchor = jQuery( clickedLink.hash ).filter( this.getSettings( 'selectors.targets' ) );

		if ( ! $anchor.length ) {
			return;
		}

		var adminBarHeight = this.elements.$wpAdminBar.height(),
			scrollTop = $anchor.offset().top - adminBarHeight;

		event.preventDefault();

		scrollTop = ceFrontend.hooks.applyFilters( 'frontend/handlers/menu_anchor/scroll_top_distance', scrollTop );

		this.elements.$scrollable.animate( {
			scrollTop: scrollTop
		}, this.getSettings( 'scrollDuration' ) );
	},

	onInit: function() {
		ViewModule.prototype.onInit.apply( this, arguments );

		this.bindEvents();
	}
} );

},{"../../utils/view-module":20}],17:[function(require,module,exports){
var ViewModule = require( '../../utils/view-module' );

module.exports = ViewModule.extend( {
	getDefaultSettings: function() {
		return {
			isInserted: false,
			APISrc: 'https://www.youtube.com/iframe_api',
			selectors: {
				firstScript: 'script:first'
			}
		};
	},

	getDefaultElements: function() {

		return {
			$firstScript: jQuery( this.getSettings( 'selectors.firstScript' ) )
		};
	},

	insertYTAPI: function() {
		this.setSettings( 'isInserted', true );

		this.elements.$firstScript.before( jQuery( '<script>', { src: this.getSettings( 'APISrc' ) } ) );
	},

	onYoutubeApiReady: function( callback ) {
		var self = this;

		if ( ! self.getSettings( 'IsInserted' ) ) {
			self.insertYTAPI();
		}

		if ( window.YT && YT.loaded ) {
			callback( YT );
		} else {
			// If not ready check again by timeout..
			setTimeout( function() {
				self.onYoutubeApiReady( callback );
			}, 350 );
		}
	}
} );

},{"../../utils/view-module":20}],18:[function(require,module,exports){
'use strict';

/**
 * Handles managing all events for whatever you plug it into. Priorities for hooks are based on lowest to highest in
 * that, lowest priority hooks are fired first.
 */
var EventManager = function() {
	var slice = Array.prototype.slice,
		MethodsAvailable;

	/**
	 * Contains the hooks that get registered with this EventManager. The array for storage utilizes a "flat"
	 * object literal such that looking up the hook utilizes the native object literal hash.
	 */
	var STORAGE = {
		actions: {},
		filters: {}
	};

	/**
	 * Removes the specified hook by resetting the value of it.
	 *
	 * @param type Type of hook, either 'actions' or 'filters'
	 * @param hook The hook (namespace.identifier) to remove
	 *
	 * @private
	 */
	function _removeHook( type, hook, callback, context ) {
		var handlers, handler, i;

		if ( ! STORAGE[ type ][ hook ] ) {
			return;
		}
		if ( ! callback ) {
			STORAGE[ type ][ hook ] = [];
		} else {
			handlers = STORAGE[ type ][ hook ];
			if ( ! context ) {
				for ( i = handlers.length; i--; ) {
					if ( handlers[ i ].callback === callback ) {
						handlers.splice( i, 1 );
					}
				}
			} else {
				for ( i = handlers.length; i--; ) {
					handler = handlers[ i ];
					if ( handler.callback === callback && handler.context === context ) {
						handlers.splice( i, 1 );
					}
				}
			}
		}
	}

	/**
	 * Use an insert sort for keeping our hooks organized based on priority. This function is ridiculously faster
	 * than bubble sort, etc: http://jsperf.com/javascript-sort
	 *
	 * @param hooks The custom array containing all of the appropriate hooks to perform an insert sort on.
	 * @private
	 */
	function _hookInsertSort( hooks ) {
		var tmpHook, j, prevHook;
		for ( var i = 1, len = hooks.length; i < len; i++ ) {
			tmpHook = hooks[ i ];
			j = i;
			while ( ( prevHook = hooks[ j - 1 ] ) && prevHook.priority > tmpHook.priority ) {
				hooks[ j ] = hooks[ j - 1 ];
				--j;
			}
			hooks[ j ] = tmpHook;
		}

		return hooks;
	}

	/**
	 * Adds the hook to the appropriate storage container
	 *
	 * @param type 'actions' or 'filters'
	 * @param hook The hook (namespace.identifier) to add to our event manager
	 * @param callback The function that will be called when the hook is executed.
	 * @param priority The priority of this hook. Must be an integer.
	 * @param [context] A value to be used for this
	 * @private
	 */
	function _addHook( type, hook, callback, priority, context ) {
		var hookObject = {
			callback: callback,
			priority: priority,
			context: context
		};

		// Utilize 'prop itself' : http://jsperf.com/hasownproperty-vs-in-vs-undefined/19
		var hooks = STORAGE[ type ][ hook ];
		if ( hooks ) {
			// TEMP FIX BUG
			var hasSameCallback = false;
			jQuery.each( hooks, function() {
				if ( this.callback === callback ) {
					hasSameCallback = true;
					return false;
				}
			} );

			if ( hasSameCallback ) {
				return;
			}
			// END TEMP FIX BUG

			hooks.push( hookObject );
			hooks = _hookInsertSort( hooks );
		} else {
			hooks = [ hookObject ];
		}

		STORAGE[ type ][ hook ] = hooks;
	}

	/**
	 * Runs the specified hook. If it is an action, the value is not modified but if it is a filter, it is.
	 *
	 * @param type 'actions' or 'filters'
	 * @param hook The hook ( namespace.identifier ) to be ran.
	 * @param args Arguments to pass to the action/filter. If it's a filter, args is actually a single parameter.
	 * @private
	 */
	function _runHook( type, hook, args ) {
		var handlers = STORAGE[ type ][ hook ], i, len;

		if ( ! handlers ) {
			return ( 'filters' === type ) ? args[ 0 ] : false;
		}

		len = handlers.length;
		if ( 'filters' === type ) {
			for ( i = 0; i < len; i++ ) {
				args[ 0 ] = handlers[ i ].callback.apply( handlers[ i ].context, args );
			}
		} else {
			for ( i = 0; i < len; i++ ) {
				handlers[ i ].callback.apply( handlers[ i ].context, args );
			}
		}

		return ( 'filters' === type ) ? args[ 0 ] : true;
	}

	/**
	 * Adds an action to the event manager.
	 *
	 * @param action Must contain namespace.identifier
	 * @param callback Must be a valid callback function before this action is added
	 * @param [priority=10] Used to control when the function is executed in relation to other callbacks bound to the same hook
	 * @param [context] Supply a value to be used for this
	 */
	function addAction( action, callback, priority, context ) {
		if ( 'string' === typeof action && 'function' === typeof callback ) {
			priority = parseInt( ( priority || 10 ), 10 );
			_addHook( 'actions', action, callback, priority, context );
		}

		return MethodsAvailable;
	}

	/**
	 * Performs an action if it exists. You can pass as many arguments as you want to this function; the only rule is
	 * that the first argument must always be the action.
	 */
	function doAction( /* action, arg1, arg2, ... */ ) {
		var args = slice.call( arguments );
		var action = args.shift();

		if ( 'string' === typeof action ) {
			_runHook( 'actions', action, args );
		}

		return MethodsAvailable;
	}

	/**
	 * Removes the specified action if it contains a namespace.identifier & exists.
	 *
	 * @param action The action to remove
	 * @param [callback] Callback function to remove
	 */
	function removeAction( action, callback ) {
		if ( 'string' === typeof action ) {
			_removeHook( 'actions', action, callback );
		}

		return MethodsAvailable;
	}

	/**
	 * Adds a filter to the event manager.
	 *
	 * @param filter Must contain namespace.identifier
	 * @param callback Must be a valid callback function before this action is added
	 * @param [priority=10] Used to control when the function is executed in relation to other callbacks bound to the same hook
	 * @param [context] Supply a value to be used for this
	 */
	function addFilter( filter, callback, priority, context ) {
		if ( 'string' === typeof filter && 'function' === typeof callback ) {
			priority = parseInt( ( priority || 10 ), 10 );
			_addHook( 'filters', filter, callback, priority, context );
		}

		return MethodsAvailable;
	}

	/**
	 * Performs a filter if it exists. You should only ever pass 1 argument to be filtered. The only rule is that
	 * the first argument must always be the filter.
	 */
	function applyFilters( /* filter, filtered arg, arg2, ... */ ) {
		var args = slice.call( arguments );
		var filter = args.shift();

		if ( 'string' === typeof filter ) {
			return _runHook( 'filters', filter, args );
		}

		return MethodsAvailable;
	}

	/**
	 * Removes the specified filter if it contains a namespace.identifier & exists.
	 *
	 * @param filter The action to remove
	 * @param [callback] Callback function to remove
	 */
	function removeFilter( filter, callback ) {
		if ( 'string' === typeof filter ) {
			_removeHook( 'filters', filter, callback );
		}

		return MethodsAvailable;
	}

	/**
	 * Maintain a reference to the object scope so our public methods never get confusing.
	 */
	MethodsAvailable = {
		removeFilter: removeFilter,
		applyFilters: applyFilters,
		addFilter: addFilter,
		removeAction: removeAction,
		doAction: doAction,
		addAction: addAction
	};

	// return all of the publicly available methods
	return MethodsAvailable;
};

module.exports = EventManager;

},{}],19:[function(require,module,exports){
var Module = function() {
	var $ = jQuery,
		instanceParams = arguments,
		self = this,
		settings,
		events = {};

	var ensureClosureMethods = function() {
		$.each( self, function( methodName ) {
			var oldMethod = self[ methodName ];

			if ( 'function' !== typeof oldMethod ) {
				return;
			}

			self[ methodName ] = function() {
				return oldMethod.apply( self, arguments );
			};
		});
	};

	var initSettings = function() {
		settings = self.getDefaultSettings();

		var instanceSettings = instanceParams[0];

		if ( instanceSettings ) {
			$.extend( settings, instanceSettings );
		}
	};

	var init = function() {
		self.__construct.apply( self, instanceParams );

		ensureClosureMethods();

		initSettings();

		self.trigger( 'init' );
	};

	this.getItems = function( items, itemKey ) {
		if ( itemKey ) {
			var keyStack = itemKey.split( '.' ),
				currentKey = keyStack.splice( 0, 1 );

			if ( ! keyStack.length ) {
				return items[ currentKey ];
			}

			if ( ! items[ currentKey ] ) {
				return;
			}

			return this.getItems(  items[ currentKey ], keyStack.join( '.' ) );
		}

		return items;
	};

	this.getSettings = function( setting ) {
		return this.getItems( settings, setting );
	};

	this.setSettings = function( settingKey, value, settingsContainer ) {
		if ( ! settingsContainer ) {
			settingsContainer = settings;
		}

		if ( 'object' === typeof settingKey ) {
			$.extend( settingsContainer, settingKey );

			return self;
		}

		var keyStack = settingKey.split( '.' ),
			currentKey = keyStack.splice( 0, 1 );

		if ( ! keyStack.length ) {
			settingsContainer[ currentKey ] = value;

			return self;
		}

		if ( ! settingsContainer[ currentKey ] ) {
			settingsContainer[ currentKey ] = {};
		}

		return self.setSettings( keyStack.join( '.' ), value, settingsContainer[ currentKey ] );
	};

	this.on = function( eventName, callback ) {
		if ( ! events[ eventName ] ) {
			events[ eventName ] = [];
		}

		events[ eventName ].push( callback );

		return self;
	};

	this.off = function( eventName, callback ) {
		if ( ! events[ eventName ] ) {
			return self;
		}

		if ( ! callback ) {
			delete events[ eventName ];

			return self;
		}

		var callbackIndex = events[ eventName ].indexOf( callback );

		if ( -1 !== callbackIndex ) {
			delete events[ eventName ][ callbackIndex ];
		}

		return self;
	};

	this.trigger = function( eventName ) {
		var methodName = 'on' + eventName[ 0 ].toUpperCase() + eventName.slice( 1 ),
			params = Array.prototype.slice.call( arguments, 1 );

		if ( self[ methodName ] ) {
			self[ methodName ].apply( self, params );
		}

		var callbacks = events[ eventName ];

		if ( ! callbacks ) {
			return;
		}

		$.each( callbacks, function( index, callback ) {
			callback.apply( self, params );
		} );
	};

	init();
};

Module.prototype.__construct = function() {};

Module.prototype.getDefaultSettings = function() {
	return {};
};

Module.extend = function( properties ) {
	var $ = jQuery,
		parent = this;

	var child = function() {
		return parent.apply( this, arguments );
	};

	$.extend( child, parent );

	child.prototype = Object.create( $.extend( {}, parent.prototype, properties ) );

	child.prototype.constructor = child;

	child.__super__ = parent.prototype;

	return child;
};

module.exports = Module;

},{}],20:[function(require,module,exports){
var Module = require( './module' ),
	ViewModule;

ViewModule = Module.extend( {
	elements: null,

	getDefaultElements: function() {
		return {};
	},

	bindEvents: function() {},

	onInit: function() {
		this.initElements();

		this.bindEvents();
	},

	initElements: function() {
		this.elements = this.getDefaultElements();
	}
} );

module.exports = ViewModule;

},{"./module":19}],21:[function(require,module,exports){
var WidgetAjaxForm = (function(c){return(c.constructor.prototype=c).constructor})({
	constructor: function WidgetAjaxForm( $element ) {
		this.$element = $element;

		this.settings = {
			selectors: {
				form: 'form',
				submitButton: '[type="submit"]'
			}
		};

		this.elements = {};
		this.elements.$form = this.$element.find( this.settings.selectors.form );
		this.elements.$submitButton = this.elements.$form.find( this.settings.selectors.submitButton );

		this.bindEvents();
	},

	bindEvents: function() {
		this.elements.$form.on( 'submit', $.proxy( this, 'handleSubmit' ) );
	},

	beforeSend: function() {
		var $form = this.elements.$form;

		$form
			.animate( { opacity: '0.45' }, 500 )
			.addClass( 'elementor-form-waiting' )
		;
		$form.find( '.elementor-message' ).remove();
		$form.find( '.elementor-error' ).removeClass( 'elementor-error' );

		$form
			.find( 'div.elementor-field-group' )
			.removeClass( 'error' )
			.find( 'span.elementor-form-help-inline' )
			.remove()
			.end()
			.find( ':input' ).attr( 'aria-invalid', 'false' )
		;
		this.elements.$submitButton
			.attr( 'disabled', 'disabled' )
			.find( '> span' )
			.prepend( '<span class="elementor-button-text elementor-form-spinner"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>' )
		;
	},

	getFormData: function() {
		var formData = new FormData( this.elements.$form[0] );
		formData.append(
			this.elements.$submitButton[0].name,
			this.elements.$submitButton[0].value
		);
		return formData;
	},

	onSuccess: function( response, status ) {
		var $form = this.elements.$form,
			insertMethod = $form.data( 'msg' ) === 'before' ? 'prepend' : 'append';

		this.elements.$submitButton
			.removeAttr( 'disabled' )
			.find( '.elementor-form-spinner' )
			.remove()
		;
		$form
			.animate( { opacity: '1' }, 100 )
			.removeClass( 'elementor-form-waiting' )
		;

		if ( response.success ) {
			var message = $form.data( 'success' ) || response.success;

			$form.trigger( 'submit_success', response );
			$form.trigger( 'form_destruct', response );
			$form.trigger( 'reset' );

			$form[insertMethod]( '<div class="elementor-message elementor-message-success" role="alert">' + message + '</div>' );
		} else {
			var message = $form.data( 'error' ) || response.errors && response.errors.join( '<br>' ) || 'Unknown error';

			$form[insertMethod]( '<div class="elementor-message elementor-message-danger" role="alert">' + message + '</div>' );
		}
	},

	onError: function( xhr, desc ) {
		var $form = this.elements.$form;

		$form.append( '<div class="elementor-message elementor-message-danger" role="alert">' + desc + '</div>' );

		this.elements.$submitButton
			.html( this.elements.$submitButton.text() )
			.removeAttr( 'disabled' )
		;
		$form
			.animate( {
				opacity: '1'
			}, 100 )
			.removeClass( 'elementor-form-waiting' )
		;
		$form.trigger( 'error' );
	},

	handleSubmit: function( event ) {
		var $form = this.elements.$form;

		event.preventDefault();

		if ( $form.hasClass( 'elementor-form-waiting' ) ) {
			return false;
		}

		this.beforeSend();

		$.ajax( {
			url: $form.attr('action'),
			type: 'POST',
			dataType: 'json',
			data: this.getFormData(),
			processData: false,
			contentType: false,
			success: $.proxy( this, 'onSuccess' ),
			error: $.proxy( this, 'onError' )
		} );
	}
});

module.exports = function( $scope, $ ) {
	new WidgetAjaxForm( $scope );
};

},{}],22:[function(require,module,exports){
var Countdown = function( $countdown, endTime, actions, $expireMessage, $ ) {
	var timeInterval,
		elements = {
			$daysSpan: $countdown.find( '.elementor-countdown-days' ),
			$hoursSpan: $countdown.find( '.elementor-countdown-hours' ),
			$minutesSpan: $countdown.find( '.elementor-countdown-minutes' ),
			$secondsSpan: $countdown.find( '.elementor-countdown-seconds' )
		};

	var updateClock = function() {
		var timeRemaining = Countdown.getTimeRemaining( endTime );

		$.each( timeRemaining.parts, function( timePart ) {
			var $element = elements[ '$' + timePart + 'Span' ],
				partValue = this.toString();

			if ( 1 === partValue.length ) {
				partValue = 0 + partValue;
			}

			if ( $element.length ) {
				$element.text( partValue );
			}
		} );

		if ( timeRemaining.total <= 0 ) {
			clearInterval( timeInterval );
			runActions();
		}
	};

	var initializeClock = function() {
		timeInterval = setInterval( updateClock, 1000 );

		updateClock();
	};

	var runActions = function() {
		$countdown.trigger( 'countdown_expire', $countdown );

		if ( !actions ) {
			return;
		}

		actions.forEach( function(action) {
			switch ( action.type ) {
				case 'hide':
					$countdown.hide();
					break;

				case 'redirect':
					if ( action.redirect_url && !ceFrontend.isEditMode() ) {
						action.redirect_is_external
							? window.open(action.redirect_url)
							: window.location.href = action.redirect_url
						;
					}
					break;

				case 'message':
					$expireMessage.show();
					break;
			}
		} );
	};

	initializeClock();
};

Countdown.getTimeRemaining = function( endTime ) {
	var timeRemaining = endTime - new Date(),
		seconds = Math.floor( ( timeRemaining / 1000 ) % 60 ),
		minutes = Math.floor( ( timeRemaining / 1000 / 60 ) % 60 ),
		hours = Math.floor( ( timeRemaining / ( 1000 * 60 * 60 ) ) % 24 ),
		days = Math.floor( timeRemaining / ( 1000 * 60 * 60 * 24 ) );

	if ( days < 0 || hours < 0 || minutes < 0 ) {
		seconds = minutes = hours = days = 0;
	}

	return {
		total: timeRemaining,
		parts: {
			days: days,
			hours: hours,
			minutes: minutes,
			seconds: seconds
		}
	};
};

module.exports = function( $scope, $ ) {
	var $element = $scope.find( '.elementor-countdown-wrapper' ),
		date = new Date( $element.data( 'date' ) * 1000 ),
		actions = $element.data( 'expire-actions' ),
		$expireMessage = $scope.find( '.elementor-countdown-expire--message' );

	new Countdown( $element, date, actions, $expireMessage, $ );
};

},{}]},{},[2]);

// Quick View
$('html').on('click.ce', '.elementor-quick-view', function(e) {
	e.preventDefault();

	if (window.prestashop && prestashop.emit) {
		prestashop.emit('clickQuickView', {
			dataset: $(this).closest('.elementor-product-miniature').data()
		});
	} else {
		this.rel = $(this).addClass('quick-view').closest('a').prop('href');
	}
});

$(function ceReady() {
	// Fix for category description
	$('#js-product-list-header').attr('id', 'product-list-header');

	// AJAX Search
	var $searchWidget = $('.elementor-ajax-search'),
		$searchBox = $searchWidget.find('input[type=search]'),
		searchURL = $searchWidget.attr('action'),
		baseDir = window.baseDir || prestashop.urls.base_url,
		require = $.ui ? ($.ui.autocomplete ? '' : 'jquery.ui.autocomplete.min') : 'jquery-ui.min';

	if ($searchWidget.length) {
		!require ? initSearch() : $searchBox.one('focus.ce', function loadAutocomplete() {
			$('<link rel="stylesheet" type="text/css">').attr({
				href: baseDir + 'js/jquery/ui/themes/base/minified/' + require + '.css'
			}).appendTo(document.head);

			if ('jquery-ui.min' === require) {
				$('<link rel="stylesheet" type="text/css">').attr({
					href: baseDir + 'js/jquery/ui/themes/base/minified/jquery.ui.theme.min.css'
				}).appendTo(document.head);
			}
			$.ajax({
				url: baseDir + 'js/jquery/ui/' + require + '.js',
				cache: true,
				dataType: 'script',
				success: initSearch
			});
		});
	}

	function initSearch() {
		$.widget('prestashop.ceBlockSearchAutocomplete', $.ui.autocomplete, {
			_renderItem: function (ul, product) {
				return $("<li>")
					.append($("<a>")
						.append($("<span>").html(product.category_name).addClass("category"))
						.append($("<span>").html(product.name).addClass("product"))
					).appendTo(ul)
				;
			}
		});

		$searchBox.ceBlockSearchAutocomplete({
			source: function(query, response) {
				$.post(searchURL, {
					s: query.term,
					resultsPerPage: 10
				}, null, 'json').then(function(resp) {
					response(resp.products);
				}).fail(response);
			},
			select: function(event, ui) {
				var url = ui.item.url;
				window.location.href = url;
			},
		});
	}
});
