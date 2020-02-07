/**
 * Dynamic Block
 */
 ( function( blocks, editor, element, serverSideRender ) {
    var el = element.createElement,
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
	        source: 'html',
	        selector: 'input'
	        //
	        // type: 'string',
	        // source: 'attribute',
	        // selector: 'input',
	        // attribute: 'value'
		    },
		    category: {
		    	type: 'string',
		    	source: 'html',
		    	selector: 'input'
		    },
				tag: {
					type: 'string',
		    	source: 'html',
		    	selector: 'input',
					default: ''
				},
				count: {
					type: 'number',
		    	source: 'html',
		    	selector: 'input',
					default: 3,
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
		    var group = wp.element.createElement(
		    	wp.components.TextControl,
		    	{
		    		label: 'Group',
		    		value: props.attributes.group,
		    		key: 'group',
		    		onChange: updateGroupValue
		    	}
		    );
		    var category = wp.element.createElement(
		    	wp.components.TextControl,
		    	{
		    		label: 'Category',
		    		value: props.attributes.category,
		    		key: 'category',
		    		onChange: updateCategoryValue
		    	}
		    );
		    var tag = wp.element.createElement(
		    	wp.components.TextControl,
		    	{
		    		label: 'Tag',
		    		value: props.attributes.tag,
		    		key: 'tag',
		    		onChange: updateTagValue
		    	}
		    );
		    var count = wp.element.createElement(
		    	wp.components.TextControl,
		    	{
		    		label: 'Count',
		    		value: props.attributes.count,
		    		key: 'count',
		    		onChange: updateCountValue
		    	}
		    );
		    return wp.element.createElement(
	        'div',
	        null,
	        [ group, category, tag, count ]
		    );
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
	    		'LiveWhale Calendar',
	    		props.attributes.category
	    	);
			},
    } );
}(
  window.wp.blocks,
  window.wp.editor,
  window.wp.element,
  window.wp.serverSideRender
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
