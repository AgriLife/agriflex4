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
      dev:
        options:
          loadPath: 'node_modules/foundation-sites/scss'
          style: 'expanded'
          precision: 2
        files:
          'css/admin.css': 'css/src/admin.scss'
          'css/default.css': 'css/src/default.scss'
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
          archive: 'agriflex4.zip'
        files: [
          {src: ['style.css']},
          {src: ['rtl.css']},
          {src: ['css/*.css']},
          {src: ['js/*.js']},
          {src: ['images/**']},
          {src: ['src/*.php']},
          {src: ['functions.php']},
          {src: ['search.php']},
          {src: ['README.md']},
          {src: ['screenshot.png']},
          {src: ['vendor/**']}
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
  @registerTask 'release', ['setbranch', 'setrepofullname', 'setlasttag', 'setmsg', 'setpost', 'seturl', 'gitrelease']
  @registerTask 'releaseatts', ['setbranch', 'setrepofullname', 'setlasttag', 'setpost', 'setmsg', 'seturl']
  @registerTask 'setbranch', 'Set release branch for use in the release task', ->
    done = @async()
    grunt.util.spawn {
      cmd: 'git'
      args: [ 'rev-parse', '--abbrev-ref', 'HEAD' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        matches = result.stdout.match /([^\n]+)$/
        grunt.config 'release.branch', matches[1]
      done(err)
      return
    return
  @registerTask 'setrepofullname', 'Set repo full name for use in the release task', ->
    done = @async()
    grunt.util.spawn {
      cmd: 'git'
      args: [ 'config', '--get', 'remote.origin.url' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        matches = result.stdout.match /([^\n]+)$/
        val = matches[1].replace /http(s)?:\/\/github.com\//
        grunt.config 'release.repofullname', val
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
        grunt.config 'release.lasttag', matches[1]
      done(err)
      return
    return
  @registerTask 'setmsg', 'Set gh_release body with commit messages since last release', ->
    done = @async()
    releaserange = grunt.template.process '<%= release.lasttag %>..HEAD'
    grunt.util.spawn {
      cmd: 'git'
      args: ['shortlog', releaserange, '--no-merges']
    }, (err, result, code) ->
      if result.stdout isnt ''
        message = result.stdout.replace /(\n)\s\s+/g, '$1- '
        message = message.replace /\s*\[skip ci\]/g, ''
        grunt.config 'release.msg', message
      done(err)
      return
    return
  @registerTask 'setpost', 'Set post object for use in the release task', ->
    done = @async()

    val =
      tag_name: grunt.config.get('pkg.version')
      target_commitish: grunt.config.get('release.branch')
      name: grunt.config.get('pkg.version')
      body: grunt.config.get('release.msg')
      draft: false
      prerelease: false

    strval = JSON.stringify val

    grunt.config 'release.post', "'" + strval + "'"
    grunt.log.write grunt.config.get 'release.post'

    return
  @registerTask 'seturl', 'Set release url for use in the release task', ->
    done = @async()

    val = 'https://api.github.com/repos/'
    val += grunt.config.get 'release.repo_full_name'
    val += '/releases?access_token=RELEASE_TOKEN'

    grunt.config 'release.url', val

    return
  @registerTask 'gitrelease', 'Create a Github release', ->
    done = @async()
    releaserange = grunt.template.process '<%= lasttag %>..HEAD'
    grunt.util.spawn {
      cmd: 'curl'
      args: ['--data', grunt.config.get 'release.post', '--no-merges', grunt.config.get 'release.url']
    }, (err, result, code) ->
      done(err)
      return
    return

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
