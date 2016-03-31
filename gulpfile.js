// require packages
var gulp = require('gulp'),
    mainBowerFiles = require('main-bower-files'),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    rename = require("gulp-rename"),
    watch = require('gulp-watch'),
    batch = require('gulp-batch'),
    del = require('del');

// declare globals
var destination_js = './public/javascript',
    destination_css = './public/css',
    destination_fonts = './public/fonts',
    source_sass = './resources/assets/sass',
    source_my_sass = './resources/assets/my_sass',
    source_custom_css = './resources/assets/css',
    source_custom_js = './resources/assets/javascript',
    source_custom_fonts = './resources/assets/fonts',
    watch_timer;

gulp.task('clean_destination_js', function () {
    return del([
        destination_js+"/**/*"
    ]);
});

gulp.task('copy_js', ['clean_destination_js'], function() {
    var sources = mainBowerFiles('**/*.js');
    sources.push(source_custom_js+'/*.js');
    return gulp.src(sources,{base:'bower_components'})
     .pipe(rename(function (path) {
         path.dirname = "";
         }))
     .pipe(gulp.dest(destination_js))
});

gulp.task('clean_source_sass', function () {
    return del([
        source_sass+"/**/*"
    ]);
});

gulp.task('copy_sass', ['clean_source_sass'], function() {
    var sources = [];
    return gulp.src(sources,{base:'bower_components'})
        .pipe(rename(function (path) {
            path.dirname = "";
        }))
        .pipe(gulp.dest(source_sass))
});

gulp.task('compile_sass', ['copy_sass'], function () {
  return gulp.src([source_sass+"/*.scss",source_my_sass+"/*.scss"])
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./resources/assets/css'))
});

gulp.task('clean_destination_css', function () {
    return del([
        destination_css+"/**/*"
    ]);
});

gulp.task('copy_css', ['compile_sass','clean_destination_css'], function() {
    var sources = mainBowerFiles('**/*.css');
    sources.push(source_custom_css+'/*.css');
    return gulp.src(sources,{base:'bower_components'})
        .pipe(rename(function (path) {
            path.dirname = "";
        }))
        .pipe(gulp.dest(destination_css))
});

gulp.task('clean_destination_fonts', function () {
    return del([
        destination_fonts+"/**/*"
    ]);
});

gulp.task('copy_fonts', ['clean_destination_fonts'], function() {
    var sources = mainBowerFiles('**/fonts/*.*');
    sources.push(source_custom_fonts+'/*.*');
    return gulp.src(sources,{base:'bower_components'})
        .pipe(rename(function (path) {
            path.dirname = "";
        }))
        .pipe(gulp.dest(destination_fonts))
});

gulp.task('watch', function () {

    var sources = mainBowerFiles('**/*');

    sources.push(source_my_sass+"/*.scss");
    sources.push(source_custom_css+"/*.css");
    sources.push(source_custom_js+"/*.js");
    sources.push("!./resources/assets/css/style.css");

    gutil.log("The following files will be watched:");

    for (var i = 0; i < sources.length; i++) {
        sources[i] = sources[i].replace("/Library/WebServer/Documents/convergence",".");
         gutil.log(sources[i]);
    }

    watch(sources, function (events, done) {
     clearTimeout(watch_timer);
     watch_timer = setTimeout(function(path) {
         gulp.start('default');
         gutil.log("=====> Something Changed... UPDATE! <=====");
     }, 1500);
    });
});

gulp.task('default', ['copy_js','copy_css','copy_fonts']);
