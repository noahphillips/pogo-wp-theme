jQuery(document).ready(function ($) {
	var body = document.querySelector('body');
	var dotsContainer = document.querySelector('.dots');
	var dots = dotsContainer.querySelectorAll('.dot');

	$('video').each(function () {
		this.preload = 'metadata';
		this.load();
	});

	// fullpage customization
	$('#fullpage').fullpage({
		onLeave: onLeave,
		afterLoad: afterLoad,
		scrollingSpeed: 2000,
		paddingTop: '4.5rem',
		licenseKey: 'EDE15887-A90149CB-BF02D47C-CCD9EDA8'
	});


	for (var i = 0; i < dots.length; i++) {
		var dot = dots[i];
		dot.addEventListener('click', function (e) {
			var count = e.target.getAttribute('data-count');
			var currentDot = document.querySelector('#dot-' + count);
			var fullPageWrapper = document.querySelector('.fullpage-wrapper');

			// if(!fullPageWrapper.classList.contains('fp-destroyed')) { // Put back
			if(!currentDot.classList.contains('active') || !body.classList.contains('ie11')) {
				fullpage_api.moveTo(count);
			}
			// }
		});
	}


	function onLeave(o, d, dir) {
		console.log('onLeave function');
		var prevVid = document.querySelector('video.active');

		if(Math.abs(o.index - d.index) === 1) {
			var vid = document.getElementById(
				(dir === 'down' ? 'f' : 'r') + o.index
			);
			if(prevVid !== vid) {
				prevVid.classList.remove('active');
				vid.classList.add('active');
				prevVid.currentTime = 0;
			}
			playVid(vid);
		} else {
			var vid = document.getElementById(
				(dir === 'down' ? 'r' : 'f') + d.index
			)
			fadeBetween(prevVid, vid);
		}
		dots[o.index].classList.remove('active');
		dots[d.index].classList.add('active');
	}

	function afterLoad(o, d, dir) {
		console.log('After Load');
		if(d.isLast) {
			fullpage_api.destroy();
			window.scrollTo(0, 0);
		}
	}

	function playVid(video) {
		console.log('Play Vid');
		freeze();
		video.addEventListener('ended', unfreeze);
		video.play();
	}

	function fadeBetween(prevVid, vid) {
		console.log('Fade Between');
		freeze();
		setTimeout(function () {
			prevVid.classList.add('fade');
			vid.classList.add('active');
			prevVid.classList.remove('active');
			setTimeout(function () {
				prevVid.classList.remove('fade');
				prevVid.currentTime = 0;
				unfreeze();
			}, 1500);
		}, 500);
	}

	function freeze() {
		console.log('Freeze function');
		fullpage_api.setAllowScrolling(false);
		fullpage_api.setKeyboardScrolling(false);
		dotsContainer.classList.add('frozen');
	}

	function unfreeze() {
		fullpage_api.setAllowScrolling(true);
		fullpage_api.setKeyboardScrolling(true);
		dotsContainer.classList.remove('frozen');
	}

});



