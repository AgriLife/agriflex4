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

  $updateStickyTop = (e) ->
    # Change top position of filters when sticky header changes its stuck status.
    $data = $sticky_search.data()
    if window.innerWidth <= 700
      window.requestAnimationFrame ->
        $header_height = Math.ceil(($('.site-header').outerHeight() / 16) * 10) / 10
        $search_filters.css( 'top', $header_height + 'rem' )
    else
      active = stickyIsActive()
      if active
        window.requestAnimationFrame ->
          # Plugin is active and screen is large enough to show the search filters as a sidebar.
          header_height = Math.ceil(($('.site-header').outerHeight() / 16) * 10) / 10
          search_m_top = $sticky_search.css('margin-top')
          $sticky_search.attr('data-options', $sticky_search.attr('data-options').replace(/marginTop:[^;]+/, 'marginTop:' + header_height))
          $sticky_search.data('zfPlugin').options.marginTop = header_height

          if e.namespace is 'stuckto:top.zf' and
          search_m_top isnt header_height and
          parseInt(search_m_top) isnt 0
            console.log search_m_top
            $sticky_search.css('margin-top', header_height + 'em')

  $('.site-header [data-sticky]').on 'sticky.zf.stuckto:top', $updateStickyTop
  $('.site-header [data-sticky]').on 'sticky.zf.unstuckfrom:top', $updateStickyTop

  # Initialize the sticky plugin.
  options = JSON.parse('{' + $sticky_search.data('options').replace(/;/g,',').replace(/,$/,'').replace(/\b([^:,]+)\b/g,'"$1"') + '}')
  $data_margin_top = Math.ceil(($('.site-header').outerHeight() / 16) * 10) / 10
  options['marginTop'] = $data_margin_top

  if window.innerWidth > 700
    new Foundation.Sticky($sticky_search, options)

  # Destroy or create the sticky plugin based on the current viewport width.
  $(window).on 'resize', (e) ->
    $sticky_search = $('[data-post-tile-search] .sticky-target')
    $data = $sticky_search.data()

    if window.innerWidth > 700
      if $sticky_search.parent().hasClass('sticky-container') is false
        $search_filters.css( 'top', '' )
        new Foundation.Sticky($sticky_search, options)
      else
        # Update max-width since it is set by the Sticky plugin
        window.requestAnimationFrame ->
          new_width = $sticky_search.parent().outerWidth()
          if $sticky_search.css('max-width') isnt new_width
            $sticky_search.css('max-width', new_width)
    else
      if stickyIsActive()
        $sticky_search.foundation('_destroy')

  return
) jQuery
