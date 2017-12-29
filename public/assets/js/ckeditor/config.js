/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.allowedContent = true;
	config.extraAllowedContent = 'div(*)';

	config.autoParagraph = false;
	config.fillEmptyBlocks = false;
	config.tabSpaces = 0;
	config.basicEntities = false;
	
	// config.entities_greek = false; 
	// config.entities_latin = false; 
	// config.entities_additional = '';
};
