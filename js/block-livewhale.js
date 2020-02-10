/**
 * Dynamic Block
 */
 ( function( blocks, editor, element, components, serverSideRender, InspectorControls ) {
    var el = element.createElement,
    		TextControl = components.TextControl,
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
	        type: 'string',
	        // source: 'attribute',
	        // attribute: 'value',
	        // selector: '.control-group input'
	        //
	        // type: 'string',
	        // source: 'attribute',
	        // selector: 'input',
	        // attribute: 'value'
		    },
		    category: {
		    	type: 'string',
		    	// source: 'attribute',
	      //   attribute: 'value',
		    	// selector: '.control-category input'
		    },
				tag: {
					type: 'string',
		   //  	source: 'attribute',
	    //     attribute: 'value',
		   //  	selector: '.control-tag input',
					// default: ''
				},
				count: {
					type: 'string',
		   //  	source: 'attribute',
	    //     attribute: 'value',
					default: '3',
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
		    		label: 'Count',
		    		className: 'control-count',
		    		value: props.attributes.count,
		    		key: 'count',
		    		onChange: updateCountValue
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
		    		[ group, category, tag, count ]
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
//  ( function( blocks, editor, element, serverSideRender ) {
//     var el = element.createElement,
//     		registerBlockType = blocks.registerBlockType,
//     		ServerSideRender = serverSideRender;

//     registerBlockType( 'agriflex4/livewhale-calendar', {
//         title: 'LiveWhale Calendar',
//         icon: 'calendar-alt',
//         category: 'embed',
//       	anchor: true,
//       	multiple: false,
//         attributes: {
// 					content: {
// 						type: 'string',
// 						default: 'Editable block content...',
// 					},
// 				},
//         edit: function( props ) {
//         	var content = props.attributes.content;
//         	function onChangeContent( newContent ) {
//         		props.setAttributes( { content: newContent } );
//         	}
//         	var mel = el( ServerSideRender, {
//             block: 'agriflex4/livewhale-calendar',
//             attributes: props.attributes,
//           } );
//           return ( mel );
//         },
//         save: function() {
//         	return null;
//         },
//     } );
// }(
//   window.wp.blocks,
//   window.wp.editor,
//   window.wp.element,
//   window.wp.serverSideRender
// ) );
// ( function( blocks, element, data ) {
//   var el = element.createElement,
//     registerBlockType = blocks.registerBlockType,
//     withSelect = data.withSelect;

//   registerBlockType( 'agriflex4/livewhale-calendar', {
// 		title: __( 'LiveWhale Calendar' ), // Block title. __() function allows for internationalization.
// 		description: __( 'Block showing TAMU events' ), // Block description. __() function allows for internationalization.
//     icon: 'calendar-alt',
//     category: 'embed',
//     attributes: {
// 			group: {
// 				type: 'string',
// 				default: 'College of Agriculture and Life Sciences',
// 			},
// 			category: {
// 				type: 'string',
// 				default: '',
// 			},
// 			tag: {
// 				type: 'string',
// 				default: '',
// 			},
// 			starredonly: {
// 				type: 'boolean',
// 				default: false,
// 			},
// 			count: {
// 				type: 'number',
// 				default: 3,
// 			},
// 			content: {
// 				type: 'string',
// 				default: 'Editable block content...',
// 			},
// 		},
//     edit: function() {
// 	    return wp.element.createElement(
//         'div',
//         null,
//         'Your block.'
// 	    );
// 		},
// 		save: function() {
// 	    return wp.element.createElement(
//         'div',
//         null,
//         'Your block.'
// 	    );
// 		}
// }(
//     window.wp.blocks,
//     window.wp.element,
//     window.wp.data,
// ) );
