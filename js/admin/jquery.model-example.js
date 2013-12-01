/**
 * testing the modular approach to js
 */
var Model_Example = function (options) {
	var globalfun = 'hello';
	this.hello = 'default';
	this.options = options;
};


Model_Example.prototype.getGlobalfun = function(value) {
	return globalfun;
};


Model_Example.prototype.setHello = function(value) {
	return this.hello = value;
};


Model_Example.prototype.getHello = function() {
	return this.hello;
};


Model_Example.prototype.getOptions = function() {
	return this.options;
};


Model_Example.prototype.setEvent = function() {
};


modelExample = new Model_Example({
	option1: true,
	option2: false
});
