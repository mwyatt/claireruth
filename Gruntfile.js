module.exports = function(grunt) {
 
  grunt.registerTask('watch', [ 'watch' ]);

  grunt.initConfig({
    concat: {
      js: {
        options: {
          separator: ';'
        },
        src: [
          'js/*.js'
        ],
        dest: 'js/main.min.js'
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
      },
      dev: {                                  
        files: {
          'screen.css': 'screen.scss',
          'admin/screen.css': 'admin/screen.scss',
        }
      }
    },
    watch: {
      js: {
        files: ['js/*.js'],
        tasks: ['concat:js', 'uglify:js'],
        options: {
          livereload: true
        }
      },
      css: {
        files: ['sass/*.scss'],
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
