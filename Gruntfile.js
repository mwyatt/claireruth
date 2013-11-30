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
        dest: 'js/public/main.js'
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
        dest: 'js/public/main-admin.js'
      },
    },
    uglify: {
      options: {
        mangle: false
      },
      js: {
        files: {
          'js/main.min.js': ['js/main.min.js']
        }
      },
    },
    compass: {
      dist: {
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
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-compass');
 
};
