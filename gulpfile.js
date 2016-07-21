var gulp = require('gulp');
var $ = require('gulp-load-plugins')();
var cleanCSS = require('gulp-clean-css');

var sassPaths = [
    'bower_components/bootstrap-sass/stylesheets',
    'bower_components/wow/css/libs',
    'bower_components/devicons/css',
    'bower_components/fontawesome/scss'
];

gulp.task('sass', function() {
    return gulp.src('scss/app.scss')
    .pipe($.sass({
        includePaths: sassPaths
    })
    .on('error', $.sass.logError))
    .pipe($.autoprefixer({
        browsers: ['last 20 versions', 'ie >= 9']
    }))
    .pipe(gulp.dest('assets/css'));
});

gulp.task('minify-css', function() {
    return gulp.src('assets/css/*.css')
    .pipe(cleanCSS({compatibility: 'ie8'}))
    .pipe(gulp.dest('assets/css'));
});

gulp.task('icons', function() { 
    return gulp.src('bower_components/fontawesome/fonts/**.*') 
        .pipe(gulp.dest('./assets/fonts')); 
});

gulp.task('default', ['sass', 'minify-css', 'icons'], function() {
    gulp.watch(['scss/**/*.scss'], ['sass']);
    // gulp.watch(['assets/css/*.css'], ['minify-css']);
});