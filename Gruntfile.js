module.exports = function(grunt) {
  grunt.registerTask('default', ['watch']);
  grunt.initConfig({
    config: grunt.file.readJSON('app/json/config.json'),
    concat: {
	    js: {
        options: {
          separator: ';'
        },
        src: [
          'js/site/<%= config.site %>/*.js',
          'js/vendor/*.js',
          'js/global/*.js',
          'js/*.js'
        ],
        dest: 'asset/main.js'
      },
	    js_admin: {
        options: {
          separator: ';'
        },
        src: [
          'js/vendor/*.js',
          'js/global/*.js',
          'js/admin/vendor/*.js',
          'js/admin/*.js'
        ],
        dest: 'asset/admin/main.js'
      },
    },
    compass: {
      dist: {
        options: {
          // httpPath = '/claireruth/',
          httpPath: '/',
          require: 'breakpoint',
          cssDir: 'asset',
          sassDir: 'sass',
          javascriptsDir: 'js',
          imagesDir: 'media',
          relativeAssets: true

// # output_style = :expanded or :nested or :compact or :compressed

        },
        files: {                            
          'screen.css': 'screen.scss',        
          'admin/screen.css': 'admin/screen.scss'
        }
      }
      // dev: {                                  
      //   files: {
      //     'screen.css': 'screen.scss',
      //     'admin/screen.css': 'admin/screen.scss',
      //   }
      // }
    },
    watch: {
      js: {
        files: [
          'js/site/<%= config.site %>/*.js',
          'js/vendor/*.js',
          'js/global/*.js',
          'js/*.js'
        ],
        tasks: ['concat:js'],
        options: {
          livereload: true
        }
      },
      js_admin: {
        files: [
          'js/vendor/*.js',
          'js/global/*.js',
          'js/admin/vendor/*.js',
          'js/admin/*.js'
        ],
        tasks: ['concat:js_admin'],
        options: {
          livereload: true
        }
      },
      css: {
        files: ['sass/**'],
        tasks: ['compass'],
        options: {
          livereload: true
        }
      },
    },
  });
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
};
