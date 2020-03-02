( function () {
    // infinite-load starts
    var sentinel = document.getElementById( 'sentinel' );
    if ( sentinel ) {
	var offset = 2;
	var content = document.getElementById( 'content' );
	var spinner = document.getElementById( 'spinner' );
	var working = false;
	var monitorOffset = 500; /*px*/
	var noMorePosts = false;
	var hayStack = [ 1 ];

	function loadItems() {
	    var xhr = new XMLHttpRequest();
	    xhr.open( 'POST', postUrl, true );
	    xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
	    xhr.onload = function () {
		// Process our return data
		if ( xhr.status >= 200 && xhr.readyState == 4 ) {
		    // What do when the request is successful
		    var data = JSON.parse( xhr.responseText );
		    //var data = xhr.responseText;
		    if ( data.content ) {
			// Check if item isn't already in page (problem with double request)
			// https://stackoverflow.com/a/51286985
			if ( hayStack.indexOf( data.page ) === -1 ) {
			    // Append template to dom
			    content.insertAdjacentHTML( 'beforeend', data.content )
			    hayStack.push( data.page );
			    spinner.innerHTML = spinnerHTML;
			} else {
			    // Json response with duplicated data
			    spinner.innerHTML = rescrollMsg;
			}


			// If we use build-in bLazy - vanilla js lazyload
			if ( typeof bLazy !== 'undefined' ) {
			    bLazy.revalidate();
			}

		    } else if ( data.hasOwnProperty( 'noMoreContent' ) ) {
			// Return "no more posts" and stop request
			spinner.innerHTML = noMorePostsMsg;
			enoughIsEnough();
			return;
		    } else {
			console.log( unexpectedMsg )
			spinner.innerHTML = unexpectedMsg;
			enoughIsEnough();
			return;
		    }
		    offset += 1;
		    if ( !observerSupport() ) {
			// For older browsers limit new request for 2s
			setTimeout( function () {
			    working = false;
			}, 2000 )
		    }
		} else {
		    // What do when the request fails
		    console.log( 'AJAX request failed. Returned status is ' + xhr.status + '. That\'s all we know.' );
		}

	    };
	    var msg = {
		page: offset,
		action: 'digitalnomad_infinite_load',
		object: postTypeObject,
		what_kind: whatKind,
		nonce: ajaxNonce,
	    }

	    xhr.send( encodeObjectForPost( msg ) );
	}

	function scrollTrigger() {
	    if ( working == false ) {
		if ( window.pageYOffset + window.innerHeight + monitorOffset >= content.offsetHeight ) {
		    loadItems();
		    working = true;
		}
	    }
	    if ( noMorePosts === true ) {
		window.removeEventListener( 'scroll', scrollTrigger );
		window.removeEventListener( 'resize', scrollTrigger );
	    }
	}


	if ( observerSupport() ) {
	    // Create a new IntersectionObserver instance
	    var inOb = new IntersectionObserver( function ( entries ) {
		if ( entries[0].intersectionRatio <= 0 ) {
		    return;
		}
		loadItems();
	    } );
	    inOb.observe( sentinel );
	} else {
	    window.addEventListener( 'scroll', scrollTrigger );
	    window.addEventListener( 'resize', scrollTrigger );
	}

	// Detect browser support for IntersectionObserver
	function observerSupport() {
	    return ( 'IntersectionObserver' in window && 'IntersectionObserverEntry' in window && 'intersectionRatio' in window.IntersectionObserverEntry.prototype ) ? true : false;
	}
	// Encode object for POST AJAX request
	// https://blog.garstasio.com/you-dont-need-jquery/ajax/#url-encoding
	function encodeObjectForPost( object ) {
	    var encodedString = '';
	    for ( var prop in object ) {
		if ( object.hasOwnProperty( prop ) ) {
		    if ( encodedString.length > 0 ) {
			encodedString += '&';
		    }
		    encodedString += encodeURI( prop + '=' + object[prop] );
		}
	    }
	    return encodedString;
	}
	function enoughIsEnough() {
	    if ( observerSupport() ) {
		inOb.disconnect();
	    }
	    noMorePosts = true;
	}
    }
    // infinite-load ends
    // 
    // 
    // go-up starts
    var body = document.body, stickyHeaderTop = body.offsetTop + 300;
    window.onscroll = function () {
	window.pageYOffset > stickyHeaderTop ? body.classList.add( 'scrl' ) : body.classList.remove( 'scrl' )
    };
    // go-up ends
    // 
    // 
    // deferred-styles starts
    var loadDeferredStyles = function () {
	var addStylesNode = document.getElementById( 'deferred-styles' );
	var replacement = document.createElement( 'div' );
	replacement.innerHTML = addStylesNode.textContent;
	document.body.appendChild( replacement )
	addStylesNode.parentElement.removeChild( addStylesNode );
    };
    if ( window.addEventListener )
	window.addEventListener( "load", loadDeferredStyles, false );
    else if ( window.attachEvent )
	window.attachEvent( "onload", loadDeferredStyles );
    else
	window.onload = loadDeferredStyles;
    // deferred-styles ends
}() )