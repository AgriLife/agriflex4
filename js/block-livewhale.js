/**
 * LiveWhale Gutenberg Block
 */
 ( function( blocks, editor, element, components, serverSideRender, InspectorControls ) {
    var el = element.createElement,
    		TextControl = components.TextControl,
    		ToggleControl = components.ToggleControl,
    		PanelBody = components.PanelBody,
    		StepperCell = components.StepperCell,
    		StepperControl = components.StepperControl,
    		registerBlockType = blocks.registerBlockType,
    		ServerSideRender = serverSideRender;

    registerBlockType( 'agriflex4/livewhale-calendar', {
      title: 'LiveWhale Calendar',
      icon: 'calendar-alt',
      category: 'embed',
    	anchor: true,
    	multiple: false,
      attributes: {
				group: {
	        type: 'string'
		    },
		    category: {
		    	type: 'string'
		    },
				tag: {
					type: 'string'
				},
				count: {
					type: 'string',
					default: '3'
				},
				starred: {
					type: 'boolean'
				},
				content: {
					type: 'string',
					source: 'html',
					selector: 'a.wp-block-agriflex4-livewhale-calendar'
				}
			},
      edit: function( props ) {
		    var updateGroupValue = function( val ) {
	        props.setAttributes( { group: val } );
		    };
		    var updateCategoryValue = function( val ) {
	        props.setAttributes( { category: val } );
		    };
		    var updateTagValue = function( val ) {
	        props.setAttributes( { tag: val } );
		    };
		    var updateCountValue = function( val ) {
	        props.setAttributes( { count: val } );
		    };
		    var updateStarredValue = function( val ) {
	        props.setAttributes( { starred: val } );
		    };
		    var group = el(
		    	TextControl,
		    	{
		    		label: 'Group',
		    		className: 'control-group',
		    		value: props.attributes.group,
		    		key: 'group',
		    		onChange: updateGroupValue
		    	}
		    );
		    var category = el(
		    	TextControl,
		    	{
		    		label: 'Category',
		    		className: 'control-category',
		    		value: props.attributes.category,
		    		key: 'category',
		    		onChange: updateCategoryValue
		    	}
		    );
		    var tag = el(
		    	TextControl,
		    	{
		    		label: 'Tag',
		    		className: 'control-tag',
		    		value: props.attributes.tag,
		    		key: 'tag',
		    		onChange: updateTagValue
		    	}
		    );
		    var count = el(
    			TextControl,
    			{
		    		label: 'Events to display',
		    		className: 'control-count',
		    		value: props.attributes.count,
		    		key: 'count',
		    		onChange: updateCountValue
		    	}
				);
				var starred = el(
    			ToggleControl,
    			{
		    		label: 'Starred Events Only',
		    		checked: props.attributes.starred,
		    		className: 'control-starred',
		    		value: props.attributes.starred,
		    		key: 'starred',
		    		onChange: updateStarredValue
		    	}
				);

				// Show these controls in the sidebar
		    var sidebar = el(
		    	InspectorControls,
		    	null,
		    	el(
		    		PanelBody,
		    		{
		    			title: 'LiveWhale settings',
		    			initialOpen: true
		    		},
		    		[ group, category, tag, count, starred ]
				  )
				);

				// Calendar link in block content.
				var urlparams = 'https://calendar.tamu.edu/live/json/events';
				if ( props.attributes.hasOwnProperty('group') && props.attributes.group !== '' ) {
					urlparams += '/group/' + encodeURI(props.attributes.group);
				}
				if ( props.attributes.hasOwnProperty('category') && props.attributes.category !== '' ) {
					urlparams += '/category/' + encodeURI(props.attributes.category);
				}
				if ( props.attributes.hasOwnProperty('tag') && props.attributes.tag !== '' ) {
					urlparams += '/tag/' + encodeURI(props.attributes.tag);
				}
				if ( props.attributes.hasOwnProperty('starred') && true === props.attributes.starred ) {
					urlparams += '/only_starred/true';
				}
				urlparams += '/hide_repeats/true/';

				var content = el(
	    		'a',
	    		{href: urlparams, "data-count": props.attributes.count},
	    		'LiveWhale Calendar'
				);

		    return [ content, sidebar ];
			},
			save: function( props ) {
				var urlparams = 'https://calendar.tamu.edu/live/json/events';
				if ( props.attributes.hasOwnProperty('group') && props.attributes.group !== '' ) {
					urlparams += '/group/' + encodeURI(props.attributes.group);
				}
				if ( props.attributes.hasOwnProperty('category') && props.attributes.category !== '' ) {
					urlparams += '/category/' + encodeURI(props.attributes.category);
				}
				if ( props.attributes.hasOwnProperty('tag') && props.attributes.tag !== '' ) {
					urlparams += '/tag/' + encodeURI(props.attributes.tag);
				}
				if ( props.attributes.hasOwnProperty('starred') && false === props.attributes.starred ) {
					urlparams += '/starred/true';
				}
				urlparams += '/hide_repeats/true/';


		    return el(
	    		'a',
	    		{href: urlparams, "data-count": props.attributes.count},
	    		'LiveWhale Calendar'
	    	);
			},
    } );
}(
  window.wp.blocks,
  window.wp.editor,
  window.wp.element,
  window.wp.components,
  window.wp.serverSideRender,
  window.wp.blockEditor.InspectorControls
) );
