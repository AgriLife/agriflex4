/*
 * LiveWhale Gutenberg Block
 */
(function(blocks, editor, element, components, InspectorControls) {
  var PanelBody, StepperCell, StepperControl, TextControl, ToggleControl, el, getEditorContent, getLiveWhaleURL, registerBlockType;
  el = element.createElement;
  TextControl = components.TextControl;
  ToggleControl = components.ToggleControl;
  PanelBody = components.PanelBody;
  StepperCell = components.StepperCell;
  StepperControl = components.StepperControl;
  getLiveWhaleURL = function(props) {
    var url;
    url = 'https://calendar.tamu.edu/live/json/events';
    if (props.attributes.hasOwnProperty('count') && props.attributes.count !== '') {
      url += '/max/' + encodeURI(props.attributes.count);
    }
    if (props.attributes.hasOwnProperty('group') && props.attributes.group !== '') {
      url += '/group/' + encodeURI(props.attributes.group);
    }
    if (props.attributes.hasOwnProperty('category') && props.attributes.category !== '') {
      url += '/category/' + encodeURI(props.attributes.category);
    }
    if (props.attributes.hasOwnProperty('tag') && props.attributes.tag !== '') {
      url += '/tag/' + encodeURI(props.attributes.tag);
    }
    if (props.attributes.hasOwnProperty('starred') && true === props.attributes.starred) {
      url += '/only_starred/true';
    }
    url += '/hide_repeats/true/';
    return url;
  };
  getEditorContent = function(props) {
    return el('a', {
      href: getLiveWhaleURL(props)
    }, 'LiveWhale Calendar Link');
  };
  registerBlockType = blocks.registerBlockType;
  registerBlockType('agriflex4/livewhale-calendar', {
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
    edit: function(props) {
      var category, content, count, group, sidebar, starred, tag, updateCategoryValue, updateCountValue, updateGroupValue, updateStarredValue, updateTagValue;
      updateGroupValue = function(val) {
        props.setAttributes({
          group: val
        });
      };
      updateCategoryValue = function(val) {
        props.setAttributes({
          category: val
        });
      };
      updateTagValue = function(val) {
        props.setAttributes({
          tag: val
        });
      };
      updateCountValue = function(val) {
        props.setAttributes({
          count: val
        });
      };
      updateStarredValue = function(val) {
        props.setAttributes({
          starred: val
        });
      };
      group = el(TextControl, {
        label: 'Group',
        className: 'control-group',
        value: props.attributes.group,
        key: 'group',
        onChange: updateGroupValue
      });
      category = el(TextControl, {
        label: 'Category',
        className: 'control-category',
        value: props.attributes.category,
        key: 'category',
        onChange: updateCategoryValue
      });
      tag = el(TextControl, {
        label: 'Tag',
        className: 'control-tag',
        value: props.attributes.tag,
        key: 'tag',
        onChange: updateTagValue
      });
      count = el(TextControl, {
        label: 'Events to display',
        className: 'control-count',
        value: props.attributes.count,
        key: 'count',
        onChange: updateCountValue
      });
      starred = el(ToggleControl, {
        label: 'Starred Events Only',
        checked: props.attributes.starred,
        className: 'control-starred',
        value: props.attributes.starred,
        key: 'starred',
        onChange: updateStarredValue
      });
      // Show these controls in the sidebar.
      sidebar = el(InspectorControls, null, el(PanelBody, {
        title: 'LiveWhale settings',
        initialOpen: true
      }, [group, category, tag, count, starred]));
      // Show this content in the block body.
      content = getEditorContent(props);
      return [content, sidebar];
    },
    save: function(props) {
      return getEditorContent(props);
    }
  });
})(window.wp.blocks, window.wp.editor, window.wp.element, window.wp.components, window.wp.blockEditor.InspectorControls);
