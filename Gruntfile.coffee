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

    watch:
      files: ['src/**/*', 'spec/**/*', 'tests/**/*']
      tasks: ['phpspec', 'phpunit']

  grunt.loadNpmTasks 'grunt-phpspec'
  grunt.loadNpmTasks 'grunt-phpunit'
  grunt.loadNpmTasks 'grunt-contrib-watch'
