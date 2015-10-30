var gulp = require('gulp'),
    stylus = require('gulp-stylus')
    coffee = require('gulp-coffee'), 
		rename = require('gulp-rename'),
		autoprefixer = require('gulp-autoprefixer'),
		minifyCSS = require('gulp-minify-css'),
		uglify = require('gulp-uglify'),
		gutil = require('gulp-util'),
		concat = require('gulp-concat'),
		chalk = require('chalk');

handleError = function(err){
  gutil.log(
    "\n\n-----------------\n",
    chalk.bold.red(err.name),
    "\n-----------------\n",
    err.message
  );

  gutil.beep();
}

gulp.task('stylus', function() {

	return gulp
		.src('stylus/main.styl')
    .pipe(stylus({
      'include css': true,
      'compress': true,
      'linenos': true
  	}))
    .on('error', handleError)
    .pipe(autoprefixer({browsers: ['last 15 versions']}))
		.pipe(minifyCSS())
		.pipe(rename(function (path) {
			path.dirname = '';
			path.basename = '_';
			path.extname = '.css'
		}))
		.pipe(gulp.dest('public'));

});

gulp.task('coffee', function() {

	return gulp
		.src('coffee/main.coffee')
	  .pipe(coffee({
	    //bare: true
	  }))
		.on('error', handleError)
		.pipe(rename(function (path) {
			path.dirname = '';
			path.basename = '_';
			path.extname = '.js'
		}))
		.pipe(gulp.dest('tmp'));

});

gulp.task('js', function() {

	return gulp
		.src([
			'bower/jquery/dist/jquery.js',
			'bower/Materialize/dist/js/materialize.js',
			'bower/moment/min/moment-with-locales.min.js',
			'tmp/_.js'
		])
		.pipe(concat('_.js'))
		.pipe(uglify())
		.pipe(gulp.dest('public'));;

});

gulp.task('watch', function(){
  gulp.watch('coffee/*.coffee', ['coffee' , 'js']);
  gulp.watch('stylus/*.styl', ['stylus']);
});

gulp.task('default', ['coffee', 'stylus', 'js', 'watch']);
gulp.task('dist', ['coffee', 'stylus', 'js']);

