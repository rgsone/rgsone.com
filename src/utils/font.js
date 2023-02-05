async function fontIsLoaded() {
	let loaded = await document.fonts.ready;
}

export { fontIsLoaded };
