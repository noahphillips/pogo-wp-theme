jQuery(document).ready(function ($) {

	// console.log('jQuery init');

// fullpage customization
	$('#fullpage').fullpage({
		onLeave: onLeave,
		afterLoad: afterLoad,
		scrollingSpeed: 2000,
		paddingTop: '4.5rem',
		licenseKey: 'EDE15887-A90149CB-BF02D47C-CCD9EDA8'
	});


	var dotsContainer = document.querySelector(".dots");
	var dots = dotsContainer.querySelectorAll(".dot");

	function onLeave(o, d, dir) {
		var prevVid = document.querySelector('video.active');

		if(Math.abs(o.index - d.index) === 1) {
			var vid = document.getElementById(
				(dir === "down" ? 'f' : 'r') + o.index
			);
			if(prevVid !== vid) {
				prevVid.classList.remove('active');
				vid.classList.add('active');
				prevVid.currentTime = 0;
			}
			playVid(vid);
		} else {
			var vid = document.getElementById(
				(dir === "down" ? 'r' : 'f') + d.index
			)
			fadeBetween(prevVid, vid);
		}
		dots[o.index].classList.remove("active");
		dots[d.index].classList.add("active");
	}

	function afterLoad(o, d, dir) {
		if(d.isLast) {
			fullpage_api.destroy();
			window.scrollTo(0, 0);
		}
		// if (dir === null) {
		//   fullpage_api.moveTo(1);
		//   // // console.log(fullpage_api);

		// }
	}




	for (var i = 0; i < dots.length; i++) {
		var dot = dots[i];
		dot.addEventListener('click', function (e) {
			var count = e.target.getAttribute('data-count');
			var currentDot = document.querySelector('#dot-' + count);

			if(!currentDot.classList.contains('active')) {
				fullpage_api.moveTo(count);
			}
		});
	}

	function playVid(video) {
		// console.log('Play Video function Run start\n');
		freeze();
		video.addEventListener("ended", unfreeze);
		video.play();
		// console.log('Play Video function Run end\n');
	}

	function fadeBetween(prevVid, vid) {
		// console.log('Fade Between function Run start\n');
		freeze();
		setTimeout(function () {
			prevVid.classList.add("fade");
			vid.classList.add("active");
			prevVid.classList.remove("active");
			setTimeout(function () {
				prevVid.classList.remove("fade");
				prevVid.currentTime = 0;
				unfreeze();
			}, 1500);
		}, 500);
		// console.log('Fade Between function Run end\n');
	}

	function freeze() {
		// console.log('Freeze function Run start\n');
		fullpage_api.setAllowScrolling(false);
		fullpage_api.setKeyboardScrolling(false);
		dotsContainer.classList.add("frozen");
		// console.log('Freeze function Run end\n');
	}

	function unfreeze() {
		// console.log('Unfreeze function Run start\n');
		fullpage_api.setAllowScrolling(true);
		fullpage_api.setKeyboardScrolling(true);
		dotsContainer.classList.remove("frozen");
		// console.log('Unfreeze function Run end\n');
	}

});
