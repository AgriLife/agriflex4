###
# LiveWhale Gutenberg Block
###

((blocks, editor, element, components) ->
	el = element.createElement
	TextControl = components.TextControl
	ToggleControl = components.ToggleControl
	PanelBody = components.PanelBody
	StepperCell = components.StepperCell
	StepperControl = components.StepperControl
	InspectorControls = editor.InspectorControls

	getLiveWhaleURL = ( props ) ->

		url = 'https://calendar.tamu.edu/live/json/events'
		if props.attributes.hasOwnProperty('count') and props.attributes.count != ''
			url += '/max/' + encodeURI(props.attributes.count)
		if props.attributes.hasOwnProperty('subscription') and props.attributes.subscription != ''
			url += '/subscription/' + encodeURI(props.attributes.subscription)
		if props.attributes.hasOwnProperty('group') and props.attributes.group != ''
			url += '/group/' + encodeURI(props.attributes.group)
		if props.attributes.hasOwnProperty('category') and props.attributes.category != ''
			url += '/category/' + encodeURI(props.attributes.category)
		if props.attributes.hasOwnProperty('tag') and props.attributes.tag != ''
			url += '/tag/' + encodeURI(props.attributes.tag)
		if props.attributes.hasOwnProperty('starred') and true == props.attributes.starred
			url += '/only_starred/true'
		url += '/hide_repeats/true/'
		return url

	getEditorContent = ( props ) ->
		el 'div', null,
			el 'div', { class: 'livewhale-block grid-x grid-padding-x' }, [
				el 'div', { class: 'event cell auto' },
					el 'div', { class: 'grid-x grid-padding-x' }, [
						el 'div', { class: 'cell date shrink' }, [
							el 'div', { class: 'month h3' }, 'Jan'
							el 'div', { class: 'h2 day' }, '01'
						]
						el 'div', { class: 'cell title auto' }, [
							el 'a', {
								href: getLiveWhaleURL props
								title: 'LiveWhale Feed URL'
								class: 'event-title'
								}, 'Event Title'
							el 'div', { class: 'location' }, 'Location'
						]
					]
				el 'div', { class: 'events-all cell shrink' },
					el 'a', {
						class: 'h3 arrow-right',
						href: getLiveWhaleURL props
							.replace /\/max\/\d+/, ''
					}, 'All Events'
			]

	registerBlockType = blocks.registerBlockType
	registerBlockType 'agriflex4/livewhale-calendar',
		title: 'LiveWhale Calendar'
		icon: 'calendar-alt'
		category: 'embed'
		anchor: true
		multiple: false
		attributes:
			group: type: 'string'
			category: type: 'string'
			tag: type: 'string'
			count:
				type: 'string'
				default: '3'
			starred: type: 'boolean'
			subscription: type: 'string'
			content:
				type: 'string'
				source: 'html'
				selector: '.livewhale-block'
		edit: (props) ->
			updatesubscriptionValue = (val) ->
				props.setAttributes subscription: val
				return
			updateGroupValue = (val) ->
				props.setAttributes group: val
				return
			updateCategoryValue = (val) ->
				props.setAttributes category: val
				return
			updateTagValue = (val) ->
				props.setAttributes tag: val
				return
			updateCountValue = (val) ->
				props.setAttributes count: val
				return
			updateStarredValue = (val) ->
				props.setAttributes starred: val
				return

			subscription = el(TextControl,
				label: 'Calendar'
				className: 'control-subscription'
				value: props.attributes.subscription
				key: 'subscription'
				onChange: updatesubscriptionValue)
			group = el(TextControl,
				label: 'Group'
				className: 'control-group'
				value: props.attributes.group
				key: 'group'
				onChange: updateGroupValue)
			category = el(TextControl,
				label: 'Category'
				className: 'control-category'
				value: props.attributes.category
				key: 'category'
				onChange: updateCategoryValue)
			tag = el(TextControl,
				label: 'Tag'
				className: 'control-tag'
				value: props.attributes.tag
				key: 'tag'
				onChange: updateTagValue)
			count = el(TextControl,
				label: 'Events to display'
				className: 'control-count'
				value: props.attributes.count
				key: 'count'
				onChange: updateCountValue)
			starred = el(ToggleControl,
				label: 'Starred Events Only'
				checked: props.attributes.starred
				className: 'control-starred'
				value: props.attributes.starred
				key: 'starred'
				onChange: updateStarredValue)

			# Show these controls in the sidebar.
			sidebar = el(InspectorControls, null, el(PanelBody, {
				title: 'LiveWhale settings'
				initialOpen: true
			}, [
				subscription
				group
				category
				tag
				count
				starred
			]))

			# Show this content in the block body.
			content = getEditorContent props

			[
				content
				sidebar
			]
		save: (props) ->
			getEditorContent props
	return
) window.wp.blocks, window.wp.editor, window.wp.element, window.wp.components
