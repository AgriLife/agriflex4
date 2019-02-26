module.exports = (grunt) ->
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
        'css/src/*.scss'
      ]
      tasks: ['sasslint', 'sass:dev', 'postcss:dev']
    postcss:
      pkg:
        options:
          processors: [
            require('autoprefixer')({browsers: ['last 2 versions','ie > 9']})
          ]
          failOnError: true
        files:
          'css/admin.css': 'css/admin.css'
          'css/default.css': 'css/default.css'
          'css/header-nav-footer.css': 'css/header-nav-footer.css'
      dev:
        options:
          map: true
          processors: [
            require('autoprefixer')({browsers: ['last 2 versions','ie > 9']})
          ]
          failOnError: true
        files:
          'css/admin.css': 'css/admin.css'
          'css/default.css': 'css/default.css'
          'css/header-nav-footer.css': 'css/header-nav-footer.css'
    sass:
      pkg:
        options:
          loadPath: 'node_modules/foundation-sites/scss'
          sourcemap: 'none'
          style: 'compressed'
          precision: 2
        files:
          'css/admin.css': 'css/src/admin.scss'
          'css/default.css': 'css/src/default.scss'
          'css/header-nav-footer.css': 'css/src/header-nav-footer.scss'
      dev:
        options:
          loadPath: 'node_modules/foundation-sites/scss'
          style: 'expanded'
          precision: 2
        files:
          'css/admin.css': 'css/src/admin.scss'
          'css/default.css': 'css/src/default.scss'
          'css/header-nav-footer.css': 'css/src/header-nav-footer.scss'
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
            'js/*.js',
            'node_modules/foundation-sites/js/foundation/foundation?(.topbar).js',
            'node_modules/foundation-sites/js/vendor/modernizr.js',
            'bower_components/jquery/{dist,sizzle}/**/*.js',
            'bower_components/jquery-placeholder/*.js',
            'bower_components/jquery.cookie/jquery.cookie.js'
          ]
    sasslint:
      options:
        configFile: '.sass-lint.yaml'
      target: ['scss/**/*.s+(a|c)ss']
    compress:
      main:
        options:
          archive: '<%= pkg.name %>.zip'
        files: [
          {src: ['style.css']},
          {src: ['css/*.css']},
          {src: ['js/*.js']},
          {src: ['images/**']},
          {src: ['src/**']},
          {src: ['functions.php']},
          {src: ['search.php']},
          {src: ['members-only.php']},
          {src: ['readme.md']},
          {src: ['vendor/autoload.php', 'vendor/composer/**']}
        ]
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
          'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveToggle.min.js'
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
          'node_modules/foundation-sites/dist/js/plugins/foundation.responsiveToggle.js'
        ]
        dest: 'js/foundation.concat.js'

  @loadNpmTasks 'grunt-contrib-sass'
  @loadNpmTasks 'grunt-jsvalidate'
  @loadNpmTasks 'grunt-contrib-watch'
  @loadNpmTasks 'grunt-contrib-compress'
  @loadNpmTasks 'grunt-contrib-concat'
  @loadNpmTasks 'grunt-sass-lint'
  @loadNpmTasks 'grunt-postcss'

  @registerTask 'default', ['sass:pkg', 'concat:dist', 'jsvalidate', 'postcss:pkg']
  @registerTask 'develop', ['sasslint', 'sass:dev', 'concat:dev', 'jsvalidate', 'postcss:dev']
  @registerTask 'release', ['compress', 'makerelease']
  @registerTask 'makerelease', 'Set release branch for use in the release task', ->
    done = @async()

    # Define simple properties for release object
    grunt.config 'release.key', process.env.RELEASE_KEY
    grunt.config 'release.file', grunt.template.process '<%= pkg.name %>.zip'

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
        grunt.task.run 'setlasttag'

      done(err)
      return
    return
  @registerTask 'setlasttag', 'Set release message as range of commits', ->
    done = @async()

    grunt.util.spawn {
      cmd: 'git'
      args: [ 'tag' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        matches = result.stdout.match /([^\n]+)$/
        grunt.config 'release.lasttag', matches[1] + '..'

      grunt.task.run 'setmsg'

      done(err)
      return
    return
  @registerTask 'setmsg', 'Set gh_release body with commit messages since last release', ->
    done = @async()

    releaserange = grunt.template.process '<%= release.lasttag %>HEAD'

    grunt.util.spawn {
      cmd: 'git'
      args: ['shortlog', releaserange, '--no-merges']
    }, (err, result, code) ->
      msg = grunt.template.process 'Upload <%= release.file %> to your dashboard.'

      if result.stdout isnt ''
        message = result.stdout.replace /(\n)\s\s+/g, '$1- '
        message = message.replace /\s*\[skip ci\]/g, ''
        msg += '\n\n# Changes\n' + message

      grunt.config 'release.msg', msg
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
      # target_commitish: grunt.config.get 'release.branch'
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
    return

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
