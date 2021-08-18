import { Console } from "console";

const gulp = require("gulp"),

  argv = require('yargs').argv,

  autoprefixer  = require("autoprefixer"),
  babel         = require("gulp-babel"),
  browserSync   = require("browser-sync").create(),
  CacheBuster   = require('gulp-cachebust'),
  concatJS      = require("gulp-concat"),
  concatCSS     = require("gulp-concat"),
  cssnano       = require("cssnano"),
  del           = require("del"),
  fs            = require("fs"),
  minify        = require("gulp-minify"),
  postcss       = require("gulp-postcss"),
  purgecss      = require('gulp-purgecss'),
  strip         = require("gulp-strip-comments"),
  rename        = require("gulp-rename"),

  replace       = require("gulp-replace"),
  
  gulp_run           = require('gulp-run'),
  sass          = require('gulp-sass')(require('node-sass')),
  
  sassVariables = require('gulp-sass-variables'),
  
  sourcemaps    = require('gulp-sourcemaps'),
  svgSymbols    = require("gulp-svg-symbols"),
  svgmin        = require("gulp-svgmin"),
  tailwindcss   = require('Tailwindcss');

  var cachebust = new CacheBuster();

var theproxy = "http://wavesplugin.local/wp-admin/admin.php?page=ds-waves-page";

console.log ("Proxy Address: " + theproxy);

const header = {
  path: "./template-parts/header.php",
  loc:  "./template-parts/",
}
const assets_path     = "./assets";
const artifacts_path  = "./artifacts";
const paths = {
  css: {
    dir:  assets_path + "/css/concat",
    concat: {
      dir:    artifacts_path + "/css/03_concat/",
      name:   "style.concat.css",
    },
    clean: {
      dir:    artifacts_path + "/css/02_clean/",
      name:   "style.clean.css",
    },
    compiled: {
      dir:    artifacts_path + "/css/01_compiled/",
      name:   "scss_complied.css",
    },
    final: {
      // dir:    "./",
      dir:    assets_path,
      name:   "style.clean.min.css",
    },
    min: {
      dir:    artifacts_path + "/css/min/",
      name:   "style.min.css",
    },
    nocom: {
      dir:    artifacts_path + "/css/04_no_comments/",
      name:   "style.no_comments.css",
    },
  },
  fonts: {
    dir:      assets_path + "/fonts/",
    src:      assets_path + "/fonts/webfonts.css",
  },
  scss:{
    dir:      assets_path + "/scss/",
    temp:     artifacts_path + "/scss/",
  },
  styles: {
    // dir:      assets_path + 
    cache:    assets_path + "style.min.css",
    compiled: assets_path + "/css/scss_compiled",
    concat:   [assets_path + "/css/concat"],
    dest:     assets_path,
    min:      assets_path + "/css/minification",
    name:     "style.css",
    min:      "style.min.css",
    src:      [assets_path + "/scss/*.scss", assets_path + "/TailWindCSS/tailwind.post.css"],
  },
  tailwind: {
    config:   "tailwind.config.js",
    dir:      assets_path + "/TailWindCSS/",
    post: {
      dir:      assets_path + "/TailWindCSS/",
      name:     "tailwind.post.css",
    },
    pre:  {
      dir:      assets_path + "/TailWindCSS/",
      name:     "tailwind_includes.css",      
    }
  },
  scripts: {
    cache:    assets_path + "global.min.js",
    src:      [assets_path + "/scripts/*.js"],
    pre:      [assets_path + "/js/preprocess/"],
    preall:   [assets_path + "/js/preprocess/*"],
    con:      [assets_path + "/js/concat"],
    conall:   [assets_path + "/js/concat/*"],
    nocom:    [assets_path + "/js/no_comments/"],
    nocomall: [assets_path + "/js/no_comments/*"],
    min:      [assets_path + "/min.js"],
    name:     "global.js",
  },
  svg: {
    src: "./icons/*.svg"
  },
  php: {
    src: [
      "./*.php", 
      "./functions/*", 
      "./pages/*",
      "./templates/*", 
    ]
  }
};

const directories = {
  scripts: [
    assets_path + "/scripts/*.js",
  ],
  styles: [
    paths.scss.dir + "*.scss",
    paths.fonts.src,
    "./assets/fonts/Montserrat/stylesheet.css",
    // paths.tailwind.dir +  paths.tailwind.post.name,
  ],
  tailwindcss: [
    paths.tailwind.dir +  paths.tailwind.pre.name,
    paths.tailwind.dir +  paths.tailwind.post.name,
    paths.tailwind.config,
  ],
  php: [
    // "./**/*.php", 
    "./class_generics/*.php", 
    "./classes/**/*.php", 
    "./page_templates/*.php",
  ]
}

/* STYLES */
export function do_styles(done) {
    return gulp.series(
      verify_tailwind_exists,
      delete_artifacts,
      compile_styles,
      clean_unused_styles,
      concat_css_files,
      // strip_css_comments,
      // finish_style_actions,
      move_final_style,
      delete_artifacts,
    reload,
    done => {
      cachebust.references()
      done();
    })(done);
}

export function compile_styles() {
  return gulp
    .src(directories.styles)
    .pipe(sourcemaps.init())
    .pipe(sass()).on("error", sass.logError)
    .pipe(postcss([tailwindcss(paths.tailwind.config), autoprefixer(), cssnano()]))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(paths.css.compiled.dir))
    .pipe(browserSync.stream());
}

export function verify_tailwind_exists(){
  var file_exists = check_file_exists(paths.tailwind.post.dir + paths.tailwind.post.name);
  if (!file_exists){
    return compile_tailwindcss();
  }
  return Promise.resolve();

}
const check_file_exists = (file_path) => {
  var value = fs.existsSync(file_path);
  if (value) {
    return true;
  } else {
    return false;
  }
}


/* STYLES */
export function gulp_compile_tailwindcss(done) {
  return gulp.series(
    compile_tailwindcss,
  )(done);
}

export function compile_tailwindcss(done){
  
  var tailwind_pre = paths.tailwind.pre.dir + paths.tailwind.pre.name; 
  var tailwind_post = paths.tailwind.post.dir + paths.tailwind.post.name;
  return gulp.src(tailwind_pre)
  .pipe(postcss([tailwindcss( paths.tailwind.config)]))
  .pipe(rename(function (path) {
    var name_arr      = paths.tailwind.post.name.split(".");
    var name_arr_len  = name_arr.length;
    var name          = name_arr.slice(0, name_arr_len -1 ).join(".");
    path.basename = name;
  }))
  .pipe(gulp.dest( paths.tailwind.post.dir ));
}

function refresh_tailwindcss(done){
  return gulp.series(
  delete_tailwindcss_post,
  build_tailwind,
  done => {
    cachebust.references()
    done();
  })(done);
}

export function build_tailwind(){
  var cmd = new gulp_run.Command("npm run compile_tailwindcss");
  cmd.exec("Run");
}

function delete_tailwindcss_post(){
  var tailwind_post = paths.tailwind.post.dir + paths.tailwind.post.name;
  return del(tailwind_post);
}

export function clean_unused_styles(){
  var import_path = paths.css.compiled.dir + "*";
  return gulp
  .src(import_path)
    // . pipe(purgecss({
    //   content: directories.php
    //   }))
    .pipe(gulp.dest(paths.css.clean.dir))
}


export function finish_style_actions() {

}

export function rename_main_style(){
  console.log(paths.css.compiled.dir + ",  " + paths.styles.name);
  return gulp
    .src(paths.styles.compiled + '/' + paths.styles.name)
    .pipe(rename(paths.css.compiled.path + '/' + paths.styles.min));
}

function deleteOldMainStyle() {
  return del(paths.styles.min + paths.styles.name);
}

export function strip_css_comments(){
  const import_file  = paths.css.concat.dir + paths.css.concat.name;
  const nocom_name   = paths.css.nocom.name;
  const nocom_path   = paths.css.nocom.dir;
  console.log(import_file);
  console.log("STRIP");
  gulp.src(import_file)
    .pipe(strip())
    .pipe(gulp.dest(nocom_path));
}

export function move_final_style() {

  console.log("here");
  const no_com_file = paths.css.nocom.dir + paths.css.nocom.name;
  const no_com_file_exists = check_file_exists(no_com_file);

  const final_dest  = paths.css.final.dir;
  const final_name  = paths.css.final.name;

  if (no_com_file_exists){
    return move_file(no_com_file, final_name, final_dest);
  } 

  const concat_file = paths.css.concat.dir + paths.css.concat.name;
  const concat_file_exists = check_file_exists(concat_file);

  if (concat_file_exists){
    return move_file(concat_file, final_name, final_dest);
  } 
  return;
}

function move_file(src, name, dest){
  return gulp
  .src(src)
  .pipe(rename(name))
  .pipe(gulp.dest(dest));

}


export function concat_css_files(){
  const import_files  = paths.css.clean.dir + "*.css";
  const concat_name   = paths.css.concat.name;
  const concat_path   = paths.css.concat.dir;
  return gulp.src(import_files)
  .pipe(concatCSS(concat_name))
  .pipe(gulp.dest(concat_path));
}
/* END STYLES */


/* SCRIPTS */
function doScripts(done) {
  var global_path = assets_path + "/" + paths.scripts.name;
  var global_min_path = assets_path + "/" + paths.scripts.name;
  return gulp.series(
    preprocessJs,
    concatJs,
    stripCommentsjs,
    minifyJs,
    deleteArtifacts,
    reload,
    done => {
      cachebust.references()
      cacheBust(header.path, header.loc);
      cacheBust(global_path, assets_path);
      cacheBust(global_min_path, assets_path);
      done();
    }
  )(done);
}

function stripCommentsCSS(){
  return gulp
  .src(paths.scripts.conall)
  .pipe(strip())
  .pipe(gulp.dest(paths.scripts.nocom));
}

function stripCommentsjs(){
  return gulp
  .src(paths.scripts.conall)
  .pipe(strip())
  .pipe(gulp.dest(paths.scripts.nocom));
}

function preprocessJs() {
  return gulp
    .src(paths.scripts.src, {allowEmpty: true })
    .pipe(
      babel({
        presets: ["@babel/env"],
        plugins: ["@babel/plugin-proposal-class-properties"]
      })
    )
    .pipe(gulp.dest(paths.scripts.pre));
}

function concatJs() {
  return gulp
    .src(paths.scripts.preall)
    .pipe(concatJS(paths.scripts.name))
    .pipe(gulp.dest(paths.scripts.con));
}

function minifyJs() {
  return gulp
    .src(paths.scripts.nocomall)
    .pipe(
      minify({
        ext: {
          src: ".js",
          min: ".min.js"
        }
      })
    )
    .pipe(gulp.dest(assets_path));
}

export function delete_artifacts(){
  console.log("Delete Artifacts");
  return del([
    artifacts_path,
  ]);
}
/* END SCRIPTS */


function reload(done) {
  browserSync.reload();
  done();
}

export function watch() {
  browserSync.init({
    proxy: theproxy
  });
  gulp.watch(directories.styles, do_styles);
  gulp.watch(directories.tailwindcss, refresh_tailwindcss);
  gulp.watch(directories.scripts, doScripts);
  // gulp.watch(paths.svg.src, doSvg);
  gulp.watch(directories.php, reload);
}
exports.default = gulp.series(watch);