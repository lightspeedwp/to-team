const gulp         = require('gulp');
const gettext      = require('gulp-gettext');
const jshint       = require('gulp-jshint');
const plumber      = require('gulp-plumber');
const rename       = require('gulp-rename');
const sort         = require('gulp-sort');
const uglify       = require('gulp-uglify');
const wppot        = require('gulp-wp-pot');

gulp.task('default', function() {
	console.log('Use the following commands');
	console.log('--------------------------');
	console.log('gulp wordpress-lang to compile the to-team.pot, to-team-en_EN.po and to-team-en_EN.mo');
});

gulp.task('wordpress-pot', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-team',
			package: 'to-team',
			bugReport: 'https://bitbucket.org/feedmycode/to-team',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/to-team.pot'))
});

gulp.task('wordpress-po', function() {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'to-team',
			package: 'to-team',
			bugReport: 'https://bitbucket.org/feedmycode/to-team',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/to-team-en_EN.po'))
});

gulp.task('wordpress-po-mo', ['wordpress-po'], function() {
	return gulp.src('languages/to-team-en_EN.po')
		.pipe(gettext())
		.pipe(gulp.dest('languages'))
});

gulp.task('wordpress-lang', (['wordpress-pot', 'wordpress-po-mo']));
