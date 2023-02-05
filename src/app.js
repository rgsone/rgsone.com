import 'bootstrap/dist/css/bootstrap-reboot.css';
import './styles/app.css';
import {registerWindowHeight} from './utils/window.js';
import {fontIsLoaded} from "./utils/font.js";

function launch() {

	// get and set viewport height css var
	registerWindowHeight();
	// wait for font loading
	fontIsLoaded();

	//const canvasElem = document.getElementById('canvas');
	//const canvasApp = new CanvasApp(canvasElem);

	// app is ready
	setTimeout(() => {
		document.documentElement.classList.add('ready');
	}, 1200);
}

////////////////////////////////////////////////////

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', launch);
} else {
	launch();
}
