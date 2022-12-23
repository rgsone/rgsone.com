/** @type {import('vite').UserConfig} */
export default {

	//root: './assets',
	base: '/',

	server: {
		port: 3137,
		https: false,
		host: 'rgsone.local',
	},

	build: {
		//assetsDir: 'assets',
		//manifest: true,
		//outDir: 'dist',
		//emptyOutDir: true,
		//cssCodeSplit: false,
		rollupOptions: {
			output: { manualChunks: undefined /* empêche la création du fichier vendor */ },
			// app entry
			//input: './assets/app.js'
		}
	}

};
