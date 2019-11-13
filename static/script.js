jQuery(document).ready(function($) {

	console.log('jQuery init');

// fullpage customization
	$('#fullpage').fullpage({
		onLeave: onLeave,
		afterLoad: afterLoad,
		scrollingSpeed: 2000,
		paddingTop: "4.5rem"
	});



	var dotsContainer = document.querySelector(".dots");
	var dots = dotsContainer.querySelectorAll(".dot");

	function onLeave(o, d, dir) {
		console.log('On leave function Run start\n');
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
		console.log('On leave function Run end\n');
	}

	function afterLoad(o, d, dir) {
		console.log('After Load function Run start\n');
		if(d.isLast) {
			fullpage_api.destroy();
			window.scrollTo(0, 0);
		}
		// if (dir === null) {
		//   fullpage_api.moveTo(1);
		//   // console.log(fullpage_api);

		// }
		console.log('After Load function Run end\n');
	}

	for (var i = 0; i < dots.length; i++) {
		var dot = dots[i];
		dot.addEventListener("click", function (e) {
			if(!dot.classList.contains("active")) {
				fullpage_api.moveTo(i + 1);
			}
		});
	}

	function playVid(video) {
		console.log('Play Video function Run start\n');
		freeze();
		video.addEventListener("ended", unfreeze);
		video.play();
		console.log('Play Video function Run end\n');
	}

	function fadeBetween(prevVid, vid) {
		console.log('Fade Between function Run start\n');
		freeze();
		setTimeout(function () {
			prevVid.classList.add("fade");
			vid.classList.add("active");
			prevVid.classList.remove("active");
			setTimeout(() => {
				prevVid.classList.remove("fade");
				prevVid.currentTime = 0;
				unfreeze();
			}, 1500);
		}, 500);
		console.log('Fade Between function Run end\n');
	}

	function freeze() {
		console.log('Freeze function Run start\n');
		fullpage_api.setAllowScrolling(false);
		fullpage_api.setKeyboardScrolling(false);
		dotsContainer.classList.add("frozen");
		console.log('Freeze function Run end\n');
	}

	function unfreeze() {
		console.log('Unfreeze function Run start\n');
		fullpage_api.setAllowScrolling(true);
		fullpage_api.setKeyboardScrolling(true);
		dotsContainer.classList.remove("frozen");
		console.log('Unfreeze function Run end\n');
	}

});
