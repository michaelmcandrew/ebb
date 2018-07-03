let gulp = require('gulp')
let watch = require('gulp-watch');
let shell = require('gulp-shell');

gulp.task('generate', shell.task('bin/generate'))

gulp.task('watch',['generate'], function () {
    gulp.watch(['bin/**', 'src/**', 'templates/**'] , ['generate']);
});
