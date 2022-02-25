$.fn.animateNumber = function(to, time, dec) {
	dec = dec!==0 && !dec ? 0 : dec;
	let $this = $(this);
	let start = $this.text();
	start = n = Number.parseFloat(start);
	to = Number.parseFloat(to);
	let i = (to - start) * 10 / time;
	if (!i) return true;
	let idTimer = setInterval(function(){
		n += i;
		if ((i>0 && n>to) || (i<0 && n<to)) {
			n = to;
			clearInterval(idTimer);
		}
		let str = String(n).split('.');
		str[1] = str[1] ? str[1].padEnd(dec, '0').substr(0, dec) : ''.padEnd(dec, '0');
		
		str = dec ? str.join('.') : str[0];
		
		$this.text(str);
	}, 10);
}