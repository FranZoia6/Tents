class Utils {
	static createElement(tag, options) {
		const element = document.createElement(tag);

		for (const option in options) {
			element[option] = options[option];
		}

		return element;
	}

	static addLink({ id, href, type, rel }, fnCallback) {
		// Gets the Link
		let link = document.getElementById(id);
		// If the Link already exists, then execute the callback
		if (link) return fnCallback();
		// Otherwise create the Link
		link = Utils.createElement('link', { id });
		link.setAttribute("type",type);
		link.setAttribute("rel", rel);
		link.setAttribute("href", href);
		// After the Link loads, execute the code
		link.onload = fnCallback;
		// Append the script
		document.head.append(link);
	}

	static addScript({ id, src }, fnCallback) {
		// Gets the Script
		let script = document.getElementById(id);
		// If the Script already exists, then execute the callback
		if (script) return fnCallback();
		// Otherwise create the script
		script = Utils.createElement('script', { id });
		// Sets the scr
		script.setAttribute("src", src);
		// After the Script loads, execute the code
		script.onload = fnCallback;
		// Append the script
		document.head.append(script);
	}

	static makeAjaxCall({ method, url, body, callback } = {}) {
		let http_request;
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			http_request = new XMLHttpRequest();
		} else if (window.ActiveXObject) { // IE
			http_request = new ActiveXObject("Microsoft.XMLHTTP");
		}

		http_request = new XMLHttpRequest();

		if (callback) {

			http_request.onload = (event) => {
				callback(JSON.parse(event.target.responseText));
			}
		}

		http_request.open(method, url, true);

		if (body) {
			http_request.setRequestHeader('Content-type', 'application/json; charset=UTF-8');
			http_request.send(JSON.stringify(body));
		} else {
			http_request.send();
		}
	}
}