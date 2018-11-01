module.exports = (grunt) ->
  @initConfig
    pkg: @file.readJSON('package.json')
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
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.nest.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.box.min.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.motion.min.js',
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
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.nest.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.box.js',
          'node_modules/foundation-sites/dist/js/plugins/foundation.util.motion.js',
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
  @registerTask 'phpscan', 'Compare results of vip-scanner with known issues', ->
    done = @async()

    # Ensure strings use the same HTML characters
    unescape_html = (str) ->
      str.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace /&gt;/g, '>'

    # Known issues
    known_issues = grunt.file.readJSON('known-issues.json')
    known_issues_string = JSON.stringify(known_issues)
    known_issues_string = unescape_html(known_issues_string)

    # Current issues
    current_issues = grunt.file.read('vipscan.json')
    start = current_issues.indexOf('[{')
    end = current_issues.lastIndexOf('}]')
    current_issues_string = current_issues.slice(start, end) + '}]'
    current_issues_string = unescape_html(current_issues_string)
    current_issues_json = JSON.parse(current_issues_string)

    # New issues
    new_issues = []
    i = 0
    while i < current_issues_json.length
      issue = current_issues_json[i]
      issue_string = JSON.stringify(issue)
      if known_issues_string.indexOf(issue_string) < 0
        new_issues.push(issue)
      i++

    # Display issues information
    grunt.log.writeln('--- VIP Scanner Results ---')
    grunt.log.writeln(known_issues.length + ' known issues.')
    grunt.log.writeln(current_issues_json.length + ' current issues.')
    grunt.log.writeln(new_issues.length + ' new issues:')
    grunt.log.writeln '------------------'
    i = 0
    while i < new_issues.length
      obj = new_issues[i]

      for key,value of obj
        if value != ''
          if Array.isArray(value)
            value = value.join(' ')
            grunt.log.writeln(key + ': ' + value)
          else if typeof value == 'object'
            grunt.log.writeln(key + ':')
            for key2,value2 of value
              grunt.log.writeln('>> Line ' + key2 + ': ' + value2)
          else
            grunt.log.writeln(key + ': ' + value)

      grunt.log.writeln '------------------'
      i++

    grunt.log.writeln 'All new issues as JSON: '
    grunt.log.writeln JSON.stringify(new_issues)

    return

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
