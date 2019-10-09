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
        console.log this.className
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

  # Handle sticky search filters.
  $tiled_search = $('[data-post-tile-search] .sticky-target')

  # Update the top margin offset for the sticky filters based on changing header height.
  $updateMarginTop = (e) ->
    $data = $tiled_search.data()
    $data_margin_top = Math.ceil(($('.site-header').outerHeight() / 16) * 10) / 10

    if $data.hasOwnProperty('zfPlugin') isnt false
      $tiled_search.data('zfPlugin').options.marginTop = $data_margin_top

  $(window).on 'load,resize,scroll', $updateMarginTop

  # Initialize the sticky plugin.
  options = JSON.parse('{' + $tiled_search.data('options').replace(/;/g,',').replace(/,$/,'').replace(/\b([^:,]+)\b/g,'"$1"') + '}')
  $data_margin_top = Math.ceil(($('.site-header').outerHeight() / 16) * 10) / 10
  options['marginTop'] = $data_margin_top

  if window.innerWidth > 700
    new Foundation.Sticky($tiled_search, options)

  # Destroy or create the sticky plugin based on the current viewport width.
  $(window).on 'resize', (e) ->
    $tiled_search = $('[data-post-tile-search] .sticky-target')
    $data = $tiled_search.data()

    if window.innerWidth > 700
      if $tiled_search.parent().hasClass('sticky-container') is false
        new Foundation.Sticky($tiled_search, options)
    else
      if $data.hasOwnProperty('zfPlugin') and $data['zfPlugin'].className is 'Sticky' and $tiled_search.parent().hasClass('sticky-container')
        $tiled_search.foundation('_destroy')

  return
) jQuery
