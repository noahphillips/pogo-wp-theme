jQuery(document).ready(function ($) {
	var body = document.querySelector('body');
	var dotsContainer = document.querySelector('.dots');
	var dots = dotsContainer.querySelectorAll('.dot');

	$('video').each(function () {
		this.preload = 'metadata';
		this.load();
	});

	var videoPlayer = document.getElementById('f0');

	videoPlayer.addEventListener('ended', function () {
		this.pause();
	}, false);


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


	function onLeave(current, next, dir) {
		var prevVid = document.querySelector('video.active');
		// console.log('Current index: ', current.index);
		// console.log('Next index: ', next.index);
		// console.log('Direction: ', dir);

		if(Math.abs(current.index - next.index) === 1) {
			// console.log('Math === 1');

			if(current.index === 0) {

				if(dir === 'down') {
					var vid = document.getElementById('f1');
				} else {
					var vid = document.getElementById('r0');
				}

			} else if(current.index === 1) {
				if(dir === 'down') {
					var vid = document.getElementById('f2');
				} else {
					var vid = document.getElementById('r1');
				}
			} else if(current.index === 2) {
				if(dir === 'down') {
					var vid = document.getElementById('f3');
				} else {
					var vid = document.getElementById('r2');
				}
			} else if(current.index === 3) {
				if(dir === 'down') {
					var vid = document.getElementById('f3');
				} else {
					var vid = document.getElementById('r3');
				}
			}

			if(prevVid !== vid) {
				prevVid.classList.remove('active');
				vid.classList.add('active');
				prevVid.currentTime = 0;
			}
			playVid(vid);
		} else {
			// console.log('Math NOT !== 1');

			var vid = document.getElementById(
				(dir === 'down' ? 'r' : 'f') + next.index
			)
			fadeBetween(prevVid, vid);
		}

		dots[current.index].classList.remove('active');
		dots[next.index].classList.add('active');
	}

	function afterLoad(o, d, dir) {
		if(d.isLast) {
			fullpage_api.destroy();
			window.scrollTo(0, 0);
		}
	}

	function playVid(video) {
		freeze();
		video.addEventListener('ended', unfreeze);
		video.play();
	}

	function fadeBetween(prevVid, vid) {
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



