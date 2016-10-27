var gulp = require('gulp');

gulp.task('default', function() {	 
	console.log('Use the following commands');
	console.log('--------------------------');
	console.log('gulp js				to compile the to-team.js to to-team.min.js');
	console.log('gulp compile-js		to compile both JS files above');
	console.log('gulp watch				to continue watching all files for changes, and build when changed');
	console.log('gulp wordpress-pot		to compile the lsx-mega-menus.pot');
	console.log('gulp reload-node-js	Copy over the .js files from teh various node modules');
});

var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var sort = require('gulp-sort');
var wppot = require('gulp-wp-pot');

gulp.task('js', function () {
	gulp.src('assets/js/to-team.js')
		.pipe(concat('to-team.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('assets/js'));
});
gulp.task('compile-js', (['js']));

gulp.task('watch', function() {
	gulp.watch('assets/js/to-team.js', ['js']);
});

gulp.task('wordpress-pot', function () {
	gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-team',
			destFile: 'to-team.pot',
			package: 'to-team',
			bugReport: 'https://www.lsdev.biz/product/tour-operator-team/issues',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages'));
});