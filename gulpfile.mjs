import gulp from 'gulp';
import clean from 'gulp-clean';
import uglify from 'gulp-uglify';
import cleanCSS from 'gulp-clean-css';
import zip from 'gulp-zip';

// Paths
const paths = {
    root: './',
    temp: 'temp/',
    prod: 'prod/',
    dist: 'dist/',
    js: 'assets/js/*.js',
    css: 'assets/css/*.css'
};

// Task: Clean `dist` folder
export const cleanDist = () => {
    return gulp.src(paths.dist, { read: false, allowEmpty: true }).pipe(clean());
};

// Task: Clean `temp` and `prod` folders
export const cleanProd = () => {
    return gulp
        .src([paths.temp, paths.prod], { read: false, allowEmpty: true })
        .pipe(clean());
};

// Task: Minify JavaScript and output to `dist/js/`
export const minifyJs = () => {
    return gulp
        .src(paths.js)
        .pipe(uglify())
        .pipe(gulp.dest(`${paths.dist}js/`));
};

// Task: Minify CSS and output to `dist/css/`
export const minifyCss = () => {
    return gulp
        .src(paths.css)
        .pipe(cleanCSS())
        .pipe(gulp.dest(`${paths.dist}css/`));
};

// Task: Copy all plugin files to `temp/` (excluding dev-only files)
export const copyFilesToTemp = () => {
    return gulp
        .src([
            `${paths.root}**/*`,
            `!${paths.root}node_modules{,/**}`,
            `!${paths.root}prod{,/**}`,
            `!${paths.root}assets{,/**}`,
            `!${paths.root}temp{,/**}`,
            `!${paths.root}gulpfile.mjs`,
            `!${paths.root}package*.json`,
            `!${paths.root}fs-seo-internal-link-juicer{,/**}`,
            `!${paths.root}**/.*`
        ])
        .pipe(gulp.dest(paths.temp));
};


// Task: Copy the `dist/` folder to `temp/`
export const copyDistToTemp = () => {
    return gulp
        .src(`${paths.dist}**/*`)
        .pipe(gulp.dest(`${paths.temp}dist/`));
};

// Task: Zip the contents of `temp/` and output to `prod/`
export const zipTempToProd = () => {
    return gulp
        .src(`${paths.temp}**/*`)
        .pipe(zip('fs-seo-internal-link-juicer.zip'))
        .pipe(gulp.dest(paths.prod));
};

// Task: Clean up `temp/` folder after zipping
export const cleanTempFolder = () => {
    return gulp.src(paths.temp, { read: false, allowEmpty: true }).pipe(clean());
};

// Task: Watch for changes in JS and CSS files
export const watchFiles = () => {
    gulp.watch(paths.js, minifyJs);
    gulp.watch(paths.css, minifyCss);
};

// Build `dist/` only
export const buildDist = gulp.series(cleanDist, gulp.parallel(minifyJs, minifyCss));

// Build `dist/` with watch mode
export const buildDistWatch = gulp.series(buildDist, watchFiles);

// Build `prod/` (including zip) from `dist/`
export const buildProd = gulp.series(
    cleanProd,            // Clean `temp` and `prod/`
    copyFilesToTemp,      // Copy all plugin files to `temp/`
    copyDistToTemp,       // Copy `dist/` folder to `temp/`
    zipTempToProd,        // Zip the contents of `temp/`
    cleanTempFolder       // Clean up `temp/`
);

export default buildDist;
