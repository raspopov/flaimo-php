<?php
# =================================================================== #
#
# iMarc PHP Library
# Copyright 1999, 2002 David Fox, Angryrobot Productions 
#                (See below for full license)
# 
# VERSION: 2.1
# LAST UPDATE: 2002-06-04
# CONTENT: PHP file upload class
#
# =================================================================== #
# 
# USAGE and SETUP instructions at the bottom of this page (README)
# 
# =================================================================== #
/*
	METHODS:
		max_filesize() 		- set a max filesize in bytes
		max_image_size() 	- set max pixel dimenstions for image uploads
		upload() 			- checks if file is acceptable, uploads file to server's temp directory
		save_file() 		- moves the uploaded file and renames it depending on the save_file($overwrite_mode)
		
		cleanup_text_file()	- (private class function) convert Mac and/or PC line breaks to UNIX

	Error codes:
		$errors[0] - "No file was uploaded"
		$errors[1] - "Maximum file size exceeded"
		$errors[2] - "Maximum image size exceeded"
		$errors[3] - "Only specified file type may be uploaded"
		$errors[4] - "File already exists" (save only)
*/
# ------------------------------------------------------------------- #
# UPLOADER CLASS
# ------------------------------------------------------------------- #
class uploader {

	var $file;
	var $errors;
	var $accepted;
	var $max_filesize;
	var $max_image_width;
	var $max_image_height;

	# ----------------------------------- #
	# FUNCTION: 	max_filesize 
	# DESCRIPTION: 	Set the maximum file size in bytes ($size), allowable by the object.
	#
	# ARGS: 		$size			(int) file size in bytes
	#
	# NOTE: PHP's configuration file also can control the maximum upload size, which is set to 2 or 4 
	# megs by default. To upload larger files, you'll have to change the php.ini file first.
	# ----------------------------------- #
	function max_filesize($size){
		$this->max_filesize = $size;
	}

	# ----------------------------------- #
	# FUNCTION: 	max_image_size 
	# DESCRIPTION: 	Sets the maximum pixel dimensions for image uploads
	#
	# ARGS: 		$width 			(int) maximum pixel width of image uploads
	#				$height			(int) maximum pixel height of image uploads
	# ----------------------------------- #
	function max_image_size($width, $height){
		$this->max_image_width  = $width;
		$this->max_image_height = $height;
	}

	# ----------------------------------- #
	# FUNCTION: 	upload 
	# DESCRIPTION: 	Checks if the file is acceptable and copies it to 
	# 
	# ARGS: 		$filename		(string) form field name of uploaded file
	#				$accept_type	(string) acceptable mime-types
	#				$extension		(string) default filename extenstion
	# ----------------------------------- #
	function upload($filename='', $accept_type='', $extention='') {
		if (!$filename || $filename == "none") {
			$this->errors[0] = "No file was uploaded";
			$this->accepted  = FALSE;
			return FALSE;
		}
		
		// Copy PHP's global $_FILES array to a local array
		$this->file = $_FILES[$filename];
		$this->file['file'] = $filename;
		
		// test max size
		if($this->max_filesize && ($this->file["size"] > $this->max_filesize)) {
			$this->errors[1] = "Maximum file size exceeded. File may be no larger than " . $this->max_filesize/1000 . "KB (" . $this->max_filesize . " bytes).";
			$this->accepted  = FALSE;
			return FALSE;
		}
	 	
	 	if(ereg("image", $this->file["type"])) {
	 		
	 		/* IMAGES */
	 		
	 		$image = getimagesize($this->file["tmp_name"]);
	 		$this->file["width"]  = $image[0];
	 		$this->file["height"] = $image[1];
			
			// test max image size
			if(($this->max_image_width || $this->max_image_height) && (($this->file["width"] > $this->max_image_width) || ($this->file["height"] > $this->max_image_height))) {
				$this->errors[2] = "Maximum image size exceeded. Image may be no more than " . $this->max_image_width . " x " . $this->max_image_height . " pixels";
				$this->accepted  = FALSE;
				return FALSE;
			}
			// Image Type is returned from getimagesize() function
	 		switch($image[2]) {
	 			case 1:
	 				$this->file["extention"] = ".gif"; break;
	 			case 2:
	 				$this->file["extention"] = ".jpg"; break;
	 			case 3:
	 				$this->file["extention"] = ".png"; break;
	 			case 4:
	 				$this->file["extention"] = ".swf"; break;
	 			case 5:
	 				$this->file["extention"] = ".psd"; break;
	 			case 6:
	 				$this->file["extention"] = ".bmp"; break;
	 			case 7:
	 				$this->file["extention"] = ".tif"; break;
	 			case 8:
	 				$this->file["extention"] = ".tif"; break;
	 			default:
					$this->file["extention"] = $extention; break;
	 		}
		} elseif(!ereg("(\.)([a-z0-9]{3,5})$", $this->file["name"]) && !$extention) {
			// Try and autmatically figure out the file type
			// For more on mime-types: http://httpd.apache.org/docs/mod/mod_mime_magic.html
			switch($this->file["type"]) {
				case "text/plain":
					$this->file["extention"] = ".txt"; break;
				case "text/richtext":
					$this->file["extention"] = ".txt"; break;
				default:
					break;
			}
	 	} else {
			$this->file["extention"] = $extention;
		}
		
		// check to see if the file is of type specified
		if($accept_type) {
			if(ereg(strtolower($accept_type), strtolower($this->file["type"]))) {
				$this->accepted = TRUE;
			} else { 
				$this->accepted = FALSE;
				$this->errors[3] = "Only " . ereg_replace("\|", " or ", $accept_type) . " files may be uploaded";
			}
		} else { 
			$this->accepted = TRUE;
		}
		return $this->accepted;
	}

	# ----------------------------------- #
	# FUNCTION: 	save_file 
	# DESCRIPTION: 	Cleans up the filename, copies the file from PHP's temp location to $path, 
	#				and checks the overwrite_mode
	#
	# ARGS:			$path			(string) File path to your upload directory
	#				$overwrite_mode	(int) 	1 = overwrite existing file
	#										2 = rename if filename already exists (file.txt becomes file_copy0.txt)
	#										3 = do nothing if a file exists
	# ----------------------------------- #
	function save_file($path, $overwrite_mode="3"){
		$this->path = $path;	
		$n = 0;
    $copy = '';		
		if($this->accepted) {
			// Clean up file name (only lowercase letters, numbers and underscores)
			$this->file["name"] = ereg_replace("[^a-z0-9._]", "", str_replace(" ", "_", str_replace("%20", "_", strtolower($this->file["name"]))));
			
			// Clean up text file breaks
			if(ereg("text", $this->file["type"])) {
				$this->cleanup_text_file($this->file["tmp_name"]);
			}
						
			// get the raw name of the file (without it's extenstion)
			if(ereg("(\.)([a-z0-9]{2,5})$", $this->file["name"])) {
				$pos = strrpos($this->file["name"], ".");
				if(!$this->file["extention"]) { 
					$this->file["extention"] = substr($this->file["name"], $pos, strlen($this->file["name"]));
				}
				$this->file['raw_name'] = substr($this->file["name"], 0, $pos);
			} else {
				$this->file['raw_name'] = $this->file["name"];
				if ($this->file["extention"]) {
					$this->file["name"] = $this->file["name"] . $this->file["extention"];
				}
			}
			
			switch($overwrite_mode) {
				case 1: // overwrite mode
					$aok = copy($this->file["tmp_name"], $this->path . $this->file["name"]);
					break;
				case 2: // create new with incremental extention
					while(file_exists($this->path . $this->file['raw_name'] . $copy . $this->file["extention"])) {
						$copy = "_copy" . $n;
						$n++;
					}
					$this->file["name"]  = $this->file['raw_name'] . $copy . $this->file["extention"];
					$aok = copy($this->file["tmp_name"], $this->path . $this->file["name"]);
					break;
				case 3: // do nothing if exists, highest protection
					if(file_exists($this->path . $this->file["name"])){
						$this->errors[4] = "File &quot" . $this->path . $this->file["name"] . "&quot already exists";
						$aok = null;
					} else {
						$aok = copy($this->file["tmp_name"], $this->path . $this->file["name"]);
					}
					break;
				default:
					break;
			}
			
			if(!$aok) { unset($this->file['tmp_name']); }
			return $aok;
		} else {
			$this->errors[3] = "Only " . ereg_replace("\|", " or ", $accept_type) . " files may be uploaded";
			return FALSE;
		}
	}
	
	# ----------------------------------- #
	# FUNCTION: 	cleanup_text_file 
	# DESCRIPTION: 	Convert Mac and/or PC line breaks to UNIX
	#
	# ARGS: 		$file	(string) Path and name of text file
	# ----------------------------------- #
	function cleanup_text_file($file){
		// chr(13)  = CR (carridge return) = Macintosh
		// chr(10)  = LF (line feed)       = Unix
		// Win line break = CRLF
		$new_file  = '';
		$old_file  = '';
		$fcontents = file($file);
		while (list ($line_num, $line) = each($fcontents)) {
			$old_file .= $line;
			$new_file .= str_replace(chr(13), chr(10), $line);
		}
		if ($old_file != $new_file) {
			// Open the uploaded file, and re-write it with the new changes
			$fp = fopen($file, "w");
			fwrite($fp, $new_file);
			fclose($fp);
		}
	}

}

?>