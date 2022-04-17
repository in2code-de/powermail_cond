/* jshint node: true */
'use strict';

const { src, dest, watch, series, parallel } = require('gulp');
const rollup = require('rollup').rollup;
const rollupConfig = require('./rollup.config');

const project = {
	base: __dirname + '/../../Public',
	css: __dirname + '/../../Public/Css',
	js: __dirname + '/../../Public/JavaScript',
	images: __dirname + '/../../Public/Images'
};

function js(done) {
  rollup(rollupConfig).then(bundle => {
    rollupConfig.output.plugins = rollupConfig
    bundle.write(rollupConfig.output).then(() => done());
  });
};

// "npm run build"
const build = series(js);

// "npm run watch"
const def = parallel(
  function watchJS() { return watch(__dirname + '/JavaScript/*.js', series(js)) }
);

module.exports = {
  default: def,
  build,
  js
};
