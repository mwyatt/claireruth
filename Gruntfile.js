module.exports = function(grunt) {
  grunt.registerTask('default', ['watch']);
  grunt.initConfig({
    concat: {
	    js: {
        options: {
          separator: ';'
        },
        src: [
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
        dest: 'asset/main-admin.js'
      },
    },
    compass: {
      dist: {
        options: {
          // httpPath = '/claireruth/',
          httpPath: '/',
          cssDir: 'asset'
          sassDir: 'sass'
          imagesDir: 'media'
          imagesDir: 'media'

          css_dir = "css"
          sass_dir = "sass"
          images_dir = "media"
          javascripts_dir = "js"


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
