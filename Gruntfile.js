module.exports = function (grunt) {

    var bowerDir = 'app/bower_components/';
    var autoprefixer = require('autoprefixer-core');

    // Use a grunt process timer
    require('time-grunt')(grunt);



    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),


        bower: {
            install: {
            //just run 'grunt bower:install' and you'll see files from your Bower packages in lib directory
            }
        },


        // Clean out the build directory before copying source files to it
        clean: {
            build: {
                src: [ 'build' ]
            }
        },


        // Copy source files files to the build directory
        copy: {


            // App scss files to build directory
            buildcss: {
                expand: true,
                cwd: 'app/styles',
                src: [ '**/*' ],
                dest: 'build/app/styles',
            },

            // Javascript files over to build directory
            buildjs: {
                expand: true,
                cwd: 'app/js',
                src: ['**/*'],
                dest: 'build/app/js/',
            },


            // Copy compiled files over to the distributed (live) directory
            distcss: {
                expand: true,
                cwd: 'build/processed/styles/css/',
                src: ['*'],
                dest: 'stylesheets'
            },


            distAppjs: {
                expand: true,
                flatten: true,
                src: [ 'build/processed/js/main.*', 'build/processed/js/default.*'],
                dest: 'assets/js/app'
            },

            distLibjs: {
                expand: true,
                flatten: true,
                src: [ 'build/processed/js/bootstrap*.js'],
                dest: 'assets/js/lib'
            },
        },


        // Grunt Contrib Compass
        compass: {
            dist: {
              options: {
                app: 'stand_alone',
                basePath: '',
                sassDir: 'build/app/styles/scss/',
                // environment: 'development',
                // specify: 'build/app/styles/scss/screen.scss',
                // cssPath: 'build/processed/styles/css/',
                // extensionsDir: 'build/bower_components/bower-compass-core/',
                // require: 'compass'
              }
            },
        },


        // Concatenate files
        concat: {


            defaultJS: {
                src: [
                    'build/app/js/default.js'
                ],
                dest: 'build/processed/js/default.js'
            },

            mainJS: {
                src: [
                    'build/app/js/jquery.selectable.js',
                    'app/bower_components/blazy/blazy.min.js',
                    'app/bower_components/isotope/dist/isotope.pkgd.min.js',
                    // 'app/bower_components/imagesloaded/imagesloaded.pkgd.js',
                    'build/app/js/main.js'
                ],
                dest: 'build/processed/js/main.js'
            }
        },


        // Autoprefixer - post css processor
        // https://www.npmjs.com/package/autoprefixer
        postcss: {
            options: {
                processors: [
                  autoprefixer({ browsers: ['last 2 version'] }).postcss
                ]
            },
            dist: { src: 'build/processed/styles/css/screen.css' }
        },


        // Minify the css
        cssmin: {
            build: {
                files: {
                    'build/processed/styles/css/screen.min.css': 'build/processed/styles/css/screen.css'
                }
            }
        },


        // Minify the js
        uglify: {
            build: {
                options: {
                  mangle: false,
                  report: 'min'
                },
                files: {
                  'build/processed/js/default.min.js': ['build/processed/js/default.js'],
                  'build/processed/js/main.min.js': ['build/processed/js/main.js']
                }
            }
        },


        // Watch these folders with LiveReload
        watch: {
            options: {
                spawn: false,
                  livereload: {
                    port: 1337
                  },
            },
            html: {
                files: [

                ]
            },
            css: {
              files: ['app/styles/scss/partials/*.scss', 'app/styles/scss/*.scss'],
            },
            js: {
              files: ['app/js/*.js'],
              tasks: ['buildjs']
            }
        },


        // Generate statistics for the css file. Run as 'grunt stylestats'.
        stylestats: {
            options: {

            },
            src: ['stylesheets/screen.css']
        },
    });



    // Load all grunt tasks in the package.json file
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);



    // ===============================================================================
    // Individual Grunt tasks
    // ===============================================================================


    // -------------------------------
    // Copy all source files to build
    // -------------------------------

    grunt.registerTask('copybuild', 'Copies the files to the build directory.', [ 'clean', 'copy:buildcss',  'copy:buildjs']);



    // ------------------------
    // Process CSS only
    // ------------------------

    grunt.registerTask('css', 'Compiles all of the scss (libsass) and copies the files to the live directory.', [ 'copybuild', 'compass', 'cssmin', 'copy:distcss']);



    // ------------------------
    // Process JS only
    // ------------------------
    grunt.registerTask('js', 'Compiles all of the js and copies the files to the live directory.', [ 'copybuild', 'concat', 'uglify', 'copy:distAppjs', 'copy:distLibjs']);



    // -----------------------------
    // Copy processed files to dist
    // -----------------------------

    grunt.registerTask('copydist', 'Copies the compiled files to the live directory.', [ 'copy:distcss',  'copy:distAppjs', 'copy:distLibjs']);



    // ===============================================================================
    // Default Grunt task to build the entire site.
    // ===============================================================================

    grunt.registerTask('default', ['copybuild', 'compass','concat', 'cssmin', 'uglify', 'copydist']);

};