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
  return
) jQuery
