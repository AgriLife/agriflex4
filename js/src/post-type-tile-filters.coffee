(($) ->
  'use strict'

  # Make taxonomy checkboxes show and hide degrees.
  $update = (e) ->
    $entries = $ '[data-post-search-tiles] .entry'
    $search = $ '[data-post-tile-search]'
    $inputs = $search.find 'input'
    taxonomyList = $search.attr('data-taxonomy-list')
    taxonomyPattern = new RegExp '(' + taxonomyList.replace( /,/g, '|' ) + ')-\\S+', 'g'
    $activeInputs = $inputs.filter ':checked'
    if $activeInputs.length is 0
      # Show all degrees
      $entries.filter ':hidden'
        .fadeIn()
      $inputs.not ':enabled'
        .removeAttr 'disabled'
    else
      # Decide which degrees to show.
      activeInputClasses = []
      $activeInputs.each ( index ) ->
        activeInputClasses.push '.' + this.value
      selected = activeInputClasses.join ''
      $activeItems = $entries.filter selected
      # Show or hide degrees.
      $activeItems.fadeIn()
      $entries.not selected
        .fadeOut()
      # Find which taxonomies are present in active degrees.
      activeTaxonomies = []
      $activeItems.each ->
        matches = this.className.match taxonomyPattern
        j = 0
        while j < matches.length
          if matches[j] not in activeTaxonomies then activeTaxonomies.push '.' + matches[j]
          j++
      activeTaxonomies = activeTaxonomies.join ','
      # Change enabled state of filters.
      $inputs.filter activeTaxonomies
        .not ':enabled'
        .removeAttr 'disabled'
      $inputs.not activeTaxonomies
        .not ':disabled'
        .attr 'disabled', true
  $reset = (e) ->
    e.preventDefault();
    $inputs = $ '[data-post-tile-search] input'
    $activeInputs = $inputs.filter ':checked'
    $activeInputs.each (i) ->
      $(this).prop 'checked', false
    $update()
  $update()
  $search = $ '[data-post-tile-search]'
  $search.find('input').on 'change', $update
  $search.find('[data-post-tile-reset]').on 'click', $reset

  ## Handle sticky search filters.
  ###
    - The top position of the sticky sidebar needs to change because the height of
      the header changes when it is stuck.
    - The search filters are a sidebar on large screens, and a show-hide button on
      small screens which occupies most of the web page below the site header.
  ###

  $search_filters = $ '[data-post-tile-search]'
  $sticky_search = $search_filters.find '.sticky-target'

  # Update the top margin offset for the sticky filters based on changing header height.
  stickyIsActive = (e) ->
    $data = $sticky_search.data()
    if $data.hasOwnProperty('zfPlugin') isnt false and
    $sticky_search.parent().hasClass('sticky-container')
      return true
    else
      return false

  adminHeaderHeight = ->
    if $('body').hasClass('logged-in') and $('.site-header .sticky.is-anchored').length > 0
      admin_height = $('#wpadminbar').outerHeight()
      height = Math.ceil((admin_height / 16) * 10) / 10
      return height
    else return 0

  $updateStickyTop = (e) ->
    # Change top position of filters when sticky header changes its stuck status.
    $data = $sticky_search.data()
    if Foundation.MediaQuery.atLeast('medium2')
      active = stickyIsActive()
      if active
        window.requestAnimationFrame ->
          # Plugin is active and screen is large enough to show the search filters as a sidebar.
          logged_in_offset = adminHeaderHeight()
          header_height = Math.ceil(($('.site-header .sticky').outerHeight() / 16) * 10) / 10 + logged_in_offset
          search_m_top = $sticky_search.css('margin-top')
          $search_filters.css( 'top', '' )
          $sticky_search.attr('data-options', $sticky_search.attr('data-options').replace(/marginTop:[^;]+/, 'marginTop:' + header_height))
          $sticky_search.data('zfPlugin').options.marginTop = header_height
          if ( e.namespace is 'stuckto:top.zf' or e.type is 'resize' ) and
          search_m_top isnt header_height and
          parseInt(search_m_top) isnt 0
            $sticky_search.css('margin-top', header_height + 'em')
    else
      window.requestAnimationFrame ->
        logged_in_offset = adminHeaderHeight()
        $header_height = Math.ceil(($('.site-header .sticky').outerHeight() / 16) * 10) / 10 + logged_in_offset
        $search_filters.css( 'top', $header_height + 'rem' )

  $('.site-header [data-sticky]').on 'sticky.zf.stuckto:top', $updateStickyTop
  $('.site-header [data-sticky]').on 'sticky.zf.unstuckfrom:top', $updateStickyTop

  # Initialize the sticky plugin.
  logged_in_offset = adminHeaderHeight()
  options = JSON.parse('{' + $sticky_search.data('options').replace(/;/g,',').replace(/,$/,'').replace(/\b([^:,]+)\b/g,'"$1"') + '}')
  $data_margin_top = Math.ceil(($('.site-header').outerHeight() / 16) * 10) / 10 + logged_in_offset
  options['marginTop'] = $data_margin_top

  # Manage position and size for the search filters as needed when the window resizes.
  # The following things change when the window resizes:
  # Header height, navigation menu height, sidebar max width
  $(window).on 'resize', (e) ->
    window.requestAnimationFrame ->
      $sticky_search = $('[data-post-tile-search] .sticky-target')
      $data = $sticky_search.data()
      active = stickyIsActive()

      # Update top position of sticky filter container
      $updateStickyTop e

      if Foundation.MediaQuery.atLeast('medium2') and
      active is true
        # Update max-width since it is set by the Sticky plugin
        new_width = $sticky_search.parent().outerWidth()
        if $sticky_search.css('max-width') isnt new_width
          $sticky_search.css('max-width', new_width)

  return
) jQuery
