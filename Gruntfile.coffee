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
      dev:
        src: ['reactjs/**/*.js', '!reactjs/**/__tests__/*']
        dest: 'public/js/bundle.js'
        options:
          transform: ['reactify']

    sass:
      dev:
        files: { 'public/css/style.css': 'public/css/style.scss' }

    watch:
      php:
        files: ['src/**/*', 'spec/**/*', 'tests/**/*']
        tasks: ['phpspec', 'phpunit']
      frontend:
        files: ['reactjs/**/*', 'public/css/*.scss']
        tasks: ['browserify:dev', 'sass:dev']

  grunt.loadNpmTasks 'grunt-browserify'
  grunt.loadNpmTasks 'grunt-phpspec'
  grunt.loadNpmTasks 'grunt-phpunit'
  grunt.loadNpmTasks 'grunt-contrib-watch'
  grunt.loadNpmTasks 'grunt-jest'
  grunt.loadNpmTasks 'grunt-contrib-sass'
