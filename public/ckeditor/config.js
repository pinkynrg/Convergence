/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserBrowseUrl = '/ckfinder/ckfinder.html?type=Images';
	// config.filebrowserImageBrowseUrl = '/kcfinder/browser/?opener=ckeditor&type=images';
	// config.filebrowserFlashBrowseUrl = '/kcfinder/browser/?opener=ckeditor&type=flash';
	config.filebrowserUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	// config.filebrowserImageUploadUrl = '/kcfinder/uploader/?opener=ckeditor&type=images';
	// config.filebrowserFlashUploadUrl = '/kcfinder/uploader/?opener=ckeditor&type=flash';
};
