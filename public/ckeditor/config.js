/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserBrowseUrl = '/ckfinder/ckfinder.html?type=Images';
	config.filebrowserUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.extraPlugins = 'codesnippet';
	config.toolbar_Basic =
		[
    		[ 'Bold', 'Italic', 'Underline', 'SpellChecker', 'Image', 'CodeSnippet' ]
		];
	config.toolbar = 'Basic';
};
