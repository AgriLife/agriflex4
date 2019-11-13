module.exports = (grunt) ->
  sass = require 'node-sass'
  @initConfig
    pkg: @file.readJSON('package.json')
    release:
      branch: ''
      repofullname: ''
      lasttag: ''
      msg: ''
      post: ''
      url: ''
    watch:
      files: [
        'css/src/**/*.scss',
        '!css/src/_themecomment.scss',
        'js/src/*.coffee',
        'package.json'
      ]
      tasks: ['develop']
    postcss:
      pkg:
        options:
          processors: [
            require('autoprefixer')({browsers: ['last 2 versions','ie > 9']})
            require('cssnano')()
          ]
          failOnError: true
        files:
          'css/admin.css': 'css/admin.css'
          'css/style.css': 'css/style.css'
          'css/service-landing-page.css': 'css/service-landing-page.css'
      dev:
        options:
          map: true
          processors: [
            require('autoprefixer')({browsers: ['last 2 versions','ie > 9']})
          ]
          failOnError: true
        files:
          'css/admin.css': 'css/admin.css'
          'css/style.css': 'css/style.css'
          'css/service-landing-page.css': 'css/service-landing-page.css'
    cmq:
      your_target:
        files:
          'css': ['css/*.css']
    sass:
      pkg:
        options:
          implementation: sass
          noSourceMap: true
          outputStyle: 'compressed'
          precision: 4
          includePaths: ['node_modules/foundation-sites/scss']
        files:
          'css/admin.css': 'css/src/admin.scss'
          'css/style.css': 'css/src/style.scss'
          'css/service-landing-page.css': 'css/src/service-landing-page.scss'
      dev:
        options:
          implementation: sass
          sourceMap: true
          outputStyle: 'nested'
          precision: 4
          includePaths: ['node_modules/foundation-sites/scss']
        files:
          'css/admin.css': 'css/src/admin.scss'
          'css/style.css': 'css/src/style.scss'
          'css/service-landing-page.css': 'css/src/service-landing-page.scss'
    sasslint:
      options:
        configFile: '.sass-lint.yml'
      target: [
        'css/**/*.s+(a|c)ss',
        '!css/src/_themecomment.scss'
      ]
    jsvalidate:
      options:
        globals:
          jQuery: true
          console: true
          module: true
          document: true
      targetName:
        files:
          src: [
            'js/*.js'
          ]
    compress:
      main:
        options:
          archive: '<%= pkg.name %>.zip'
        files: [
          {src: ['css/*.css']},
          {src: ['images/**']},
          {src: ['js/*.js']},
          {src: ['src/**']},
          {src: ['templates/**']},
          {src: ['vendor/autoload.php', 'vendor/composer/**']},
          {src: ['*.php']},
          {src: ['readme.md']},
          {src: ['style.css']}
        ]
    coffee:
      compile:
        options:
          bare: true
        files:
          'js/post-type-tile-filters.js': 'js/src/post-type-tile-filters.coffee'
    concat:
      dist:
        options:
          stripBanners: true
          separator: '\n'
          process: (src, filepath) ->
            return src.replace(/\n\/\/# sourceMappingURL=[^\n]+\n?/g, '')
        src: [
          'node_modules/what-input/dist/what-input.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.core.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.triggers.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.mediaQuery.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.keyboard.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.touch.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.nest.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.box.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.motion.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.sticky.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.dropdownMenu.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.drilldown.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.accordionMenu.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveMenu.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveToggle.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.smoothScroll.min.js'
        ]
        dest: 'js/foundation.concat.js'
      dev:
        options:
          sourceMap: true
        src: [
          'node_modules/what-input/dist/what-input.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.core.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.triggers.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.mediaQuery.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.keyboard.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.touch.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.nest.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.box.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.motion.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.sticky.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.dropdownMenu.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.drilldown.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.accordionMenu.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveMenu.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveToggle.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.smoothScroll.js'
        ]
        dest: 'js/foundation.concat.js'

  @loadNpmTasks 'grunt-jsvalidate'
  @loadNpmTasks 'grunt-contrib-coffee'
  @loadNpmTasks 'grunt-contrib-watch'
  @loadNpmTasks 'grunt-contrib-compress'
  @loadNpmTasks 'grunt-contrib-concat'
  @loadNpmTasks 'grunt-sass-lint'
  @loadNpmTasks 'grunt-sass'
  @loadNpmTasks 'grunt-postcss'
  @loadNpmTasks 'grunt-combine-media-queries'

  @registerTask 'default', ['themecomment', 'sasslint', 'sass:pkg', 'concat:dist', 'coffee', 'jsvalidate', 'cmq', 'postcss:pkg']
  @registerTask 'develop', ['themecomment', 'sasslint', 'sass:dev', 'concat:dev', 'coffee', 'jsvalidate', 'postcss:dev']
  @registerTask 'release', ['compress', 'makerelease']
  @registerTask 'makerelease', 'Set release branch for use in the release task', ->
    done = @async()

    # Define simple properties for release object
    grunt.config 'release.key', process.env.RELEASE_KEY
    grunt.config 'release.file', grunt.template.process '<%= pkg.name %>.zip'
    grunt.config 'release.msg', grunt.template.process 'Upload <%= pkg.name %>.zip to your dashboard.'

    grunt.util.spawn {
      cmd: 'git'
      args: [ 'rev-parse', '--abbrev-ref', 'HEAD' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        matches = result.stdout.match /([^\n]+)$/
        grunt.config 'release.branch', matches[1]
        grunt.task.run 'setrepofullname'

      done(err)
      return
    return
  @registerTask 'setrepofullname', 'Set repo full name for use in the release task', ->
    done = @async()

    # Get repository owner and name for use in Github REST requests
    grunt.util.spawn {
      cmd: 'git'
      args: [ 'config', '--get', 'remote.origin.url' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        grunt.log.writeln 'Remote origin url: ' + result
        matches = result.stdout.match /([^\/:]+)\/([^\/.]+)(\.git)?$/
        grunt.config 'release.repofullname', matches[1] + '/' + matches[2]
        grunt.task.run 'setpostdata'

      done(err)
      return
    return
  @registerTask 'setpostdata', 'Set post object for use in the release task', ->
    val =
      tag_name: 'v' + grunt.config.get 'pkg.version'
      name: grunt.template.process '<%= pkg.name %> (v<%= pkg.version %>)'
      body: grunt.config.get 'release.msg'
      draft: false
      prerelease: false
    grunt.config 'release.post', JSON.stringify val
    grunt.log.write JSON.stringify val

    grunt.task.run 'createrelease'
    return
  @registerTask 'createrelease', 'Create a Github release', ->
    done = @async()

    # Create curl arguments for Github REST API request
    args = ['-X', 'POST', '--url']
    args.push grunt.template.process 'https://api.github.com/repos/<%= release.repofullname %>/releases?access_token=<%= release.key %>'
    args.push '--data'
    args.push grunt.config.get 'release.post'
    grunt.log.write 'curl args: ' + args

    # Create Github release using REST API
    grunt.util.spawn {
      cmd: 'curl'
      args: args
    }, (err, result, code) ->
      grunt.log.write '\nResult: ' + result + '\n'
      grunt.log.write 'Error: ' + err + '\n'
      grunt.log.write 'Code: ' + code + '\n'

      if result.stdout isnt ''
        obj = JSON.parse result.stdout
        # Check for error from Github
        if 'errors' of obj and obj['errors'].length > 0
          grunt.fail.fatal 'Github Error'
        else
          # We need the resulting "release" ID value before we can upload the .zip file to it.
          grunt.config 'release.id', obj.id
          grunt.task.run 'uploadreleasefile'

      done(err)
      return
    return
  @registerTask 'uploadreleasefile', 'Upload a zip file to the Github release', ->
    done = @async()

    # Create curl arguments for Github REST API request
    args = ['-X', 'POST', '--header', 'Content-Type: application/zip', '--upload-file']
    args.push grunt.config.get 'release.file'
    args.push '--url'
    args.push grunt.template.process 'https://uploads.github.com/repos/<%= release.repofullname %>/releases/<%= release.id %>/assets?access_token=<%= release.key %>&name=<%= release.file %>'
    grunt.log.write 'curl args: ' + args

    # Upload Github release asset using REST API
    grunt.util.spawn {
      cmd: 'curl'
      args: args
    }, (err, result, code) ->
      grunt.log.write '\nResult: ' + result + '\n'
      grunt.log.write 'Error: ' + err + '\n'
      grunt.log.write 'Code: ' + code + '\n'

      if result.stdout isnt ''
        obj = JSON.parse result.stdout
        # Check for error from Github
        if 'errors' of obj and obj['errors'].length > 0
          grunt.fail.fatal 'Github Error'

      done(err)
      return
    returnreturn
  @registerTask 'themecomment', 'Add WordPress header to style.css and css/style.css', ->
    scss = 'css/src/_themecomment.scss'
    css = 'style.css'
    options =
      encoding: 'utf-8'
    output = '/*!\n'
    output += '  Theme Name:  <%= pkg.org_agrilife.themename %>\n'
    output += '  Theme URI:   <%= pkg.repository.url %>\n'
    output += '  Author:      <%= pkg.author %>\n'
    output += '  Author URI:  <%= pkg.org_agrilife.authoruri %>\n'
    output += '  Description: <%= pkg.description %>\n'
    output += '  Version:     <%= pkg.version %>\n'
    output += '  License:     <%= pkg.license %>\n'
    output += '  License URI: <%= pkg.org_agrilife.licenseuri %>\n'
    output += '  Text Domain: <%= pkg.name %>\n'
    output += '  Template:    <%= pkg.org_agrilife.template %>\n'
    output += '*/\n'
    output = grunt.template.process output
    grunt.file.delete scss
    grunt.file.write scss, output, options
    output += '\n/* ----------------------------------------------------------------------------\n\n'
    output += '  WordPress requires a style.css file located in the theme\'s root folder for\n'
    output += '  stuff to work. However, we will not be using vanilla CSS. We\'re using Sass.\n\n'
    output += '  Sass is a superset of CSS that adds in amazing features such as variables,\n'
    output += '  nested selectors and loops. It\'s also the easiest way to customize\n'
    output += '  Foundation. All Sass files are located in the /css/src folder.\n\n'
    output += '  Please note that none of your scss files will be compiled to /css/style.css\n'
    output += '  before you run "npm start" or "grunt" or "grunt develop".\n\n'
    output += '  Please read the README.md file before getting started. More info on how to\n'
    output += '  use Sass with Foundation can be found here:\n'
    output += '  http://foundation.zurb.com/docs/sass.html\n\n'
    output += '---------------------------------------------------------------------------- */'
    grunt.file.delete css
    grunt.file.write css, output, options
    return

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
