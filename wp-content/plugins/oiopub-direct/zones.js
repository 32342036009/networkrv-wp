(function(win, doc) {

	//set vars
	var dwData = '',
		adCache = [],
		serverUri = '',
		cssLoaded = false,
		zoneAttr = 'data-oio-zone',
		serverAttr = 'data-oio-uri',
		jsonObj = 'oio' + Math.floor(Math.random()*99999999);

	//init js
	var init = function() {
		//get elements
		var i, zn, zno,
			els = doc.querySelectorAll('*[' + zoneAttr + ']');
		//loop through array
		for(i=0; i < els.length; i++) {
			//get zone data
			if(zn = els[i].getAttribute(zoneAttr)) {
				zn += '&rand=' + Math.floor(Math.random()*99999999);
			}
			//parse query
			zno = parseQuery(zn);
			//valid zone?
			if(!zno || !zno.type || !zno.zone) {
				continue;
			}
			//add element
			zno.el = els[i];
			zno.qs = zn;
			zno.uri = els[i].getAttribute(serverAttr) || serverUri;
			//add to cache
			adCache.push(zno);
			//delete attr
			els[i].removeAttribute(zoneAttr);
		}
		//load ads?
		if(adCache.length) {
			loadAds();
		}
		//next loop
		setTimeout(init, 1000);
	};

	//load ads
	var loadAds = function() {
		//set vars
		var i, uri, ref = 0, queries = [];
		//loop through array
		for(i=0; i < adCache.length; i++) {
			//skip row?
			if(adCache[i].processing) {
				continue;
			}
			//set flag
			adCache[i].processing = true;
			//add query
			ref = adCache[i].ref || 0;
			uri = adCache[i].uri || serverUri;
			queries.push('queries[]=' + encodeURIComponent(adCache[i].qs + (adCache[i].refreshed ? '&refreshed=1' : '')));
		}
		//stop here?
		if(!queries.length) {
			return;
		}
		//set extras
		queries.push('rand=' + Math.floor(Math.random()*99999999));
		queries.push('cls=' + jsonObj);
		queries.push('ref=' + ref);
		//load script
		loadResource('script', uri + '?' + queries.join('&'), doc.body, function() {
			this.parentNode.removeChild(this);
		});
	};

	//execute json
	var execJson = function(data) {
		//set vars
		var cache = {};
		//add responses
		for(var i=0; i < data.length; i++) {
			//get cache
			for(var z=0; z < adCache.length; i++) {
				//match found?
				if(adCache[z].processing && adCache[z].qs == data[i].query) {
					cache = adCache[z];
					adCache.splice(z, 1);
					break;
				}
			}
			//stop here?
			if(!cache) {
				continue;
			}
			//create nodes from content
			var node = doc.createElement('div');
			node.innerHTML = data[i].content;
			//replace parent element
			var parent = doc.createElement('div');
			cache.el.parentNode.replaceChild(parent, cache.el);
			//write nodes to DOM
			writeNode(node.firstChild, parent);
			//set refresh?
			if(data[i].refresh > 0) {
				//calc timer
				var timer, ms = data[i].refresh * 1000;
				//check last time
				if(cache.lastTime) {
					timer = (cache.lastTime + ms) - Date.now();
				} else {
					timer = ms;
				}
				//is negative?
				while(timer < 0 && ms > 0) {
					timer = timer + ms;
				}
				//set timeout
				(function(qso, el, timer) {
					setTimeout(function() {
						qso.processing = false;
						qso.refreshed = 1;
						qso.el = el;
						qso.lastTime = Date.now();
						adCache.push(qso);
					}, timer);
				})(cache, parent, timer > 0 ? timer : 0);
			}
		}
	};

	//write node to DOM
	var writeNode = function(node, parent, isChild) {
		//valid node?
		if(!node || !parent) {
			return;
		}
		//set vars
		var clone, orgDw = [];
		//replace doc write?
		if(!isChild) {
			orgDw = [ doc.write, doc.writeln ];
			doc.write = function() { return docWrite([].slice.call(arguments, 0), ""); };
			doc.writeln = function() { return docWrite([].slice.call(arguments, 0), "\n"); };
		}
		//start loop
		while(node) {
			//script node?
			if(node.nodeName.toLowerCase() == 'script') {
				//execute script
				loadResource('script', node.src || node.text, parent, function() {
					//data to process?
					if(!dwData) return;
					//create node
					var node = doc.createElement('div');
					node.innerHTML = dwData;
					//clear data
					dwData = '';
					//write to DOM
					writeNode(node.firstChild, this.parentNode, true);
				});
			} else {
				//clone node
				clone = node.cloneNode(false);
				//add to parent node
				parent.appendChild(clone);
				//has children?
				if(node.firstChild) {
					if(node.getElementsByTagName('script').length) {
						writeNode(node.firstChild, clone);
					} else {
						clone.innerHTML = node.innerHTML;
					}
				}
			}
			//set next node
			node = node.nextSibling;
		}
		//restore DW?
		if(orgDw) {
			doc.write = orgDw[0];
			doc.writeln = orgDw[1];
		}
	};

	//document write replacement
	var docWrite = function(args, sep) {
		//convert to array?
		if(typeof args === 'string') {
			args = [ args ];
		}
		//loop through args
		while(args.length) {
			dwData += args.shift() + (sep || '');
		}
	};

	//load resource
	var loadResource = function(tag, content, parent, callback) {
		//set vars
		var el, attr,
			isUrl = content.indexOf('http') == 0 || content.indexOf('//') == 0;
		//is script?
		if(tag == 'script') {
			attr = isUrl ? 'src' : 'text';
		}
		//is style?
		if(tag == 'link' || tag == 'style') {
			tag = isUrl ? 'link' : 'style';
			attr = isUrl ? 'href' : 'innerHTML';
		}
		//create element
		el = doc.createElement(tag);
		//add attr
		el[attr] = content;
		//add rel?
		if(tag == 'link') {
			el['rel'] = 'stylesheet';
		}
		//onload callback
		if(callback && isUrl) {
			el.onload = function() { callback.call(el); };
		}
		//load resource
		parent.appendChild(el);
		//callback now?
		if(callback && !isUrl) {
			callback.call(el);
		}
	};

	//get server uri
	var serverUri = function() {
		//set vars
		var i, s = doc.getElementsByTagName('script');
		//loop through scripts
		for(i=0; i < s.length; i++) {
			//match found?
			if(s[i].src && s[i].src.indexOf('/zones.js') >= 0) {
				return s[i].src.replace('/zones.js', '/js_http.php');
			}
		}
		//not found
		return null;
    };

	//parse query string
	var parseQuery = function(qstr) {
		var query = {};
		var a = qstr.split('&');
		for(var i = 0; i < a.length; i++) {
			var b = a[i].split('=');
			query[decodeURIComponent(b[0])] = decodeURIComponent(b[1] || '');
		}
		return query;
	};

	//already loaded?
	if(win['oiopub'] && win['oiopub']['__loaded']) {
		return;
	}

	//set flag
	win['oiopub'] = {
		__loaded: true
	};

	//json obj
	win[jsonObj] = {
		json: function(data) {
			//skip execution?
			if(!data || !data.length) {
				return;
			}
			//css to load?
			if(!cssLoaded && data[0].css) {
				//update flag
				cssLoaded = true;
				//css file already exists?
				if(!doc.querySelector('link[href*="/images/style/output.css"]')) {
					//load css
					loadResource('style', data[0].css, doc.body, function() {
						execJson(data);
					});
					//stop
					return;
				}
			}
			//execute
			execJson(data);
		}
	};

	//set server uri
	serverUri = serverUri();

	//check dom
	if(/complete|interactive|loaded/.test(doc.readyState)) {
		init();
	} else {
		doc.addEventListener('DOMContentLoaded', init, false);
	}

})(window, document);