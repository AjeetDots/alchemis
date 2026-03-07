var gulp = require('gulp'),
  browserify = require('browserify'),
  source = require('vinyl-source-stream'),
  plumber = require('gulp-plumber'),
  livereload = require('gulp-livereload'),
  sass = require('gulp-sass');

gulp.task('browserify', function () {
  return browserify('./app/view/js/app/app.js')
    .bundle()
    .on('error', function (err) {
      console.log(err.message);
      this.end();
    })
    .pipe(source('bundle.js'))
    .pipe(gulp.dest('./app/view/js/'))
    .pipe(livereload());
});

gulp.task('sass', function () {
  gulp.src('./app/view/styles/*.scss')
    .pipe(plumber())
    .pipe(sass())
    .pipe(gulp.dest('./app/view/styles'))
    .pipe(livereload());
});

gulp.task('watch', function () {
  livereload.listen();
  gulp.watch([
    'app/view/js/app/*.js',
    'app/view/js/app/**/*.js'
  ],['browserify']);
  gulp.watch(['app/view/styles/*.scss'], ['sass']);
  // templates
  gulp.watch(['app/view/templates/**/*.tpl'])
    .on('change', function (e) {
      livereload.changed(e.path);
    });
});

gulp.task('default', ['watch', 'sass', 'browserify']);