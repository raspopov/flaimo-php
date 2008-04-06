<?php
require_once 'class.AtomBase.inc.php';

class Atom_V_030 extends Atom_V_abstract {

	function __construct(AtomBuilder &$atomdata) {
		parent::__construct($atomdata);
	} // end constructor

	protected function getContent(AtomContent &$content, $tagname = '', &$parentnode) {
		$newnode = $this->xml->createElement($tagname);
		if ($content->getContentType() != 'text/plain') {
			$newnode->setAttribute('type', $content->getContentType());
			if ($content->getContentType() != 'multipart/alternative') {
				$newnode->setAttribute('mode', $content->getMode());
				$functionname = ($content->getMode() != 'xml') ? 'createCdataSection' : 'createTextNode';
				$newnode->appendChild($this->xml->$functionname($content->getData()));
			} // end if
		} else {
			$newnode->appendChild($this->xml->createTextNode($content->getData()));
		} // end if
		$parentnode->appendChild($newnode);
	} // end function

	protected function getPerson(AtomPerson &$person, $tagname = '', &$parentnode) {
		$newnode = $this->xml->createElement($tagname);
		$parentnode->appendChild($newnode);
		$personname = $this->xml->createElement('name');
		$personname->appendChild($this->xml->createTextNode($person->getName()));
		$newnode->appendChild($personname);

		if ($person->getEmail() != FALSE) {
			$personmail = $this->xml->createElement('email');
			$personmail->appendChild($this->xml->createTextNode($person->getEmail()));
			$newnode->appendChild($personmail);
		} // end if

		if ($person->getURL() != FALSE) {
			$personurl = $this->xml->createElement('url');
			$personurl->appendChild($this->xml->createTextNode($person->getURL()));
			$newnode->appendChild($personurl);
		} // end if
	} // end function

	protected function getLink(AtomLink &$link, &$parentnode) {
		$newnode = $this->xml->createElement('link');
		$newnode->setAttribute('rel', $link->getRelation());
		$newnode->setAttribute('type', $link->getLinkType());
		$newnode->setAttribute('href', $link->getURL());
		if ($link->getTitle() != FALSE) {
			$newnode->setAttribute('title', $link->getTitle());
		} // end if
		$parentnode->appendChild($newnode);
	} // end function

	protected function generateXML() {
		parent::generateXML();
		$feed = $this->xml->createElement('feed');
		$feed->setAttribute('version', '0.3');
		$feed->setAttribute('xmlns', 'http://purl.org/atom/ns#');

		if ($this->atomdata->getLanguage() != FALSE) {
			$feed->setAttribute('xml:lang', $this->atomdata->getLanguage());
		} // end if

		$this->xml->appendChild($feed);
		$title = $this->xml->createElement('title');
		$title->appendChild($this->xml->createTextNode($this->atomdata->getTitle()));
		$feed->appendChild($title);
		$generator = $this->xml->createElement('generator');
		$generator->appendChild($this->xml->createTextNode(AtomBuilder::GENERATOR_NAME));
		$generator->setAttribute('url', AtomBuilder::GENERATOR_URL);
		$generator->setAttribute('version', AtomBuilder::GENERATOR_VERSION);
		$feed->appendChild($generator);

		if ($this->atomdata->getCopyright() != FALSE) {
			$copyright = $this->xml->createElement('copyright');
			$copyright->appendChild($this->xml->createTextNode($this->atomdata->getCopyright()));
			$feed->appendChild($copyright);
		} // end if

		if ($this->atomdata->getTagline() != FALSE) {
			$tagline = $this->xml->createElement('tagline');
			$tagline->appendChild($this->xml->createTextNode($this->atomdata->getTagline()));
			$feed->appendChild($tagline);
		} // end if

		if ($this->atomdata->getModified() != FALSE) {
			$modified = $this->xml->createElement('modified');
			$modified->appendChild($this->xml->createTextNode(parent::getDateTime($this->atomdata->getModified())));
			$feed->appendChild($modified);
		} // end if

		if ($this->atomdata->getInfo() != FALSE) {
			$this->getContent($this->atomdata->getInfo(), 'info', $feed);
		} // end if

		if ($this->atomdata->getID() != FALSE) {
			$id = $this->xml->createElement('id');
			$id->appendChild($this->xml->createTextNode($this->atomdata->getID()));
			$feed->appendChild($id);
		} // end if

		$this->getPerson($this->atomdata->getAuthor(), 'author', $feed);

		foreach ($this->atomdata->getContributors() as $contributor) {
			$this->getPerson($contributor, 'contributor', $feed);
		} // end foreach

		foreach ($this->atomdata->getLinks() as $link) {
			$this->getLink($link, $feed);
		} // end foreach

		foreach ($this->atomdata->getEntries() as $current_entry) {
			$entry = $this->xml->createElement('entry');
			$entrytitle = $this->xml->createElement('title');
			$entrytitle->appendChild($this->xml->createTextNode($current_entry->getTitle()));
			$entry->appendChild($entrytitle);
			$entryid = $this->xml->createElement('id');
			$entryid->appendChild($this->xml->createTextNode($current_entry->getID()));
			$entry->appendChild($entryid);
			$entryissued = $this->xml->createElement('issued');
			$entryissued->appendChild($this->xml->createTextNode(parent::getDateTime($current_entry->getIssued())));
			$entry->appendChild($entryissued);

			if ($current_entry->getModified() != FALSE) {
				$entrymodified = $this->xml->createElement('modified');
				$entrymodified->appendChild($this->xml->createTextNode(parent::getDateTime($current_entry->getModified())));
				$entry->appendChild($entrymodified);
			} // end if

			if ($current_entry->getCreated() != FALSE) {
				$entrycreated = $this->xml->createElement('created');
				$entrycreated->appendChild($this->xml->createTextNode(parent::getDateTime($current_entry->getCreated())));
				$entry->appendChild($entrycreated);
			} // end if

			if ($current_entry->getAuthor() != FALSE) {
				$this->getPerson($current_entry->getAuthor(), 'author', $entry);
			} // end if

			foreach ($current_entry->getContributors() as $entrycontributor) {
				$this->getPerson($entrycontributor, 'contributor', $entry);
			} // end foreach

			foreach ($current_entry->getLinks() as $entrylink) {
				$this->getLink($entrylink, $entry);
			} // end foreach

			if ($current_entry->getSummary() != FALSE) {
				$this->getContent($current_entry->getSummary(), 'summary', $entry);
			} // end if

			if ($current_entry->getContent() != FALSE) {
				$this->getContent($current_entry->getContent(), 'content', $entry);
			} // end if

			$feed->appendChild($entry);
		} // end foreach
	} // function
} // end class
?>