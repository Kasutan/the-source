// Grab our gulp packages
const gulp  = require('gulp');
const sass = require('gulp-sass');
const cleanCSS = require('gulp-clean-css');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
//const concat = require('gulp-concat');
//const rename = require('gulp-rename');




// Compile Main Sass, create source Map and Autoprefix
gulp.task('styles', function() {
    
    return gulp.src('sass/*.scss')
        .pipe(sourcemaps.init()) // Start Sourcemaps
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 2 versions'],
            cascade: false
        }))
		//.pipe(cleanCSS({compatibility: '*'}))
        .pipe(sourcemaps.write('.')) // Creates sourcemaps for minified styles
		.pipe(gulp.dest('./'));

});

// Compile Sass for blocks
gulp.task('blocks-styles', function() {
    
	return gulp.src('partials/blocks/*/*.scss')
		.pipe(sass())
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}))
		//.pipe(cleanCSS({compatibility: '*'}))
		.pipe(gulp.dest(function (file) {
			return file.base;
		}));
});

gulp.task('all-styles', ['styles','blocks-styles']);
gulp.task('styles-all', ['styles','blocks-styles']);

// Watch files for changes (without Browser-Sync)
gulp.task('watch', function() {

	// Watch main theme .scss files
	gulp.watch('sass/*/*.scss', ['styles']);

	// Watch blocks .scss files
	gulp.watch('partials/blocks/*/*.scss', ['blocks-styles']);



  // Watch js files
 // gulp.watch('.//js/*.js', ['site-js']);


}); 

