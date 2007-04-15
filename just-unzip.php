<?php
/*
Plugin Name: Just Unzip
Plugin URI: http://james.revillini.com/projects/just-unzip/
Description: Just Unzip (LGPL Licensed) takes a zip file you upload from the "Write" page, unzips it, stores the zipped files in your upload folder, and associates the unzipped files with the current post.    Just Unzip makes use of the PclZip php library (LGPL Licensed).
Version: 0.2.2
Author: James Revillini
Author URI: http://james.revillini.com
*/
/*
 * Just Unzip - a Wordpress Plugin to make uploading many files
 * to a single post easy by uploading a zipped file.
 * Copyright (C) 2006-2007  James Revillini
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

$just_unzip_url  = $_SERVER['PHP_SELF'];

class just_unzip {
	/**
	 * unzip
	 * @param string filesystem location of file to be unzipped
	 * @return array returned values described at http://www.phpconcept.net/pclzip/man/en/index.php?understand#returned_values
	 * This function will unzip a file using the GPL PclZip library an returns
	 * an array containing file information arrays which are ready to send to 
	 * just_unzip_attach_to_post.  Unzipped files are stored in the uploads
	 * folder as specified in your Wordpress configuration.
	 *
	 */	
	function unzip($file)  {
		//the basic unzip operation
		require_once('pclzip.lib.php');
		$archive = new PclZip($file);
		if ( 0 == ($files = $archive->extract(PCLZIP_OPT_PATH, dirname($file).'/')) ) {
			die("Error : ".$archive->errorInfo(true));
		}
		return $archive->listContent();
	}
	/**
	 * uploading_iframe_src
	 * @param string the default upload_iframe_src which a url to the default inline-uploading.php
	 * @return string the url to the inline-uploading.php file which comes with Just Unzip
	 * This function will replace the url passed in with a url to the inline-uploading.php which
	 * is contained in the Just Unzip plugin package.  This is the file that gets loaded into
	 * the uploading iframe when you're posting something
	 */  
	function uploading_iframe_src($uploading_iframe_src) {
		$uploading_iframe_src = str_replace(
			'inline-uploading.php',
			'../wp-content/plugins/just-unzip/inline-uploading.php',
			$uploading_iframe_src
		);
		return $uploading_iframe_src;
	}
}

//use filter hook to go to custom inline-uploading.php
add_filter('uploading_iframe_src', array('just_unzip','uploading_iframe_src'));

?>
