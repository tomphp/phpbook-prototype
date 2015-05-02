module.exports = (grunt) ->
  grunt.initConfig
    pkg: grunt.file.readJSON('package.json')

    phpspec:
      app:
        specs: 'spec/'
      options:
        prefix: 'vendor/bin/'

    phpunit:
      classes:
        dir: 'tests/'
      options:
        bin: 'vendor/bin/phpunit'
        bootstrap: 'vendor/autoload.php'
        colors: true

    browserify:
      dist:
        src: ['reactjs/**/*.js']
        dest: 'public/js/bundle.js'
        options:
          transform: ['reactify']

    watch:
      files: ['src/**/*', 'spec/**/*', 'tests/**/*', 'reactjs/**/*']
      tasks: ['phpspec', 'phpunit', 'browserify']

  grunt.loadNpmTasks 'grunt-browserify'
  grunt.loadNpmTasks 'grunt-phpspec'
  grunt.loadNpmTasks 'grunt-phpunit'
  grunt.loadNpmTasks 'grunt-contrib-watch'
