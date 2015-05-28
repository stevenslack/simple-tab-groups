
module.exports = function(grunt) {

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        // sass
        sass: {
            options: {
                outputStyle: 'compressed',
                sourceMap: false
            },
            dist: {
                files: {
                    'assets/css/display.css': 'assets/sass/display.scss',
                    'assets/css/admin.css': 'assets/sass/admin.scss',
                }
            }
        },

        // autoprefixer
        autoprefixer: {

            multiple_files: {
                options: {
                    browsers: ['last 2 versions', 'ie 8', 'ie 9', 'ios 6', 'android 4'],
                    map: false
                },
                expand: true,
                flatten: true,
                src: 'assets/css/*.css',
                dest: 'assets/css/',
            }
        },

        // uglify to concat, minify, and make source maps
        uglify: {
            options: {
                sourceMap: false
            },
            main: {
                files: {
                    'assets/js/display.js': [
                        'bower_components/tabby/dist/js/classList.js',
                        'bower_components/tabby/dist/js/tabby.js',
                        'assets/js/init.js'
                    ],
                }
            }
        },

        // watch for changes and trigger sass
        watch: {
            sass: {
                files: ['assets/css/*.{scss,sass}'],
                tasks: ['sass', 'autoprefixer']
            },
            js: {
                files: ['assets/js/*.js'],
                tasks: ['uglify']
            }
        },

    });


    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // register task
    grunt.registerTask( 'default', ['sass', 'autoprefixer', 'uglify', 'watch'] );

};
