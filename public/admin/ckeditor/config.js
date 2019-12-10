/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	// Define changes to default configuration here. For example:
	config.language = 'fr';
	// config.uiColor = '#AADC6E';
	//config.extraPlugins = 'filebrowser';
	config.extraPlugins = 'widget,lineutils,slideshow,wpmore,youtube,videodetector,html5audio,wenzgmap,chart,notificationaggregator,notification,embedbase,embed,oembed';
	//config.extraPlugins = 'wpmore'; // Add 'WPMore' plugin - must be in plugins folder
	// Use <br> as break and not enclose text in <p> when pressing <Enter> or <Shift+Enter>
    //config.enterMode = CKEDITOR.ENTER_BR; //For wpmore
    //config.shiftEnterMode = CKEDITOR.ENTER_BR; //For wpmore
    config.fillEmptyBlocks = false;    // Prevent filler nodes in all empty blocks (for wpmore)
	//config.removeButtons = 'Save,,NewPage,Preview,Templates,Print,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Styles,Flash,PageBreak,Iframe,ShowBlocks,About';
	config.toolbar = [
	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ /*'Cut', 'Copy',*/ 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	{ name: 'editing', items: [ 'Find', /*'Replace', 'SelectAll',*/ 'Scayt' ] },
	{ name: 'links', items: [ 'Link', 'Unlink', /*'Anchor'*/ ] },
	{ name: 'insert', items: [ 'Image', 'Slideshow', 'Youtube',/*'Embed',*/ 'oembed', /*'VideoDetector', 'wenzgmap'*/, 'Html5audio'/*, 'Chart', 'Flash'*/, 'Table', 'HorizontalRule', 'Emoticons', 'SpecialChar' ] },
	{ name: 'tools', items: [ 'Maximize' ] },
	{ name: 'others', items: [ 'WPMore' ] },
	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Strike'/*, 'Underline', 'Subscript', 'Superscript', '-', 'RemoveFormat'*/ ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', /*'-', 'Outdent', 'Indent',*/ '-', 'Blockquote', /*'CreateDiv',*/ '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'/*, 'BidiLtr', 'BidiRtl'*/ ] },
	{ name: 'styles', groups: [ 'Styles', 'Format', 'Font', 'FontSize' ], items: [ 'Format', 'Font', /*'FontSize',*/ 'TextColor'/*, 'BGColor'*/ ] },
	{ name: 'about', groups: [ 'About' ] }
];
};
