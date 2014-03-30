/*! rgsone.com | @author rgsone | @url rgsone.com */
(function( global, doc, undefined ){

	"use strict";

	var app = {};
	var scrollDuration = 800;

	/* == PRIVATE ====== */

	/**
	 * @param opts
	 * 		duration: duration in ms,
	 *		delta: delta calcul function, ex: quintEaseOut,
	 *		step : function to call each step,
	 *		onComplete : function to call on complete
	 */
	var animate = function( opts )
	{
		var start = new Date();
		var rafId;

		function loop()
		{
			rafId = global.requestAnimationFrame( loop );

			var timePassed = new Date() - start;
			var progress = timePassed / opts.duration;

			if ( progress > 1 )
			{
				progress = 1;
			}

			var delta = opts.delta( progress );

			opts.step( delta );

			if ( progress === 1 )
			{
				global.cancelAnimationFrame( rafId );
				opts.onComplete();
			}
		}

		loop();
	};

	/**
	 * Tween function quintEaseOut
	 * @param k
	 * @returns {number}
	 */
	var quintEaseOut = function( k )
	{
		return 1 - ( --k * k * k * k );
	};

	/* == PUBLIC ====== */

	/**
	 * Check if DOM is loaded
	 * @param callback
	 */
	app.onReady = function( callback )
	{
		var isReady = false;

		function ready()
		{
			if ( isReady ) return;
			isReady = true;
			callback();
		}

		doc.onreadystatechange = function()
		{
			if ( this.readyState === "complete" )
			{
				doc.onreadystatechange = null;
				ready();
			}

		};
	};

	/**
	 * addEventListener for IE and others
	 * @param el
	 * @param eventName
	 * @param eventHandler
	 */
	app.bindEvent = function( elem, eventName, eventHandler )
	{
		if ( elem.addEventListener )
		{
			elem.addEventListener( eventName, eventHandler, false );
		}
		else if ( elel.attachEvent )
		{
			elem.attachEvent( 'on' + eventName, eventHandler );
		}
	};

	/**
	 * Scroll to anchor
	 * @param targetElem
	 * @param offsetCorrection
	 */
	app.scrollToAnchor = function( targetElem, offsetCorrection )
	{
		var offsetCorrect = offsetCorrection || 0;
		var targetYPos = this.getYPosFromTop( targetElem );
		var curScrollPos = global.pageYOffset || doc.documentElement.scrollTop;
		var distance = targetYPos - curScrollPos + offsetCorrect;

		animate({
			duration: scrollDuration,
			delta: quintEaseOut,
			step: function( delta )
			{
				global.scrollTo( 0, curScrollPos + delta * distance );
			},
			onComplete: function()
			{
				//global.location.hash = '#';
			}
		});
	};

	/**
	 * Return the elem Y position from top
	 * @param elem
	 * @returns {number}
	 */
	app.getYPosFromTop = function( elem )
	{
		if ( elem === undefined || elem === null )
		{
			return 0;
		}

		var topPos = elem.offsetTop,
			parent = elem.offsetParent;

		while ( parent != doc.body )
		{
			topPos += parent.offsetTop;
			parent = parent.offsetParent;
		}

		return ( topPos < 1 ) ? 1 : topPos;
	};

	/**
	 * Check if class exist
	 * @param elem
	 * @param className
	 * @returns {boolean}
	 */
	app.containsClass = function( elem, className )
	{
		return ( " " + elem.className + " " ).indexOf( " " + className + " " ) > -1;
	};

	//// bind to global ////

	global.rgsoneApp = app;

})( window, window.document );



///////////////// MAIN ////////////////////////////////////////////



rgsoneApp.onReady( function()
{
	var isHome = rgsoneApp.containsClass( document.querySelector( 'body' ), 'home' );

	if ( isHome )
	{
		var projectsSection = document.querySelector( '.projects' );
		var navSection = document.querySelector( '.home-nav' );
		var navSectionHeight = navSection.offsetHeight;
		var navSectionYPos = rgsoneApp.getYPosFromTop( projectsSection ) - navSectionHeight;

		var isFixed = false;

		function fixedNavLoop()
		{
			window.requestAnimationFrame( fixedNavLoop );

			if ( window.matchMedia && window.matchMedia( "(min-width: 540px)" ).matches )
			{
				var yScrollPos = window.pageYOffset || document.documentElement.scrollTop;
				navSectionHeight = navSection.offsetHeight;
				navSectionYPos = rgsoneApp.getYPosFromTop( projectsSection ) - navSectionHeight;

				if ( yScrollPos > navSectionYPos )
				{
					setFixedNavCss();
					isFixed = true;
				}
				else
				{
					resetFixedNavCss();
					isFixed = false;
				}
			}
			else if ( isFixed )
			{
				resetFixedNavCss();
			}
		}

		function setFixedNavCss()
		{
			navSection.style.position = "fixed";
			navSection.style.zIndex = "10";
			navSection.style.top = "0";
			navSection.style.right = "0";
			navSection.style.left = "0";

			projectsSection.style.marginTop = navSectionHeight + "px";
		}

		function resetFixedNavCss()
		{
			navSection.style.position = "static";
			navSection.style.zIndex = "auto";
			navSection.style.top = "0";
			navSection.style.right = "0";
			navSection.style.left = "0";

			projectsSection.style.marginTop = "0";
		}

		fixedNavLoop();
	}

	/* =================================== */

	var navLinks = document.querySelectorAll( 'a[href^="#"]' );

	Array.prototype.forEach.call( navLinks, function( elem, index, nodeList )
	{
		rgsoneApp.bindEvent( elem, "click", function( e )
		{
			var targetElemID = e.currentTarget.href.match( /#([a-zA-Z0-9_-]+)$/ )[ 1 ];
			var targetElem = document.getElementById( targetElemID );

			var offsetTreshold = 0;

			if ( window.matchMedia && window.matchMedia( "(min-width: 540px)" ).matches && navSectionHeight )
			{
				offsetTreshold = -navSectionHeight;
			}

			rgsoneApp.scrollToAnchor( targetElem, offsetTreshold );

			document.activeElement.blur();
			e.preventDefault();
		});
	});
});
