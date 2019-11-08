//
// new Fullpage("#fullpage", {
// 	onLeave: onLeave,
// 	afterLoad: afterLoad,
// 	scrollingSpeed: 2000,
// 	paddingTop: "4.5rem"
// });


// fullpage customization
jQuery('#fullpage').fullpage({
	onLeave: onLeave,
	afterLoad: afterLoad,
	scrollingSpeed: 2000,
	paddingTop: "4.5rem"
});


let dotsContainer = document.querySelector(".dots");
let dots = dotsContainer.querySelectorAll(".dot");

function onLeave(o, d, dir) {
	let prevVid = document.querySelector('video.active');

	if(Math.abs(o.index - d.index) === 1) {
		let vid = document.getElementById(
			(dir === "down" ? 'f' : 'r') + o.index
		);
		if(prevVid !== vid) {
			prevVid.classList.remove('active');
			vid.classList.add('active');
			prevVid.currentTime = 0;
		}
		playVid(vid);
	} else {
		let vid = document.getElementById(
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
	//   // console.log(fullpage_api);

	// }
}

for (let i = 0; i < dots.length; i++) {
	let dot = dots[i];
	dot.addEventListener("click", e => {
		if(!dot.classList.contains("active")) {
			fullpage_api.moveTo(i + 1);
		}
	});
}

function playVid(video) {
	console.log('Play vid');
	freeze();
	video.addEventListener("ended", unfreeze);
	video.play();
}

function fadeBetween(prevVid, vid) {
	freeze();
	setTimeout(() => {
		prevVid.classList.add("fade");
		vid.classList.add("active");
		prevVid.classList.remove("active");
		setTimeout(() => {
			prevVid.classList.remove("fade");
			prevVid.currentTime = 0;
			unfreeze();
		}, 1500);
	}, 500);
}

function freeze() {
	fullpage_api.setAllowScrolling(false);
	fullpage_api.setKeyboardScrolling(false);
	dotsContainer.classList.add("frozen");
}

function unfreeze() {
	fullpage_api.setAllowScrolling(true);
	fullpage_api.setKeyboardScrolling(true);
	dotsContainer.classList.remove("frozen");
}
