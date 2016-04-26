//构建JS
var static_dir = '../public';

var gulp = require('gulp'),
    uglify = require('gulp-uglify');

var src_dir = static_dir + '/js/src/*.js';
var to_dir = static_dir + '/js/min';
gulp.task('js', function() {
        gulp.src(src_dir)
            .pipe(uglify())     //压缩js
            .pipe(gulp.dest(to_dir)); //位置
});

//构建css
var minifyCSS = require('gulp-minify-css'),
    importCss = require('gulp-import-css'),
    rename = require('gulp-rename'),
    less = require('gulp-less');

var css_src_dir = static_dir + '/css/src/*.css';
var css_to_dir = static_dir + '/css/min';
gulp.task('css', function () {
    gulp.src(css_src_dir)
        .pipe(importCss())
        //.pipe(less())
        .pipe(minifyCSS())
        // .pipe(rename(function(path){
        //      path.dirname = path.dirname.replace('page', 'min');
        // }))
        //.pipe(rename({suffix:'.min'}))
        .pipe(gulp.dest(css_to_dir));
});

var t1 = 0;
var t2 = 0;
gulp.task('watch', function () {
    gulp.watch(static_dir + '/js/src/**/*.js', function(event){
        if(event.type == 'added' || event.type == 'changed' || event.type == 'deleted'){//added, changed or deleted
            if(t1){
                clearTimeout(t1);
            }
            t1 = setTimeout(function(){
                gulp.start('js');
            }, 1000);
        }
    });

    gulp.watch(static_dir + '/css/src/*.css', function(event){
        //added, changed or deleted
        if(event.type == 'added' || event.type == 'changed' || event.type == 'deleted'){
            if(t2){
                clearTimeout(t2);
            }
            t2 = setTimeout(function(){
                gulp.start('css');
            }, 1000);
        }
    });


});

gulp.task('default', ['watch']);