function registerWindowHeight()
{
	let windowHeight = window.innerHeight;

	document.documentElement.style.setProperty('--vh', `${window.innerHeight * 0.01}px`);
	document.documentElement.style.setProperty('--windowHeight', `${window.innerHeight}px`);

	window.addEventListener('resize', () => {

		if (windowHeight === window.innerHeight) return;

		windowHeight = window.innerHeight;
		document.documentElement.style.setProperty('--vh', `${window.innerHeight * 0.01}px`);
		document.documentElement.style.setProperty('--windowHeight', `${window.innerHeight}px`);

	});
}

/**
 * Returns viewport width and height,
 * including scrollbars if exists
 */
function viewportFullSizes() {
	return {
		width: Math.max(document.documentElement.clientWidth, window.innerWidth),
		height: Math.max(document.documentElement.clientHeight, window.innerHeight),
	}
}

/**
 * Returns viewport width and height,
 * exluding scrollbars if exists
 */
function viewportSizes() {
	return {
		width: Math.floor(document.documentElement.getBoundingClientRect().width),
		height: Math.floor(document.documentElement.getBoundingClientRect().height),
	}
}

export { registerWindowHeight, viewportSizes, viewportFullSizes };
